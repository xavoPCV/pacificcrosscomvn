<?php
/**
 * @version     1.0.7a
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

// Import CSS
$document = JFactory::getDocument();
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
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    </h2>
</div>
<?php if (JFactory::getUser()->authorise('core.create', 'com_gnosis')): ?>
    <div class="gnosis_add"><a
        href="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.edit&id=0'); ?>"><?php echo JText::_("COM_GNOSIS_ADD_ITEM"); ?></a>
    </div>
<?php endif; ?>
<div class="clearfix"></div>
<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_gnosis&view=words'); ?>" method="post" name="adminForm"
      id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>

            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search"
                           class="element-invisible"><?php echo JText::_('COM_GNOSIS_FILTER'); ?></label>
                    <input type="text" name="filter_search" id="filter_search"
                           placeholder="<?php echo JText::_('COM_GNOSIS_FILTER'); ?>"
                           value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                           title="<?php echo JText::_('COM_GNOSIS_FILTER'); ?>"/>
                </div>
                <div class="btn-group pull-left">
                    <button class="btn hasTooltip" type="submit"
                            title="<?php echo JText::_('COM_GNOSIS_FILTER_SUBMIT'); ?>"><i class="icon-search"></i>
                    </button>
                    <button class="btn hasTooltip" type="button"
                            title="<?php echo JText::_('COM_GNOSIS_FILTER_CLEAR'); ?>"
                            onclick="document.id('filter_search').value='';this.form.submit();"><i
                            class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit"
                           class="element-invisible"><?php echo JText::_('COM_GNOSIS_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->getLimitBox(); ?>
                </div>

            </div>
            <div class="clearfix"></div>
            <table class="table" id="wordList">
                <thead>
                <tr>


                    <th class='left gnosis-list-word'>
                        <?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_WORD', 'a.word', $listDirn, $listOrder); ?>
                    </th>

                    <?php if ($this->params->get('glshowpronounciation') == 1) : ?>
                        <th class='left gnosis-list-pron'><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_PRONOUNCIATION', 'a.pronounciation', $listDirn, $listOrder); ?></th>
                    <?php endif; ?>

                    <?php if ($this->params->get('glshowcategory') == 1) : ?>
                        <th class='left gnosis-list-cate'><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_CATEGORY', 'a.category', $listDirn, $listOrder); ?></th>
                    <?php endif; ?>

                    <?php if ($this->params->get('glshowdefinition') == 1) : ?>
                        <th class='left gnosis-list-defi'><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_DEFINITION', 'a.definition', $listDirn, $listOrder); ?></th>
                    <?php endif; ?>

                    <?php if ($this->params->get('glshowauthor') == 1) : ?>
                        <th class='left gnosis-list-auth'><?php echo JHtml::_('grid.sort', 'COM_GNOSIS_WORDS_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?></th>
                    <?php endif; ?>


                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->items as $i => $item) :
                    $ordering = ($listOrder == 'a.ordering');
                    $canCreate = $user->authorise('core.create', 'com_gnosis');
                    $canEdit = $user->authorise('core.edit', 'com_gnosis');
                    $canCheckin = $user->authorise('core.manage', 'com_gnosis');
                    $canChange = $user->authorise('core.edit.state', 'com_gnosis');
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">


                        <td>


                            <a href="<?php echo JRoute::_('index.php?option=com_gnosis&view=word&id=' . (int)$item->id); ?>"><?php echo $item->word; ?></a>

                        </td>

                        <?php if ($this->params->get('glshowpronounciation') == 1): ?>
                            <td><?php echo $item->pronounciation; ?></td>
                        <?php endif; ?>

                        <?php if ($this->params->get('glshowcategory') == 1) : ?>
                            <td>
                                <?php
                                if ($item->category != ''):
                                    $data = array();
                                    foreach (explode(',', $item->category) as $value):
                                        $db = JFactory::getDbo();
                                        $query = $db->getQuery(true);
                                        $query
                                            ->select('category_name')
                                            ->from('`#__gnosis_category`')
                                            ->where('id = ' . $value);
                                        $db->setQuery($query);
                                        $results = $db->loadObjectList();
                                        if (count($results)) {
                                            $data[] = $results[0]->category_name;
                                        }
                                    endforeach;
                                    $item->category = implode(',', $data);

                                endif; ?>
                                <?php //echo $item->category; ?>


                                <a href="<?php echo JRoute::_('index.php?option=com_gnosis&view=category&id=' . $value); ?>"><?php echo $item->category; ?></a>
                            </td>
                        <?php endif; ?>

                        <?php if ($this->params->get('glshowdefinition') == 1) : ?>
                            <?php $definitionstriped = substr(strip_tags($item->definition), 0, $this->params->get('glwordsdefsize')); ?>
                            <?php if ($this->params->get('glcontentplugins') == 1) {
                                $definitionprepare = JHtml::_('content.prepare', $definitionstriped);
                            } else {
                                $definitionprepare = $definitionstriped;
                            }

                            ?>
                            <td><?php echo $definitionprepare; ?>...</td>
                        <?php endif; ?>

                        <?php if ($this->params->get('glshowauthor') == 1) : ?>
                            <td><?php echo $item->created_by; ?></td>
                        <?php endif; ?>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
<div class="pagination">
    <p class="counter">
        <?php echo $this->pagination->getPagesCounter(); ?>
    </p>
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
		
