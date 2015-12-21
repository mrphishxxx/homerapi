<?php
/**
 * @api {post} /auth/login Login with push notification parameter
 * @apiName Login
 * @apiGroup Auth
 * 
 * @apiParam {String} email User Email.
 * @apiParam {String} pswd User Password.
 * @apiParam {Number} push_type Device type for push notification. 1 : iOS, 2 : Android.
 * @apiParam {String} push_token Device token for push notification.
*/

function api_login(){
	$params = ['email', 'pswd', 'push_type', 'push_token'];
	$result = validateParam($params);

	if ($result === true){
		extract($_POST);
		$user = __login($email, $pswd, $push_type, $push_token);
		$result = array();
		if ($user == NULL){
			$result['success'] = 'false';
			$result['message'] = 'No such user';
		} else {
			$result['success'] = 'true';
			$result['message'] = 'Successfully logged in.';
			$result['token'] = $user->login_token;
			$result['avatar'] = $user->avatar;
			$result['full_name'] = $user->full_name;
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /auth/register Register a user
 * @apiName Register
 * @apiGroup Auth
 * 
 * @apiParam {String} full_name User Full name.
 * @apiParam {String} email User Email.
 * @apiParam {String} pswd User Password.
 * @apiParam {Number} push_type Device type for push notification. 1 : iOS, 2 : Android.
 * @apiParam {String} push_token Device token for push notification.
*/

function api_register(){
	$params = ['full_name', 'email', 'pswd', 'push_type', 'push_token'];
	$result = validateParam($params);

	if ($result === true){
		extract($_POST);
		$result = array();
		if (__register($full_name, $email, $pswd)){
			$user = __login($email, $pswd, $push_type, $push_token);
			
			if ($user == NULL)
			{
				$result['success'] = 'false';
				$result['message'] = 'No such user';
			} else{
				$result['message'] = 'Successfully registered.';
				$result['success'] = 'true';
				$result['token'] = $user->login_token;
				$result['avatar'] = $user->avatar;
				$result['full_name'] = $user->full_name;
			}
		} else{
			$result['message'] = 'This email is already used.';
			$result['success'] = 'false';
		}
	} else{
		$result['token'] = $user->login_token;
		$result['avatar'] = $user->avatar;
		$result['full_name'] = $user->full_name;
	}
	echo json_encode($result);
}


/**
 * @api {post} /auth/forgot-password Notify a user has forgotten password
 * @apiName ForgotPassword
 * @apiGroup Auth
 * 
 * @apiParam {String} email User's email.
*/
function api_reserve_reset(){
	$params = ['email'];
	$result = validateParam($params);

	if ($result === true){
		extract($_POST);
		if (__reserve_reset($email)){
			$result['success'] = 'true';
    		$result['message'] = 'Password reset key has sent to the user';
		} else{
			$result['success'] = 'false';
			$result['message'] = 'No such user';
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /auth/reset-password Reset Password
 * @apiName ResetPassword
 * @apiGroup Auth
 * 
 * @apiParam {String} email User's email.
 * @apiParam {String} code Verification code.
 * @apiParam {String} newpass New Password.
 * @apiParam {Number} push_type Device type for push notification. 1 : iOS, 2 : Android.
 * @apiParam {String} push_token Device token for push notification.
*/
function api_reset_password(){
	$params = ['email', 'code', 'newpass', 'push_type', 'push_token'];
	$result = validateParam($params);

	if ($result === true){
		
		if (__password_reset($email, $code, $newpass)){
			$user = __login($email, $newpass);
			if ($user != NULL){
				$result['token'] = $user->login_token;
				$result['avatar'] = $user->avatar;
				$result['full_name'] = $user->full_name;
				$result['success'] = 'true';
    			$result['message'] = 'Password has been reset';
			} else{
				$result['success'] = 'false';
    			$result['message'] = 'Login failed.';
			}
		} else{
			$result['success'] = 'false';
    		$result['message'] = 'Invalid Code';
		}
	}
	echo json_encode($result);
}


?>