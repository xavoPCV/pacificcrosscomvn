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
$policyHolder = $_POST[ 'form' ][ 'PolicyHolder' ];
$policyHolderAddress = $_POST[ 'form' ][ 'PolicyHolderAddress' ];
$policyHolderTel = $_POST[ 'form' ][ 'PolicyHolderTel' ];
$policyHolderEmail = $_POST[ 'form' ][ 'PolicyHolderEmail' ];
$originCountry = strtoupper( $_POST[ 'form' ][ 'OriginCountry' ][ 0 ] );

if ($_POST[ 'form' ][ 'PremiumType' ][ 0 ] == 'Individual' ) {
	$premiumType = "Individual";
} else {
	$premiumType = "Family";
}

$planID = $_POST[ 'planchoicefull' ];

$rrarday = explode( '/', $_POST[ 'form' ][ 'DepartureDate' ] );
$departureDate = $rrarday[ 1 ] . '/' . $rrarday[ 0 ] . '/' . $rrarday[ 2 ] . ' 23:00:00';
$rrarday = explode( '/', $_POST[ 'form' ][ 'ReturnDate' ] );
$returnDate = $rrarday[ 1]  . '/' . $rrarday[ 0 ] . '/' . $rrarday[ 2 ] . ' 23:59:00';
$trial = $_POST[ 'form' ][ 'Trial' ] == 0 ? 0 : 1;
$itinerary = '';

// Initiate member list
$insuredPersons = array();

for ( $i == 1; $i <= 10; $i++ ) {
	if ( $_POST[ 'form' ][ 'FullName' . $i ] != '' && $_POST[ 'form' ][ 'MemberType' . $i ] != '' ) {
		$RentalCarFrom = $RentalCarTo = '';
		if ( $_POST[ 'form' ][ 'PersonRentalCar' . $i ][ 0 ] == 'Yes' ) {
			$rrarday = explode( '/', $_POST[ 'form' ][ $i . 'dayfrom' ] );
			$RentalCarFrom = $rrarday[ 1 ] . '/' . $rrarday[ 0 ] . '/' . $rrarday[ 2 ] . ' 23:00:59';
			$rrarday = explode( '/', $_POST[ 'form' ][ $i . 'dayto' ] );
			$RentalCarTo = $rrarday[ 1 ] . '/' . $rrarday[ 0 ] . '/' . $rrarday[ 2 ] . ' 23:58:00';
		}
		$rrarday = explode( '/', $_POST[ 'form' ][ 'DateOfBrirth' . $i ] );
		$insuredPersons[] = array(
			'FullName' => $_POST[ 'form' ][ 'FullName' . $i ],
			'DateOfBirth' => $_POST[ 'form' ][ 'DateOfBrirth' . $i ][ 'm' ] . '/' . $_POST[ 'form' ][ 'DateOfBrirth' . $i ][ 'd' ] . '/' . $_POST[ 'form' ][ 'DateOfBrirth' . $i ][ 'y' ],
			'Gender' => $_POST[ 'form' ][ 'Gender' . $i ][ 0 ],
			'Passport' => $_POST[ 'form' ][ 'Passport' . $i ],
			'MemberType' => $_POST[ 'form' ][ 'MemberType' . $i ][ 0 ],
			'RentalCarProtection' => $_POST[ 'form' ][ 'PersonRentalCar' . $i ][ 0 ],
			'RentalCarFrom' => $RentalCarFrom,
			'RentalCarTo' => $RentalCarTo
		);
	}
}
$list = $client->call( 'issueCertificate', array( 
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
) );
file_put_contents(__DIR__ . "/textaa.txt", $trial );
$err = $client->getError();
if ( $err ) {
	echo '<h2>Error Found While Connecting</h2><pre>' . $err . '</pre>\n';
} else {
	$list[ 'TotalPrice' ] = number_format( ( $list[ 'TotalPrice' ] * 1000 ) ) . ' VND';
	echo json_encode( $list );
}
?>