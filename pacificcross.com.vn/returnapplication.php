<?php
	require_once( 'lib/nusoap.php' );    


	$client = new nusoap_client( "http://bluecrossvietnam.com.vn/webservice/ws_pcv.php?wsdl" );	
	// Input value

	$tokens = array
	(
		'www.pacific.com.vn' => '7e57e5d8447cfd5a12a770569d63c5cd19dd146321e88ba71ded94d986cb3a03fbde4229446b3317357880645b07e651',
		'www.gobear.com'     => '4eae35f1b35977a00ebd8086c259d4c9e41b85d47d29927b2c82e9e126460b3a4d236d9a2d102c5fe6ad1c50da4bec50'
	);
	$agent = (isset($_GET['source']) && array_key_exists($_GET['source'], $tokens)) ? $_GET['source'] : 'www.pacific.com.vn';
	$token = $tokens[$agent];

    $PNRNo = '';
	$refNo = '';
	$policyHolder = $_POST['form']['APPLICANT'];
	$policyHolderAddress = $_POST['form']['PolicyHolderAddress'];
	$policyHolderTel = $_POST['form']['PolicyHolderTel'];
	$policyHolderEmail = $_POST['form']['PolicyHolderEmail'];
	$originCountry =strtoupper ($_POST['form']['OriginCountry'][0]);
        
        if ($_POST['form']['PremiumType'][0] == 'Individual'){
             $premiumType = "Individual";              
        } else {
             $premiumType = "Family";
        }
	
	
	$planID = $_POST['form']['planchoice'];
	$itinerary = '';
   

/*
if ($planID  == 'A' || $planID  == 'B' || $planID  == 'C' ){


        
        
        
        

}else{

	$rrarday = explode('/', $_POST['form']['DepartureDate']);
    $departureDate = $rrarday[1].'/'.$rrarday[0].'/'.$rrarday[2].' 23:00:00';
    $rrarday = explode('/', $_POST['form']['ReturnDate']);
	$returnDate = $rrarday[1].'/'.$rrarday[0].'/'.$rrarday[2].' 23:59:00';
	

}
*/


	
$rrarday = explode('/', $_POST['form']['from']);
	
	$departureDate = $rrarday[1].'/'.$rrarday[0].'/'.$rrarday[2].' 23:00:00';
	
	$dateold =  $_POST['form']['forday'] - 1;
	
	$stop_date =  $rrarday[2].'-'.$rrarday[1].'-'.$rrarday[0];
	
	$returnDate = date('m/d/Y', strtotime($stop_date . ' +'.$dateold.' day')).' 23:59:00';

	$trial = 1;

	

	
	// Initiate member list
	$insuredPersons = array();
        
        for( $i = 1 ; $i <= 10 ;$i++ ){
            
            
             
             if ($_POST['form']['FullName'.$i] != '' && $_POST['form']['MemberType'.$i] != '' ){
                    $RentalCarFrom =  $RentalCarTo = ''; 
                    if ($_POST['form']['PersonRentalCar'.$i][0]  == 'Yes'){
                        $checkretal = 1; 
                        $rrarday = explode('/', $_POST['form'][$i.'dayfrom']);
                        $RentalCarFrom = $rrarday[1].'/'.$rrarday[0].'/'.$rrarday[2].' 23:00:59';
                        $dateold =  $_POST['form']['PersonRentalCarDay'.$i][0] - 1;                      
                        $stop_date =  $rrarday[2].'-'.$rrarday[1].'-'.$rrarday[0];	
                        $RentalCarTo = date('m/d/Y', strtotime($stop_date . ' +'.$dateold.' day')).' 23:58:00';
                      
                    }		 
                    if ($premiumType == 'Individual' ) {
                             // $_POST['form']['MemberType'.$i][0] = '';				 
                    }
				 
                    $rrarday = explode('/', $_POST['form']['DateOfBrirth'.$i]);
                    $insuredPersons[] = array(
                       'FullName' => $_POST['form']['FullName'.$i],
                       'DateOfBirth' => $_POST['form']['DateOfBrirth'.$i]['m'].'/'.$_POST['form']['DateOfBrirth'.$i]['d'].'/'.$_POST['form']['DateOfBrirth'.$i]['y'],
                       'Gender' => $_POST['form']['Gender'.$i][0],
                       'Passport' => $_POST['form']['Passport'.$i],
                       'MemberType' =>  $_POST['form']['MemberType'.$i][0],
                       'RentalCarProtection' => $_POST['form']['PersonRentalCar'.$i][0],
                       'RentalCarFrom' => $RentalCarFrom,
                       'RentalCarTo' => $RentalCarTo
                   );
            }
        }
       
        if ($checkretal == 1 ) {
            $planID = $planID.'C';
        }else{
            $planID = $planID.'N';
        }
	
        
        
	$list = $client->call( 'issueCertificate',
		array(
			'token' => "$token",
			'PNRNo' => "$PNRNo",
			'refNo' => "$refNo",
			'policyHolder' => "$policyHolder",
			'policyHolderAddress' => "$policyHolderAddress",
			'policyHolderTel' => "$policyHolderTel",
			'policyHolderEmail' => "$policyHolderEmail",
			'originCountry' => "$originCountry",
			'planID' => "$planID",
			'itinerary' => "$itinerary",
			'premiumType' => "$premiumType",			
			'departureDate' => "$departureDate",
			'returnDate' => "$returnDate",
			'insuredPersons' => $insuredPersons,
			'trial' => $trial,
			'IsSendEmail' => $senditmail
		)
	);
        
        
        
// Print results	
//	echo '<pre>';
//	echo 'Error message: ' . $list[ 'Error' ] . "<br/>";
//	echo "Certificate Link = " . $list[ 'CertificateLink' ] . "<br/>";
//	echo 'Certificate No = ' . $list[ 'CertificateNo' ] . "<br/>";
//	echo 'Total Price = ' . $list[ 'TotalPrice' ] * 1000 . " VND<br/>";
//	echo 'Unit Price = ' . $list[ 'UnitPrice' ] * 1000 . " VND<br/>";
//	echo '</pre>';
	
	$err = $client->getError();
	if ( $err ) {
		echo '<h2>Error Found While Connecting</h2><pre>' . $err . '</pre>\n';
        }else {
            $list[ 'TotalPrice' ]  = number_format( ($list[ 'TotalPrice' ]*1000) ).' VND';
            echo json_encode ($list);
		file_put_contents(__DIR__.'/textaa.text',json_encode ($list));
        }
?>