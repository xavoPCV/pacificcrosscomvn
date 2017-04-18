<?php
/**
This Example shows how to Subscribe a New Member to a List using the MCAPI.php 
class and do some basic error checking.
**/
require_once 'inc/MCAPI.class.php';
require_once 'inc/config.inc.php'; //contains apikey

$api = new MCAPI('9b30e6f1bfa7bb0f07e6a4bd2c3232f4-us7');

/*$merge_vars = array('FNAME'=>'Test', 'LNAME'=>'Account', 
                  'GROUPINGS'=>array(
                        array('name'=>'Your Interests:', 'groups'=>'Bananas,Apples'),
                        array('id'=>22, 'groups'=>'Trains'),
                        )
                    );*/
					

// By default this sends a confirmation email - you will not see new members
// until the link contained in it is clicked!
$retval = $api->listInterestGroupAdd( 'dad969b3a0', 'Test Group 1' );

if ($api->errorCode){
	echo "Unable to add group()!\n";
	echo "\tCode=".$api->errorCode."\n";
	echo "\tMsg=".$api->errorMessage."\n";
} else {
    echo "Group added !\n";
}

?>
