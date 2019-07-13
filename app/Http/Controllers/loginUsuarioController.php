<?php

namespace arsatapi\Http\Controllers;

use arsatapi\Http\Controllers\UserLDAP_Controller;
use arsatapi\User;
use Illuminate\Http\Request;
use Auth;

use \Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Monolog\Handler\FingersCrossed\ActivationStrategyInterface;
use arsatapi\ResponseApi;
use arsatapi\Token;

class loginUsuarioController extends Controller
{


    public function loginAttempt(Request $request){
        $ipaddres = $request->getClientIps();
        $callFrom = substr($request->path(),0,4);
        $returnParams = array();
        $status = '';
        $code = '';

        $credenciales = $this->validate(request(),[
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $email = bcrypt($credenciales['email']);
        $passw = bcrypt($credenciales['password']);
        if (Hash::check($credenciales['email'],env('NEDUA')) && Hash::check($credenciales['password'],env('PEDUA'))) {
            $usr = User::where('email', 'like',$credenciales['email'])->first();
            session()->push('usr', $usr);
            Auth::login($usr);
            if ($callFrom == "api/") {
                $status = 'OK';
                $code = '200';
                $tcg = new TokenController();
                $token =  $tcg->saveToken($usr->id);
                array_push($returnParams, $usr);
                array_push($returnParams, array('token'=>$token));
                array_push($returnParams, array('pwExist'=>'-1'));
                $permi = $usr->getAllPermissions();
                            $permisos = [];
                            foreach($permi as $p){
                                array_push($permisos, $p->name);
                            }
                            array_push($returnParams, array($permisos));
                $returnParams =json_encode($returnParams);
            }else{
                $returnParams = view('inicio', compact('usr'));
            }
            activity()->causedBy($usr->id)->withProperties(['User'=>$usr, 'IP'=>$ipaddres])->log('Login administrador');
        }else {
            $usrLdap = new UserLDAP_Controller();
            $usrLdap = $usrLdap->validar( $credenciales['email'] , $credenciales['password']);
            if($usrLdap){
                $data = [
                    'EmpleadoId' => $usrLdap['employeeid'],
                    'LastName' =>  $usrLdap['sn'],
                    'Name' => $usrLdap['givenname'],
                    'Email' => $usrLdap['mail'],
                    'Position' => $usrLdap['description'],
                ];
                    $usr = User::withTrashed()->where('email', 'like',$data['Email'])->first();
                    session()->forget('usr');
                    if ( !is_null($usr) && $usr->deleted_at == NULL ) {
                        session()->push('usr', $usr);
                        Auth::login($usr);
                        if ($callFrom == "api/") {
                            $status = 'OK';
                            $code = '200';
                            $pwc = new PasswordController();
                            $exist = $pwc->PasswordExist($usr->id);
                            //array_push($usr, array('pwExist'=>$exist));

                            $tcg = new TokenController();
                            $token =  $tcg->saveToken($usr->id);
                            array_push($returnParams, $usr);
                            array_push($returnParams, array('token'=>$token));
                            array_push($returnParams, array('pwExist'=>$exist));
                            $permi = $usr->getAllPermissions();
                            $permisos = [];
                            foreach($permi as $p){
                                array_push($permisos, $p->name);
                            }
                            array_push($returnParams, array($permisos));
                            $returnParams = json_encode($returnParams);
                        }else{
                            $returnParams = view('inicio', compact('usr'));
                        }
                        activity()->causedBy($usr->id)->withProperties(['User'=>$usr, 'IP'=>$ipaddres])->log('Login usuario');
                    }elseif (!is_null($usr) && $usr->deleted_at != NULL) {
                        $error = ['credenciales','Su usuario ha sido bloqueado. Comuníquese con su superior.'];
                        if ($callFrom == "api/") {
                            $status = 'Unauthorized';
                            $code = '401';
                            $returnParams =json_encode($error);
                        }else{
                            $returnParams = $error;
                        }
                        activity()->causedBy($usr->id)->withProperties(['email' => $usr->email, 'IP'=>$ipaddres])->log('Usuario bloqueado');
                    }
                    else{
                        $newU = new User();
                        $nombre = $data['Name'];
                        $apellido = $data['LastName'];
                        $correo = $data['Email'];
                        $idempleado = $data['EmpleadoId'];
                        $posicion = $data['Position'];

                        $newU->name = $nombre[0];
                        $newU->lastname = $apellido[0];
                        $newU->email = $correo[0];
                        $newU->employeeid = $idempleado[0];
                        $newU->position = $posicion[0];
                        $newU->save();
                        $usr = User::query()->where('email', 'like',$data['Email'])->first();

                        $tcg = new TokenController();
                        $token =  $tcg->saveToken($usr->id);


                        session()->push('usr', $usr);
                        $mensaje = 'Ha sido de alta satisfactoriamente, por favor contacte con su superior para el otorgamiento de accesos.';
                        Auth::login($usr);
                        $array = [$usr, $mensaje, $token];

                        if ($callFrom == "api/") {
                            $status = 'Created';
                            $code = '201';
                            array_push($returnParams, $usr);
                            array_push($returnParams, array('token'=>$token));
                            array_push($returnParams, array('pwExist'=>'-1'));
                            $permi = $usr->getAllPermissions();
                            $permisos = [];
                            foreach($permi as $p){
                                array_push($permisos, $p->name);
                            }
                            array_push($returnParams, array($permisos));
                            $returnParams =json_encode($returnParams);
                        }else{
                            $returnParams = view('inicio', compact('usr','mensaje'));;
                        }
                        activity()->causedBy($usr->id)->withProperties(['email' => $usr->email, 'IP'=>$ipaddres])->log('Usuario creado');
                    }
            }else{
                $error = ['credenciales','Las credenciales ingresadas son incorrectas.'];
                if ($callFrom == "api/") {
                    $status = 'Forbidden';
                    $code = '403';
                    $returnParams =json_encode($error);
                }else{
                    $returnParams = $error;
                }
                activity()->withProperties(['email' => $credenciales['email'], 'IP'=>$ipaddres])->log('Credenciales incorrectas');
            }
        }

        if ($callFrom == "api/") {
            $retApi = new ResponseApi();
            return $retApi->setResponse($status, $code, $returnParams);
        }else{
            return $returnParams;
        }

    }

    public function logout(Request $request){
        $ipaddres = $request->getClientIps();
        $callFrom = substr($request->path(),0,4);
        $id = $request->id;
        Auth::logout();
        session()->forget('usr');
        if ($callFrom == "api/") {
            $tc = new TokenController();
            $data = $request->getContent();
            $data = json_decode($data);
            $ret = $tc->destroyToken($data->token);
            if ($ret == 'OK') {
                $mensaje = 'Se deslogueo con exito.';
                $retApi = new ResponseApi();
                activity()->causedBy($id)->withProperties([$request,'IP'=>$ipaddres])->log($mensaje);
                return $retApi->setResponse('OK', '200', json_encode($mensaje));
            }else{
                $mensaje = 'Error en el deslogueo de usuario.';
                $retApi = new ResponseApi();
                activity()->causedBy($id)->withProperties(['Status' => 'Service Unavailable','IP'=>$ipaddres])->log($mensaje);
                return $retApi->setResponse('Service Unavailable', '503', json_encode($mensaje));
            }
        }else{
            return redirect('/');
        }
    }



}
