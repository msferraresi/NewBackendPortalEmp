<?php

namespace arsatapi\Http\Controllers;

use Illuminate\Http\Request;
use arsatapi\ResponseApi;
use arsatapi\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Hamcrest\Description;
use arsatapi\Password;
use Smalot\PdfParser\Parser;
use Mpdf\Mpdf;
//use Gufy\PdfToHtml\Pdf;
//use Mpdf\Mpdf;
//use mikehaertl\pdftk\Pdf;

class FileController extends Controller
{

    public function upload(Request $request){

        $tipoArchivo = DB::table('type_files')->where('id',$request->tipo)->get()->first();
        $retApi = new ResponseApi();
        $fileName = $tipoArchivo->description.'_'.$request->mes.'_'.$request->year;
        $exists = Storage::disk()->exists("public/$fileName.pdf");
        if(!$exists){
            $file = $request->file('archivo');
            $cript = $file->getClientOriginalName();

            $password = env('PENC');
            $chunk_size = $file->getSize();
            $alg = SODIUM_CRYPTO_PWHASH_ALG_DEFAULT;
            $opslimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE;
            $memlimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE;
            $salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);

            $secret_key = sodium_crypto_pwhash(SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
                                           $password, $salt, $opslimit, $memlimit, $alg);

            $fd_in = fopen($file, 'rb');
            $path = storage_path();
            $fd_out = fopen( $path.'\app\pdf\\'.$fileName.'.pdf', 'wb');

            fwrite($fd_out, pack('C', $alg));
            fwrite($fd_out, pack('P', $opslimit));
            fwrite($fd_out, pack('P', $memlimit));
            fwrite($fd_out, $salt);

            list($stream, $header) = sodium_crypto_secretstream_xchacha20poly1305_init_push($secret_key);

            fwrite($fd_out, $header);

            $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
            do {
                $chunk = fread($fd_in, $chunk_size);
                if (stream_get_meta_data($fd_in)['unread_bytes'] <= 0) {
                    $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL;
                }
                $encrypted_chunk = sodium_crypto_secretstream_xchacha20poly1305_push($stream, $chunk, '', $tag);
                fwrite($fd_out, $encrypted_chunk);
            } while ($tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);

            fclose($fd_out);
            fclose($fd_in);
            $fileToDb = new File();
            $fileToDb->path =  $fileName.'.pdf';
            $fileToDb->month = $request->mes;
            $fileToDb->year = $request->year;
            $fileToDb->id_typeFile = $request->tipo;
            $fileToDb->save();

            return $retApi->setResponse('OK', '200', json_encode($fileName));
            }
            //Ya existe
            return $retApi->setResponse('OK', '200', json_encode($exists));
    }

    public function generarPdf(Request $request){
        //dd($request);
        $recibos = DB::table('files')->get('path');
        // print($request->id);
        // print($request->legajo);
        // print($request->token);

        //dd($recibos);

        foreach($recibos as $rec){
            //Arma el temporal
            $encrypted_file = storage_path( env('EPATH').$rec->path);
            $password = env('PENC');
            $chunk_size = filesize($encrypted_file);
            $fd_in = fopen($encrypted_file, 'rb');
            $arr = explode('.', $rec->path) ;
            $fd_out = fopen(storage_path("app\\temp\\$arr[0].$request->legajo.$arr[1]"), 'w');
            //$fd_out = Storage::get("app\pdf\\$rec->path");

            $alg = unpack('C', fread($fd_in, 1))[1];
            $opslimit = unpack('P', fread($fd_in, 8))[1];
            $memlimit = unpack('P', fread($fd_in, 8))[1];

            $salt = fread($fd_in, SODIUM_CRYPTO_PWHASH_SALTBYTES);

            $header = fread($fd_in, SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_HEADERBYTES);

            $secret_key = sodium_crypto_pwhash(SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES, $password, $salt, $opslimit, $memlimit, $alg);

            $stream = sodium_crypto_secretstream_xchacha20poly1305_init_pull($header, $secret_key);

            $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
            while (stream_get_meta_data($fd_in)['unread_bytes'] > 0 && $tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL) {
                $chunk = fread($fd_in, $chunk_size + SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_ABYTES);
                $res = sodium_crypto_secretstream_xchacha20poly1305_pull($stream, $chunk);
                if ($res === FALSE) {
                    break;
                }
                list($decrypted_chunk, $tag) = $res;
                fwrite($fd_out, $decrypted_chunk);
            }
            $ok = stream_get_meta_data($fd_in)['unread_bytes'] <= 0;

            $auxPath = storage_path("app\\temp\\$rec->path");

            fclose($fd_out);
            fclose($fd_in);

            //Encripta con el pass de usr
            //$auxPath = 'C:\\xampp\\htdocs\\NewBackendPortalEmpleados\\storage\\app\\pdf\\SAC_6_2018NE.pdf';
            $this->generarArchivos($auxPath, $request->legajo, $request->id);


            unlink( storage_path("app\\temp\\$rec->path") );


            if (!$ok) {
                die('Invalid/corrupted input');
            }
        }
        $retApi = new ResponseApi();
        $exists = 'ok';
    }

    private function generarArchivos($filePath, $legajo, $id){
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $pages = $pdf->getPages();

        foreach($pages as $page){
            $array[] = $page->getText();
        };

        $numPage = 0;
        foreach($array as $item){
            $pos = false;

            $pos = stripos($item, $legajo);
            if($pos == true){
                break;
            }
            $numPage++;
        }

        $pdfs = new \TonchikTm\PdfToHtml\Pdf($filePath, [
            'pdftohtml_path' => 'C:/Varios/poppler-0.68.0/bin/pdftohtml.exe',
            'pdfinfo_path' => 'C:/Varios/poppler-0.68.0/bin/pdfinfo.exe'
        ]);
        set_time_limit(300);
        //dd($numPage);
        $contentPage = $pdfs->getHtml()->getPage($numPage);
        dd($contentPage);

        $outFile = storage_path("app\\temp\\SAC$legajo.pdf");

        $mpdf = new Mpdf();

        $mpdf->WriteHTML($contentPage);

        $mpdf->Output($outFile);
        dd($outFile, $legajo, $id);
        //$respuest = $this->encriptFileUser($outFile, $legajo, $id);

       // dd($respuest);
    }

    private function encriptFileUser($pathFile, $legajo, $id){
        $retApi = new ResponseApi();
        $pwdCont = new PasswordController();
        //$pwd =  $pwdCont->getPassword($id);
        $fileName = 'TestingEncriptation';

        $exists = Storage::disk()->exists($pathFile);
        dd($exists);
        if(!$exists){
            $file = $pathFile;// $request->file('archivo');
            //$cript = $file->getClientOriginalName();

            $password = $pwdCont->getPassword($id);
            $chunk_size = 300;//$file->getSize();
            $alg = SODIUM_CRYPTO_PWHASH_ALG_DEFAULT;
            $opslimit = SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE;
            $memlimit = SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE;
            $salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);

            $secret_key = sodium_crypto_pwhash(SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_KEYBYTES,
                                           $password, $salt, $opslimit, $memlimit, $alg);

            $fd_in = fopen($file, 'rb');
            $path = storage_path();
            $fd_out = fopen( $path.'\app\pdf\\'.$legajo.'\\'.$fileName.'.pdf', 'wb');

            fwrite($fd_out, pack('C', $alg));
            fwrite($fd_out, pack('P', $opslimit));
            fwrite($fd_out, pack('P', $memlimit));
            fwrite($fd_out, $salt);

            list($stream, $header) = sodium_crypto_secretstream_xchacha20poly1305_init_push($secret_key);

            fwrite($fd_out, $header);

            $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_MESSAGE;
            do {
                $chunk = fread($fd_in, $chunk_size);
                if (stream_get_meta_data($fd_in)['unread_bytes'] <= 0) {
                    $tag = SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL;
                }
                $encrypted_chunk = sodium_crypto_secretstream_xchacha20poly1305_push($stream, $chunk, '', $tag);
                fwrite($fd_out, $encrypted_chunk);
            } while ($tag !== SODIUM_CRYPTO_SECRETSTREAM_XCHACHA20POLY1305_TAG_FINAL);

            fclose($fd_out);
            fclose($fd_in);
            $fileToDb = new File();
            $fileToDb->path =  $fileName.'.pdf';
            $fileToDb->month = 6;
            $fileToDb->year = 2018;
            $fileToDb->id_typeFile = 3;
            $fileToDb->save();

            return $retApi->setResponse('OK', '200', json_encode($fileName));
            }
            //Ya existe
            return $retApi->setResponse('OK', '200', json_encode($exists));

    }


}
