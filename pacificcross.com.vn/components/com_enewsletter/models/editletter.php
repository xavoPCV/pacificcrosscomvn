<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );


class EnewsletterModeleditletter extends JModel
{
    
        public function getListcta()
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
		$query->where('a.published = 1 ');
		$selected_item_a = $this->getState('filter.selected_item_a');
		if (is_array($selected_item_a)) {
			$query->where('(a.id IN ('.implode(',',$selected_item_a).'))');
		}//if
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'a.id');
		$orderDirn	= $this->state->get('list.direction', 'DESC');

		
		$query->order($db->escape($orderCol.' '.$orderDirn));
               $db->setQuery($query);
	
		#exit;
		
		return $db->loadAssocList();
                
                
                
	}

}
