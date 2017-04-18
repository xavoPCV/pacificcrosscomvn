<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

class RSFormController extends JControllerLegacy {
	public function benersingle( $deq = NULL ) {
		require_once JPATH_SITE . '/components/com_rsform/benefit.php';
		
		$get = jrequest::get( 'get' );
		$data = json_decode( base64_decode( file_get_contents( JPATH_ROOT . '/datatmp/' . $get[ 'data' ] ) ) );
		if ( $data != '' ) {
			unlink( JPATH_ROOT . '/datatmp/' . $get[ 'data' ] );
		}
		$data = ( array ) $data;
		// check user same benerfit
		if ( $deq == '' ) {
			for ( $i = 1; $i <= $data[ 'number' ]; $i++ ) {
				if ( $i == 1 ) {
					$ext = '';
				} else {
					$ext = $i;
				}
				if ( $data[ 'uage' . $ext ] == '' ) {
					continue;
				}
				$allplan[ $data[ 'product' . $ext ] ][ 'Name0' ][] = $data[ 'name' . $ext ];
				$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ] = [];
				// Foundation
				if ( $data[ 'product' . $ext ] == 'Foundation - Standard' || $data[ 'product' . $ext ] == 'Foundation - Executive' || $data[ 'product' . $ext ] == 'Foundation - Premier' ) {
					if ( $data[ 'fsoutpatient' . $ext ] == 'Outpatient - Standard' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name31'][] = $data[ 'name' . $ext ];
					} elseif ( $data[ 'fsoutpatient' . $ext ] == 'Outpatient - Executive' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name36'][] = $data[ 'name' . $ext ];
					} elseif ( $data[ 'fsoutpatient' . $ext ] == 'Outpatient - Premier' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name41'][] = $data[ 'name' . $ext ];
					}
					if ( $data[ 'fsoptions' . $ext ] == 'Dental benefit 1 VND5,000,000' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' ][] = $data[ 'name' . $ext ];
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ][] = 55;
					} elseif ( $data[ 'fsoptions' . $ext ] == 'Dental benefit 2 VND10,000,000' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' ][] = $data[ 'name' . $ext ];
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ][] = 56;
					}
				}
				// Master
				$m = 0;
				if ( $data[ 'product' . $ext ] == 'Master - M1+' ) {
					$m = 1;
				} elseif ( $data[ 'product' . $ext ] == 'Master - M2' ) {
					$m = 2;
				} elseif ( $data[ 'product' . $ext ] == 'Master - M3' ) {
					$m = 3;
				}
				if ( $m > 0 ) {
					if ( !in_array( 'Outpatient Exclude (-30%)', $data[ 'm' . $m . 'discount' . $ext ] ) && $data[ 'm' . $m . 'discount' . $ext ] != 'Outpatient Exclude (-30%)' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name31'][] = $data[ 'name' . $ext ];
						// $allplan[ $data[ 'product' . $ext ] ][ 'Name46'][] = $data[ 'name' . $ext ];
					}
					$isName53 = 0;
					if ( $data[ 'm' . $m . 'options' . $ext ] != '' ) {
						$isName53 = 1;
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ][] = 55;
					}
					if ( $data[ 'm' . $m . 'personalaccidentbenefit' . $ext ] != '' ) {
						$isName53 = 1;
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ][] = 57;
						if ( $get[ 'lang' ] == 'vi' ) {
							$allplan[ $data[ 'product' . $ext ] ][ 'PA' . $data[ 'name' . $ext ] ] = str_replace( ',', '.', number_format( $data[ 'm'. $m . 'personalaccidentbenefit' . $ext ] ) );
						} else {
							$allplan[ $data[ 'product' . $ext ] ][ 'PA' . $data[ 'name' . $ext ] ] = number_format( $data[ 'm'. $m . 'personalaccidentbenefit' . $ext ] );
						}
					}
					if ( $data[ 'm' . $m . 'option2s' . $ext ] == 'Lifestyle Upgrade 1' ) {
						$isName53 = 1;
						array_push( $allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ], 58, 59, 60, 61, 62 );
					} elseif ( $data[ 'm' . $m . 'option2s' . $ext ] == 'Lifestyle Upgrade 2' ) {
						$isName53 = 1;
						array_push( $allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ], 63, 64, 65, 66, 67 );
					}
					if ( $isName53 == 1 ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' ][] = $data[ 'name' . $ext ];
					}
				}
				// Senior
				if ( $data[ 'product' . $ext ] == 'Senior Plan_SM1' || $data[ 'product' . $ext ] == 'Senior Plan_SM2' || $data[ 'product' . $ext ] == 'Senior Plan_SM3' ) {
					if ( !in_array( 'Outpatient Exclude (-30%)', $data[ 'spdiscount' . $ext ] ) && $data[ 'spdiscount' . $ext ] != 'Outpatient Exclude (-30%)' ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name31'][] = $data[ 'name' . $ext ];
						// $allplan[ $data[ 'product' . $ext ] ][ 'Name46'][] = $data[ 'name' . $ext ];
					}
					$isName53 = 0;
					if ( $data[ 'spoptions' . $ext ] != '' ) {
						$isName53 = 1;
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ][] = 55;
					}
					if ( $data[ 'sppersonalaccidentbenefit' . $ext ] != '' ) {
						$isName53 = 1;
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' . $data[ 'name' . $ext ] ][] = 57;
						if ( $get[ 'lang' ] == 'vi' ) {
							$allplan[ $data[ 'product' . $ext ] ][ 'PA' . $data[ 'name' . $ext ] ] = str_replace( ',', '.', number_format( $data[ 'sppersonalaccidentbenefit' . $ext ] ) );
						} else {
							$allplan[ $data[ 'product' . $ext ] ][ 'PA' . $data[ 'name' . $ext ] ] = number_format( $data[ 'sppersonalaccidentbenefit' . $ext ] );
						}
					}
					if ( $isName53 == 1 ) {
						$allplan[ $data[ 'product' . $ext ] ][ 'Name53' ][] = $data[ 'name' . $ext ];
					}
				}
			}
		}
		$name = [ 0, 31, 36, 41, 53 ];
		$header = [ 3, 25, 32, 33, 37, 38, 42, 43, 46 ];
		foreach ( $allplan as $product => $plan ) {
			$bnf = $benefit[ $get[ 'lang' ] ][ $data[ 'currencyunit' ] ][ $product ];
			$bnfTitle = $benefitTitle[ $get[ 'lang' ] ];
			$nameExist = [ 0 ];
			if ( empty( $plan[ 'Name31' ] ) ) {
				$bnf[ 31 ] = 'No';
				$bnf[ 32 ] = 'No';
				$bnf[ 33 ] = 'No';
				$bnf[ 34 ] = 'No';
				$bnf[ 35 ] = 'No';
				$bnf[ 46 ] = 'No';
				$bnf[ 47 ] = 'No';
				$bnf[ 48 ] = 'No';
				$bnf[ 49 ] = 'No';
				$bnf[ 50 ] = 'No';
				$bnf[ 51 ] = 'No';
				$bnf[ 52 ] = 'No';
			} else {
				$nameExist[] = 31;
			}
			if ( empty( $plan[ 'Name36' ] ) ) {
				$bnf[ 36 ] = 'No';
				$bnf[ 37 ] = 'No';
				$bnf[ 38 ] = 'No';
				$bnf[ 39 ] = 'No';
				$bnf[ 40 ] = 'No';
			} else {
				$nameExist[] = 36;
			}
			if ( empty( $plan[ 'Name41' ] ) ) {
				$bnf[ 41 ] = 'No';
				$bnf[ 42 ] = 'No';
				$bnf[ 43 ] = 'No';
				$bnf[ 44 ] = 'No';
				$bnf[ 45 ] = 'No';
			} else {
				$nameExist[] = 41;
			}
			for ( $i = 1; $i < count( $nameExist ); $i++ ) {
				if ( $plan[ 'Name' . $nameExist[ $i ] ] == $plan[ 'Name' . $nameExist[ $i - 1 ] ] ) {
					$bnf[ $nameExist[ $i ] ] = 'No';
				}
			}
			?>
			<table style="width:100%;margin-top: 40px;" cellpadding="10" > <?php
			for ( $i = 0; $i < 53; $i++ ) {
				if ( $get[ 'lang' ] == 'vi' ) {
					$bnf[ $i ] = str_replace( ',', '.', $bnf[ $i ] );
				}
				if ( in_array( $i, $name ) ) {
					$bgcolor = 'background-color:#1d4576;color: #ffffff';
				} elseif ( in_array( $i, $header ) ) {
					$bgcolor = 'background-color:#0050A4;color: #ffffff;';
				} elseif ( $bgcolor == 'background-color:#DBE6F5' ) {
					$bgcolor = 'background-color:#c2daf3';
				} else {
					$bgcolor = 'background-color:#DBE6F5';
				}
				if ( $bnf[ $i ] != 'No' && $bnf[ $i ] != 'Không' ) {
					if ( $bnf[ $i ] == '' ) { ?>
						<tr style="<?php echo $bgcolor ?>" >
							<td colspan=2 style="width:100%; text-align:justify"><?php echo $bnfTitle[ $i ] . implode( ', ', $plan[ 'Name' . $i ] ); ?></td>
						</tr><?php
					} else { ?>
						<tr style="<?php echo $bgcolor ?>" >
							<td style="width: 70%; text-align:justify"><?php echo $bnfTitle[ $i ] . implode( ', ', $plan[ 'Name' . $i ] ); ?></td>
							<td valign="center" style="width:30%; text-align:center"><?php echo $bnf[ $i ]; ?></td>
						</tr><?php
					}
				}
			}
			foreach ( $plan[ 'Name53' ] as $nameInsured ) { ?>
				<tr style="background-color:#1d4576;color: #ffffff" >
					<td colspan=2 style="width: 100%"><?php echo $bnfTitle[ 53 ] . $nameInsured; ?></td>
				</tr>
				<tr style="background-color:#0050A4;color: #ffffff" >
					<td colspan=2 style="width: 100%"><?php echo $bnfTitle[ 54 ]; ?></td>
				</tr><?php
				foreach( $plan [ 'Name53' . $nameInsured ] as $i ) {
					if ( $get[ 'lang' ] == 'vi' ) {
						$bnf[ $i ] = str_replace( ',', '.', $bnf[ $i ] );
					}
					if ( $bgcolor == 'background-color:#DBE6F5' ) {
						$bgcolor = 'background-color:#c2daf3';
					} else {
						$bgcolor = 'background-color:#DBE6F5';
					}
					if ( $bnf[ $i ] != 'No' && $bnf[ $i ] != 'Không' ) { ?>
					<tr style="<?php echo $bgcolor ?>" >
						<td style="width: 70%; text-align:justify"><?php echo $bnfTitle[ $i ]; ?></td> <?php
						if ( $i == 57 ) { ?>
						<td valign="center" style="width: 30%;text-align:center;"><?php echo $plan[ 'PA' . $nameInsured ]; ?></td> <?php
						} else { ?>
						<td valign="center" style="width: 30%;text-align:center;"><?php echo $bnf[ $i ]; ?></td> <?php
						} ?>
					</tr> <?php
					}
				}
			} ?>
			</table><?php
		}
		if ( $deq == '' ) {
			die;
		} else {
			return;
		}
	}

	public function benerdouble() {
		require_once JPATH_SITE . '/components/com_rsform/benefit.php';
		
		$get = jrequest::get( 'get' );
		$data = json_decode( base64_decode( file_get_contents( JPATH_ROOT . '/datatmp/' . $get[ 'data' ] ) ) );
		
		if ( $data != '' ) {
			unlink( JPATH_ROOT . '/datatmp/' . $get[ 'data' ] );
		}
		$data = ( array ) $data;
		$name = [ 31, 36, 41, 53 ];
		$header = [ 0, 3, 25, 32, 33, 37, 38, 42, 43, 46, 54 ];
		for ( $iPerson = 1; $iPerson <= $data[ 'checkcomparegroup' ]; $iPerson++ ) {
			$compare = [ $iPerson, $iPerson + $data[ 'checkcomparegroup' ] ];
			if ( $iPerson == 1 ) {
				$compare[ 0 ] = '';
			}
			$bnfTitle = $benefitTitle[ $get[ 'lang' ] ];
			$bnfTitle[ 0 ] .= $data[ 'name' . $compare[ 0 ] ];
			foreach ( $compare as $key => $value ) {
				$bnf[ $key ] = $benefit[ $get[ 'lang' ] ][ $data[ 'currencyunit' ] ][ $data[ 'product' . $value ] ];
				$bnf[ $key ][ 54 ] = 'No';
				// Foundation
				if ( $data[ 'product' . $value ] == 'Foundation - Standard' || $data[ 'product' . $value ] == 'Foundation - Executive' || $data[ 'product' . $value ] == 'Foundation - Premier' ) {
					if ( $data[ 'fsoutpatient' . $value ] == '' ) {
						$bnf[ $key ][ 32 ] = 'No';
						$bnf[ $key ][ 33 ] = 'No';
						$bnf[ $key ][ 34 ] = 'No';
						$bnf[ $key ][ 35 ] = 'No';
					} elseif ( $data[ 'fsoutpatient' . $value ] == 'Outpatient - Executive' ) {
						$bnf[ $key ][ 32 ] = $bnf[ $key ][ 37 ];
						$bnf[ $key ][ 33 ] = $bnf[ $key ][ 38 ];
						$bnf[ $key ][ 34 ] = $bnf[ $key ][ 39 ];
						$bnf[ $key ][ 35 ] = $bnf[ $key ][ 40 ];
					} elseif ( $data[ 'fsoutpatient' . $value ] == 'Outpatient - Premier' ) {
						$bnf[ $key ][ 32 ] = $bnf[ $key ][ 42 ];
						$bnf[ $key ][ 33 ] = $bnf[ $key ][ 43 ];
						$bnf[ $key ][ 34 ] = $bnf[ $key ][ 44 ];
						$bnf[ $key ][ 35 ] = $bnf[ $key ][ 45 ];
					}
					if ( $data[ 'fsoptions' . $value ] == '' ) {
						$bnf[ $key ][ 55 ] = 'No';
					} else{
						if ( $data[ 'fsoptions' . $value ] == 'Dental benefit 2 VND10,000,000' ) {
							$bnfTitle[ 55 ] = $bnfTitle[ 56 ];
							$bnf[ $key ][ 55 ] = $bnf[ $key ][ 56 ];
						}
						$bnf[ $key ][ 54 ] = '';
					}
					$bnf[ $key ][ 56 ] = 'No';
				}
				for ( $i = 37; $i <= 45; $i++ ) {
					$bnf[ $key ][ $i ] = 'No';
				}
				// Master
				$m = 0;
				if ( $data[ 'product' . $value ] == 'Master - M1+' ) {
					$m = 1;
				} elseif ( $data[ 'product' . $value ] == 'Master - M2' ) {
					$m = 2;
				} elseif ( $data[ 'product' . $value ] == 'Master - M3' ) {
					$m = 3;
				}
				if ( $m > 0 ) {
					if ( in_array( 'Outpatient Exclude (-30%)', $data[ 'm' . $m . 'discount' . $value ] ) || $data[ 'm' . $m . 'discount' . $value ] == 'Outpatient Exclude (-30%)'  ) {
						for ( $i = 31; $i <= 52; $i++ ) {
							$bnf[ $key ][ $i ] = 'No';
						}
					}
					if ( $data[ 'm' . $m . 'options' . $value ] == '' ) {
						$bnf[ $key ][ 55 ] = 'No';
					} else {
						$bnf[ $key ][ 54 ] = '';
					}
					if ( $data[ 'm' . $m . 'personalaccidentbenefit' . $value ] == '' ) {
						$bnf[ $key ][ 57 ] = 'No';
					} else {
						$bnf[ $key ][ 57 ] = number_format( $data[ 'm' . $m . 'personalaccidentbenefit' . $value ] );
						$bnf[ $key ][ 54 ] = '';
					}
					if ( $data[ 'm' . $m . 'option2s' . $value ] == '' ) {
						$bnf[ $key ][ 58 ] = 'No';
						$bnf[ $key ][ 59 ] = 'No';
						$bnf[ $key ][ 60 ] = 'No';
						$bnf[ $key ][ 61 ] = 'No';
						$bnf[ $key ][ 62 ] = 'No';
					} else {
						if ( $data[ 'm' . $m . 'option2s' . $value ] == 'Lifestyle Upgrade 2' ) {
							$bnf[ $key ][ 58 ] = $bnf[ $key ][ 63 ];
							$bnf[ $key ][ 59 ] = $bnf[ $key ][ 64 ];
							$bnf[ $key ][ 60 ] = $bnf[ $key ][ 65 ];
							$bnf[ $key ][ 61 ] = $bnf[ $key ][ 66 ];
							$bnf[ $key ][ 62 ] = $bnf[ $key ][ 67 ];
						}
						$bnf[ $key ][ 54 ] = '';
					}
					for ( $i = 63; $i <= 67; $i++ ) {
						$bnf[ $key ][ $i ] = 'No';
					}
				}
				// Senior
				if ( $data[ 'product' . $value ] == 'Senior Plan_SM1' || $data[ 'product' . $value ] == 'Senior Plan_SM2' || $data[ 'product' . $value ] == 'Senior Plan_SM3' ) {
					if ( in_array( 'Outpatient Exclude (-30%)', $data[ 'spdiscount' . $value ] ) || $data[ 'spdiscount' . $value ] == 'Outpatient Exclude (-30%)' ) {
						for ( $i = 31; $i <= 52; $i++ ) {
							$bnf[ $key ][ $i ] = 'No';
						}
					}
					if ( $data[ 'spoptions' . $value ] == '' ) {
						$bnf[ $key ][ 55 ] = 'No';
					} else {
						$bnf[ $key ][ 54 ] = '';
					}
					if ( $data[ 'sppersonalaccidentbenefit' . $value ] == '' ) {
						$bnf[ $key ][ 57 ] = 'No';
					} else {
						$bnf[ $key ][ 57 ] = number_format( $data[ 'sppersonalaccidentbenefit' . $value ] );
						$bnf[ $key ][ 54 ] = '';
					}
				}
			}
			if ( $bnf[ 0 ][ 54 ] == '' || $bnf[ 1 ][ 54 ] == '' ) {
				$bnf[ 0 ][ 54 ] = '';
				$bnf[ 1 ][ 54 ] = '';
			} ?>
			<table style="width:100%;margin-top: 40px;" cellpadding="10" > <?php
			for ( $i = 0; $i <= 67; $i++ ) {
				if ( $get[ 'lang' ] == 'vi' ) {
					$bnf[ 0 ][ $i ] = str_replace( ',', '.', $bnf[ 0 ][ $i ] );
					$bnf[ 1 ][ $i ] = str_replace( ',', '.', $bnf[ 1 ][ $i ] );
				}
				if ( in_array( $i, $name ) ) {
					$bnf[ 0 ][ $i ] = 'No';
					$bnf[ 1 ][ $i ] = 'No';
				} elseif ( in_array( $i, $header ) ) {
					$bgcolor = 'background-color:#0050A4;color: #ffffff;';
				} elseif ( $bgcolor == 'background-color:#DBE6F5' ) {
					$bgcolor = 'background-color:#c2daf3';
				} else {
					$bgcolor = 'background-color:#DBE6F5';
				}
				if ( ( $bnf[ 0 ][ $i ] != 'No' && $bnf[ 0 ][ $i ] != 'Không' ) || ( $bnf[ 1 ][ $i ] != 'No' && $bnf[ 1 ][ $i ] != 'Không' ) ) {
					if ( $bnf[ 0 ][ $i ] == '' && $bnf[ 1 ][ $i ] == '' ) { ?>
						<tr style="<?php echo $bgcolor ?>" >
							<td colspan=3 style="width: 100%; text-align:justify"><?php echo $bnfTitle[ $i ]; ?></td>
						</tr><?php
					} else { ?>
						<tr style="<?php echo $bgcolor ?>" >
							<td style="width: 60%; text-align:justify"><?php echo $bnfTitle[ $i ]; ?></td>
							<td valign="center" style="width: 20%;text-align:center;"><?php echo $bnf[ 0 ][ $i ]; ?></td>
							<td valign="center" style="width: 20%;text-align:center;"><?php echo $bnf[ 1 ][ $i ]; ?></td>
						</tr><?php
					}
				}
			} ?>
			</table><?php
		}
		die;
	}

	public function captcha() {
		require_once JPATH_SITE . '/components/com_rsform/helpers/captcha.php';

		$componentId = JFactory::getApplication()->input->getInt( 'componentId' );
		$captcha = new RSFormProCaptcha( $componentId );

		JFactory::getSession()->set( 'com_rsform.captcha.' . $componentId, $captcha->getCaptcha() );
		JFactory::getApplication()->close();
	}

	public function excelupload() {
		$allowedExts = array( "xlsx", "xls", "csv" );
		$temp = explode( ".", $_FILES[ "imspf" ][ "name" ] );

		$extension = end( $temp );

		if ( ( $_FILES[ "imspf" ][ "size" ] < 200000 ) && in_array( $extension, $allowedExts ) ) {
			if ( $_FILES[ "imspf" ][ "error" ] > 0 ) {
				echo "Return Code: " . $_FILES[ "imspf" ][ "error" ] . "<br>";
			} else {
				jimport( 'joomla.filesystem.file' );
				jimport( 'joomla.filesystem.folder' );
				$fieldName = 'imspf';
				$fileName = $_FILES[ $fieldName ][ 'name' ];
				$uploadedFileNameParts = explode( '.', $fileName );
				$duoifile = $uploadedFileNameParts[ 1 ];
				$fileName = preg_replace( "/[^A-Za-z0-9]/i", "-", $fileName );
				$fn = rand( 0, 10000000000 ) . "." . $duoifile;
				$uploadPath = JPATH_SITE . DS . 'tmp' . DS . $fn;
				$fileTemp = $_FILES[ $fieldName ][ 'tmp_name' ];
				if ( !JFile::upload( $fileTemp, $uploadPath ) ) {
					echo JText::_( 'ERROR MOVING FILE' );
					die;
				} else {
					require_once( __DIR__ . '/PHPExcel.php' );
					$excelFile = $uploadPath;
					$pathInfo = pathinfo( $excelFile );
					$type = $pathInfo[ 'extension' ] == 'xlsx' ? 'Excel2007' : 'Excel5';

					$objReader = PHPExcel_IOFactory::createReader( $type );
					$objPHPExcel = $objReader->load( $excelFile );
					$objPHPExcel->setActiveSheetIndex( 0 );

					foreach ( $objPHPExcel->setActiveSheetIndex( 0 )->getRowIterator() as $row ) {
						$cellIterator = $row->getCellIterator();
						$cellIterator->setIterateOnlyExistingCells( false );

						foreach ( $cellIterator as $cell ) {
							if ( !is_null( $cell ) ) {
								$value = $cell->getCalculatedValue();
								if ( $value != '' ) {
									$asd[] = $value;
								}
							}
						}
					}

					$m = $k = 0;
					$ar1 = '';
					foreach ( $asd as $r ) {
						$r = str_replace( '/', '-', $r );
						if ( $k > 3 ) {
							$m++;
							if ( $m == 1 ) {
								$ar1[ 'name' ] = $r;
							} elseif ( $m == 2 ) {
								$ar1[ 'y' ] = date( 'Y', strtotime( $r ) );
								$ar1[ 'm' ] = date( 'm', strtotime( $r ) );
								$ar1[ 'd' ] = date( 'd', strtotime( $r ) );
							} else if ( $m == 3 ) {
								$ar1[ 'smoker' ] = $r;
								$ar2[] = $ar1;
								$m = 0;
								$ar1 = '';
							}
						}
						$k++;
					}
					print_r( json_encode( $ar2 ) );
					die;
				}
			}
		} else {
			echo "Invalid file";
		}
		die;
	}

	public function plugin() {
		JFactory::getApplication()->triggerEvent( 'rsfp_f_onSwitchTasks' );
	}

	/* deprecated */
	public function showForm() {}

	public function submissionsViewFile() {
		$db = JFactory::getDbo();
		$secret = JFactory::getConfig()->get( 'secret' );
		$hash = JFactory::getApplication()->input->getCmd( 'hash' );

		// Load language file
		JFactory::getLanguage()->load( 'com_rsform' , JPATH_ADMINISTRATOR );

		if ( strlen( $hash ) != 32 ) {
			JError::raiseError( 500, JText::_( 'RSFP_VIEW_FILE_NOT_FOUND' ) );
		}

		$db->setQuery( "SELECT * FROM #__rsform_submission_values WHERE MD5(CONCAT(SubmissionId,'" . $db->escape( $secret ) . "',FieldName)) = '" . $hash . "'" );
		if ( $result = $db->loadObject() ) {
			// Check if it's an upload field
			$db->setQuery( "SELECT c.ComponentTypeId FROM #__rsform_properties p LEFT JOIN #__rsform_components c ON (p.ComponentId=c.ComponentId) WHERE p.PropertyName='NAME' AND p.PropertyValue='" . $db->escape( $result->FieldName ) . "' AND c.FormId='" . ( int ) $result->FormId . "'" );
			$type = $db->loadResult();
			if ( $type != 9 ) {
				JError::raiseError( 500, JText::_( 'RSFP_VIEW_FILE_NOT_UPLOAD' ) );
			}

			if ( file_exists( $result->FieldValue ) ) {
				RSFormProHelper::readFile( $result->FieldValue );
			}
		} else {
			JError::raiseError( 500, JText::_( 'RSFP_VIEW_FILE_NOT_FOUND' ) );
		}
	}

	public function ajaxValidate() {
		$db = JFactory::getDbo();
		$form = JRequest::getVar( 'form' );
		$formId = ( int ) @$form[ 'formId' ];

		$db->setQuery( "SELECT ComponentId, ComponentTypeId FROM #__rsform_components WHERE `FormId`='" . $formId . "' AND `Published`='1' ORDER BY `Order`" );
		$components = $db->loadObjectList();

		$page = JRequest::getInt( 'page' );

		if ( $page ) {
			$current_page = 1;
			foreach ( $components as $i => $component ) {
				if ( $current_page != $page ) {
					unset($components[$i]);
				}
				if ( $component->ComponentTypeId == 41 ) {
					$current_page++;
				}
			}
		}

		$removeUploads = array();
		$formComponents = array();

		foreach ( $components as $component ) {
			$formComponents[] = $component->ComponentId;
			if ( $component->ComponentTypeId == 9 ) {
				$removeUploads[] = $component->ComponentId;
			}
		}

		echo implode( ',', $formComponents );

		echo "\n";

		$invalid = RSFormProHelper::validateForm( $formId );

		// Trigger Event - onBeforeFormValidation
		$mainframe = JFactory::getApplication();
		$post = JRequest::getVar( 'form', array(), 'post', 'none', JREQUEST_ALLOWRAW );
		$mainframe->triggerEvent( 'rsfp_f_onBeforeFormValidation', array( array( 'invalid'=>&$invalid, 'formId' => $formId, 'post' => &$post ) ) );

		if ( count( $invalid ) ) {
			foreach ( $invalid as $i => $componentId ) {
				if ( in_array( $componentId, $removeUploads ) ) {
					unset( $invalid[ $i ] );
				}
			}
			$invalidComponents = array_intersect( $formComponents, $invalid );
			echo implode(',', $invalidComponents);
		}

		if ( isset( $invalidComponents ) ) {
			echo "\n";
			$pages = RSFormProHelper::componentExists( $formId, 41 );
			$pages = count( $pages );

			if ( $pages && !$page ) {
				$first = reset( $invalidComponents );
				$current_page = 1;
				foreach ( $components as $i => $component ) {
					if ( $component->ComponentId == $first ) {
						break;
					}
					if ( $component->ComponentTypeId == 41 ) {
						$current_page++;
					}
				}
				echo $current_page;
				echo "\n";
				echo $pages;
			}
		}
		jexit();
	}

	public function confirm() {
		$db 	= JFactory::getDbo();
		$app	= JFactory::getApplication();
		$hash 	= $app->input->getCmd( 'hash' );

		if ( strlen( $hash) == 32 ) {
			$db->setQuery( "SELECT `SubmissionId` FROM `#__rsform_submissions` WHERE MD5(CONCAT(`SubmissionId`,`FormId`,`DateSubmitted`)) = '" . $db->escape( $hash ) . "' " );
			if ( $SubmissionId = $db->loadResult() ) {
				$db->setQuery( "UPDATE `#__rsform_submissions` SET `confirmed` = 1 WHERE `SubmissionId` = '" . ( int ) $SubmissionId . "'" );
				$db->execute();

				$app->triggerEvent( 'rsfp_f_onSubmissionConfirmation', array( array( 'SubmissionId' => $SubmissionId, 'hash' => $hash ) ) );

				JError::raiseNotice( 200, JText::_( 'RSFP_SUBMISSION_CONFIRMED' ) );
			}
		} else {
			JError::raiseWarning( 500, JText::_( 'RSFP_SUBMISSION_CONFIRMED_ERROR' ) );
		}
	}
}