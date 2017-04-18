<?php

/**
 * @version     1.0.0
 * @package     com_faq
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sang <thanhsang52@gmail.com> - http://
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Faq records.
 */
class FaqModelFaqs extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {

        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        //Filtering category
    $tfilter_category =   $app->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '', 'string');
     if ( ! is_numeric($tfilter_category)){   
         $tfilter_category =  0;
     }
		$this->setState('filter.category',$tfilter_category);
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
		if(empty($ordering)) {
			$ordering = 'a.ordering';
		}

        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
		$catid = JRequest::getInt('catid');
        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );

        $query->from('`#__faq_faqs` AS a');

       
		
	

		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		// Join over the category 'faq_category_id'
		$query->select('faq_category_id.title AS faq_category_id_title');
		$query->join('LEFT', '#__categories AS faq_category_id ON faq_category_id.id = a.faq_category_id ');
        

        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.faq_question LIKE '.$search.' )');
            }
        }
		
		
		 $lang = JFactory::getLanguage();
		if ($lang->getTag() == 'vi-VN' ) {
				$query->where(' faq_category_id.language = "'.$lang->getTag().'" ') ;
		
		}else {
		
			$query->where(' faq_category_id.language <> "vi-VN" ') ;
		}
	
		
		//Filtering category
		$filter_category = $this->state->get("filter.category");
		if ($filter_category) {
			$query->where("a.faq_category_id = '".$filter_category."'");
		}
		if($catid)
			$query->where('faq_category_id='.$catid);
        
		//echo $query->dump();

        return $query;
    }

    public function getItems() {
        return parent::getItems();
    }

}
