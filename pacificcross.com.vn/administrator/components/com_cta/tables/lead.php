<?php
// No direct access.
defined('_JEXEC') or die;

class CtaTableLead extends JTable {

	function __construct(&$_db) {
		parent::__construct('#__cta_register', 'id', $_db);
	}

}