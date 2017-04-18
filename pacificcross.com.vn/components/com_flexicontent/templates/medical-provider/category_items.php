<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
/*
$db = jfactory::getDBO();

for ($i = 2500 ; $i <= 3056 ; $i++ ){
	
$sql = "select * from #__flexicontent_fields_item_relations where field_id IN (262,261) and item_id = $i ";
	
$db->setquery($sql);
$data  = $db->loadobjectlist();
	
	//echo count($data).'<br>';
	if (count($data) == 1 ) {
		print_r($data[0]->item_id);echo '<br>';
	}
	
}


die;
*/
if ($_GET['tab'] != ''){
	$countd = 1 ;
}

if ( JRequest::getVar('layoutv') == 1  || count($_GET) == (6 + $countd) ) {
    include_once __DIR__.'/view2.php';
}else {
    include_once __DIR__.'/view1.php';
}
 

