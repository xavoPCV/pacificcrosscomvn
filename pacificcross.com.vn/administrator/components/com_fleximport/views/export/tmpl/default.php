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
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.modal');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="width-30 fltlft">
	<?= JHtml::_('sliders.start', 'sliders-export', array('useCookie'=>0)); ?>
	<?= JHtml::_('sliders.panel', JText::_('COM_FLEXIMPORT_PARAMS_EXPORT'),'params'); ?>
	<fieldset class="adminform">
		<ul class="adminformlist">
		<?php foreach($this->form->getFieldset('params') as $field): ?>
               <li><?= $field->label. $field->input;?></li>
		<?php endforeach; ?>
		</ul>
	</fieldset>
	<?= JHtml::_('sliders.panel', JText::_('COM_FLEXIMPORT_FILTER_STANDARD'),'filters'); ?>
	<fieldset class="adminform">
		<ul class="adminformlist">
		<?php foreach($this->form->getFieldset('filters') as $field): ?>
               <li><?= $field->label. $field->input;?></li>
		<?php endforeach; ?>
		</ul>
	</fieldset>
	<?= JHtml::_('sliders.end'); ?>
</div>
<div class="width-70 fltlft">
	<div id="result_export">
		<?= $this->loadTemplate('list');?>
	</div>
</div>
<input type="hidden" name="option" value="com_fleximport" />
<input type="hidden" name="view" value="export" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="" />
<?= JHtml::_( 'form.token' ); ?>
</form>


