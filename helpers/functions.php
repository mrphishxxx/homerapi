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
    $user->quickblox_id = qbSignupUser($token, '', $email, $full_name, QB_USER_PASS)->id;

    if ($user->quickblox_id != NULL){
      $user->save();
      return true;
    } else{
      return false;
    }
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
    return true;
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

  if (count($r->results) > 0){
    $post->lat = $r->results[0]->geometry->location->lat;
    $post->lng = $r->results[0]->geometry->location->lng;
  } else {
    $post->lat = NULL;
    $post->lng = NULL;
  }
  $user->posts()->save($post);

  __process_post($post); // processes similar matches and finding matches

  return $post;
}

function __edit_post($uid, $post_id, $post_type, $property_type, $location, $num_rooms, $area, $price, $description){
  $post = Post::find($post_id);
  $user = User::find($uid);

  $user->viewedMatches()->detach($post->id);

  if ($post->post_type != $post_type){
    SimilarPost::where('post_from', $post->id)->delete();       // if post type had been changed, similar / matching relationship would have been broken.
    MatchingPost::where('post_from', $post->id)->delete();
  }
  
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

function __rate_user($fromid, $toid, $score, $comment){
  $rating = new UserRating;
  $rating->user_from = $fromid;
  $rating->user_to = $toid;
  $rating->score = $score;
  $rating->comment = $comment;
  $rating->save();
}



function __get_user_from_token($token){
  $login = Login::where('token', $token)->first();
  if ($login == NULL)
    return NULL;
  $login->updated_at = date('Y-m-d');
  $login->save();
  return $login->user;
}

function __process_post($post){

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
                  ->where('user_id', '<>', $post->user_id)   // i won't regard mine as matching post
                  ->get();

  foreach ($similars as $s){
    $dist = distance($post->lat, $post->lng, $s->lat, $s->lng, 'K'); 
    
    $sp = new SimilarPost;
    $sp->post_from = $post->id;
    $sp->post_to = $s->id;
    $sp->dist = $dist;
    $sp->state = 0;
    $sp->save();
    
    if ($post->num_rooms == $s->num_rooms){
      SimilarPost::where('post_to', $post->id)->where('post_from', $s->id)->delete();
      
      $ssp = new SimilarPost;
      $ssp->post_to = $post->id;
      $ssp->post_from = $s->id;
      $ssp->dist = $dist;
      $ssp->state = 0;
      $ssp->save();
      
    }
  }

  // finding matchings
  $matchings = Post::where('property_type', $post->property_type)
                  ->whereRaw('abs(' . $post->area . ' - area) <= ' . $areaRange)
                  ->whereRaw('abs(' . $post->price . ' - price) <= ' . $priceRange)
                  ->where('num_rooms', '>=', $post->num_rooms)
                  ->where('post_type', $ptype)
                  ->where('id', '<>', $post->id)
                  ->where('user_id', '<>', $post->user_id)   // i won't regard mine as matching post
                  ->get();
  
  $devices = array();
  foreach ($matchings as $m){
    $dist = distance($post->lat, $post->lng, $m->lat, $m->lng, 'K'); 
    $match = MatchingPost::where('post_from', $post->id)->where('post_to', $m->id)->first();
     
    if ($match == NULL){
        $match = new MatchingPost;
        $match->post_from = $post->id;
        $match->post_to = $m->id;
        $match->dist = $dist;
        $match->state = 0;
        $match->save();
    } else{ 
        $nmatch = new MatchingPost();
        $nmatch->post_from = $post->id;
        $nmatch->post_to = $m->id;
        $nmatch->dist = $dist;
        $nmatch->state = $match->state;
        $nmatch->save();
        $match->delete();
        $match = $nmatch;
    }
    
    if ($post->num_rooms == $m->num_rooms){
      $amatch = MatchingPost::where('post_from', $m->id)->where('post_to', $post->id)->first();
      if ($amatch == NULL){
        $amatch = new MatchingPost;
        $amatch->post_from = $m->id;
        $amatch->post_to = $post->id;
        $amatch->dist = $dist;
        $amatch->state = 0;
        $amatch->save();
      } else{
        $anmatch = new MatchingPost;
        $anmatch->post_from = $m->id;
        $anmatch->post_to = $post->id;
        $anmatch->dist = $dist;
        $anmatch->state = $amatch->state;
        $anmatch->save();
        $amatch->delete();
      }
    }
    
    if ($match->state == 0 && $dist < 5){ // send notification to post owners within 5 km (distance between post and post)
        $user = $m->user;
        unset($devices);
        $devices = array();
        foreach ($user->logins as $login){
            if ($login->push_type == 2){
                if ($login->push_token == NULL || strlen($login->push_token) < 10){
                continue;
                }
                $devices[] = $login->push_token;
            }
        }
        $match->state = 1;
        $match->save();

        if (count($devices) == 0){
          break;
        }
        $message = array(
          'message' => $post->user->full_name . ' has just posted that matches your post',
          'post_id' => $match->post_to,
          'post_from' => $match->post_from,
          );
        sendGCMMessage($devices, $message);
    }
  }
}

function __view_match_post($user_id, $mpost_id){
  $user = User::find($user_id);
  $user->viewedMatches()->detach($mpost_id);
  $user->viewedMatches()->attach($mpost_id);
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
    $sid = "SKe44ed0bc0d4fe3aa1522d2a44a7af704"; // Your Account SID from www.twilio.com/user/account
    $token = "bga6bpnuSSWb1HNLq1yvonTowBqZaqwK"; // Your Auth Token from www.twilio.com/user/account

    $content = 'Use code ' . $verification->verification_code . ' for verification.';
    $user = User::find($user_id);

    $account_sid = 'AC9937d29c6659b015a408cf849e5aa611'; 
    $auth_token = '5ecc8903da4ad3913e380025f75a82b3'; 
    $client = new Services_Twilio($account_sid, $auth_token); 
   
    $client->account->messages->create(array( 
      'To' => $user->phone, 
      'From' => "+18559729840", 
      'Body' => $content,
    ));
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

function __get_auth_admin($token){
  $admin = Admin::where('remember_token', $token)->first();
  return $admin;
}

?>