<?php

/* ------------------------------------------------------------------------
  # controller.php - Advisor Lead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * Advisorlead Component Controller
 */
class AdvisorleadController extends JController {

    function display($cachable = false, $urlparams = false) {

        global $user;
		
		$app = JFactory::getApplication();
		
		$input = $app->input;
        $plain_template = $input->get('plain_template');
        $current_view = $input->get('view');

        $redirect_url = JURI::base() . "advisorlead";
		
		$no_required_login_view = array('fbpage');
		
		$pub_view = false;
		if (in_array($current_view, $no_required_login_view)) {
			$pub_view = true;
			$plain_template = 1;
		}//if
		
        if (empty($user->id) && !$pub_view ) {

            $redirect_url = JURI::base() . "advisorlead";
            
            $app->setUserState("users.login.form.data", array('return' => $redirect_url));

            $url = JRoute::_('index.php?option=com_users&view=login');
            $this->setRedirect($url, JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'));
            return;
        }
		
		
        $model_template = JModelLegacy::getInstance('templates', 'AdvisorleadModel');
        $this->categories = $model_template->get_categories();

        if (empty($plain_template))
            include_once HTML_TEMPLATES_PATH . 'header.php';
			
			
		
        
        parent::display();
		
        
        if (empty($plain_template))
            include_once HTML_TEMPLATES_PATH . 'footer.php';
        
		if (!$pub_view)	exit;
		
    }//if
	
	function export() {
		$app = JFactory::getApplication();
		
		$page_id = JRequest::getInt('id');
		
		$model_page = JModelLegacy::getInstance('pages', 'AdvisorleadModel');
		
		$page = $model_page->get_page($page_id);
		
		$full_url = JURI::base() . "$page->slug";
		
		//echo $full_url;
		
		$full_html = file_get_contents($full_url);
		
		$tmp_filename = time().'.html';
		
		$pdf_file_path = JPATH_ROOT.'/tmp/'.$tmp_filename;
		
		$pdf_filename = file_put_contents($pdf_file_path, $full_html);
		
		// Clears file status cache
		clearstatcache();
		$fileSize 		= @filesize($pdf_file_path);
		$mimeType 		= 'application/force-download';
		$fileName		= $pdf_filename;
		// Clean the output buffer
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0"); 
		header("Content-Description: File Transfer");
		header("Expires: Sat, 30 Dec 1990 07:07:07 GMT");
		header("Content-Type: " . (string)$mimeType);
		// Problem with IE
		header("Content-Length: ". (string)$fileSize);
		header('Content-Disposition: attachment; filename="'.$tmp_filename.'"');
		header("Content-Transfer-Encoding: binary\n");
		@readfile($pdf_file_path);
	
	
		$app->close();
	}//func
	
	

}

?>