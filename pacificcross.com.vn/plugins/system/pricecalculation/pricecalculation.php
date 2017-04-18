<?php
// First start with information about the Plugin and yourself. For example:
/**
 * @package			Joomla.Plugin
 * @subpackage	System.onepay
 *
 * @copyright		Copyright
 * @license			License, for example GNU/GPL
 */

// To prevent accessing the document directly, enter this code:
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the component's router file (Replace 'nameofcomponent' with the component your providing the search for
// require_once JPATH_SITE .  '/components/nameofcomponent/helpers/route.php';

/**
 * All functions need to get wrapped in a class
 *
 * The class name should start with 'PlgSearch' followed by the name of the plugin. Joomla calls the class based on the name of the plugin, so it is very important that they match
 */
class PlgSystemPricecalculation extends JPlugin {
	/**
	 * Constructor
	 *
	 * @access		protected
	 * @param		object	$subject	The object to observe
	 * @param		array		$config		An array that holds the plugin configuration
	 * @since		1.6
	 */
	public function __construct( & $subject, $config ) {
        parent::__construct( $subject, $config );
        $this->loadLanguage();
	}
	
	public function onAjaxPricecalculation() {
		$jinput = JFactory::getApplication()->input;
		$post = $jinput->get( 'form', null, null );
		$arr = json_decode( $post[ 'data' ] );
		
		foreach ( $arr as $r ) {
			$r->name = str_replace( "form[", '', $r->name );
			$r->name = str_replace( "]", '', $r->name );
			$r->name = str_replace( "[", '', $r->name );
			$r->name = strtolower( $r->name );
			$arm[ $r->name ][] = $r->value;
		}
		foreach ( $arm as $key => $r ) {
			if ( count( $r ) == 1 ) {
				$ark[ $key ] = $r[ 0 ];
			} else {
				$ark[ $key ] = $r;
			}
		}
		$arm = $ark;
		
		$tatalrealnum = 0;
		for ( $i = 1; $i <= $arm[ 'number' ]; $i++ ) {
			$total = '';
			$totalsemi = '';
			$sum = '';
			
			if ( $i == 1) {
				if ( $arm[ 'uage' ] != '' &&  $arm[ 'product' ] != '' ) {
					$tatalrealnum++;
				}
			} else {
				if ($arm[ 'uage' . $i ] != '' &&  $arm[ 'product' . $i ] != '' ) {
					$tatalrealnum++;
				}
			}
		}
		
		if ( $arm[ 'checkcomparegroup' ] >= 1 ) {
			$tatalrealnum = $arm[ 'checkcomparegroup' ];
		}
		if ( $tatalrealnum >= 3 && $tatalrealnum < 5 ) {
			$this->discount_group = 'group_discount_3_4';
		} elseif ( $tatalrealnum >= 5 ) {
			$this->discount_group = 'group_discount_5_10';
		} elseif ( $tatalrealnum >= 11 ) {
			$this->discount_group = 'group_discount_11_20';
		} elseif ( $tatalrealnum >= 21 ) {
			$this->discount_group = 'group_discount_21';
		} else {
			$this->discount_group = '';
		}

		// is New Premium 2017 or not
		list( $d, $m, $y ) = explode( '.', $arm[ 'effectivedate' ] );
		$premium2017 = '_2017';
		if ( strcmp( $y . $m . $d, '20170401' ) < 0 ) {
			$premium2017 = '';
		}
		
		for ( $i = 1; $i <= $arm[ 'number' ]; $i++ ) {
			$total = '' ;
			$totalsemi = '';
			$sum = '';
			
			if ( $i == 1 ) {
				if ( $arm[ 'uage' ] != '' &&  $arm[ 'product' ] != '' ) {
					switch ( $arm[ 'product' ] ) {
						case 'Foundation - Standard':
							$total = $this->madetotalpl( $arm[ 'uage' ], $arm[ 'fsoutpatient' ], $arm[ 'fsoptions' ], $arm[ 'smoker' ], $premium2017 );
							break;
						case 'Foundation - Executive':
							$total = $this->madetotalp2( $arm[ 'uage' ], $arm[ 'fsoutpatient' ], $arm[ 'fsoptions' ], $arm[ 'smoker' ], $premium2017 );
							break;
						case 'Foundation - Premier':
							$total = $this->madetotalp3( $arm[ 'uage' ], $arm[ 'fsoutpatient' ], $arm[ 'fsoptions' ], $arm[ 'smoker' ], $premium2017 );
							break;
						case 'Master - M1+':
							$total = $this->madetotalp4( $arm[ 'uage' ], $arm[ 'm1upgradebenefits' ], $arm[ 'm1options' ], $arm[ 'm1option2s' ], $arm[ 'm1discount' ], $arm[ 'smoker' ], $arm[ 'm1personalaccidentbenefit' ], $premium2017 );
							break;
						case 'Master - M2':
							$total = $this->madetotalp5( $arm[ 'uage' ], $arm[ 'm2options' ], $arm[ 'm2option2s' ], $arm[ 'm2discount' ], $arm[ 'smoker' ], $arm[ 'm2personalaccidentbenefit' ], $premium2017 );
							break;
						case 'Master - M3':
							$total = $this->madetotalp6( $arm[ 'uage' ], $arm[ 'm3options' ], $arm[ 'm3option2s' ], $arm[ 'm3discount' ], $arm[ 'smoker' ], $arm[ 'm3personalaccidentbenefit' ], $premium2017 );
							break;
						case 'Senior Plan_SM1':
							$total = $this->madetotalp7( $arm[ 'uage' ], $arm[ 'spoptions' ], $arm[ 'spdiscount' ], $arm[ 'smoker' ], $arm[ 'sppersonalaccidentbenefit' ], $premium2017 );
							break;
						case 'Senior Plan_SM2':
							$total = $this->madetotalp8( $arm[ 'uage' ], $arm[ 'spoptions' ], $arm[ 'spdiscount' ], $arm[ 'smoker' ], $arm[ 'sppersonalaccidentbenefit' ], $premium2017 );
							break;
						case 'Senior Plan_SM3':
							$total = $this->madetotalp9( $arm[ 'uage' ], $arm[ 'spoptions' ], $arm[ 'spdiscount' ], $arm[ 'smoker' ], $arm[ 'sppersonalaccidentbenefit' ], $premium2017 );
							break;
					}
				}
			} else {
				if ( $arm[ 'uage' . $i ] != '' &&  $arm[ 'product' . $i ] != '' ) {
					switch ( $arm[ 'product' . $i ] ) {
						case 'Foundation - Standard':
							$total = $this->madetotalpl( $arm[ 'uage' . $i ], $arm[ 'fsoutpatient' . $i ], $arm[ 'fsoptions' . $i ], $arm[ 'smoker' . $i ], $premium2017 );
							break;
						case 'Foundation - Executive':
							$total = $this->madetotalp2( $arm[ 'uage' . $i ], $arm[ 'fsoutpatient' . $i ], $arm[ 'fsoptions' . $i ], $arm[ 'smoker' . $i ], $premium2017 );
							break;
						case 'Foundation - Premier':
							$total = $this->madetotalp3 ($arm[ 'uage' . $i ], $arm[ 'fsoutpatient' . $i ], $arm[ 'fsoptions' . $i ], $arm[ 'smoker' . $i ], $premium2017 );
							break;
						case 'Master - M1+':
							$total = $this->madetotalp4( $arm[ 'uage' . $i ], $arm[ 'm1upgradebenefits' . $i ], $arm[ 'm1options' . $i ], $arm[ 'm1option2s' . $i ], $arm[ 'm1discount' . $i ], $arm[ 'smoker' . $i ], $arm[ 'm1personalaccidentbenefit' . $i ], $premium2017 );
							break;
						case 'Master - M2':
							$total = $this->madetotalp5( $arm[ 'uage' . $i ], $arm[ 'm2options' . $i ], $arm[ 'm2option2s' . $i ], $arm[ 'm2discount' . $i ], $arm[ 'smoker' . $i ], $arm[ 'm2personalaccidentbenefit' . $i ], $premium2017 );
							break;
						case 'Master - M3':
							$total = $this->madetotalp6( $arm[ 'uage' . $i ], $arm[ 'm3options' . $i ], $arm[ 'm3option2s' . $i ], $arm[ 'm3discount' . $i ], $arm[ 'smoker' . $i ], $arm[ 'm3personalaccidentbenefit' . $i ], $premium2017);
							break;
						case 'Senior Plan_SM1':
							$total = $this->madetotalp7( $arm[ 'uage' . $i ], $arm[ 'spoptions' . $i ], $arm[ 'spdiscount' . $i ], $arm[ 'smoker' . $i ], $arm[ 'sppersonalaccidentbenefit' . $i ], $premium2017 );
							break;
						case 'Senior Plan_SM2':
							$total = $this->madetotalp8( $arm[ 'uage' . $i ], $arm[ 'spoptions' . $i ], $arm[ 'spdiscount' . $i ], $arm[ 'smoker' . $i ], $arm[ 'sppersonalaccidentbenefit' . $i ], $premium2017 );
							break;
						case 'Senior Plan_SM3':
							$total = $this->madetotalp9( $arm[ 'uage' . $i ], $arm[ 'spoptions' . $i ], $arm[ 'spdiscount' . $i ], $arm[ 'smoker' . $i ], $arm[ 'sppersonalaccidentbenefit' . $i ], $premium2017 );
							 break;
					}
				}
			}
			if ( $arm[ 'currencyunit' ] == "VND" ) {
				$total = round( $total * 1000, -3 );
				$totalsemi = round( $total * 0.52, -3 );
			} elseif ( $arm[ 'currencyunit' ] == "USD" ) {
				$total = round( ( $total * 1000 ) / $arm[ 'usexchange' ], 2 );
				$totalsemi = round( $total * 0.52, 2 );
			}
			
			$sum[ 'total' ] = $total;
			$sum[ 'totalsemi' ] =$totalsemi;
			$tsum[] = $sum;
		}
		echo json_encode( $tsum );
		die;
	}
	
	function madetotalpl( $age, $fsoutpatient, $fsoptions, $sm, $premium2017 ) {
		// get price default
		$pde = $this->getParamValueByAge( $age, 'plan1fs' . $premium2017 );
		
		if ( $fsoutpatient == 'Outpatient - Standard' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan1fs_fsoutpatient' . $premium2017 );
		}
		if ( $fsoutpatient == 'Outpatient - Executive' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan2fs_fsoutpatient' . $premium2017 );
		}
		if ( $fsoutpatient == 'Outpatient - Premier' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan3fs_fsoutpatient' . $premium2017 );
		}
		
		if ( $fsoptions != '' ) {
			if ( $fsoptions == 'Dental benefit 1 VND5,000,000' ) {
				$pfsoptions = $this->getConfigParam( 'fd_benefit1', 0 );
			}
			if ( $fsoptions == 'Dental benefit 2 VND10,000,000' ) {
				$pfsoptions =  $this->getConfigParam( 'fd_benefit2', 0 );
			}
		}
		if ( $this->discount_group != '' ) {
			$finalbase = ( $pde + $pfsoutpatient ) - ( $pde + $pfsoutpatient ) * $this->getConfigParam( $this->discount_group, 0 );
		} else {
			$finalbase = $pde + $pfsoutpatient;
		}
		
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			return ( $finalbase + $pfsoptions ) * $this->getConfigParam( 'config_smoker', 0 ) + ( $finalbase + $pfsoptions );
		} else {
			return ( $finalbase + $pfsoptions );
		}
	}
	
	function madetotalp2( $age, $fsoutpatient, $fsoptions, $sm, $premium2017 ) {
		// get price default
		$pde = $this->getParamValueByAge( $age, 'plan2fs' . $premium2017 );
		
		if ( $fsoutpatient == 'Outpatient - Standard' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan1fs_fsoutpatient' . $premium2017 );
		}
		if ( $fsoutpatient == 'Outpatient - Executive' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan2fs_fsoutpatient' . $premium2017 );
		}
		if ( $fsoutpatient == 'Outpatient - Premier' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan3fs_fsoutpatient' . $premium2017 );
		}
		
		if ( $fsoptions != '' ) {
			if ( $fsoptions == 'Dental benefit 1 VND5,000,000' ) {
				$pfsoptions = $this->getConfigParam( 'fd_benefit1' , 0 );
			}
			if ( $fsoptions == 'Dental benefit 2 VND10,000,000' ) {
				$pfsoptions = $this->getConfigParam( 'fd_benefit2' , 0 );
			}
		}
		if ( $this->discount_group != '' ) {
			$finalbase = ( $pde + $pfsoutpatient ) - ( $pde + $pfsoutpatient ) * $this->getConfigParam( $this->discount_group, 0 );
		} else {
			$finalbase = $pde + $pfsoutpatient;
		}
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			return ( $finalbase + $pfsoptions ) * $this->getConfigParam( 'config_smoker', 0 ) + ( $finalbase + $pfsoptions );
		} else {
			return ( $finalbase + $pfsoptions );
		}
	}
	
	function madetotalp3( $age, $fsoutpatient, $fsoptions, $sm, $premium2017 ) {
		 // get price default
		$pde = $this->getParamValueByAge( $age, 'plan3fs' . $premium2017);
		
		if ( $fsoutpatient == 'Outpatient - Standard' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan1fs_fsoutpatient' . $premium2017 );
		}
		if ( $fsoutpatient == 'Outpatient - Executive' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan2fs_fsoutpatient' . $premium2017 );
		}
		if ( $fsoutpatient == 'Outpatient - Premier' ) {
			$pfsoutpatient = $this->getParamValueByAge( $age, 'plan3fs_fsoutpatient' . $premium2017 );
		}
		
		if ( $fsoptions != '' ) {
			if ( $fsoptions == 'Dental benefit 1 VND5,000,000' ) {
				$pfsoptions = $this->getConfigParam( 'fd_benefit1', 0 );
			}
			if ( $fsoptions == 'Dental benefit 2 VND10,000,000' ) {
				$pfsoptions = $this->getConfigParam( 'fd_benefit2', 0 );
			}
		}
		if ( $this->discount_group != '' ) {
			$finalbase = ( $pde + $pfsoutpatient ) - ( $pde + $pfsoutpatient ) * $this->getConfigParam( $this->discount_group, 0 );
		} else {
			$finalbase = $pde + $pfsoutpatient;
		}
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			return ( $finalbase + $pfsoptions ) * $this->getConfigParam( 'config_smoker', 0 ) + ( $finalbase + $pfsoptions );
		} else {
		   return ( $finalbase + $pfsoptions );
		}
	}
	
	function madetotalp4( $age, $upgradebenefits, $options, $options2, $discount, $sm, $ac, $premium2017 ) {
		$pde = $this->getParamValueByAge( $age, 'plan1m' . $premium2017 );
		if ( $upgradebenefits != '' ) {
		   $pupgradebenefits = $this->getParamValueByAge( $age, 'plan1mup' . $premium2017 );
		}
		if ( $options != '' ) {
			if ( $premium2017 == '_2017' ) {
				if ( $age < 6 ) {
					$Dental = 3150;
				} elseif ( $age >= 6 ) {
					$Dental = 5775;
				}
			} else {
				if ( $age < 4 ) {
					$Dental = 3150;
				} elseif ( $age >= 4 ) {
					$Dental = 5775;
				}
			}
		}
		
		if ( $options2 == 'Lifestyle Upgrade 1' ) {
			$Lifestyle = 4494;
		}
		if ( $options2 == 'Lifestyle Upgrade 2' ) {
			$Lifestyle = 7035;
		}
		
		$Basicpremium = $pde + $pupgradebenefits + $Dental + $Lifestyle;
		if ( !is_array( $discount ) ) {
			$discount = array( '', $discount );
		}
		
		$tal = $out = $ded = $disc = 0;
		foreach ( $discount as $r ) {
			if ( $r == "Treatment Area Limit (-25%)" ) {
				$tal = 0.25;
			} elseif ( $r == "Co-payment (-25%)" ) {
				$disc = 0.25;
			} elseif ( $r == "Outpatient Exclude (-30%)" ) {
				$out = 0.3;
			} elseif ( $r == "VND50,000,000 Inpatient Deductible (20% discount)" ) {
				$ded = 0.2;
			}
		}
		if ( $this->discount_group != '' ) {
			$discount_group = $this->getConfigParam( $this->discount_group, 0 );
		} else {
			$discount_group = 0;
		}
		
		// {[Basic premium x (1-TAL dis – O/P exclusion dis – 20% co-payment dis)] + [Surgeon’s Fee x (1 - TAL dis – 20% co-payment dis)]} x (1 + loading) x (1 – group discount)
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			$pde = ( $pde * ( 1 - $tal - $out - $ded - $disc ) + $pupgradebenefits * ( 1 - $tal - $ded -$disc ) ) * ( 1 + $this->getConfigParam( 'config_smoker', 0 ) ) * ( 1 - $discount_group ) + $Dental + $Lifestyle + ( $ac * 28350 / 20000000 ) / 1000;
			return $pde;
		} else {
			$pde = ( $pde * ( 1 - $tal - $out - $ded - $disc ) + $pupgradebenefits * ( 1 - $tal - $ded -$disc ) ) * ( 1 - $discount_group ) + $Dental + $Lifestyle + ( $ac * 28350 / 20000000 ) / 1000;
			return $pde;
		}
	}
	
	function madetotalp5( $age, $options, $options2, $discount, $sm, $ac, $premium2017 ) {
		$pde = $this->getParamValueByAge( $age, 'plan2m' . $premium2017 );
		if ( $options != '' ) {
			if ( $premium2017 == '_2017' ) {
				if ( $age < 6 ) {
					$Dental = 3150;
				} elseif ( $age >= 6 ) {
					$Dental = 5775;
				}
			} else {
				if ( $age < 4 ) {
					$Dental = 3150;
				} elseif ( $age >= 4 ) {
					$Dental = 5775;
				}
			}
		}
		if ( $options2 == 'Lifestyle Upgrade 1' ) { 
			$Lifestyle = 4494;
		}
		if ( $options2 == 'Lifestyle Upgrade 2' ) {
			$Lifestyle = 7035;
		}
		
		if ( $this->discount_group != '' ) {
			$discount_group = $this->getConfigParam( $this->discount_group, 0 );
		} else {
			$discount_group = 0;
		}
		
		$disc = 0;
		if ( !is_array( $discount ) ) {
			$discount = array( '', $discount );
		}
		foreach ( $discount as $r ) {
			if ( $r == "Treatment Area Limit (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Co-payment (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Outpatient Exclude (-30%)" ) {
				$disc += 0.3;
			} elseif ( $r == "VND50,000,000 Inpatient Deductible (20% discount)" ) {
				$disc += 0.2;
			}
		}
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			$pde = ( $pde * ( 1 - $disc ) ) *( 1 + $this->getConfigParam( 'config_smoker', 0 ) ) * ( 1 - $discount_group ) + $Dental + $Lifestyle + ( $ac * 28350 / 20000000 ) / 1000;
			return $pde;
		} else {
			$pde = ( $pde * ( 1 - $disc ) ) * ( 1 - $discount_group ) + $Dental + $Lifestyle + ( $ac * 28350 / 20000000 ) / 1000;
			return $pde;
		}
	}
	
	function madetotalp6( $age, $options, $options2, $discount, $sm, $ac, $premium2017 ) {
		$pde = $this->getParamValueByAge( $age, 'plan3m' . $premium2017 );
		
		if ( $options != '' ) {
			if ( $premium2017 == '_2017' ) {
				if ( $age < 6 ) {
					$Dental = 3150;
				} elseif ( $age >= 6 ) {
					$Dental = 5775;
				}
			} else {
				if ( $age < 4 ) {
					$Dental = 3150;
				} elseif ( $age >= 4 ) {
					$Dental = 5775;
				}
			}
		}
		
		if ( $options2 == 'Lifestyle Upgrade 1' ) {
			$Lifestyle = 4494;
		}
		if ( $options2 == 'Lifestyle Upgrade 2' ) {
			$Lifestyle = 7035;
		}
		
		if ( $this->discount_group != '' ) {
			$discount_group = $this->getConfigParam( $this->discount_group, 0 );
		} else {
			$discount_group = 0;
		}
		
		$disc = 0;
		if ( !is_array( $discount ) ) {
			$discount = array( '', $discount );
		}		
		foreach ( $discount as $r ) {
			if ( $r == "Treatment Area Limit (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Co-payment (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Outpatient Exclude (-30%)" ) {
				$disc += 0.3;
			} elseif ( $r == "VND50,000,000 Inpatient Deductible (20% discount)" ) {
				$disc += 0.2;
			}
		}
		
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			$pde = ( $pde * ( 1 - $disc ) ) * ( 1 + $this->getConfigParam( 'config_smoker', 0 ) ) * ( 1 - $discount_group ) + $Dental + $Lifestyle + ( $ac * 28350 / 20000000 ) / 1000;
			return $pde;
		} else {
			$pde = ( $pde * ( 1 - $disc ) ) * ( 1 - $discount_group ) + $Dental + $Lifestyle + ( $ac * 28350 / 20000000 ) / 1000;
			return $pde;
		}
	}
	
	function madetotalp7( $age, $options, $discount, $sm, $ac, $premium2017 ) {
		$pde = $this->getParamValueByAge( $age, 'plan1sp' . $premium2017 );
		$disc = 0;
		if ( $options != '' ) {
			$Dental = 5775;
		}
		
		if ( !is_array( $discount ) ) {
			$discount = array( '', $discount );
		}
		foreach ( $discount as $r ) {
			if ( $r == "Treatment Area Limit (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Co-payment (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Outpatient Exclude (-30%)" ) {
				$disc += 0.3;
			}
		}
		$pde = $pde * ( 1 - $disc );
		
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			return $pde * ( 1 + $this->getConfigParam( 'config_smoker', 0 ) ) * ( 1 - $discount_group ) + $Dental + ( $ac * 28350 / 20000000 ) / 1000;
		} else {
			return $pde * ( 1 - $discount_group ) + $Dental + ( $ac * 28350 / 20000000 ) / 1000;
		}
	}
	
	function madetotalp8( $age, $options, $discount, $sm, $ac, $premium2017 ) {
		$pde = $this->getParamValueByAge( $age, 'plan2sp', $premium2017 );
		$disc = 0;
		if ( $options != '' ) {
			$Dental = 5775;
		}
		
		if ( !is_array( $discount ) ) {
			$discount = array( '',$discount );
		}
		foreach ( $discount as $r ) {
			if ( $r == "Treatment Area Limit (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Co-payment (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Outpatient Exclude (-30%)" ) {
				$disc += 0.3;
			}
		}
		$pde = $pde * ( 1 - $disc );
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			return $pde * ( 1 + $this->getConfigParam( 'config_smoker', 0 ) ) * ( 1 - $discount_group ) + $Dental + ( $ac * 28350 / 20000000 ) / 1000;
		} else {
			return $pde * ( 1 - $discount_group ) + $Dental + ( $ac * 28350 / 20000000 ) / 1000;
		}
	}
	
	function madetotalp9( $age, $options, $discount, $sm, $ac, $premium2017 ) {
		$pde = $this->getParamValueByAge( $age, 'plan3sp' . $premium2017 );
		$disc = 0;
		if ( $options != '' ) {
			$Dental = 5775;
		}
		
		if ( !is_array( $discount ) ) {
			$discount = array( '', $discount );
		}
		foreach ( $discount as $r ) {
			if ( $r == "Treatment Area Limit (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Co-payment (-25%)" ) {
				$disc += 0.25;
			} elseif ( $r == "Outpatient Exclude (-30%)" ) {
				$disc += 0.3;
			}
		}
		$pde = $pde * ( 1 - $disc );
		
		if ( $sm == 'yes' || $sm == 'Yes' ) {
			return $pde * ( 1 + $this->getConfigParam( 'config_smoker', 0 ) ) * ( 1 - $discount_group ) + $Dental + ( $ac * 28350 / 20000000 ) / 1000;
		} else {
			return $pde * ( 1 - $discount_group ) + $Dental + ( $ac * 28350 / 20000000 ) / 1000;
		}
	}
	
	function getParamValueByAge( $age, $paramPrefix ) {
		$ret = 0;
		switch ( true ) {
			case ( $age >= 0 && $age <= 5 ):
				$ret = $this->getConfigParam( $paramPrefix . '_0_5', 0 );
				break;
			case ( $age > 5 && $age <= 18 ):
				$ret = $this->getConfigParam( $paramPrefix . '_6_18', 0 );
				break;
			case ( $age > 18 && $age <= 25 ):
				$ret = $this->getConfigParam( $paramPrefix . '_19_25', 0 );
				break;
			case ( $age > 25 && $age <= 30 ):
				$ret = $this->getConfigParam( $paramPrefix . '_26_30', 0 );
				break;
			case ( $age > 30 && $age <= 35 ):
				$ret = $this->getConfigParam( $paramPrefix . '_31_35', 0 );
				break;
			case ( $age > 35 && $age <= 40 ):
				$ret = $this->getConfigParam( $paramPrefix . '_36_40', 0 );
				break;
			case ( $age > 40 && $age <= 45 ):
				$ret = $this->getConfigParam( $paramPrefix . '_41_45', 0 );
				break;
			case ( $age > 45 && $age <= 50 ):
				$ret = $this->getConfigParam( $paramPrefix . '_46_50', 0 );
				break;
			case ( $age > 50 && $age <= 55 ):
				$ret = $this->getConfigParam( $paramPrefix . '_51_55', 0 );
				break;
			case ( $age > 55 && $age <= 60 ):
				$ret = $this->getConfigParam( $paramPrefix . '_56_60', 0 );
				break;
			case ( $age > 60 && $age <= 65 ):
				$ret = $this->getConfigParam( $paramPrefix . '_61_65', 0 );
				break;
			case ( $age > 65 && $age <= 70 ):
				$ret = $this->getConfigParam( $paramPrefix . '_66_70', 0 );
				break;
			case ( $age > 70 && $age <= 75 ):
				$ret = $this->getConfigParam( $paramPrefix . '_71_75', 0 );
				break;
			case ( $age > 75 && $age <= 80 ):
				$ret = $this->getConfigParam( $paramPrefix . '_76_80', 0 );
				break;
			case ( $age > 80 && $age <= 85 ):
				$ret = $this->getConfigParam( $paramPrefix . '_81_85', 0 );
				break;
			case ( $age > 85 && $age <= 90 ):
				$ret = $this->getConfigParam( $paramPrefix . '_86_90', 0 );
				break;
		}
		return $ret;
	}
	
	function getConfigParam( $paramName, $defaultValue ) {
		return $this->params->get( $paramName, $defaultValue );
	}
}