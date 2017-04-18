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

        js('input:hidden.category').each(function () {
            var name = js(this).attr('name');
            if (name.indexOf('categoryhidden')) {
                js('#jform_category option[value="' + js(this).val() + '"]').attr('selected', true);
            }
        });
        js("#jform_category").trigger("liszt:updated");
    });

    Joomla.submitbutton = function (task) {
        if (task == 'word.cancel') {
            Joomla.submitform(task, document.getElementById('word-form'));
        }
        else {

            if (task != 'word.cancel' && document.formvalidator.isValid(document.id('word-form'))) {

                Joomla.submitform(task, document.getElementById('word-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_gnosis&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" enctype="multipart/form-data" name="adminForm" id="word-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
                <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('word'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('word'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('pronounciation'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('pronounciation'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('category'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('category'); ?></div>
                </div>

                <?php
                foreach ((array)$this->item->category as $value):
                    if (!is_array($value)):
                        echo '<input type="hidden" class="category" name="jform[categoryhidden][' . $value . ']" value="' . $value . '" />';
                    endif;
                endforeach;
                ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('definition'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('definition'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('examples'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('examples'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('etymology'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('etymology'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('quiz'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('quiz'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
                </div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('creation_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('creation_date'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('modified_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('modified_date'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tags'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('tags'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('source'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('source'); ?></div>
			</div>

            </fieldset>
        </div>

        <div class="clr"></div>

        <?php if (JFactory::getUser()->authorise('core.admin', 'gnosis')): ?>
            <div class="fltlft" style="width:86%;">
                <fieldset class="panelform">
                    <?php echo JHtml::_('sliders.start', 'permissions-sliders-' . $this->item->id, array('useCookie' => 1)); ?>
                    <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                    <?php echo JHtml::_('sliders.end'); ?>
                </fieldset>
            </div>
        <?php endif; ?>

        <input type="hidden" name="task" value=""/>
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>