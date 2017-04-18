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
 * HTML Templates View class for the Advisorlead Component
 */
class AdvisorleadViewTemplates extends JViewLegacy {

    // Overwriting JViewLegacy display method
    function display($tpl = null) {
        // Assign data to the view

        $app = JFactory::getApplication();
        $this->template_id = $app->input->get('template_id');
        $plain_template = $app->input->get('plain_template');
      
        if (!empty($plain_template)) {

            $this->template_type = $app->input->get('template_type');

            if ($this->template_type == 'cta') {
                $cta_id = $app->input->get('cta_id');
                $cta_model = JModelLegacy::getInstance('CTA', 'AdvisorleadModel');
                $this->template = $cta_model->get_cta_data($cta_id);
            } else if ($this->template_type == 'page') {

                $page_id = $app->input->get('page_id');
                $page_model = JModelLegacy::getInstance('Pages', 'AdvisorleadModel');
                              
                $this->template = $page_model->get_page_data($page_id,$this->template_id);
               // echo $page_id;die;
                $db = JFactory::getDbo();
                $sql = 'select custom_domain from #__advisorlead_pages where id = '.$page_id; 
                $db->setQuery($sql);
                $this->domains = $db->loadResult();
              //  echo $this->domains;die;
            }
        }
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        };
		
		#HT
		/*
		$db = JFactory::getDBO();
		$new_article_id = 46;
		
		//SHOULD find/check/add menutype no assign to any module
		$menutype = 'advisorlead-menu';
		
		$pre_alias = 'root';
		$alias = 'pre-alias-'.time();
		
		$title = 'test add page '.time();
		
		$link = 'index.php?option=com_content&view=article&id='.$new_article_id;
		
		 $arr_params = array(
                            'show_title' => '',
                            'link_titles' => '',
                            'show_intro' => '',
                            'show_category' => '',
                            'link_category' => '',
                            'show_parent_category' => '',
                            'link_parent_category' => '',
                            'show_author' => '',
                            'link_author' => '',
                            'show_create_date' => '',
                            'show_modify_date' => '',
                            'show_publish_date' => '',
                            'show_item_navigation' => '',
                            'show_vote' => '',
                            'show_icons' => '',
                            'show_print_icon' => '',
                            'show_email_icon' => '',
                            'show_hits' => '',
                            'show_noauth' => '',
                            'urls_position' => '',
                            'menu-anchor_title' => '',
                            'menu-anchor_css' => '',
                            'menu_image' => '',
                            'menu_text' => 1,
                            'page_title' => '',
                            'show_page_heading' => 0,
                            'page_heading' => '',
                            'pageclass_sfx' => '',
                            'menu-meta_description' => '',
                            'menu-meta_keywords' => '',
                            'robots' => '',
                            'secure' => 0
                        );
		
		
		$sqlinsertmenu = "SELECT rgt FROM #__menu WHERE alias = '$pre_alias'";
		
		$db->setQuery($sqlinsertmenu);
		$lastRgt = $db->loadResult();
		
		
		$sqlinsertmenu = "UPDATE #__menu SET rgt=rgt+2 WHERE rgt > $lastRgt";
		
		$db->setQuery($sqlinsertmenu);
		$db->execute();
        
		$sqlinsertmenu = "UPDATE #__menu SET lft=lft+2 WHERE lft > $lastRgt";
		
		$db->setQuery($sqlinsertmenu);
		$db->execute();
		
        $sqlinsertmenu = "INSERT INTO #__menu (menutype, title, alias, note, path, link, type, published, parent_id, level,component_id, ordering, access, img, template_style_id,params, lft, rgt, home, language, client_id) VALUES('$menutype','$title','$alias','','$alias','$link','component',1,1,1,22,0,1,'',0,'" . json_encode($arr_params) . "', $lastRgt, ".($lastRgt+1).", 0, '*', 0)";
		
		$db->setQuery($sqlinsertmenu);
		$db->execute();
		
		$new_menuid = $db->insertid();
		
		
        $sqlinsertmenu = "UPDATE #__menu SET rgt=rgt+2 WHERE alias='$pre_alias'";
		
		$db->setQuery($sqlinsertmenu);
		$db->execute();
        
		
		
		
		
		var_dump($new_menuid);
		exit;
		*/
		
		
		

        // Display the view
     
        parent::display($tpl);
    }

}

?>