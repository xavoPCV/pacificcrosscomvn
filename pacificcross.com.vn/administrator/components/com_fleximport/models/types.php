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

jimport( 'joomla.application.component.modellist');

class FlexImportModelTypes extends JModelList {
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 't.id',
				'flexi_type_id', 't.flexi_type_id',
				'flexi_type', 'ft.name',
				'import_type', 't.import_type',
				'name', 't.name',
				'description', 't.description',
				'published', 't.published'
			);
		}
		parent::__construct($config);
	}

	protected function getListQuery() {
		$query = $this->_db->getQuery(true);
		$query->select('t.*, u.name AS editor, ft.name AS flexi_type, COUNT(f.type_id) AS fassigned');
		$query->from('#__fleximport_types AS t');
		$query->join('left','#__fleximport_fields AS f ON t.id = f.type_id');
		$query->join('left','#__flexicontent_types AS ft ON t.flexi_type_id = ft.id');
		$query->join('left','#__users AS u ON u.id = t.checked_out');
		$query->group('t.id');

		$filter_published = $this->getState('filter.published');
		if (is_numeric($filter_published)) {
			$query->where('t.published = '.(int)$filter_published);
		}
		$filter_id = $this->getState('filter.id');
		if (is_numeric($filter_id)) {
			$query->where('t.id = '.(int)$filter_id);
		}
		$filter_flexi_type_id = $this->getState('filter.flexi_type_id');
		if (is_numeric($filter_flexi_type_id)) {
			$query->where('t.flexi_type_id = '.(int)$filter_flexi_type_id);
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering','t.name');
		$orderDirn	= $this->state->get('list.direction','asc');

		$query->order($this->_db->escape($orderCol.' '.$orderDirn));

		return $query;
	}

	protected function populateState($ordering = 't.name', $direction = 'asc')
	{
		$filter_published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published');
		$this->setState('filter.published', $filter_published);

		$filter_id = $this->getUserStateFromRequest($this->context.'.filter.id', 'filter_id');
		$this->setState('filter.id', $filter_id);

		$filter_flexi_type_id = $this->getUserStateFromRequest($this->context.'.filter.flexi_type_id', 'filter_flexi_type_id');
		$this->setState('filter.flexi_type_id', $filter_flexi_type_id);

		parent::populateState($ordering,$direction);

	}

	function copy($cid = array())
	{
		if (count($cid)) {
			foreach ($cid as $id) {
				$type = $this->getTable('Types', 'FlexImportTable');
				$type->load($id);
				$type->id = 0;
				$type->name = $type->name . ' [copy]';
				if ($type->check()) {
					$type->store();

					$query = 'SELECT * FROM #__fleximport_fields'
					 . ' WHERE type_id = ' . (int)$id ;
					$this->_db->setQuery($query);
					$fields = $this->_db->loadObjectList();

					foreach ($fields as $field) {
						if (!in_array($field->field_type, $GLOBALS['fi_fields_nocopy'] )) {
							$query = 'INSERT INTO #__fleximport_fields (`type_id`, `field_type`, `flexi_field_id`, `name`, `label`, `description`, `iscore`, `isrequired`, `params`, `ordering`, `published`) '
							 . ' VALUES(' . $type->id . ',' . $this->_db->Quote($field->field_type) . ',' . (int)$field->flexi_field_id . ',' . $this->_db->Quote($field->name . ' [copy]') . ',' . $this->_db->Quote($field->label) . ',' . $this->_db->Quote($field->description) . ',' . $field->iscore . ',' . $field->isrequired . ',' . $this->_db->Quote($field->params) . ',' . (int)$field->ordering . ',true)';
							$this->_db->setQuery($query);
							$this->_db->execute();
						}
					}
				} else {
					return false;
				}
			}
			return true;
		}
		return false;
	}
}
