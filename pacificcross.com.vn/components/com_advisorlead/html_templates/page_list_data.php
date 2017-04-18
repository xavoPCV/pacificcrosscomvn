<?php
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

$user = JFactory::getUser();
$integrations = AdvisorleadHelper::get_integrations($user->id);
$email_services_options = AdvisorleadHelper::get_option('email_services');
$email_services = json_decode($email_services_options);
$api_key = $email_services->cc_app_key;
$canEmail = false;
if ($integrations['constantcontact']['active'] && $integrations['constantcontact']['access_token'] ) {
	$canEmail = true;
}//if
?>

<table id="page-table" class="table pages-table table-bordered table-hover">
    <?php
    
    foreach ($pages as $page) {
        //$publish_url = JURI::base() . "$page->article_id-$page->slug";
	//	
		
		$publish_url = JURI::base() . "$page->slug";
		
        $edit_url = ADVISORLEAD_URL . "/pages/$page->template_id/$page->id";
        $delete_url = ADVISORLEAD_URL . "/pages/$page->id/delete/";
        $screenshot = ASSETS_URL . "/inc/page-templates/$page->template_slug/screenshot.png";
        $href = $page->custom_domain;
        $href = str_replace('http://', '', $href);
        $href = str_replace('https://', '', $href);
        $href = str_replace('/', '', $href);
        ?>
        <tr>
            <td>
                <a   target="_blank" class="page-name page" href="<?php echo $edit_url ?>" style="background-image: url('<?php echo $screenshot ?>');"><?php echo $page->name ?></a> 
                <?php if($href !=''){ ?>
                <div style="    position: absolute;      top: 120px;    left: 166px;    font-size: 20px;">Domain: <a  href="<?php echo 'http://'.$href ?>" target="_blank" ><?php echo 'http://'.$href; ?></a></div>
                <?php } ?> 
                <div class="edit-options-analytics">
                    
					<a class="publish-custom-btn" href="<?php echo $publish_url ?>" target="_blank"><i class="fa fa-eye"></i> View Page</a>
                    <a class="edit-custom-btn" href="<?php echo $edit_url ?>"><i class="fa fa-edit"></i> Edit Page</a>
                    <a class="options-btn" href="<?php echo $delete_url ?>" onclick="MP.deletePage(this); return false;"><i class="fa fa-trash-o"></i> Delete Page</a>
					<br/>
					<?php if ($canEmail):?>
					<a href="#" class="export-custom-btn asendemnail" rel="<?php echo $page->id;?>">Email</a>
                    <?php endif;?>
					<!-- <a href="<?php echo JURI::base();?>index.php?option=com_advisorlead&task=export&id=<?php echo $page->id ?>" target="_blank" class="export-custom-btn">Export</a> -->
					
					
					<a href="#" class="export-custom-btn add-to-facebook-button" rel="<?php echo $page->id;?>">Facebook</a>
					
					<div class="page-inline-controls pull-left table-controls table-controls-custom">
                        <?php if ($page->optins > 0) { ?>
                            <div class="black-i" title="Conversion Rate">
                                <a href="#"><small><?php echo $page->rates ?>%</small></a>
                                <div>Conversion Rate</div>
                            </div>
                            <div class="black-i" title="Optins">
                                <a href="#"><small><?php echo $page->optins ?></small></a>
                                <div>Opt-ins</div>
                            </div>
                        <?php } ?>
                        <div class="black-i" title="Unique Viewers">
                            <div>Uniques -  <a href="#"><small><?php echo $page->uniques ?></small></a></div>
                        </div>
                    </div>

                </div>
                <label class="checkbox selected_page_lbl">
                    <input type="checkbox" class="selected_page_chbox" value="<?php echo $page->id ?>">
                </label>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<script>
jQuery(document).ready(function($) {


	var pageid = 0;
	var app_id = '<?php echo APP_ID;?>';

	addPageTabCallback = function(response) {
		
		var tabs_added = Object.keys(response.tabs_added).join(','), query, link;
		if (tabs_added) {
			query = 'tabs_added[' + tabs_added + ']'
			query = encodeURIComponent(query) + '=1';
			url = '<?php echo JURI::root(false);?>index.php?option=com_advisorlead&task=pages.addfppage&app_id='+app_id+'&page_id='+pageid+'&'+query;
			
			
			console.log(url);
			
			try {
				parent.window.location.href = url;
			} catch (e) {
				window.location.href = url;
			}
		}//if
	};

	$('.add-to-facebook-button').click(function(e) {
	
		e.preventDefault();
		
		
		pageid = $(this).attr('rel');
		
		
		
		FB.ui(
			{method: 'pagetab'},
			addPageTabCallback
		);
		return false;
	});
});
</script>
<?php if ($canEmail):?>
<div style="display:none;">
	<div id="previewdiv">
	<form action="<?php echo htmlspecialchars( JFactory::getURI()->toString() );?>" method="post" class="theemailform" id="theemailform">.
<?php
		
		$access_token = $integrations['constantcontact']['access_token'];
		$cc = new ConstantContact($api_key);
		$lists = array();
		try {
			$lists = $cc->getLists($access_token);
		} catch (CtctException $ex) {
			
		}//try
			
		if (!empty($lists)) {
		
		
			echo "<h3>From Name:</h3>";
			
			echo "<ul>";
			echo "<li><input name='from_name' value='' /></li>";
			echo "</ul>";
			
		
			echo "<h3>From Email:</h3>";
		
			$verifiedEmailAddresses = $cc->getVerifiedEmailAddresses($access_token);
			echo "<ul>";
			
			echo "<li><select name='from_email_address'>";
			
			foreach($verifiedEmailAddresses as $verifiedEmailAddress){
				echo "<option value=\"$verifiedEmailAddress->email_address\">$verifiedEmailAddress->email_address</option>";
			}//for
			echo "</select></li></ul>";
			
			
			echo "<h3>Email List:</h3>";
			
			echo "<ul>";
			
			foreach ($lists as $list) {
				echo "<li><input type='checkbox' name='cclist[]' value='$list->id' /> <label>$list->name ($list->contact_count)</label></li>";
			}//for
			echo "</ul>";
		}//if
		?>
		<button type="button" name="Send" value="Send" id="btnSendEmail">Send</button> <button type="button" name="Close" value="Close" id="btnCloseEmail" onclick="javascript:parent.jQuery.fancybox.close();">Close</button>
		<input type="hidden" name="pageid" value="" />
	</form>
	</div>
</div>
<script>
jQuery(document).ready(function($) {

	$('a.asendemnail').on('click', function(e) {
		e.preventDefault();
		$('input[name="pageid"]').val($(this).attr('rel'));
		
		$.fancybox.open({
			href : '#previewdiv',
			type : 'inline',
			padding : 20,
			width: 800,
			height: 600,
			autoSize: false,
			minHeight: 600,
			modal : true,
			closeBtn: false
		});
		
	});
	
	
	
	
	$('#btnSendEmail').on('click', function(e) {
		e.preventDefault();
		
		var atLeastOneIsChecked = $('input[name="cclist[]"]:checked').length > 0;
		
		
		
		if ( !parseInt($('input[name="pageid"]').val()) ) {
			alert('Please select a page!');
			return false;
		}//if
		
		if ( !$('input[name="from_name"]').val() ) {
			alert('Please specify from name!');
			return false;
		}//if
		
		if (!atLeastOneIsChecked) {
			alert('Please choice at least one list!');
			return false;
		}//if
		
		
		
		//ajax
		var ajax_url = API_URL + '&request=send_email';
		
		
		var data_save = $( "#theemailform" ).serialize();
		
		
		//console.log(data_save);
		//return;
		
		$(this).prop('disabled', true);
		$('#btnCloseEmail').prop('disabled', true);
		
		$.ajax({
				url: ajax_url,
				type: "POST",
				data: data_save,
				dataType: 'json',
				success: function(data) {
					console.log(data);
					if (parseInt(data.status)) {
						alert('Sent successful!');
					} else {
						alert('Sent fail: ' + data.msg);
					}//if
					
					$('#btnSendEmail').prop('disabled', false);
					$('#btnCloseEmail').prop('disabled', false);
					
				}//success
		});
		
		
	});
});
</script>
<?php endif;?>