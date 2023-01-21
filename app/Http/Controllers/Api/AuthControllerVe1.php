<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Config;

class AuthControllerVe1 extends Controller
{
    public $successStatus = 200;   
    public function apicall(Request $request,$var1,$var2="")   
    { 
		       
        try {
            $Baseurl = Config::get('constants.appurlVe1');
            $header = $request->header('Authorization');
            $url=$Baseurl."/".$var1;
            if($var2!="")
            { 
                $url=$url."/".$var2;
            } 
           
            $header = array(
                'Authorization: '.$request->header('Authorization'),
            );
            $input=$request->all();
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
            $result = curl_exec($ch); 
            curl_close($ch);
            return $result;
       } catch (\Throwable $th) { 
            $arrayerror = ["msg" => "Please Logout And Try Again"];
            return response()->json(
                ["status" => false, "message" => $arrayerror],
                $this->successStatus
            );
        }
    }
    function build_post_fields( $data,$existingKeys='',&$returnArray=[]){
        if(($data instanceof \CURLFile) or !(is_array($data) or is_object($data))){
            $returnArray[$existingKeys]=$data;
            return $returnArray;
        }
        else{
            foreach ($data as $key => $item) {
                $this->build_post_fields($item,$existingKeys?$existingKeys."[$key]":$key,$returnArray);
            }
            return $returnArray;
        }
    }
}
