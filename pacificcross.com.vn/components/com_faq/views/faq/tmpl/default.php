<?php
/**
 * @version     1.0.0
 * @package     com_faq
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sang <thanhsang52@gmail.com> - http://
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_faq', JPATH_ADMINISTRATOR);

?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_CATEGORY_ID'); ?>:
			<?php echo $this->item->faq_category_id_title; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_QUESTION'); ?>:
			<?php echo $this->item->faq_question; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_ANSWER'); ?>:
			<?php echo $this->item->faq_answer; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_UPVOTES'); ?>:
			<?php echo $this->item->faq_upvotes; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_DOWNVOTES'); ?>:
			<?php echo $this->item->faq_downvotes; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_LEARNINGCENTER'); ?>:
			<?php echo $this->item->faq_learningcenter; ?></li>
			<li><?php echo JText::_('COM_FAQ_FORM_LBL_FAQ_FAQ_DATE'); ?>:
			<?php echo $this->item->faq_date; ?></li>


        </ul>

    </div>
    
<?php
else:
    echo JText::_('COM_FAQ_ITEM_NOT_LOADED');
endif;
?>
