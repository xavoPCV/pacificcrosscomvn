<?php
/**
This Example shows how to retrieve a list of your campaigns via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI('9b30e6f1bfa7bb0f07e6a4bd2c3232f4-us7');

$retval = $api->campaigns();

echo '<pre>';
print_r($retval);

if ($api->errorCode){
	echo "Unable to Pull list of Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
    echo sizeof($retval['total'])." Total Campaigns Matched.\n";
    echo sizeof($retval['data'])." Total Campaigns returned:\n";
    foreach($retval['data'] as $c){
        echo "Campaign Id: ".$c['id']." - ".$c['title']."<br>";
        echo "\tStatus: ".$c['status']." - type = ".$c['type']."<br>";
        echo "\tsent: ".$c['send_time']." to ".$c['emails_sent']." members<br>";
		echo '<hr>';
    }
}

?>
