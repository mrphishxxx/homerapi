<?php

function __login($email, $pswd, $type, $dtoken){
  $user = User::where('email', 'like', $email)->where('password', md5($pswd . $email))->first();
  if ($user != NULL){
    $login = new Login;
    $login->push_type = $type;
    $login->push_token = $dtoken;
    $login->token = md5($email . date('Y-m-d'));
    $user->logins()->save($login);
  }
  return $user;
}

function __register($full_name, $email, $pswd){
  $user = User::where('email', 'like', $email)->first();
  if ($user == NULL){
    $user = new User;
    $user->full_name = $full_name;
    $user->email = $email;
    $user->password = md5($pswd . $email);
    $user->avatar = '/images/avatars/default.png';
    $user->save();
    
    return true;
  } else{
    return false;
  }
}

function __reserve_reset($email){
  $user = User::where('email', 'like', $email)->first();
  $result = array();
  if ($user == NULL){
    return false;
  } else{
    $user->password_reset = substr(md5($email . date('Y-m-d')), 0, 4);
    $user->save();
    $msg = "We've received your request for password reset. <br/> Please remember the code below and use it in your app for password reset verification.<br> Code : " . $user->password_reset;
    
    sendEmail($user->email, 'Password Reset', $msg);
    return false;
  }
}

function __reset_password($email, $code, $newpass){
  if ($code != 'NONE'){
    $user = User::where('email', 'like', $email)->where('password_reset', $code)->first();
    $result = array();
    if ($user == NULL){
      return false;
    } else{
      $user->password = md5($newpass . $email);
      $user->password_reset = 'NONE';
      $user->save();
      return true;
    }
  }
  return false;
}
?>