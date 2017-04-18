<?php
/**
 * @version 1.1
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
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * flexIMPORT Component Import Model
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportModelImport extends JModelLegacy {
    /**
     * Type data
     *
     * @var object
     */
    var $_import = null;

    /**
     * Constructor
     *
     * @since 1.0
     */
    function __construct()
    {
        parent::__construct();


    }

    /**
     * Method to store the type
     *
     * @access public
     * @return boolean True on success
     * @since 1.0
     */
    function import($data)
    {

        return true;
    }

}