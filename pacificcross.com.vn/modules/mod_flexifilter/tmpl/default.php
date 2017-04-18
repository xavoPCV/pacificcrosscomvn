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



// use css class fc_nnnnn_clear to override wrapping
?>
<script>
	
	
	jQuery( document ).ready(function($) {
  
	$("#moduleFCform_<?php echo $module->id ?>_261_val").append($("#moduleFCform_<?php echo $module->id ?>_261_val option").remove().sort(function(a, b) {
		var at = $(a).text(), bt = $(b).text();
		if(at == '- Country -' || bt == '- Country -' ){
		return (at > bt)?1:((at < bt)?-1:0);
		}else{
		return (at < bt)?1:((at > bt)?-1:0);
		}
	}));
		
	$("#moduleFCform_104_261_val").change( function () {   
	
		

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

<?php include(JPATH_SITE.'/components/com_flexicontent/tmpl_common/filters.php'); ?>

	<span class="fc_filter accs" style="display:none;    float: right;    margin-top: -38px;" >
				<span class="fc_buttons">
					<button class="<?php echo $flexi_button_class_go; ?>" onclick="var form=document.getElementById('<?php echo $form_id; ?>'); adminFormPrepare(form, 2); return false;">
						<span title="<?php echo JText::_( 'FLEXI_APPLY_FILTERING' ); ?>"><?php echo JText::_( 'FLEXI_GO' ); ?></span>
					</button>
					
					<?php if ($show_search_reset && !$buttons_added_already) : ?>
					<button class="<?php echo $flexi_button_class_reset; ?>" onclick="var form=document.getElementById('<?php echo $form_id; ?>'); adminFormClearFilters(form); adminFormPrepare(form, 2); return false;">
						<span title="<?php echo JText::_( 'FLEXI_REMOVE_FILTERING' ); ?>"><?php echo JText::_( 'FLEXI_RESET' ); ?></span>
					</button>
					<?php endif; ?>
					
				</span>
				<span id="<?php echo $form_id; ?>_submitWarn" class="fc-mssg fc-note" style="display:none;"><?php echo JText::_('FLEXI_FILTERS_CHANGED_CLICK_TO_SUBMIT'); ?></span>
			</span>

</form>

<?php
// FORM in slider
if ($ff_placement) echo JHtml::_('sliders.end');
?>

</div>

</div> <!-- mod_flexifilter_wrap -->
