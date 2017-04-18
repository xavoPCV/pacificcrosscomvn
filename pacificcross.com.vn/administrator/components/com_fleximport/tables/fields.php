<?php
/**
 *
 * @version 2.0
 * @package Joomla
 * @subpackage flexIMPORT
 * @copyright (C) 2011 NetAssoPro - www.netassopro.com
 * @license GNU/GPL v2
 *
 * flexIMPORT is a addon for the excellent FLEXIcontent component. Some part of
 * code is directly inspired.
 * @copyright (C) 2009 Emmanuel Danan
 * see www.vistamedia.fr for more information
 *
 * flexIMPORT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die;

class FlexImportTableFields extends JTable {
    function __construct(&$db)
    {
        parent::__construct('#__fleximport_fields', 'id', $db);
    }
    public function bind($array, $ignore = '')
    {
        if (isset($array['params']) && is_array($array['params'])) {
            // Convert the params field to a string.
            $parameter = new JRegistry;
            $parameter->loadArray($array['params']);
            $array['params'] = (string)$parameter;
        }
        return parent::bind($array, $ignore);
    }
    function check()
    {
        // Not typed in a label?
        if (trim($this->label) == '') {
        	throw new Exception(JText::_('FLEXI_ADD_LABEL'), 400);
            return false;
        }

        $newname = str_replace('-', '', JFilterOutput::stringURLSafe($this->label));

        if (empty($this->name)) {
            $this->name = $newname;
        }

        /**
         * * check for existing name
         */
        $query = 'SELECT id'
         . ' FROM #__fleximport_fields'
         . ' WHERE name = ' . $this->_db->Quote($this->name) ;
        $this->_db->setQuery($query);

        $xid = intval($this->_db->loadResult());
        if ($xid && $xid != intval($this->id)) {
        	throw new Exception(JText::sprintf('FLEXI_THIS_FIELD_NAME_ALREADY_EXIST', $this->name), 400);
            return false;
        }
    	/* Force la valeur core */
    	$query = $this->_db->getQuery(true);
    	$query->select('f.iscore')->from('#__flexicontent_fields AS f');
    	$query->where('f.id=' . (int)$this->flexi_field_id);
    	$this->_db->setQuery($query);
    	$this->iscore = (bool) $this->_db->loadResult();
    	if (in_array($this->field_type,$GLOBALS['fi_fields_system'])) {
    		$this->iscore = 1;
    	}
    	if (empty($this->ordering))
    		$this->ordering = $this->getNextOrder('type_id='.$this-type_id);
        return true;
    }
}