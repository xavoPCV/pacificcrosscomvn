<?php
/**
 * @version     1.0.0
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
class GnosisModelwords extends JModelList
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
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);


        //Filtering category
        $this->setState('filter.category', $app->getUserStateFromRequest($this->context . '.filter.category', 'filter_category', '', 'string'));

		//Filtering creation_date
		$this->setState('filter.creation_date.from', $app->getUserStateFromRequest($this->context.'.filter.creation_date.from', 'filter_from_creation_date', '', 'string'));
		$this->setState('filter.creation_date.to', $app->getUserStateFromRequest($this->context.'.filter.creation_date.to', 'filter_to_creation_date', '', 'string'));

		//Filtering modified_date
		$this->setState('filter.modified_date.from', $app->getUserStateFromRequest($this->context.'.filter.modified_date.from', 'filter_from_modified_date', '', 'string'));
		$this->setState('filter.modified_date.to', $app->getUserStateFromRequest($this->context.'.filter.modified_date.to', 'filter_to_modified_date', '', 'string'));


        // Load the parameters.
        $params = JComponentHelper::getParams('com_gnosis');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.word', 'asc');
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
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.*'
            )
        );
        $query->from('`#__gnosis` AS a');


        // Join over the foreign key 'category'
        $query->select('category AS category');
        $query->join('LEFT', '#__gnosis_category AS category ON category.id = a.category');
        // Join over the user field 'created_by'
        $query->select('created_by.name AS created_by');
        $query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = ' . (int)$published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }


        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int)substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('( a.word LIKE ' . $search . '  OR  a.definition LIKE ' . $search . '  OR  a.examples LIKE ' . $search . ' )');
            }
        }


        //Filtering category
        $filter_category = $this->state->get("filter.category");
        if ($filter_category) {
            $query->where("a.category = '" . $db->escape($filter_category) . "'");
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

			if (isset($oneItem->category)) {
				$values = explode(',', $oneItem->category);

				$textValue = array();
				foreach ($values as $value){
					$db = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
							->select('word')
							->from('`#__gnosis`')
							->where('id = ' .$value);
					$db->setQuery($query);
					$results = $db->loadObject();
					if ($results) {
						$textValue[] = $results->word;
					}
				}

			$oneItem->category = !empty($textValue) ? implode(', ', $textValue) : $oneItem->category;

			}

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
					$oneItem->tags = !empty($namedTags) ? implode(', ',$namedTags) : $oneItem->tags;
				}
		}
        return $items;
    }
	
}
