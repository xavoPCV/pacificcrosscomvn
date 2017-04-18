<?php
//First start with information about the Plugin and yourself. For example:
/**
 * @package     Joomla.Plugin
 * @subpackage  System.onepay
 *
 * @copyright   Copyright
 * @license     License, for example GNU/GPL
 */

//To prevent accessing the document directly, enter this code:
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Require the component's router file (Replace 'nameofcomponent' with the component your providing the search for
//require_once JPATH_SITE .  '/components/nameofcomponent/helpers/route.php';

/**
 * All functions need to get wrapped in a class
 *
 * The class name should start with 'PlgSearch' followed by the name of the plugin. Joomla calls the class based on the name of the plugin, so it is very important that they match
 */
class PlgSystemTravelpricecalculation extends JPlugin
{
    const PLAN_FLEXIBLE = 'Flexible Travel Insurance';
    const PLAN_ANNUAL = 'Annual Travel Insurance';

    const COVERAGE_FAMILY = 'Family';

    const PLAN_CHOICE_PREMIER = 'Premier';
    const PLAN_CHOICE_EXECUTIVE = 'Executive';
    const PLAN_CHOICE_A = 'Plan A';
    const PLAN_CHOICE_B = 'Plan B';
    const PLAN_CHOICE_C = 'Plan C';

    /**
     * Constructor
     *
     * @access      protected
     * @param       object  $subject The object to observe
     * @param       array   $config  An array that holds the plugin configuration
     * @since       1.6
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onAjaxTravelpricecalculation(){
        $jinput = JFactory::getApplication()->input;
        $post = $jinput->get('form', null, null);

        $travelPlan = $this->getTravelPlan($post);

        $persons = $this->getTravelPersonPlan($travelPlan, $post);
        $result = new stdClass();
        $total = 0;
        if ($travelPlan->option1Plan == self::PLAN_FLEXIBLE && $travelPlan->option3Plan == self::COVERAGE_FAMILY){
            // bon voyage travel family
            $total += $this->calculateFamilyPlan($travelPlan);
            foreach($persons as $person) {
                $personTotal = $this->calculateCarFeeForFamilyPlan($travelPlan, $person) ;
                $total += $personTotal;
            }
        }else if ($travelPlan->option1Plan == self::PLAN_FLEXIBLE){
            // bon voyage travel individual
           foreach($persons as $person) {
                $personTotal = $this->calculateIndividualPlan($travelPlan, $person);
                $total += $personTotal;
            }
        }else if ($travelPlan->option1Plan == self::PLAN_ANNUAL && $travelPlan->option2Plan == self::PLAN_CHOICE_PREMIER){
            // annual premier travel
            foreach($persons as $person) {
                $personTotal = $this->calculatePremierPlan($person);

                $total += $personTotal;
            }
        }else if ($travelPlan->option1Plan == self::PLAN_ANNUAL && $travelPlan->option2Plan == self::PLAN_CHOICE_EXECUTIVE){
            // annual executive travel
            foreach($persons as $person) {
                $personTotal = $this->calculateExecutivePlan($person);
                $total += $personTotal;
            }
        }
        $result->total = $total;
        return json_encode($result);
    }

    function calculatePremierPlan($personPlan){
        // annual fee
        $annualFee = $this->getConfigParam('pi_premier_annual',0);

        // rent car fee
        $rentCarFee = 0;
        if ($personPlan->optionRentalCar){
            $rentCarFee = $this->getConfigParam('pi_premier_opt_car',0);
        }

        // accident fee
        $accidentCarFee = 0;
        if ($personPlan->optionAccident){
            $accidentCarFee = $personPlan->optionAccident;
        }

        return $annualFee + $rentCarFee + $accidentCarFee;
    }

    function calculateExecutivePlan($personPlan){
        // annual fee
        $annualFee = $this->getConfigParam('pi_executive_annual',0);

        // rent car fee
        $rentCarFee = 0;
        if ($personPlan->optionRentalCar){
            $rentCarFee = $this->getConfigParam('pi_executive_opt_car',0);
        }

        // accident fee
        $accidentCarFee = 0;
        if ($personPlan->optionAccident){
            $accidentCarFee = $personPlan->optionAccident;
        }

        return $annualFee + $rentCarFee + $accidentCarFee;
    }

    function calculateIndividualPlan($travelPlan, $personPlan){
        $travelDay = $travelPlan->optionTravelDay;
      //  $total = $carFee = 0;
        if ($travelPlan->option2Plan == self::PLAN_CHOICE_A){
            $total = $this->getParamValueByTravelDay( $travelDay, 'pi_voyage_idv_a');
        }else if ($travelPlan->option2Plan == self::PLAN_CHOICE_B){
            $total = $this->getParamValueByTravelDay( $travelDay, 'pi_voyage_idv_b');
        }else if ($travelPlan->option2Plan == self::PLAN_CHOICE_C){
            $total = $this->getParamValueByTravelDay( $travelDay, 'pi_voyage_idv_c');
        }

        $carFee = 0;
        if ($travelPlan->option1Plan == self::PLAN_FLEXIBLE && $personPlan->optionRentalCarDay > 0){
            $carFee = $this->getConfigParam('pi_voyage_car',0) * $personPlan->optionRentalCarDay;
        }

        return $total + $carFee;
    }

    function calculateFamilyPlan($travelPlan){
        $travelDay = $travelPlan->optionTravelDay;
        if ($travelPlan->option2Plan == self::PLAN_CHOICE_A){
            $total = $this->getParamValueByTravelDay( $travelDay, 'pi_voyage_family_a');
        }else if ($travelPlan->option2Plan == self::PLAN_CHOICE_B){
            $total = $this->getParamValueByTravelDay( $travelDay, 'pi_voyage_family_b');
        }else if ($travelPlan->option2Plan == self::PLAN_CHOICE_C){
            $total = $this->getParamValueByTravelDay( $travelDay, 'pi_voyage_family_c');
        }

        return $total;
    }

    function calculateCarFeeForFamilyPlan($travelPlan, $personPlan){
        $carFee = 0;
        if ($travelPlan->option1Plan == self::PLAN_FLEXIBLE && $personPlan->optionRentalCarDay > 0){
            $carFee = $this->getConfigParam('pi_voyage_car',0) * $personPlan->optionRentalCarDay;
        }

        return $carFee;
    }

    // get travel plan data for post
    public function getTravelPlan($post){
        $travelPlan = new TravelPlan();
        $plan = '';
        if(isset($post['PlanOption1']) && $post['PlanOption1']) {
            $plan = $post['PlanOption1'][0];
        }

        $planChoice = '';
        $planCoverage = '';
        $travelDay = 0;
        if($plan == self::PLAN_FLEXIBLE) {
            if(isset($post['Option1PlanChoice']) && $post['Option1PlanChoice']) {
                $planChoice = $post['Option1PlanChoice'][0];
            }
            if(isset($post['CoverageType']) && $post['CoverageType']) {
                $planCoverage = $post['CoverageType'][0];
            }
            if(isset($post['TravelDay']) && $post['TravelDay']) {
                $travelDay = (int)$post['TravelDay'];
            }
        }elseif($plan == self::PLAN_ANNUAL) {
            if(isset($post['Option2PlanChoice']) && $post['Option2PlanChoice']) {
                $planChoice = $post['Option2PlanChoice'][0];
            }
        }

        $travelPlan->option1Plan = $plan;
        $travelPlan->option2Plan = $planChoice;
        $travelPlan->option3Plan = $planCoverage;
        $travelPlan->optionTravelDay = $travelDay;
        return $travelPlan;
    }

    // get param value by person age
    function getParamValueByTravelDay($day, $paramPrefix) {
        $ret= 0;
           switch(true) {
             case($day > 0 && $day <= 5 ):
                  $ret = $this->getConfigParam($paramPrefix.'_5', 0 );
                  break;
              case($day > 0 && $day <= 8):
                  $ret = $this->getConfigParam($paramPrefix.'_8',0);
                  break;
              case($day > 0 && $day <= 11):
                  $ret = $this->getConfigParam($paramPrefix.'_11',0);
                  break;
              case($day > 0 && $day <= 15):
                  $ret = $this->getConfigParam($paramPrefix.'_15',0);
                  break;
              case($day > 0 && $day <= 24):
                  $ret = $this->getConfigParam($paramPrefix.'_24',0);
                  break;
              case($day > 0 && $day <=31):
                  $ret = $this->getConfigParam($paramPrefix.'_31',0);
                  break;
              case($day > 31 ):
                  $ret = $this->getConfigParam($paramPrefix.'_31',0);
                  $remainDay = $day - 31;
                  // maximum is 180 days
                  if ($remainDay > 149){
                      $remainDay = 149;
                  }
                  while($remainDay > 0){
                      $ret += $this->getConfigParam($paramPrefix.'_step',0);
                      $remainDay -= 7;
                  }
                  break;
        }
        return $ret;
    }

    // parse post to get personal option and smokings
    public function getTravelPersonPlan($travelPlan, $post) {
        $prefix = array('1st','2nd','3rd','4th','5th','6th','7th','8th','9th', '10th');
        $persons = array();
        if (isset($post['insuredQuantity']) && $post['insuredQuantity']) {
            $numberPerson = (int)$post['insuredQuantity'][0];
            if ($numberPerson > 10){
                $numberPerson = 10;
            }
            for ($i = 0; $i < $numberPerson; $i++) {
                if (isset($post[$prefix[$i] . 'PersonFirstName']) && $post[$prefix[$i] . 'PersonFirstName']) {
                    $personPlan = new TravelPersonPlan();
                    if ($travelPlan->option1Plan == self::PLAN_FLEXIBLE) {
                        if (isset($post[$prefix[$i] . 'PersonRentalCarDay']) && $post[$prefix[$i] . 'PersonRentalCarDay']) {
                            $personPlan->optionRentalCarDay = (int)$post[$prefix[$i] . 'PersonRentalCarDay'][0];
                        }
                    } else {
                        if (isset($post[$prefix[$i] . 'PersonAccident']) && $post[$prefix[$i] . 'PersonAccident']) {
                            $personPlan->optionAccident = (int)$post[$prefix[$i] . 'PersonAccident'][0];
                        }
                        if (isset($post[$prefix[$i] . 'PersonRentalCar']) && $post[$prefix[$i] . 'PersonRentalCar'][0] == 'Yes') {
                            $personPlan->optionRentalCar = 1;
                        }
                    }
                    $persons[] = $personPlan;
                }
            }
        }
        return $persons;
    }

    function getConfigParam($paramName, $defaultValue){
        return $this->params->get($paramName, $defaultValue);
    }
}

class TravelPlan {
    // fee
    public $option1Plan;
    public $option2Plan;
    public $option3Plan;
    public $optionTravelDay;
}

class TravelPersonPlan {
    // fee
    public $optionAccident = 0;
    public $optionRentalCarDay = 0;
    public $optionRentalCar = 0;
}

