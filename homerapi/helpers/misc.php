<?php

use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Gcm as GcmAdapter,
	Sly\NotificationPusher\Adapter\Apns as ApnsAdapter,
    Sly\NotificationPusher\Collection\DeviceCollection,
    Sly\NotificationPusher\Model\Device,
    Sly\NotificationPusher\Model\Message,
    Sly\NotificationPusher\Model\Push
;

function time_elapsed_string($ptime)
{
	$ptime = strtotime($ptime);
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

function validateParam($names){
	foreach ($names as $name){
		if (!isset($_POST[$name])){
			$result = array(
				'success' => 'false',
				'message' => 'Missing parameter : ' . $name,
				);
			return $result;
		}
	}
	return true;
}

function sendEmail($to, $subject, $content, $cc=[]){
	
	// $headers   = array();
	// $headers[] = "MIME-Version: 1.0";
	// $headers[] = "Content-type: text/html; charset=iso-8859-1";
	// $headers[] = "From: noreply@homer.com";
	// $headers[] = "Reply-To: noreply@homer.com<noreply@homer.com>";
	// if (count($cc)){
	// 	$ccs = implode(',', $cc);
	// 	$headers[] = "Cc: $ccs";
	// }
	// $headers[] = "Subject: {$subject}";
	// $headers[] = "X-Mailer: PHP/".phpversion();

	// return mail($to, $subject, $content, implode("\r\n", $headers));
	send_mailgun($to, $subject, $content);
}

function qbGenerateSession() {
	// Generate signature
	$nonce = rand();
	$timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
	$signature_string = "application_id=" . QB_APP_ID . "&auth_key=" . QB_AUTH_KEY . "&nonce=" . $nonce . "&timestamp=" . $timestamp;

	$signature = hash_hmac('sha1', $signature_string , QB_AUTH_SECRET);

	//echo $signature;
	//echo $timestamp;

	// Build post body
	$post_body = http_build_query( array(
		'application_id' => QB_APP_ID,
		'auth_key' => QB_AUTH_KEY,
		'timestamp' => $timestamp,
		'nonce' => $nonce,
		'signature' => $signature,
		));

	// Configure cURL
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api.quickblox.com/auth.json'); // Full path is - https://api.quickblox.com/auth.json
	curl_setopt($curl, CURLOPT_POST, true); // Use POST
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	// Execute request and read response
	$response = curl_exec($curl);

	$token = null;

	try {
		$authInfo = json_decode($response);
		$token = $authInfo->session->token;
	}
	catch (Exception $e) {
		curl_close($curl);
		return null;
	}

	// Close connection
	curl_close($curl);

	return $token;
}

function qbSignupUser($token, $login, $email, $full_name, $password) {
	$request = json_encode(array(
		'user' => array(
			'login' => $login,
			'email' => $email,
			'password' => $password,
			'full_name' => $full_name,
			)
		));

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.quickblox.com/users.json'); // Full path is - https://api.quickblox.com/users.json
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
												'Content-Type: application/json',
												'QuickBlox-REST-API-Version: 0.1.0',
												'QB-Token: ' . $token
	));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);

	$user = null;

	/*

	*/
	ob_start();
	try {
		$resp = json_decode($response);

		$error = $resp->errors;

		if ($error) {
			return null;
		}


		$user = json_decode($response)->user;
	}
	catch (Exception $e) {
		curl_close($ch);
		return null;
	}

	ob_end_clean();

	curl_close($ch);

	return $user;
 }



function getCoordinate($address){

	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=";
	$url = $url . urlencode($address);

	$geoloc = file_get_contents($url);

	return json_decode($geoloc);
}


function invalidApi(){
	echo json_encode(array(
		'success' => 'false',
		'message' => 'Invalid API: ' . $path,
		));
}

/*
function sendGcmMessage($message, $devTokens){
	// First, instantiate the manager.
	//
	// Example for production environment:
	// $pushManager = new PushManager(PushManager::ENVIRONMENT_PROD);
	//
	// Development one by default (without argument).
	$pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);

	// Then declare an adapter.
	$gcmAdapter = new GcmAdapter(array(
	    'apiKey' => DROID_API_KEY,
	));

	$devarray = array();
	foreach ($devTokens as $dtoken){
		$devarray[] = new Device($dtoken);
	}

	// Set the device(s) to push the notification to.
	$devices = new DeviceCollection($devarray);

	// Then, create the push skel.
	$msg = new Message($message);

	// Finally, create and add the push to the manager, and push it!
	$push = new Push($gcmAdapter, $devices, $msg);
	$pushManager->add($push);
	$pushManager->push(); // Returns a collection of notified devices
}
*/

function upload($image_category, $name = 'image'){
	$result = array();
	$path = "";
	$valid_image_formats = array("jpg", "png", "gif", "bmp", "jpeg", "PNG", "JPG", "JPEG", "GIF", "BMP");
	$uploadOk = 1;
	$msg = "";
	
	$hash = md5(time());
	$uploadOk = 1;
	$msg = "";
	$target_file = "";

	if ($_FILES[$name]["size"] > 5000000) {
		$msg = "Sorry, your file is too large.";
		$uploadOk = 0;
	}
			
	$ext = pathinfo($_FILES[$name]["name"], PATHINFO_EXTENSION);
	
	if(in_array($ext, $valid_image_formats))
	{
		$path .= "images/" . $image_category . '/';
	}
	else
	{
		$msg = "Not a valid format.";
		$uploadOk = 0;
	}
	if ($uploadOk != 0) {
		
		$target_file = $path . $hash . "." . $ext;
		move_uploaded_file($_FILES[$name]["tmp_name"], $target_file);
		$msg = "Upload Succeeded.";
	}
	
	$result['status'] = $uploadOk;
	$result['msg'] = $msg;
	$result['path'] = $target_file;
	$result['name'] = $_FILES[$name]['name'];
	return $result;
}

function send_mailgun($to, $subject, $body){
 
	$config = array();
 
	$config['api_key'] = "key-1b3d22c7b50b7182b478ce190e6a7a81";
 
	$config['api_url'] = "https://api.mailgun.net/v3/sandboxc6e19469cd9f420cbda5e5e7d7e8694f.mailgun.org/messages";
 
	$message = array();
 
	$message['from'] = "noreply@homer.com;";
 
	$message['to'] = $to;
 
	$message['h:Reply-To'] = "noreply@homer.com";
 
	$message['subject'] = $subject;
 
	$message['html'] = $body;
 
	$ch = curl_init();
 
	curl_setopt($ch, CURLOPT_URL, $config['api_url']);
 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
 
	curl_setopt($ch, CURLOPT_USERPWD, "api:{$config['api_key']}");
 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 
	curl_setopt($ch, CURLOPT_POST, true); 
 
	curl_setopt($ch, CURLOPT_POSTFIELDS,$message);
 
	$result = curl_exec($ch);
 
	curl_close($ch);

	return $result;
 
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if ($lat1 == NULL || $lat2 == NULL || $lon1 == NULL || $lon2 == NULL){
      return 99999;
  }
  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "M") {
    return $miles; // mile
  } else if ($unit == "N") {
      return ($miles * 0.8684); // nautical mile
    } else {
        return ($miles * 1.609344);  // kilometre
      }
}

function sendPush($logins, $content){
	$ios_devs = array();
	$droid_devs = array();
	foreach ($logins as $login){
		if ($login->push_token == NULL || strlen($login->push_token) < 10){
        	continue;
        }
		if ($login->push_type == 1){
			$ios_devs[] = $login->push_token;
		} else if ($login->push_type == 2){
			$droid_devs[] = $login->push_token;
		}
	}

	sendGCMMessage($droid_devs, $content);
	sendAPNMessage($ios_devs, $content);
}

function sendGCMMessage($registration_ids, $content)
{

	$fields = array(
		'registration_ids' =>  $registration_ids,
		'data'			   => 	$content
		);

	$headers = array(
		'Authorization: key=' . DROID_API_KEY,
		'Content-Type: application/json'
	);

	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	
//	print_r($registration_ids);
//	print_r($fields);
//	echo $result;
	if(curl_errno($ch))
	{
		 // echo 'GCM error:'.curl_error($ch);
	}
	curl_close( $ch );

}

function sendAPNMessage($registration_ids, $content){
	$pushManager = new PushManager(PushManager::ENVIRONMENT_DEV);
	
	$apnsAdapter = new ApnsAdapter(array(
	    'certificate' => '/certs/apns-certificate.pem',
	    'passPhrase' => 'apple',
	));

	$devices = new DeviceCollection($registration_ids);

	$message = new Message($content['message'], array(

	    'custom' => array('custom data' => $content)
	));

	// Finally, create and add the push to the manager, and push it!
	$push = new Push($apnsAdapter, $devices, $message);
	$pushManager->add($push);
	$pushManager->push(); // Returns a collection of notified devices

}

?>