<?php 

// var_dump($_SERVER['REQUEST_URI']);

 // get route from the global variable $_SERVER
$path=$_SERVER['REQUEST_URI'];

//remove slash in front and the end
$path = trim ($path,'/');
//remove the string start with "?"
$path = parse_url($path, PHP_URL_PATH);

switch ($path){
  case 'login':
    require "pages/login.php";
  break;
  case 'signup':
    require "pages/signup.php";
  break;
  case 'logout':
    require "pages/logout.php";
  break;
  case 'cart' :
    require "pages/cart.php";
  break;
  case 'orders':
    require "pages/orders.php";
  break;    
  case 'checkout' :
    require "pages/checkout.php";
    break;
  case 'payment-verification' :
    require "pages/payment-verification.php";
    break;
  
  default:
    require "pages/home.php";
    break;
}

