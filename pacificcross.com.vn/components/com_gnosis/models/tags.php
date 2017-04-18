<?php

/**
 * @version     1.0.7
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Gnosis records.
 */
class GnosisModelTags extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {

        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'ordering', 'a.ordering',
                'state', 'a.state',
                'word', 'a.word',
                'pronounciation', 'a.pronounciation',
                'category', 'a.category',
                'definition', 'a.definition',
                'examples', 'a.examples',
                'etymology', 'a.etymology',
                'quiz', 'a.quiz',
                'created_by', 'a.created_by',
                'creation_date', 'a.creation_date',
                'modified_date', 'a.modified_date',
                'tags', 'a.tags',
                'source', 'a.source',
            );
        }
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since    1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {

        // Initialise variables.
        $app = JFactory::getApplication();
		
		// override jpagination because of the limit issue.
		JLoader::register('JPagination', JPATH_COMPONENT_ADMINISTRATOR . '/overrides/pagination.php', true);
	
		
        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

        //from admin
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);
        //end from admin
        if (empty($ordering)) {
            $ordering = 'a.ordering';
        }

        // List state information.
        //parent::populateState($ordering, $direction);
        parent::populateState('a.word', 'ASC');
    }

    /**
     * Method to get a store id based on model configuration state.
     *
     * This is necessary because the model is used by the component and
     * different modules that might need different sets of data or different
     * ordering requirements.
     *
     * @param    string $id    A prefix for the store id.
     * @return    string        A store id.
     * @since    1.6
     */
    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return    JDatabaseQuery
     * @since    1.6
     */
    protected function getListQuery()
    {
        $input = JFactory::getApplication()->input;
        $menuitemid = $input->getInt('Itemid'); // this returns the menu id number so you can reference parameters
        $menu = JSite::getMenu();
		$app = JFactory::getApplication();
        $this->params = $app->getParams('com_gnosis');
        $gwcreatedbydisplay = $this->params->get('gwcreatedbydisplay', 1);

        if ($gwcreatedbydisplay == 0) {
            $gwusername = 'name';
        } else {
            $gwusername = $gwusername = 'username';
        }
		
		
		 if ($menuitemid) {
            $menuparams = $menu->getParams($menuitemid);
            $tagid = $menuparams->get('tags'); // This shows how to get an individual parameter for use

        }

        if (JFactory::getApplication()->input->get('id')) {
            $tagid = JFactory::getApplication()->input->get('id');
        }
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select', 'a.*'
            )
        );

        $query->from('`#__gnosis` AS a');


        // Join over the foreign key 'category'
        $query->select('category AS category');
        $query->join('LEFT', '#__gnosis_category AS category ON category.id = a.category');
        // Join over the created by field 'created_by'
        $query->select('created_by.' . $gwusername . ' AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
				//$tagsearch = $search;
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.word LIKE ' . $search . '  OR  a.definition LIKE ' . $search . '  OR  a.examples LIKE ' . $search . ')');
            }
        }
		else {
            
			$query->where('FIND_IN_SET (' . $tagid . ',' . 'a.tags )');
        }


        //Filtering category
        $filter_category = $this->state->get("filter.category");
        if ($filter_category) {
            $query->where("a.category = '" . $filter_category . "'");
        }

		//Filtering creation_date
		$filter_creation_date_from = $this->state->get("filter.creation_date.from");
		if ($filter_creation_date_from) {
			$query->where("a.creation_date >= '".$db->escape($filter_creation_date_from)."'");
		}
		$filter_creation_date_to = $this->state->get("filter.creation_date.to");
		if ($filter_creation_date_to) {
			$query->where("a.creation_date <= '".$db->escape($filter_creation_date_to)."'");
		}

		//Filtering modified_date
		$filter_modified_date_from = $this->state->get("filter.modified_date.from");
		if ($filter_modified_date_from) {
			$query->where("a.modified_date >= '".$db->escape($filter_modified_date_from)."'");
		}
		$filter_modified_date_to = $this->state->get("filter.modified_date.to");
		if ($filter_modified_date_to) {
			$query->where("a.modified_date <= '".$db->escape($filter_modified_date_to)."'");
		}
		

        //Filtering tags
        $filter_tags = $this->state->get("filter.tags");
        if ($filter_tags) {
            $query->where("a.tags= '" . $filter_tags . "'");
			//echo 'filter tags';
        }


        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol . ' ' . $orderDirn));
        }
        return $query;
    }
// added to support tags
    public function getItems() {
        $items = parent::getItems();
        
		foreach ($items as $oneItem) {


			if ( isset($oneItem->tags) ) {
				// Catch the item tags (string with ',' coma glue)
				$tags = explode(",",$oneItem->tags);

				$db = JFactory::getDbo();
					$namedTags = array(); // Cleaning and initalization of named tags array

					// Get the tag names of each tag id
					foreach ($tags as $tag) {

						$query = $db->getQuery(true);
						$query->select("title");
						$query->from('`#__tags`');
						$query->where( "id=" . intval($tag) );

						$db->setQuery($query);
						$row = $db->loadObjectList();

						// Read the row and get the tag name (title)
						if (!is_null($row)) {
							foreach ($row as $value) {
								if ( $value && isset($value->title) ) {
									$namedTags[] = trim($value->title);
								}
							}
						}

					}

					// Finally replace the data object with proper information
					$oneItem->tagsId = $oneItem->tags;
					$oneItem->tags = !empty($namedTags) ? implode(', ',$namedTags) : $oneItem->tags;
				}
		}
        return $items;
    }
}
