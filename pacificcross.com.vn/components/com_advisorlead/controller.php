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
class AdvisorleadController extends JControllerLegacy {
            function getcontent_fbc(){
                
                $get = JRequest::get('get');
                $db = JFactory::getDbo();
                if($get['limitmax_fbc'] == 'all'){
                    $limit ='';
                }else{
                $limit = 'Limit 0, '.$get['limitmax_fbc'];}
                $sql = 'SELECT * FROM #__apifbc where published = 1 and access = 1  ORDER BY created DESC '.$limit;
                $db->setQuery($sql);
                $data =  $db->loadObjectList();
                ?>
                <div id="tabs-2" style="display: block;">
                    <table id="tabel-tab1" style="width: 95%;    text-align: center;">
                        <thead style="font-weight: bold;">
                                <th width="10%" >#</th>
                                <th width="10%">id</th>
                                 <th width="80%">Title</th>
                        </thead>
                        <tbody>
                            <?php 
                            $j=1;
                            foreach ($data as $r){ ?>
                                <tr <?php if($j%2==0){ echo ' style="    background-color: #f0f0f0;" '; } ?> >
                                    <td><input type="radio" name="tableradio" onclick="$('#numartical').val('<?php  echo $r->article_id; ?>');" /></td>
                                     <td><?php echo $r->article_id; ?></td>
                                     <td style="text-align: left;"> 
                                         <div id="art-tit<?php echo $r->article_id; ?>" ><?php echo $r->article_title; ?></div>
                                         <div style="display: none;" id="art-image<?php echo $r->article_id; ?>" ><?php echo $r->slideshowimage; ?></div>
                                         <div style="display: none;" id="art-description<?php echo $r->article_id; ?>" ><?php echo $r->description; ?></div>
                                         <div style="display: none;" id="art-link<?php echo $r->article_id; ?>" ><?php echo JURI::base() ?>index.php?option=com_apicontent&view=fbclist&id=<?php echo $r->article_id;  ?></div>
                                     
                                     </td>
                                </tr>
                            <?php $j++;} ?>
                        </tbody>
                        
                    </table>
                </div>
                <?php
                die;
            }
            
             function getcontent_fnc(){
                
                $get = JRequest::get('get');
                $db = JFactory::getDbo();
                if($get['limitmax_fnc'] == 'all'){
                    $limit ='';
                }else{
                $limit = 'Limit 0, '.$get['limitmax_fnc'];}
                $sql = 'SELECT * FROM #__apifnc where published = 1 and access = 1  ORDER BY created DESC '.$limit;
                $db->setQuery($sql);
                $data1 =  $db->loadObjectList();
                ?>
                <div id="tabs-4" style="display: block;">
                   <table style="width: 95%;text-align: center;">
                        <thead style="font-weight: bold;" >
                                <th width="10%" >#</th>
                                <th width="10%">id</th>
                                 <th width="80%">Title</th>
                        </thead>
                        <tbody>
                            <?php foreach ($data1 as $r1){ ?>
                                <tr <?php if($j%2==0){ echo ' style="    background-color: #f0f0f0;" '; } ?> >
                                    <td><input type="radio" name="tableradio" onclick="$('#numartical').val('<?php  echo $r1->article_id; ?>" /></td>
                                     <td><?php echo $r1->article_id; ?></td>
                                    <td style="text-align: left;" >
                                         <div id="art-tit<?php echo $r1->article_id; ?>" ><?php echo $r1->article_title; ?></div>
                                         <div style="display: none;" id="art-image<?php echo $r1->article_id; ?>" ><?php echo $r1->slideshowimage; ?></div>
                                         <div style="display: none;" id="art-description<?php echo $r1->article_id; ?>" ><?php echo $r1->description; ?></div>
                                         <div style="display: none;" id="art-link<?php echo $r1->article_id; ?>" ><?php echo JURI::base() ?>index.php?option=com_apicontent&view=fnclist&id=<?php echo $r->article_id;  ?></div>
                                     
                                     </td>
                                </tr>
                            <?php $j++; } ?>
                        </tbody>
                        
                    </table>
                </div>
                <?php
                die;
            }
            function getcontent(){
          // select  arti
          $db = JFactory::getDbo();
          $session =& JFactory::getSession();
         
          $get = JRequest::get('get');
          
          
            $limit = 'Limit 0, 30';       
         
          
          $sql = 'SELECT * FROM #__apifbc where published = 1 and access = 1  ORDER BY created DESC '.$limit;
          $db->setQuery($sql);
          $data =  $db->loadObjectList();
          
          
          $sql = 'SELECT * FROM #__apifnc  where published = 1 and access = 1 ORDER BY created DESC '.$limit;
          $db->setQuery($sql);
          $data1 =  $db->loadObjectList();
          //print_r($data1);die;
             ?>
            
<div style="display: none;    position: fixed;    z-index: 1000000;    background: white;    top: 100px;    left: 300px;     width: 1000px;    height: 600px;    overflow: auto;    padding: 10px 65px 65px 65px;    border: solid #ccc 5px;    border-radius: 16px;" id="nsjdnasjkndkjas" >
    <div onclick="$('#nsjdnasjkndkjas').hide();$('#redactor_modal_overlay').hide();" style="    color: red;    position: absolute;    right: 10px;    font-size: 31px;    font-weight: bold;    top: 12px;    cursor: pointer;">X</div>
    <button class="1_save_content_block" onclick="$('#nsjdnasjkndkjas').hide();$('#redactor_modal_overlay').hide(); " style="        background: #008e08;    border-radius: 8px;    padding: 10px 40px;    text-shadow: 0 2px 0 #005703;    font-size: 20px;    font-weight: bold;    text-transform: uppercase;      background: rgb(0, 111, 3);    background: -moz-linear-gradient(270deg, rgb(0, 111, 3) 0%, rgb(0, 162, 11) 100%);    background: -webkit-linear-gradient(270deg, rgb(0, 111, 3) 0%, rgb(0, 162, 11) 100%);    background: -o-linear-gradient(270deg, rgb(0, 111, 3) 0%, rgb(0, 162, 11) 100%);    background: -ms-linear-gradient(270deg, rgb(0, 111, 3) 0%, rgb(0, 162, 11) 100%);    background: linear-gradient(0deg, rgb(0, 111, 3) 0%, rgb(0, 162, 11) 100%); color: white;    position: absolute;    right: 67px;    font-size: 31px;    font-weight: bold;    top: 40px;    cursor: pointer;">Save</button>
   <h3>Select Block </h3>
   <input type="hidden" id="numblockse" value="" />
   <input type="hidden" id="numartical" value="" />
  <?php for($i = 1;$i<=$get['num'];$i++){ ?>
   <input style="    margin: 0px 0px 0px 13px;" type="radio" name="blockse" onclick="$('#numblockse').val('<?php echo $i ?>');" /> <span> <?php echo $i ?> </span> 
  <?php } ?>
   <br><br>
    <div id="tabs" >
        <ul style="  margin: 0;  height: 45px;    max-height: 45px;    width: 95%;    position: relative;      border-bottom: 1px solid #000; ">
	
            <li  style="float: left;  position: relative;    list-style: none;    ">
                <a id="dsadsadjs0" style="  height: 45px;    display: block;    text-align: center;    font-size: 13px;    line-height: 45px;    color: #333;    font-weight: bold;    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.22);    text-decoration: none;    padding: 0 25px;    overflow: hidden;    position: relative;   " href="#" class="alltab current" onclick="$('#limitmax_fnc').hide();$('#limitmax_fbc').show();$('#dsadsadjs1').removeClass('current');$('#dsadsadjs0').addClass('current');$('#tabs-4').hide();$('#tabs-2').fadeIn('1000');">FBC</a>
            </li>
            <li style="float: left;   position: relative;  list-style: none;       ">
                <a  style=" height: 45px;    display: block;    text-align: center;    font-size: 13px;    line-height: 45px;    color: #333;  font-weight: bold;  text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.22);    text-decoration: none;    padding: 0 25px;    overflow: hidden;    position: relative; " href="#" id="dsadsadjs1" class="alltab" onclick="$('#limitmax_fbc').hide();$('#limitmax_fnc').show();$('#dsadsadjs0').removeClass('current');$('#dsadsadjs1').addClass('current');$('#tabs-2').hide();$('#tabs-4').fadeIn('1000');">FNC</a>
            </li>
            <li style="float: right;   position: relative;  list-style: none;       ">
              
                <select id="limitmax_fbc" name="limitmax_fbc" style="    width: 69px;    margin: 0;">
                       <option value="30" >30</option>
                       <option value="50" >50</option>
                       <option value="100" >100</option>
                       <option value="200" >200</option>
                       <option value="all" >All</option>
                </select>
                <select id="limitmax_fnc" name="limitmax_fnc" style="   display:none ;width: 69px;    margin: 0;">
                       <option value="30" >30</option>
                       <option value="50" >50</option>
                       <option value="100" >100</option>
                       <option value="200" >200</option>
                       <option value="all" >All</option>
                </select>
            </li>
            </ul>
            <div id="tabs-2" style="display: block;">
                    <table id="tabel-tab1" style="width: 95%;    text-align: center;">
                        <thead style="font-weight: bold;">
                                <th width="10%" >#</th>
                                <th width="10%">id</th>
                                 <th width="80%">Title</th>
                        </thead>
                        <tbody>
                            <?php 
                            $j=1;
                            foreach ($data as $r){ ?>
                                <tr <?php if($j%2==0){ echo ' style="    background-color: #f0f0f0;" '; } ?> >
                                    <td><input type="radio" name="tableradio" onclick="$('#numartical').val('<?php  echo $r->article_id; ?>');" /></td>
                                     <td><?php echo $r->article_id; ?></td>
                                     <td style="text-align: left;"> 
                                         <div id="art-tit<?php echo $r->article_id; ?>" ><?php echo $r->article_title; ?></div>
                                         <div style="display: none;" id="art-image<?php echo $r->article_id; ?>" ><?php echo $r->slideshowimage; ?></div>
                                         <div style="display: none;" id="art-description<?php echo $r->article_id; ?>" ><?php echo $r->description; ?></div>
                                         <div style="display: none;" id="art-link<?php echo $r->article_id; ?>" ><?php echo JURI::base() ?>index.php?option=com_apicontent&view=fbclist&id=<?php echo $r->article_id;  ?></div>
                                     
                                     </td>
                                </tr>
                            <?php $j++;} ?>
                        </tbody>
                        
                    </table>
                </div>
                <div id="tabs-4" style="display: none;" >
                    <table style="width: 95%;text-align: center;">
                        <thead style="font-weight: bold;" >
                                <th width="10%" >#</th>
                                <th width="10%">id</th>
                                 <th width="80%">Title</th>
                        </thead>
                        <tbody>
                            <?php foreach ($data1 as $r1){ ?>
                                <tr <?php if($j%2==0){ echo ' style="    background-color: #f0f0f0;" '; } ?> >
                                    <td><input type="radio" name="tableradio" onclick="$('#numartical').val('<?php  echo $r1->article_id; ?>" /></td>
                                     <td><?php echo $r1->article_id; ?></td>
                                    <td style="text-align: left;" >
                                         <div id="art-tit<?php echo $r1->article_id; ?>" ><?php echo $r1->article_title; ?></div>
                                         <div style="display: none;" id="art-image<?php echo $r1->article_id; ?>" ><?php echo $r1->slideshowimage; ?></div>
                                         <div style="display: none;" id="art-description<?php echo $r1->article_id; ?>" ><?php echo $r1->description; ?></div>
                                         <div style="display: none;" id="art-link<?php echo $r1->article_id; ?>" ><?php echo JURI::base() ?>index.php?option=com_apicontent&view=fnclist&id=<?php echo $r->article_id;  ?></div>
                                     
                                     </td>
                                </tr>
                            <?php $j++; } ?>
                        </tbody>
                        
                    </table>
                
                </div>
		
	
    </div>
</div>
             <?php
            die;
        }

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