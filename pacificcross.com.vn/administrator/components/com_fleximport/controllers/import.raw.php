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

require_once (JPATH_COMPONENT . '/classes/import.php');

class FleximportControllerImport extends JControllerLegacy {
	public function __construct($config=array()){
		//permet d'�viter l'affichage de message d'erreur, on les r�cup�re au mmoment de renvoyer le json
		ob_start();
		FleximportHelper::loadFlexiContent();
		parent::__construct($config);
	}
    public function start()
    {
        // get variables
        $files = JFactory::getApplication()->input->get->files->getArray();
        $session = JFactory::getSession();
        // default return
        if ($import = new fleximportImport(JFactory::getApplication()->input->request->getArray(), $files))
            $import->process();

        $session->set("finish", true, "fleximport");
        // call the __destruct
        $import = null;
        FleximportHelper::AjaxReload(false);
    	return true;
    }
    public function ajax()
    {
        if ($import = new fleximportImport(null, null))
            $import->process();

        $session = JFactory::getSession();
        $session->set("finish", true, "fleximport");
        // call the __destruct
        $import = null;
        FleximportHelper::AjaxReload(false);
    }
	/* Fonction pour traiter l'import directement via une tache cron*/
	public function cron(){
		$cparams = JComponentHelper::getParams('com_fleximport');
		if (!$cparams->get('allow_cron', 0)) die('Cron not Allowed.');
		$post['import_method']= 'cron';
		$post['type_id']= JFactory::getApplication()->input->get('cronid',null,'int') ;
		$post['task']= 'cron';
		$files = null;

		$session = JFactory::getSession();

		if ($import = new fleximportImport($post, $files))
			$import->process();

		$session->set("finish", true, "fleximport");
		// call the __destruct
		$import = null;
        FleximportHelper::AjaxReload(false,'cron');
	}
	/* Fonction pour charger les etapes de l'import via une tache cron*/
	function cron_reload(){
		$cparams = JComponentHelper::getParams('com_fleximport');
		if (!$cparams->get('allow_cron', 0)) die('Cron not Allowed.');
		if ($import = new fleximportImport(null, null))
			$import->process();
		$session = JFactory::getSession();
		$session->set("finish", true, "fleximport");
		// call the __destruct
		$import = null;
        FleximportHelper::AjaxReload(false,'cron');
	}

}