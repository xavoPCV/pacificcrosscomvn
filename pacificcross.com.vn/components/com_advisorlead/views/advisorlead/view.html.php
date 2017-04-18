<?php

/* ------------------------------------------------------------------------
  # view.html.php - AdvisorLead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML Account View class for the Advisorlead Component
 */
class AdvisorleadViewAdvisorLead extends JViewLegacy {

    // Overwriting JViewLegacy display method
    function display($tpl = null) {
        // Assign data to the view
        global $user;

        $model = JModelLegacy::getInstance('dashboard', 'AdvisorleadModel');
        $input = JFactory::getApplication()->input;
        $this->integration = $input->get('integration');

        if (!empty($_SESSION['message'])) {
            $this->message = json_encode($_SESSION['message']);
            unset($_SESSION['message']);
        }

        if (!empty($this->integration)) {
            $this->integration_title = $input->get('integration_title');
            $this->integrations = AdvisorleadHelper::get_integrations($user->id);
            $service_action = $input->get('service_action');
            if (!empty($service_action)) {
                $model->integration_action($this->integration, $this->integration_title, $service_action, $_REQUEST);
            }
        } else {
            $this->video_categories = $model->get_video_categories();
            $this->videos = $model->get_videos();
            $this->today_leads = $model->get_user_optins($user->id, strtotime('today'), strtotime('tomorrow'));
            $this->yesterday_leads = $model->get_user_optins($user->id, strtotime('yesterday'), strtotime('today'));
            $this->overall_leads = $model->get_user_optins($user->id);
        }



        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        };

        // Display the view
        parent::display($tpl);
    }

}

?>