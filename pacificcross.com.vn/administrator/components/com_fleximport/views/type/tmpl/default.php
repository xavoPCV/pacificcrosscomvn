<?php
/**
 * @version 2.0
 * @package Joomla
 * @subpackage flexIMPORT
 * @copyright (C) 2011 NetAssoPro - www.netassopro.com
 * @license GNU/GPL v2
 *
 * flexIMPORT is a addon for the excellent FLEXIcontent component. Some part of
 * code is directly inspired.
 * @copyright (C) 2009 Emmanuel Danan
 * see www.vistamedia.fr for more information
 *
 * flexIMPORT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
// No direct access
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'type.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>
<form action="<?= JRoute::_('index.php?option=com_fleximport&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform form-horizontal">
			<legend><?= JText::_( 'COM_FLEXISTORE_TYPE_DETAIL' ); ?></legend>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('details') as $field): ?>
                <li><?= $field->label.$field->input;?></li>
			<?php endforeach; ?>
			<?php if ($this->item->cronLinkExport && $this->item->cronLinkImport) : ?>
			<li><label>Cron Import</label><input type="text" readonly="readonly" value="<?= $this->item->cronLinkImport;?>" size="80" /></li>
			<li><label>Cron Export</label><input type="text" readonly="readonly" value="<?= $this->item->cronLinkExport;?>" size="80" /></li>
			<?php endif; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-40 fltrt">
	<?php  $fieldSets = $this->form->getFieldsets('params'); ?>
	<?php  $fieldSetPlugin = $this->pluginForm->getFieldset('fleximport'); ?>
	<?php if ($fieldSets || $fieldSetPlugin) : ?>
	<?= JHtml::_('sliders.start', 'sliders-type', array('useCookie'=>1)); ?>
	<?php foreach($fieldSets as $nameFs => $fieldSet): ?>
		<?= JHtml::_('sliders.panel', JText::_($fieldSet->label), $nameFs); ?>
		<fieldset class="panelform">
			<?php if (isset($fieldSet->description) && trim($fieldSet->description)) : ?>
				<p class="tip"><?= $this->escape(JText::_($fieldSet->description));?></p>
			<?php endif; ?>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset($nameFs) as $field): ?>
            <li>
               <?php if (!$field->hidden): ?>
                  <?= $field->label; ?>
               <?php endif; ?>
               <?= $field->input; ?>
            </li>
			<?php endforeach; ?>
			</ul>
		</fieldset>
		<?php endforeach; ?>

	<?php if ($fieldSetPlugin) : ?>
		<?= JHtml::_('sliders.panel', JText::_("COM_FLEXIMPORT_PLUGIN_PARAMS"), 'fiplugins'); ?>
		<fieldset class="panelform">
			<?php if (isset($fieldSetPlugin->description) && trim($fieldSetPlugin->description)) : ?>
				<p class="tip"><?= $this->escape(JText::_($fieldSetPlugin->description));?></p>
			<?php endif; ?>
			<ul class="adminformlist">
			<?php foreach($fieldSetPlugin as $field): ?>
            <li>
               <?php if (!$field->hidden): ?>
                  <?= $field->label; ?>
               <?php endif; ?>
               <?= $field->input; ?>
            </li>
		<?php endforeach; ?>
		</ul>
		</fieldset>
		<?php endif; ?>
		<?= JHtml::_('sliders.end'); ?>
	<?php endif; ?>
    </div>
	<div class="clr"></div>
	<div>
		<input type="hidden" name="task" value="" />
		<?= JHtml::_('form.token'); ?>
	</div>
</form>