<?php
/**
 *
 * @version 2.0
 * @package Joomla
 * @subpackage flexIMPORT
 * @copyright (C) 2011 NetAssoPro - www.netassopro.com
 * @license GNU/GPL v2
 *
 * flexIMPORT is a addon for the excellent FLEXIcontent component. Some part of
 * code is directly inspired.
 * @copyright (C) 2009 Emmanuel Danan
 * see www.vistamedia.fr for more information
 *
 * flexIMPORT is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * flexIMPORT Component Export Model
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportModelExport extends JModelList {
    /**
     * Constructor
     *
     * @since 1.0
     */
    function __construct($config = array())
    {
        parent::__construct($config);
    }
    protected function getListQuery()
    {
        $query = $this->_db->getQuery(true);

        $query->select('distinct i.*,i.catid AS maincat, t.name AS type_name');
        $query->from('#__content AS i');
        $query->join('left', '#__flexicontent_items_ext AS ie ON ie.item_id = i.id');
        $query->join('left', '#__flexicontent_cats_item_relations AS rel ON rel.itemid = i.id');
        $query->join('left', '#__flexicontent_types AS t ON t.id = ie.type_id');
        $query->join('left', '#__users AS u ON u.id = i.checked_out');
        $query->group('i.id');
        $query->order('i.id ASC');

		if ($this->getState('params.type')) {
			$query2 = $this->_db->getQuery(true);
			$query2->select('flexi_type_id')->from('#__fleximport_types')->where('id=' . (int)$this->getState('params.type'));
			$this->_db->setQuery($query2);
			$query->where('ie.type_id = '.(int)$this->_db->loadResult());
		}else{
			$query->where('ie.type_id = 0');
		}

        $filter_added = $this->getState('filter.added');
        if ($filter_added<>'all') {
            $query2 = $this->_db->getQuery(true);
            $query2->select('id');
            $query2->from('#__content');
            $query2->where('id NOT IN(SELECT distinct item_id FROM #__fleximport_export WHERE type_id=' . (int)$this->getState('params.type') . ')');
            $this->_db->setQuery($query2);
            if ($result = $this->_db->loadColumn()) {
                $result = implode(',', $result);
                if ($filter_added == 'include') {
                    $query->where('i.id IN (' . $result . ')');
                } elseif ($filter_added == 'exclude') {
                    $query->where('i.id NOT IN (' . $result . ')');
                }
            }
        }
        $filter_modified = $this->getState('filter.modified');
        if ($filter_modified<>'all') {
            $query2 = $this->_db->getQuery(true);
            $query2->select('date_export');
            $query2->from('#__fleximport_export');
            $query2->where('type_id=' . (int)$this->getState('params.type'));
            $query2->order('date_export DESC');
            $this->_db->setQuery($query2, 0, 1);
            $lastDate = $this->_db->loadResult();
            if ($lastDate) {
                $query2 = $this->_db->getQuery(true);
                $query2->select('id');
                $query2->from('#__content');
                $query2->where('modified > ' . $this->_db->quote($lastDate));
                $this->_db->setQuery($query2);
                if ($result = $this->_db->loadColumn()) {
                    $result = implode(',', $result);
                    if ($filter_modified == 'include') {
                        $query->where('i.id IN (' . $result . ')');
                    } elseif ($filter_modified == 'exclude') {
                        $query->where('i.id NOT IN (' . $result . ')');
                    }
                }
            }
        }
        $filter_addedormodified = $this->getState('filter.addedormodified');
        if ($filter_addedormodified<>'all') {
            $query2 = $this->_db->getQuery(true);
            $query2->select('date_export');
            $query2->from('#__fleximport_export');
            $query2->where('type_id=' . (int)$this->getState('params.type'));
            $query2->order('date_export DESC');
            $this->_db->setQuery($query2, 0, 1);
            $lastDate = $this->_db->loadResult();
            if ($lastDate) {
                $query2 = $this->_db->getQuery(true);
                $query2->select('id');
                $query2->from('#__content');
                $where ='(modified > ' . $this->_db->quote($lastDate).') OR ';
                $where.='(id NOT IN(SELECT distinct item_id FROM #__fleximport_export WHERE type_id=' . (int)$this->getState('params.type') . '))';

                $query2->where($where);

                $this->_db->setQuery($query2);
                if ($result = $this->_db->loadColumn()) {
                    $result = implode(',', $result);
                    if ($filter_addedormodified == 'include') {
                        $query->where('i.id IN (' . $result . ')');
                    } elseif ($filter_addedormodified == 'exclude') {
                        $query->where('i.id NOT IN (' . $result . ')');
                    }
                }
            }
        }

        $filter_noexported = $this->getState('filter.noexported');
        if ($filter_noexported) {
            $query2 = $this->_db->getQuery(true);
            $query2->select('id');
            $query2->from('#__content');
            $query2->where('id IN(SELECT distinct item_id FROM #__fleximport_export WHERE type_id=' . (int)$this->getState('params.type') . ' AND date_export<' . $this->_db->quote($filter_noexported) . ')');
            $this->_db->setQuery($query2);
            if ($result = $this->_db->loadColumn()) {
                $query2 = $this->_db->getQuery(true);
                $query2->select('id');
                $query2->from('#__content');
                $query2->where('and id NOT IN(SELECT distinct item_id FROM #__fleximport_export WHERE type_id=' . (int)$this->getState('params.type') . ')');
                $this->_db->setQuery($query2);
                $result2 = $this->_db->loadColumn();
                $result = array_merge($result, $result2);
                if (!count($result)) {
                    $result[] = 0;
                }
                $result = implode(', ', $result);
                $query->where('i.id IN (' . $result . ')');
            }
        }
        $filter_search = $this->getState('filter.search');
        $filter_scope = $this->getState('filter.scope');
        $filter_scope_include = $this->getState('filter.scope_include');
        if ($filter_scope_include == 'include' && $filter_search) {
            switch ($filter_scope) {
                case 1:
                    $query->where('LOWER(i.title) LIKE ' . $this->_db->quote('%' . $filter_search . '%', false));
                    break;
                case 2:
                    $query->where('LOWER(i.introtext) LIKE ' . $this->_db->quote('%' . $filter_search . '%', false));
                    break;
                case 3:
                    $query->where('MATCH (ie.search_index) AGAINST (' . $this->_db->quote($filter_search, false) . ' IN BOOLEAN MODE)');
                    break;
            }
        } elseif ($filter_scope_include == 'exclude' && $filter_search) {
            switch ($filter_scope) {
                case 1:
                    $query->where('LOWER(i.title) NOT LIKE ' . $this->_db->quote('%' . $filter_search . '%', false));
                    break;
                case 2:
                    $query->where('LOWER(i.introtext) NOT LIKE ' . $this->_db->quote('%' . $filter_search . '%', false));
                    break;
                case 3:
                    $query->where('MATCH (ie.search_index) AGAINST (' . $this->_db->quote('-' . $filter_search, false) . ' IN BOOLEAN MODE)');
                    break;
            }
        }
        $filter_state = $this->getState('filter.state');
        array_walk($filter_state, array($this, 'arrayQuote'));
        $filter_state_include = $this->getState('filter.state_include');
        if ($filter_state_include == 'include' && count($filter_state)) {
            $query->where('i.state IN (' . implode(',', $filter_state) . ')');
        } elseif ($filter_state_include == 'exclude' && count($filter_state)) {
            $query->where('i.state NOT IN (' . implode(',', $filter_state) . ')');
        }

        $filter_maincats = $this->getState('filter.maincats');
        array_walk($filter_maincats, array($this, 'arrayQuote'));
        $filter_maincats_include = $this->getState('filter.maincats_include');
        if ($filter_maincats_include == 'include' && count($filter_maincats)) {
            $query->where('i.catid IN (' . implode(',', $filter_maincats) . ')');
        } elseif ($filter_maincats_include == 'exclude' && count($filter_maincats)) {
            $query->where('i.catid NOT IN (' . implode(',', $filter_maincats) . ')');
        }

        $filter_cats = $this->getState('filter.cats');
        array_walk($filter_cats, array($this, 'arrayQuote'));
        $filter_cats_include = $this->getState('filter.cats_include');
        if ($filter_cats_include == 'include' && count($filter_cats)) {
            $query->where('rel.catid IN (' . implode(',', $filter_cats) . ')');
        } elseif ($filter_cats_include == 'exclude' && count($filter_cats)) {
            $query->where('rel.catid NOT IN (' . implode(',', $filter_cats) . ')');
        }

        $filter_authors = $this->getState('filter.authors');
    	array_walk($filter_authors, array($this, 'arrayQuote'));
        $filter_authors_include = $this->getState('filter.authors_include');
        if ($filter_authors_include == 'include' && count($filter_authors)) {
            $query->where('i.created_by IN (' . implode(',', $filter_authors) . ')');
        } elseif ($filter_authors_include == 'exclude' && count($filter_authors)) {
            $query->where('i.created_by NOT IN (' . implode(',', $filter_authors) . ')');
        }

        $filter_date = $this->getState('filter.date');
        $filter_startdate = $this->getState('filter.startdate');
        $filter_enddate = $this->getState('filter.enddate');
        $nullDate = $this->_db->getNulldate();
        if ($filter_date == 1) {
            if ($filter_startdate && !$filter_enddate) { // from only
                $query->where('i.created >= ' . $this->_db->quote($filter_startdate));
            }
            if (!$filter_startdate && $filter_enddate) { // to only
                $query->where('i.created <= ' . $this->_db->quote($filter_enddate));
            }
            if ($filter_startdate && $filter_enddate) { // date range
                $query->where('(i.created >= ' . $this->_db->quote($filter_startdate) . ' AND i.created <= ' . $this->_db->quote($filter_enddate) . ' )');
            }
        }
        if ($filter_date == 2) {
            if ($filter_startdate && !$filter_enddate) { // from only
                $query->where('(i.modified >= ' . $this->_db->quote($filter_startdate) . ' OR ( i.modified = ' . $this->_db->quote($nullDate) . ' AND i.created >= ' . $this->_db->quote($filter_startdate) . '))');
            }
            if (!$filter_startdate && $filter_enddate) { // to only
                $query->where('(i.modified <= ' . $this->_db->quote($filter_enddate) . ' OR ( i.modified = ' . $this->_db->quote($nullDate) . ' AND i.created <= ' . $this->_db->quote($filter_enddate) . '))');
            }
            if ($filter_startdate && $filter_enddate) { // date range
                $query->where('((i.modified >= ' . $this->_db->quote($filter_startdate) . ' OR ( i.modified = ' . $this->_db->quote($nullDate) . ' AND i.created >= ' . $this->_db->quote($filter_startdate) . ')) AND ( i.modified <= ' . $this->_db->quote($filter_enddate) . ' OR ( i.modified = ' . $this->_db->quote($nullDate) . ' AND i.created <= ' . $this->_db->quote($filter_enddate) . ')))');
            }
        }
        if ($filter_date == 3) {
            if ($filter_startdate && !$filter_enddate) { // from only
                $query->where('i.publish_up >= ' . $this->_db->quote($filter_startdate));
            }
            if (!$filter_startdate && $filter_enddate) { // to only
                $query->where('i.publish_up <= ' . $this->_db->quote($filter_enddate));
            }
            if ($filter_startdate && $filter_enddate) { // date range
                $query->where('i.publish_up >= ' . $this->_db->quote($filter_startdate) . ' AND i.publish_up <= ' . $this->_db->quote($filter_enddate) . ' )');
            }
        }

        $filter_tags = $this->getState('filter.tags');
        array_walk($filter_tags, array($this, 'arrayQuote'));
        $filter_tags_include = $this->getState('filter.tags_include');
        $resultTags = array();
        if ($filter_tags_include && count($filter_tags)) {
            $query2 = $this->_db->getQuery(true);
            $query2->select('itemid')->from('#__flexicontent_tags_item_relations')->where('tid IN (' . implode(',', $filter_tags) . ')');
            $this->_db->setQuery($query2);
            $resultTags = $this->_db->loadColumn();
        }
        if ($filter_tags_include == 'include' && count($filter_tags)) {
            if (count($resultTags)) {
                $query->where('i.id IN (' . implode(',', $resultTags) . ')');
            } else {
                // aucun article ne correspond � un tag � inclure donc on n'affiche rien
                $query->where('i.id = 0');
            }
        } elseif ($filter_tags_include == 'exclude' && count($filter_tags) && count($resultTags)) {
            $query->where('i.id NOT IN (' . implode(',', $resultTags) . ')');
        }

        $filter_lang = $this->getState('filter.lang');
        $filter_lang_include = $this->getState('filter.lang_include');
        if ($filter_lang_include == 'include' && $filter_lang) {
            $query->where('ie.language =' . $this->_db->quote($filter_lang) . ')');
        } elseif ($filter_lang_include == 'exclude' && $filter_lang) {
            $query->where('ie.language !=' . $this->_db->quote($filter_lang) . ')');
        }

        $filter_id = $this->getState('filter.id');
        $filter_id_include = $this->getState('filter.id_include');
        if ($filter_id_include == 'include' && count(explode(',', $filter_id))) {
            $query->where('i.id IN (' . $filter_id . ')');
        } elseif ($filter_id_include == 'exclude' && count(explode(',', $filter_id))) {
            $query->where('i.id NOT IN (' . $filter_id . ')');
        }

        $filter_cid = $this->getState('filter.cid');
        array_walk($filter_cid, array($this, 'arrayQuote'));
        if (count($filter_cid)) {
            $query->where('i.id IN (' . implode(',', $filter_cid) . ')');
        }
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');

        $query->order($this->_db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    public function getItems()
    {
        $items = parent::getItems();
        if ($items) {
            foreach ($items as &$item) {
                $item->categories = $this->getCategories($item->id);
            }
        }
        return $items;
    }

	public function getAllItems()
	{
		$limit = $this->getState('list.limit');
		$limitStart = $this->getState('list.start');
		$this->setState('list.limit',0);
		$this->setState('list.start',0);

		$items = parent::getItems();
		if ($items) {
			foreach ($items as &$item) {
				$item->categories = $this->getCategories($item->id);
			}
		}
		$this->setState('list.limit',$limit);
		$this->setState('list.start',$limitStart);
		return $items;
	}

    protected function populateState($ordering = 'i.id', $direction = 'asc')
    {
    	// force l'index pour les tableaux vides
    	$listArray=array('filter_maincats','filter_cats','filter_tags');
    	foreach ($listArray as $array){
    		if (!isset($_REQUEST[$array]) && !$this->getUserStateFromRequest($this->context . '.'.$array, $array, array(), 'array')) {
    			$_REQUEST[$array]=array();
    		}
    	}
        $params_type = $this->getUserStateFromRequest($this->context . '.params_type', 'params_type', '', 'int');
        $this->setState('params.type', $params_type);

    	$params_record = $this->getUserStateFromRequest($this->context . '.params_record', 'params_record', '1', 'int');
    	$this->setState('params.record', $params_record);

    	$params_export_attachment = $this->getUserStateFromRequest($this->context . '.params_export_attachment', 'params_export_attachment', '1', 'int');
    	$this->setState('params.export_attachment', $params_export_attachment);

        $filter_added = $this->getUserStateFromRequest($this->context . '.filter_added', 'filter_added', '', 'word');
        $this->setState('filter.added', $filter_added);

        $filter_modified = $this->getUserStateFromRequest($this->context . '.filter_modified', 'filter_modified', '', 'word');
        $this->setState('filter.modified', $filter_modified);

        $filter_addedormodified = $this->getUserStateFromRequest($this->context . '.filter_addedormodified', 'filter_addedormodified', '', 'word');
        $this->setState('filter.addedormodified', $filter_addedormodified);


        $filter_noexported = $this->getUserStateFromRequest($this->context . '.filter_noexported', 'filter_noexported', '', 'cmd');
        $this->setState('filter.noexported', $filter_noexported);

    	$filter_scope_include = $this->getUserStateFromRequest($this->context . '.filter_scope_include', 'filter_scope_include', '', 'word');
    	$this->setState('filter.scope_include', $filter_scope_include);
    	if ($filter_scope_include) {
    		$filter_scope = $this->getUserStateFromRequest($this->context . '.filter_scope', 'filter_scope', 1, 'int');
    		$this->setState('filter.scope', $filter_scope);
    		$filter_search = $this->getUserStateFromRequest($this->context . '.filter_search', 'filter_search', '', 'string');
    		$this->setState('filter.search', $filter_search);
    	}else{
    		$this->setState('filter.scope', 1);
    		$this->setState('filter.search', '');
    	}


        $filter_state_include = $this->getUserStateFromRequest($this->context . '.filter_state_include', 'filter_state_include', '', 'word');
        $this->setState('filter.state_include', $filter_state_include);
    	if ($filter_state_include) {
    		$filter_state = $this->getUserStateFromRequest($this->context . '.filter_state', 'filter_state', array(), 'array');
    		$this->setState('filter.state', $filter_state);
    	}else{
    		$this->setState('filter.state', array());
    	}

        $filter_maincats_include = $this->getUserStateFromRequest($this->context . '.filter_maincats_include', 'filter_maincats_include', '', 'word');
        $this->setState('filter.maincats_include', $filter_maincats_include);
    	if ($filter_maincats_include) {
    		$filter_maincats = $this->getUserStateFromRequest($this->context . '.filter_maincats', 'filter_maincats', array(), 'array');
    		$this->setState('filter.maincats', $filter_maincats);
    	}else{
    		$this->setState('filter.maincats', array());
    	}

        $filter_cats_include = $this->getUserStateFromRequest($this->context . '.filter_cats_include', 'filter_cats_include', '', 'word');
        $this->setState('filter.cats_include', $filter_cats_include);
		if ($filter_cats_include) {
			$filter_cats = $this->getUserStateFromRequest($this->context . '.filter_cats', 'filter_cats', array(), 'array');
			$this->setState('filter.cats', $filter_cats);
		}else{
			$this->setState('filter.cats', array());
		}

        $filter_authors_include = $this->getUserStateFromRequest($this->context . '.filter_authors_include', 'filter_authors_include', '', 'word');
        $this->setState('filter.authors_include', $filter_authors_include);
    	if ($filter_authors_include) {
    		$filter_authors = $this->getUserStateFromRequest($this->context . '.filter_authors', 'filter_authors', array(), 'array');
    		$this->setState('filter.authors', $filter_authors);
    	}else{
    		$this->setState('filter.authors', array());
    	}

        $filter_date = $this->getUserStateFromRequest($this->context . '.filter_date', 'filter_date', 1, 'int');
        $this->setState('filter.date', $filter_date);
        $filter_startdate = $this->getUserStateFromRequest($this->context . '.filter_startdate', 'filter_startdate', '', 'cmd');
        $this->setState('filter.startdate', $filter_startdate);
        $filter_enddate = $this->getUserStateFromRequest($this->context . '.filter_enddate', 'filter_enddate' , '', 'cmd');
        $this->setState('filter.enddate', $filter_enddate);

        $filter_tags_include = $this->getUserStateFromRequest($this->context . '.filter_tags_include', 'filter_tags_include', '', 'word');
        $this->setState('filter.tags_include', $filter_tags_include);
    	if ($filter_tags_include) {
    		$filter_tags = $this->getUserStateFromRequest($this->context . '.filter_tags', 'filter_tags', array(), 'array');
    		$this->setState('filter.tags', $filter_tags);
    	}else{
    		$this->setState('filter.tags', array());
    	}

        $filter_lang_include = $this->getUserStateFromRequest($this->context . '.filter_lang_include', 'filter_lang_include', '', 'word');
        $this->setState('filter.lang_include', $filter_lang_include);
    	if ($filter_lang_include) {
    		$filter_lang = $this->getUserStateFromRequest($this->context . '.filter_lang', 'filter_lang','', 'word');
    		$this->setState('filter.lang', $filter_lang);
    	}else{
    		$this->setState('filter.lang', '');
    	}

    	$filter_id_include = $this->getUserStateFromRequest($this->context . '.filter_id_include', 'filter_id_include', '', 'word');
    	$this->setState('filter.id_include', $filter_id_include);
    	if ($filter_id_include) {
    		$filter_id = $this->getUserStateFromRequest($this->context . '.filter_id', 'filter_id', '', 'string');
    		$this->setState('filter.id', $filter_id);
    	}else{
    		$this->setState('filter.id', '');
    	}

        $filter_cid = JFactory::getApplication()->input->get('cid',array(),'array');
        $this->setState('filter.cid', $filter_cid);

        parent::populateState($ordering, $direction);
    }
    private function getCategories($id)
    {
        $query = $this->_db->getQuery(true);
        $query->select('DISTINCT c.id, c.title')->from('#__categories AS c');
        $query->join('left', '#__flexicontent_cats_item_relations AS rel ON rel.catid = c.id');
        $query->where('rel.itemid = ' . (int)$id);
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
    public function getForm()
    {
        $form = new JForm('com_fleximport.export');
        $formpath = JPATH_COMPONENT_ADMINISTRATOR . '/models/forms/export.xml';
        if (file_exists($formpath)) {
            // Get the plugin form.
            if (!$form->loadFile($formpath, false, '//form')) {
                throw new Exception(JText::_('JERROR_LOADFILE_FAILED'), 400);
            }
        }
        $values = (array)$this->getState();


        $filterList = array('params.type', 'params.record', 'params.export_attachment', 'filter.added', 'filter.modified',
            'filter.addedormodified','filter.noexported', 'filter.scope_include', 'filter.scope', 'filter.search', 'filter.state_include',
            'filter.state', 'filter.maincats_include', 'filter.cats_include', 'filter.maincats', 'filter.cats', 'filter.authors_include',
            'filter.authors', 'filter.date', 'filter.startdate', 'filter.enddate', 'filter.tags_include', 'filter.tags',
            'filter.lang_include', 'filter.lang', 'filter.id_include', 'filter.id');

        foreach ($filterList as $filter) {
            if (strpos($filter,'filter.')!==false)
                $key = str_replace('filter.', 'filter_', $filter);
            else
                $key = str_replace('params.', 'params_', $filter);
            $form->setValue($key,null,$this->getState($filter));
        }
/*
        foreach ($values as $key => $value) {
        	if (strpos($key,'filter.')!==false) {
        		$key = str_replace('filter.', 'filter_', $key);
        		$form->setValue($key,null,$value);
        	}elseif( strpos($key,'params.')!==false){
        		$key = str_replace('params.', 'params_', $key);
        		$form->setValue($key,null,$value);

        	}
        }*/
        return $form;
    }

    private function arrayQuote(&$value = null)
    {
        $value = $this->_db->quote($value);
    }
}