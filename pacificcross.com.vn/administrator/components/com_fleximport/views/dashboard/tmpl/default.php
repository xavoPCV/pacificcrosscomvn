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

defined( '_JEXEC' ) or die;
JHtml::_('behavior.tooltip');
$html = JHtml::_('icons.buttons', $this->icons);
?>
<div class="width-60 fltlft">
<?php if (!empty($html)): ?>
<div id="cpanel"><?= $html;?></div>
<?php endif;?>
</div>
<div class="width-40 fltrt">
	<div class="credits">
		<?= JHtml::_('image', 'media/com_fleximport/images/com_fleximport.png', 'flexIMPORT' ); ?>
		<?= JText::sprintf('COM_FLEXIMPORT_LEGALS',FLEXIMPORT_VERSION,JUri::root(true));?>
	</div>
</div>