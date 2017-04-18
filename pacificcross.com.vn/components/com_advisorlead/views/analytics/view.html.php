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
 * HTML Analytics View class for the Advisorlead Component
 */
class AdvisorleadViewAnalytics extends JViewLegacy {

    // Overwriting JViewLegacy display method
    function display($tpl = null) {
        // Assign data to the view
     
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