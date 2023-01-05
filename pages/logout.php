<?php

session_start();

require "includes/functions.php";

if( isLoggedIn()){
   logout();
   header('Location: /login');
   exit;
}else
{
    header('Location:/login');
    exit;
}