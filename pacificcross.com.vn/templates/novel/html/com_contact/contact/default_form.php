<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

if (isset($this->error)) : ?>
	<div class="contact-error">
		<?php echo $this->error; ?>
	</div>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php'); ?>" method="post" class="form-validate pure-form pure-form-stacked contact-form">
    <div class="pure-g">
        <fieldset>
            <legend><?php echo JText::_('COM_CONTACT_FORM_LABEL'); ?></legend>
            <div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-left">
                <?php echo $this->form->getLabel('contact_email'); ?>
                <input type="email" name="jform[contact_email]" class="pure-u-1 validate-email required invalid" id="jform_contact_email" value="" required="required" aria-required="true" aria-invalid="true">
            </div>
            <div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-right">
                <?php echo $this->form->getLabel('contact_name'); ?>
                <input type="text" name="jform[contact_name]" id="jform_contact_name" value="" class="pure-u-1 required" required="required" aria-required="true">
            </div>
            <div class="pure-u-1">
                <?php echo $this->form->getLabel('contact_subject'); ?>
                <input type="text" name="jform[contact_subject]" id="jform_contact_emailmsg" value="" class="pure-u-1 required" required="required" aria-required="true">
            </div>
            <div class="pure-u-1">
                <?php echo $this->form->getLabel('contact_message'); ?>
                <textarea name="jform[contact_message]" id="jform_contact_message" cols="50" rows="10" class="pure-u-1 required" required="required" aria-required="true"></textarea>
            </div>
            <div class="pure-u-1">
                <label for="remember" class="pure-checkbox">
                    <input type="checkbox" name="jform[contact_email_copy]" id="jform_contact_email_copy" value="1">&nbsp; <?php echo JText::_('COM_CONTACT_CONTACT_EMAIL_A_COPY_LABEL'); ?>
                </label>
            </div>
            <div class="text-center" id="recaptcha">
                <?php foreach ($this->form->getFieldsets() as $fieldset) : ?>
                    <?php if ($fieldset->name != 'contact') : ?>
                        <?php $fields = $this->form->getFieldset($fieldset->name); ?>
                        <?php foreach ($fields as $field) : ?>
                            <div>
                                <?php if ($field->hidden) : ?>
                                    <?php echo $field->input; ?>
                                <?php else: ?>
                                    <?php echo $field->input; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <br/>
                <button type="submit" class="pure-button pure-button-primary validate"><?php echo JText::_('COM_CONTACT_CONTACT_SEND'); ?></button>
            </div>
            <input type="hidden" name="option" value="com_contact" />
                <input type="hidden" name="task" value="contact.submit" />
                <input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
                <input type="hidden" name="id" value="<?php echo $this->contact->slug; ?>" />
                <?php echo JHtml::_('form.token'); ?>
        </fieldset>
    </div><!-- end of pure-g -->
</form>
