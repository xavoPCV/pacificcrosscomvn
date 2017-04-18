<?php
defined('_JEXEC') or die;



use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

// Get api details
//
$APIKEY = CONSTANT_APIKEY;
$ACCESS_TOKEN =  $this->allsetting[0]->api_key;
$custome_url  =  $this->allsetting[0]->custom_link_article;
// Get all lists of constant contact
$error = '';
$cc = new ConstantContact($APIKEY);
try{  
	$groups = $cc->getLists($ACCESS_TOKEN);
}catch (CtctException $ex) {
	$ccerror = $ex->getErrors();
	$error .= $ccerror[0]['error_message'];
        $app = JFactory::getApplication();
	$app->enqueueMessage(JText::_($ccerror[0]['error_message'])." - ( from Constant Contact)",'error');
	$app->redirect('index.php?option=com_enewsletter');
       // echo 'wrong';
	die();
}


?> 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="administrator/components/com_enewsletter/css/jquery-ui.min.css">
<link rel="stylesheet" href="administrator/components/com_enewsletter/css/demo_table.css">

        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
         <script src="administrator/components/com_enewsletter/js/jquery.dataTables.js"></script>
 <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/style2.css" />
    
        <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  
	$(document).ready(function() {
            
	
    api = $('#apikey').val();
    apitype = $('#newsletter_api').val(); 
  
    if(api){
        $.ajax({
               type : 'POST',
        			url: 'index.php?option=com_enewsletter&task=getverifiedemaillist',
              data :   'apitype='+apitype+'&apikey='+api,
        			success: function( data ){
                    $('#verified_email').html(data);              
              }
        		}); 
      }

	} );
	

  </script>
  <script>
      function checkform(){
          var error = 0;
            if(!$('input[name="changetemps"]:checked', '#adform').is(':checked')) { 
              
                 alert('You must Choice Archive Draft frist ! ');  
                 return false;
                
            }
            if($('#jform_subject').val().trim() == '') {
                
                alert('Subject Line is not blank ');  
                 return false;
                
            }
            if ($('.checkall_group:checked').length > 0 ) {
                
            }else {
                alert('Choice List send out ');  
                 return false;
            }
//            if($('#jform_campaigntitle').val().trim() == '') {
//                
//                alert('Title is not blank ');  
//                 return false;
//                
//            }
       
             $('#adform').submit();
      }
  </script>
      
   <link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/newsletter_1.css">

        <div class="allpage" >
            <div class="col-1" > 
                    <div class="list_menu_button">
            <div class="div_btn" style="width:100%; float: left;">
                <div class="logo_row" style="">
                    <a class="lefteditlogo" href="<?php echo juri::base() ?>"><img src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/small_logo.png" style="width: 159px; height: 48px"></a>        
                    <div class="top_button_save_and_logout">
                        <div onclick="subhtml();" class="logo-save btncontactus" id="btn_inactive_edittemplate_and_deletetemplate">Save</div>
                                            </div>                   
                    <div style="display: none;">
                        <input type="button" value=" Delete Other Template " id="btn_delete_other_tempalte_cus">
                        <input type="button" id="btn_contactus_template_edit" class="btncontactus left_menu_edit_btn row2" value="Contact">
                        <input type="button" id="btn_training_videos_template_edit" class="btncontactus row2" value="Tutorials">
                    </div>
                </div>
            </div>
            <div class="div_btn" style="width:100%; float: left;">
                                    <div class="pane-left">
                        <div class="pane-img">
                            <a class="btntemplate btncontactus row2" href="<?php echo juri::base() ?>index.php/setup-your-site">
                                <img class="img-btn" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/template.png">
                                <img class="img-btn-hover" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/template-hover.png">
                            </a>
                        </div>
                        <div class="text">Template</div>
                    </div>
                                    <div class="pane-left btncontactus" id="btn_videos_template_edit">
                                          <a href="<?php echo juri::base() ?>"> 
                    <div class="pane-img">
                        <img class="video img-btn" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/video.png">
                        <img class="video img-btn-hover" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/video-hover.png">
                    </div>
                                          </a>
                    <div class="text text-video">Video</div>
                </div>
                <div class="pane-left btncontactus btntemplate">
                    <div class="pane-img ">
                        <a href="<?php echo juri::base() ?>"> <img class="img-btn" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/content.png"></a>
                        <a href="<?php echo juri::base() ?>"><img class="img-btn-hover " src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/content-hover.png"></a>
                    </div>
                    <div class="text text-content">Content</div>
                </div>
                <div class="pane-left" id="btn_customize_template_edit">
                      <a href="<?php echo juri::base() ?>"> 
                    <div class="pane-img">
                        <img class="img-btn" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/customize.png">
                        <img class="img-btn-hover" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/customize-hover.png">
                    </div>
                    <div class="text text-customize">Customize</div>
                      </a>
                </div>
                <div class="pane-left btncontactus" id="btn_banner_template_edit">
                      <a href="<?php echo juri::base() ?>"> 
                    <div class="pane-img">
                        <img class="img-btn " src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/banner.png">
                        <img class="img-btn-hover " src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/banner-hover.png">
                    </div>
                    <div class="text">Banner</div>
                      </a>
                </div>

            </div>

            <div class="div_btn" style="width:100%; float: left;">
                <div class="pane-left btncontactus" id="btn_module_template_edit">
                       <a href="<?php echo juri::base() ?>"> 
                    <div class="pane-img ">
                        <img class="img-btn  " src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/social.png">
                        <img class="img-btn-hover" src="<?php echo juri::base() ?>modules/mod_leftmenuedit/cus-edit/images/social-hover.png">
                    </div>
                    <div class="text">Modules</div>
                       </a>
                </div>
                <div class="pane-left  pane-left-icon left_menu_edit_btn" id="btn_eventcalendar_template_edit">
                     <a href="<?php echo juri::base() ?>"> 
                    <div class="pane-img  row2">
                        <i class="fa fa-calendar icon_fa"></i>
                    </div>
                    <div class="text">Events</div>
                     </a>
                </div>
                <div class="pane-left  pane-left-icon left_menu_edit_btn" id="btn_acepolls">
                     <a  href="<?php echo juri::base() ?>"> 
                    <div class="pane-img row2">
                        <i class="fa fa-bar-chart icon_fa"></i>
                    </div>
                    <div class="text">Acepolls</div>
                    </a>
                </div>
                <div class="pane-left  pane-left-icon  active">
                    <div class="pane-img ">
                        <a href="<?php echo juri::base() ?>edit-enewsletter" > 
                            <i class="fa fa-inbox icon_fa"></i>

                    </a></div><a href="<?php echo juri::base() ?>edit-enewsletter" >
                    <div class="text">Newsletters</div>
                    </a>
                </div>
                
                    <div class="pane-left  pane-left-icon easy-blog-icon">
                        <div class="pane-img ">
                            <a href="<?php echo juri::base() ?>easyblog"> 
                                <i class="fa fa-list icon_fa"></i>
                        </a></div><a href="<?php echo juri::base() ?>easyblog">
                        <div class="text">Easy Blog</div>
                        </a>
                    </div>

                    <div style="display: none;"><input type="button" id="btn_view_easyblog_template_edit" class="left_menu_edit_btn btncontactus row2" value="Easy Blog"></div>
                    
            </div>
            <div class="div_btn" style="width:100%; float: left;">
                <div class="pane-left  pane-left-icon">
                    <div class="pane-img ">
                        <a href="<?php echo juri::base() ?>advisorlead" > 
                            <i class="fa fa-cogs icon_fa"></i>

                    </a></div><a href="<?php echo juri::base() ?>advisorlead" >
                    <div class="text">AdvisorLead</div></a>
                </div>

            </div>

            <div class="div_btn1 list_fa_icon_hover" style="width: 100%; float: left; padding: 0px; margin: 0px; display: none;">
                <div class="pane-color">
                    <div id="btn_favicon_template_edit" class="left_menu_edit_btn row2">
                        <i class="fa fa-desktop"></i>
                        <span>Favicon</span>
                    </div>
                </div>
                <div class="pane-color">
                    <div id="btn_menucolor_template_edit" class="left_menu_edit_btn row2">
                        <i class="fa fa-paint-brush"></i>
                        <span>Colors</span>
                    </div>
                </div>
                <div class="pane-color">
                    <div id="btn_socialmedia_template_edit" class="left_menu_edit_btn row2">
                        <i class="fa fa-external-link"></i>
                        <span>Social</span>
                    </div>
                </div>
            </div>
        </div>
                      <div class="main-1 left_menu_wrap">
                   
                          
                          
                          <div style="background-color: #e4e4e4 !important; padding-top: 20px;padding-bottom: 20px;padding-left: 30px;"><div class="sqs-navigation-item"><span class="icon icon-projects"></span></div>Enewsletter</div>
                  
                   
                        
                    <br>
                         <button onclick="window.location.href='index.php?option=com_enewsletter&view=editletter'   " id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 20px 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;width: 82%; " >Go Back</button>
                         <button onclick="checkform();"  id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 0 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;     background: url('images/sendmail.png');    font-size: 0;    width: 261px;    height: 65px;    background-size: 100%;    background-repeat: no-repeat;    margin-left: 31px;" >Send</button>
                        
                    
                          
                    
                    
                    
                    
                </div>
        </div>
              
                 <div class="col-2" > 
                 <form id="adform" method="post" action="" >
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" name="task" value="send" >   
                         
                        
                         <input type="hidden" name="view" value="sendmail" >
                         <input type="hidden" id="apikey" name="apikey" value="<?php echo $this->allsetting[0]->api_key  ?>">
                         <input type="hidden" id="newsletter_api" name="newsletter_api" value="<?php echo $this->allsetting[0]->newsletter_api  ?>">
                         
                         <?php echo JHtml::_('form.token'); ?>
              <div id="tabs">
	<ul>
		<li><a href="#tabs-1" class="alltab">Personalize</a></li>
	
		<li><a href="#tabs-3" class="alltab">Lists</a></li>
	</ul>
	
	
	<div id="tabs-1">
	<div class="width-100">
			<fieldset class="adminform">
				<legend><?php echo JText::_('Personalize'); ?></legend>
				<div>
									
					<ul class="adminformlist">
          
            <li>	
							<label title=""><b>From Email Address </b><span class="star"> *</span></label>	
                                                        <br> <br>
							<div id="verified_email">
              </div>
						</li> 
            
            <li id="emailtemplatediv" >
            <?php  
              if(count($emailtemplates) > 1){   
                 
                  ?>
                  <label style="float:left;" >Email Template</label>
                  <div id="emailtemplatelistsdiv" >            
                      <select id="templateselect" class="ntemplate"  name="jform[ndefaultemail]" >
                      <option value="" >Select Template</option>
                      <?php   foreach($emailtemplates as $et=>$desc){ 
                       
                        if($et == $default_template){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                      ?>
                          <option value="<?php echo $et;?>" <?php echo $selected;?> > <?php echo $desc;?> </option>
                      <?php  } ?>
                      </select>              
              </div>
              <br /><br />
              <?php  }else{?>
                  <input type="hidden" name="jform[ndefaultemail]" class="ntemplate" value="<?php echo $default_template;?>" >
              <?php }?>
            </li>
        
            
            <li style="clear:both;">
                <br><label title="" >Subject Line <span class="star"> *</span></label>
							<div>
							<input type="text" size="100" maxlength="200" class="inputbox" value="<?php echo htmlentities(@$data['subject']);?>" id="jform_subject" name="jform[subject]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 200 characters.</span> 
							</div>						
							
						</li>
            
					<!--	<li style="clear:both;">
                                                    <br>
                                                    <label title="" >Title <span class="star"> *</span></label><br>					
							<input type="text" size="100" maxlength="60" class="inputbox" value="<?php echo @$data['title'];?>" id="jform_campaigntitle" name="jform[title]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 60 characters. The title text will appear in the body of the E-Newsletter.</span>
						</li>	-->					
						 <li style="  ">
                                                     <br>
                                                     <span><b>Archive Draft *</b></span><br>
                                                     
                                                    
                                                          
                                                       <?php
                                                           foreach ($this->tems as $r){ 
                                                               $k++;
                                                           ?>
                                                           <br> <input type="radio" name="changetemps"  id="changetemps<?php echo $k ?>" value="<?php echo $r ?>" /> <?php echo str_replace("_".JFactory::getUser()->id."_", " : ", $r); ?>  

                                                           <?php 
                                                       }
                                                       ?> 
                                                
                                                    </li>
                                                    
                                                    
                                                    
						<li>
                                                    <br><label title="" ><b>Lists *</b></label><br>
            	<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="grouptable">
				<thead>
					<tr>
						<th width="1%">
							<!--<input type="checkbox" name="checkall_group" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />-->
						</th>
						<th class="left">
							
						</th>

			
					</tr>
				</thead>
				<tbody>
				<?php foreach ($groups as $i => $group) :?>
					<?php 
              if($group->contact_count != 0 )  {
          ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php 
							if(in_array($group->id,$gid)){
								$gchecked = 'checked="checked"';
							}else{
								$gchecked = '';
							}?>
								<input id="cb2" class="checkall_group" type="checkbox" <?php echo $gchecked;?> onclick="Joomla.isChecked(this.checked);" value="<?php echo $group->id;?>" name="gid[]" />
						</td>
					
					
						<td class="left" >
						<?php echo $this->escape($group->name); ?>
						</td>			
						
					</tr>
					<?php }?>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
		</div>
						</li>

										
					</ul>
					
					
                                        <div style="display: none;" >
					<label>Trailer</label>
					<div class="clr"></div>
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[trailer]',@$data['trailer'], '100%', '250', '40', '10', false);
					?>
				
                                        </div>					
				</div>				
				
			</fieldset>
			
			
		</div>
	</div>

	
	
</div><!--tabs-->
	
</form>
</div>
            </div>
             
           
<style>
            body{
                    margin-top: 40px;
            }
              .allpage {
               width: 100% ;
              }
              .col-1{
                      width: 380px;
                     margin-left: 10px;
              }
              .block-2{
                      width: 28%;
              }
              #adform{
                     
                          padding-left: 65px;
              }
              .col-2{
                  width: 76%;
              }
              input[type=text] {
                      width: 96%;
                      font-size: 1em;
                      margin-left: -15px;
                      margin-bottom: 20px;
              }
              .button-blu{
                    
                          background: url('images/save.png');
                        background-repeat: no-repeat;
                        width: 165px;
                        background-size: 165px;
                         color: rgba(0, 0, 0, 0.1);
                                font-size: 1px;
                                height: 50px;
              }
        #weekly:hover,   #invest:hover, #address:hover, #logomail:hover, .module:hover{
                  cursor: pointer;
                  border: red 3px solid!important;
                  border-radius: 5px;
              }
              #cloud-tag{
                      padding: 0px !important;
              }
              .header1{
                  margin-bottom: 0px;
              }
              .text1{
                  color: #777;
              }
              .edittem:hover{
                  border: solid 2px red;
              }
        </style>
     
              
                 
            </div>
  
     <?php die; ?>