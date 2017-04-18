<?php
/**
 * @version     1.0.0
 * @package     com_faq
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sang <thanhsang52@gmail.com> - http://
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Faq controller class.
 */
class FaqControllerFaq extends JControllerForm
{

    function __construct() {
        $this->view_list = 'faqs';
        parent::__construct();
    }

}