<?php
defined('_JEXEC') or die;

// include mailchimp library file 
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey


$app = JFactory::getApplication();
$APIKEY = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");

// Get all lists of mailchimp
$api = new MCAPI(trim($ACCESS_TOKEN));
$groups = $api->lists();
$groups = $groups['data'];


JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.formvalidation');
?>

<style>
.fltrt{float:right;}
</style>

<script>

function savedata(){
	if(document.formvalidator.isValid(document.id('user-form'))){
  
  flag = 0;
   var checkboxes = document.getElementsByName('cid[]');

    for (var i=0; i<checkboxes.length; i++) {
         // And stick the checked ones onto an array...
         if (checkboxes[i].checked) {
            flag = 1;
         }
    }
		
		if(flag == 0){
			alert('Please select at least one.');
			return false;
		}
    
    Joomla.submitform('subscription.save', document.getElementById('user-form'));

	}
}

</script>

<div class="well" style="margin-left:30px;">
<?php echo $this->data->join_list_instruction;?>
<form action="" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data" style="width:340px;" >
        <div class="control-group" style="margin-top:10px;">
			<label title="" id="jform_email-lbl" for="jform_email" class="required">Email <span class="star"> *</span></label>
			<input class="validate-email inputbox required fltrt" type="email" size="30" value="" id="jform_email" name="jform[email]" placeholder="Email">
        </div>
		
        <div class="control-group" style="margin-top:10px;">
            <label style="width:200px;">First Name</label>
            <input type="text" name="jform[fname]" size="30"  class="fltrt" placeholder="First Name" />
        </div>
		
        <div class="control-group" style="margin-top:10px;">
            <label>Last Name</label>
            <input type="text" id="last_name" name="jform[lname]" size="30" class="fltrt" placeholder="Last Name">
        </div>
        <div class="control-group" style="margin-top:10px;">
            <label class="control-label" style="font-weight:bold;" for="list">List :</label>
            <div class="controls" style="margin-top:10px;">
                    <?php
                     foreach ($groups as $i => $group){ 
                        if($group['stats']['member_count'] != 0){
                          echo '<input type="checkbox" class="checkalls" name="cid[]" value="' . $group['id'] . '" />'.$group['name'].'<br>';
                        }
                      }
                     
                    ?>
            </div>
        </div>
        <div class="control-group" style="margin-top:20px;">
            <label class="control-label">
                <div class="controls">
                    <input type="button" onclick="savedata();" value="SUBMIT" class="btn btn-primary"/>
                </div>
				<input type="hidden" name="jeff" value="jeff" id="hiddend"  />
        </div>
		<input type="hidden" name="task" value="" />
    </form>
</div>


