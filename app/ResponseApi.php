<?php

namespace arsatapi;

use Illuminate\Database\Eloquent\Model;

class ResponseApi extends Model
{
    protected $code = ['200' => 'OK', '401' =>'Unauthorized', '403' =>'Forbidden', '404' => 'Not Found', '405'=> 'Method not allowed', '422'=> 'Unprocessable entity', '500' => 'Server error','201'=> 'Created','202'=>'Accepted','203'=>'Non-authoritative Information','423'=>'Locked','503'=>'Service Unavailable'];

    public function setResponse($s,$c,$de,$da = null){
        $response = new \Illuminate\Http\Response;
        $response->header('Content-Type', 'application/json');
        $response->setStatusCode($c,$this->code[$c]);
        if(empty($da))
            $response->setContent($de);
        else
            $response->setContent($da);

        return $response;
    }

}
