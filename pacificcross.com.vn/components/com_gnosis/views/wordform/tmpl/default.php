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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_gnosis', JPATH_ADMINISTRATOR);
?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
    .front-end-edit ul {
        padding: 0 !important;
    }

    .front-end-edit li {
        list-style: none;
        margin-bottom: 6px !important;
    }

    .front-end-edit label {
        margin-right: 10px;
        display: block;
        float: left;
        width: 200px !important;
    }

    .front-end-edit .radio label {
        float: none;
    }

    .front-end-edit .readonly {
        border: none !important;
        color: #666;
    }

    .front-end-edit #editor-xtd-buttons {
        height: 50px;
        width: 600px;
        float: left;
    }

    .front-end-edit .toggle-editor {
        height: 50px;
        width: 120px;
        float: right;
    }

    #jform_rules-lbl {
        display: none;
    }

    #access-rules a:hover {
        background: #f5f5f5 url('../images/slider_minus.png') right top no-repeat;
        color: #444;
    }

    fieldset.radio label {
        width: 50px !important;
    }
</style>
<script type="text/javascript">
    function getScript(url, success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
            done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function () {
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', function () {
        js = jQuery.noConflict();
        js(document).ready(function () {
            js('#form-word').submit(function (event) {

            });


            js('input:hidden.category').each(function () {
                var name = js(this).attr('name');
                if (name.indexOf('categoryhidden')) {
                    js('#jform_category option[value="' + js(this).val() + '"]').attr('selected', true);
                }
            });
            js("#jform_category").trigger("liszt:updated");
        });
    });

</script>

<div class="word-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h2>Edit <?php echo $this->item->id; ?></h2>
    <?php else: ?>
        <h2>Add New Word</h2>
    <?php endif; ?>

    <form id="form-word" action="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.save'); ?>" method="post"
          class="form-validate" enctype="multipart/form-data">
        <ul>
            <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
            <input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>"/>

            <div class="control-group">
                <?php $canState = false; ?>
                <?php if ($this->item->id): ?>
                    <?php $canState = $canState = JFactory::getUser()->authorise('core.edit.state', 'com_gnosis.word'); ?>
                <?php else: ?>
                    <?php $canState = JFactory::getUser()->authorise('core.edit.state', 'com_gnosis.word.' . $this->item->id); ?>
                <?php endif; ?>                <?php if (!$canState): ?>
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <?php
                    $state_string = 'Unpublish';
                    $state_value = 0;
                    if ($this->item->state == 1):
                        $state_string = 'Publish';
                        $state_value = 1;
                    endif;
                    ?>
                    <div class="controls"><?php echo $state_string; ?></div>
                    <input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>"/>
                <?php else: ?>
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div
                        class="controls"><?php echo $this->form->getInput('state'); ?></div>                    <?php endif; ?>
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
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tags'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('tags'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('source'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('source'); ?></div>
			</div>

            <?php
            foreach ((array)$this->item->category as $value):
                if (!is_array($value)):
                    echo '<input type="hidden" name="jform[categoryhidden][' . $value . ']" value="' . $value . '" />';
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
			<div class="clr"></div>

        </ul>

        <div>
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.cancel'); ?>"
               title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_gnosis"/>
            <input type="hidden" name="task" value="wordform.save"/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
