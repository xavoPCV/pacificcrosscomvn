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
class fleximportPlugintags extends fleximportFieldPlugin {
    public function formatValues()
    {
        $tags = explode(",", $this->_fieldParams->get('tags_default'));
        // ajout des tags par défaut au tags déjà importés
        foreach ($tags as $defaultTag) {
            $this->_fieldValues[] = $defaultTag;
        }
        $model = $this->getModel('tag');
    	$db = JFactory::getDbo();
        $tags = array();
        // pour chaque tag
        foreach ($this->_fieldValues as $tag) {
            // si ce n'est pas une valeur nulle
            if ($tag) {
                // si le tag n'a pas déjà été recherché, recherche de l'ID en fonction du nom
                if (!isset($tagsData[$tag])) {
                	$query = $db->getQuery(true);
                	$query->select('id')->from('#__flexicontent_tags')->where('name='.$db->quote($tag));
                    $db->setQuery($query);
                    if (!($idTag = $db->loadResult())) {
                        // ajout du tag
                        $result = $model->addtag($tag);
                        if ($result)
                            $idTag = $model->_tag->id;
                    }
                    // mémorisation du tag
                    $tagsData[$tag] = $idTag;
                }
                $tags[] = $tagsData[$tag];
            }
        }
        $this->_fieldValues = $tags;
    }
    public function getPostValues()
    {
        $post['jform']['tag'] = $this->_fieldValues;

        return $post;
    }
    public function getFlexicontentValues($itemID = 0)
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('t.name')->from('#__flexicontent_tags AS t')->innerJoin('#__flexicontent_tags_item_relations AS i ON t.id = i.tid')->where('i.itemid='.(int)$itemID);
        $db->setQuery($query);
        return $db->loadColumn();
    }
}