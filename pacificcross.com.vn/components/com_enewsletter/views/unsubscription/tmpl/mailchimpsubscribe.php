<?php
defined('_JEXEC') or die;

// include mailchimp library file 
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey


$app = JFactory::getApplication();
$APIKEY = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.formvalidation');
?>

<style>
.fltrt{float:right;}
</style>

<script>

function getmlist(){
  email = document.getElementById('jform_email').value;
  if(email != ''){

    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (!filter.test(email)) {
        alert('Please provide a valid email address.');
        document.getElementById('jform_email').value = '';
        document.getElementById('jform_email').focus();
        return false;
    }
    
    url = '<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=unsubscription.getsubscribedlist';
    var xmlhttp;
    if (window.XMLHttpRequest)
      {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
      }
    else
      {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function()
        {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
          {
            document.getElementById("listDiv").innerHTML=xmlhttp.responseText;
            document.getElementById('btnsubmit').disabled = false;
          }
        }
      xmlhttp.open("POST",url,true);
      xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
      xmlhttp.send("email="+email);
  }else{
    alert('Please provide an email address.');
    document.getElementById('jform_email').focus();
    return false;
  }
}

function savedata(){
	if(document.formvalidator.isValid(document.id('user-form'))){

    cflag = 0;
    var collection = document.getElementById("listDiv").getElementsByTagName('INPUT');
    for (var x=0; x<collection.length; x++) {
        if (collection[x].type.toUpperCase()=='CHECKBOX')
            cflag = 1;
    }
    
    if(cflag != 0){
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
        
        Joomla.submitform('unsubscription.unsubscribe', document.getElementById('user-form'));
    }

    
	}
}

</script>

<div class="well" style="margin-left:30px;">
<form action="" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data" style="width:340px;" >

      <div class="control-group" style="margin-top:10px;">
			<label title="" id="jform_email-lbl" for="jform_email" class="required">Email <span class="star"> *</span></label>
			<input class="validate-email inputbox required" type="email" size="30" value="" onblur="getmlist()" id="jform_email" name="jform[email]" placeholder="Email">
        </div>

        <div id="listDiv" style="padding-top:20px;">
        </div>

       <div class="control-group" style="margin-top:20px;">
            <label class="control-label">
                <div class="controls">
                    <input type="button" onclick="savedata();" value="SUBMIT" class="btn btn-primary" id="btnsubmit" disabled />
                </div>
				<input type="hidden" name="jeff" value="jeff" id="hiddend"  />
        </div>
		<input type="hidden" name="task" value="" />
 </form>
</div>


