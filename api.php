<?php
/**
 * @api {post} /auth/login Login with push notification parameter
 * @apiVersion 1.0.0
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
			$result['data']['token'] = $user->logins()->first()->token;
			$result['data']['avatar'] = $user->avatar;
			$result['data']['full_name'] = $user->full_name;
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /auth/login Login with push notification parameter
 * @apiVersion 1.0.0
 * @apiName Login
 * @apiGroup Auth
 * 
 * @apiParam {String} full_name User's full name.
 * @apiParam {String} email User Email.
 * @apiParam {String} pswd User Password.
 * @apiParam {Number} push_type Device type for push notification. 1 : iOS, 2 : Android.
 * @apiParam {String} push_token Device token for push notification.
 * @apiParam {String} facebook_id Facebook Id
*/

function api_login_facebook(){
	$params = ['full_name', 'email', 'pswd', 'push_type', 'push_token', 'facebook_id'];
	$result = validateParam($params);

	if ($result === true){
		extract($_POST);
		$user = __login_facebook($email, $pswd, $push_type, $push_token, $facebook_id);
		$result = array();
		if ($user == NULL){
			$result['success'] = 'false';
			$result['message'] = 'No such user';
		} else {
			$result['success'] = 'true';
			$result['message'] = 'Successfully logged in.';
			$result['data']['token'] = $user->login_token;
			$result['data']['avatar'] = $user->avatar;
			$result['data']['full_name'] = $user->full_name;
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /auth/login Login with push notification parameter
 * @apiVersion 1.0.0
 * @apiName Login
 * @apiGroup Auth
 * 
 * @apiParam {String} full_name User's full name.
 * @apiParam {String} email User Email.
 * @apiParam {String} pswd User Password.
 * @apiParam {Number} push_type Device type for push notification. 1 : iOS, 2 : Android.
 * @apiParam {String} push_token Device token for push notification.
 * @apiParam {String} google_id Google ID
*/

function api_login_google(){
	$params = ['full_name', 'email', 'pswd', 'push_type', 'push_token', 'google_id'];
	$result = validateParam($params);

	if ($result === true){
		extract($_POST);
		$user = __login_google($email, $pswd, $push_type, $push_token, $google_id);
		$result = array();
		if ($user == NULL){
			$result['success'] = 'false';
			$result['message'] = 'No such user';
		} else {
			$result['success'] = 'true';
			$result['message'] = 'Successfully logged in.';
			$result['data']['token'] = $user->login_token;
			$result['data']['avatar'] = $user->avatar;
			$result['data']['full_name'] = $user->full_name;
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /auth/register Register a user
 * @apiVersion 1.0.0
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
				$result['data']['token'] = $user->logins()->first()->token;
				$result['data']['avatar'] = $user->avatar;
				$result['data']['full_name'] = $user->full_name;
			}
		} else{
			$result['message'] = 'This email is already used.';
			$result['success'] = 'false';
		}
	}

	echo json_encode($result);
}


/**
 * @api {post} /auth/forgot-password Notify a user has forgotten password
 * @apiVersion 1.0.0
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
 * @apiVersion 1.0.0
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
		extract($_POST);
		if (__password_reset($email, $code, $newpass)){
			$user = __login($email, $newpass);
			if ($user != NULL){
				$result['data']['token'] = $user->login_token;
				$result['data']['avatar'] = $user->avatar;
				$result['data']['full_name'] = $user->full_name;
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

/**
 * @api {post} /post/add Add Post
 * @apiVersion 1.0.0
 * @apiName AddPost
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {String} post_type 1 : "need" or 2 : "has"
 * @apiParam {String} property_type either of "apartment", "house", "penthouse", etc...
 * @apiParam {String} location Google location string
 * @apiParam {Number} num_rooms Number of rooms
 * @apiParam {Number} area area
 * @apiParam {Number} price Price
 * @apiParam {String} description Description
*/
function api_add_post(){
	$params = ['post_type', 'property_type', 'location', 'num_rooms', 'area', 'price', 'description'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		extract($_POST);
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid token ' . $token,
				);
		} else{
			$post = __add_post($user->id, $post_type, $property_type, $location, $num_rooms, $area, $price, $description);
			$result = array(
				'success' => 'true',
				'message' => 'Successfully posted'
				);
			$result['data']['post_id'] = $post->id;
		}
	}
	echo json_encode($result);
}


/**
 * @api {post} /post/get-own Get Own Post
 * @apiVersion 1.0.0
 * @apiName GetOwnPosts
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
*/
function api_get_own_posts(){
	$token = $_SERVER['Authorization'];
	$user = __get_user_from_token($token);
	if ($user == NULL){
		$result = array(
			'success' => 'false',
			'message' => 'Invalid token ' . $token,
			);
	} else{
		$posts = $user->posts()->orderBy('id', 'desc')->get();
		$seenPosts = $user->viewedPosts;
		$seenIds = array();
		$totalMatch = 0;
		foreach ($seenPosts as $p){
			$seenIds[] = $p->id;
		}

		$rposts = array();
		foreach ($posts as $post){
			$matches = $post->matchedPosts()->whereNotIn('id', $seenIds)->count() + $post->matchingPosts()->whereNotIn('id', $seenIds)->count();
			$totalMatch += $matches;
			$rpost = array(
				'post_id' => $post->id,
				'post_type' => $post->post_type,
				'property_type' => $post->property_type,
				'location' => $post->location,
				'lat' => $post->lat,
				'lng' => $post->lng,
				'num_rooms' => $post->num_rooms,
				'area' => $post->area,
				'price' => $post->price,
				'description' => $post->description,
				'post_date' => $post->post_time,
				'num_new_match' => $matches,
				);
			$rposts[] = $rpost;
		}

		$result = array(
			'success' => 'true',
			'message' => 'Successfully fetched'
			);

		$result['data']['total_num_new_match'] = $totalMatch;
		$result['data']['posts'] = $rposts;
	}
	echo json_encode($result);
}

/**
 * @api {post} /post/get-all Get Own Post
 * @apiVersion 1.0.0
 * @apiName GetAllPosts
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
*/
function api_get_all_posts(){
	$token = $_SERVER['Authorization'];
	$user = __get_user_from_token($token);
	if ($user == NULL){
		$result = array(
			'success' => 'false',
			'message' => 'Invalid token',
			);
	} else{
		$posts = Post::orderBy('id', 'desc')->get();
		$rposts = array();

		foreach ($posts as $post){
			$rpost = array(
				'post_id' => $post->id,
				'post_type' => $post->post_type,
				'property_type' => $post->property_type,
				'location' => $post->location,
				'lat' => $post->lat,
				'lng' => $post->lng,
				'num_rooms' => $post->num_rooms,
				'area' => $post->area,
				'price' => $post->price,
				'description' => $post->description,
				'post_date' => $post->post_time,
				'agent_id' => $post->user->id,
				'agent_name' => $post->user->full_name,
				'quickblox_id' => $post->user->quickblox_id,
				);
			$rposts[] = $rpost;
		}

		$result = array(
			'success' => 'true',
			'message' => 'Successfully fetched',
			'data' => array(
				'posts' => $rposts,
				),
			);
	}
	echo json_encode($result);
}

/**
 * @api {post} /post/get-own-detail Get Own Post
 * @apiVersion 1.0.0
 * @apiName GetOwnPostDetail
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} post_id Post ID
*/
function api_get_own_post_detail(){
	$params = ['post_id'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		extract($_POST);
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid token',
				);
		} else{
			$post = Post::find($post_id);
			if ($post == NULL){
				$result = array(
					'success' => 'false',
					'message' => 'No such post',
					);
			} else{
				$dist = 'CoordinateDistanceKM(lat, lng, ' . $post->lat . ', ' . $post->lng . ')';
				$matchings = $post->matchingPosts()->whereRaw($dist)->orderByRaw($dist, 'asc')->limit(100)->get();// + $post->matchedPosts;
				$similars = $post->similarFrom()->whereRaw($dist)->orderByRaw($dist, 'asc')->limit(100)->get();// + $post->similarTo;

				$seenPosts = $user->viewedPosts;
				$seenIds = array();
				foreach ($seenPosts as $p){
					$seenIds[] = $p->id;
				}

				$marray = array();
				$sarray = array();
				foreach ($matchings as $post){
					$m = array(
						'post_id' => $post->id,
						'image_avatar' => $post->user->avatar,
						'agent_id' => $post->user->id,
						'agent_name' => $post->user->full_name,
						'quickblox_id' => $post->user->quickblox_id,
						'location' => $post->location,
						'lat' => $post->lat,
						'lng' => $post->lng,
						'num_rooms' => $post->num_rooms,
						'area' => $post->area,
						'price' => $post->price,
						'description' => $post->description,
						'post_date' => $post->post_time,
						'is_new' => in_array($post->id, $seenIds),
						);
					$marray[] = $m;
				}

				foreach ($similars as $post) {
					$s = array(
						'post_id' => $post->id,
						'image_avatar' => $post->user->avatar,
						'agent_id' => $post->user->id,
						'agent_name' => $post->user->full_name,
						'quickblox_id' => $post->user->quickblox_id,
						'location' => $post->location,
						'lat' => $post->lat,
						'lng' => $post->lng,
						'num_rooms' => $post->num_rooms,
						'area' => $post->area,
						'price' => $post->price,
						'description' => $post->description,
						'post_date' => $post->post_time,
						'is_new' => in_array($post->id, $seenIds),
						);
					$sarray[] = $s;
				}

				__view_post($user->id, $post->id);

				$result = array(
					'success' => 'true',
					'message' => 'Successfully fetched',
					'data' => array(
						'matchings' => $marray,
						'similars' => $sarray,
						),
					);
			}
		}
	}

	echo json_encode($result);
}

/**
 * @api {post} /post/get-detail Get Post
 * @apiVersion 1.0.0
 * @apiName GetPostDetail
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} post_id Post ID
*/
function api_get_post_detail(){
	$params = ['post_id'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		extract($_POST);
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid token',
				);
		} else{
			$post = Post::find($post_id);
			if ($post == NULL){
				$result = array(
					'success' => 'false',
					'message' => 'No such post',
					);
			} else{
				$dist = 'CoordinateDistanceKM(lat, lng, ' . $post->lat . ', ' . $post->lng . ')';
				$matchings = $post->matchingPosts()->whereRaw($dist)->orderByRaw($dist, 'asc')->limit(100)->get();// + $post->matchedPosts;
				$similars = $post->similarFrom()->whereRaw($dist)->orderByRaw($dist, 'asc')->limit(100)->get();// + $post->similarTo;

				$marray = array();
				$sarray = array();
				foreach ($matchings as $post){
					$m = array(
						'post_id' => $post->id,
						'image_avatar' => $post->user->avatar,
						'agent_id' => $post->user->id,
						'agent_name' => $post->user->full_name,
						'quickblox_id' => $post->user->quickblox_id,
						'location' => $post->location,
						'lat' => $post->lat,
						'lng' => $post->lng,
						'num_rooms' => $post->num_rooms,
						'area' => $post->area,
						'price' => $post->price,
						'description' => $post->description,
						'post_date' => $post->post_time,
						);
					$marray[] = $m;
				}

				foreach ($similars as $post) {
					$s = array(
						'post_id' => $post->id,
						'image_avatar' => $post->user->avatar,
						'agent_id' => $post->user->id,
						'agent_name' => $post->user->full_name,
						'quickblox_id' => $post->user->quickblox_id,
						'location' => $post->location,
						'lat' => $post->lat,
						'lng' => $post->lng,
						'num_rooms' => $post->num_rooms,
						'area' => $post->area,
						'price' => $post->price,
						'description' => $post->description,
						'post_date' => $post->post_time,
						);
					$sarray[] = $s;
				}

				__view_post($user->id, $post->id);

				$result = array(
					'success' => 'true',
					'message' => 'Successfully fetched',
					'data' => array(
						'matchings' => $marray,
						'similars' => $sarray,
						),
					);
			}
		}
	}

	echo json_encode($result);
}

/**
 * @api {post} /post/delete Delete Post
 * @apiVersion 1.0.0
 * @apiName DeletePost
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} post_id Post ID
*/
function api_delete_post(){
	$params = ['post_id'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		extract($_POST);
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid token',
				);
		} else{
			$post = Post::find($post_id);
			if ($post == NULL){
				$result = array(
					'success' => 'false',
					'message' => 'No such post',
					);
			} else{
				$post->delete();
				$result = array(
					'success' => 'true',
					'message' => 'Successfully deleted',
					);
			}
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /post/edit Edit Post
 * @apiVersion 1.0.0
 * @apiName EditPost
 * @apiGroup Post
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} post_id Post ID
 * @apiParam {String} post_type 1 : "need" or 2 : "has"
 * @apiParam {String} property_type either of "apartment", "house", "penthouse", etc...
 * @apiParam {String} location Google location string
 * @apiParam {Number} num_rooms Number of rooms
 * @apiParam {Number} area area
 * @apiParam {Number} price Price
 * @apiParam {String} description Description
*/
function api_edit_post(){
	$params = ['post_id', 'post_type', 'property_type', 'location', 'num_rooms', 'area', 'price', 'description'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		extract($_POST);
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid token',
				);
		} else{
			$post = Post::find($post_id);
			if ($post == NULL){
				$result = array(
					'success' => 'false',
					'message' => 'No such post',
					);
			} else{
				__edit_post($user->id, $post_id, $post_type, $property_type, $location, $num_rooms, $area, $price, $description);

				$result = array(
					'success' => 'true',
					'message' => 'Successfully deleted',
					);
			}
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /user/get-mine Get My Profile
 * @apiVersion 1.0.0
 * @apiName GetMyProfile
 * @apiGroup User
 * 
 * @apiHeader {String} Authorization Users unique access-key.
*/
function api_get_my_profile(){
	$token = $_SERVER['Authorization'];
	$user = __get_user_from_token($token);
	$result = array();
	if ($user == NULL){
		$result = array(
			'success' => 'false',
			'message' => 'Invalid Token',
			);
	} else{
		$result = array(
			'success' => 'true',
			'message' => 'Successfully fetched user profile',
			'data' => array(
				'name' => $user->full_name,
				'email_verified' => $user->email_verified,
				'phone_verified' => $user->phone_verified,
				'creci_verified' => $user->creci_verified,
				'score' => $user->ratings()->avg('score'),
				)
			);
	}
	echo json_encode($result);
}

/**
 * @api {post} /user/get Get User Profile
 * @apiVersion 1.0.0
 * @apiName GetUserProfile
 * @apiGroup User
 * 
 * @apiHeader {String} Authorization Users unique access-key.
*/
function api_get_user_profile(){
	$params = ['agent_id', 'score'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		$result = array();
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid Token',
				);
		} else{
			$user = User::find($agent_id);
			if ($user == NULL){
				$result = array(
					'success' => 'false',
					'message' => 'No such user',
					);
			} else{
				$result = array(
					'success' => 'true',
					'message' => 'Successfully fetched user profile',
					'data' => array(
						'name' => $user->full_name,
						'email_verified' => $user->email_verified,
						'phone_verified' => $user->phone_verified,
						'creci_verified' => $user->creci_verified,
						'score' => $user->ratings()->avg('score'),
						)
					);
			}
		}
	}
	echo json_encode($result);
}

/**
 * @api {post} /user/rate Rate User
 * @apiVersion 1.0.0
 * @apiName RateUser
 * @apiGroup User
 * 
 * @apiHeader {String} Authorization Users unique access-key.
 * @apiParam {Number} post_id Post ID
*/
function api_rate_user(){
	$params = ['agent_id', 'score'];
	$result = validateParam($params);

	if ($result === true){
		$token = $_SERVER['Authorization'];
		$user = __get_user_from_token($token);
		extract($_POST);
		if ($user == NULL){
			$result = array(
				'success' => 'false',
				'message' => 'Invalid token',
				);
		} else{
			if ($agent_id == $user->id){
				$result = array(
					'success' => 'false',
					'message' => "You can't rate yourself"
					);
			} else{
				$tuser = User::find($agent_id);
				if ($tuser == NULL){
					$result = array(
						'success' => 'false',
						'message' => 'No such user',
						);
				} else{
					if ($tuser->ratings()->where('user_from', $user->id)->count > 0){
						$result = array(
							'success' => 'false',
							'message' => "You've already rated this user"
						);
					} else{
						__rate_user($user->id, $tuser->id, $score);
						$result = array(
							'success' => 'true',
							'message' => "Successfully rated",
							);
					}
				}
			}
		}
	}

	echo json_encode($result);
}

function api_test(){
	// $post = Post::find(3);
	// $areaRange = $post->area / 5;
 //  	$priceRange = $post->price / 5;
 //  	$ptype = $post->post_type == 1 ? 0 : 1;

	// $similars = Post::where('property_type', $post->property_type)
 //                  ->whereRaw('abs(' . $post->area . ' - area) <= ' . $areaRange)
 //                  ->whereRaw('abs(' . $post->price . ' - price) <= ' . $priceRange)
 //                  ->where('num_rooms', '>=', $post->num_rooms)
 //                  ->where('post_type', $post->post_type)
 //                  ->where('id', '<>', $post->id)
 //                  ->get();

 //    echo json_encode($similars);
	// $user = User::find(5);
	// echo json_encode($user->posts);
	if (sendEmail('lodestar9317@163.com', 'Test', 'This is a test', ['lodestar9317@gmail.com'])){
		echo 'success';
	} else{
		echo 'failed';
	}
}

?>