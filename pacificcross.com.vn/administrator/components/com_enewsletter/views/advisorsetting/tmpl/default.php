<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include constant contact / mailchimp library files
require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/auth/MC_OAuth2Client.php');



use Ctct\Auth\CtctOAuth2;
use Ctct\Exceptions\OAuth2Exception;

// Get api type (C=constant contact/ M=mailchimp)
$app = JFactory::getApplication();
$APIKEY  = CONSTANT_APIKEY;

$advisordetails = $app->getUserState("com_enewsletter.advisormssqldetails");

/*var_dump($APIKEY);
var_dump(CONSUMER_SECRET);
var_dump(REDIRECT_URI);
*/

// instantiate the CtctOAuth2 and MC_OAuth2Client class
$oauth = new CtctOAuth2($APIKEY, CONSUMER_SECRET, REDIRECT_URI);
$client = new MC_OAuth2Client();

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');

$document = JFactory::getDocument();						
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-1.9.1.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-ui.js");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/jquery-ui.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/style.css");
?>

<style>
fieldset.adminform label{ min-width: 210px;}
.hinthelp{ float:right;margin:0px;width:15px;}
.hinthelp:hover {cursor : pointer;}
.setupbtn {background:#e6e6e6;}
.setupbtn:hover{cursor : pointer;}
</style>


<script type="text/javascript">

// Clear all API details when we press clear all button and remove API details from database
function clearall(){
    var confirmBox = confirm("It will clear the current login & API information, and you'll have to supply new information. ARE YOU SURE?");
  	if(!confirmBox){
  		return false;
  	}
        
    $('.newsletter_api').prop('disabled',false);
    $('#api_login_name').prop('readonly',false);
    $('#apikey').prop('readonly',false);
    $('#api_login_name').val('');
    $('#apikey').val('');
    $('#from_email').val('');
    $('#from_name').val('');
    $('#get_access_token_li').show();
    $('#get_access_token_li_div').show();
    $('#clearall_div').hide();
    $('#get_from_email_div').hide();
    
    $.ajax({
    			url: '<?php echo JURI::base();?>index.php?option=com_enewsletter&task=advisorsetting.clearapidetails',
    		}); 
              
}

function fill_email(a){
  var val = a.value;
  $('#from_email').val(val);
}
// Set tabing
$(function() {
$( "#tabs" ).tabs();
});
	
Joomla.submitbutton = function(task) {

		if (task == 'advisorsetting.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
    
    	if(task == 'advisorsetting.apply'){
          loginname = $('#api_login_name').val();
          api = $('#apikey').val();
          weeklysubject = $('#weekly_subject').val();
          fromname = $('#from_name').val();
          fromemail = $('#verified_emails').val();
          address1 = $('#address1').val();
          city =  $('#city').val();
          state = $('#state').val();
          zip = $('#zip').val();
          email_name = $('#verified_email_name').val();
          apitype = $('.newsletter_api:checked').val();
          
          if(loginname == ''){
            alert('Please provide login name.');
            $("#tabs").tabs({active: 0});
            $("#api_login_name").focus();
            return false;
          }
          
          if(api == ''){
            alert('Please provide API key.'); 
            $("#tabs").tabs({active: 0});
            $("#apikey").focus();
            return false;
          }
          
          if(apitype == 'M'){
                if(email_name == ''){     
                  alert('Please provide default email address.');   
                  $("#tabs").tabs({active: 0});
                  return false;
                }
                if(fromemail == ''){
                  alert('Please provide domain name for default email address.'); 
                  $("#tabs").tabs({active: 0});
                  return false;
                }
            }
/*else{
             //!!!! Constant Contact verifeid address is  not needed this at this point - we will alert them when they try to send a newsletter   
				if(fromemail == ''){
                  alert('Please provide default email address.'); 
                  $("#tabs").tabs({active: 0});
                  return false;
                }
            }
*/        
            
          
          
          
          
          if(fromname == ''){
            alert('Please provide from name.'); 
            $("#tabs").tabs({active: 0});
            $("#from_name").focus();
            return false;
          }
		  
		  if( $.trim($('#jform_firm').val()) == ''){
            alert('Please provide Firm.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_firm").focus();
            return false;
          }
		  if( $.trim($('#jform_url').val()) == ''){
            alert('Please provide URL.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_url").focus();
            return false;
          }
		  if( $.trim($('#jform_path_quote').val()) == ''){
            alert('Please provide Path to quote.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_path_quote").focus();
            return false;
          }
		  if( $.trim($('#jform_custom_link_article').val()) == ''){
            alert('Please provide Custom Link For Articles.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_custom_link_article").focus();
            return false;
          }
          
          if(address1 == ''){
            alert('Please provide address.'); 
            $("#tabs").tabs({active: 0});
            $("#address1").focus();
            return false;
          }
          
          if(city == ''){
            alert('Please provide city.'); 
            $("#tabs").tabs({active: 0});
            $("#city").focus();
            return false;
          }
          
          if(zip == ''){
            alert('Please provide zip.'); 
            $("#tabs").tabs({active: 0});
            $("#zip").focus();
            return false;
          }
          
          if(state == ''){
            alert('Please provide state.'); 
            $("#tabs").tabs({active: 0});
            $("#state").focus();
            return false;
          }    
          
          if(weeklysubject == '') {
          
            if($("#auto_update").prop('checked') == true){ 
                alert('Subject line required for automatic Weekly Update.');
             }
             $("#tabs").tabs({active: 1});
             return false;
          }
          
          if($("#auto_update").prop('checked') == true){   
            if($( "#listsdiv input:checkbox:checked" ).length == 0){
    					alert('Select at least one list to recieve automatic Weekly Update.');
              $("#tabs").tabs({active: 1});
    					return false;
    				}
          }
      }

			Joomla.submitform(task, document.getElementById('user-form'));
		}
}

$(document).ready(function(){

$('#apikey').blur(function(){
      api = $('#apikey').val();
      apitype = $('.newsletter_api:checked').val(); 
      $i = 0;

      if(api){
          $.ajax({
                 type : 'POST',
          			url: '<?php echo JURI::base();?>index.php?option=com_enewsletter&task=getverifiedemaillist',
                	data :   'apitype='+apitype+'&apikey='+api,
          			success: function( data ){
                  	if(data == 'error'){
                      alert('Please provide correct details.');
                      $("#tabs").tabs({active: 0});
                      $("#apikey").val('');
                      return false;
                    }else{
                      $('#get_from_email_div').show();
                      $('#verified_email').html(data);
                      $i = 1;
                      $.ajax({
                          type : 'POST',
                    	  url: '<?php echo JURI::base();?>index.php?option=com_enewsletter&task=getemaillists',
                          data :   'apitype='+apitype+'&apikey='+api,
                    	  success: function( data ){
                              if(data == 'error'){
                                alert('Please provide correct details.');
                                $("#tabs").tabs({active: 0});
                                return false;
                              }else{
                                $('#listsdiv').html(data);
                              }
                             
                          }
                    		}); 
                    }                  
                }
          		}); 
              
             /* if($i != 0){
                
              }   */
              
      } 
});

 
 <?php if($this->details->api_key){  ?> 
    $('#get_access_token_li_div').hide();
    $('#get_from_email_div').show();
    $('#clearall_div').show();
       
    api = $('#apikey').val();
      apitype = $('.newsletter_api:checked').val(); 

      if(api){
          $.ajax({
                 type : 'POST',
          			url: '<?php echo JURI::base();?>index.php?option=com_enewsletter&task=getverifiedemaillist',
                data :   'apitype='+apitype+'&apikey='+api,
          			success: function( data ){
                  if(data == 'error'){
                      alert('Please provide correct details.');
                      $("#tabs").tabs({active: 0});
                      return false;
                    }else{
                      $('#get_from_email_div').show();
                      $('#verified_email').html(data);
                    }                  
                }
          		}); 
              
              $.ajax({
                 type : 'POST',
          			url: '<?php echo JURI::base();?>index.php?option=com_enewsletter&task=getemaillists',
                data :   'apitype='+apitype+'&apikey='+api,
          			success: function( data ){
                    if(data == 'error'){
                      alert('Please provide correct details.');
                      $("#tabs").tabs({active: 0});
                      return false;
                    }else{
                      $('#listsdiv').html(data);
                    }
                   
                }
          		}); 
      }
       
 <?php }else{?>
    $('#clearall_div').hide();
    $('#get_from_email_div').hide();
    $('#get_access_token_li_div').show();
 <?php } ?>

  $('.alltab').click(function(){
  
          loginname = $('#api_login_name').val();
          api = $('#apikey').val();
          weeklysubject = $('#weekly_subject').val();
          fromname = $('#from_name').val();
          fromemail = $('#verified_emails').val();
          address1 = $('#address1').val();
          city =  $('#city').val();
          state = $('#state').val();
          zip = $('#zip').val();
          email_name = $('#verified_email_name').val();
          apitype = $('.newsletter_api:checked').val();

          if(loginname == ''){
            alert('Please provide login name.');
            $("#tabs").tabs({active: 0});
            $("#api_login_name").focus();
            return false;
          }
          
          if(api == ''){
            alert('Please provide API key.'); 
            $("#tabs").tabs({active: 0});
            $("#apikey").focus();
            return false;
          }
          
          if(apitype == 'M'){
                if(email_name == ''){     
                  alert('Please provide default email address.');   
                  $("#tabs").tabs({active: 0});
                  return false;
                }
                if(fromemail == ''){
                  alert('Please provide domain name for default email address.'); 
                  $("#tabs").tabs({active: 0});
                  return false;
                }
            }
/* else{
				// !!!!! No longer require verfied email address at this point 
                if(fromemail == ''){
                  alert('Please provide default email address.'); 
                  $("#tabs").tabs({active: 0});
                  return false;
                }
            }
*/          
          
          if(fromname == ''){
            alert('Please provide from name.'); 
            $("#tabs").tabs({active: 0});
            $("#from_name").focus();
            return false;
          }
          
          
		  
		  if( $.trim($('#jform_firm').val()) == ''){
            alert('Please provide Firm.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_firm").focus();
            return false;
          }
		  if( $.trim($('#jform_url').val()) == ''){
            alert('Please provide URL.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_url").focus();
            return false;
          }
		  if( $.trim($('#jform_path_quote').val()) == ''){
            alert('Please provide Path to quote.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_path_quote").focus();
            return false;
          }
		  if( $.trim($('#jform_custom_link_article').val()) == ''){
            alert('Please provide Custom Link For Articles.'); 
            $("#tabs").tabs({active: 0});
            $("#jform_custom_link_article").focus();
            return false;
          }
		  
		  if(address1 == ''){
            alert('Please provide address.'); 
            $("#tabs").tabs({active: 0});
            $("#address1").focus();
            return false;
          }
          
          if(city == ''){
            alert('Please provide city.'); 
            $("#tabs").tabs({active: 0});
            $("#city").focus();
            return false;
          }
          
          if(zip == ''){
            alert('Please provide zip.'); 
            $("#tabs").tabs({active: 0});
            $("#zip").focus();
            return false;
          }
          
          if(state == ''){
            alert('Please provide state.'); 
            $("#tabs").tabs({active: 0});
            $("#state").focus();
            return false;
          }  
          
         var curTab = $('.ui-tabs-active');
         curTabIndex = curTab.index();

     
	});
  
	
	// Change url of 'Get Access Token' link based on api selection
	$('.newsletter_api').click(function(){
		var api = $(this).val();
		$('#api_login_name').val('');
		$('#apikey').val('');
		$('#get_from_email_div').hide();
		$('#get_access_token_li').show();
		$('#email_campaign_key').val(api);	
		
			$('#get_access').css('visibility','visible' );
		
			if(api == 'M'){		     
				$('#get_access').attr('href','<?php echo $client->getLoginUri();?>');
			}else if(api == 'C'){
				$('#get_access').attr('href','<?php echo $oauth->getAuthorizationUrl();?>' );
			}else if(api == 'G'){
				$('#get_access').css('visibility','hidden' );
			}else if(api == 'I'){
				$('#get_access').css('visibility','hidden' );
			}
	});
});
</script>

<script type="text/javascript">
// Initialize squeezebox 
window.addEvent('domready', function() {

 SqueezeBox.initialize();
 
 SqueezeBox.assign($$('a[rel=boxed][href^=#]'), {
  size: {x: 700, y: 500}
 });
 
});
</script>

<!-- Start newsletter set up form -->
<form action="<?php echo JRoute::_('index.php?option=com_enewsletter&view=advisors'); ?>" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data">

<?php
if($this->item->id != 0){
	$id = $this->item->id;
}else{
	$id =  $app->getUserState("com_enewsletter.User_ID");
	echo '<input type="hidden" name="jform[user]" value="advisor" />';
}
?>

<div id="tabs">
	<ul>
		<li><a href="#tabs-1" class="alltab" >Set Up</a></li>
    	<li><a href="#tabs-4" class="alltab" >Weekly Update Set Up</a></li>
		<li><a href="#tabs-2" class="alltab" >Newsletter Set Up</a></li>
		<li><a href="#tabs-3" class="alltab" >Mass Email Set Up</a></li>
	</ul>
	
	
	<div id="tabs-1">
	<div class="width-100">
			<fieldset class="adminform">
			<legend><?php echo JText::_('E-Newsletter Set Up Page - Advisor ID: '); echo $id;?> 
                        
                            
                        
                        </legend>
				<div class="fltlft">
					<ul class="adminformlist">
						
						<li>
                                                    <label>Profile Setting<span class="star"> *</span></label>
                                <select name="iduser" onchange="$('#formview').val('advisorsetting');$('#user-form').submit()" >
                                                <option value="" >Global </option>
                                                <?php foreach ($this->us as $r) { ?>
                                                <option <?php if($r->id == $this->post['iduser']){ echo 'selected="selected"';} ?>  value="<?php echo $r->id ?>" ><?php echo $r->id ?> <?php echo $r->name ?> <?php echo $r->email ?></option>
                                                <?php  }?>
                                  </select> 
                                     <?php if(!$this->details): ?>
                                     <label>Use Global Setting</label>  
                                     <input onclick="$('#formview').val('advisorsetting');$('#user-form').submit()" name="datade" type="checkbox" value="1"  />
                                     <?php endif; ?>
                                     
							<label>Select email campaign service<span class="star"> *</span>
                <a href="#hintbox" rel="boxed" >
                <img src="<?php echo JURI::base()."components/com_enewsletter/images/";?>BTTN_HintTrigger.png" class="hinthelp" />
                </a>
              </label>						
							<fieldset class="radio">
                <?php if($this->details->api_key){$cdisable = 'disabled';}else{$cdisable = '';}?>
								<input type="radio" value="C" class="newsletter_api" name="jform[newsletter_api]" <?php if(@$this->details->newsletter_api == 'C' || @$this->details->newsletter_api == '') echo "checked='true'";?> <?php echo $cdisable;?> />
								<label>Constant Contact</label>
								<input type="radio" value="M" class="newsletter_api" name="jform[newsletter_api]" <?php if(@$this->details->newsletter_api == 'M') echo "checked";?> <?php echo $cdisable;?> />
														
								
								<input type="hidden" name="jform[email_campaign_key]" id="email_campaign_key" value="<?php if($this->details->newsletter_api)echo $this->details->newsletter_api;else echo 'C';?>" >
                				<label>Mailchimp</label>
								
								
								
								<input type="radio" value="G" class="newsletter_api" name="jform[newsletter_api]" <?php if(@$this->details->newsletter_api == 'G') echo "checked";?> <?php echo $cdisable;?> />	
								<label>GetResponse</label>
								
								<input type="radio" value="I" class="newsletter_api" name="jform[newsletter_api]" <?php if(@$this->details->newsletter_api == 'I') echo "checked";?> <?php echo $cdisable;?> />	
								<label>infusionsoft</label>
								
							</fieldset>
						</li>
            
            <div id="clearall_div">
            <li style="clear:both;">
								<label>&nbsp;</label>	
                <a href="javascript:void(0)" onclick="clearall();" ><input type="button"  class="bfont setupbtn" value="Clear"  /></a>						
						</li>
            </div>

           
							<div id="get_access_token_li_div">
						<li id="get_access_token_li" style="clear:both;">
								<label >&nbsp;</label>
								<?php 
								if(@$this->details->newsletter_api == 'M'){
									$authurl = $client->getLoginUri();
								}else{
									$authurl = $oauth->getAuthorizationUrl();
								}
								?>
								<a href="<?php echo $authurl; ?>" id="get_access" target="_blank" >
									<img src="<?php echo JURI::base()."components/com_enewsletter/images/";?>getapikey.png"  />
								</a>
							</li>
              </div>
						
						<li>
							<label>Login Name<span class="star"> *</span></label>		
              <?php if($this->details->api_login_name){$lreadonly = 'readonly=true';}else{$lreadonly = '';}?>					                        
							<input type="text" size="30"  id="api_login_name" class="inputbox" value="<?php echo @$this->details->api_login_name;?>" name="jform[api_login_name]" <?php echo $lreadonly; ?> >
						</li>
							
						<li>
								<label>API Key<span class="star"> *</span></label>
                <?php if($this->details->api_key){$areadonly = 'readonly=true';}else{$areadonly = '';}?>
								<input type="text" size="30" id="apikey" class="inputbox" value="<?php echo @$this->details->api_key;?>" name="jform[api_key]" <?php echo $areadonly; ?> >
						</li>

							
							<?php 
							/*if($app->getUserState("com_enewsletter.User") == 'admin'){
								$readonly = '';
							}else{
								$readonly = 'readonly="true"';
							}*/
							?>
							
							
						<li>
							<label>Archive CC List</label>							                        
							<input type="text" size="30"  id="archive_cc_list" <?php echo $readonly;?> class="inputbox" value="<?php echo @$this->details->archive_cc_list;?>" name="jform[archive_cc_list]">
						</li>
						
						<?php //if($app->getUserState("com_enewsletter.User") == 'admin'){?>
						<li>
							<label>Firm <span class="star"> *</span></label>						
							<input type="text" size="30" class="inputbox required" value="<?php echo @$this->details->firm;?>"  name="jform[firm]" id="jform_firm">
						</li>

						<li>
							<label>Url <span class="star"> *</span></label>						
							<input type="text" size="30" class="inputbox required" value="<?php echo @$this->details->url;?>"  name="jform[url]" id="jform_url">
						</li>
						<li>
							<label>Path to quote <span class="star"> *</span></label>						
							<input type="text" size="30" class="inputbox required" value="<?php echo @$this->details->path_quote;?>"  name="jform[path_quote]" id="jform_path_quote">
						</li>
						<li>
							<label>Custom Link For Articles <span class="star"> *</span></label>						
							<input type="text" size="30" class="inputbox required" value="<?php echo @$this->details->custom_link_article;?>"  name="jform[custom_link_article]" id="jform_custom_link_article">
						</li>
						<li>
							<label>Allow To Use Subscriber Name</label>						
							<input type="checkbox" value="Y" name="jform[allow_to_use_name]" <?php if(@$this->details->allow_to_use_name == 'Y') echo "checked";?> />
						</li>
						<li>
							<label>Custom Website</label>						
							<input type="checkbox" value="Y" name="jform[customer_website]" <?php if(@$this->details->customer_website == 'Y') echo "checked";?> />
						</li>
						<?php //} ?>
							
					</ul>
				</div>
        
        <div class="fltlft" style="margin-left:50px;" id="get_from_email_div" >
      		<fieldset class="adminform" style="width:100%;">
      			<legend></legend>
      			<h3>Details from email campaign service.</h3>
            
            <ul class="advisordetails">
						
                <li>
    							<label style="min-width:140px;" >Default From Email<span class="star"> *</span></label>	
                  <?php 
                    if($app->getUserState("com_enewsletter.API") == 'M'){
                      
                    }
                  ?>					
    							  <div id="verified_email" style="width:440px;" >
                    </div>
    						</li>
                
                
                
                <li>
    							<label style="min-width:140px;" >Default From Name<span class="star"> *</span></label>						
    							<input type="text" size="30" class="inputbox" style="width:290px;" id="from_name" value="<?php echo @$this->details->from_name;?>" name="jform[from_name]">
    						</li>
    						
    						
    						
    					</ul>
          
          </fieldset>
        </div>
				

			</fieldset>
		</div>
		
		
		<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend></legend>
			<h3>Address information used for new subscriber emails</h3>
			
			<ul class="advisordetails">
				<li>
					<label>Newsletter</label>					
					<input type="checkbox" value="Y" name="jform[send_newsletter]" <?php if(@$this->details->send_newsletter == 'Y') echo "checked";?> /> <?php
						echo JHTML::tooltip('<p>Send latest newsletter mail to added subscriber</p>', '', '', '<img src="'.$this->baseurl.'/components/com_enewsletter/images/info.png">');
						?>
				</li>
				<li>
					<label>Update</label>					
					<input type="checkbox" value="Y" name="jform[send_update]" <?php if(@$this->details->send_update == 'Y') echo "checked";?> /> <?php
						echo JHTML::tooltip('<p>Send latest weekly update mail to added subscriber</p>', '', '', '<img src="'.$this->baseurl.'/components/com_enewsletter/images/info.png">');
						?>
				</li>
         
        		<li>
					<label>Address<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox" id="address1" value= "<?php echo @$this->details->address1;?>"  name="jform[address1]">
				</li>
				
				<li>
					<label>Address 2</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo @$this->details->address2;?>"  name="jform[address2]">
				</li>
				
				<li>
					<label>Phone</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo @$this->details->phone;?>"  name="jform[phone]">
				</li>
				
				<li>
					<label>City<span class="star"> *</span></label>						
					<input type="text" size="30" id="city" class="inputbox" value="<?php echo @$this->details->city;?>"  name="jform[city]">
				</li>
				
				<li>
					<label>Zip<span class="star"> *</span></label>						
					<input type="text" size="30" id="zip" class="inputbox" value="<?php echo @$this->details->zip;?>"  name="jform[zip]">
				</li>
				
				<li>
					<label>State<span class="star"> *</span></label>						
					<input type="text" size="30" id="state" class="inputbox" value="<?php echo @$this->details->state;?>"  name="jform[state]">
				</li>
				
				<li  style="display:none;">
					<label>Country</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo @$this->details->country;?>"  name="jform[country]">
				</li>
			</ul>
		</fieldset>
		</div><!--60-->
		<div class="width-40 fltrt">
			<fieldset class="adminform">
				<legend></legend>
				<h3>Second Address</h3>
				<ul>
					<li>
					<label>Address</label>						
					<input type="text" size="30" class="inputbox" value= "<?php echo $this->details->second_address1;?>"  name="jform[second_address1]">
				</li>
				
				<li>
					<label>Address 2</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo $this->details->second_address2;?>"  name="jform[second_address2]">
				</li>
				
				<li>
					<label>Phone</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo $this->details->second_phone;?>"  name="jform[second_phone]">
				</li>
				
				<li>
					<label>City</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo $this->details->second_city;?>"  name="jform[second_city]">
				</li>
				
				<li>
					<label>Zip</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo $this->details->second_zip;?>"  name="jform[second_zip]">
				</li>
				
				<li>
					<label>State</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo $this->details->second_state;?>"  name="jform[second_state]">
				</li>
				
				<li style="display:none;">
					<label>Country</label>						
					<input type="text" size="30" class="inputbox" value="<?php echo $this->details->second_country;?>"  name="jform[second_country]">
				</li>
					
				</ul>
			</fieldset>
		</div>
		<div class="clr"></div>

		<div class="width-80 fltlft">
		<fieldset class="adminform">
			<legend></legend>
			<h3>Header Info</h3>
			<ul class="advisordetails">
				<li>
					<label>Logo</label>						
					<input type="file" name="logo" />
					<?php
					if ($this->details->logo) {
						$logo = '<div class="logo_preview"><img src="'.JURI::root(true)."/media/com_enewsletter/logo/".$this->details->logo.'" border=0/></div>';
						echo JHTML::tooltip($logo, '', '', 'preview');
					}//if
					?>
				</li>
				<?php
				if (strlen( $this->details->social_links ) >0)
				{
					$social_links = json_decode($this->details->social_links);
				
					foreach ($social_links as $keyy => $vv) {
						$$keyy = $vv;
					}//for
				}
				?>
				<li>
					<label>Social Links</label>						
					<ul>
						<li><label>Facebook:</label> <input type="text" size="60" class="inputbox" value="<?php echo $facebook;?>" name="jform[social_links][facebook]"> 
						<?php
						echo JHTML::tooltip('<p>Facebook page link</p>', '', '', '<img src="'.$this->baseurl.'/components/com_enewsletter/images/info.png">');
						?>
						
						</li>
						<li><label>Twitter:</label> <input type="text" size="60" class="inputbox" value="<?php echo $twitter;?>" name="jform[social_links][twitter]"> 
						<?php
						echo JHTML::tooltip('<p>Twitter link</p>', '', '', '<img src="'.$this->baseurl.'/components/com_enewsletter/images/info.png">');
						?>
						</li>
						<li><label>LinkedIn:</label> <input type="text" size="60" class="inputbox" value="<?php echo $linkedin;?>" name="jform[social_links][linkedin]"> 
						<?php
						echo JHTML::tooltip('<p>LinkedIn link</p>', '', '', '<img src="'.$this->baseurl.'/components/com_enewsletter/images/info.png">');
						?>
						</li>
					</ul>
					<div class="clr"></div>
				</li>
				<li>
					<label>Banner Image</label>						
					<input type="file" name="bannerimg" />
					<?php
					if ($this->details->bannerimg) {
						$bannerimg = '<div class="logo_preview"><img src="'.JURI::root(true)."/media/com_enewsletter/banner/".$this->details->bannerimg.'" border=0/></div>';
						echo JHTML::tooltip($bannerimg, '', '', 'preview');
						?>
					<p style="clear:both; float:none;"><label>&nbsp;</label><input type="checkbox" name="delbanner" value="1" /> Remove Banner</p>
					
					<input type="hidden" name="oldbanner" value="<?php echo $this->details->bannerimg;?>" />
					
						<?php
					}//if
					?>
				</li>
				<li>
					<label>Address Position</label>
					<fieldset class="checkboxes">
						<?php
						$opts = array();	
						$opts[] 	= JHTML::_('select.option',  '0', 'Top' );
						$opts[] 	= JHTML::_('select.option',  '1', 'Bottom' );
						$opts[] 	= JHTML::_('select.option',  '2', 'Both' );
						echo JHTML::_('select.radiolist',  $opts, 'jform[address_position]', 'class=""', 'value', 'text', intval($this->details->address_position) );
						?>
					</fieldset>
				</li>	
			</ul>
		</fieldset>
		</div><!--width-80-->
		<div class="width-20 fltrt">
			<div style="padding:10px;"><a href="<?php echo $this->baseurl.'/components/com_enewsletter/images/';?>banner.jpg" class="modal"><img src="<?php echo $this->baseurl.'/components/com_enewsletter/images/';?>banner.jpg" border="0" style="max-width:100%;" /></a></div>
		</div><!--width-40-->
		<div class="clr"></div>
		
	</div>
	<div id="tabs-4">
				
				<div>	
					<label>Weekly Update Subject<span class="star"> *</span></label>	
          <?php 
            if($this->details->update_subject){
              $weeklysubject   =    $this->details->update_subject;
            }else{
              $weeklysubject   =    'Weekly Update';
            }
          ?>	
					<input type="text" size="100" maxlength="200" id="weekly_subject" class="inputbox" value="<?php echo htmlentities($weeklysubject);?>" name="jform[update_subject]"  />
				  <br><br>
         
		 <span style="display:block;">
		  <input type="radio" value="Y" id="auto_update" name="jform[auto_update]" <?php if (@$this->details->auto_update == 'Y') echo "checked";?> onclick="document.getElementById('autoorsemidiv').style.display='block';" />
          <label for="auto_update">Send Weekly Update</label>
		  <input type="radio" value="N" id="auto_updateN" name="jform[auto_update]" <?php if (@$this->details->auto_update == 'N') echo "checked";?> onclick="document.getElementById('autoorsemidiv').style.display='none';" />
          <label for="auto_updateN">Not Send</label>
		  </span>
        </div>
		
		<?php
		$com_params = JComponentHelper::getParams('com_enewsletter');
		$sss2 = 'checked="checked"';
		$sss = '';
		if ($com_params->get('autoweeklysend', 1)) {
			$sss = 'checked="checked"';
			$sss2 = '';
		}//if
		?>
		<div id="autoorsemidiv" <?php if (@$this->details->auto_update == 'N') echo 'style="display:none;"';?>>
			<p>
				<input type="radio" name="autoweeklysend" value="1" id="autoweeklysend1" <?php echo $sss;?>  /> <label for="autoweeklysend1"> Weekly Update every Friday Automatically</label> <input type="radio" name="autoweeklysend" value="0" id="autoweeklysend0" <?php echo $sss2;?> /> <label for="autoweeklysend0">Semi-Auto</label>
			</p>
			<p>
				<label for="semiautoemail">Email for Semi-Auto</label> <input type="text" size="50" maxlength="150" id="semiautoemail" class="inputbox validate-email" value="<?php echo $com_params->get('semiautoemail');?>" name="semiautoemail" />
			</p>
       
			<div>
			  <label>Select the list you want to send the weekly update to</label>
			  <div id="listsdiv"></div>
			</div>
		
		 </div><!--autoorsemidiv-->
		
        <br /><br />
        
				
				<div>	
					<label class="bold">Weekly Update Intro</label>	
					<?php 
							$editor = JFactory::getEditor();
							echo $editor->display( 'jform[weekly_update_intro]', @$this->details->weekly_update_intro, '100%', '250px', '40', '10',false );
						?>		

				</div>
				<br /><br />
				<div>	
					<label class="bold">Weekly Update Disclosure</label>	
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[weekly_update_newsletter]', @$this->details->weekly_update_newsletter, '100%', '250px', '40', '10',false );
					?>	

				</div><p></p>

	</div>
	<div id="tabs-2">
		<p>
    <label class="bold">Disclosure</label>
			<?php 
				$editor = JFactory::getEditor();
				echo $editor->display( 'jform[newsletter_disclosure]', @$this->details->newsletter_disclosure, '100%', '250px', '40', '10',false);
			?>
		</p>
	</div>
	
	<div id="tabs-3">
		<p>
    <label class="bold">Disclosure</label>
			<?php 
				$editor = JFactory::getEditor();
				echo $editor->display( 'jform[mass_email_disclosure]', @$this->details->mass_email_disclosure, '100%', '250px', '40', '10',false );
			?>
		</p>
	</div>
	
	
	
	
	
	
</div>
	<input type="hidden" name="task" value="" />
        <input type="hidden" name="view" id="formview" value="" />
	<input type="hidden" name="option" value="com_enewsletter" />
	<input type="hidden" name="jform[id]" value="<?php echo $id;?>" />
</form>
<!-- End newsletter set up form -->
<?php 
$app->setUserState("com_enewsletter.data",'');
$app->setUserState("com_enewsletter.gid",'');
$app->setUserState("com_enewsletter.email",'');
$app->setUserState("com_enewsletter.cid",'');
?>
<div style="display:none;">
  <div id="hintbox" >Mailchimp or Constant Contact</div>
</div>