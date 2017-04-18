<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require(JPATH_SITE.'/administrator/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/administrator/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Exceptions\CtctException;

// Get api details
$app = JFactory::getApplication();
$api=$app->getUserState("com_enewsletter.API");
$APIKEY  = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");

// Get all data of current page
$data=$app->getUserState("com_enewsletter.data");
$gid = $app->getUserState("com_enewsletter.gid");

$mm = new MCAPI(trim($ACCESS_TOKEN));
$cc = new ConstantContact(trim($APIKEY));
if($api == 'M'){
	$campaigns = $mm->campaigns();
	$campaigns = $campaigns['data'];
	$lists = array();
	$list = $mm->lists();

	foreach($list['data'] as $li){
		$lists[$li['id']] = $li['name'];
	}
  
	
	$campaign_ids = array();
	$campaign_webids = array();
	foreach($campaigns as $cm){							
		$campaign_ids[] = $cm['id'];
		$campaign_webids[$cm['id']] = $cm['web_id'];
		$campaign_listids[$cm['id']] = $cm['list_id'];
	}
	
}

// Add scripts and css
// include css and js files
$document = JFactory::getDocument();
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-1.9.1.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-ui.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery.dataTables.js");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/jquery-ui.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/demo_page.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/demo_table.css");

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<link rel="stylesheet" href="<?php echo JURI::root();?>media/system/css/modal.css" type="text/css" />
<script src="<?php echo JURI::root();?>media/system/js/modal-uncompressed.js" type="text/javascript"></script>

<!-- Assigns height and width of popup window -->
<style>
#sbox-window{width:800px !important;height:700px !important;}

table.adminlist .sorting_asc {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_asc.png') no-repeat center right !important;
}
table.adminlist .sorting_desc {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_desc.png') no-repeat center right !important;
}
table.adminlist .sorting {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_both.png') no-repeat center right !important;
}
</style>	

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		
		// Assigns delete action to delete icon 
		$('#toolbar-delete a').attr("onclick","Joomla.submitbutton('savedemail.delete')");
		
		// Assigns datatable to saved email list
		$('#savedemails').dataTable( {
			"aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]],
      		'iDisplayLength': 50,
       		"aoColumnDefs": [
         		{ "bSortable": false, "aTargets": [ 0,5,7,9 ] }
        	] 
		} );
	} );

// This function is used to check/uncheck all checkbox foe group and subscriber
function mailsCheckAll(a){
	var checked = $(a).is(':checked');
	if(checked){
		 $("."+a.name).prop("checked", true);
	 }else{
		$("."+a.name).prop("checked", false);
	 }
}	
</script>

<script type="text/javascript" charset="utf-8">
	
// Used to perform different action send/edit/preview for individual saved mail
function performaction(id,action){
	$('#email_id').val(id);
	if(action == 'edit' || action == 'send' || action == 'selectgroup'){
		$('#stask').val(action);
		Joomla.submitbutton('savedemail.edit');
	}else if(action == 'preview'){
		Joomla.submitbutton('savedemail.preview');
	}else if(action == 'send_email'){
	 	 $("#email_"+id).prop('checked', true);
		Joomla.submitbutton('savedemail.send');
	}else if(action == 'sendtocompliance'){
		$("#email_"+id).prop('checked', true);
		Joomla.submitbutton('savedemail.sendtocompliance');
	}
	
}

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

<script type="text/javascript">

	// Perform submit action 
	Joomla.submitbutton = function(task)
	{
		// Used for confirmation when we delete record
		if(task == 'savedemail.delete' || task == 'savedemail.send' || task == 'savedemail.sendtocompliance'){
			if($( "#savedemails input:checkbox:checked" ).length == 0){
				alert('Select at least one list.');
				return false;
			}
			
			if(task == 'savedemail.delete'){
			  var confirmBox = confirm("Delete - are you sure?");
   				if(!confirmBox){
					return false;
				}
			}
		}

		if (task == 'savedemail.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<div class="clr"> </div>
<!-- Start saved email form -->
<form action="" method="post" name="adminForm" id="user-form"  enctype="multipart/form-data">

	<div class="width-100">
		<table class="adminlist" id="savedemails">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkallgroup" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="mailsCheckAll(this);" />
					</th>
					<th width="8%" class="left">
						<?php echo  'Date'; ?>
					</th>
          
          			<th class="left" width="20%">
						<?php echo 'Title'; ?>
					</th>
					
					<th class="left" width="20%">
						<?php echo 'Subject'; ?>
					</th>
          
         			 <th class="left" width="8%">
						<?php echo 'Edited By'; ?>
					</th>
					
					<th class="left" width="13%">
						<?php echo 'Actions'; ?>
					</th>
          
					<th class="left" width="8%">
						<?php echo 'Email Type'; ?>
					</th>
					<th class="left" width="5%">
						<?php echo 'Send Email'; ?>
					</th>
					<th class="left " width="10%">
						<?php echo 'Campaign Details'; ?>
					</th>
					<!--#HT-->
					<?php if ($this->doCompliance):?>
					<th class="left" width="8%" nowrap="nowrap">
						Compliance Status
					</th>
					<?php endif;?>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($this->item as $i => $g) :?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
							<input class="checkallgroup" id="email_<?php echo $g->id;?>" type="checkbox"  value="<?php echo $g->id;?>" name="email_id[]" onclick="Joomla.isChecked(this.checked);" /> 
					</td>
					<td class="left" >
						<?php echo $g->dte_created; ?>
					</td>	
          
          			<td class="left">
						<?php echo $g->title; ?>
					</td>		
					
					<td class="left">
						<?php echo $g->subject; ?>
					</td>
          
          			<td class="left">
						<?php echo $g->name; ?>
					</td>
					
					<td class="left">
						<?php
						if ($this->doCompliance) { 
							if( $g->approval_status == 'PND' && $g->email_sent_status == 0 ){?>
								<a href="javascript:void(0)" onclick="performaction(<?php echo $g->id; ?>,'edit')">Edit</a> |
							<?php } ?>
							<a href="#savedmail_<?php echo $g->id;?>" rel="boxed" >Preview </a>
							
							<?php if( $g->approval_status == 'APR' && $g->email_sent_status == 0 ){ ?>
								<?php if($g->group == 'Y'){?>
										   |<a href="javascript:void(0)" onclick="performaction(<?php echo $g->id; ?>,'send')"> Send </a>
								  <?php }else if($g->group == 'N'){?>
									  |<a href="javascript:void(0)" onclick="performaction(<?php echo $g->id; ?>,'selectgroup')"> Select Lists </a>
								  <?php }?>
							<?php } ?>
						<?php } else { ?>
							<?php 
								if($g->approval_status == 'APR' || $g->approval_status == ''){ ?>
									<a href="javascript:void(0)" onclick="performaction(<?php echo $g->id; ?>,'edit')" >Edit </a>|
								<?php } ?>
					
								<a href="#savedmail_<?php echo $g->id;?>" rel="boxed" >Preview </a>
					
								<?php if($g->approval_status == 'APR' || $g->approval_status == ''){ ?>
					  				<?php if($g->group == 'Y'){?>
									   |<a href="javascript:void(0)" onclick="performaction(<?php echo $g->id; ?>,'send')"> Send </a>
					  				<?php }else if($g->group == 'N'){?>
						  				|<a href="javascript:void(0)" onclick="performaction(<?php echo $g->id; ?>,'selectgroup')"> Select Lists </a>
					  				<?php }?>
								<?php } ?>
						<?php } ?>
					</td>
					
					
					<td class="left">
						<?php echo $g->type; ?>
					</td>
					
					<td class="left">
						<?php 
						if($g->approval_status == 'APR' || $g->approval_status == ''){
  							if($g->email_sent_status == '1'){
  								echo 'Sent';
  							}else{	
  							  echo 'Not Sent';
  							}
						 }else{
						 	if($g->approval_status == 'APR')
							$status = 'Approved';
							else if($g->approval_status == 'PND')
							$status = 'Pending';
							else if($g->approval_status == 'REJ')
							$status = 'Rejected';
							
						 	echo $status;
						 }?>
					</td>
					
					<td class="left">
					<?php 
					if($g->email_sent_status == '1'){
						if($api == 'C'){
						  
						  try{
    						   $campaign_details = $cc->getEmailCampaign($ACCESS_TOKEN, $g->email_id);
        			           echo '<a href="https://ui.constantcontact.com/rnavmap/evaluate.rnav/?activepage=report.ecampaigns&pageName=report.ecampaigns&action=sent&agent.uid='.$g->email_id.'" target="_blank" >Launch</a>';
						  }catch (CtctException $ex){
							 echo 'Not connected';
						  }
						}else if($api == 'M'){
							$email_ids = array();
							$email_ids = explode(',',$g->email_id);
									
							for($i=0;$i<count($email_ids);$i++){
								if(in_array($email_ids[$i],$campaign_ids)){
									echo '<a href="https://us7.admin.mailchimp.com/reports/summary?id='.$campaign_webids[$email_ids[$i]].'" target="_blank" >';
									echo 'Launch - '.$lists[$campaign_listids[$email_ids[$i]]].'</a><br>';
								}else{
				                      echo 'Not connected';
                				}
							}//for
						}//if
					} else {
						echo '';	
					}//if
					?>
					</td>
					<?php if ($this->doCompliance):?>
					<td class="center">
						<?php 
						if (isset($g->status_approved)) {
							if ($g->status_approved=='-1')
								echo '<a class="jgrid" title="Rejected"><span class="state expired"><span class="text">Rejected</span></span></a>';
							else if ($g->status_approved == 1)
								echo '<a class="jgrid" title="Approved"><span class="state publish"><span class="text">Approved</span></span></a>';
							else if ($g->status_approved != NULL)
								echo '<a class="jgrid" title="Pending"><span class="state pending"><span class="text">Pending</span></span></a>';
						}//if
						?>
					</td>
					<?php endif;?>
				</tr>
				<?php  endforeach; ?>
			</tbody>
		</table>
			
	</div>
	
	<div>
	<input type="hidden" name="stask" id="stask" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="email_id" name="jform[id]" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<!-- End saved email form --> 

<?php foreach ($this->item as $i => $g){?>
	<div style="display:none;" >
		<div id="savedmail_<?php echo $g->id;?>" >
    <?php if($g->type == 'weeklyupdate'){?>
    <style>
    p {
        font-size: 1.5em;
        margin: 0 0 15px 10px;
        padding: 0;
    }
  </style>
    <?php } ?>
			<?php 
				$dom=new SmartDOMDocument();
				$dom->loadHTML($g->content);
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
<?php }?>


<?php 
$app->setUserState("com_enewsletter.data",'');
$app->setUserState("com_enewsletter.gid",'');
$app->setUserState("com_enewsletter.cid",'');
$app->setUserState("com_enewsletter.showimage_ids",'');
?>

