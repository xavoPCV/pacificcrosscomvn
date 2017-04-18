<?php
// No direct access.
defined('_JEXEC') or die;

class ctaTableCusitem extends JTable {

	function __construct(&$_db) {
		parent::__construct('#__cta_cusitems', 'id', $_db);
	}
	
	

}