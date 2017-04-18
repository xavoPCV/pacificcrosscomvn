<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Get api details
$app = JFactory::getApplication();
$api=$app->getUserState("com_enewsletter.API");

// include css and js files
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/bootstrap.min.css");
//$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/strapper.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/smoothness/theme.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/akeebaui.css");

?>
 
<div class="akeeba-bootstrap">
	<div id="cpanel" class="row-fluid">

		<div>
			<div class="icon">
				<a href="index.php?option=com_enewsletter&view=advisorsetting">
					<div class="ak-icon ak-icon-nsetup">&nbsp;</div>
					<span>Set Up</span>
				</a>
			</div>
			
			<div class="icon">
				<a href="index.php?option=com_enewsletter&view=buildnewsletter">
					<div class="ak-icon ak-icon-nbuildnewsletter">&nbsp;</div>
					<span>E-Newsletter</span>       
				</a>
			</div>
			
			<div class="icon">
				<a href="index.php?option=com_enewsletter&view=massemail">
					<div class="ak-icon ak-icon-nmassemail">&nbsp;</div>
					<span>Mass Email</span>
				</a>
			</div>
			
			<div class="icon">
				<a href="index.php?option=com_enewsletter&view=weeklyupdate">
					<div class="ak-icon ak-icon-nweeklyupdate">&nbsp;</div>
					<span>Weekly Update</span>
				</a>
			</div>			
		
		</div>
		
		
		<div style="clear:both;">
		
			<div class="icon">
				<a href="index.php?option=com_enewsletter&view=savedemail">
					<div class="ak-icon ak-icon-nsavedemail">&nbsp;</div>
					<span>Saved Email List</span>
				</a>
			</div>				
			
			<div class="icon">
				<a href="index.php?option=com_enewsletter&view=history">
					<div class="ak-icon ak-icon-nhistory">&nbsp;</div>
					<span>History</span>
				</a>
			</div>
			
			<div class="icon">
				<?php if($api == 'C'){?>
				<a href="https://ui.constantcontact.com/rnavmap/evaluate.rnav/?activepage=report.ecampaigns" target="_blank">
				<div class="ak-icon ak-icon-nstatistics">&nbsp;</div>
					<span>Statistics</span>
				</a>
				<?php }else if($api == 'M'){ ?>
				<a href="https://us7.admin.mailchimp.com/reports" target="_blank" >
				<div class="ak-icon ak-icon-nstatistics">&nbsp;</div>
					<span>Statistics</span>
				</a>
				<?php }else{ ?>
				<a href="javascript:void(0);" target="_blank" >
				<div class="ak-icon ak-icon-nstatistics">&nbsp;</div>
					<span>Statistics</span>
				</a>
				<?php } ?>
					
			</div>
      
      <?php if($app->getUserState("com_enewsletter.usertype") == 'admin'){ ?>
      <div class="icon">
				<a href="index.php?option=com_enewsletter&view=managetemplate">
					<div class="ak-icon ak-icon-nmanagetemplate">&nbsp;</div>
					<span>Manage Templates</span>
				</a>
			</div>
      <?php }?>
		
		</div>

	</div>
</div>
