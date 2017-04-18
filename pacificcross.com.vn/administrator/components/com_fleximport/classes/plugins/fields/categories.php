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
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT.'/classes/plugins/field.php');
class fleximportPlugincategories extends fleximportFieldPlugin {
    public function getDefaultValue()
    {
        $defaultValue = array();
    	$categories = $this->_fieldParams->get('categories_default', '');
    	if (!is_array($categories)) {
    		$categories = explode(",", $this->_fieldParams->get('categories_default', ''));
    	}

        foreach ($categories as $defaultCat) {
            $defaultValue[] = $defaultCat;
        }
        return $defaultValue;
    }
    public function formatValues()
    {
        // si on a gérer les valeurs par défaut, on a tableau multidimensionnel , il faut le réduire au premier niveau
        if (is_array($this->_fieldValues[0]))
            $this->_fieldValues = $this->_fieldValues[0];
        $catFormat = $this->_fieldParams->get('categories_format', '1');
        $catSplit = $this->_fieldParams->get('categories_split', '');
        $catForceDefault = $this->_fieldParams->get('categories_force_default', '0');
        // s'il faut spliter les valeurs des catégories avant de traiter la liaison
        if ($catSplit) {
            foreach ($this->_fieldValues as &$fieldValue) {
                $listValues = explode($catSplit, $fieldValue);
                // récupère la dernière valeur
                $fieldValue = trim($listValues[(count($listValues) - 1)]);
            }
        }
        // si on gère les catégories en fonction de l'ID
        $categories = array();
        $flexiCategories = $this->getCategoriesList();
        if ($catFormat == '1') {
            foreach ($this->_fieldValues as $categorieValue) {
                // on ne mémorise que les valeurs numériques
                if (in_array($categorieValue, $flexiCategories)) {
                    $categories[] = $categorieValue;
                }
            }
            // si on gère les catégories par nom
        } elseif ($catFormat == '2') {
            foreach ($this->_fieldValues as $categorieTitle) {
                $categorieValue = $this->getCategoriesList($categorieTitle);
                // on ne mémorise que les valeurs numériques
                if (in_array($categorieValue, $flexiCategories)) {
                    $categories[] = $categorieValue;
                }
            }
        }
        if ($catForceDefault && count($categories)) {
            $categories = array_merge($this->getDefaultValue(), $categories);
        } elseif (!count($categories)) {
            // force la valeur par défaut si aucune catégorie correspond aux catégories existantes
            $categories = $this->getDefaultValue();
        }

        $this->_fieldValues = $categories;

    	return true;
    }
    public function formatValuesExport()
    {
        $catFormat = $this->_fieldParams->get('categories_format', '1');
        foreach ($this->_fieldValues as $idv => $fieldValue) {
            if ($catFormat == '2') {
                $this->_fieldValues[$idv] = $this->getCategoryTitle($fieldValue);
            }
        }
    }
    private function getCategoriesList($byTitle = '')
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('id')->from('#__categories')->where('extension = "com_content"');

        if ($byTitle) {
            $query->where('title='.$db->quote($byTitle));
        	$db->setQuery($query,0,1);
        }else{
        	$db->setQuery($query);
        }
        if ($byTitle) {
            return $db->loadResult();
        } else {
            return $db->loadColumn();
        }
    }
    private function getCategoryTitle($catID = null)
    {
        if (!$catID) return;
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('title')->from('#__categories')->where('id='.(int)$catID);
        $db->setQuery($query);
        return $db->loadResult();
    }
    public function getPostValues()
    {
        $post['jform']['cid'] = $this->_fieldValues;
        // catégorie principale
        $post['jform']['catid'] = $this->_fieldValues[0];

        return $post;
    }
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('catid')->from('#__flexicontent_cats_item_relations')->where('itemid='.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
}