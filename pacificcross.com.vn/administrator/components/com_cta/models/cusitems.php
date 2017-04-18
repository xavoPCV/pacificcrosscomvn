<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class CtaModelcusitems extends JModelList {

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			
			$config['filter_fields'] = array(
				'a.id'
				, 'a.date_created'
				, 'a.title'
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
		$query->from($db->quoteName('#__cta_cusitems').' AS a');
		
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.title LIKE '.$search.' OR a.file_name LIKE '.$search.')');
			}
		}//if
		
		$selected_item_a = $this->getState('filter.selected_item_a');
		if (is_array($selected_item_a)) {
			$query->where('(a.id IN ('.implode(',',$selected_item_a).'))');
		}//if
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.id');
		$orderDirn	= $this->state->get('list.direction', 'DESC');

		
		$query->order($db->escape($orderCol.' '.$orderDirn));

		#echo $query->dump();
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