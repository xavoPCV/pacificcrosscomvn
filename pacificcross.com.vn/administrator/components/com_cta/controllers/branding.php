<?php
// No direct access
defined('_JEXEC') or die;

class CtaControllerBranding extends JControllerLegacy {
	
	
	function save() {
		
		#print_r($_POST);
		#exit;
		JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));
		
		$id = JRequest::getInt('id');
		$opacity = JRequest::getInt('opacity');
		
		if ($id) {
		
			$logo = $_FILES['watermark_logo'];
			
			define('PHOTOS_URL', 'components/com_cta/logo/');
			define('PHOTOS_DIR', JPATH_ROOT.'/'.PHOTOS_URL);
			if (!is_dir(PHOTOS_DIR)) mkdir(PHOTOS_DIR);
			
			if ($logo['name'] && !$logo['error']) {
				foreach (glob(JPATH_ROOT.'/'.PHOTOS_URL."*") as $filename) {
					unlink($filename);
				}//for
				if (move_uploaded_file($logo['tmp_name'], PHOTOS_DIR.$logo['name'])) {
					$desFile = $originalFile = PHOTOS_DIR.$logo['name'];
					$image = new JImage($originalFile);
					$properties = JImage::getImageFileProperties($originalFile);
					$resizedImage = $image->resize(100, 100, true);
					$return_result = $resizedImage->toFile($desFile, $properties->type);
					if (resizedImage) {
						$watermark_logo = $logo['name'];
					}//if
				}//if
			}//if
			
			$db = & JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->update('#__cta_setting');
			$query->set('`opacity` = '.(int)$opacity);
			if ($watermark_logo) $query->set('`watermark_logo` = '.$db->quote($watermark_logo));
			$query->where('`id` = '.(int)$id);
			$db->setQuery($query);
			$db->execute();
			
			#echo $query;
			#exit;
			
			$this->setRedirect(JRoute::_('index.php?option=com_cta&view=branding', false));
			return true;
			
		}//if
		
		
	}//func
}
