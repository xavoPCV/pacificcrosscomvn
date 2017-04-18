<?php
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$doc = JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_cta/assets/style.css');

$content_css = ".preview_video img {
				opacity:".($this->item->opacity/100).";
				filter:alpha(opacity=".$this->item->opacity.");
			}";

$doc->addStyleDeclaration($content_css);
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
<form action="<?php echo JRoute::_('index.php?option=com_cta&view=branding&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="weblink-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<div class="adminform">
			<ul class="adminformlist">
				<li><label>Brand your videos by uploading your logo.
				<br/>Dimensions: W:100px x H:100px</label>
				<br/><input type="file" name="watermark_logo" />
				</li>
				<li style="margin-top:20px;"><label>A watermarked version of your logo that is barely visible can be displayed in your video
				<br/>Adjust its opacity here</label> <?php echo JHTML::_('select.integerlist',  10, 100, 10, 'opacity', 'id="opacity"', (int)$this->item->opacity );?>
				</li>
				<?php if ($this->item->watermark_logo):?>
				<li style="margin-top:20px;">
					<div class="preview_video"><img src="<?php echo JURI::root().'components/com_cta/logo/'.$this->item->watermark_logo;?>" border="0" /></div>
				</li>
				<?php endif;?>
				<!--Preview-->
			</ul>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>