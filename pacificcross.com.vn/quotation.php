<?php
	$token = $_GET[ 't' ];
	require_once( 'lib/nusoap.php' );
	$client = new nusoap_client( "http://bluecrossvietnam.com.vn/webservice/ws_pcv.php?wsdl" );
	$result = $client->call( 'getQuotation',
		array(
			'QuoteToken' => "$token"
		)
	);
	$carRental = 'No';
	if ( strlen( $result[ 'PlanID' ] ) == 5 ) {
		$areaOfCover = 'WORLDWIDE';
		switch( $result[ 'PlanID' ][ 1 ] ) {
			case 'A':
				$areaOfCover = 'ASIA';
				break;
			case 'E':
				$areaOfCover = 'ASEAN';
				break;
		}
		$incidental = 'Yes';
		if ( $result[ 'PlanID' ][ 3 ] == 'N' ) {
			$incidental = 'No';
		}
		if ( $result[ 'PlanID' ][ 4 ] == 'C' ) {
			$carRental = 'Yes';
		}
		$travelType = 'Travel Flex';
	} elseif ( strlen( $result[ 'PlanID' ] ) == 2 ) {
		if ( $result[ 'PlanID' ][ 1 ] == 'C' ) {
			$carRental = 'Yes';
		}
		$travelType = 'Bon Voyage';
	}
	$nQuote = count( $result[ 'InsuredPersons' ] );
?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Pacific Cross Vietnam</title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://bluecrossvietnam.com.vn/img/bootstrap.min.css">
  <script src="http://bluecrossvietnam.com.vn/img/jquery.min.js"></script>
  <script src="http://bluecrossvietnam.com.vn/img/bootstrap.min.js"></script>
  <style>
	.jumbotron {
	  margin-top: 60px;
	}
	.sz20 {
		font-size: 20px;
	}
	.sz18 {
		font-size: 18px;
	}
	.td {
		font-size: 16px;
	}
	.btn-buy {
		border-radius: 3px;
		background-color: white;
		color: #337ab7;
		border: 2px solid #337ab7;
		margin-top: 40px;
	}
	.table th, .table td{
    border: #337ab7 solid 1px !important;
	}
	.thumbnail {
		margin-bottom: -10px;
	}
	.panel {
		margin-bottom: 2px;
	}
	.jumbotron p {
    margin-bottom: 6px;
	}
	@media screen and (max-width: 768px) {
		.container .jumbotron, .container-fluid .jumbotron {
				padding-right: 30px;
				padding-left: 30px;
		}
		.jumbotron p {
			font-size: 2.1vmin;
		}
		.thumbnail {
			margin-top: -18px;
		}
		.jumbotron {
			margin-top: 30px;
		}
		h2 {
			font-size: 3vmin;
		}
		.panel-heading {
			padding: 5px 15px;
		}
		.sz20 {
			font-size: 2.3vmin;
		}
		.sz18 {
			font-size: 1.8vmin;
		}
		.td {
			font-size: 2.1vmin;
		}
		.btn-buy {
			margin-top: 20px;
			margin-bottom: -25px;
		}
	}
	@media screen and (max-width: 425px) {
		.jumbotron {
			margin-top: 15px;
			margin-bottom: 15px;
		}
		.thumbnail {
			margin-top: -18px;
		}
		.container .jumbotron, .container-fluid .jumbotron {
				padding-right: 10px;
				padding-left: 10px;
		}
		h2 {
			font-size: 3.7vmin;
		}
		.sz20 {
			font-size: 2.5vmin;
		}
		.panel-heading {
			padding: 5px 5px;
		}
		.jumbotron p {
			font-size: 3vmin;
			margin-left: -30px;
		}
		.col-xs-4 {
			margin-left: 10px;
		}
		.sz18 {
			font-size: 3vmin;
		}
		.td {
			font-size: 3vmin;
		}
	}
	@media screen and (max-width: 320px) {
		.sz18 {
			font-size: 3.5vmin;
		}
		.td {
			font-size: 3.5vmin;
		}
		.jumbotron p {
			font-size: 3.5vmin;
		}
		.sz20 {
			font-size: 3.5vmin;
		}
	}
  </style>
</head>

<body>
<a href="http://selectpdf.com/save-as-pdf/"><img src="http://selectpdf.com/buttons/save-as-pdf2.gif"/></a>
<div class="container">
  <div class="jumbotron">
		<div class="thumbnail">
			<a href="#"><img style="width:300px;" src="http://pacificcross.com.vn/images/logo.png" class="img-responsive" alt="Logo Pacific Cross Vietnam" style="display:block" width="60%"></a>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<h2 class="text-center"><strong>Travel Insurance Quotation - <?php echo $travelType?></strong></h2>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="row panel panel-primary">
					<div class="panel-heading sz20">Policy Holder Information</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Name</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18 szx">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $result[ 'PolicyHolder' ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Address</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $result[ 'PolicyHolderAddress' ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Email</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $result[ 'PolicyHolderEmail' ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Telephone</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $result[ 'PolicyHolderTel' ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Country of Origin</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $result[ 'OriginCountry' ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Premium Type</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $result[ 'PremiumType' ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Period of Insurance</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx">From <?php echo date( 'd-M-Y', strtotime( $result[ 'DepartureDate' ] ) ) ?> to <?php echo date( 'd-M-Y', strtotime( $result[ 'ReturnDate' ] ) ) ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="row panel panel-primary">
					<div class="panel-heading sz20">Plan Information</div>
				</div>
			</div>
		</div>
		<?php if ( strlen( $result[ 'PlanID' ] ) > 2 ) { ?>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Medical Plan</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx">Level <?php echo $result[ 'PlanID' ][ 0 ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Area of Cover</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $areaOfCover ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Personal Accident</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx">Level <?php echo $result[ 'PlanID' ][ 2 ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Incidental</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $incidental ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Car Rental</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $carRental ?></p>
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Plan</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx">Level <?php echo $result[ 'PlanID' ][ 0 ] ?></p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
					<p class="sz18"><strong>Car Rental</strong></p>
				</div>
				<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
					<p class="sz18">:</p>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-5">
					<p class="sz18 szx"><?php echo $carRental ?></p>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
				<div class="row panel panel-primary">
					<div class="panel-heading sz20">Personal Information</div>
					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="text-center sz18"><strong>#</strong></th>
									<th class="text-center sz18"><strong>Full Name</strong></th>
									<th class="text-center sz18"><strong>Gender</strong></th>
									<th class="text-center sz18"><strong>Birth Date</strong></th>
									<th class="text-center sz18"><strong>Passport</strong></th>
									<?php if ( $carRental == 'Yes' ) { ?>
									<th class="text-center sz18"><strong>Rental Car</strong></th>
									<th class="text-center sz18"><strong>Rental From</strong></th>
									<th class="text-center sz18"><strong>Rental To</strong></th>
									<?php } ?>
									<th class="text-center sz18"><strong>Age</strong></th>
									<th class="text-center sz18"><strong>Premium</strong></th>
								</tr>
							</thead>
							<tbody>
								<?php for( $i = 0; $i < $nQuote; $i++ ) { ?>
								<tr>
									<td class="text-center td"><?php echo ( $i + 1 ); ?></td>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'FullName' ] ?></td>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'Gender' ] ?></td>
									<td class="text-center td"><?php echo date( 'd-M-Y', strtotime( $result[ 'InsuredPersons' ][ $i ][ 'DateOfBirth' ] ) ) ?></td>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'Passport' ] ?></td>
									<?php if ( $carRental == 'Yes' ) { ?>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'RentalCarProtection' ] ?></td>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'RentalCarProtection' ] == "Yes" ? date( 'd-M-Y', strtotime( $result[ 'InsuredPersons' ][ $i ][ 'RentalCarFrom' ] ) ) : "" ?></td>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'RentalCarProtection' ] == "Yes" ? date( 'd-M-Y', strtotime( $result[ 'InsuredPersons' ][ $i ][ 'RentalCarTo' ] ) )  : "" ?></td>
									<?php } ?>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'Age' ] ?></td>
									<td class="text-center td"><?php echo $result[ 'InsuredPersons' ][ $i ][ 'Premium' ] === 0 ? 'INCLUDED' : number_format( $result[ 'InsuredPersons' ][ $i ][ 'Premium' ] * 1000 , 0, '.', ',') . ' VND' ?></td>
								</tr>
								<?php } ?>
								<tr>
									<?php if ( $carRental == 'Yes' ) { ?>
									<th colspan="9" class="text-center sz18"><strong>Total Premium</strong></td>
									<?php } else { ?>
									<th colspan="6" class="text-center sz18"><strong>Total Premium</strong></td>
									<?php } ?>
									<th class="text-center sz18"><strong><?php echo number_format( $result[ 'TotalPremium' ] * 1000 , 0, '.', ',') . ' VND' ?></strong></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!--
		<div class="row">
				<div class="text-center">
					<button type="button" class="btn btn-lg active btn-buy sz20" name="btnBuy" id="btnBuy"><strong>Click Here To Purchase</strong></button>
				</div>
			</div>
		</div>
		-->
  </div>
</div>
</body>
<script>
	// $( 'button' ).click( function() {
		// $('button').prop( 'disabled', true );
		// <?php
		// $list = $client->call( 'issueCertificate',
			// array(
				// 'token' => "7e57e5d8447cfd5a12a770569d63c5cd19dd146321e88ba71ded94d986cb3a03fbde4229446b3317357880645b07e651",
				// 'PNRNo' => "",
				// 'refNo' => "",
				// 'policyHolder' => $result[ 'PolicyHolder' ],
				// 'policyHolderAddress' => $result[ 'PolicyHolderAddress' ],
				// 'policyHolderTel' => $result[ 'PolicyHolderTel' ],
				// 'policyHolderEmail' => $result[ 'PolicyHolderEmail' ],
				// 'originCountry' => $result[ 'OriginCountry' ],
				// 'planID' => $result[ 'PlanID' ],
				// 'itinerary' => "",
				// 'premiumType' => $result[ 'PremiumType' ],			
				// 'departureDate' => date( 'm/d/Y', strtotime( $result[ 'DepartureDate' ] ) ),
				// 'returnDate' => date( 'm/d/Y', strtotime( $result[ 'ReturnDate' ] ) ),
				// 'insuredPersons' => $result[ 'InsuredPersons' ],
				// 'trial' => 0,
			// )
		// );
		// ?>
		// alert( 'Thank your for your purchase! We will send you an email which attached the certificate.' );
	// });
</script>
</html>