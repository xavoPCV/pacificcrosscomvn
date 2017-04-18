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
 * HTML Pages View class for the Advisorlead Component
 */
class AdvisorleadViewPages extends JViewLegacy {

    // Overwriting JViewLegacy display method
    function display($tpl = null) {
	
		
		
		//var_dump($user);
		//exit;
	
        // Assign data to the view
        $app = JFactory::getApplication();
        $model = $this->getModel('pages');
        $type = $app->input->get('type');
        $plain_template = $app->input->get('plain_template');
        if (!empty($plain_template)) {
            switch ($type) {
                case "publish":
                    $this->page_id = $app->input->get('page_id');

                    $page = $model->get_page($this->page_id);

                    $this->page_name = $page->name;
                    $this->page_url = JURI::base() . "$page->article_id-$page->slug";

                    $data = json_decode($page->data);

                    break;

                case "html":
                    $this->template_id = $app->input->get('template_id');

                    $this->template_html = $model->get_page_html($this->template_id);
                    $this->is_editing = $app->input->get('is_editing');
                    break;

                case "delete":
                    $page_id = $app->input->get('page_id');
                    $model->delete_page($page_id);
                    break;

                case "optin":
                    $page_id = $app->input->get('page_id');
                    $params = JRequest::get();
                    echo $model->page_optin($page_id, $params);
                    break;

                case "payment":
                    $params = JRequest::get();
                    $page_id = $app->input->get('page_id');
                    echo $model->payment_charge($page_id,$params);
                    break;
            }
        }

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        };
		
		//var_dump($tpl);
		//exit;
		
		
		$messages = JFactory::getApplication()->getMessageQueue();
		
		//var_dump($messages);

		// Build the sorted message list
		if (is_array($messages) && !empty($messages))
		{
			foreach ($messages as $msg)
			{
				if (isset($msg['type']) && isset($msg['message']))
				{
					$lists[$msg['type']][] = $msg['message'];
				}
			}
			
			// If messages exist render them
			if (is_array($lists))
			{
				$buffer .= "\n<dl id=\"system-message\">";
				foreach ($lists as $type => $msgs)
				{
					if (count($msgs))
					{
						$buffer .= "\n<dt class=\"" . strtolower($type) . "\">" . JText::_($type) . "</dt>";
						$buffer .= "\n<dd class=\"" . strtolower($type) . " message\">";
						$buffer .= "\n\t<ul>";
						foreach ($msgs as $msg) {
							$buffer .= "\n\t\t<li>" . $msg . "</li>";
						}
						$buffer .= "\n\t</ul>";
						$buffer .= "\n</dd>";
					}
				}
				$buffer .= "\n</dl>";
				
				
				echo $buffer;
			}//if
			
			
			
		}//if

        // Display the view
        parent::display($tpl);
    }

}

?>