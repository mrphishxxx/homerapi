<?php

function time_elapsed_string($ptime)
{
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