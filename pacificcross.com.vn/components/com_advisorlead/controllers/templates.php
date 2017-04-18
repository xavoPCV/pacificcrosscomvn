<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class AdvisorLeadControllerTemplates extends JControllerLegacy {

    function api_call() {

        $app = JFactory::getApplication();
        $input = $app->input;
        $request = $input->getString('request', '');
        $results = array();


        switch ($request) {
            case "get_templates":
                $category = $input->get('category');
                $results = $this->get_templates_content($category);
                break;
            case "my-images":
                JLoader::import('advisorlead', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'controllers');
                $advisorlead_controller = new AdvisorLeadControllerAdvisorlead();
                $results = $advisorlead_controller->get_user_images_content();
                break;
            case "save-cta":
                $results = $this->save_cta_content($_REQUEST);
                break;
            case "get-cta-data":
                $model = $this->getModel('cta');
                $cta_id = $input->get('cta_id');
                $results = $model->get_cta_fields_data($cta_id);
                break;
            case "save-page":
                $results = $this->save_page_content($_REQUEST);
                break;
            case "get-page-data":
                $model = $this->getModel('pages');
                $page_id = $input->get('page_id');
                $results = $model->get_page_fields_data($page_id);
                break;
            case "upload-image":
                $type = $input->get('type');
                echo $this->get_upload_iframe($type);
                exit;
            case "page-integrations":
                JLoader::import('pages', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'controllers');
                $page_controller = new AdvisorLeadControllerPages();

                $type = $input->get('integration_type');
                $results = $page_controller->page_integrations($type);
                break;
            case "cta-integrations":
                JLoader::import('cta', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'controllers');
                $cta_controller = new AdvisorLeadControllerCTA();

                $type = $input->get('integration_type');
                $results = $cta_controller->cta_integrations($type);
                break;
            case "cta-form-fields":
                JLoader::import('cta', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'controllers');
                $cta_controller = new AdvisorLeadControllerCTA();

                $type = $input->get('integration_type');
                $form_id = $_REQUEST['form_id'];
                $results = $cta_controller->cta_form_fields($type, $form_id);
                break;
        }
        echo json_encode($results);

        $app->close();
    }

    function get_templates_content($category) {

        $model = $this->getModel('templates');
        $templates = $model->get_templates($category);
        $path = HTML_TEMPLATES_PATH . 'template_block.php';
        $html = AdvisorleadHelper::render($path, array('templates' => $templates));
        $results = array(
            'status' => 200,
            'body' => array('has_more' => false, 'cstr' => '', 'html' => $html)
        );
        return $results;
    }

    function get_user_images_content() {
        global $user;
        $model = $this->getModel('templates');
        $images = $model->get_user_images($user->id);
        $results = array(
            'status' => 200,
            'body' => array('has_more' => false, 'cstr' => '', 'images' => $images)
        );
        return $results;
    }

    function save_cta_content($params) {
        $model = $this->getModel('cta');
        return $model->save_cta_data($params);
    }

    function save_page_content($params) {
        $model = $this->getModel('pages');
        return $model->save_page_data($params);
    }

    function get_upload_iframe($type = '') {
        $image_id = 0;
        $url = '';
        $upload_successful = false;
        if (isset($_FILES['file'])) {
            $model = $this->getModel('templates');
            $result = $model->upload_image($_FILES['file']);
            if ($result['id']) {
                $image_id = $result['id'];
                $url = $result['url'];
                $upload_successful = true;
            }
        }

        $action_url = JURI::base() . "/index.php?option=com_advisorlead&task=templates.api_call&request=upload-image&type=$type";
        $path = HTML_TEMPLATES_PATH . '/upload_images.php';
        $params = array(
            'upload_successful' => $upload_successful,
            'image_id' => $image_id,
            'url' => $url,
            'action_url' => $action_url,
            'type' => $type
        );
        $html = AdvisorleadHelper::render($path, $params);
        return $html;
    }

}