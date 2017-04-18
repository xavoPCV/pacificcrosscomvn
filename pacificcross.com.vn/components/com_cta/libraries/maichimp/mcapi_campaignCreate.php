<?php
/**
This Example shows how to create a basic campaign via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey


$file = file_get_contents('data.html', true);
//$homepage = file_get_contents('http://localhost/maichimp_example/data.html');
//echo $file;exit;

$api = new MCAPI('9b30e6f1bfa7bb0f07e6a4bd2c3232f4-us7');

$type = 'regular';

$opts['list_id'] = 'dad969b3a0';
$opts['subject'] = 'Test Newsletter Subject';
$opts['from_email'] = 'paresh.nagar@agileinfoways.com'; 
$opts['from_name'] = 'ACME, Inc.';

$opts['tracking']=array('opens' => true, 'html_clicks' => true, 'text_clicks' => false);

$opts['authenticate'] = true;
$opts['analytics'] = array('google'=>'my_google_analytics_key');
$opts['title'] = 'Test Newsletter Title';

//$content = array('html'=>'some pretty html content *|UNSUB|* message', 
//		  'text' => 'text text text *|UNSUB|*'
//		);
$content = array('html'=>$file, 
		  'text' => 'text text text *|UNSUB|*'
		);
		
		
/** OR we could use this:
$content = array('html_main'=>'some pretty html content',
		 'html_sidecolumn' => 'this goes in a side column',
		 'html_header' => 'this gets placed in the header',
		 'html_footer' => 'the footer with an *|UNSUB|* message', 
		 'text' => 'text content text content *|UNSUB|*'
		);
$opts['template_id'] = "1";
**/

$retval = $api->campaignCreate($type, $opts, $content);

if ($api->errorCode){
	echo "Unable to Create New Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "New Campaign ID:".$retval."\n";
}

?>
