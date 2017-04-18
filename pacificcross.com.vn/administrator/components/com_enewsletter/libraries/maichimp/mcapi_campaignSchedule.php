<?php
/**
This Example shows how to schedule a campaign for future delivery
via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI('9b30e6f1bfa7bb0f07e6a4bd2c3232f4-us7');

$schedule_for = '2018-04-01 09:05:21';
$retval = $api->campaignSchedule(122769, $schedule_for);

if ($api->errorCode){
	echo "Unable to Schedule Campaign!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
	echo "Campaign Scheduled to be delivered $schedule_for!\n";
}

?>
