<?php

date_default_timezone_set('Europe/Berlin');

$path = $_SERVER['PATH_INFO'];

switch ($path){
	case '/auth/login':
		api_login();
		break;
	case '/auth/login_facebook':
		api_login_facebook();
		break;
	case 'auth/login_google':
		api_login_google();
		break;
	case '/auth/register':
		api_register();
		break;
	case '/auth/forgot-password':
		api_reserve_reset($email);
		break;
	case '/auth/reset-password':
		api_reset_password($email, $code, $newpass);
		break;
	case '/post/add': // add
		api_add_post();
		break;
	case '/post/get-own': // my post
		api_get_own_posts();
		break;
	case '/post/get-all': // get all posts
		api_get_all_posts();
		break;
	case '/post/get-own-detail':
		api_get_own_post_detail();
		break;
	case '/post/get-detail':
		api_get_post_detail();
		break;
	case '/post/delete':
		api_delete_post();
		break;
	case '/post/edit':
		api_edit_post();
		break;
	case '/user/get-profile':
		api_get_profile();
		break;
	case '/user/rate':
		break;
	default:
		echo json_encode(array(
			'success' => 'false',
			'message' => 'Invalid API: ' . $path,
			));
		break;
}

?>