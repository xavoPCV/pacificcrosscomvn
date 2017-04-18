<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/**
 * Editor Image buton
 *
 * @package		Joomla.Plugin
 * @subpackage	Editors-xtd.image
 * @since 1.5
 */
class plgButtonfiles extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of (imageName, textToInsert)
	 */
	function onDisplay($name, $asset, $author)
	{
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_media');
		$user = JFactory::getUser();
		$extension = JRequest::getCmd('option');
		if ($asset == ''){
			$asset = $extension;
		}
		if (	file_exists(JPATH_ROOT.'/components/com_enewsletter/views/uploadfile/view.html.php'))
                    {
                        $getContent = $this->_subject->getContent($name);
                        $doc= jfactory::getDocument();
                        $config = new JConfig();
                        $edittor = $config->editor;     
                        if ($edittor == 'tinymce'){
                            $doc->addScriptDeclaration("     
                          
                                function  parentinsertfile(editor){   
                                var highlighted = tinyMCE.activeEditor.selection.getContent();
                                        if (highlighted.length > 1){
                                             jInsertEditorText('<a href='+editor+' > '+highlighted+'</a>','".$name."');
                                        }else {
                                             jInsertEditorText('<a href='+editor+' > '+editor+'</a>','".$name."');
                                        }                                  
                                    document.getElementById('sbox-btn-close').click();
                                }
                                function parentinsertfileclose (){
                                 document.getElementById('sbox-btn-close').click();
                                }
                            "); 
                        }elseif ($edittor == 'ckeditor'){
                            $doc->addScriptDeclaration("     
                          

                               


                                function  parentinsertfile(editor){   
                                  
                                    if(oEditor) 
                                     {
                                         if (CKEDITOR.env.ie && CKEDITOR.env.version > 10 && oEditor.ie11_bookmarks)
                                             oEditor.setBookmarks(oEditor.ie11_bookmarks);                                                      
                                     }
                                                     else
                                     {
                                     var oEditor = CKEDITOR.instances['$name'];
                                       if (CKEDITOR.env.ie && CKEDITOR.env.version > 10 && oEditor.ie11_bookmarks)
                                             oEditor.setBookmarks(oEditor.ie11_bookmarks);                                   
                                     }
                                    
                                    
                                    if (oEditor.getSelection().getNative() != '' ){
                                        jInsertEditorText('<a href='+editor+' > '+oEditor.getSelection().getNative()+'</a>','".$name."');
                                    }else {
                                        jInsertEditorText('<a href='+editor+' > '+editor+'</a>','".$name."');
                                    }
                                    document.getElementById('sbox-btn-close').click();
                                }
                                function parentinsertfileclose (){
                                 document.getElementById('sbox-btn-close').click();
                                }
                            "); 
                        }else{
                              $doc->addScriptDeclaration("     
                          
                                function  parentinsertfile(editor){   
                                    jInsertEditorText('<a href='+editor+' > '+editor+'</a>','".$name."');
                                    document.getElementById('sbox-btn-close').click();
                                }
                                function parentinsertfileclose (){
                                 document.getElementById('sbox-btn-close').click();
                                }
                            "); 
                        }
                      
                      
                        $doc->addStyleDeclaration("                        
                            .files .a {
                            
                            }
                            .button2-left .files{
                                    background: url(../plugins/editors-xtd/files/j_button1_refresh.png) 100% 0 no-repeat;
                                     background-size: 22px;
                            }
                        "); 

                            $link = '../index.php?option=com_enewsletter&view=uploadfile&paddcode='.md5(JURI::root().JURI::root());
                            JHtml::_('behavior.modal');
                            $button = new JObject;
                            $button->set('modal', true);
                            $button->set('link', $link);
                            $button->set('text', 'Upload File');
                            $button->set('name', 'files');
                            $button->set('style', 'width:20px;');
                            $button->set('options', "{handler: 'iframe', size: {x: 800, y: 650}}");
                            return $button;
                    }
		else
                    {
                            return false;
                    }
	}
}
