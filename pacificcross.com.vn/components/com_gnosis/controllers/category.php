<?php
/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Words list controller class.
 */
class GnosisControllerCategory extends GnosisController
{
    /**
     * Proxy for getModel.
     * @since    1.6
     */
    public function &getModel($name = 'Category', $prefix = 'GnosisModel')
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }
}