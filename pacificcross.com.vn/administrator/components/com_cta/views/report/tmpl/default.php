<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (document.formvalidator.isValid(document.id('weblink-form'))) {
		//custom check
		var form = document.id('weblink-form');
		if (form.auto_num.value > 15 || form.auto_num.value < 1 ) {
			alert('Automatic display maximun is 15');
		} else {
			Joomla.submitform(task, document.getElementById('weblink-form'));
		}//if
	} else {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
	}
}

window.addEvent('domready', function(){
	
	$$('.video_id_lst').addEvent('click', function(e) {
		
		if (this.checked) {
			//alert($$('.video_id_lst').length);
			var found = 0;
			$$('.video_id_lst').each(function(elem, i){
				if (elem.checked) found++;
			});
			if (found > 15) {
				e.stop();
			}//if
		}//if			
	});
	
	
	
});
</script>
<form action="<?php echo JRoute::_('index.php?option=com_cta&view=report&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="weblink-form" class="form-validate">
	<div class="width-70 fltlft">
		<div class="adminform">
			<ul class="adminformlist">
				<li><input type="radio" name="is_auto" value="1" <?php echo $this->item->is_auto?'checked="checked"':NULL;?> id="is_auto1" /> <label for="is_auto1">Automatic display the</label> <input type="text" name="auto_num" value="<?php echo $this->item->auto_num;?>" class="required validate-numeric" maxlength="2" size="5" /><label> most recent research reports on my website.</label>
				</li>
				<li>OR</li>
				<li><input type="radio" name="is_auto" value="0" <?php echo !$this->item->is_auto?'checked="checked"':NULL;?> id="is_auto0" /> <label for="is_auto0">Select which report to display</label></li>
				
				<li>
					<ul>
						<?php foreach ($this->videos as $video_row):?>
						<li><input type="checkbox" name="video_id[]" class="video_id_lst" value="<?php echo $video_row['VideoId'];?>" id="video_id<?php echo $video_row['VideoId'];?>" <?php echo in_array($video_row['VideoId'], $this->item->selected_video)?'checked="checked"':NULL;?> /> <label for="1video_id<?php echo $video_row['VideoId'];?>"><a href="<?php echo JRoute::_('index.php?option=com_cta&view=report&layout=video&vidfile='.$video_row['VideoFile'].'&vidimg='.$video_row['ImgCTA']);?>" class="modal" rel="{handler: 'iframe', size: {x:820, y:400}}"><?php echo $video_row['Title'];?></a></label></li>
						<?php endforeach;?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<div class="width-30 fltrt">
		<div class="adminform">
			<ul class="adminformlist">
			
				<li>Send to Email: <input type="text" name="send_to_email" size="40" value="<?php echo $this->params->get('send_to_email');?>" class="required validate-email" /></li>
			</ul>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>