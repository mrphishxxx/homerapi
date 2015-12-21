<?php
/**
 * @api {post} /login Login with push notification parameter
 * @apiName Login
 * @apiGroup Auth
 * 
 * @apiParam {String} email User Email.
 * @apiParam {String} pswd User Password.
 * @apiParam {Number} type Device type for push notification. 1 : iOS, 2 : Android.
 * @apiParam {String} dtoken Device token for push notification.
*/

function login($email, $pswd, $type, $dtoken){
	$user = User::where('email', 'like', $email)->where('password', md5($pswd . $email))->first();
	$result = array();
	if ($user == NULL){
		$result['success'] = 'false';
		$result['message'] = 'no such user';
	} else {
		$user->login_token = md5($email . date('Y-m-d'));
		$user->push_token = $dtoken;
		$user->push_dev = $type;
		$user->save();
		$result['success'] = 'true';
		$result['token'] = $user->login_token;
	}
	echo json_encode($result);
}

/**
 * @api {post} /register Register a user
 * @apiName Register
 * @apiGroup Auth
 * 
 * @apiParam {String} email User Email.
 * @apiParam {String} pswd User Password.
*/

function register($email, $pswd){
	$user = User::where('email', 'like', $email)->first();
	$result = array();
	if ($user == NULL){
		$user = new User;
		$user->email = $email;
		$user->password = md5($pswd . $email);
		$user->save();
		$result['message'] = 'Successfully registered.';
		$result['success'] = 'true';
	} else{
		$result['message'] = 'This email is already used.';
		$result['success'] = 'false';
	}
	echo json_encode($result);
}

/**
 * @api {post} /set-data Write date-specific data
 * @apiName SetData
 * @apiGroup Data
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} year Year of date.
 * @apiParam {Number} month Month of date.
 * @apiParam {Number} day Day of date.
 * @apiParam {String} data Data to write.
*/
function set_data($token, $y, $m, $d, $data){
	$user = User::where('login_token', $token)->first();
	$result = array();
	if ($user == NULL){
		$result['success'] = 'false';
		$result['message'] = 'Invalid Token';
		return;
	} else{
		if ($m < 10)
			$m = '0' . $m;
		if ($d < 10)
			$d = '0' . $d;
		$date = "$y-$m-$d";
		$record = $user->records()->where('recdate', $date)->first();
		if ($record == NULL){
			$record = new Record;
			$record->recdate = $date;
			$record->data = $data;
			$user->records()->save($record);
		} else{
			$record->data = $data;
			$record->save();
		}
		$result['success'] = 'true';
		$result['message'] = 'Saved data';
	}
	echo json_encode($result);
}

/**
 * @api {post} /get-data Get date-specific data
 * @apiName GetData
 * @apiGroup Data
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} year Year of date.
 * @apiParam {Number} month Month of date.
 * @apiParam {Number} day Day of date.
*/
function get_data($token, $y, $m, $d){
	$user = User::where('login_token', $token)->first();
	if ($user == NULL){
		$result['success'] = 'false';
		$result['message'] = 'No record yet.';
	} else{
		if ($m < 10)
			$m = '0' . $m;
		if ($d < 10)
			$d = '0' . $d;
		$date = "$y-$m-$d";
		$record = $user->records()->where('recdate', $date)->first();
		$result = array();
		if ($record == NULL){
			$result['success'] = 'false';
			$result['message'] = 'No record yet.';
		} else{
			$result['success'] = 'true';
			$result['record'] = $record;
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /forgot-password Notify a user has forgotten password
 * @apiName ForgotPassword
 * @apiGroup Auth
 * 
 * @apiParam {String} email User's email.
*/
function reserve_reset($email){
	$user = User::where('email', 'like', $email)->first();
	$result = array();
	if ($user == NULL){
		$result['success'] = 'false';
		$result['message'] = 'No such user';
	} else{
		$user->password_reset = substr(md5($email . date('Y-m-d')), 0, 4);
		$user->save();
		$msg = "We've received your request for password reset. <br/> Please remember the code below and use it in your app for password reset verification.<br> Code : " . $user->password_reset;
		
		sendEmail($user->email, 'Password Reset', $msg);

		$result['success'] = 'true';
		$result['message'] = 'Password reset key has sent to the user';
	}
	echo json_encode($result);
}

/**
 * @api {post} /reset-password Reset Password
 * @apiName ResetPassword
 * @apiGroup Auth
 * 
 * @apiParam {String} email User's email.
 * @apiParam {String} code Verification code.
 * @apiParam {String} newpass New Password.
*/
function reset_password($email, $code, $newpass){
	if ($code != 'NONE'){
		$user = User::where('email', 'like', $email)->where('password_reset', $code)->first();
		$result = array();
		if ($user == NULL){
			$result['success'] = 'false';
			$result['message'] = 'Invalid Code';
		} else{
			$user->password = md5($newpass . $email);
			$user->password_reset = 'NONE';
			$user->save();
			$result['success'] = 'true';
		}
	} else {
		$result['success'] = 'false';
		$result['message'] = 'Invalid Code';
	}
	echo json_encode($result);
}

 function sendEmail($to, $subject, $content, $cc=[]){
	
	$headers   = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/html; charset=iso-8859-1";
	$headers[] = "From: noreply@toptenpercent.co";
	$headers[] = "Reply-To: noreply@toptenpercent.co<noreply@toptenpercent.co>";
	if (count($cc)){
		$ccs = implode(',', $cc);
		$headers[] = "Cc: $ccs";
	}
	$headers[] = "Subject: {$subject}";
	$headers[] = "X-Mailer: PHP/".phpversion();

	mail($to, $subject, $content, implode("\r\n", $headers));
}



?>