<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class AdvisorLeadControllerCTA extends JControllerLegacy {

    function api_call() {

        $app = JFactory::getApplication();
        $input = $app->input;
        $request = $input->getString('request', '');
        $results = array();
        switch ($request) {
            case "get_ctas":
                $request = JRequest::get();
                $results = $this->get_ctas_content($request);
                break;
            case "save-cta-display":
                $request = JRequest::get();
                $results = $this->save_cta_display_content($request);
                break;
        }
        echo json_encode($results);

        $app->close();
    }

    function get_ctas_content($request) {
        global $user;

        $model = $this->getModel('cta');

        $more = false;
        $paged = 1;
        $per_page = 15;
        $order = !empty($request['order']) ? $request['order'] : '';
        $cstr = !empty($request['cstr']) ? $request['cstr'] : '';

        if (!empty($cstr)) {
            $paged = substr($cstr, 0, 1);
            $paged++;
        }

        $total = $model->get_total_ctas($user->id);
        $ctas = $model->get_ctas($user->id, $paged, $per_page, $order);

        $count = count($ctas);
        if (!empty($ctas)) {
            $path = HTML_TEMPLATES_PATH . 'cta_list_data.php';
            $html = AdvisorleadHelper::render($path, array('ctas' => $ctas));
        }
        else
            $html = '<h3 style="text-align: center; margin-top: 20px">No CTAs found!</h3>';

        $last_page = $total / $per_page;
        if ($count == $per_page && $total > $per_page && $paged != $last_page)
            $more = true;
        $cstr = $paged . sha1(time());

        $results = array(
            'status' => 200,
            'body' => array('has_more' => $more, 'cstr' => $cstr, 'html' => $html)
        );
        return $results;
    }

    function save_cta_display_content($request) {
        $db = JFactory::getDbo();
        $result = array('status' => 400, 'body' => array());

        $update_arr = array(
            'id' => $request['cta_id'],
            'display_data' => stripslashes(nl2br($request['data'])),
            'updated' => time(),
            'status' => 1
        );

        $updates = (object) $update_arr;
        $updated = $db->updateObject(CTA_TABLE_UNQUOTE, $updates, 'id');

        //Unpublish the rests
        $fields = array(
            $db->quoteName('status') . ' = 0'
        );

        $conditions = array(
            $db->quoteName('id') . " != " . $request['cta_id']
        );

        $query = $db->getQuery(true);
        $query->update(CTA_TABLE)->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();

        $result['status'] = 200;

        return $result;
    }

    function cta_integrations($type) {
        global $user;
        $result = array('status' => 201, 'lists' => array(), 'message' => '');
        $lists = array();

        $integrations = AdvisorleadHelper::get_integrations($user->id);
        $email_services_options = AdvisorleadHelper::get_option('email_services');
        $email_services = json_decode($email_services_options);

        switch ($type) {
            case "aweber":
                $aweber = new AWeberAPI($email_services->aweber_key, $email_services->aweber_secret);
                try {
                    $account = $aweber->getAccount($integrations['aweber']['access_token'], $integrations['aweber']['access_token_secret']);
                    foreach ($account->lists as $list) {
                        foreach ($list->web_forms as $web_form) {
                            $lists["$list->id/$web_form->id"] = $list->name . '*@*' . $web_form->name;
                        }
                    }
                } catch (AWeberAPIException $exc) {
                    $result['message'] = $exc->message;
                }
                break;
            case "mailchimp":
                $mailchimp = new MailChimp($integrations['mailchimp']['api_key']);
                $method = 'lists/list';
                $args = array('limit' => 100);
                $result = $mailchimp->call($method, $args);
                foreach ($result['data'] as $item) {
                    $lists[$item['id']] = $item['name'];
                }
                break;
            case "infusionsoft":
                $is = new iSDK();
                $connected = $is->cfgCon($integrations['infusionsoft']['app_name'], $integrations['infusionsoft']['api_key']);
                if (!$connected) {
                    return;
                }

                $map = $is->getWebFormMap();
                if (!empty($map)) {
                    foreach ($map as $id => $name) {
                        $lists[$id] = $name;
                    }
                }
                break;
            case "constantcontact":
                $access_token = $integrations['constantcontact']['access_token'];
                $constantcontact = new ConstantContact($email_services->cc_app_key);
                try {
                    $forms = $constantcontact->getLists($access_token);
                    if (!empty($forms)) {
                        foreach ($forms as $form) {
                            $lists[$form->id] = $form->name;
                        }
                    }
                } catch (CtctException $ex) {
                    $result['message'] = $exc->getTraceAsString();
                }

                break;
            case "icontact":
                iContactApi::getInstance()->setConfig(array(
                    'appId' => $email_services->icontact_app_id,
                    'apiPassword' => $integrations['icontact']['api_password'],
                    'apiUsername' => $integrations['icontact']['api_username']
                ));
                $iContact = iContactApi::getInstance();
                try {
                    $forms = $iContact->getLists();
                    if (!empty($forms)) {
                        foreach ($forms as $form) {
                            $lists[$form->listId] = $form->name;
                        }
                    }
                } catch (Exception $oException) {
                    $result['message'] = $iContact->getErrors();
                }
                break;

            case "getresponse":
                $instance = new GetResponse($integrations['getresponse']['api_key']);
                $forms = $instance->getCampaigns();
                foreach ($forms as $id => $form) {
                    $lists[$id] = $form->name;
                }
                break;
        }

        if (!empty($lists)) {
            $result['status'] = 200;
            $result['lists'] = $lists;
        }
        return $result;
    }

    function cta_form_fields($selected_integration, $form_id) {
        global $user;
        $result = array('status' => 201);

        $integrations = $integrations = AdvisorleadHelper::get_integrations($user->id);
        $email_services_options = AdvisorleadHelper::get_option('email_services');
        $email_services = json_decode($email_services_options);

        $text_input = $hidden_input = array();

        if (!empty($form_id) && !empty($selected_integration)) {
            switch ($selected_integration) {
                case "aweber":
                    $form_id = explode('/', $form_id);
                    $aweber = new AWeberAPI($email_services->aweber_key, $email_services->aweber_secret);
                    try {
                        $account = $aweber->getAccount($integrations['aweber']['access_token'], $integrations['aweber']['access_token_secret']);
                        $listURL = "$account->lists_collection_link/{$form_id[0]}/web_forms/{$form_id[1]}";
                        $form = $account->loadFromUrl($listURL);
                        $form_html_link = str_replace('.html', '.htm', $form->html_source_link);

                        $form_html = file_get_contents($form_html_link);
                        $document = new DOMDocument();
                        libxml_use_internal_errors(true);
                        $document->loadHTML($form_html);
                        $xpath = new DOMXPath($document);

                        $form_element = $xpath->query('//form')->item(0);

                        if (empty($form_element)) {
                            $result['message'] = 'Sorry, we don\'t support the this form type!';
                            return;
                        }

                        $inputs = $xpath->query('//input');
                        if (!empty($inputs)) {

                            foreach ($inputs as $input) {
                                $type = $input->getAttribute('type');
                                if ($input->getAttribute('name') == 'meta_required') {
                                    $required_fields = explode(',', $input->getAttribute('value'));
                                }

                                if ($type == 'text') {
                                    $required = 0;
                                    if (in_array($input->getAttribute('name'), $required_fields)) {
                                        $required = 1;
                                    }

                                    $text_input[] = array(
                                        'display_name' => $input->getAttribute('name'),
                                        'field_name' => $input->getAttribute('name'),
                                        'required' => $required,
                                        'is_show' => 1
                                    );
                                } else if ($type == 'hidden') {
                                    $hidden_input[] = array(
                                        'display_name' => $input->getAttribute('name'),
                                        'field_name' => $input->getAttribute('name'),
                                        'value' => $input->getAttribute('value'),
                                    );
                                }
                            }
                            $result['status'] = 200;
                            $result['content'] = array('text_input' => $text_input, 'hidden_input' => $hidden_input);
                        }
                    } catch (AWeberAPIException $exc) {
//                        print "<h3>AWeberAPIException:</h3>";
//                        print " <li> Type: $exc->type              <br>";
//                        print " <li> Msg : $exc->message           <br>";
//                        print " <li> Docs: $exc->documentation_url <br>";
//                        print "<hr>";
                        $result['message'] = $exc->message;
                    }
                    break;
                case "mailchimp":
                    $mailchimp = new MailChimp($integrations['mailchimp']['api_key']);
                    $method = 'lists/merge-vars';
                    $response = $mailchimp->call($method, array('id' => array($form_id)));
                    $data = $response['data'][0];
                    if (!empty($data)) {
                        foreach ($data['merge_vars'] as $field) {
                            if ($field['public']) {
                                $text_input[] = array(
                                    'display_name' => $field['name'],
                                    'field_name' => $field['tag'],
                                    'required' => $field['req'] ? 1 : 0,
                                    'is_show' => $field['show'] ? 1 : 0
                                );
                            } else {
                                $hidden_input[] = array(
                                    'display_name' => $field['name'],
                                    'field_name' => $field['tag'],
                                    'value' => '',
                                );
                            }
                        }
                    }
                    $result['status'] = 200;
                    $result['content'] = array('text_input' => $text_input, 'hidden_input' => $hidden_input);
                    break;
                case "infusionsoft":
                    $is = new iSDK();
                    $connected = $is->cfgCon($integrations['infusionsoft']['app_name'], $integrations['infusionsoft']['api_key']);
                    if ($connected) {
                        $form_html = $is->getWebFormHtml($form_id);
                        $document = new DOMDocument();
                        libxml_use_internal_errors(true);
                        $document->loadHTML($form_html);
                        $xpath = new DOMXPath($document);

                        $form_element = $xpath->query('//form')->item(0);

                        if (empty($form_element)) {
                            $result['message'] = 'Sorry, we don\'t support the this form type!';
                            return;
                        }

                        $inputs = $xpath->query('//input');
                        if (!empty($inputs)) {
                            foreach ($inputs as $input) {
                                $type = $input->getAttribute('type');
                                if ($type == 'hidden') {
                                    $hidden_input[] = array(
                                        'display_name' => $input->getAttribute('name'),
                                        'field_name' => $input->getAttribute('name'),
                                        'value' => $input->getAttribute('value'),
                                    );
                                }
                            }
                        }

                        $labels = $xpath->query('//label');
                        if (!empty($labels)) {
                            foreach ($labels as $label) {
                                $field_name = $label->getAttribute('for');
                                $display_name = $label->textContent;
                                $required = 0;
                                if (strpos($label->textContent, '*') !== false) {
                                    $text = explode('*', $label->textContent);
                                    $display_name = trim($text[0]);
                                    $required = 1;
                                }

                                $text_input[] = array(
                                    'display_name' => $display_name,
                                    'field_name' => $field_name,
                                    'required' => $required,
                                    'is_show' => 1
                                );
                            }
                        }

                        $result['status'] = 200;
                        $result['content'] = array('text_input' => $text_input, 'hidden_input' => $hidden_input);
                    }
                    break;

                case "icontact":
                    $text_input = AdvisorleadHelper::icontact_init_fields();
                    $result['status'] = 200;
                    $result['content'] = array('text_input' => $text_input, 'hidden_input' => $hidden_input);
                    break;

                case "getresponse":
                    $instance = new GetResponse($integrations['getresponse']['api_key']);
                    $fields = $instance->getAccountCustoms();
                    $text_input = array(
                        array(
                            'display_name' => 'Email',
                            'field_name' => 'email',
                            'required' => 1,
                            'is_show' => 1
                        ),
                        array(
                            'display_name' => 'Name',
                            'field_name' => 'name',
                            'required' => 0,
                            'is_show' => 1
                        )
                    );
                    if (!empty($fields)) {
                        foreach ($fields as $field) {
                            $text_input[] = array(
                                'display_name' => ucfirst(str_replace('_', ' ', $field->name)),
                                'field_name' => $field->name,
                                'required' => 0,
                                'is_show' => 0
                            );
                        }
                    }
                    $result['status'] = 200;
                    $result['content'] = array('text_input' => $text_input, 'hidden_input' => $hidden_input);
                    break;

                case "constantcontact":
                    $text_input = AdvisorleadHelper::constantcontact_init_fields();
                    $result['status'] = 200;
                    $result['content'] = array('text_input' => $text_input, 'hidden_input' => $hidden_input);
                    break;
            }
        } else {
            $result['message'] = 'Can\'t get the form, please try again!';
        }

        return $result;
    }
}