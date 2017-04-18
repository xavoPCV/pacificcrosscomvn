<?php
/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$document = & JFactory::getDocument();
$base_url = JURI::base();
$document->addStyleSheet($base_url . 'components/com_gnosis/css/gnosis.css');
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_gnosis');
$saveOrder = $listOrder == 'a.ordering';

if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_gnosis&task=words.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'wordList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>

<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        } else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<div class="page-header">
    <h2>
        <?php echo $this->escape($this->params->get('page_heading'));

        ?>
    </h2>
</div>
<?php if (JFactory::getUser()->authorise('core.create', 'com_gnosis')): ?>
    <div class="gnosis_add"><a
        href="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.edit&id=0'); ?>"><?php echo JText::_("COM_GNOSIS_ADD_ITEM"); ?></a>
    </div>
<?php endif; ?>
<form action="<?php echo JRoute::_('index.php?option=com_gnosis&view=words'); ?>" method="post" name="adminForm"
      id="adminForm">
    <table class="gnosis_list">
        <tbody>
        <tr>
            <th><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_WORD', 'a.word', $listDirn, $listOrder); ?></th>
            <th><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_CATEGORY', 'a.category', $listDirn, $listOrder); ?></th>
            <th><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_DEFINITION', 'a.definition', $listDirn, $listOrder); ?></th>
        </tr>

        <?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>


            <?php
            if ($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own', ' com_gnosis'))):
                $show = true;
                ?>
                <tr class="gnosis_list_item">
                    <td class="gnosis_list_item_link"><a
                            href="<?php echo JRoute::_('index.php?option=com_gnosis&view=word&id=' . (int)$item->id); ?>"><?php echo $item->word; ?></a>
                    </td>
                    <td class="gnosis_list_category"><?php echo $item->category; ?></td>
                    <td class="gnosis_list_definition"><?php echo substr(strip_tags($item->definition), 0, 150); ?>...
                    </td>


                </tr>
            <?php endif; ?>

        <?php endforeach; ?>
        <?php
        if (!$show):
            echo JText::_('COM_GNOSIS_NO_ITEMS');
        endif;
        ?>

        </tbody>
    </table>
</form>



<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>
