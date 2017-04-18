<?php
ini_set('max_execution_time', 0);
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include mailchimp/constant contact library files
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
$api_type = $app->getUserState("com_enewsletter.API");
$APIKEY  = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");  

// Create object of maichimp and constant contact
$api = new MCAPI(trim($ACCESS_TOKEN));
$cc = new ConstantContact(trim($APIKEY));
$campaign = new Campaign();

if($api_type == 'M'){
	$campaigns = $api->campaigns();
	$campaigns = $campaigns['data'];
	$lists = array();
	$list = $api->lists();

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
table.adminlist thead th {background:inherit !important;}
#sbox-window{top:50px !important; width:800px !important;height:700px !important;}
table.adminlist .sorting_asc {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_asc.png') no-repeat center right !important;
}
table.adminlist .sorting_desc {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_desc.png') no-repeat center right !important;
}
table.adminlist .sorting {
background: url('<?php echo JURI::base()."components/com_enewsletter/" ?>images/sort_both.png') no-repeat center right !important;
</style>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
	
	
		
		// Assigns datatable to history email list
		$('#historylist').dataTable( {
			"aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]],
      'iDisplayLength': 50,
      "aoColumnDefs": [
          { "bSortable": false, "aTargets": [ 0,3,4 ] }
        ]
		} );
		
/*		$('#sbox-btn-close').click(function(){
			SqueezeBox.close();
		});*/
		$('#toolbar-print a').removeAttr('onclick');
		$('#toolbar-export a').removeAttr('onclick');
		
		$('#toolbar-print a').attr("onclick","Joomla.submitbutton('history.print')");
		$('#toolbar-export a').attr("onclick","Joomla.submitbutton('history.export')");
				
	} );
	
	// This function is used to check/uncheck all checkbox foe group and subscriber
	function CheckAll(a){
		var checked = $(a).is(':checked');
		if(checked){
			 $("."+a.name).prop("checked", true);
		 }else{
			$("."+a.name).prop("checked", false);
		 }
	}	
</script>


<script type="text/javascript">
function printpage(id)
  {
  
  	var content = '';
	content += '<table>';
	content += $('#historymail_'+id+' table').html();
	content += '</table><br><br>';
	content += $('#subscriber_'+id).html();
	$('#print_div').html(content);
  	$('#print_div').printThis(); 

  }
  
window.addEvent('domready', function() {

 SqueezeBox.initialize();
 
 SqueezeBox.assign($$('a[rel=boxed][href^=#]'), {
  size: {x: 700, y: 700}
 });
 
});



  
function performaction(id){
	$('#email_id').val(id);	
	Joomla.submitbutton('history.preview');
}
</script>


<script type="text/javascript">

	// Perform submit action 
	Joomla.submitbutton = function(task)
	{
		// Check for history mail selection
		if (task == 'history.cancel' || document.formvalidator.isValid(document.id('user-form'))) {

			if(task == 'history.export' || task == 'history.print'){
				if($( "input[name='email_id[]']:checked" ).length == 0){
					alert('Select at least one list.');
					return false;
				}
			}
			
			if(task == 'history.print'){
				
				id = $("input[name='email_id[]']:checked:visible:first").val();
				var content = '';
				content += '<table>';
				content += $('#historymail_'+id+' table').html();
				content += '</table><br><br>';
				content += $('#subscriber_'+id).html();
				$('#print_div').html(content);
  				$('#print_div').printThis();

			}else{			
				Joomla.submitform(task, document.getElementById('user-form'));
				return false;
			}
		}
	}
</script>

<!-- Start history email form -->
<form action="" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data">

	<div class="width-100">
		<table class="adminlist" id="historylist" style="clear:both;">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkallgroup" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="CheckAll(this)" />
					</th>
					<th class="left" width="30%">
						<?php echo  'Email Subject'; ?>
					</th>
          
          <th class="left" width="10%">
						<?php echo  'Sent By'; ?>
					</th>
					
					<th class="left"  width="10%">
						<?php echo 'Date Sent'; ?>
					</th>
					
					<th class="center"  width="20%">
						#Of Subscribers Sent To
					</th>
					
					<th class="left"  width="20%">
						<?php echo 'Campaign Details'; ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				$displaylist = array();
			foreach ($this->item as $i => $g) :?>
				<tr class="row<?php echo $i % 2; ?>">
					<td class="center">
							<input class="checkallgroup" type="checkbox"  value="<?php echo $g->id;?>" name="email_id[]" onclick="Joomla.isChecked(this.checked);" />  
					</td>
				
				
					<td class="left" >
						<a href="#historymail_<?php echo $g->id;?>" rel="boxed">
						<?php echo $g->subject; ?>
						</a>
						
					</td>	
          
           <td class="left" >
						<?php echo $g->name; ?>
					</td>		
					
					<td class="left" >
						<?php echo $g->dte_send; ?>
					</td>
					
					<td class="center" >
						<?php  
						if($api_type == 'M'){
							$email_ids = array();
							$email_ids = explode(',',$g->email_id);
							$total = 0;
							foreach($email_ids as $ei){
								$members = $api->campaignMembers($ei);
                if ($api->errorCode){
        					$total = 'Not Connected';
                }else{
                   $total = $total+(int)$members['total'];
                }
								
							}
							
						}else if($api_type == 'C'){
                  
							$total = 0;
							$campaign->id = $g->email_id;

               try{
							   $campaign_details = $cc->getEmailCampaign($ACCESS_TOKEN, $g->email_id);
                 if($campaign_details->status == 'SENT'){
    							$groups_details = $cc->getEmailCampaignSummaryReport($ACCESS_TOKEN, $campaign);									
    							$total = $groups_details->sends;
    							}else{
    
    								$total = 0;
    							}
    					
              }catch (CtctException $ex){
                 $total = 'Not connected';
				 $campaign_details = NULL;
              }
						} else{
               $total = 'Not connected';
            }
														
							echo $total;
						?>
					</td>
					
					<td class="left" >
						<?php 
						
							if($api_type == 'M'){
							
								$groups = $api->lists();
								
								$email_ids = array();
								$email_ids = explode(',',$g->email_id);
								
								for($i=0;$i<count($email_ids);$i++){
									if(in_array($email_ids[$i],$campaign_ids)){
										echo '<a href="https://us7.admin.mailchimp.com/reports/summary?id='.$campaign_webids[$email_ids[$i]].'" target="_blank" >';
										echo 'Launch - '.$lists[$campaign_listids[$email_ids[$i]]].'</a><br>';
										
									}else{
                    echo 'Not connected';
                  }
								}
	
							}else if($api_type == 'C'){
	          
							   if($campaign_details->status == 'SENT'){
                              
    								$totalsends = $cc->getEmailCampaignSends($ACCESS_TOKEN, $g->email_id);    
    								
    								$k =1;
    								$list = '';
    								foreach($totalsends->results as $sr){
    									$list .= $k.'. '.$sr->email_address." <br>";
    									$k++;
    								}
    								
    								$displaylist[$g->email_id] = $list;
                    echo '<a href="https://ui.constantcontact.com/rnavmap/evaluate.rnav/?activepage=report.ecampaigns&pageName=report.ecampaigns&action=sent&agent.uid='.$g->email_id.'" target="_blank" >Launch</a>';
    						}

							 if(empty($campaign_details)){
                  echo 'Not connected';
               }	
								
							} else{
                echo 'Not connected';
              }
							               
							
						?>
					</td>
			
				</tr>
					
				<?php  endforeach; ?>
			</tbody>
		</table>
			
	</div>

	
	<input type="hidden" name="task" id="task" value="" />
	<input type="hidden" id="email_id" name="jform[id]" value="" />
</form>
<!-- End history email form -->
	
<!-- Start history mail layout for preview -->	
<?php foreach ($this->item as $i => $g) :?>
<div style="display:none;">
	<div id="historymail_<?php echo $g->id;?>" >
    <style>
   .weeklyupdatetable p {
        font-size: 1.5em;
        margin: 0 0 15px 10px;
        padding: 0;
    }
  </style>
	<div style="margin-bottom:10px;" >
	<a href="javascript:void(0);" onclick="printpage(<?php echo $g->id;?>)" >Print this page</a> 
	<a href="javascript:void(0);" onclick="printpage(<?php echo $g->id;?>)" >
		<span class="icon-32-print"> </span>
	</a> 
	
	</div>
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
	
	<div id="subscriber_<?php echo $g->id;?>">
	<?php 
		if($api_type == 'M'){
									
			$email_ids = array();
			$email_ids = explode(',',$g->email_id);
			$list = '';
			$members = '';
			$k = 1;
			foreach($email_ids as $ei){
				$members = $api->campaignMembers($ei);
				foreach($members['data'] as $m){
					echo $k.'. '.$m['email']."<br>";
					$k++;
				}
			} 
	
			}else if($api_type == 'C'){
			
				echo $displaylist[$g->email_id];
								
			}
	?>
	</div>
</div>
<?php  endforeach; ?>
<!-- Start history mail layout for preview -->
	
<div id="print_main_div" style="display:none;">
	<div id="print_div">
	</div>
</div>

<?php 
$app->setUserState("com_enewsletter.data",'');
$app->setUserState("com_enewsletter.gid",'');
$app->setUserState("com_enewsletter.email",'');
$app->setUserState("com_enewsletter.cid",'');
?>