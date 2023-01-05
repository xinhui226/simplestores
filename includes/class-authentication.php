<?php

class Authentication{
   public $database;

   public function __construct(){
    //this function will triggle when the class is called
      $this->database = connectToDB();
   }

   public function login( $email='', $password=''){

    if ( 
        empty( $email ) ||  empty( $password )) 
    {
        return 'All fields are required.';
    }

    $statement = $this->database->prepare('SELECT * FROM users WHERE email = :email');
    $statement->execute([
        'email' =>$email,
    ]);

  $user = $statement->fetch();

  if($user){
     
    if(password_verify($password,$user['password']))
    { 
        $_SESSION['user']=['id'=>$user['id'],'email'=>$user['email']];
        
        header('Location: /');
        exit;
    }
    else {return 'invalid email or password !';} 
}
  else {
    return 'invalid email or password';
  }


   }

   public function signup ( $email='',$password='',$confirm_password=''){

   $error ='';

   if(empty($email)||empty($password)||empty($confirm_password)){
      $error = 'All fields are required !';
   }
   
   if(!empty($password) && !empty($confirm_password) && $password != $confirm_password){
      $error = 'Password and Confirm Password should match !';
   }

   if(!empty($error))
   return $error;

    $statement = $this->database->prepare('SELECT * FROM users WHERE email = :email');
    $statement->execute([
        'email' =>$email,
    ]);

  $user = $statement->fetch();

  if($user){
    return 'Email is already exist !';
  }else{
    $statement = $this->database->prepare(
      "INSERT INTO users (email,password)
      VALUES (:email, :password)"
    );
    $statement->execute([
        'email' => $email,
        'password' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    header('Location: /login');
    exit;
  }

   }

};