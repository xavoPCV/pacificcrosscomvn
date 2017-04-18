<?php
// no direct access
defined('_JEXEC') or die;
?>
<div class="<?php echo $module->module;?> <?php echo $moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));?>">
	<a href="#" id="<?php echo $module->module.$module->id;?>"><?php if ($screenshot):?><img src="<?php echo $screenshot;?>" border="0" title="<?php echo $cc_data->name;?>" alt="<?php echo $cc_data->name;?>" /><?php else:?><?php echo $cc_data->name;?><?php endif;?></a>
	<div style="display:none;">
		<?php echo $model->load_cta($page_id);?>
	</div>
	<script>
	window.addEvent('domready', function() {
		$('<?php echo $module->module.$module->id;?>').addEvent( 'click', function(e) {
			e.stop();
			var div = this.getNext('div');
			div.setStyle('display', 'block');
			var capture_clicks_wrap = div.getChildren('div.capture_clicks_wrap')[0];
			//console.log(capture_clicks_wrap);
			capture_clicks_wrap.setStyle('visibility', 'visible');
			capture_clicks_wrap.setStyle('opacity', '1');
		});
	});
	</script>
</div><!--mod_pro_banners-->
