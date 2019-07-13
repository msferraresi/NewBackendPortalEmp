<?php

namespace arsatapi\Http\Controllers;

use arsatapi\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use arsatapi\ResponseApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PasswordController extends Controller
{
    //Verifica si hay contraseñas generadas
    public function PasswordExist($id){
        $exist = Password::where('id_usuario', $id)->first();
        if($exist){
            return 1;
        }
        return -1;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $ipaddres = $request->getClientIps();
        $data = json_decode($request->getContent());
        $tcg = new TokenController();
        $token =  $tcg->validateToken($data->id, $data->token);
        $retApi = new ResponseApi();
        $flag = true;
            if($token == 1){
                //Validación de token y usuario ok
                $pwd = new Password();
                $auxPW = new PasswordController();
                $lst = $auxPW->lstPasswords($data->id);

                if ($lst->count() > 0 && $lst->count() <= 9) {
                    //agarra el primero de la lista y le agrega la fecha de delete
                    Password::where('deleted_at',null)->delete();
                }else if($lst->count() == 10){
                    //eliminacion fisica de la clave mas vieja
                    $result = DB::table('passwords')->selectRaw('min(deleted_at)')->delete();
                }

                //foreach buscando que la clave no exista
                foreach ($lst as $item) {
                    if (Hash::check($data->password,$item['password'])) {
                        $flag = false;
                        break;
                    }
                }

                if ($flag) {
                    $pwd->id_usuario = $data->id;
                    $pwd->password = Hash::make($data->password);
                    $pwd->save();
                    activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('Contraseña generada');
                    $mensaje = "Su contraseña ha sido generada con éxito";
                    return $retApi->setResponse('OK', '200', json_encode($mensaje));
                }else{
                    activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('Intento de registro de clave ya utilizada');
                    $mensaje = "La clave que intenta utilizar se encuentra dentro de las ultimas 10 ya usadas";
                    return $retApi->setResponse('OK', '200', json_encode($mensaje));
                }
            }
            else{
                activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('Error al generar la contraseña de usuario | Credenciales o token expirado');
                $mensaje = 'Ocurrió un error durante la validación o su token ha expirado';
                return $retApi->setResponse('Forbidden', '404', json_encode($mensaje));
            }

    }

    public function changePassword(Request $request){
        $ipaddres = $request->getClientIps();
        $data = json_decode($request->getContent());
        $tcg = new TokenController();
        $token =  $tcg->validateToken($data->id, $data->token);
        $retApi = new ResponseApi();
        $flag = true;
        $index = '';
        if($token == 1){
            $pwd = new Password();
            $auxPW = new PasswordController();
            $lst = $auxPW->lstPasswords($data->id);
            if($data->passOld != $data->passNew){
                foreach ($lst as $item) {
                    if(Hash::check($data->passNew,$item['password'])){
                        $flag = false;
                    }
                }

                foreach ($lst as $item) {
                    if (!Hash::check($data->passOld,$item['password'])) {
                        $index++;
                    }else{
                        break;
                    }
                }

                if($flag && $index != ''){
                    if ($lst->count > 0 && $lst->count <= 9) {
                        //agarra el primero de la lista y le agrega la fecha de delete
                        Password::where('deleted_at',null)
                        ->where('id_usuario', $data->id)
                        ->delete();
                    }else if($lst->count == 10){
                        //eliminacion fisica de la clave mas vieja
                        Password::where('deleted_at',null)
                        ->where('id_usuario', $data->id)
                        ->delete();
                        $result = DB::table('passwords')->selectRaw('min(created_at) as min')->get()->first();

                        DB::table('passwords')->where('created_at', $result->min)->delete();
                    }
                    $pwd->id_usuario = $data->id;
                    $pwd->password = Hash::make($data->passNew);
                    $pwd->save();
                    activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('Su contraseña ha sido modificada con éxito');
                    $mensaje = "Su contraseña ha sido modificada con éxito";
                    return $retApi->setResponse('OK', '200', json_encode($mensaje));
                }else {
                    activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('No se ha podido modificar la contraseña');
                    $mensaje = "No se ha podido modificar la contraseña";
                    return $retApi->setResponse('Forbidden', '403', json_encode($mensaje));
                }
            }else{
                //Mensaje de error pass iguales
                activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('La contraseña nueva y la actual no pueden ser iguales');
                $mensaje = "La contraseña nueva y la actual no pueden ser iguales";
                return $retApi->setResponse('Forbidden', '403', json_encode($mensaje));
            }
        }else{
            //Mensaje error token
            activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('La sesion ha expirado');
            $mensaje = "La sesion ha expirado";
            return $retApi->setResponse('Forbidden', '403', json_encode($mensaje));
        }
    }


    public function resetPassword(Request $request){
        $ipaddres = $request->getClientIps();
        $data = json_decode($request->getContent());
        $tcg = new TokenController();
        //dd($data);
        $token =  $tcg->validateToken($data->id, $data->token);
        if($token==1){
            $retApi = new ResponseApi();
        $pwd = $data->password;
        //$index = 0;
        $auxPW = new PasswordController();
        $old_pass = $auxPW->lstPasswords($data->id);
        foreach($old_pass as $old){
            //chequea que la nueva pass no coincida con las anteriores
            if(Hash::check($pwd,$old['password'])){
                //$index++;
                $mensaje = "La contraseña nueva no puede coincidir con ninguna de las últimas 10 contraseñas utilizadas";
                return $retApi->setResponse('Forbidden', '403', json_encode($mensaje));
            }
            if($old_pass->count() > 0 && $old_pass->count() <= 9){
                Password::where('deleted_at',null)
                ->where('id_usuario', $data->id)
                ->delete();
            }
            if( $old_pass->count() == 10 ){
                Password::where('deleted_at',null)
                ->where('id_usuario', $data->id)
                ->delete();
                $result = DB::table('passwords')
                ->where('id_usuario', $data->id)
                ->selectRaw('min(created_at) as min')->get()->first();

                DB::table('passwords')->where('created_at', $result->min)->delete();
            }
            $n_pwd = new Password();
            $n_pwd->id_usuario = $data->id;
            $n_pwd->password = Hash::make($data->password);
            $n_pwd->estado = false;
            $n_pwd->save();
            activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('Su contraseña ha sido modificada con éxito');
            $mensaje = "Su contraseña ha sido modificada con éxito";
            return $retApi->setResponse('OK', '200', json_encode($mensaje));
        }
        //Es diferente
        return 'ok';
        }else{
            //Mensaje error token
            activity()->causedBy($data->id)->withProperties(['email' => $data->email,'IP'=>$ipaddres])->log('La sesion ha expirado');
            $mensaje = "La sesion ha expirado";
            return $retApi->setResponse('Forbidden', '403', json_encode($mensaje));
        }
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \arsatapi\Password  $password
     * @return \Illuminate\Http\Response
     */
    public function destroy(Password $password)
    {
        //
    }

    public function getPassword($id_user){
        $pwd = DB::table('passwords')->where('id_usuario',$id_user)->get('password')->first();

        return $pwd;
    }

    private function lstPasswords($id_user){
        $lst = Password::withTrashed()->where('id_usuario', $id_user)->orderBy('deleted_at', 'asc')->get();
        return $lst;
    }


}
