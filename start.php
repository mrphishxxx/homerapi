<?php

date_default_timezone_set('Europe/Berlin');

$path = $_SERVER['PATH_INFO'];
$params = explode('/', $path);

switch ($path){
	case '/auth/login':
		api_login();
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

	default:
		echo json_encode(array(
			'success' => 'false',
			'message' => 'Invalid API: ' . $path,
			));
		break;
}

?>