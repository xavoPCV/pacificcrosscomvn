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

class fleximportPluginTypeBase {
    public $_pathToTemp = ''; // Chemin vers le r�pertoire temporaire
    public $_params = null; // Param�tres du type d'import
    public $_filename = ''; // Nom du fichier XML qui doit �tre trait�
    public $_data = array(); // Les donn�es � import�es de 1 ou plusieurs fichiers
	public $_nbAjaxStep0 = null; // nombre de ligne � traiter lors de l'appel ajax
	public $_firstLoad = true; // permet de savoir si la classe a �t� apell� ou non pour la premiere fois

    function __construct($filename = '', $pathToTemp = '')
    {
        $this->_filename = $filename;
        $this->_pathToTemp = $pathToTemp;
    	$cparams = JComponentHelper::getParams('com_fleximport');
    	$this->_nbAjaxStep0 = (int) $cparams->get('ajax_import_step0',20);
    	$this->_firstLoad = (JFactory::getApplication()->input->get('task')=="start" || JFactory::getApplication()->input->get('task')=="cron" );
        return true;
    }
    /*
	   M�thode pour connaitre le nombre d'enregistrement dans un fichier
	*/
    public function getTotal()
    {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('count(id)')->from('#__fleximport_tmp_import_raw');
    	$db->setQuery($query);
        return $db->loadResult();
    }
	public function getRawData($index = 0){
		// recherche de la ligne
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('raw')->from('#__fleximport_tmp_import_raw')->where('id='.(int)$index);
		$db->setQuery($query);
		return $db->loadResult();
	}
    /* M�thode pour enregistrer un �l�ment export� dans la base donn�e
	 */
    public function exportDBRecord($itemID = null, $type = 0)
    {
        if (JFactory::getApplication()->input->get('params_record')=='2' && $itemID && $type) {
            $dbExport = JTable::getInstance('Export', 'FlexImportTable');
            $dbExport->type_id = $type;
            $dbExport->item_id = $itemID;
            $dbExport->date_export = JFactory::getDate()->toSql();
            if (!$dbExport->check())return false;
            if (!$dbExport->store())return false;
        }
        return true;
    }
}
