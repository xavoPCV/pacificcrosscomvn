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

/**
 * View class for the flexIMPORT logs screen
 *
 * @package Joomla
 * @subpackage flexIMPORT
 * @since 1.0
 */
class FleximportViewExport extends JViewLegacy {
    protected $form;
    protected $state;
    protected $user;
    protected $items;
    protected $pagination;

    function display($tpl = null)
    {
        $layout = JFactory::getApplication()->input->get('layout');

        if ($layout == 'savefilter') {
            $this->_displaySaveFilter($tpl);
            return;
        }
        if ($layout == 'restorefilter') {
            $this->_displayRestoreFilter($tpl);
            return;
        }
        // initialise variables
        $this->user = JFactory::getUser();
        $this->state = $this->get('State');
        $this->form = $this->get('Form');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.categories.php');
        require_once (JPATH_SITE . '/components/com_flexicontent/classes/flexicontent.helper.php');
        require_once (JPATH_SITE . '/components/com_flexicontent/helpers/route.php');

        $this->addToolbar();
        // ajout d'un script pour overider la validation joomla
        $validScript = "
		Joomla.submitform = function (pressbutton){
			Joomla.submitbutton(pressbutton);
		}
		Joomla.submitbutton = function(pressbutton){
			if (!pressbutton){
				pressbutton='export.filter';
			}
			form = document.adminForm;
			if(pressbutton != 'export.savefilter' &&  pressbutton != 'export.restorefilter') {
				if(form.params_type.value != '0') {
					if (pressbutton=='export.filter'){
						var postData = jQuery('#adminForm').serializeArray();
						jQuery.ajax({
							type:'POST',
							url:'index.php?option=com_fleximport&view=export&format=raw',
							data: postData,
							dataType: 'html'
						}).done(function(html){
							jQuery('#result_export').html(html);
							SqueezeBox.assign($$('#result_export a.modal'), {
								parse: 'rel'
							});
						});
					}else{
						form.task.value=pressbutton;
						form.submit();
					}
				} else {
					var typeImport = jQuery('#params_type').focus();
					alert('" . JText::_('COM_FLEXIMPORT_EXPORT_SELECT_A_TYPE', true) . "');
				}
			} else {
				if (pressbutton) {
					form.task.value=pressbutton;
				}
				form.submit();
			}
		}";
        $this->document->addScriptDeclaration($validScript);
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $user = JFactory::getUser();
        JToolBarHelper::title(JText::_('COM_FLEXIMPORT_EXPORT'), 'export');
        if ($user->authorise('fleximport.export', 'com_fleximport')) {
            $toolbar = JToolBar::getInstance();

            	JHTML::_('behavior.modal','button.modal');
            	$htmlButton ="<button class=\"btn btn-small modal\" href=\"index.php?option=com_fleximport&amp;view=export&amp;layout=savefilter&amp;tmpl=component\" rel=\"{handler: 'iframe', size: {x: 400, y: 400}}\">
					<span class=\"icon-export-savefilter\"></span>
					".JText::_('COM_FLEXIMPORT_EXPORT_FILTER_SAVE')."</button>";
                $toolbar->appendButton('Custom', $htmlButton );
            	$htmlButton ="<button class=\"btn btn-small modal\" href=\"index.php?option=com_fleximport&amp;view=export&amp;layout=restorefilter&amp;tmpl=component'\" rel=\"{handler: 'iframe', size: {x: 400, y: 400}}\">
					<span class=\"icon-export-restorefilter\"></span>
					".JText::_('COM_FLEXIMPORT_EXPORT_FILTER_RESTORE')."</button>";
            	$toolbar->appendButton('Custom', $htmlButton );


            JToolBarHelper::divider();
            JToolBarHelper::custom('export.filter', 'export-filter', 'export-filter', 'COM_FLEXIMPORT_EXPORT_FILTER', false);
            JToolBarHelper::divider();
            JToolBarHelper::custom('export.selection', 'export-selection', 'export-selection', 'COM_FLEXIMPORT_EXPORT_SELECTION' , true);
            JToolBarHelper::custom('export.all', 'export-all', 'export-all', 'COM_FLEXIMPORT_EXPORT_ALL' , false);
        }

        if ($user->authorise('core.admin', 'com_fleximport')) {
            JToolBarHelper::divider();
            JToolBarHelper::preferences('com_fleximport');
        }
    }

    function _displaySaveFilter($tpl = null)
    {
        $default = JText::_('COM_FLEXIMPORT_EXPORT_FILTER_DEFAULT_NAME') . date('Y_m_d');
        $css = '
			dt{
				display:none;
			}
			dd{
				margin:0;
			}
			ul li{
				height:40px;
				width:220px;
				overflow:hidden;
				list-style:none;
				padding-top:10px;
			}
		';
        $this->document->addStyleDeclaration($css);
        $this->assignRef('value_default' , $default);
        parent::display($tpl);
    }
    function _displayRestoreFilter($tpl = null)
    {
        jimport('joomla.filesystem.folder');
        $pathtofilters = JPATH_COMPONENT . '/classes/filters';
        $filterFiles = JFolder::files($pathtofilters, '.ini');
        $i = 0;
        $list = array();
        foreach ($filterFiles as $filterFile) {
            $list[$i]['name'] = substr($filterFile, 0, strlen($filterFile) - 4);
            $list[$i]['open'] = JUri::base() . 'index.php?option=com_fleximport&amp;view=export&amp;task=export.openfilter&amp;file=' . $filterFile;
            $list[$i]['delete'] = JUri::base() . 'index.php?option=com_fleximport&amp;view=export&amp;task=export.deletefilter&amp;layout=restorefilter&amp;tmpl=component&amp;file=' . $filterFile;
            $i++;
        }
        $this->items = $list;
        $css = '
			dt{
				display:none;
			}
			dd{
				margin:0;
			}
			ul li{
				height:40px;
				width:320px;
				overflow:hidden;
				list-style:none;
				padding-top:10px;
			}
		';
        $this->document->addStyleDeclaration($css);
        $js = "
		function getParent(url){
			window.parent.location.href = url;
		}";
        $this->document->addScriptDeclaration($js);
        parent::display($tpl);
    }
}