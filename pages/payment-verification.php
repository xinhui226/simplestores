<?php

require "config.php";
require "includes/functions.php";
require "includes/class-orders.php";

// make sure the $_GET['billplz'] is available
if(isset($_GET['billplz'])){
    $string = 'billplzid'.$_GET['billplz']['id'].'|billplzpaid_at'.$_GET['billplz']['paid_at'].'|billplzpaid'.$_GET['billplz']['paid'];

    $signature = hash_hmac('sha256', $string, BILLPLZ_X_SIGNATURE);

    //verify the signature
    if($signature == $_GET['billplz']['x_signature']){
        
        //get order status
        $status = $_GET['billplz']['paid'] == 'true' ?  'completed' : 'failed';

        //update order status
        $orders = new Orders();
        $orders->updateOrder(
            $_GET['billplz']['id'],
            $status
        );

        //redirect user back to the orders page
        header('Location: /orders');
        exit;

    }else{
        echo 'Invalid signature';
    }

}else{
    echo "No billplz data found";
}