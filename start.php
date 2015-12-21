<?php

date_default_timezone_set('Europe/Berlin');

$api = $_GET['api'];

switch ($api){
	case 'login':
		$email = $_POST['email'];
		$pswd = $_POST['pswd'];
		$pushtype = $_POST['type'];
		$pushtoken = $_POST['dtoken'];
		login($email, $pswd, $pushtype, $pushtoken);
		break;
	case 'register':
		$email = $_POST['email'];
		$pswd = $_POST['pswd'];
		register($email, $pswd);
		break;
	case 'forgot-password':
		$email = $_POST['email'];
		reserve_reset($email);
		break;
	case 'reset-password':
		$email = $_POST['email'];
		$code = $_POST['code'];
		$newpass = $_POST['newpass'];
		reset_password($email, $code, $newpass);
		break;
	case 'set-data':
		$token = $_SERVER['Authorization'];
		$y = $_POST['year'];
		$m = $_POST['month'];
		$d = $_POST['day'];
		$data = $_POST['data'];
		set_data($token, $y, $m, $d, $data);
		break;
	case 'get-data':
		$token = $_SERVER['Authorization'];
		$y = $_POST['year'];
		$m = $_POST['month'];
		$d = $_POST['day'];
		get_data($token, $y, $m, $d);
		break;
}

?>