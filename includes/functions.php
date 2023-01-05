<?php

function connectToDB(){
  return new PDO('mysql:host=devkinsta_db;dbname=simple_stores',
  'root',
  'cD4FYhCb9HPk9bc0');
};

function isLoggedIn(){
    //if the user log in return true
    //if the user not log in return false
    return isset( $_SESSION['user']);
};

function logOut(){
    //delete the user session data so the user log out
   unset($_SESSION['user']);
};

function callAPI($api_url='',$method='GET',$formdata=[],$headers=[])
    {
        //init curl
        $curl = curl_init();

        //assign
        $curl_props= [
            CURLOPT_URL=>$api_url,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_FAILONERROR=>true,
            CURLOPT_CUSTOMREQUEST=>$method
        ];



        //if $formdata is not empty, then we'll add in a new key call CURLOPT_POST_FIELDS
        if(!empty($formdata)){
            $curl_props[CURLOPT_POSTFIELDS] = json_encode($formdata);
        }

        //if $headers is not empty, then add in a new key called "CURLOPT_HTTPHEADER"
    if ( !empty( $headers ) ) {
        $curl_props[ CURLOPT_HTTPHEADER ] = $headers;
    }

        //setup curl
        curl_setopt_array($curl,$curl_props);

        //execute cURL
        $response = curl_exec($curl);

        //catch error
        $error = curl_error($curl);

        //close curl
        curl_close($curl);

        if($error)
            return 'API not Working';

        return json_decode($response);
    }