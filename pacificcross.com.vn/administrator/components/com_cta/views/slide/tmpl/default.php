<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (document.formvalidator.isValid(document.id('weblink-form'))) {
		Joomla.submitform(task, document.getElementById('weblink-form'));
	} else {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
	}
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_cta&view=slide&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="weblink-form" class="form-validate">
	<div class="width-100 fltlft">
		<div class="adminform">
			<ul class="adminformlist">
			<li>
				<label>Slides link URL:</label>
				<input type="text" name="cta_url" value="" size="80" placeholder="Default will link to CTA component Form page" />
			</li>
			<?php if ($this->item->is_auto):?>
				<li><label>Number of Calls to Actions to create</label></li>
				<li>
					<ul>
						<li><input type="radio" name="nu_slide" value="1" <?php echo ($this->item->nu_slide==1?'checked="checked"':NULL);?> /> <label>One</label></li>
						<li><input type="radio" name="nu_slide" value="2" <?php echo ($this->item->nu_slide==2?'checked="checked"':NULL);?> /> <label>Two</label></li>
					</ul>
				</li>
				<li style="margin-top:20px;"><button type="button" name="create_cta_slide" onclick="Joomla.submitbutton('slide.save')">Create HTML code</button></li>
				<li>
					<textarea name="cta_html_code" id="cta_html_code" cols="50" rows="10" style="width:900px; height:300px;"><?php echo $this->escape($this->item->slide_code);?></textarea>
				</li>
				<?php if ($this->item->nu_slide==2):?>
				<li>
					<textarea name="cta_html_code2" id="cta_html_code2" cols="50" rows="10" style="width:900px; height:300px;"><?php echo $this->escape($this->item->slide_code2);?></textarea>
				</li>
				<?php endif;?>
			<?php else :?>
				<li><label>Create call-to-action graphics to place on your website</label></li>
				<li><label>Select up to 3 reports to be displayed in call-to-action graphics on your website</label></li>
				<li>
					<table cellpadding="5" cellspacing="5" border="0">
					<tr valign="top">
						<td>
						<select name="video_list[]" id="video_list" multiple="multiple" size="6" style="min-width:300px;">
						<?php foreach ($this->videos as $video_row):?>
							<?php if (!in_array($video_row['VideoId'], $this->item->slide_video)):?>
								<option value="<?php echo $video_row['VideoId'];?>"><?php echo $this->escape($video_row['Title']);?></option>
							<?php endif;?>
						<?php endforeach;?>
						</select>
						</td>
						<td>
							<button type="button" id="but_add">Add</button>
						</td>
						<td>
						<select name="slide_list[]" id="slide_list" multiple="multiple" size="4" style="min-width:300px;">
						<?php foreach ($this->videos as $video_row):?>
							<?php if (in_array($video_row['VideoId'], $this->item->slide_video)):?>
								<option value="<?php echo $video_row['VideoId'];?>"><?php echo $this->escape($video_row['Title']);?></option>
							<?php endif;?>
						<?php endforeach;?>
						</select>
						<br/><button type="button" id="but_del">Remove</button>
						</td>
					</tr>
					</table>
					<script type="text/javascript">
					window.addEvent( 'domready', function() {
					
						function AlwaysOn() {
							//always On
							for (i=0; i < $('slide_list').length; i++) {
								var el = $('slide_list').options[i];
								el.setProperty('selected', true);
							}//for
						}//func
						
						AlwaysOn();
					
						$('but_del').addEvent('click', function() {
							$('slide_list').getSelected().each(function(el) {
								el.inject($('video_list'));
							});
							AlwaysOn();
						});
						$('but_add').addEvent('click', function() {
							var length = $('slide_list').length;
							if (length < 3) {
								$('video_list').getSelected().each(function(el) {
									if (length < 3) {
										el.inject($('slide_list'));
									}//if
									length++;
								});
							} else {
								alert('Select 3 reports only!');
							}//if
							
							AlwaysOn();
						});
						
						
						
					});
					</script>
				</li>
				<li style="margin-top:20px;"><button type="button" name="create_cta_slide" onclick="Joomla.submitbutton('slide.save')">Create HTML code</button></li>
				<li>
					<textarea name="cta_html_code" id="cta_html_code" cols="50" rows="10" style="width:900px; height:300px;"><?php echo $this->escape($this->item->slide_code);?></textarea>
				</li>
			<?php endif;?>
				
				
				
				<li style="margin-top:20px;"><a href="#">Click here for instructions on how to use the code</a></li>
			</ul>
		</div>
	</div>
	<input type="hidden" name="is_auto" value="<?php echo $this->item->is_auto;?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>