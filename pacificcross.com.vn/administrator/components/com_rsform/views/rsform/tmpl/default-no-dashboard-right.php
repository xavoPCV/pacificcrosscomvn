<?php
/**
* @package RSForm! Pro
* @copyright (C) 2007-2014 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<div id="dashboard-left" style="width: 100%">
			<?php echo $this->loadTemplate('buttons'); ?>
		</div>
	</div>
	
	<input type="hidden" name="option" value="com_rsform" />
	<input type="hidden" name="task" value="" />
</form>