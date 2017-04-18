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
 * HTML CTA View class for the Advisorlead Component
 */
class AdvisorleadViewCTA extends JViewLegacy {

    // Overwriting JViewLegacy display method
    function display($tpl = null) {

        $app = JFactory::getApplication();
        $model_cta = $this->getModel('cta');
        $type = $app->input->get('type');
        $plain_template = $app->input->get('plain_template');
        if (!empty($plain_template)) {
            switch ($type) {
                case "publish":
                    $this->cta_id = $app->input->get('cta_id');

                    $cta = $model_cta->get_cta($this->cta_id);

                    $display_data = json_decode($cta->display_data);
                    $encoded = urlencode(base64_encode($this->cta_id));

                    $this->active_tab = !empty($display_data->popup_type) ? $display_data->popup_type : 'standard';
                    $link_type = !empty($display_data->link_type) ? $display_data->link_type : 'link';
                    $this->link_text = !empty($display_data->link_text) ? $display_data->link_text : 'Click Here to Subscribe';
                    $this->appear_time = !empty($display_data->appear_time) ? $display_data->appear_time : '0';
                    $this->button_style_arr = !empty($display_data->style) ? (array) $display_data->style : array();
                    $styles = array();
                    if (!empty($display_data->style)) {
                        foreach ($display_data->style as $key => $value) {
                            $styles[] = "$key: $value";
                        }
                    }
                    $button_style = implode(';', $styles);
                    $button_box_shadow = !empty($display_data->style) ? $display_data->style->{'box-shadow'} : '';
                    $this->button_box_shadow = explode(' ', $button_box_shadow);

                    $standard_style = $link_type == 'button' ? "style='$button_style'" : '';

                    $this->embed_link = "<a href='#' $standard_style id='capture_clicks_open'>$this->link_text</a>";
                    $embed_script = JURI::base() . "ctas/rmaction/$encoded/get";
                    $this->script = htmlentities($embed_script);
                    break;

                case "html":
                    $this->template_id = $app->input->get('template_id');

                    $this->template_html = $model_cta->get_cta_html($this->template_id);
                    $this->is_editing = $app->input->get('is_editing');
                    $this->wrap_style = $this->is_editing ? 'style="visibility: visible; opacity: 1"' : 0;
                    break;

                case "delete":
                    $cta_id = $app->input->get('cta_id');
                    $model_cta->delete_cta($cta_id);
                    break;

                case "load-cta-scripts":
                    $cta_id = $app->input->get('cta_id');
                    echo $model_cta->load_cta_scripts($cta_id);
                    exit;

                case"close-forever":
                    echo $model_cta->close_forever();
                    break;

                case "optin":
				
					//die('sadfdsf');
				
                    $cta_id = $app->input->get('cta_id');
                    $params = JRequest::get();
                    echo $model_cta->cta_optin($cta_id, $params);
                    break;
            }
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