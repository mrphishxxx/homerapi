<?php

function __login($email, $pswd, $type, $dtoken){
  $user = User::where('email', 'like', $email)->where('password', md5($pswd . $email))->first();
  if ($user != NULL){
    Login::where('user_id', $user->id)->delete();
    $login = new Login;
    $login->push_type = $type;
    $login->push_token = $dtoken;
    $login->token = md5($email . date('Y-m-d'));
    $user->logins()->save($login);
  }
  return $user;
}

function __login_facebook($full_name, $email, $type, $dtoken, $facebook_id){
  $user = User::where('email', 'like', $email)->first();
  if ($user == NULL){
    __register($full_name, $email, NULL, $facebook_id);
  } else{
    if ($user->facebook_id != $facebook_id){
      $user->facebook_id = $facebook_id;
      $user->save();

      Login::where('user_id', $user->id)->delete();
      $login = new Login;
      $login->push_type = $type;
      $login->push_token = $dtoken;
      $login->token = md5($email . date('Y-m-d'));
      $user->logins()->save($login);
    }
  }
  return $user;
}

function __login_google($full_name, $email, $type, $dtoken, $google_id){
  $user = User::where('email', 'like', $email)->first();
  if ($user == NULL){
    __register($full_name, $email, NULL, NULL, $google_id);
  } else{
    if ($user->google_id != $google_id){
      $user->google_id = $google_id;
      $user->save();

      Login::where('user_id', $user->id)->delete();
      $login = new Login;
      $login->push_type = $type;
      $login->push_token = $dtoken;
      $login->token = md5($email . date('Y-m-d'));
      $user->logins()->save($login);
    }
  }
  return $user;
}

function __register($full_name, $email, $pswd, $facebook=NULL, $google=NULL){
  $user = User::where('email', 'like', $email)->first();
  if ($user == NULL){
    $user = new User;
    $user->full_name = $full_name;
    $user->email = $email;
    if ($pswd != NULL){
      $user->password = md5($pswd . $email);
    }
    $user->avatar = 'images/avatars/default.png';
    $user->facebook_id = $facebook;
    $user->google_id = $google;
    $user->email_verified = 0;
    $user->phone_verified = 0;
    $user->creci_verified = 0;

    $token = qbGenerateSession();
    $user->quickblox_id = qbSignupUser($token, '', $email, QB_USER_PASS)->id;
    
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

function __add_post($uid, $post_type, $property_type, $location, $num_rooms, $area, $price, $description){
  $post = new Post;
  $user = User::find($uid);

  $post->post_type = $post_type;
  $post->property_type = $property_type;
  $post->location = $location;
  $post->num_rooms = $num_rooms;
  $post->area = $area;
  $post->price = $price;
  $post->description = $description;
  $r = getCoordinate($location);
  $post->lat = $r->results[0]->geometry->location->lat;
  $post->lng = $r->results[0]->geometry->location->lng;
  $user->posts()->save($post);

  __process_post($post); // processes similar matches and finding matches

  return $post;
}

function __edit_post($uid, $post_id, $post_type, $property_type, $location, $num_rooms, $area, $price, $description){
  $post = Post::find($post_id);
  $user = User::find($uid);

  $user->viewedPosts()->detach($post->id);

  $post->post_type = $post_type;
  $post->property_type = $property_type;
  $post->num_rooms = $num_rooms;
  $post->area = $area;
  $post->price = $price;
  $post->description = $description;
  if ($post->location != $location){
    $post->location = $location;
    $r = getCoordinate($location);
    $post->lat = $r->results[0]->geometry->location->lat;
    $post->lng = $r->results[0]->geometry->location->lng;
  }
  $user->posts()->save($post);

  __process_post($post); // processes similar matches and finding matches

  return $post;
}

function __rate_user($fromid, $toid, $score){
  $rating = new UserRating;
  $rating->user_from = $fromid;
  $rating->user_to = $toid;
  $rating->score = $score;
  $rating->save();
}



function __get_user_from_token($token){
  $login = Login::where('token', $token)->first();
  if ($login == NULL)
    return NULL;
  return $login->user;
}

function __process_post($post){

  SimilarPost::where('post_from', $post->id)->delete();
  MatchingPost::where('post_from', $post->id)->delete();

  // finding similar matches

  $areaRange = $post->area / 5;
  $priceRange = $post->price / 5;
  $ptype = $post->post_type == 1 ? 2 : 1;
  $similars = Post::where('property_type', $post->property_type)
                  ->whereRaw('abs(' . $post->area . ' - area) <= ' . $areaRange)
                  ->whereRaw('abs(' . $post->price . ' - price) <= ' . $priceRange)
                  ->where('num_rooms', '>=', $post->num_rooms)
                  ->where('post_type', $post->post_type)
                  ->where('id', '<>', $post->id)
                  ->get();

  foreach ($similars as $s){
    $post->similarTo()->attach($s->id);
  }

  // finding matchings
  $matchings = Post::where('property_type', $post->property_type)
                  ->whereRaw('abs(' . $post->area . ' - area) <= ' . $areaRange)
                  ->whereRaw('abs(' . $post->price . ' - price) <= ' . $priceRange)
                  ->where('num_rooms', '>=', $post->num_rooms)
                  ->where('post_type', $ptype)
                  ->where('id', '<>', $post->id)
                  ->get();
  
  $devices = array();
  foreach ($matchings as $m){
    $post->matchedPosts()->attach($m->id);
    $user = $post->user;
    foreach ($user->logins as $login){
      if ($login->push_type == 2){
        $devices[] = $login->push_token;
      }
    }
  }
  $message = $post->user->full_name . ' has just posted that matches your post';
  sendGcmMessage($message, $devices);
}

function __view_post($user_id, $post_id){
  $user = User::find($user_id);
  $user->viewedPosts()->detach($post_id);
  $user->viewedPosts()->attach($post_id);
}

function __reserve_verification($user_id, $verification_type){
  $verification = Verification::where('user_id', $user_id)->where('verification_type', $verification_type)->first();
  if ($verification == NULL){
    $verification = new Verification;
    $verification->user_id = $user_id;
    $verification->verification_type = $verification_type;
  }
  $verification->verification_code = substr(md5($user_id . date('Y-m-d H:i:s')), 0, 5);
  $verification->save();
  if ($verification_type == 'phone'){

  } else if ($verification_type == 'email') {
    $user = User::find($user_id);
    $content = 'Use this code <b>' . $verification->verification_code . '</b> for verification.';
    sendEmail($user->email, 'User Email Verification', $content);
  }
}

function __verify_user($user_id, $verification_type, $verification_code){
  $verification = Verification::where('user_id', $user_id)->where('verification_type', $verification_type)->first();
  $user = User::find($user_id);

  if ($verification == NULL){
    return -2;                      // no such verification was requested.
  }
  if ($verification->verification_code != $verification_code){
    return 0;                       // wrong code
  }
  $ptime = strtotime($verification->updated_at);
  $etime = time() - $ptime;
  if ($etime > 300){
    return -1;                      // 5 minutes passed. this code is no need.
  }

  $verification->verification_code = substr(md5($user_id . date('Y-m-d H:i:s')), 0, 5); // change code randomly.
  $verification->save();

  if ($verification_type == 'phone'){
    $user->phone_verified = 1;
  } else if ($verification_type == 'email'){
    $user->email_verified = 1;
  }
  $user->save();
  return 1;                         // correct.
}

?>