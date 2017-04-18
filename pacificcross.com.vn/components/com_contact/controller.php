<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Contact Component Controller
 *
 * @since  1.5
 */
class ContactController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JControllerLegacy  This object to support chaining.
	 *
	 * @since   1.5
	 */
	
	function savewcontact(){
            
            
            JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
            $post = JRequest::get('post');
            if ($post['cs_captchaResult'] != base64_decode($post['cs_captchaCode']) ){
                 
                  $this->setRedirect('index.php', $msg='Captcha wrong');
                  return false;
            }
            if ($post['cs_email'] == '' ) {
             
                   $this->setRedirect('index.php', $msg='Lost data');
                   return false;
            }
          //  $file = JRequest::getVar('uploadfile', null, 'files', 'array');
        //    if($file['size'] > 10000){
                
         ///         $this->setRedirect('index.php', $msg='File is larger');
         //        return false;
          //  }
          
            
                         $app = JFactory::getApplication();

			

			$mailfrom = $app->get('mailfrom');
			$fromname = $app->get('fromname');
			$sitename = $app->get('sitename');

			$name    = 'Custommer';
			$email   = JStringPunycode::emailToPunycode($post['mail_resent']);
			$subject = $post['cs_subject'].' - Custommer Submit Contacts From Widget ';
			$body    = '<br> <b>Product:</b> '.$post['cs_subject']. '<br> <b>Name:</b> '.$post['cs_name'].' <br> '.' <b>Email:</b> '.$post['cs_email'].' <br> <b>Message:</b> '.$post['cs_message'];

			// Prepare email body
			$prefix = JText::sprintf('COM_CONTACT_ENQUIRY_TEXT', JUri::base());
			$body   = $prefix . "\n" . $name . ' <' . $email . '>' . "\r\n\r\n" . stripslashes($body);

			$mail = JFactory::getMailer();
			$mail->addRecipient($post['mail_resent']);
			$mail->addReplyTo($email, $name);
			if ($$file['tmp_name'] != ''){
			//$mail->addAttachment($file['tmp_name']);
			}	
                        
			$mail->setSender(array($mailfrom, $fromname));
			$mail->setSubject($sitename . ': ' . $subject);
                        $mail->isHTML(true);
			$mail->setBody($body);
                        $mail->addBCC( "'".$post['mail_resent']."'" , 'HT');
			$sent = $mail->Send();
        
            $this->setMessage('Susscess');
		$this->setRedirect(JRoute::_('index.php'), JTEXT::_('SUCCESS') );
             return true;
        }


	
	public function display($cachable = false, $urlparams = array())
	{
		$cachable = true;

		// Set the default view name and format from the Request.
		$vName = $this->input->get('view', 'categories');
		$this->input->set('view', $vName);

		$safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT',
			'limit' => 'UINT', 'limitstart' => 'UINT', 'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING',
			'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN',
			'lang' => 'CMD');

		parent::display($cachable, $safeurlparams);

		return $this;
	}
	public function update() {
	
		$db = jfactory::getDBO();
		$query = $db->getquery(true);
		$query->select('*')->from('1_provider');
		$db->setquery($query);
		$data = $db->loadobjectlist(); 
		
		foreach ($data as $r) {
                // echo '<pre>'; print_r($r); echo "</pre>";   die;
                    // check same data
                    $query = $db->getquery(true);
                    $query->select('id')->from('#__content')->where('title like "%'.$r->name_vn.'%" or title like "%'.$r->name_vn.'" or title like "%'.$r->name_en.'%" or title like "%'.$r->name_en.'"  ' );
                    $db->setquery($query);
                    $data = $db->loadResult(); 
                    //echo $data."<br>";
                    if (!$data) {
                        
                        
                         echo '<pre>'; print_r($r); echo "</pre>";   die;
                    }
                }
	echo 'done';	
	die;
	}
}
