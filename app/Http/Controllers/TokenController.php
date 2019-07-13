<?php

namespace arsatapi\Http\Controllers;

use arsatapi\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function saveToken($pId){

        $token = new Token();
        $token->id_usuario = $pId;
        $token->token = $this->genToken();
        $token->save();
        return $token->token;
    }

    public function destroyToken($pToken){
        $token = new Token();
        $token->token = $pToken;
        $result = DB::table('tokens')->where('token', 'like', "%$pToken%")->delete();
        if ($result == 1) {
            $result = 'OK';
        }else{
            $result = 'Error';
        }
        return  $result;
    }

    public function validateToken($pId, $pToken){
        $result = DB::table('tokens')->where('id_usuario',$pId)->first();
        //Compara usuario y token || Si coinciden devuelve 1 || Sino devuelve -1
        if($result->token == $pToken){
            return 1;
        }
        return -1;
    }

    private function genToken(){
        return Str::random(60);
    }

}
