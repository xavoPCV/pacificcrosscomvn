<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');


//$doc = JFactory::getDocument();
$this->document->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
?>
<script type="text/javascript">
function isValidURL(url){
	var RegExp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
	if (RegExp.test(url)){
        return true;
    } else {
        return false;
    }
}

window.addEvent('domready', function(){
	document.formvalidator.setHandler('url', function(value) {
		var test = isValidURL(value);
		//if (!test) alert('Invalid URL');
		return test;
	});
});
Joomla.submitbutton = function(task) {
	if (document.formvalidator.isValid(document.id('weblink-form'))) {
		Joomla.submitform(task, document.getElementById('weblink-form'));
	} else {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
	}
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_cta&view=subscription&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="weblink-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-50 fltlft">
		<div class="adminform">
			<ul class="adminformlist">
				<li><label>Require email subscription to access reports</label> <input type="radio" name="mandatory_enewsletter" value="1" <?php echo $this->item->mandatory_enewsletter?'checked="checked"':NULL;?> id="mandatory_enewsletter1" /> yes <input type="radio" name="mandatory_enewsletter" value="0" <?php echo !$this->item->mandatory_enewsletter?'checked="checked"':NULL;?> id="mandatory_enewsletter0" /> no</li>
				
				<li style="padding:10px 0;"><label>Show Icons</label></li>	
				<li>
					<ul>
						<li><input type="radio" name="social_icon_size" value="16" <?php echo $this->item->social_icon_size==16?'checked="checked"':NULL;?> id="social_icon_size16" /> Small(16x16)
							<span class="social_icon16"></span>
						</li>
						<li><input type="radio" name="social_icon_size" value="32" <?php echo $this->item->social_icon_size==32?'checked="checked"':NULL;?> id="social_icon_size32" /> Medium(32x32)
							<span class="social_icon32"></span>
						</li>
						<li><input type="radio" name="social_icon_size" value="64" <?php echo $this->item->social_icon_size==64?'checked="checked"':NULL;?> id="social_icon_size64" /> Large(64x64)
							<span class="social_icon64"></span>
						</li>
					</ul>
				</li>
				<li style="padding:10px 0;"><label>Select connection options to be displayed</label></li>	
				<li>
					<table>
						<tr>
							<td><input type="checkbox" name="used_facebook" value="1" <?php echo $this->item->used_facebook?'checked="checked"':NULL;?> /> Facebook</td>
							<td>URL <input type="text" name="link_facebook" class="validate-url" value="<?php echo $this->item->link_facebook;?>" size="50" /></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="used_linkedin" value="1" <?php echo $this->item->used_linkedin?'checked="checked"':NULL;?> /> LinkedIn</td>
							<td>URL <input type="text" name="link_linkedin" class="" value="<?php echo $this->item->link_linkedin;?>" size="50" /></td>
						</tr>
						<tr>
							<td><input type="checkbox" name="used_twitter" value="1" <?php echo $this->item->used_twitter?'checked="checked"':NULL;?> /> Twitter</td>
							<td>URL <input type="text" name="link_twitter" class="validate-url" value="<?php echo $this->item->link_twitter;?>" size="50" /></td>
						</tr>
						<tr valign="top">
							<td><input type="checkbox" name="used_enewsletter" value="1" <?php echo $this->item->used_enewsletter?'checked="checked"':NULL;?> /> E-Newsletter</td>
							<td><input type="file" name="enewsletter_logo" value="" />
							<?php if ($this->item->enewsletter_logo):
								$logo_text = '<img src="'.JURI::root().'components/com_cta/enewsletter/'.$this->item->enewsletter_logo.'" border="0" width="200" />';
								//echo $logo_text;
							?>
								<?php echo JHTML::tooltip($logo_text, NULL, NULL, 'Current Logo');?>
								<input type="hidden" name="old_enewsletter_logo" value="<?php echo $this->item->enewsletter_logo;?>" />
								<p><input type="checkbox" name="del_enewsletter_logo" value="1" /> <label>Delete E-Newsletter Logo</label></p>
							<?php endif;?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>
							<label><i>Subscribe to:</i></label>
							<ul style="padding:0; margin:0;" id="enewsletter_opt">
								<li><input type="checkbox" name="constantcontact" value="1" <?php echo $this->item->constantcontact?'checked="checked"':NULL;?> > <label>Constant contact</label></li>
								<li>
									<fieldset class="subscribe_field">
										<ul>
											<li>
												<label>Constant email</label> <input type="text" name="constant_contact_email" size="50" value="<?php echo $this->item->constant_contact_email;?>" />
											</li>
											<!--
											<li>
												<label>Constant key <span class="star">*</span></label> <input type="text" name="constant_contact_key" size="50" value="<?php echo $this->item->constant_contact_key;?>" />
											</li>
											<li>
												<label>Constant secret</label> <input type="text" name="constant_contact_secret" size="50" value="<?php echo $this->item->constant_contact_secret;?>" />
											</li>
											-->
											<li>
												<label>Constant token <span class="star">*</span></label> <input type="text" name="constant_contact_token" size="50" value="<?php echo $this->item->constant_contact_token;?>" />
											</li>
										</ul>
									</fieldset>
								</li>
								<li><input type="checkbox" name="mailchimp" value="1" <?php echo $this->item->mailchimp?'checked="checked"':NULL;?> > <label>Mailchimp</label></li>
								<li>
									<fieldset class="subscribe_field">
										<ul>
											<li>
												<label>Mailchimp email</label> <input type="text" name="mailchimp_email" size="50" value="<?php echo $this->item->mailchimp_email;?>" />
											</li>
											<li>
												<label>Mailchimp API key <span class="star">*</span></label> <input type="text" name="mailchimp_api_key" size="50" value="<?php echo $this->item->mailchimp_api_key;?>" />
											</li>
										</ul>
									</fieldset>
								</li>
							</ul>							
							</td>
						</tr>
					</table>
				</li>
			</ul>
		</div>
	</div>
	<div class="width-50 fltrt">
		<div class="adminform">
			<ul class="adminformlist">
				<li>
					<table>
						<tr>
						  <td><label>Text position</label> <input type="radio" name="social_text_position" value="left" <?php echo $this->item->social_text_position=='left'?'checked="checked"':NULL;?>> <label>Left</label> <input type="radio" name="social_text_position" value="right" <?php echo $this->item->social_text_position=='right'?'checked="checked"':NULL;?>> <label>Right</label></td>
					  </tr>
						<tr>
						  <td>
						  <label>Social text</label>
						  <div class="clr"></div>
						  <?php
							$editor = JFactory::getEditor();
							echo $editor->display('social_text',  $this->item->social_text, '100%;', '350', '75', '20', false);
						  ?>
						  </td>
					  </tr>
					</table>
				</li>
			</ul>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
<div class="clr"></div>