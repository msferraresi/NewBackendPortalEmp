<?php

namespace arsatapi\Http\Controllers;

use Illuminate\Http\Request;
use arsatapi\ResponseApi;
use Illuminate\Support\Facades\Storage;

class uploadFileController extends Controller
{
    public function upload(Request $request){

        $retApi = new ResponseApi();
        $fileName = $request->tipo.'_'.$request->mes.'_'.$request->year;
        $exists = Storage::disk()->exists("public/$fileName.pdf");
        if(!$exists){
            $request->file('archivo')->storeAs('public',$fileName.'.pdf');
            return $retApi->setResponse('OK', '200', json_encode($fileName));
        }
        //Ya existe
        return $retApi->setResponse('OK', '200', json_encode($exists));
    }
}
