<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$db = jfactory::getdbo();
$sql = "select (select a.value from #__flexicontent_fields_item_relations a where c.id  =  a.item_id and a.field_id = 261 ) as coun , ( select b.value from #__flexicontent_fields_item_relations b where c.id  =  b.item_id and b.field_id = 258 ) as city from #__content as c  ";
$db->setquery($sql);
$data =  $db->loadobjectlist();
$temp = '';
foreach ($data as $r) {

	if($r->coun != '' && $r->city != '' ){
		
		$lis[$r->coun][] = $r->city;
	
	}

}
$lis['Thailand'] = array_unique($lis['Thailand']);
$lis['Vietnam'] = array_unique($lis['Vietnam']);


if (strtolower(JFactory::getLanguage()->getTag()) == 'vi-vn' ){
    $directBillingText = 'Tải danh sách các Nhà cung cấp dịch vụ y tế có áp dụng Dịch vụ thanh toán trực tiếp';
	$directBillingLink = 'download.php?file=List of Medical Providers for Direct Billing Services_vn.pdf';
}else {
	$directBillingText = 'Download the list of Medical Providers for Direct Billing Services';
	$directBillingLink = 'download.php?file=List of Medical Providers for Direct Billing Services_en.pdf';
}
// use css class fc_nnnnn_clear to override wrapping
?>
<script>
	
	
	jQuery( document ).ready(function($) {
  

        
	$("#moduleFCform_<?php echo $module->id ?>_261_val").change( function () {   
	
		

		if ($(this).val() == '') {
		      $("#moduleFCform_<?php echo $module->id ?>_258_val").empty();
	          $("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Vietnam'] as $r1 ) { ?>
			
			$("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
			
			<?php foreach ($lis['Thailand'] as $r1 ) { ?>
			
			$("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
		
		
		} else if( $(this).val() == 'Vietnam' ) {
			
			$("#moduleFCform_<?php echo $module->id ?>_258_val").empty();
	          $("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Vietnam'] as $r1 ) { ?>
			
			$("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
		
		} else if( $(this).val() == 'Thailand' ) {
			$("#moduleFCform_<?php echo $module->id ?>_258_val").empty();
	          $("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Thailand'] as $r1 ) { ?>
			
			$("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
		}
	
	});
                     $("#moduleFCform_<?php echo $module->id ?>_261_val").val('Vietnam');
		
		$("#moduleFCform_<?php echo $module->id ?>_258_val").empty();
	          $("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Vietnam'] as $r1 ) { ?>
			
			$("#moduleFCform_<?php echo $module->id ?>_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
 
});
	
</script>

<script>
jQuery( document ).ready(function($) {

    $('.fc_filter_line').hide();
    $(".fc_filter_text_search").show();
    
     $('#namesr').click(function (){
            $(".fc_filter_line").hide();
          $(".fc_filter_text_search").show();
         
       
         
     });
     
      $('#losr').click(function (){
            $(".fc_filter_line").show();
          $(".fc_filter_text_search").hide();
         
     });
    
});
        
        
</script>


 
<div class="mod_flexifilter_wrapper mod_flexifilter_wrap<?php echo $moduleclass_sfx; ?>" id="mod_flexifilter_default<?php echo $module->id ?>">

<?php
// Prepare remaining form parameters
$form_id = $form_name;
$form_method = 'post';   // DO NOT CHANGE THIS

$show_filter_labels = $params->get('show_filter_labels', 1);
$filter_placement = $params->get( 'filter_placement', 1 );
$filter_container_class  = $filter_placement ? 'fc_filter_line' : 'fc_filter';
$filter_container_class .= $filter_placement==2 ? ' fc_clear_label' : '';
$text_search_val = JRequest::getString('filter', '', 'default');

// 4. Create (print) the form
?>

<div class="fcfilter_form_outer fcfilter_form_module">

<?php
// FORM in slider
$ff_placement = $params->get('ff_placement', 0);

if ($ff_placement){
	$ff_slider_id = 
		($module->id     ? '_'.$module->id : '')
		;
	$ff_slider_title = JText::_($params->get('ff_toggle_search_title', 'FLEXI_SEARCH_FORM_TOGGLE'));
	echo JHtml::_('sliders.start', 'fcfilter_form_slider'.$ff_slider_id, array('useCookie'=>1, 'show'=>-1, 'display'=>-1, 'startOffset'=>-1, 'startTransition'=>1));
	echo JHtml::_('sliders.panel', $ff_slider_title, 'fcfilter_form_togglebtn'.$ff_slider_id);
}
?>

<form id='<?php echo $form_id; ?>' action='<?php echo $form_target; ?>' data-fcform_default_action='<?php echo $default_target; ?>' method='<?php echo $form_method; ?>' >
<?php include(JPATH_SITE.'/components/com_flexicontent/tmpl_common/filters.php'); ?>
<?php if ( !empty($cats_select_field) ) : ?>
<fieldset class="fc_filter_set" style="padding-bottom:0px;">
	<span class="<?php echo $filter_container_class. ' fc_odd'; ?>" style="margin-bottom:0px;">
		<span class="fc_filter_label fc_cid_label"><?php echo JText::_($mcats_selection ? 'FLEXI_FILTER_CATEGORIES' : 'FLEXI_FILTER_CATEGORY'); ?></span>
		<span class="fc_filter_html fc_cid_selector"><span class="cid_loading" id="cid_loading_<?php echo $module->id; ?>"></span><?php echo $cats_select_field; ?></span>
	</span>
</fieldset>
<div class="fcclear"></div>
<?php elseif ( !empty($cat_hidden_field) ): ?>
	<?php echo $cat_hidden_field; ?>
<?php endif; ?>


<input type="radio" name="aaaa" id="namesr" checked="checked" style="width:30px;float: left;   " /> <label style="width:200px;float: left;    font-size: 18px;"><?php echo JText::_( 'FLEXI_NAME_SEARCH' ); ?></label><br>
    <input type="radio" name="aaaa" id="losr" style="width:30px;float: left" /> <label style="width:200px;float: left; font-size: 18px;" > <?php echo JText::_( 'FLEXI_LOCATION_SEARCH' ); ?>  </label>
    <br>
    
<span class="fc_filter accs" style="display:none; " >
				<span class="fc_buttons">
					<button class="<?php echo $flexi_button_class_go; ?>" onclick="var form=document.getElementById('<?php echo $form_id; ?>'); adminFormPrepare(form, 2); return false;">
						<span title="<?php echo JText::_( 'FLEXI_APPLY_FILTERING' ); ?>"><?php echo JText::_( 'FLEXI_GO' ); ?></span>
					</button>
					
				
					<button class="<?php echo $flexi_button_class_reset; ?>" onclick="var form=document.getElementById('<?php echo $form_id; ?>'); adminFormClearFilters(form); adminFormPrepare(form, 2); return false;">
						<span title="<?php echo JText::_( 'FLEXI_REMOVE_FILTERING' ); ?>"><?php echo JText::_( 'FLEXI_RESET' ); ?></span>
					</button>
				
					
				</span>
				<span id="<?php echo $form_id; ?>_submitWarn" class="fc-mssg fc-note" style="display:none;"><?php echo JText::_('FLEXI_FILTERS_CHANGED_CLICK_TO_SUBMIT'); ?></span>
			</span>

</form>
<a href="<?php echo $directBillingLink ?>"><button title="<?php echo $directBillingText?>">Download</button></a>
<?php
// FORM in slider
if ($ff_placement) echo JHtml::_('sliders.end');
?>

</div>

</div> <!-- mod_flexifilter_wrap -->
