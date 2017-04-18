<?php
/**

 * $Id: default.php 11917 2009-05-29 19:37:05Z ian $

 */
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');
$doc->addScript($this->baseurl.'/components/com_cta/assets/function.js');
$doc->addScript('http://www.google.com/recaptcha/api/js/recaptcha_ajax.js');


if ($this->params->get('use_captcha')) {

	$plugin = &JPluginHelper::getPlugin('captcha', 'recaptcha');
	$pluginParams = new JRegistry($plugin->params);
	
	$pubkey		= $pluginParams->get('public_key', '');
	$theme		= $pluginParams->get('theme', 'clean');
	//clean-white-blackglass-red
	
	$doc->addScriptDeclaration('window.addEvent(\'domready\', function() {
				Recaptcha.create("'.$pubkey.'", "dynamic_recaptcha_1", {theme: "'.$theme.'",lang : \'en\',tabindex: 0});});'
	);

}//if
?>
<div class="com_cta">
<?php if ($this->params->get('show_page_heading')!=0):?>
	<h2 class="page_heading"><?php echo $this->params->get('page_heading')?$this->params->get('page_heading'):$this->params->get('page_title');?></h2>
<?php else:?>
	<h2 class="page_heading">View Report<?php echo (count($this->videos)>1 || count($this->cusitem_id_arr)>1)?'s':'';?></h2>
<?php endif;?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	
	//alert(task);
	
	if (document.formvalidator.isValid(document.id('adminForm'))) {
		//custom check
		var form = document.getElementById('adminForm');
		var formValid = true;
		
		<?php if ($this->setting->used_enewsletter && $this->setting->mandatory_enewsletter):?>
		formValid = checkbox_check(form, 'used_enewsletter');
		if (!formValid) {
			alert('E-Newsletter connect is mandatory!');
		}
		<?php endif;?>
		
		<?php if ($this->videos ):?>
		if (formValid) {
			formValid = checkbox_check(form, 'video_id[]');
			if (!formValid) {
				alert('Please select at least ONE Report!');
			}//if
		}//if
		<?php endif;?>
		
		if (formValid) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}//if
	} else {
		<?php #echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>
		alert('Please enter First Name, Last Name and Email!');
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
			if (found > 5) {
				e.stop();
			}//if
		}//if			
	});
	
	
	
});
</script>
<form action="<?php #echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="report_form form-validate" <?php if (JRequest::getVar('tmpl','')):?>target="_parent"<?php endif;?>>
	<!--<p class="clr">Connect</label></p>-->
	
	<div class="<?php if (!$this->doMod) :?><?php echo $this->setting->social_text_position=='right'?'social_text_right':'social_text_left';?><?php endif;?>">
		
		<?php if ($this->setting->social_text):?>
		<div class="social_text"><?php echo $this->setting->social_text;?></div>
		<?php endif;?>
		<dl>
			<!--
			<dt>First Name<span class="star">*</span></dt>
			<dd><input type="text" name="first_name" id="first_name" class="" value="" /></dd>
			<dt>Last Name<span class="star">*</span></dt>
			<dd><input type="text" name="last_name" id="last_name" class="" value="" /></dd>
			-->
			<dt>Email<span class="star">*</span></dt>
			<dd><input type="email" name="email" id="email" class="required validate-email" placeholder="Email" value="" /></dd>
		</dl>
		<div class="clr"></div>
		<?php
		if ($this->doMod) {
		?>
		<?php
			if (is_array($this->videos)):
				foreach ($this->videos as $video_row):?>
				<input type="checkbox" name="video_id[]" checked="checked" id="video_id<?php echo $video_row['VideoId'];?>" class="video_id_lst" value="<?php echo $video_row['VideoId'].'|'.$this->escape($video_row['Title']);?>" style="display:none;" />
			<?php 
				endforeach;
			endif;
		?>
		<?php 
			if (is_array($this->cusitem_id_arr)):
				foreach ($this->cusitem_id_arr as $cusitem_id):?>
				<input type="checkbox" name="cusitem_id[]" checked="checked" id="cusitem_id<?php echo $cusitem_id;?>" class="cusitem_id_lst" value="<?php echo $cusitem_id;?>" style="display:none;" />
			<?php 
				endforeach;
			endif;
		?>
		<?php
		} else {
		?>
		<p class="clr">Select up to 5 reports<span class="star">*</span></p>
		<ul>
			<?php if (strpos($_SERVER['SERVER_NAME'], 'localhost')!==false):?>
			<li><input type="checkbox" name="video_id[]" id="video_id1" class="video_id_lst" value="1|test 1" checked="checked" /> <label for="video_id1">test 1</label></li>
			<?php endif;?>
			
			<?php 
			if (is_array($this->videos)):
				foreach ($this->videos as $video_row):?>
			<li><input type="checkbox" name="video_id[]" id="video_id<?php echo $video_row['VideoId'];?>" class="video_id_lst" value="<?php echo $video_row['VideoId'].'|'.$this->escape($video_row['Title']);?>" /> <label for="video_id<?php echo $video_row['VideoId'];?>"><?php echo $video_row['Title'];?></label></li>
			<?php 
				endforeach;
			endif;
		?>
		
			<?php 
			if (is_array($this->cusitem_id_arr)):
				foreach ($this->cusitem_id_arr as $cusitem_id):?>
				<li><input type="checkbox" name="cusitem_id[]" checked="checked" id="cusitem_id<?php echo $cusitem_id;?>" class="cusitem_id_lst" value="<?php echo $cusitem_id;?>" /></li>
			<?php 
				endforeach;
			endif;
		?>
		
		</ul>
		<?php
		}//if
		?>
		
		<?php if($this->setting->used_enewsletter):?>
		<p style="display:none;"><input type="checkbox" name="used_enewsletter" id="used_enewsletter" checked="checked" <?php echo ($this->setting->mandatory_enewsletter?'class="readonly" onclick="return false;"':NULL);?> value="1" /> <label for="used_enewsletter" class="enewsletter_logo">
				<?php if ($this->setting->enewsletter_logo):?>
				<img src="<?php echo $this->baseurl.'/components/com_cta/enewsletter/'.$this->setting->enewsletter_logo;?>" border="0" />
				<?php else :?>
				Please sign me up for your email newsletter.<?php if($this->setting->mandatory_enewsletter):?>*<?php endif;?>
				<?php endif;?>
				</label>
		</p>
		<?php endif;?>
		
		<?php if ($this->params->get('use_captcha')):?>
		<p>Captcha-Please input the text you see in the image<span class="star">*</span></p>
		<?php endif;?>
		<ul>
			<?php if ($this->params->get('use_captcha')):?>
			<li><div id="dynamic_recaptcha_1"></div></li>
			<?php endif;?>
			<li style="margin-top:10px; text-align:center;"><button type="button" onclick="Joomla.submitbutton('form.submit')">Submit</button></li>
		</ul>
	</div><!--social_text_left-->
	
	
	<?php if (!$this->doMod) :?>
	<div class="<?php echo $this->setting->social_text?($this->setting->social_text_position=='right'?'social_links_left':'social_links_right'):NULL;?>">
		<p>Be sure to connect with us on social media:</p>
		<ul>
			<li class="social_links">
				<?php if($this->setting->used_facebook && $this->setting->link_facebook):?>	
				<a class="used_facebook<?php echo $this->setting->social_icon_size;?>" target="_blank" href="<?php echo $this->setting->link_facebook;?>" title="Facebook"></a>
				<?php endif;?>
				<?php if($this->setting->used_linkedin && $this->setting->link_linkedin):?>
				<a class="used_linkedin<?php echo $this->setting->social_icon_size;?>" target="_blank" href="<?php echo $this->setting->link_linkedin;?>" title="Linkedin"></a>
				<?php endif;?>
				<?php if($this->setting->used_twitter && $this->setting->link_twitter):?>
				<a class="used_twitter<?php echo $this->setting->social_icon_size;?>" target="_blank" href="<?php echo $this->setting->link_twitter;?>" title="Twitter"></a>	
				<?php endif;?>
				<div class="clr"></div>
			</li>
		</ul>
	</div><!--social_links_left-->
	<?php endif;?>
	<div class="clr"></div>
	<!--<p>Please fill in your information to receive our periodic research reports:</p>-->
	
	<input type="hidden" name="tmpl" value="<?php //echo JRequest::getVar('tmpl','');?>" />
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_cta" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div><!--com_cta-->