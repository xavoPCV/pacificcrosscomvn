<?php
/**
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

jimport( 'joomla.application.component.modellist' );


class FleximportModelFields extends JModelList {

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'f.id',
				'type_id', 'f.type_id',
				'field_type', 'f.field_type',
				'flexi_field_id', 'f.flexi_field_id',
				'name', 'f.name',
				'label', 'f.label',
				'iscore', 'f.iscore',
				'isrequired', 'f.isrequired',
				'ordering', 'f.ordering',
				'description', 'f.description',
				'published', 'f.published'
			);
		}
		parent::__construct($config);
	}

	protected function getListQuery() {

		$query = $this->_db->getQuery(true);
		$query->select('f.*, u.name AS editor');
		$query->from('#__fleximport_fields AS f');
		$query->join('left','#__users AS u ON u.id = f.checked_out');
		$query->group('f.id');

		$filter_published = $this->getState('filter.published');
		if (is_numeric($filter_published)) {
			$query->where('f.published = '.(int)$filter_published);
		}
		$filter_id = $this->getState('filter.id');
		if (is_numeric($filter_id)) {
			$query->where('f.id = '.(int)$filter_id);
		}
		$filter_flexi_type = $this->getState('filter.type');
		if (is_numeric($filter_flexi_type)) {
			$query->where('f.type_id = '.(int)$filter_flexi_type);
		}
		$filter_usefor = $this->getState('filter.usefor');
		if ($filter_usefor != 1) {
			$query->where('(f.params LIKE ' . $this->_db->Quote('%"usefor":"' . $filter_usefor . '"%') . ' OR f.params LIKE '.$this->_db->Quote('%"usefor":"1"%').')');
		}
		$filter_search = $this->getState('filter.search');
		if ($filter_search) {
			$query->where(' LOWER(f.name) LIKE ' . $this->_db->Quote('%' . $this->_db->escape($filter_search, true) . '%'));
		}
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');

		$query->order($this->_db->escape($orderCol.' '.$orderDirn));

		return $query;
	}
	protected function populateState($ordering = 'f.ordering', $direction = 'asc')
	{
		$filter_published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $filter_published);

		$filter_id = $this->getUserStateFromRequest($this->context.'.filter.id', 'filter_id');
		$this->setState('filter.id', $filter_id);

		$filter_type = $this->getUserStateFromRequest($this->context.'.filter.type', 'filter_type');
		$this->setState('filter.type', $filter_type);

		$filter_usefor = $this->getUserStateFromRequest($this->context.'.filter.usefor', 'filter_usefor',1);
		$this->setState('filter.usefor', $filter_usefor);

		$filter_search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$filter_search = $this->_db->escape(trim(strtolower($filter_search)));

		$this->setState('filter.search', $filter_search);

		parent::populateState($ordering,$direction);

	}
	private function isCore($id = null){
		$query = $this->_db->getQuery(true);
		$query->select('iscore')->from('#__fleximport_fields')->where('id='.(int)$id);
		$this->_db->setQuery($query);
		return (bool)$this->_db->loadResult();
	}
	private function getLastID(){
		$query = $this->_db->getQuery(true);
		$query->select('MAX(id)')->from('#__fleximport_fields');
		$this->_db->setQuery($query);
		return (int)$this->_db->loadResult();
	}
    public function copy($cid = array())
    {
        if (count($cid)) {
            foreach ($cid as $id) {
                // only non core fields
                if (!$this->isCore($id)) {
                    $field = $this->getTable('Fields', 'FlexImportTable');
                    $field->load($id);
                    $field->id = 0;
                    $field->name = 'field' . ($this->getLastID() + 1);
                    $field->label = $field->label . ' [copy]';
                    $field->check();
                    $field->store();
                }
            }
            return true;
        }
        return false;
    }
}