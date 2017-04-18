<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// Add scripts and css
// include css and js files
$document = JFactory::getDocument();
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-1.9.1.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-ui.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery.dataTables.js");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/jquery-ui.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/demo_page.css");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/demo_table.css");

$app = JFactory::getApplication();
$templates = $app->getUserState("com_enewsletter.template_array");

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
?>
<link rel="stylesheet" href="<?php echo JURI::root();?>media/system/css/modal.css" type="text/css" />
<script src="<?php echo JURI::root();?>media/system/js/modal-uncompressed.js" type="text/javascript"></script>

<!-- Assigns height and width of popup window -->
<style>
.setupbtn {background:#e6e6e6;}
.setupbtn:hover{cursor : pointer;}

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
		
		// Assigns datatable to saved email list
		$('#emailtemplates').dataTable( {
			"aLengthMenu": [[50, 100, 200, -1], [50, 100, 200, "All"]],
      'iDisplayLength': 50,
       "aoColumnDefs": [
          { "bSortable": false, "aTargets": [ 0,4 ] }
        ]    
		} );
		
	} );

</script>

<script type="text/javascript">
// Initialize squeezebox 
window.addEvent('domready', function() {

 SqueezeBox.initialize();
 
 SqueezeBox.assign($$('a[rel=boxed][href^=#]'), {
  size: {x: 700, y: 500}
 });
 
});

// Used to remove template
function removetemplate(id){
    var confirmBox = confirm("ARE YOU SURE?");
  	if(!confirmBox){
  		return false;
  	}
    $('#template_id').val(id);
    Joomla.submitbutton('managetemplate.remove');
}
</script>


<script type="text/javascript">

	// Perform submit action 
	Joomla.submitbutton = function(task)
	{
		if (task == 'managetemplate.cancel' || document.formvalidator.isValid(document.id('user-form'))) {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<div class="clr"> </div>
<!-- Start saved email form -->
<form action="" method="post" name="adminForm" id="user-form"  enctype="multipart/form-data">

	<div class="width-100">
    <a href="index.php?option=com_enewsletter&view=addtemplate" ><input type="button"  class="bfont setupbtn" value="Add New Template"  /></a>
     <br><br>
		<table class="adminlist" id="emailtemplates">
			<thead>
				<tr>
					<th width="1%">
						<input type="checkbox" name="checkallgroup" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="mailsCheckAll(this);" />
					</th>
					<th width="40%" class="left">
						<?php echo  'Description'; ?>
					</th>
          
          <th class="left" width="20%">
						<?php echo 'File Name'; ?>
					</th>
					
					<th class="left"width="10%">
						<?php echo 'Type'; ?>
					</th>
					
					<th class="left" width="10%">
						<?php echo 'Actions'; ?>
					</th>

				</tr>
			</thead>
      
			<tbody>
      <?php foreach($this->templatedata as $e) {?>
          <tr>
            <td class="center">
							<input class="checkallemail" id="email_<?php echo $e->id;?>" type="checkbox"  value="<?php echo $e->id;?>" name="email_id[]" onclick="Joomla.isChecked(this.checked);" /> 
					 </td>
            <td >
              <?php echo $e->description;?>
            </td>
            <td >
              <?php echo $e->filename;?>
            </td>
            <td >
              <?php echo $e->type;?>
            </td>
            <td >
              <a href="index.php?option=com_enewsletter&view=addtemplate&id=<?php echo $e->id;?>" >Edit </a>|
              <?php if(!in_array($e->id,$templates)){?>
              <a href="javascript:void(0)" onclick="removetemplate(<?php echo $e->id;?>)" >Remove </a>|
              <?php }?>
              <a href="#mail_<?php echo $e->id;?>" rel="boxed" >Preview </a>
            </td>
          </tr>
      <?php }?>
			</tbody>
		</table>
			
	</div>
	
	<div>
	<input type="hidden" name="stask" id="stask" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="template_id" name="jform[id]" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<!-- End saved email form --> 

<?php foreach ($this->templatedata as $e){?>
	<div style="display:none;" >
		<div id="mail_<?php echo $e->id;?>" >
			<?php 
				 
				 
				 
			if ($e->filename == 'massemail.html' || $e->filename == 'weeklyupdate.html') 	 {
			$maildata = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$e->filename);
                        }else {
                            $tenfile =   explode('.html', $e->filename);
                            $e->filename = $tenfile[0].'_defaults.html';
                            $maildata = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$e->filename);
                        }
		
		
        	$dta_tmp = array();
		 
			$maildata = enewsletterHelper::replaceTemplateCode($e->type, $dta_tmp, NULL, $maildata, true);
		
		

        	$dom = new SmartDOMDocument();
      		$dom->loadHTML($maildata); 
		

		
        
        	$as = $dom->getElementsByTagName("a");
    		foreach($as as $a){
				$a->setAttribute( 'href' , '#' );
    		}
			
			$maildata = $dom->saveHTML();
			
			echo $maildata;
			?>
		</div>
	</div>
<?php }?>


