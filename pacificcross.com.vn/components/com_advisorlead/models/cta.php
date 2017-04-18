<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class AdvisorleadModelCTA extends JModelList {

    function get_cta($cid = '', $uid = '', $slug = '') {
        $db = JFactory::getDbo();
        $where = '';
        if (!empty($cid)) {
            $where = " AND id = $cid";
        }
        if (!empty($uid)) {
            $where .= " AND uid = $uid";
        }
        if (!empty($slug)) {
            $where .= " AND slug LIKE '$slug'";
        }
        $query = "SELECT * FROM " . CTA_TABLE . " WHERE 1 $where LIMIT 1";
        $db->setQuery($query);
        return $db->loadObject();
    }

    function get_total_ctas($uid) {
        $db = JFactory::getDbo();
        $query = "SELECT COUNT(*) FROM " . CTA_TABLE . " WHERE uid = $uid";
        $db->setQuery($query);
        return $db->loadResult();
    }

    function get_ctas($uid, $paged, $per_page, $order) {
        $db = JFactory::getDbo();

        $query = "SELECT cc.*,t.slug as template_slug FROM " . CTA_TABLE . " cc JOIN " . TEMPLATES_TABLE . " t ON cc.template_id = t.id WHERE uid = $uid";
        switch ($order) {
            case 'up_desc':
                $query .= " ORDER BY updated DESC";
                break;
            case 'up_asc':
                $query .= " ORDER BY updated ASC";
                break;
            case 'cr_desc':
                $query .= " ORDER BY created DESC";
                break;
            case 'cr_asc':
                $query .= " ORDER BY created ASC";
                break;
            case 'nm_desc':
                $query .= " ORDER BY name DESC";
                break;
            case 'nm_asc':
                $query .= " ORDER BY name ASC";
                break;
            case 'lb_desc':
                $query .= " ORDER BY label DESC";
                break;
            case 'lb_asc':
                $query .= " ORDER BY label ASC";
                break;
            case 'crate_desc':
                $query .= " ORDER BY rates DESC";
                break;
            case 'crate_asc':
                $query .= " ORDER BY rates ASC";
                break;
            case 'uniques_desc':
                $query .= " ORDER BY uniques DESC";
                break;
            case 'uniques_asc':
                $query .= " ORDER BY uniques ASC";
                break;
            case 'optins_desc':
                $query .= " ORDER BY optins DESC";
                break;
            case 'optins_asc':
                $query .= " ORDER BY optins ASC";
                break;
        }
        $query .= " LIMIT " . ($paged - 1) * $per_page . "," . $per_page;
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function get_cta_data($cta_id = 0) {

        global $user;

        $cta = array();
        $integrations = AdvisorleadHelper::get_integrations($user->id);

        if (!empty($cta_id)) {
            $cta = $this->get_cta($cta_id, $user->id);
        }

        $cta_id = 0;
        $cta_name = '';
        $cta_slug = '';
        $selected_integration = '';
        $selected_form = '';
        $user_head_code = '';
        $user_end_code = '';
        if (!empty($cta)) {
            $cta_id = $cta->id;
            $cta_name = $cta->name;
            $cta_slug = $cta->slug;
            $user_head_code = $cta->user_head_code;
            $user_end_code = $cta->user_end_code;
            $field_data = json_decode($cta->field_data);
            if (!empty($field_data) && $cta->field_data != '{}') {
                $selected_integration = $field_data->selected_integration;
                $selected_form = $field_data->selected_form;
            }
        }

        $params = array(
            'is_edit' => !empty($cta) ? true : false,
            'cta_id' => $cta_id,
            'cta_name' => $cta_name,
            'cta_slug' => $cta_slug,
            'user_head_code' => $user_head_code,
            'user_end_code' => $user_end_code,
            'selected_integration' => $selected_integration,
            'selected_form' => $selected_form,
            'integrations' => $integrations
        );

        return (object) $params;
    }

    function get_cta_fields_data($cta_id) {

        $cta = $this->get_cta($cta_id);
        $data = json_decode($cta->data);
        $style_data = json_decode($cta->style_data);
        $color_data = json_decode($cta->color_data);
        $field_data = json_decode($cta->field_data);
        $return_data = array(
            'data' => $data,
            'style_data' => $style_data,
            'color_data' => $color_data,
            'field_data' => $field_data
        );
        $result = array(
            'status' => 200,
            'body' => $return_data
        );
        return $result;
    }

    function save_cta_data($request) {
        global $user;
        $result = array('status' => 400, 'body' => array());
        $name = !empty($request['capture_clicks_name']) ? $request['capture_clicks_name'] : '';
        $cid = !empty($request['capture_clicks_id']) ? $request['capture_clicks_id'] : 0;
        $slug = JApplication::stringURLSafe($name);
        $db = JFactory::getDbo();

        if ($cid > 0) {

            $is_exists = $this->get_cta($cid);

            if (!empty($is_exists)) {
                $update_arr = array(
                    'id' => $cid,
                    'name' => $name,
                    'slug' => $slug,
                    'data' => AdvisorleadHelper::clean_html($request['data']),
                    'color_data' => $request['color_data'],
                    'style_data' => $request['style_data'],
                    'field_data' => $request['field_data'],
                    'user_head_code' => $request['user_head_code'],
                    'user_end_code' => $request['user_end_code'],
                    'updated' => time(),
                );
                $updates = (object) $update_arr;
                $updated = $db->updateObject(CTA_TABLE_UNQUOTE, $updates, 'id');
                if ($updated) {
                    $result['status'] = 200;
                    $result['body'] = $cid;
                }
            }
        } else {
            $is_exists = $this->get_cta('', $user->id, $slug);

            if (empty($is_exists)) {

                $display_data = array(
                    'popup_type' => 'standard',
                    'link_type' => 'link',
                    'link_text' => 'Click Here to Subscribe'
                );
                $insert_arr = array(
                    'uid' => $user->id,
                    'template_id' => $request['template_id'],
                    'name' => $name,
                    'slug' => $slug,
                    'color_data' => $request['color_data'],
                    'data' => AdvisorleadHelper::clean_html($request['data']),
                    'style_data' => $request['style_data'],
                    'field_data' => $request['field_data'],
                    'display_data' => json_encode($display_data),
                    'user_head_code' => $request['user_head_code'],
                    'user_end_code' => $request['user_end_code'],
                    'views' => 0,
                    'uniques' => 0,
                    'optins' => 0,
                    'rates' => 0,
                    'updated' => time(),
                    'created' => time(),
                );

                $inserts = (object) $insert_arr;
                $inserted = $db->insertObject(CTA_TABLE_UNQUOTE, $inserts);
                if ($inserted) {
                    $cid = $db->insertid();
                    $result['status'] = 200;
                    $result['body'] = $cid;
                }
            } else {
                $result['body'] = array('message' => 'This name is already exists!', 'title' => 'This name is already used');
            }
        }

        return $result;
    }

    function get_cta_html($template_id, $is_editing = true) {
        $template_model = JModelLegacy::getInstance('templates', 'AdvisorleadModel');
        $template = $template_model->get_template($template_id);
        $template_path = "/inc/cta-templates/$template->slug/";
        $template_file = ASSETS_PATH . $template_path . 'template.html';
        $search = array('{LOCAL_URL}');
        $replace = array(ASSETS_URL . $template_path);
        $html = '';
        if (!$is_editing) {
            $html = '<div id="capture_clicks_wrap" class="capture_clicks_wrap">';
        }
        $html.= AdvisorleadHelper::render_replace($template_file, $search, $replace);
        if (!$is_editing) {
            $html .= '</div>';
        }
        return $html;
    }

    function get_published_cta_id() {
        $db = JFactory::getDbo();
        $query = "SELECT id FROM " . CTA_TABLE . " WHERE status = 1 LIMIT 1";
        $db->setQuery($query);
        return $db->loadResult();
    }

    function show_published_cta() {

        $cta_id = $this->get_published_cta_id();
        $cta = $this->get_cta($cta_id);
        $script = '';
        if (!empty($cta)) {

            $display_data = json_decode($cta->display_data);

            $active_tab = !empty($display_data->popup_type) ? $display_data->popup_type : 'standard';
            $link_text = !empty($display_data->link_text) ? $display_data->link_text : 'Click Here to Subscribe';

            $styles = array();
            if (!empty($display_data->style)) {
                foreach ($display_data->style as $key => $value) {
                    $styles[] = "$key: $value";
                }
            }
            $button_style = implode(';', $styles);
            $standard_style = $active_tab == 'button' ? "style='$button_style'" : '';

            $embed_link = $active_tab != 'popup' ? "<a href='#' $standard_style id='capture_clicks_open'>$link_text</a>" : '';
            $embed_script = $this->load_cta_scripts($cta_id);
            $script = $embed_link . $embed_script;
        }

        return $script;
    }

    function close_forever() {
        $cookie_values = time();
        $app = JFactory::getApplication();
        $cookie_path = $app->get('cookie_path', '/');
        $cookie_domain = $app->get('cookie_domain');

        $test = setcookie('close_forever', $cookie_values, time() + 3600 * 24 * 100, $cookie_path, $cookie_domain, false);

        $im = imagecreate(1, 1);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagesetpixel($im, 1, 1, $white);
        header('Content-Type: image/gif');
        imagejpeg($im);
        imagedestroy($im);
    }

    function is_show_cta($cta) {
        if (!empty($_COOKIE['close_forever'])) {

            $data = $_COOKIE['close_forever'];
            if ($cta->updated > $data) {
                unset($_COOKIE['close_forever']);
            } else {
                return false;
            }
        }
        return true;
    }

    function load_cta($cta_id) {

        $cta = $this->get_cta($cta_id);
        $is_show = $this->is_show_cta($cta);
        if (!$is_show) {
            return false;
        }
        $template_id = $cta->template_id;
        if (!empty($cta) && !empty($template_id)) {

            $cta_template = $this->get_cta_html($template_id, false);
            $document = new DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($cta_template);
            $xpath = new DOMXPath($document);
            $display_data = json_decode($cta->display_data);
            $field_data = json_decode($cta->field_data);
            $style_data = json_decode($cta->style_data);
            $color_data = json_decode($cta->color_data);
            $element_data = json_decode($cta->data);
            $element_data->form_style = '';
            $fonts = array();
            foreach ($element_data as $key => $data) {
                if (!empty($data->type)) {
                    switch ($data->type) {
                        case "image":
                            $els = $xpath->query('//*[@id="' . $key . '"]');
                            foreach ($els as $el) {
                                $el->setAttribute("src", $data->url);
                                if ($data->removable) {
                                    $style = $el->getAttribute("style");
                                    $style = preg_replace('/display:([^;]*)/', '', $style);
                                    if ($data->removed)
                                        $style = 'display:none;' . $style;
                                    $el->setAttribute("style", $style);
                                }
                            }
                            break;
                        case "text":
                            $els = $xpath->query('//*[@id="' . $key . '"]');
                            foreach ($els as $el) {
                                $el->nodeValue = '';
                                $frag = $document->createDocumentFragment();
                                $text = htmlspecialchars($data->text);
                                if ($frag->appendXML($text))
                                    $el->appendChild($frag);

                                if ($data->removable) {
                                    $style = $el->getAttribute("style");
                                    $style = preg_replace('/display:([^;]*)/', '', $style);
                                    if ($data->removed)
                                        $style = 'display:none;' . $style;
                                    $el->setAttribute("style", $style);
                                }
                            }
                            break;
                    }
                }

                $style_elements = $xpath->query('//*[@id="' . $key . '"]');
                $styles = array();

                foreach ($style_elements as $el) {
                    $old_styles = $el->getAttribute('style');
                    foreach ($style_data as $data) {
                        if (str_replace('#', '', $data->element) == $key) {
                            $value = $data->value;
                            if (is_numeric($value)) {
                                $value = $value . 'px !important';
                            }
                            $styles[] = "$data->type: $value;";
                            if ($data->type == 'font-family')
                                $fonts[] = $data->value;
                        }
                    }
                    $new_styles = implode(';', $styles) . $old_styles;
                    $el->setAttribute('style', $new_styles);
                }

                $color_elements = $xpath->query('//*[@id="' . $key . '"]');
                $colors = $child_colors = array();

                foreach ($color_elements as $el) {
                    $old_color = $el->getAttribute('style');

                    foreach ($color_data as $data) {
                        $element = str_replace('#', '', $data->element);
                        if ($element == $key) {
                            $colors[] = "$data->type: $data->more $data->value !important;";
                        }

                        if (!empty($data->parent)) {
                            $parent_element = str_replace('#', '', $data->parent);
                            if ($parent_element == $key) {
                                $child_element = $xpath->query('//*[@id="' . $element . '"]')->item(0);
                                if (!empty($child_element)) {
                                    $child_element->setAttribute('style', "$data->type: $data->more $data->value !important;");
                                }
                            }
                        }
                    }

                    $new_colors = implode(';', $colors) . $old_color;
                    $el->setAttribute('style', $new_colors);
                }
            }

            //SET FORM FIELDS
            $form_fields = $xpath->query('//*[@id="form_fields"]')->item(0);
            if (!empty($form_fields)) {
                $form_fields->nodeValue = '';

                $frag = $document->createDocumentFragment();
                $frag->appendXML("<input type='hidden' name='cta_id' value='$cta_id'/>");
                $form_fields->appendChild($frag);

                $fields_html = '';
                if (!empty($field_data->fields)) {
                    foreach ($field_data->fields as $name => $field) {
                        if ($field->is_show == 1) {
                            $fields_html .="<li>
                                    <input type='text' placeholder='$field->placeholder' name='$name' is_required='$field->is_required' />
                                    <span type='invalid' class='error-message'>$field->invalid_text</span>
                                    <span type='required' class='error-message'>$field->required_text</span>
                                </li>";
                        }
                    }

                    $frag = $document->createDocumentFragment();
                    $frag->appendXML($fields_html);
                    $form_fields->appendChild($frag);
                }

                $form_element = $xpath->query('//*[@id="form_style"]')->item(0);
                $form_element->setAttribute('action', ADVISORLEAD_URL . "/ctas/$cta_id/optin/");
            }

            //SET FONTS
            $head = $document->getElementsByTagName("head")->item(0);

            $fonts = array_unique($fonts);
            foreach ($fonts as $font) {
                if ($font != 'Arial') {
                    echo '<link href="https://fonts.googleapis.com/css?family=' . urlencode($font) . '" rel="stylesheet" type="text/css"/>';
                }
            }

            echo '<link href="' . ASSETS_URL . '/css/cta.css" rel="stylesheet" type="text/css"/>';
            echo '<script src="' . ASSETS_URL . '/js/jquery.min.js"></script>';
            echo '<script src="' . ASSETS_URL . '/js/cta_noConflict.js"></script>';
            echo '<script src="' . ASSETS_URL . '/js/cta.js"></script>';


            //SET DISPLAY DATA

            $display_variables = '';
            switch ($display_data->popup_type) {

                case "popup":
                    $timeout = $display_data->appear_time * 1000;
                    $display_variables .= "var popup_timeout = $timeout;";
                    break;
            }

            $display_variables .= "
                var popup_type = '$display_data->popup_type';
                var remote_url = '" . ADVISORLEAD_URL . "';
                var cta_id =  trackingId = $cta_id;
                var base_url = '" . JURI::base() . "';
                var tracking_type = 'cta';";

            echo "<script>$display_variables</script>";
            echo '<script src="' . ASSETS_URL . '/js/track.js"></script>';
            $html = $document->saveHTML();

            return htmlspecialchars_decode($html);
        }
    }

    function load_cta_scripts($cta_id) {
        $results = $this->load_cta($cta_id);
        if (!$results) {
            return false;
        }

        return $results;
    }

    function delete_cta($cta_id) {
        global $user;
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('id') . ' = ' . $cta_id,
            $db->quoteName('uid') . ' = ' . $user->id
        );

        $query->delete(CTA_TABLE)->where($conditions);

        $db->setQuery($query);
        $db->execute();
    }

    function cta_optin($cta_id, $params) {

        $cta = $this->get_cta($cta_id);

        $integrations = $integrations = AdvisorleadHelper::get_integrations($cta->uid);
        $email_services_options = AdvisorleadHelper::get_option('email_services');
        $email_services = json_decode($email_services_options);


        $data = json_decode($cta->field_data);

        if (!empty($data)) {

            switch ($data->selected_integration) {
                case 'aweber':

                    $list_id = explode('/', $data->selected_form);
                    $aweber = new AWeberAPI($email_services->aweber_key, $email_services->aweber_secret);
                    try {
                        $account = $aweber->getAccount($integrations['aweber']['access_token'], $integrations['aweber']['access_token_secret']);
                        $listURL = "$account->lists_collection_link/$list_id[0]";
                        $list = $account->loadFromUrl($listURL);
                        $custom_fields = array();
                        foreach ($params as $key => $value) {
                            if (strpos($key, 'custom_') !== false) {
                                $custom_fields[str_replace('custom_', '', $key)] = $value;
                            }
                        }

                        $submit = array(
                            'email' => $params['email'],
                            'name' => $params['name'],
                            'custom_fields' => $custom_fields
                        );

                        $list->subscribers->create($submit);
                    } catch (AWeberAPIException $exc) {
//                        print "<h3>AWeberAPIException:</h3>";
//                        print " <li> Type: $exc->type              <br>";
//                        print " <li> Msg : $exc->message           <br>";
//                        print " <li> Docs: $exc->documentation_url <br>";
//                        print "<hr>";
                    }
                    $tracking_email = $params['email'];
                    break;
                case 'mailchimp':
                    if ($integrations['mailchimp']['active'] && !empty($integrations['mailchimp']['api_key'])) {
                        $mailchimp = new MailChimp($integrations['mailchimp']['api_key']);
                        $method = 'lists/subscribe';
                        $email = new stdClass();
                        $email->email = $params['EMAIL'];
                        $email->euid = 0;
                        $email->leid = 0;
                        unset($params['EMAIL']);
                        $args = array(
                            'id' => $data->selected_form,
                            'email' => $email,
                            'send_welcome' => true,
                            'double_optin' => $integrations['mailchimp']['double_optin'],
                            'merge_vars' => $params
                        );
                        $result = $mailchimp->call($method, $args);
                    }
                    $tracking_email = $params['EMAIL'];
                    break;
                case 'icontact':
                    if ($integrations['icontact']['active']) {
                        // Give the API your information
                        iContactApi::getInstance()->setConfig(array(
                            'appId' => $email_services->icontact_app_id,
                            'apiPassword' => $integrations['icontact']['api_password'],
                            'apiUsername' => $integrations['icontact']['api_username']
                        ));
                        $oiContact = iContactApi::getInstance();
                        try {
                            $contact = $oiContact->addContact($params['email'], 'normal', null, $params['name'], '', '', '', '', '', '', '', $params['phone']);
                        } catch (Exception $oException) { // Catch any exceptions
                            $error = $oiContact->getErrors();
                        }
                        if (!empty($contact)) {
                            try {
                                $oiContact->subscribeContactToList($contact->contactId, $data->selected_form, 'normal');
                            } catch (Exception $oException) {
                                $error = $oiContact->getErrors();
                            }
                        }
                    }
                    $tracking_email = $params['email'];
                    break;
                case 'getresponse':
                    if ($integrations['getresponse']['active']) {
                        if (!$integrations['getresponse']['copy_paste']) {
                            $instance = new GetResponse($integrations['getresponse']['api_key']);
                            $name = $params['name'] ? $params['name'] : 'AdvisorLead User';
                            $email = $params['email'] ? $params['email'] : '';
                            unset($params['name']);
                            unset($params['email']);
                            $result = $instance->addContact($data->selected_form, $name, $email, 'standard', '0', $params);
                        }
                    }
                    $tracking_email = $params['email'];
                    break;
                case 'constantcontact':
                    if ($integrations['constantcontact']['active']) {
                        $api_key = $email_services->cc_app_key;
                        $access_token = $integrations['constantcontact']['access_token'];
						
						//var_dump($api_key);
						//var_dump($access_token);
						//exit;
						
                        $cc = new ConstantContact($api_key);
                        try {
                            // check to see if a contact with the email addess already exists in the account
                            $response = $cc->getContactByEmail($access_token, $params['email']);

                            if (empty($response->results)) {
                                $contact = new Contact();
                                $contact->addEmail($params['email']);
                                $contact->addList($data->selected_form);
                                $contact->first_name = $params['first_name'];
                                $contact->last_name = $params['last_name'];
                                $contact->home_phone = $params['home_phone'];
                                $contact->work_phone = $params['work_phone'];
                                $contact->cell_phone = $params['cell_phone'];
                                $returnContact = $cc->addContact($access_token, $contact);

                                // update the existing contact if address already existed
                            } else {
                                $contact = $response->results[0];
                                $contact->addList($data->selected_form);
                                $contact->first_name = $params['first_name'];
                                $contact->last_name = $params['last_name'];
                                $contact->home_phone = $params['home_phone'];
                                $contact->work_phone = $params['work_phone'];
                                $contact->cell_phone = $params['cell_phone'];
                                $returnContact = $cc->updateContact($access_token, $contact);
                            }
                        } catch (CtctException $ex) {
//                            echo '<pre>' . print_r($ex->getErrors()) . '</pre>';
//                            exit(1);
                        }
                    }
                    $tracking_email = $params['email'];
                    break;
                case 'infusionsoft':
                    if ($integrations['infusionsoft']['active']) {
                        $app_name = $integrations['infusionsoft']['app_name'];
                        $api_key = $integrations['infusionsoft']['api_key'];

                        $is = new iSDK();
                        $connected = $is->cfgCon($app_name, $api_key);
                        if ($connected) {
                            $html = $is->getWebFormHtml($data->selected_form);

                            $document = new DOMDocument();
                            $document->loadHTML($html);

                            $xpath = new DOMXPath($document);
                            $forms = $xpath->query('//form');

                            foreach ($forms as $form) {
                                $action = $form->getAttribute('action');
                            }

                            $inputs = $xpath->query('//input[@name="infusionsoft_version"]');
                            foreach ($inputs as $input) {
                                $version = $input->getAttribute('value');
                            }
                            $fields = array_merge(array('infusionsoft_version' => $version), $params);
                            $data = http_build_query($fields);

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $action);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                    $tracking_email = $params['inf_field_Email'];
                    break;
                case "gotowebinar":
                    $gotowebinar = json_decode(get_user_meta($cta->uid, 'gotowebinar', true));
                    $citrix = new Citrix($email_services->gotowebinar_key);
                    $citrix->set_organizer_key($gotowebinar->organizer_key);
                    $citrix->set_access_token($gotowebinar->access_token);

                    $citrix->citrixonline_create_registrant_of_webinar($data->selected_form, $data = array(
                        'first_name' => !empty($params['name']) ? $params['name'] : 'MITS',
                        'last_name' => '.',
                        'email' => $params['email'],
                        'phone' => !empty($params['phone']) ? $params['phone'] : '',
                    ));
                    $tracking_email = $params['email'];
                    break;
            }

            JLoader::import('analytics', JPATH_BASE . DS . 'components' . DS . 'com_advisorlead' . DS . 'controllers');
            $analytics_controller = new AdvisorLeadControllerAnalytics();
            $values = array(
                'id' => $cta_id,
                'type' => 'optin',
                'tracking_type' => 'cta',
                'email' => $tracking_email,
                'ip' => AdvisorleadHelper::get_IP(),
                'created' => time()
            );
            $analytics_controller->tracking($values);
        }
        exit;
    }

}

?>