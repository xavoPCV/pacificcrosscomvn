<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class CtaModelLeads extends JModelList {

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			
			$config['filter_fields'] = array(
				'a.id'
				, 'a.date_created'
				, 'a.first_name'
				, 'a.email'
				, 'a.last_name'
			);
		}

		parent::__construct($config);
	}
	
	protected function getListQuery()
	{
		// Initialise variables.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				"a.*"
			)
		);
		$query->from($db->quoteName('#__cta_register').' AS a');
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.first_name LIKE '.$search.' OR a.last_name LIKE '.$search.' OR a.email LIKE '.$search.')');
			}
		}//if
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.id');
		$orderDirn	= $this->state->get('list.direction', 'DESC');

		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		#echo nl2br(str_replace('#__','jos_', $query));
		#exit;
		
		return $query;
	}
	
	protected function populateState($ordering = null, $direction = null) {

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		// List state information.
		parent::populateState('a.id', 'DESC');
	}
}