<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class AdvisorleadModelPages extends JModelList {

    function get_page($pid = '', $uid = '', $slug = '', $article_id = 0) {
        $db = JFactory::getDbo();
        $where = '';
        if (!empty($pid)) {
            $where = " AND p.id = $pid";
        }
        if (!empty($uid)) {
            $where .= " AND p.uid = $uid";
        }
        if (!empty($slug)) {
            $where .= " AND p.slug LIKE '$slug'";
        }
        if ($article_id > 0) {
            $where .= " AND p.article_id = '$article_id'";
        }
        $query = "SELECT p.*,t.js_variables,t.slug as template_slug FROM " . PAGES_TABLE . " p LEFT JOIN " . TEMPLATES_TABLE . " t ON t.id = p.template_id WHERE 1 $where LIMIT 1";
        $db->setQuery($query);
        return $db->loadObject();
    }
	
	//do_update = id of update page
	function check_menu_alias($slug, $do_update = 0) {
		$db = JFactory::getDbo();
		
		$where = array();
		$where[] = "`alias` = ".$db->quote($slug);
		if ($do_update) $where[] = "`id` != ".intval($do_update);
		$sql = "select count(*) from #__menu where ".implode(' and ', $where);
		$db->setQuery($sql);
		
        return $db->loadResult();
		
		
	}//func

    function get_total_pages($uid) {
        $db = JFactory::getDbo();
        $query = "SELECT COUNT(*) FROM " . PAGES_TABLE . " WHERE uid = $uid";
        $db->setQuery($query);
        return $db->loadResult();
    }

    function get_pages($uid, $paged, $per_page, $order) {
        $db = JFactory::getDbo();

        $query = "SELECT cc.*,t.slug as template_slug FROM " . PAGES_TABLE . " cc JOIN " . TEMPLATES_TABLE . " t ON cc.template_id = t.id WHERE uid = $uid";
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

    function get_page_data($page_id = 0, $template_id = 0) {

        global $user;
        $js_variables = '';
        $page = array();
        $integrations = AdvisorleadHelper::get_integrations($user->id);

        if (!empty($page_id)) {
            $page = $this->get_page($page_id, $user->id);
        } else if (!empty($template_id)) {
            $template_model = $this->getInstance('templates', 'AdvisorleadModel');
            $template = $template_model->get_template($template_id);
            $js_variables = $template->js_variables;
        }

        $page_id = 0;
        $article_id = 0;
        $page_name = '';
        $page_slug = '';

        $user_head_code = '';
        $user_end_code = '';
        $exit_popup = 0;
        $exit_popup_redirect = 0;
        $exit_popup_message = '';
        $exit_popup_redirect_url = '';
        $page_title = '';
        $page_description = '';
        $page_keywords = '';

        if (!empty($page)) {
            $page_id = $page->id;
            $article_id = $page->article_id;
            $page_name = $page->name;
            $page_slug = $page->slug;
            $user_head_code = $page->user_head_code;
            $user_end_code = $page->user_end_code;
            $js_variables = $page->js_variables;
            $exit_popup = $page->exit_popup;
            $exit_popup_redirect = $page->exit_popup_redirect;
            $exit_popup_message = $page->exit_popup_message;
            $exit_popup_redirect_url = $page->exit_popup_redirect_url;
            $page_title = $page->page_title;
            $page_description = $page->page_description;
            $page_keywords = $page->page_keywords;
        }

        $params = array(
            'is_edit' => !empty($page) ? true : false,
            'page_id' => $page_id,
            'article_id' => $article_id,
            'page_name' => $page_name,
            'page_slug' => $page_slug,
            'js_variables' => $js_variables,
            'user_head_code' => $user_head_code,
            'user_end_code' => $user_end_code,
            'exit_popup' => $exit_popup,
            'exit_popup_redirect' => $exit_popup_redirect,
            'exit_popup_message' => $exit_popup_message,
            'exit_popup_redirect_url' => $exit_popup_redirect_url,
            'page_title' => $page_title,
            'page_description' => $page_description,
            'page_keywords' => $page_keywords,
            'integrations' => $integrations
        );

        return (object) $params;
    }

    function get_page_fields_data($page_id) {

        $page = $this->get_page($page_id);
        $edit_data = json_decode($page->data);
        $font_data = json_decode($page->font_data);
        $color_data = json_decode($page->color_data);
        $js_data = json_decode($page->js_data);
        $return_data = array(
            'edit_data' => $edit_data,
            'font_data' => $font_data,
            'color_data' => $color_data,
            'js_variables_data' => $js_data
        );

        $result = array(
            'status' => 200,
            'body' => $return_data
        );
        return $result;
    }

    function save_page_data($request) {
        global $user;
        $result = array('status' => 400, 'body' => array());
        $name = !empty($request['page_name']) ? $request['page_name'] : '';
        $pid = !empty($request['page_id']) ? $request['page_id'] : 0;
        
		
		$page_url = $request['page_url'];
		
		//$slug = JApplication::stringURLSafe($name);
		
		$slug = JApplication::stringURLSafe($page_url);
        
		
		$db = JFactory::getDbo();

        if ($pid > 0) {

            $is_exists = $this->get_page($pid);
			

            if (!empty($is_exists)) {
			
				$menus = JSite::getMenu();
				$cur_menu = $menus->getItems(array('link', 'access'), array('index.php?option=com_content&view=article&id='.$is_exists->article_id, NULL), true );
				$cur_menu_id = $cur_menu->id;
				$is_menu_exist = $this->check_menu_alias($slug, $cur_menu_id);
				if ($is_menu_exist) {
					$result['body'] = array('message' => 'This page URL is already exists!', 'title' => 'This page URL is already used');
					return $result;
				}//if
			
			
                $update_arr = array(
                    'id' => $pid,
                    'name' => $name,
                    'slug' => $slug,
                    'data' => AdvisorleadHelper::clean_html($request['data']),
                    'color_data' => $request['color_data'],
                    'js_data' => $request['js_data'],
                    'font_data' => $request['font_data'],
                    'page_title' => $request['page_title'],
                    'page_keywords' => $request['page_keywords'],
                    'page_description' => $request['page_description'],
                    'exit_popup' => $request['exit_popup'] == 'true' ? 1 : 0,
                    'exit_popup_message' => $request['exit_popup_message'],
                    'exit_popup_redirect' => $request['exit_popup_redirect'] == 'true' ? 1 : 0,
                    'exit_popup_redirect_url' => $request['exit_popup_redirect_url'],
                    'user_head_code' => $request['user_head_code'],
                    'user_end_code' => $request['user_end_code'],
                    'updated' => time(),
                );
                $updates = (object) $update_arr;
                $updated = $db->updateObject(PAGES_TABLE_UNQUOTE, $updates, 'id');
                if ($updated) {
                    $result['status'] = 200;
                    $result['body']['id'] = $pid;
                    $result['body']['article_id'] = $is_exists->article_id;
					
					
					$sqlupdatemenu = "UPDATE #__menu SET `alias` = ".$db->quote($slug).", `path` = ".$db->quote($slug)." WHERE id = ".$cur_menu_id;
					$db->setQuery($sqlupdatemenu);
					$db->execute();
					
                }//if
            }
        } else {
            $is_exists = $this->get_page('', $user->id, $slug);
			
			
			$is_menu_exist = $this->check_menu_alias($slug);
			if ($is_menu_exist) {
				$result['body'] = array('message' => 'This page URL is already exists!', 'title' => 'This page URL is already used');
				return $result;
			}//if
			

            if (empty($is_exists)) {

                $article = JTable::getInstance('Content');

                $data = array(
                    'catid' => 0,
                    'title' => $name,
                    'introtext' => '<p>AdvisorLead Page</p>',
                    'fulltext' => '<p>AdvisorLead Page</p>',
                    'state' => 1,
                );

                if (!$article->bind($data)) {
                    $this->setError($article->getError());
                    return false;
                }

                if (!$article->check()) {
                    $this->setError($article->getError());
                    return false;
                }

                if (!$article->store()) {
                    $this->setError($article->getError());
                    return false;
                }
                
                $insert_arr = array(
                    'article_id' => $article->id,
                    'uid' => $user->id,
                    'template_id' => $request['template_id'],
                    'name' => $name,
                    'slug' => $slug,
                    'data' => AdvisorleadHelper::clean_html($request['data']),
                    'color_data' => $request['color_data'],
                    'js_data' => $request['js_data'],
                    'font_data' => $request['font_data'],
                    'page_title' => $request['page_title'],
                    'page_keywords' => $request['page_keywords'],
                    'page_description' => $request['page_description'],
                    'exit_popup' => $request['exit_popup'] == 'true' ? 1 : 0,
                    'exit_popup_message' => $request['exit_popup_message'],
                    'exit_popup_redirect' => $request['exit_popup_redirect'] == 'true' ? 1 : 0,
                    'exit_popup_redirect_url' => $request['exit_popup_redirect_url'],
                    'user_head_code' => $request['user_head_code'],
                    'user_end_code' => $request['user_end_code'],
                    'custom_domain' => $request['domain_url'],
                    'views' => 0,
                    'uniques' => 0,
                    'optins' => 0,
                    'rates' => 0,
                    'updated' => time(),
                    'created' => time(),
                );

                $inserts = (object) $insert_arr;
                $inserted = $db->insertObject(PAGES_TABLE_UNQUOTE, $inserts);
              
                if ($inserted) {

                    $pid = $db->insertid();
                     // update domain same
                    $sql_d = "UPDATE #__advisorlead_pages SET custom_domain = '' WHERE custom_domain LIKE '".$request['domain_url']."' and id <> $pid ";
                    $db->setQuery($sql_d);
                    $db->query();
                    $result['status'] = 200;
                    $result['body']['id'] = $pid;
                    $result['body']['article_id'] = $article->id;					
					//create menu
					//SHOULD find/check/add menutype no assign to any module
					$menutype = 'advisorlead-menu';					
					$pre_alias = 'root';
					$alias = $slug;
					$title = $name;					
					$link = 'index.php?option=com_content&view=article&id='.$article->id;
					
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
					
					$sqlinsertmenu = "INSERT INTO #__menu (menutype, title, alias, note, path, link, type, published, parent_id, level,component_id,  access, img, template_style_id,params, lft, rgt, home, language, client_id) VALUES('$menutype','$title','$alias','','$alias','$link','component',1,1,1,22,1,'',0,'" . json_encode($arr_params) . "', $lastRgt, ".($lastRgt+1).", 0, '*', 0)";
					
					$db->setQuery($sqlinsertmenu);
					$db->execute();
					
					$new_menuid = $db->insertid();
					
					
					$sqlinsertmenu = "UPDATE #__menu SET rgt=rgt+2 WHERE alias='$pre_alias'";
					
					$db->setQuery($sqlinsertmenu);
					$db->execute();
					
					//end menu
					
                }
            } else {
                $result['body'] = array('message' => 'This name is already exists!', 'title' => 'This name is already used');
            }
        }
        // send to domain
        if($request['domain_url'] != ''){
            $app = JFactory::getApplication();
            $fn = str_replace('.', '', $request['domain_url']);
            $fn = str_replace('http://', '', $fn);
            $fn = str_replace('/', '', $fn);
            $new_path = JPATH_BASE.DS. '..'.DS.$fn;
         
                     if(!is_dir($new_path)) {
                                // khong ton tai
                                mkdir("$new_path", 0777);
                                $page_id = $pid;
                                $model_page = JModelLegacy::getInstance('pages', 'AdvisorleadModel');
                                $page = $model_page->get_page($page_id);
                                $full_url = JURI::base() . "$page->slug";
                                $full_html = file_get_contents($full_url);                            
                                $pdf_file_path = $new_path.DS.'index.html';
                                $pdf_filename = file_put_contents($pdf_file_path, $full_html);                           
                                
                     }else{
                                $page_id = $pid;
                                $model_page = JModelLegacy::getInstance('pages', 'AdvisorleadModel');
                                $page = $model_page->get_page($page_id);
                                $full_url = JURI::base() . "$page->slug";
                                $full_html = file_get_contents($full_url);                           
                                $pdf_file_path = $new_path.DS.'index.html';
                                $pdf_filename = file_put_contents($pdf_file_path, $full_html);
                     }
               // get url 
		//echo $full_url;
		
        }
        return $result;
    }

    function get_page_html($template_id) {
        $template_model = JModelLegacy::getInstance('templates', 'AdvisorleadModel');
        $template = $template_model->get_template($template_id);
        $template_path = "/inc/page-templates/$template->slug/";
        $template_file = ASSETS_PATH . $template_path . 'template.html';
        $search = array('{LOCAL_URL}');
        $replace = array(ASSETS_URL . '/images/');
        $html = AdvisorleadHelper::render_replace($template_file, $search, $replace);
        return $html;
    }

    function load_page($page, $is_fb = 0) {

        $template_id = $page->template_id;
        if (!empty($page) && !empty($template_id)) {

            $page_template = $this->get_page_html($template_id, false);
            $document = new DOMDocument();
            libxml_use_internal_errors(true);
            $document->loadHTML($page_template);
            $xpath = new DOMXPath($document);
            $elements = json_decode($page->data);
            $lb_id = 'lb-id';
            $use_name = 'use-name';
            $use_phone = 'use-phone';
            $videos = array();
            $nvideo = 0;
            $gotowebinar = 0;
            foreach ($elements as $element) {
                if (!empty($element->type)) {
                    switch ($element->type) {
                        case 'image':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {

                                    $el->setAttribute("src", $element->url);

                                    if ($element->$lb_id == $elements->page_background_type->image_id && $elements->page_background_type->type == 'color') {
                                        $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }

                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                }
                            }
                            break;
                        case 'text':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $el->nodeValue = '';
                                    $frag = $document->createDocumentFragment();
                                    $text = str_replace('&nbsp;', ' ', htmlspecialchars($element->text));
                                    if ($frag->appendXML($text))
                                        $el->appendChild($frag);

                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                }
                            }
                            break;
                        case 'richtext-area':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {

                                    $el->nodeValue = '';
                                    $frag = $document->createDocumentFragment();
                                    if ($frag->appendXML(str_replace('<br>', '<br/>', $element->text)))
                                        $el->appendChild($frag);

                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                }
                            }
                            break;
                        case 'removable':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                }
                            }
                            break;
                        case 'link':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $el->setAttribute("href", $element->href);
                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                    if ($element->new_window || $is_fb) {
                                        $el->setAttribute("target", "_blank");
                                    }
                                    if ($element->nofollow) {
                                        $el->setAttribute("rel", "nofollow");
                                    }

                                    if (!empty($element->text)) {
                                        if (!$this->hasChild($el)) {
                                            $el->nodeValue = '';
                                            $el->nodeValue = $element->text;
                                        }
                                    }
                                }
                            }
                            break;
                        case 'video':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $videoOrImage = 'video-or-image';
                                    if (is_null($element->$videoOrImage) || $element->$videoOrImage) {
                                        $el->nodeValue = '';
                                        $element->value = rawurldecode($element->value);
                                        $element->value = preg_replace('/width=[\"\']{1}[0-9]*(px)?[\"\']{1}/', 'width="' . $element->width . '"', $element->value);
                                        $element->value = preg_replace('/height=[\"\']{1}[0-9]*(px)?[\"\']{1}/', 'height="' . $element->height . '"', $element->value);
                                        $el->nodeValue = '%VIDEO-' . $nvideo . '%';

                                        $iframe_style = 'display: block;';
                                        if (!empty($element->video_style)) {
                                            $video_style = '';
                                            switch ($element->video_style) {
                                                case "1":
                                                    $video_style = "background: url(" . ASSETS_URL . "/images/tablet-big.png) no-repeat; background-size: 100%; border: none";
                                                    $iframe_style .= 'top: 8%;left: 5%;width: 90%;height: 80%;';
                                                    break;
                                                case "2":
                                                    $video_style = "background: url(" . ASSETS_URL . "/images/video.png) no-repeat; border: 5px solid #fffffc; background-size: 100%";
                                                    $iframe_style .= 'top:0;left:0;width:100%;height:100%';
                                                    break;
                                                case "3":
                                                    $video_style = 'background: none; border: none';
                                                    $iframe_style .= 'top:0;left:0;width:100%;height:100%';
                                                    break;
                                            }

                                            $el->setAttribute("style", $video_style);
                                        }

                                        $new_iframe = $element->value;

                                        if (!empty($new_iframe)) {
                                            $doc_iframe = new DOMDocument();
                                            $doc_iframe->loadHTML($new_iframe);
                                            $iframe = $doc_iframe->getElementsByTagName('iframe')->item(0);

                                            if (!empty($iframe)) {
                                                $src = $iframe->getAttribute('src');
                                                if (strpos($src, '?') !== false) {
                                                    $src = "$src&wmode=opaque";
                                                } else {
                                                    $src = "$src?wmode=opaque";
                                                }

                                                $iframe->setAttribute('src', $src);
                                                $iframe->setAttribute('style', $iframe_style);
                                                $new_iframe = $doc_iframe->saveHTML();
                                            }
                                        }

                                        $videos[$nvideo++] = $new_iframe;
                                        if ($element->removable) {

                                            $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                            if ($element->removed) {
                                                $style = 'display:none;' . $style;
                                                $el->removeChild($el->firstChild);
                                            }
                                            $el->setAttribute("style", $style);
                                        }
                                    }
                                }
                            }
                            break;
                        case 'fadin-box':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $el->setAttribute("style", 'display: none;');
                                    $el->setAttribute('id', $element->$lb_id);
                                    $fadein_existed[] = array('id' => $element->$lb_id, 'time' => $element->time * 1000);
                                }
                            }
                            break;
                        case 'opt-in-form':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {

                                    $pre_name = $document->getElementById("name");
                                    if ($element->$use_name)
                                        $pre_name->setAttribute("style", "");
                                    $pre_phone = $document->getElementById("phone");
                                    if ($element->$use_phone)
                                        $pre_phone->setAttribute("style", "");

                                    $pre_email = $document->getElementById("email");

                                    if (($element->optin_type == 'aweber' && !$is_fb) || $element->optin_type == 'other' || ($element->optin_type == 'getresponse' && $element->redirect_url == '')) {

                                        if (is_numeric($element->value)) {
                                            $el->setAttribute("action", ADVISORLEAD_URL . '/pages/' . $page->id . '/optin/?is_fb=' . $is_fb);
                                            break;
                                        }

                                        $contents = rawurldecode($element->value);
                                        if ($element->optin_type == 'aweber') {
                                            $contents = str_replace('html', 'htm', $contents);
                                        }

                                        if (strpos($element->value, 'http://') === 0 || strpos($element->value, 'https://') === 0)
                                            $contents = wp_remote_retrieve_body(wp_remote_get($contents));

                                        $doc = new DOMDocument();
                                        $doc->loadHTML($contents);
                                        $style = $doc->getElementsByTagName('style')->item(0);
                                        $form_xpath = new DOMXPath($doc);

                                        $form = $form_xpath->query('//form')->item(0);

                                        if (empty($form)) {
                                            $iframe = $form_xpath->query('//iframe')->item(0);
                                            $iframe_src = $iframe->getAttribute('src');

                                            $contents = rawurldecode($iframe_src);
                                            $contents = str_replace('html', 'htm', $contents);

                                            $contents = wp_remote_retrieve_body(wp_remote_get($contents));

                                            $doc = new DOMDocument();
                                            $doc->loadHTML($contents);
                                            $style = $doc->getElementsByTagName('style')->item(0);
                                            $form_xpath = new DOMXPath($doc);

                                            $form = $form_xpath->query('//form')->item(0);
                                        }

                                        $inputs = $form_xpath->query('//input[@type="text"]');

                                        foreach ($inputs as $input) {
                                            $input_name = $input->getAttribute('name');
                                            if (strpos(strtolower($input_name), 'mail') !== false) {
                                                $pre_email->setAttribute('name', $input_name);
                                            }
                                            if (strpos(strtolower($input_name), 'fname') !== false || strpos(strtolower($input_name), 'first_name') !== false) {
                                                $pre_name->setAttribute('name', $input_name);
                                            }
                                            if (strpos(strtolower($input_name), 'phone') !== false) {
                                                $pre_phone->setAttribute('name', $input_name);
                                            }
                                        }

                                        $action = $form->getAttribute("action");

                                        $el->setAttribute("action", $action);
                                        $hiddens = $doc->getElementsByTagName("input");
                                        foreach ($hiddens as $hidden) {
                                            if ($hidden->getAttribute("type") == 'hidden') {
                                                if ($hidden->getAttribute('name') == 'redirect' || $hidden->getAttribute('name') == 'meta_redirect_onlist') {
                                                    $new_link = $this->get_final_redirect_url($hidden->getAttribute('value'));
                                                    $hidden->setAttribute('value', $new_link);
                                                }
                                                $elem = $document->importNode($hidden, true);
                                                $el->appendChild($elem);
                                            }
                                        }
                                    } else {

                                        $el->setAttribute("action", ADVISORLEAD_URL . '/pages/' . $page->id . '/optin/?is_fb=' . $is_fb);
                                    }
                                    if ($element->$use_name) {
                                        $remove_name = false;
                                    } else {
                                        $remove_name = true;
                                    }
                                    if ($element->$use_phone) {
                                        $remove_phone = false;
                                    } else {
                                        $remove_phone = true;
                                    }
                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }

                                    if ($element->webinar == 1 && !empty($element->webinar_key))
                                        $gotowebinar = $element->webinar_key;
                                }
                            }
                            break;
                        case 'text_input':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $el->setAttribute('value', $element->title);
                                    $el->setAttribute("title", $element->title);
                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }

                                    $wrapper = $el->parentNode;

                                    if ($remove_name && $element->$lb_id == 'opt-in-name') {
                                        if ($wrapper->getAttribute('data-lb') == 'opt-in-name-wrapper') {
                                            $wrapper->parentNode->removeChild($wrapper);
                                        } else {
                                            $wrapper->removeChild($el);
                                        }
                                    }

                                    if ($remove_phone && $element->$lb_id == 'opt-in-phone') {
                                        if ($wrapper->getAttribute('data-lb') == 'opt-in-phone-wrapper') {
                                            $wrapper->parentNode->removeChild($wrapper);
                                        } else {
                                            $wrapper->removeChild($el);
                                        }
                                    }
                                }
                            }
                            break;
                        case 'submit':
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $el->setAttribute('value', $element->text);
                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                }
                            }
                            break;
                        case "button":
                            $els = $xpath->query('//*[@data-lb-id="' . $element->$lb_id . '"]');
                            if ($els) {
                                foreach ($els as $el) {
                                    $el->nodeValue = '';
                                    $frag = $document->createDocumentFragment();
                                    $text = htmlspecialchars($element->text);
                                    if ($frag->appendXML($text))
                                        $el->appendChild($frag);

                                    $parent_styles = 'outline: none;';
                                    $el->parentNode->setAttribute("style", $parent_styles);

                                    $button_styles = 'transition: 0.5s all; cursor: pointer; ';
                                    $border_width = $elements->button_styles->slider->{$element->$lb_id};
                                    foreach ($border_width as $key => $value) {
                                        $button_styles .= "$key:{$value->value}px;";
                                    }

                                    $border_style = $elements->button_styles->select->{$element->$lb_id};
                                    foreach ($border_style as $key => $value) {
                                        $button_styles .= "$key:{$value->value};";
                                    }

                                    $el->setAttribute("style", $button_styles);

                                    if ($element->removable) {
                                        $style = preg_replace('/display:([^;]*)/', '', $el->getAttribute("style"));
                                        if ($element->removed)
                                            $style = 'display:none;' . $style;
                                        $el->setAttribute("style", $style);
                                    }
                                }
                            }
                            break;
                    }

                    $els = $xpath->query('//*[@data-lb="optin-form-trigger"]');
                    if ($els) {
                        foreach ($els as $el) {

                            $el->setAttribute('onclick', 'javascript:return window.triggerOptInForm()');
                        }
                    }
                }
            }

            $head = $document->getElementsByTagName("head")->item(0);

            $frag = $document->createDocumentFragment();
            $frag->appendXML('<script src="' . ASSETS_URL . '/js/page_templates.js"></script><script src="' . ASSETS_URL . '/js/page.js"></script>');
            $head->insertBefore($frag, $head->firstChild);

            $screenshot = ASSETS_URL . "/inc/page-templates/$page->template_slug/screenshot.png";
            $page_title = '';
            $og_fb = '<meta property="fb:admins" content="100008102106642"/>
                <meta property="fb:app_id" content="719478438075635"/>
                <meta property="og:type" content="website"/>
                    <meta property="og:image" content="' . $screenshot . '"/>';

            if (!empty($page->page_title)) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML('<title>' . $page->page_title . '</title>');
                $head->appendChild($frag);

                $page_title = $page->page_title;
            }

            $og_fb .= '<meta property="og:title" content="' . $page_title . '"/>';


            if (!empty($page->page_description)) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML('<meta content="' . htmlspecialchars($page->page_description) . '" name="description"/>');
                $head->appendChild($frag);
                $og_fb .= '<meta property="og:description" content="' . htmlspecialchars($page->page_description) . '"/>';
            }

            if (!empty($page->page_keywords)) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML('<meta content="' . $page->page_keywords . '" name="keywords"/>');
                $head->appendChild($frag);
            }

            $fb_url = $_SERVER['PHP_SELF'];

            $og_fb .= '<meta property="og:url" content="' . $fb_url . '"/>';

            if (!empty($_GET['tid'])) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML('<meta content="' . $_GET['tid'] . '" name="vc-meta-id"/>');
                $head->appendChild($frag);
            }

            $frag = $document->createDocumentFragment();
            $frag->appendXML('<meta content="' . $page->id . '" name="itps-meta-id"/>' . $og_fb);
            $head->appendChild($frag);

            $frag = $document->createDocumentFragment();
            $frag->appendXML('<meta content="' . $gotowebinar . '" name="gotowebinar-id"/>');
            $head->appendChild($frag);

            $frag = $document->createDocumentFragment();
            $frag->appendXML('%PAGE-SCRIPT%');
            $head->appendChild($frag);

            $fonts = json_decode($page->font_data);
            foreach ($fonts as $font) {
                if ($font->font != $font->value) {
                    $frag = $document->createDocumentFragment();
                    $frag->appendXML('<link href="https://fonts.googleapis.com/css?family=' . urlencode($font->value) . '" rel="stylesheet" type="text/css"/>');
                    $head->appendChild($frag);
                }
            }

            if (!empty($page->user_head_code)) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML(stripslashes(htmlspecialchars($page->user_head_code)));
                $head->appendChild($frag);
            }

            $body = $document->getElementsByTagName("body")->item(0);


            $body->setAttribute("onload", "itPagesOnLoadFn()");

            if (!empty($page->user_end_code)) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML('<div style="display:none;">' . stripslashes(htmlspecialchars($page->user_end_code)) . '</div>');
                $body->appendChild($frag);
            }


            $frag = $document->createDocumentFragment();
            $frag->appendXML('<script src="' . ASSETS_URL . '/js/track.js"></script>');
            $body->appendChild($frag);

            if ($is_fb) {
                $frag = $document->createDocumentFragment();
                $frag->appendXML('%FACEBOOK%');
                $body->insertBefore($frag, $body->firstChild);
            }

            $exit_popup = 'false';
            if ($page->exit_popup == 'true')
                $exit_popup = 'true';

            $page_js = file_get_contents(ASSETS_PATH . '/js/page.js');

            $page_js = str_replace('%EXIT_POPUP%', $exit_popup, $page_js);
            $page_js = str_replace('%EXIT_POPUP_MESSAGE%', str_replace(array("\r\n", "\r", "\n"), '\n', $page->exit_popup_message), $page_js);
            $page_js = str_replace('%EXIT_POPUP_REDIRECT_URL%', $page->exit_popup_redirect_url, $page_js);

            $page_js .= "var base_url = '" . JURI::base() . "'; var tracking_type = 'page'; var current_url = '" . JURI::base() . "advisorlead/pages/$page->id' ;";

            if (!empty($fadein_existed)) {
                foreach ($fadein_existed as $box) {
                    $page_js .= " $(function() { fadeInBox('" . $box['id'] . "'," . $box['time'] . "); });";
                }
            }

            if (!empty($page->form) && $page->form != '{}') {
                $page_js = str_replace('%IS_OPTIN%', 'true', $page_js);
                $page_js = str_replace('%SITE_URL%', ADVISORLEAD_URL, $page_js);
                $page_js = str_replace('%PAGE_ID%', $page->id, $page_js);
                $page_js = str_replace('%TEMPLATE_ID%', $page->template_id, $page_js);
            }
            else
                $page_js = str_replace('%IS_OPTIN%', 'false', $page_js);


            if (!empty($page->js_data) && $page->js_data != '{}') {
                $js_data_array = array();
                foreach (json_decode($page->js_data) as $data) {
                    if (strpos($data->value, 'youtu.be') !== false) {
                        $host = substr($data->value, strrpos($data->value, '://') + 3);
                        $url = explode('/', $host);
                        $data_value = 'https://www.youtube.com/embed/' . $url[1];
                    } else if (strpos($data->value, 'watch?v=') !== false) {
                        $url = explode('watch?v=', $data->value);
                        $data_value = 'https://www.youtube.com/embed/' . $url[1];
                    }
                    else
                        $data_value = $data->value;

                    $data_value = str_replace('http://www.mitspages.com', '', $data_value);
                    $js_data_array[$data->name] = $data_value;
                }

                if (isset($js_data_array['toDateTime'])) {

                    $timezone = $this->get_current_system_timezone();
                    date_default_timezone_set($timezone);

                    $current_time = new DateTime(date('Y-m-d H:i:s'));
                    $countdown_time = new DateTime($js_data_array['toDateTime']);
                    $diff_time = $current_time->diff($countdown_time);

                    if ($current_time < $countdown_time) {
                        $js_data_array['toDateDay'] = $diff_time->days;
                        $js_data_array['toDateHour'] = $diff_time->h;
                        $js_data_array['toDateMin'] = $diff_time->i;
                        $js_data_array['toDateSec'] = $diff_time->s;
                    } else {
                        $js_data_array['toDateDay'] = $js_data_array['toDateHour'] = $js_data_array['toDateMin'] = $js_data_array['toDateSec'] = 0;
                    }
                }

                $facebook_share_url = !empty($js_data_array['facebookshareurl']) ? urlencode($js_data_array['facebookshareurl']) : urlencode(ADVISORLEAD_URL . '/' . $_SERVER['REQUEST_URI']);
                $twitter_share_url = !empty($js_data_array['twitterurl']) ? urlencode($js_data_array['twitterurl']) : urlencode(ADVISORLEAD_URL . '/' . $_SERVER['REQUEST_URI']);
                $twitter_id = !empty($js_data_array['twitterid']) ? $js_data_array['twitterid'] : '';
                $twitter_text = !empty($js_data_array['twittertext']) ? $js_data_array['twittertext'] : '';
                $google_share_url = !empty($js_data_array['googleurl']) ? urlencode($js_data_array['googleurl']) : urlencode(ADVISORLEAD_URL . '/' . $_SERVER['REQUEST_URI']);

                $page_js .= "var itps_input_data = " . json_encode($js_data_array);
                $page_js = str_replace('%FACEBOOK_SHARE_URL%', "https://www.facebook.com/sharer/sharer.php?u=$facebook_share_url", $page_js);
                $page_js = str_replace('%TWITTER_SHARE_URL%', "https://twitter.com/share?url=$twitter_share_url&via=$twitter_id&text=$twitter_text", $page_js);
                $page_js = str_replace('%GOOGLE_SHARE_URL%', "https://plus.google.com/share?url=$google_share_url", $page_js);

                $video_slider = $document->getElementById('video_slider_box');
                if (!empty($video_slider)) {

                    $video_html = '<div class="flexslider"><ul class="slides">';
                    for ($i = 1; $i <= 6; $i++) {
                        if (!empty($js_data_array["vs-$i"]) && !empty($js_data_array["vsthumb-$i"])) {
                            $video_html .= '
                                <li>
                                    <a class="image_background fancybox fancybox.iframe" rel="gallery" href="' . $js_data_array["vs-$i"] . '" style="background-image: url(' . $js_data_array["vsthumb-$i"] . ')">
                                        <span class="playbtn"></span>
                                    </a>
                                </li>';
                        }
                    }
                    $video_html .= '</ul></div>';

                    $frag = $document->createDocumentFragment();
                    $frag->appendXML($video_html);
                    $video_slider->appendChild($frag);
                }
            }

            $pre_name = $document->getElementById("name");
            $pre_email = $document->getElementById("email");
            if (isset($pre_name) && isset($_SESSION['name'])) {
                $pre_name->setAttribute('value', $_SESSION['name']);
            }
            if (isset($pre_email) && isset($_SESSION['email'])) {
                $pre_email->setAttribute('value', $_SESSION['email']);
            }
            $html = $document->saveHTML();

            foreach ($videos as $key => $video)
                $html = str_replace('%VIDEO-' . $key . '%', $video, $html);
            
			$colors = json_decode($page->color_data);
			
			/*echo '<pre>';
			print_r($colors);
			print_r($fonts);
			exit;*/

            foreach ($colors as $color) {
                $html = str_replace(strtolower($color->color), $color->value, $html);
                $html = str_replace(strtoupper($color->color), $color->value, $html);
            }

            foreach ($fonts as $font) {
                if ($font->font != $font->value) {
                    $html = str_replace($font->font, $font->value, $html);
                }
            }

            $html = str_replace('%PAGE-SCRIPT%', '<script>' . $page_js . '</script>', $html);


            if ($is_fb) {
                $query = "SELECT settings_value FROM " . SETTINGS_TABLE . " WHERE settings_key LIKE 'facebook_app_id'";
                $app_id = $wpdb->get_var($query);
                $facebook = "<div id='fb-root'></div><script>window.fbAsyncInit = function() {FB.init({appId : '" . $app_id . "', status : true, cookie : true, xfbml : true}); };	(function() {var e = document.createElement('script');	e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';e.async = true;document.getElementById('fb-root').appendChild(e);}()); </script>";
                $html = str_replace('%FACEBOOK%', '<div>' . $facebook . '</div>', $html);
                $html = str_replace('http://', 'https://', $html);
            }

            return htmlspecialchars_decode($html);
        }
    }

    function delete_page($page_id) {
        global $user;
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $conditions = array(
            $db->quoteName('id') . ' = ' . $page_id,
            $db->quoteName('uid') . ' = ' . $user->id
        );
        
        // delete page domain
        
         $db = JFactory::getDbo();
         $sql = 'select custom_domain from #__advisorlead_pages where id = '.$page_id; 
         $db->setQuery($sql);
         $this->domains = $db->loadResult();
         if ($this->domains != ''){
            
            $app = JFactory::getApplication();
            $fn = str_replace('.', '', $this->domains);
            $new_path = JPATH_BASE.DS. '..'.DS.$fn;
         
                     if(is_dir($new_path)) {                               
                               // $full_url = JPATH_COMPONENT .DS."com_advisorlead".DS. "index.html";
                              //  $full_html = file_get_contents($full_url);  
                                $full_html  = '<html><body bgcolor="#FFFFFF"></body></html>';                       
                                $pdf_file_path = $new_path.DS.'index.html';
                                $pdf_filename = file_put_contents($pdf_file_path, $full_html);         
                                
                     }
         }
        
        $query->delete(PAGES_TABLE)->where($conditions);
        
        
        

        $db->setQuery($query);
        $db->execute();
    }

    function page_optin($page_id, $request) {

        $page = $this->get_page($page_id);
        $integrations = AdvisorleadHelper::get_integrations($page->uid);
        $email_services_options = AdvisorleadHelper::get_option('email_services');
        $email_services = json_decode($email_services_options);
        $is_fb = !empty($request['is_fb']) ? 1 : 0;

        $email = !empty($request['email']) ? $request['email'] : '';
        $name = !empty($request['name']) ? $request['name'] : '';
        $phone = !empty($request['phone']) ? $request['phone'] : '';

        $elements = json_decode($page->data);

        if (is_array($elements) || is_object($elements)) {
            foreach ($elements as $element) {

                $redirect_url = !empty($element->redirect_url) ? $element->redirect_url : '';

                if ($element->type == 'opt-in-form') {
                    switch ($element->optin_type) {
                        case 'aweber':
                            if (strlen($element->value) <= 26) {
                                $list_id = $element->value;
                            } else {
                                $content = rawurldecode($element->value);
                                $content = str_replace('html', 'htm', $content);

                                if (strpos($element->value, 'http://') === 0 || strpos($element->value, 'https://') === 0)
                                    $content = wp_remote_retrieve_body(wp_remote_get($content));

                                $document = new DOMDocument();
                                $document->loadHTML($content);
                                $xpath = new DOMXPath($document);

                                $form = $xpath->query('//form')->item(0);

                                if (empty($form)) {
                                    echo '<h2>Sorry, we don\'t support the this form type!</h2>';
                                }

                                $lists = $xpath->query('//input[@name="listname"]');
                                $list_name = $lists->item(0)->getAttribute('value');
                                $list_id = str_replace('awlist', '', $list_name);
                                $redirect = $xpath->query('//input[@name="redirect"]');
                                $redirect_url = $redirect->item(0)->getAttribute('value');
                            }

                            $aweber = new AWeberAPI($email_services->aweber_key, $email_services->aweber_secret);

                            try {
                                $account = $aweber->getAccount($integrations['aweber']['access_token'], $integrations['aweber']['access_token_secret']);
                                if (!is_numeric($list_id)) {
                                    $lists = $account->lists->find(array('name' => $list_id));
                                    $list_id = $lists[0]->data['id'];
                                }
                                $listURL = "$account->lists_collection_link/$list_id";
                                $list = $account->loadFromUrl($listURL);
                                $custom_fields = $list->custom_fields;
                                $do_not_create = false;


                                if (!empty($custom_fields->data['entries'])) {
                                    foreach ($custom_fields->data['entries'] as $field) {
                                        if ($field['name'] == 'Phone') {
                                            $do_not_create = true;
                                            break;
                                        }
                                    }
                                }

                                if (!$do_not_create) {
                                    try {
                                        $custom_fields->create(array('name' => 'Phone'));
                                    } catch (AWeberAPIException $exc) {

                                        if ($is_fb)
                                            echo '<script>top.location.href="' . $redirect_url . '";</script>';
                                        else
                                            echo '<script>window.location.href="' . $redirect_url . '";</script>';
                                        exit;
                                    }
                                }

                                $params = array(
                                    'email' => $email,
                                    'name' => $name,
                                    'custom_fields' => array(
                                        'Phone' => !empty($phone) ? $phone : '0'
                                    )
                                );

                                $list->subscribers->create($params);
                            } catch (AWeberAPIException $exc) {

                                if ($is_fb)
                                    echo '<script>top.location.href="' . $redirect_url . '";</script>';
                                else
                                    echo '<script>window.location.href="' . $redirect_url . '";</script>';
                                exit;
                            }

                            if ($is_fb)
                                echo '<script>top.location.href="' . $redirect_url . '";</script>';
                            else
                                echo '<script>window.location.href="' . $redirect_url . '";</script>';
                            exit;

                            break;
                        case 'mailchimp':

                            if ($integrations['mailchimp']['active'] && !empty($integrations['mailchimp']['api_key'])) {
                                $mailchimp = new MailChimp($integrations['mailchimp']['api_key']);
                                $method = 'lists/subscribe';
                                $email_ojb = new stdClass();
                                $email_ojb->email = $email;
                                $args = array(
                                    'id' => $element->value,
                                    'email' => $email_ojb,
                                    'send_welcome' => true,
                                    'double_optin' => $integrations['mailchimp']['double_optin'],
                                    'merge_vars' => array(
                                        'FNAME' => $name,
                                        'LNAME' => '.',
                                        'PHONE' => $phone
                                ));
                                $result = $mailchimp->call($method, $args);
                            }
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
                                $contact = NULL;
                                try {
                                    $fname = ($name != 'Enter Your First Name Here') ? $name : '';
                                    $contact = $oiContact->addContact($email, 'normal', null, $fname, '', '', '', '', '', '', '', $phone);
                                } catch (Exception $oException) { // Catch any exceptions
                                    $error = $oiContact->getErrors();
                                }
                                if ($contact) {
                                    try {
                                        $oiContact->subscribeContactToList($contact->contactId, $element->value, 'normal');
                                    } catch (Exception $oException) {
                                        $error = $oiContact->getErrors();
                                    }
                                }
                            }
                            break;
                        case 'getresponse':
                            if ($integrations['getresponse']['active']) {
                                if (!$integrations['getresponse']['copy_paste']) {
                                    $instance = new GetResponse($integrations['getresponse']['api_key']);
                                    $name = $name ? $name : 'AdvisorLead User';
                                    $custom = array();
                                    if (!empty($phone)) {
                                        $custom = array(
                                            'Phone' => $phone
                                        );
                                    }
                                    $instance->addContact($element->value, $name, $email, 'standard', '0', $custom);
                                }
                            }
                            break;
                        case 'constantcontact':
                            if ($integrations['constantcontact']['active']) {
                                $api_key = $email_services->cc_app_key;
                                $access_token = $integrations['constantcontact']['access_token'];
                                $cc = new ConstantContact($api_key);
                                try {
                                    // check to see if a contact with the email addess already exists in the account
                                    $response = $cc->getContactByEmail($access_token, $email);

                                    if (empty($response->results)) {
                                        $contact = new Contact();
                                        $contact->addEmail($email);
                                        $contact->addList($element->value);
                                        $contact->first_name = $name;
                                        $contact->last_name = '';
                                        $contact->home_phone = $phone;
                                        $contact->work_phone = $phone;
                                        $contact->cell_phone = $phone;
                                        $returnContact = $cc->addContact($access_token, $contact);

                                        // update the existing contact if address already existed
                                    } else {
                                        $contact = $response->results[0];
                                        $contact->addList($element->value);
                                        $contact->first_name = $name;
                                        $contact->last_name = '';
                                        $contact->home_phone = $phone;
                                        $contact->work_phone = $phone;
                                        $contact->cell_phone = $phone;
                                        $returnContact = $cc->updateContact($access_token, $contact);
                                    }
                                } catch (CtctException $ex) {
                                    //echo '<pre>'.print_r($ex->getErrors()).'</pre>';
                                    //exit(1);
                                }
                            }
                            break;
                        case 'infusionsoft':
                            if ($integrations['infusionsoft']['active']) {
                                $app_name = $integrations['infusionsoft']['app_name'];
                                $api_key = $integrations['infusionsoft']['api_key'];

                                $is = new iSDK();
                                $connected = $is->cfgCon($app_name, $api_key);
                                if ($connected) {
                                    $html = $is->getWebFormHtml($element->value);

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

                                    $fields = array(
                                        'infusionsoft_version' => $version,
                                        'inf_field_FirstName' => $name,
                                        'inf_field_Email' => $email,
                                        'inf_field_Phone1' => $phone
                                    );

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
                                    exit;
                                }
                            }
                            break;
                        case "gotowebinar":
                            $this->create_registrant_gotowebinar($page->uid, $email_services, $element->value, $request);
                            break;
                    }

                    if (!empty($redirect_url)) {
                        if ($is_fb)
                            echo '<script>top.location.href="' . $redirect_url . '";</script>';
                        else
                            echo '<script>window.location.href="' . $redirect_url . '";</script>';
                    }
                    break;
                }
            }
        }
        exit;
    }

    function hasChild($el) {
        if ($el->hasChildNodes()) {
            foreach ($el->childNodes as $c) {
                if ($c->nodeType == XML_ELEMENT_NODE)
                    return true;
            }
        }
        return false;
    }

    function payment_charge($page_id, $params) {

        $page = $this->get_page($page_id);

        if (!empty($page) && !empty($params['params'])) {
            $stripe = json_decode($params['params']);

            if (!empty($page->js_data) && $page->js_data != '{}') {
                $js_data = json_decode($page->js_data);
                $api_key = $js_data->stripe_secret_key->value;

                Stripe\Stripe::setApiKey($api_key);

                $token = $stripe->id;
                try {
                    Stripe\Charge::create(array(
                        "amount" => $params['amount'],
                        "currency" => "usd",
                        "source" => $token,
                        "description" => $params['description']
                    ));
                } catch (Stripe\Error\Card $e) {
                    var_dump($e);
                }
                exit;
            }
        }
    }

}

?>