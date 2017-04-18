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
JHtml::_('behavior.keepalive');
?>
<div class="width-50 fltlft">
	<form action="index.php" method="post" class="form-validate" name="adminForm" id="adminForm" enctype="multipart/form-data" >
	<fieldset class="adminform">
		<legend><?= JText::_( 'COM_FLEXIMPORT_IMPORT_OPTIONS' ); ?></legend>
		<ul class="adminformlist">
		<li>
			<label for="import_method">
			<?= JText::_( 'COM_FLEXIMPORT_IMPORT_METHOD' ).' :'; ?>
			</label>
			<?= $this->form['import_method']; ?>
		</li>
		<li>
			<label for="type_id">
				<?= JText::_( 'COM_FLEXIMPORT_FIELD_TYPE' ).' *: '; ?>
			</label>
			<div id="import_options">
			<?= $this->form['type_id']; ?>
			</div>
		</li>
		<li id="file_zone"></li>
		</ul>
	</fieldset>
	<?= JHtml::_( 'form.token' ); ?>
	</form>
</div>
<div class="width-50 fltrt">

</div>
<div class="clear"></div>
<div id="import_return" class="width-30 fltlft "><fieldset class="adminform"><legend><?= JText::_('COM_FLEXIMPORT_AJAX_RETURN'); ?></legend></fieldset></div>
<div id="import_errors" class="width-30 fltlft"><fieldset class="adminform"><legend><?= JText::_('COM_FLEXIMPORT_AJAX_MESSAGES'); ?></legend></fieldset></div>
<div id="import_progress" class="width-30 fltlft"></div>
<div class="clear"></div>