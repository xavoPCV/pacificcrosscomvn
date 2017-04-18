<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// include mailchimp library file 
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey


// Get api details
$app = JFactory::getApplication();
$APIKEY  = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");
$MAPI = $app->getUserState("com_enewsletter.API");
$emailtemplates = $app->getUserState("com_enewsletter.weeklyupdatetemplates");
$default_template = $app->getUserState("com_enewsletter.weeklyupdate_default_template");

// Get all lists of mailchimp
$api = new MCAPI(trim($ACCESS_TOKEN));
$groups = $api->lists();
if ($api->errorCode){
	$app->enqueueMessage(JText::_($api->errorMessage),'error');
	$app->redirect('index.php?option=com_enewsletter');
	return;
} 
$groups = $groups['data'];

// Get all inserted/selected data when we preview/test weekly update form
$data=$app->getUserState("com_enewsletter.data");
$gid = $app->getUserState("com_enewsletter.gid");

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$loggeduser = JFactory::getUser();
$advisoremail = $app->getUserState("com_enewsletter.testemail");

// include css and js files
// include css and js files
$document = JFactory::getDocument();
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-1.9.1.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-ui.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery.dataTables.js");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/jquery-ui.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/demo_page.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/demo_table.css");

// get default editor
$editor = JFactory::getEditor();
$params = array( 'smilies'=> '0' ,
				 'style'  => '1' ,  
				 'layer'  => '0' , 
				 'table'  => '0' ,
				 'clear_entities'=>'0',
				 'required'=>'required',
				 );


?>

<!-- set height and width of popup -->
<style>
#sbox-window{width:900px !important;height:700px !important;}

table.adminlist .sorting_asc {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_asc.png') no-repeat center right !important;
}
table.adminlist .sorting_desc {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_desc.png') no-repeat center right !important;
}
table.adminlist .sorting {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_both.png') no-repeat center right !important;
}
#verified_emails {float:none !important;}
</style>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		
		// Assign datatable to groups list
		$('#grouptable').dataTable( {
			"aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]],
      "aoColumnDefs": [
          { "bSortable": false, "aTargets": [ 0] }
        ],
      'iDisplayLength': 50,
			"oLanguage": {
			  "sSearch": "Search Lists:"
			}
		} );
    
    api = $('#apikey').val();
    apitype = $('#newsletter_api').val(); 
  //  alert(api);
    if(api){
        $.ajax({
               type : 'POST',
        			url: '<?php echo JURI::base();?>index.php?option=com_enewsletter&task=getverifiedemaillist',
              data :   'apitype='+apitype+'&apikey='+api,
        			success: function( data ){
                    $('#verified_email').html(data);              
              }
        		}); 
      }
		
	} );
	
</script>

<script type="text/javascript">
window.addEvent('domready', function() {

	 SqueezeBox.initialize({
		size: {x: 750, y: 600}
	});
	 
 
});
</script>

<script>
// This function is used to check/uncheck all checkbox foe group and subscriber
function groupCheckAll(a){
	var checked = $(a).is(':checked');
	if(checked){
		 $("."+a.name).prop("checked", true);
	 }else{
		$("."+a.name).prop("checked", false);
	 }
}


function fill_email(a){
  var val = a.value;
  $('#from_email').val(val);
}


// This function is used to get cookie for selected tab
function getCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
}

// This function is used to set cookie for selected tab
function setCookie(c_name, value, exdays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
}

$(document).ready(function() {	

$('#sbox-btn-close').click(function(){
	$('#preview').hide();
});

// Check for preview task, if preview then opens popup window
<?php if($data['task'] == 'preview'){
?>
	$('#preview').show();
	SqueezeBox.open('#preview','width=750,height=170');
<?php }else{
?>
	$('#preview').hide();
<?php }?>
	
	// set cookie when we change tab 
	$('.alltab').click(function(){
		setCookie('weeklytabSelected',this.id);
	});	
	
	// Get tab id and open last opened tab when we load form
	var id = getCookie('weeklytabSelected');
	
	if(typeof id == 'undefined'){
		setCookie('weeklytabSelected','ui-id-1');
		var id = getCookie('weeklytabSelected');
	}	
	
	id = id.replace('ui-id-','');
	id = Number(id) - 1;
	
	// Check for send action in saved email list page, if redirected by selecting send link in saved email list it will open group selection tab
	<?php if($app->getUserState("com_enewsletter.opengrouptab") == 'selectgroup'){ ?>
	id = 1;
	<?php }else { ?>
	id = 0;
	<?php }
	$app->setUserState("com_enewsletter.opengrouptab",'');?>

	
	$("#tabs").tabs({active: id});
	$('#tabs a').click(function(e) { 
		var curTab = $('.ui-tabs-active');
		curTabIndex = curTab.index();
		document.adminForm.currentTab.value = curTabIndex;
	});
	
});
</script>

<script type="text/javascript">

	// This function is used to perform all joomla task
	Joomla.submitbutton = function(task)
	{
    if(task == 'weeklyupdate.cancel'){  
      <?php 
           
    		$app->setUserState("com_enewsletter.data",'');
    		$app->setUserState("com_enewsletter.gid",'');
    		//$app->setUserState("com_enewsletter.cid",'');	
    
    	?>
    }

		if (task == 'weeklyupdate.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
	
			// Check for subject length
			var subjectlen = $('#jform_name').val().length;
      var subject =     $('#jform_name').val();
      var template = $('.wtemplate').val();
      var email_name = $('#verified_email_name').val();
      var fromemail = $('#verified_emails').val();
      
      if(task != 'weeklyupdate.cancel'){
      
          if(email_name == ''){     
            alert('Please provide from email address.');   
            $("#tabs").tabs({active: 0});
            return false;
          }
          if(fromemail == ''){
            alert('Please provide domain name for from email address.'); 
            $("#tabs").tabs({active: 0});
            return false;
          }
          
    			if(subjectlen == 0){
            alert('Please provide subject.');
            $("#tabs").tabs({active: 0});
    				return false;
          }else if(subjectlen > 200){
    				alert('Subject length should be less than 200 characters.');
            $("#tabs").tabs({active: 0});
    				return false;
    			}
          
          if(template == ''){
              alert('Please provide template.');
              $("#tabs").tabs({active: 0});
        			return false;
          }
      }
      
      
      if(task == 'weeklyupdate.saveascopy') {          
          if(subject == '<?php echo $data["subject"];?>')   {
            alert('Subject should not be same. Please change subject.');
            $("#tabs").tabs({active: 0});
            return false;
          }         
      } 
        
      if(task == 'weeklyupdate.saveandnew' || task == 'weeklyupdate.saveascopy'){
        $('#tmptask').val(task);
        task = 'weeklyupdate.apply';
        
    	}
		
			if(task == 'weeklyupdate.cancel'){
			<?php $app->setUserState("com_enewsletter.data",'');
				$app->setUserState("com_enewsletter.gid",'');
			?>
			}
			
			// Check for test email field when we click test button
			if(task == 'weeklyupdate.test'){
				var email = $('#jform_test_email').val();
				if(email == ''){
					$('#jform_test_email').focus();
					alert('Please provide test email.');
          $("#tabs").tabs({active: 0});			
					return false;
				}
			}
			
			// Check for group selection
			//#HT
			if(task == 'weeklyupdate.send' || task == 'weeklyupdate.sendtocompliance' || task == 'weeklyupdate.apply'){
				if($( "#grouptable input:checkbox:checked" ).length == 0){
					alert('Select at least one list.');
          			$("#tabs").tabs({active: 1});
					return false;
				}
			}
			
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<!-- Start weekly update form -->
<form action="<?php echo JRoute::_('index.php?option=com_enewsletter&view=weeklyupdate'); ?>" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data">
<a href="https://us7.admin.mailchimp.com/lists"  target="_blank" class="abutton" >Manage Lists</a><br /><br />
<input type="hidden" id="newsletter_api" value="<?php echo $MAPI;?>" >
<input type="hidden" id="apikey" value="<?php echo $ACCESS_TOKEN;?>" >
<div id="tabs">
	<ul>
		<li><a href="#tabs-1" class="alltab">Weekly Update</a></li>
		<li><a href="#tabs-2" class="alltab">Lists</a></li>
	</ul>	
	
	<div id="tabs-1">
		<div class="width-100">
			<fieldset class="adminform">
				<legend><?php echo JText::_('Weekly Update'); ?></legend>
				<div>
        
            <div>	
							<label title="">From Email Address <span class="star"> *</span></label>					
						  <div id="verified_email">
              </div>
						</div> 
            
            <div id="emailtemplatediv" >
              <?php  //unset($emailtemplates[1]);
              if(count($emailtemplates) > 1){    ?>
                  <label style="float:left;" >Email Template</label>
                  <div id="emailtemplatelistsdiv" >            
                      <select id="templateselect" class="wtemplate"  name="jform[wdefaultemail]" >
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
                  <input type="hidden" name="jform[wdefaultemail]" class="wtemplate" value="<?php echo $default_template;?>" >
              <?php }?>
            </div>        
					
					<div class="newseditor" style="clear:both;" >
						<label title="" id="jform_name-lbl" for="jform_name" class="required">Subject <span class="star"> *</span></label>
						<?php 
								if(!isset($data['subject'])){ 
									$subject = $this->subject;
								}else{ 
									$subject = $data['subject']; 
								}?>	
																	                       
						 <input type="text" size="100" maxlength="200" 3class="inputbox" value="<?php echo htmlentities($subject);?>" id="jform_name" name="jform[subject]" style="float:none;" >	
						 <br />
             <label title="" >&nbsp;</label>
						<span>Length should be less than 200 characters.</span> 
					</div>
					
					<div class="newseditor">
						<label title="" class="required">Test Email</label>												                        
            <?php if($advisoremail){ $aemail = $advisoremail;}else{ $aemail = @$loggeduser->email; }?>											                        
            <input type="text" size="30" class="inputbox" value="<?php echo $aemail; ?>" id="jform_test_email" name="jform[test_email]">
					</div>
		
					<div class="clr"></div>
					<label>Weekly Update Intro</label>
					<div class="clr"></div>
					<?php 
						if(!isset($data['intro'])){ 
							$intro = $this->weekly_intro;
						}else{ 
							$intro = $data['intro']; 
						} 
						echo $editor->display( 'jform[intro]',$intro, '100%', '250', '40', '10', false, null, null, null, $params );
					?>
										
				</div>				
				
			</fieldset>
			
			
		</div>
	</div>
	
	<div id="tabs-2">
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="grouptable">
				<thead>
					<tr>
						<th width="1%">
							<input type="checkbox" name="checkall_group" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />
						</th>
						<th class="left">
							<?php echo 'Lists'; ?>
						</th>
			
					</tr>
				</thead>

				<tbody>

				<?php foreach ($groups as $i => $group) :?>
					<?php if($group['stats']['member_count'] != 0) {?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="left">
						<?php 
							if(in_array($group['id'],$gid)){
								$gchecked = 'checked="checked"';
							}else{
								$gchecked = '';
							}?>
								<input id="cb2" class="checkall_group" type="checkbox" <?php echo $gchecked;?> onclick="Joomla.isChecked(this.checked);" value="<?php echo $group['id'];?>" name="gid[]" />
						</td>
					
					
						<td>
						<?php echo $this->escape($group['name']); ?>
						</td>
				
						
					</tr>
					<?php } ?>
					<?php  endforeach; ?>
				</tbody>
			</table>
		</div>
		
		<br /><br />

	</div>

</div>

	<input type="hidden" name="task" value="" />
  <input type="hidden" id="tmptask" name="tmptask" value="" />
	<input type="hidden" name="option" value="com_enewsletter" />
	<input type="hidden" name="jform[id]" value="<?php echo $this->id;?>" />
	<input type="hidden" name="jform[email_id]" value="<?php echo $this->email_id;?>" />
</form>
<!-- End weekly update form -->

<!-- Start weekly update layout for preview -->
<div style="display:none;">
	<div id="preview">
  
	 <style>
    p {
        font-size: 1.5em;
        margin: 0 0 15px 10px;
        padding: 0;
    }
  </style>
  
	<?php 
 
	
  	 $articlecontent = $data['articles'];
	 
	$templates = $app->getUserState("com_enewsletter.weeklyupdatetemplatefiles");
   	$WEEKLYUPDATE = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['wdefaultemail']]);  
	
	
	$content = enewsletterHelper::replaceTemplateCode('weeklyupdate', $data, NULL, $WEEKLYUPDATE);
		

	$dom=new SmartDOMDocument();
	$dom->loadHTML($content);
	
	$mock = new SmartDOMDocument;
	$body = $dom->getElementsByTagName('body')->item(0);
	foreach ($body->childNodes as $child){
		$mock->appendChild($mock->importNode($child, true));
	}
	
	
	$mock = $mock->saveHTML();
	
	echo $mock;

?>
 
</div>
</div>
<!-- End weekly update layout for preview -->
