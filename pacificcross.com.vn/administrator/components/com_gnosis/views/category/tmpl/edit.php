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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_gnosis/assets/css/gnosis.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function () {

    });

    Joomla.submitbutton = function (task) {
        if (task == 'category.cancel') {
            Joomla.submitform(task, document.getElementById('category-form'));
        }
        else {

            if (task != 'category.cancel' && document.formvalidator.isValid(document.id('category-form'))) {
                Joomla.submitform(task, document.getElementById('category-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gnosis&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" enctype="multipart/form-data" name="adminForm" id="category-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
                <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>
                <input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>"/>
                <input type="hidden" name="jform[checked_out_time]"
                       value="<?php echo $this->item->checked_out_time; ?>"/>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('category_name'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('category_name'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('description'); ?></div>
                </div>


            </fieldset>
        </div>


        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>