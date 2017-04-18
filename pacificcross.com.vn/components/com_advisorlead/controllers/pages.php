<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;

use Ctct\Services\ListService;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Components\EmailMarketing\MessageFooter;
use Ctct\Components\EmailMarketing\Schedule;
use Ctct\Exceptions\CtctException;

class AdvisorLeadControllerPages extends JControllerLegacy {

    function api_call() {

        $app = JFactory::getApplication();
        $input = $app->input;
        $request = $input->getString('request', '');
        $results = array();
        switch ($request) {
		case "get_pages":
        	$request = JRequest::get();
            $results = $this->get_pages_content($request);
            break;
		#HT
		case "send_email":
			
			$page_id = JRequest::getInt('pageid');
			$cclist = JRequest::getVar('cclist', array(), 'post', 'array');
			$from_email_address = JRequest::getVar('from_email_address', NULL);
			$from_name = JRequest::getVar('from_name', NULL);
			
			//$results = array('page_id' => $page_id, 'cclist' => $cclist );
			
			$results['status'] = 0;
			
			
			$model = $this->getModel('pages');
			$page = $model->get_page($page_id);
			
			
			$user = JFactory::getUser();
			$integrations = AdvisorleadHelper::get_integrations($user->id);
			$email_services_options = AdvisorleadHelper::get_option('email_services');
			$email_services = json_decode($email_services_options);
			$api_key = $email_services->cc_app_key;
			$canEmail = false;
			if ($integrations['constantcontact']['active'] && $integrations['constantcontact']['access_token'] ) {
			
				$canEmail = true;
				
				$access_token = $integrations['constantcontact']['access_token'];
			
			
			}//if
			
			
			if ( $canEmail && $page && count($cclist) && $from_email_address && $from_name ) {
				
				$publish_url = JURI::root(false).$page->slug;
							
				$body = array();
				
				$body[] = "<html>";
				$body[] = "<body>";
				$body[] = "<p>Please visit my page:</p>";
				$body[] = "<p><a href=\"$publish_url\">$page->name</a></p>";
				$body[] = "</body>";
				$body[] = "</html>";
				
				$msg = implode($body);
				$subject = 'Advisor Lead Page: '.$page->name;
				
				$cc = new ConstantContact($api_key);	
				$campaign = new Campaign();
	
				$campaign_title = time().'_'.$subject;
				$campaign_title =  substr($campaign_title, 0, 78);
				$campaign->name = $campaign_title;
				$campaign->subject = $subject;

				$campaign->from_name = $from_name;
				$campaign->from_email = $from_email_address;
				$campaign->reply_to_email = $from_email_address;
		  
				$campaign->greeting_string = $subject;		
				$campaign->text_content = $subject;			
				$campaign->email_content_format = 'HTML';
				$campaign->email_content = $msg;
				
				foreach($cclist as $g){
					$campaign->addList($g);
				}//for
				
				try{
					$return = $cc->addEmailCampaign($access_token, $campaign);
					try{
						$schedule = new Schedule();
						$cc->addEmailCampaignSchedule($access_token, $return->id, $schedule);
						
						$results['email_id'] = $return->id;
						$results['status'] = $return->id;
						
					}catch (CtctException $ex2) {
						$ccerror = $ex2->getErrors();
						$results['msg'] = JText::_($ccerror[0]['error_message'])." - (from Constant Contact)";
					}
					
				} catch (CtctException $ex) {
					$ccerror = $ex->getErrors();
					$results['msg'] = JText::_($ccerror[0]['error_message'])." - (from Constant-Contact)";
				}//try
				
			} else {
				$results['msg'] = 'Invalid data';
			}//if
			
			break;
        }//switch
		
		
        echo json_encode($results);

        $app->close();
    }

    function get_pages_content($request) {
        global $user;

        $model = $this->getModel('pages');

        $more = false;
        $paged = 1;
        $per_page = 15;
        $order = !empty($request['order']) ? $request['order'] : '';
        $cstr = !empty($request['cstr']) ? $request['cstr'] : '';

        if (!empty($cstr)) {
            $paged = substr($cstr, 0, 1);
            $paged++;
        }

        $total = $model->get_total_pages($user->id);
        $pages = $model->get_pages($user->id, $paged, $per_page, $order);

        $count = count($pages);
        if (!empty($pages)) {
            $path = HTML_TEMPLATES_PATH . 'page_list_data.php';
            $html = AdvisorleadHelper::render($path, array('pages' => $pages));
        }
        else
            $html = '<h3 style="text-align: center; margin-top: 20px">No pages found!</h3>';

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

    function page_integrations($type) {
        global $user;
        $result = array("status" => 201, "body" => ADVISORLEAD_URL + "/dashboard/integrations/$type/");
        $lists = array();

        $integrations = AdvisorleadHelper::get_integrations($user->id);
        $email_services_options = AdvisorleadHelper::get_option('email_services');
        $email_services = json_decode($email_services_options);


        switch ($type) {
            case "aweber":
                if ($integrations['aweber']['copy_paste']) {
                    $result = array('status' => 203, 'body' => 'Copy&Paste');
                } else {
                    $consumerKey = $email_services->aweber_key;
                    $consumerSecret = $email_services->aweber_secret;
                    $aweber = new AWeberAPI($consumerKey, $consumerSecret);
                    $aweber->adapter->debug = false;
                    $account = $aweber->getAccount($integrations['aweber']['access_token'], $integrations['aweber']['access_token_secret']);
                    $lists = array();
                    foreach ($account->lists as $list) {
                        $item = array(
                            "web_forms" => array(),
                            "id" => $list->id,
                            "name" => $list->name
                        );
                        foreach ($list->web_forms as $web_form) {
                            $form = array(
                                "html_source_link" => $web_form->html_source_link,
                                "id" => $web_form->id,
                                "name" => $web_form->name
                            );
                            $item["web_forms"][] = $form;
                        }
                        $lists[] = $item;
                    }
                    if (!empty($lists))
                        $result = array("status" => 200, "body" => $lists);
                }
                break;
            case "mailchimp":
                $mailchimp = new MailChimp($integrations['mailchimp']['api_key']);
                $method = 'lists/list';
                $args = array('limit' => 100);
                $list_data = $mailchimp->call($method, $args);
                $list = array();
                foreach ($list_data['data'] as $item) {
                    $list[] = array(
                        "web_id" => $item['web_id'],
                        "name" => $item['name'],
                        "member_count" => $item['stats']['member_count'],
                        "email_type_option" => $item['email_type_option'],
                        "member_count_since_send" => $item['stats']['member_count_since_send'],
                        "default_language" => $item['default_language'],
                        "default_from_name" => $item['default_from_name'],
                        "default_subject" => $item['default_subject'],
                        "date_created" => $item['date_created'],
                        "default_from_email" => $item['default_from_email'],
                        "unsubscribe_count" => $item['stats']['unsubscribe_count'],
                        "cleaned_count" => $item['stats']['cleaned_count'],
                        "unsubscribe_count_since_send" => $item['stats']['unsubscribe_count_since_send'],
                        "list_rating" => $item['list_rating'],
                        "id" => $item['id'],
                        "cleaned_count_since_send" => $item['stats']['cleaned_count_since_send']
                    );
                }
                $result = array("status" => 200, "body" => $list);
                break;
            case "infusionsoft":
                if ($integrations['infusionsoft']['active']) {
                    if ($integrations['infusionsoft']['copy_paste']) {
                        echo '{"status": 203, "body": "Copy&Paste"}';
                    } else {
                        $app_name = $integrations['infusionsoft']['app_name'];
                        $api_key = $integrations['infusionsoft']['api_key'];

                        $is = new iSDK();
                        $connected = $is->cfgCon($app_name, $api_key);
                        if ($connected) {
                            $map = $is->getWebFormMap();

                            $result = array("status" => 200, "body" => array());
                            foreach ($map as $id => $name)
                                $result["body"][] = array(
                                    "id" => $id,
                                    "name" => $name
                                );
                        }
                    }
                }
                break;
            case "constantcontact":
                $api_key = $email_services->cc_app_key;
                if ($integrations['constantcontact']['active']) {
                    $access_token = $integrations['constantcontact']['access_token'];
                    $cc = new ConstantContact($api_key);
                    $lists = array();
                    try {
                        $lists = $cc->getLists($access_token);
                    } catch (CtctException $ex) {
                        
                    }
                    $result = array("status" => 200, "body" => array());
                    if (!empty($lists)) {
                        foreach ($lists as $list) {
                            $result["body"][] = array(
                                "status" => $list->status,
                                "modified_date" => date('Y-m-d'),
                                "name" => $list->name,
                                "contact_count" => $list->contact_count,
                                "created_date" => date('Y-m-d'),
                                "id" => $list->id
                            );
                        }
                    }
                }

                break;
            case "icontact":
                if ($integrations['icontact']['active']) {
                    // Give the API your information
                    iContactApi::getInstance()->setConfig(array(
                        'appId' => $email_services->icontact_app_id,
                        'apiPassword' => $integrations['icontact']['api_password'],
                        'apiUsername' => $integrations['icontact']['api_username']
                    ));
                    $oiContact = iContactApi::getInstance();
                    $error = NULL;
                    $lists = NULL;
                    try {
                        $lists = $oiContact->getLists();
                    } catch (Exception $oException) { // Catch any exceptions
                        $error = $oiContact->getErrors();
                    }
                    if (!$error) {
                        $result = array("status" => 200, "body" => array());
                        if (is_array($lists) && !empty($lists)) {
                            foreach ($lists as $list) {
                                $result["body"][] = array(
                                    "id" => $list->listId,
                                    "folder_id" => $list->listId,
                                    "name" => $list->name,
                                    "list_id" => $list->listId
                                );
                            }
                        }
                    }
                }
                break;

            case "getresponse":
                if ($integrations['getresponse']['active']) {
                    if ($integrations['getresponse']['copy_paste']) {
                        $result = array('status' => 203, 'body' => 'Copy&Paste');
                    } else {
                        $instance = new GetResponse($integrations['getresponse']['api_key']);
                        $lists = $instance->getCampaigns();
                        $result = array("status" => 200, "body" => array());
                        foreach ($lists as $id => $list) {
                            $result["body"][] = array(
                                "id" => $id,
                                "name" => $list->name
                            );
                        }
                    }
                }
                break;

            case "gotowebinar":
//
//                if (isset($gotowebinar->access_token)) {
//                    $email_services = get_option('email_services', true);
//
//
//                    $citrix = new Citrix($email_services->gotowebinar_key);
//
//                    $citrix->set_organizer_key($gotowebinar->organizer_key);
//                    $citrix->set_access_token($gotowebinar->access_token);
//                    $list = $citrix->citrixonline_get_list_of_webinars();
//                    $list = $list['upcoming']['webinars'];
//
//                    if (empty($list['errorCode'])) {
//
//                        if (!empty($list)) {
//                            $result = array("status" => 200, "body" => array());
//                            foreach ($list as $data) {
//                                $result['body'][] = array(
//                                    'subject' => $data['subject'],
//                                    'webinarKey' => $data['webinarKey']
//                                );
//                            }
//
//                            echo json_encode($result);
//                        }
//                        else
//                            echo '{"status": 201, "body": "/my-account/", "error": "You don\'t have any upcoming webinars. Please create a webinar in GotoWebinar first."}';
//                    }
//                    else
//                        echo '{"status": 201, "body": "/my-account/", "error": "' . $list['description'] . '"}';
//                }
//                else
//                    echo '{"status": 201, "body": "/my-account/"}';

                break;
        }

        if (!empty($lists)) {
            $result['status'] = 200;
            $result['lists'] = $lists;
        }
        return $result;
    }

    function page_form_fields($selected_integration, $form_id) {
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
	
	function addfppage() {
	
		$db = JFactory::getDBO();
	
	
		$app_id = JRequest::getVar('app_id', NULL);
		$page_id = JRequest::getVar('page_id', NULL);
		$tabs_added = JRequest::getVar('tabs_added', NULL);
		
		
		if ($app_id && $page_id && is_array($tabs_added) && count($tabs_added)) {
		
			$new_facebook_page_id = array_shift(array_keys($tabs_added));
			$new_facebook_page = explode(',', $new_facebook_page_id);
			foreach ($new_facebook_page as $fb_page ) {
				$query = "delete from `#__advisorlead_fb_page` where app_id = '$app_id' AND fb_page = '$fb_page'";
				$db->setQuery($query);
				$db->execute();
				
				$query = "insert into `#__advisorlead_fb_page` set app_id = '$app_id', page_id = '$page_id', fb_page = '$fb_page'";
				$db->setQuery($query);
				$db->execute();
				
			}//for
			
			$this->setMessage('Done');
		
		}//if
		
		
		$this->setRedirect('index.php?option=com_advisorlead&view=pages');
	
		return false;
	}//func
	
	function fbupdatepage() {
	
		$result = array('status' => 0, 'msg' => '');
		
		$fb_page_id = JRequest::getInt('fb', 0);
		$pageid = JRequest::getInt('id', 0);
		
		if ( $fb_page_id && $pageid ) {
			$db = JFactory::getDBO();
			
			$query = $db->getQuery(true);
			
			$query->update('#__advisorlead_fb_page')
					->set('page_id = '.(int)$pageid)
					->where('id = '.(int)$fb_page_id);
			$db->setQuery($query);
			$db->execute();

			$result['status'] = 1;
			
		}//if
	
		
		echo json_encode($result);	
		exit;
	}//func

}