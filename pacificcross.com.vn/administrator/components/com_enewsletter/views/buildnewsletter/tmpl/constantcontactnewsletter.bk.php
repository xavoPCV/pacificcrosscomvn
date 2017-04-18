<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// include constant contact library file 
require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

// Get api details
$app = JFactory::getApplication();
$APIKEY  = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN",'');
$MAPI = $app->getUserState("com_enewsletter.API"); 
$custome_url = $app->getUserState("com_enewsletter.custom_link_article");
$emailtemplates = $app->getUserState("com_enewsletter.newslettertemplates");
$default_template = $app->getUserState("com_enewsletter.newsletter_default_template");

// Get all lists of constant contact
$error = '';
$cc = new ConstantContact($APIKEY);
try{
	$groups = $cc->getLists($ACCESS_TOKEN);
}catch (CtctException $ex) {
	$ccerror = $ex->getErrors();
	$error .= $ccerror[0]['error_message'];
	$app->enqueueMessage(JText::_($ccerror[0]['error_message'])." - ( from Constant Contact)",'error');
	$app->redirect('index.php?option=com_enewsletter');
	die();
}


// Get all inserted/selected data when we preview/test newsletter form
$data=$app->getUserState("com_enewsletter.data");
$cid = $app->getUserState("com_enewsletter.cid");
$gid = $app->getUserState("com_enewsletter.gid");
$showimages = $app->getUserState("com_enewsletter.showimage_ids");

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$loggeduser = JFactory::getUser();
$advisoremail = $app->getUserState("com_enewsletter.testemail");

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

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
				 'clear_entities'=>'0'
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
			"aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
      'iDisplayLength': 50,
      "aoColumnDefs": [
          { "bSortable": false, "aTargets": [ 0] }
        ],
			"oLanguage": {
			  "sSearch": "Search Lists:"
			}
		} );
		
		// Assign datatable to articles list
		oTable = $('#articletable').dataTable( {
			"aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
      		'iDisplayLength': 50,
      		"aoColumnDefs": [
          		{ "bSortable": false, "aTargets": [ 0] }
        	],
			"oLanguage": {
			  "sSearch": "Search Articles:"
			}
		} );
		
		oTable2 = $('#articletable2').dataTable( {
			"aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
      		'iDisplayLength': 50,
      		"aoColumnDefs": [
          		{ "bSortable": false, "aTargets": [ 0] }
        	],
			"oLanguage": {
			  "sSearch": "Search Articles:"
			}
		} );
		
		// set cookie when we change tab 
		$('.alltab').click(function(){
			setCookie('tabSelected',this.id);
		});
    
    $('.checkall').on('click',function(){
      if(this.checked == true)  {
	  
          $('#s_'+this.value).prop('checked',true);
       }else{
          $('#s_'+this.value).prop('checked',false);
       }
		});
    
    api = $('#apikey').val();
    apitype = $('#newsletter_api').val(); 
  
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

// This function is used to check/uncheck all checkbox foe group and subscriber
function groupCheckAll(a){
	var checked = $(a).is(':checked');
	if(checked){
		 $("."+a.name).prop("checked", true);
	 }else{
		$("."+a.name).prop("checked", false);
	 }
}


// This function is used to open all subscriber list for particular group when we click on any group
function openSubscriber(id){
	$('.subscribers').hide();
	$('#group_'+id).show();
	
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
	$('#sbox-btn-close').prop('onclick','closesqeeze();');
<?php }else{
?>
	$('#preview').hide();
<?php }?>
	
	
	// Get tab id and open last opened tab when we load form
	var id = getCookie('tabSelected');
	
	if(typeof id == 'undefined'){
		setCookie('tabSelected','ui-id-1');
		var id = getCookie('tabSelected');
	}	
	
	id = id.replace('ui-id-','');
	id = Number(id) - 1;
	
	// Check for send action in saved email list page, if redirected by selecting send link in saved email list it will open group selection tab
	<?php if($app->getUserState("com_enewsletter.opengrouptab") == 'selectgroup'){ ?>
	id = 2;
	<?php }else{ ?>
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

// This function is used to perform all joomla task
Joomla.submitbutton = function(task)
{	

	var cid = [];
	$("input[name='cid[]']:checked", oTable.fnGetNodes()).each(function(i){
		cid.push(this.value);

	});
	
	$("input[name='cid[]']:checked", oTable2.fnGetNodes()).each(function(i){
		cid.push(this.value);

	});
	
	$(".allarticles").val(cid);
	
	 var sid = [];
	$("input[name='sid[]']:checked", oTable.fnGetNodes()).each(function(i){
		sid.push(this.value);

	});	
	
	$("input[name='sid[]']:checked", oTable2.fnGetNodes()).each(function(i){
		sid.push(this.value);

	});	
		
	$(".allshowimages").val(sid);
	
	
  
  if(task == 'buildnewsletter.cancel'){     
    <?php 
  
  		$app->setUserState("com_enewsletter.data",'');
  		$app->setUserState("com_enewsletter.gid",'');
  		$app->setUserState("com_enewsletter.cid",'');	
      $app->setUserState("com_enewsletter.showimage_ids",'');
  
  	?>
  }
	
	if (task == 'buildnewsletter.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
	
		// Check for title and subject length
			var titlelen = $('#jform_campaigntitle').val().length;
			var subjectlen = $('#jform_subject').val().length;
      var title =  $('#jform_campaigntitle').val();
      var subject =   $('#jform_subject').val();      
      var template = $('.ntemplate').val();
      var fromemail = $('#verified_emails').val();
	
			if(task != 'buildnewsletter.cancel'){
      
          if(fromemail == ''){     
            alert('Please provide from email address.');   
            $("#tabs").tabs({active: 0});
            return false;
          }
          
          if(titlelen == 0)  {
            alert('Please provide title.');
            $("#tabs").tabs({active: 0});
    				return false;
          }else if(titlelen > 60){
    				alert('Title length should be less than 60 characters.');
            $("#tabs").tabs({active: 0});
    				return false;
    			}
          
          
    			if(subjectlen == 0)  {
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
      
      
      
      if(task == 'buildnewsletter.saveascopy') {
          if(title == '<?php echo $data["title"];?>')   {
            alert('Title should not be same. Please change title.');
            $("#tabs").tabs({active: 0});
            return false;
          }
          
          if(subject == '<?php echo $data["subject"];?>')   {
            alert('Subject should not be same. Please change subject.');
            $("#tabs").tabs({active: 0});
            return false;
          }         
      }
       
      
      if(task == 'buildnewsletter.saveandnew' || task == 'buildnewsletter.saveascopy'){
        $('#tmptask').val(task);
        task = 'buildnewsletter.apply';       
    	}  
      
		
		// Check for article selection
		if( $( "#articletable input:checkbox:checked" ).length == 0 && $( "#articletable2 input:checkbox:checked" ).length == 0 && task != 'buildnewsletter.cancel'){
			alert('Select at least one article.');
      		$("#tabs").tabs({active: 1});
			return false;
		}
		
		
		
		
		

    	// Check for maximum article selection length
   		var maxlen = '<?php echo Articlemaxlen;?>';
		
		var alen = $( "#articletable input[name='sid[]']:checked" ).length + $( "#articletable2 input[name='sid[]']:checked" ).length;
		
		
		if( alen > maxlen && task != 'buildnewsletter.cancel'){
		
			alert('Maximum of '+maxlen+' articles allowed.');
			$("#tabs").tabs({active: 1});
				return false;
			}
		
		// Check for test email field when we click test button
		if(task == 'buildnewsletter.test'){
      
			var email = $('#jform_test_email').val();
			if(email == ''){
				$('#jform_test_email').focus();
				alert('Please provide test email.');	
        $("#tabs").tabs({active: 0});		
				return false;
			}
		}
		
		// Check for group selection
		//#HT - Check for Lists selection
		if(task == 'buildnewsletter.send' || task == 'buildnewsletter.sendtocompliance' || task == 'buildnewsletter.apply' ){
			if($( "#grouptable input:checkbox:checked" ).length == 0 && task != 'buildnewsletter.cancel'){
				alert('Select at least one list.');
       			 $("#tabs").tabs({active: 2});
				return false;
			}
      
		}
		
		Joomla.submitform(task, document.getElementById('user-form'));
		return false;
	}
}
</script>


<script type="text/javascript">
window.addEvent('domready', function() {

 SqueezeBox.initialize({
    size: {x: 750, y: 600}
});
 
 
});


</script>

<!-- Start build newsletter form -->
<div >
<form action="" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data">
<a href="https://ui.constantcontact.com/rnavmap/distui/contacts"  target="_blank" class="abutton" >Manage Lists</a><br /><br />
<input type="hidden" id="newsletter_api" value="<?php echo $MAPI;?>" >
<input type="hidden" id="apikey" value="<?php echo $ACCESS_TOKEN;?>" >

<input type="hidden" name="currentTab" />

<div id="tabs">
	<ul>
		<li><a href="#tabs-1" class="alltab">Personalize</a></li>
		<li><a href="#tabs-2" class="alltab">Articles</a></li>
		<li><a href="#tabs-4" class="alltab">Weekly Update</a></li>
		<li><a href="#tabs-3" class="alltab">Lists</a></li>
	</ul>
	
	
	<div id="tabs-1">
	<div class="width-100">
			<fieldset class="adminform">
				<legend><?php echo JText::_('Personalize'); ?></legend>
				<div>
									
					<ul class="adminformlist">
          
            <li>	
							<label title="">From Email Address <span class="star"> *</span></label>					
							<div id="verified_email">
              </div>
						</li> 
            
            <li id="emailtemplatediv" >
            <?php  
              if(count($emailtemplates) > 1){    ?>
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
							<label title="" >Subject Line <span class="star"> *</span></label>
							<div>
							<input type="text" size="100" maxlength="200" class="inputbox" value="<?php echo htmlentities(@$data['subject']);?>" id="jform_subject" name="jform[subject]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 200 characters.</span> 
							</div>						
							
						</li>
            
						<li style="clear:both;">
							<label title="" >Title <span class="star"> *</span></label>					
							<input type="text" size="100" maxlength="60" class="inputbox" value="<?php echo @$data['title'];?>" id="jform_campaigntitle" name="jform[title]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 60 characters. The title text will appear in the body of the E-Newsletter.</span>
						</li>						
						
						<li>
							<label title="" >Test Email Address</label>
              <?php if($advisoremail){ $aemail = $advisoremail;}else{ $aemail = @$loggeduser->email; }?>
							<input type="text" size="30" class="inputbox" value="<?php echo @$aemail; ?>" id="jform_test_email" name="jform[emailaddress]" />
						</li>

										
					</ul>
					
					<div class="clr"></div>
					<label>Newsletter Intro</label>
					<div class="clr"></div>
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[intro]',@$data['intro'], '100%', '250', '40', '10', false, null, null, null, $params );
					?>	
					
					<div class="clr"></div>
					<label>Trailer</label>
					<div class="clr"></div>
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[trailer]',@$data['trailer'], '100%', '250', '40', '10', false);
					?>
				
										
				</div>				
				
			</fieldset>
			
			
		</div>
	</div>

	<div id="tabs-2">
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="articletable">
				<thead>
					<tr>
						<th width="1%">
							<input type="checkbox" name="checkall" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />
						</th>
						<th class="left">
							<?php echo 'Title'; ?>
						</th>
            <th class="center" width="10%">
							Show Image
						</th>
            <th class="left">
							<?php echo 'Type'; ?>
						</th>
						<th class="left">
							<?php echo 'Date Created'; ?>
						</th>
						<th class="center">
							<?php echo 'FINRA Status'; ?>
						</th>	
					</tr>
				</thead>
				<tbody>
				<?php 
				$weekly_items = array();
				
				foreach ($this->items as $i => $item) :
						//skip weekly
						if ( strpos($item->keywords, 'weekly update')!== false ) {
							$weekly_items[] = $item;
							continue;
						}//if
				
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array(@$item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
								echo '<input class="checkall" type="checkbox" '.$checked.' onclick="Joomla.isChecked(this.checked);" value="'.@$item->article_id.'" name="cid[]" />';?>
						</td>
					
          <td class="left" >
						<?php 
              // Create article link
					  $articlelink='';
			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;
					  
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$item->article_id, trim($custome_url));
					  }
          			  else
          			  {
              				if($item->type == 'Featured News') 
              				{
              				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }
                  
                  echo '<a href="'.$articlelink.'" target="_blank">'.$item->article_title.'</a>';
            ?>
						</a>
						</td>
					
					 <td class="center">
							<?php
                if(in_array($item->article_id,$showimages)){
  								$checked = 'checked="checked"';
  							}else{
  								$checked = '';
  							}
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
              ?>
						</td>
            
            <td class="left" >
						<?php echo $item->type; ?>
						</td>
						
						<td class="left" >
						<?php echo $item->created; ?>
						</td>					
						<td class="center">
							<?php if ($item->finra_status):?>
							<a href="<?php echo 'index.php?option=com_apicontent&task=fnclist.getFinra&fid='.$item->article_id;?>" target="_blank"><img src="<?php echo $this->baseurl;?>/components/com_apicontent/edit_finra_icon.gif" height="30" width="30"></a>
							<?php else:?>
							<span style="color:red;">pending review</span>
							<?php endif;?>
						</td>
					</tr>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-2-->
	<div id="tabs-4">
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="articletable2">
				<thead>
					<tr>
						<th width="1%">
							<!--<input type="checkbox" name="checkall" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />-->
						</th>
						<th class="left">
							<?php echo 'Title'; ?>
						</th>
           				<th class="center" width="10%">
							Show Image
						</th>
            			<th class="left">
							<?php echo 'Type'; ?>
						</th>
						<th class="left">
							<?php echo 'Date Created'; ?>
						</th>
						<th class="center">
							<?php echo 'FINRA Status'; ?>
						</th>	
					</tr>
				</thead>
				<tbody>
				<?php foreach ($weekly_items as $i => $item) :
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array(@$item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
								echo '<input class="checkall" type="checkbox" '.$checked.' onclick="" value="'.@$item->article_id.'" name="cid[]" />';?>
						</td>
         				<td class="left" >
						<?php 
              		// Create article link
					  $articlelink='';
			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;
					  
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$item->article_id, trim($custome_url));
					  }
          			  else
          			  {
              				if($item->type == 'Featured News') 
              				{
              				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }
                  
                  echo '<a href="'.$articlelink.'" target="_blank">'.$item->article_title.'</a>';
            ?>
						</a>
						</td>
					
					 <td class="center">
							<?php
                if(in_array($item->article_id,$showimages)){
  								$checked = 'checked="checked"';
  							}else{
  								$checked = '';
  							}
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
              ?>
						</td>
            
            <td class="left" >
						<?php echo $item->type; ?>
						</td>
						
						<td class="left" >
						<?php echo $item->created; ?>
						</td>					
						<td class="center">
							<?php if ($item->finra_status):?>
							<a href="<?php echo 'index.php?option=com_apicontent&task=fnclist.getFinra&fid='.$item->article_id;?>" target="_blank"><img src="<?php echo $this->baseurl;?>/components/com_apicontent/edit_finra_icon.gif" height="30" width="30"></a>
							<?php else:?>
							<span style="color:red;">pending review</span>
							<?php endif;?>
						</td>
					</tr>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-4-->
	
	<div id="tabs-3" >
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
		
		<br /><br />		
		
		
	</div>		
	
</div><!--tabs-->
	<input type="hidden" name="views" value="buildnewsletter" />
	<input type="hidden" class="allarticles" name="articles" value="" />
	<input type="hidden" class="allshowimages" name="showimages" value="" />
	<input type="hidden" name="task" value="" />
  	<input type="hidden" id="tmptask" name="tmptask" value="" />
	<input type="hidden" name="option" value="com_enewsletter" />
	<input type="hidden" name="jform[id]" value="<?php echo @$this->id;?>" />
	<input type="hidden" name="jform[email_id]" value="<?php echo @$this->email_id;?>" />
</form>
</div>
<!-- End build newsletter form -->

<!-- Start newsletter layout for preview -->
<div style="display:none;">
	<div id="preview">
	
	<?php 
	
	$templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
	$NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
	$articleimgarray = array();
	$j  =1;
	
	foreach($data['articles'] as $a) {	
		  $articlebody = $a->description;										  
		  $articletitle_rpl = '{$articletitle'.$j.'}';
		  $articlebody_rpl = '{$articlebody'.$j.'}';
		  $slide_rpl = '{$articleimage'.$j.'}';
		  $articlelink_rpl = '{$articlelink'.$j.'}';
		  
		  if(trim($a->articlelink) ==''){
			$a->articlelink = 'javascript:void(0);';	
		  }
      
      if($a->image == ''){ 
            $articleimgarray[] = 'article_img_'.$j;
        }
      
      if($a->image != ''){
			     $rsearch  = array($articletitle_rpl,$articlebody_rpl,$slide_rpl,$articlelink_rpl);
		       $rreplace = array($a->article_title,$articlebody,$a->image,$a->articlelink);
			}else{
				   $rsearch  = array($articletitle_rpl,$articlebody_rpl,$articlelink_rpl);
		       $rreplace = array($a->article_title,$articlebody,$a->articlelink);
			}

		  $NEWSLETTER	=  str_replace($rsearch, $rreplace, $NEWSLETTER);
	
		  $j++;		
	} 
	
	$dom=new SmartDOMDocument();
	$dom->loadHTML($NEWSLETTER);
	for($m=0;$m<count($articleimgarray);$m++){
			$elements = $dom->getElementById($articleimgarray[$m]);
			if(!empty($elements)){
				$elements->parentNode->removeChild($elements);
			}			
		}
    
	 for($k=$j;$k<=5;$k++){
		$elements = $dom->getElementById('article_'.$k);
		if(!empty($elements)){
			$elements->parentNode->removeChild($elements);
		}			
	}
	
	 if(trim($data['intro']) == ''){
		$introelements = $dom->getElementById('intro');
		if(!empty($introelements)){
			$introelements->parentNode->removeChild($introelements);
		}			
	 }
	 
	 if(trim($data['trailer']) == ''){
		$trailerelements = $dom->getElementById('trailer');
		if(!empty($trailerelements)){
			$trailerelements->parentNode->removeChild($trailerelements);
		}			
	 }
     
     if(trim($data['newsletter_disclosure']) == ''){
		$disclosuretextelements = $dom->getElementById('disclosuretext');
		if(!empty($disclosuretextelements)){
			$disclosuretextelements->parentNode->removeChild($disclosuretextelements);
		}
		$disclosureelements = $dom->getElementById('disclosure');
		if(!empty($disclosureelements)){
			$disclosureelements->parentNode->removeChild($disclosureelements);
		}			
	 }  
	 
	$NEWSLETTER = $dom->saveHTML();

	// Set weekly update mail content
	//$rsearch  = array('{$title}','{$intro}','{$trailer}', '{$disclosure}');
	//$rreplace = array($data['title'],$data['intro'],$data['trailer'], $data['newsletter_disclosure']);
	
	$rsearch  = array('{$title}','{$intro}','{$trailer}', '{$disclosure}','{$logo}','{$firm_address}','{$social_links}','{$firm_phone}','{$firm_address2}','{$firm_city}','{$firm_zip}','{$firm_state}','{$firm_email}','{$firm_url}','{$firm_name}','{$firm_banner}');
	$rreplace = array($data['title'],$data['intro'],$data['trailer'], $data['newsletter_disclosure'],$data['logo'],$data['firm_address'],$data['social_links'],$data['firm_phone'],$data['firm_address2'],$data['firm_city'],$data['firm_zip'],$data['firm_state'],$data['firm_email'],$data['firm_url'],$data['firm_name'],$data['firm_banner']); 
	
	$content	=  str_replace($rsearch, $rreplace, $NEWSLETTER);
	
	$dom=new SmartDOMDocument();
	$dom->loadHTML($content);
	
	$mock = new DOMDocument;
	$body = $dom->getElementsByTagName('body')->item(0);
	foreach ($body->childNodes as $child){
		$mock->appendChild($mock->importNode($child, true));
	}
	
	if ($data['firm_banner']) {        	
		$enewsheader = $mock->getElementById('enewsheader');
		if($enewsheader) $enewsheader->parentNode->removeChild($enewsheader);
	}//if
	
	$mock = $mock->saveHTML();

	echo $mock;


?>	
 
</div>
</div>
<!-- End newsletter layout for preview -->