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

/**
 * flexIMPORT table class
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FlexImportTableTypes extends JTable {
    function __construct(&$db)
    {
        parent::__construct('#__fleximport_types', 'id', $db);
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
    // overloaded check function
    function check()
    {
        // Not typed in a name?
        if (trim($this->name) == '') {
        	throw new Exception(JText::_('COM_FLEXIMPORT_ADD_NAME'), 400);
            return false;
        }

        /**
         * * check for existing name
         */
        $query = 'SELECT id'
         . ' FROM #__fleximport_types'
         . ' WHERE name = ' . $this->_db->Quote($this->name) ;
        $this->_db->setQuery($query);

        $xid = intval($this->_db->loadResult());
        if ($xid && $xid != intval($this->id)) {
        	throw new Exception(JText::sprintf('COM_FLEXIMPORT_THIS_FIELD_NAME_ALREADY_EXIST', $this->name), 400);
            return false;
        }

        return true;
    }
}