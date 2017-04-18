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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_gnosis', JPATH_ADMINISTRATOR);
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_gnosis.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_gnosis' . $this->item->id)) {
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_ID'); ?>:
                <?php echo $this->item->id; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_ORDERING'); ?>:
                <?php echo $this->item->ordering; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_STATE'); ?>:
                <?php echo $this->item->state; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_WORD'); ?>:
                <?php echo $this->item->word; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_PRONOUNCIATION'); ?>:
                <?php echo $this->item->pronounciation; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_CATEGORY'); ?>:
                <?php
                if ($this->item->category != ''):
                    $array = array();
                    foreach ((array)$this->item->category as $value):
                        if (!is_array($value)):
                            $array[] = $value;
                        endif;
                    endforeach;
                    $data = array();
                    foreach ($array as $value):
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query
                            ->select('category_name')
                            ->from('`#__gnosis_category`')
                            ->where('id = ' . $value);
                        $db->setQuery($query);
                        $results = $db->loadObjectList();
                        if ($results) {
                            $data[] = $results[0]->category_name;
                        }
                    endforeach;
                    $this->item->category = implode(',', $data);
                endif; ?>            <?php echo $this->item->category; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_DEFINITION'); ?>:
                <?php echo $this->item->definition; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_EXAMPLES'); ?>:
                <?php echo $this->item->examples; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_ETYMOLOGY'); ?>:
                <?php echo $this->item->etymology; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_QUIZ'); ?>:
                <?php echo $this->item->quiz; ?></li>
            <li><?php echo JText::_('COM_GNOSIS_FORM_LBL_WORD_CREATED_BY'); ?>:
                <?php echo $this->item->created_by; ?></li>


        </ul>

    </div>
    <?php if ($canEdit): ?>
        <a href="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.edit&id=' . $this->item->id); ?>"><?php echo JText::_("COM_GNOSIS_EDIT_ITEM"); ?></a>
    <?php endif; ?>
    <?php if (JFactory::getUser()->authorise('core.delete', 'com_gnosis.word.' . $this->item->id)):
        ?>
        <a href="javascript:document.getElementById('form-word-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_GNOSIS_DELETE_ITEM"); ?></a>
        <form id="form-word-delete-<?php echo $this->item->id; ?>" style="display:inline"
              action="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.remove'); ?>" method="post"
              class="form-validate" enctype="multipart/form-data">
            <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
            <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>
            <input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>"/>
            <input type="hidden" name="jform[word]" value="<?php echo $this->item->word; ?>"/>
            <input type="hidden" name="jform[pronounciation]" value="<?php echo $this->item->pronounciation; ?>"/>
            <input type="hidden" name="jform[category]" value="<?php echo $this->item->category; ?>"/>
            <input type="hidden" name="jform[definition]" value="<?php echo $this->item->definition; ?>"/>
            <input type="hidden" name="jform[examples]" value="<?php echo $this->item->examples; ?>"/>
            <input type="hidden" name="jform[etymology]" value="<?php echo $this->item->etymology; ?>"/>
            <input type="hidden" name="jform[quiz]" value="<?php echo $this->item->quiz; ?>"/>
            <input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>"/>
            <input type="hidden" name="option" value="com_gnosis"/>
            <input type="hidden" name="task" value="word.remove"/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    <?php
    endif;
    ?>
<?php else:
    echo JText::_('COM_GNOSIS_ITEM_NOT_LOADED');
endif;
?>
