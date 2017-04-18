<?php
defined('_JEXEC') or die;

jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of plugins
 *
 * @package Joomla.Administrator
 * @subpackage com_plugins
 * @since 1.6
 */
class JFormFieldFIordering extends JFormField {
    /**
     * The form field type.
     *
     * @var string
     * @since 1.6
     */
    protected $type = 'fiordering';

    /**
     * Method to get the field input markup.
     *
     * @return string The field input markup.
     * @since 1.6
     */
    protected function getInput()
    {
        // Initialize variables.
        $html = array();
        $attr = '';
        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
        // Get some field values from the form.
        $table = $this->element['table'];

        $elementId = (int)$this->form->getValue('id');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('ordering AS value, name AS text');
        $query->from('#__fleximport_' . $table);
        $query->order('ordering ASC');
        // Create a read-only list (no name) with a hidden input to store the value.
        if ((string) $this->element['readonly'] == 'true') {
            $html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $elementId?0:1);
            $html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
        }
        // Create a regular list.
        else {
            $html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, $elementId?0:1);
        }

        return implode($html);
    }
}