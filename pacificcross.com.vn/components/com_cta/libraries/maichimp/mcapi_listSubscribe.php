<?php
/**
This Example shows how to Subscribe a New Member to a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

//$api = new MCAPI('9b30e6f1bfa7bb0f07e6a4bd2c3232f4-us7'); // Paresh

$api = new MCAPI('0b3e16009091df5481754c60578aeaac-us7'); // Frank



/*$merge_vars = array('FNAME'=>'Test', 'LNAME'=>'Account', 
                  'GROUPINGS'=>array(
                        array('name'=>'Your Interests:', 'groups'=>'Bananas,Apples'),
                        array('id'=>22, 'groups'=>'Trains'),
                        )
                    );*/
					
$merge_vars = array('FNAME'=>'Jeff', 'LNAME'=>'Lopez');

// By default this sends a confirmation email - you will not see new members
// until the link contained in it is clicked!
$retval = $api->listSubscribe( 'b779b4aed8', 'jefflopez786@gmail.com', $merge_vars );

if ($api->errorCode){
	echo "Unable to load listSubscribe()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Subscribed - look for the confirmation email!\n";
}

?>
