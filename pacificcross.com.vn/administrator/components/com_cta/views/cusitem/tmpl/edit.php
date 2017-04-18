<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_banners
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task)
{
	if (task == "cusitem.cancel" || document.formvalidator.isValid(document.getElementById("item-form")))
	{
		Joomla.submitform(task, document.getElementById("item-form"));
	}
};
/*
Joomla.submitbutton = function(task) {

	if ( task == 'cusitem.cancel' ) {
		Joomla.submitform(task);
		return true;
	}
	
	if ( document.formvalidator.isValid(document.id('banner-form'))) {
		<?php if (!$this->item->id):  ?>
		//custom check
		if ( $('jform_file_name').value=='' ) {
			alert('Please specify file!');
			return false;
		} else {
			Joomla.submitform(task);
			return true;
		}//if
		<?php else :?>
		Joomla.submitform(task);
		return true;
		<?php endif;?>
	} else {
		alert('Invalid form!');		
		return false;
	}//if
}
*/
</script>
<form action="<?php echo JRoute::_('index.php?option=com_cta&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('New') : JText::sprintf('Edit', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?></li>
				<li><?php echo $this->form->getLabel('file_name'); ?>
				<?php echo $this->form->getInput('file_name'); ?></li>
			</ul>
		</fieldset>
	</div>
	<?php echo $this->form->getInput('cur_picture_name');?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	<div class="clr"></div>
</form>
