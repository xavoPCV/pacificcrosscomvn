<?php
/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Word controller class.
 */
class GnosisControllerCategory extends JControllerForm
{

    function __construct()
    {
        $this->view_list = 'categories';
        parent::__construct();
    }

}