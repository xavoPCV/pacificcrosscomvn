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

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_faq/assets/css/faq.css');
?>
<script type="text/javascript">
    function getScript(url,success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
        done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
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
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            

            Joomla.submitbutton = function(task)
            {
                if (task == 'faq.cancel') {
                    Joomla.submitform(task, document.getElementById('faq-form'));
                }
                else{
                    
                    if (task != 'faq.cancel' && document.formvalidator.isValid(document.id('faq-form'))) {
                        
                        Joomla.submitform(task, document.getElementById('faq-form'));
                    }
                    else {
                        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
                    }
                }
            }
        });
    });
</script>

<form action="<?php echo JRoute::_('index.php?option=com_faq&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="faq-form" class="form-validate">
    <div class="width-60 fltlft">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_FAQ_LEGEND_FAQ'); ?></legend>
            <ul class="adminformlist">

                				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				<li><?php echo $this->form->getLabel('created_by'); ?>
				<?php echo $this->form->getInput('created_by'); ?></li>
				<li><?php echo $this->form->getLabel('faq_category_id'); ?>
				<?php echo $this->form->getInput('faq_category_id'); ?></li>
				<li><?php echo $this->form->getLabel('faq_question'); ?>
				<?php echo $this->form->getInput('faq_question'); ?></li>
				<li><?php echo $this->form->getLabel('faq_answer'); ?>
				<?php echo $this->form->getInput('faq_answer'); ?></li>
				<li><?php echo $this->form->getLabel('faq_upvotes'); ?>
				<?php echo $this->form->getInput('faq_upvotes'); ?></li>
				<li><?php echo $this->form->getLabel('faq_downvotes'); ?>
				<?php echo $this->form->getInput('faq_downvotes'); ?></li>
				<li><?php echo $this->form->getLabel('faq_learningcenter'); ?>
				<?php echo $this->form->getInput('faq_learningcenter'); ?></li>
				<li><?php echo $this->form->getLabel('faq_date'); ?>
				<?php echo $this->form->getInput('faq_date'); ?></li>


            </ul>
        </fieldset>
    </div>

    

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
    <div class="clr"></div>

    <style type="text/css">
        /* Temporary fix for drifting editor fields */
        .adminformlist li {
            clear: both;
        }
    </style>
</form>