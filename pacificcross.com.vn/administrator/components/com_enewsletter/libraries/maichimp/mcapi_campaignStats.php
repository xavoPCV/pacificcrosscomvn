<?php
/**
This Example shows how to pull basic stats for a Campaign Tests 
via the MCAPI class.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI('9b30e6f1bfa7bb0f07e6a4bd2c3232f4-us7');

$retval = $api->campaignStats('bffe3d2287');

if ($api->errorCode){
	echo "Unable to Load Campaign Stats!";
	echo "\n\tCode=".$api->errorCode;
	echo "\n\tMsg=".$api->errorMessage."\n";
} else {
    echo "Stats for ".$campaignId."\n";
    foreach($retval as $k=>$v){
        echo "\t".$k." => ".$v."\n";
    }
}

?>
