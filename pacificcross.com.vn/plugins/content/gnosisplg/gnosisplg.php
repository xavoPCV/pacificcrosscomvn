<?php

/*
 * @package Joomla 3.x
 * @copyright Copyright (C) 2013 hypermodern.org. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 *
 * @plugin Gnosis
 * @copyright Copyright (C) Lander Compton www.hypermodern.org
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentgnosisplg extends JPlugin {
	
	
	public function onContentPrepare($context, &$article, &$params, $limitstart) { 
				
		// get the paramters article and context				
		$this->gnosisplg_content( $article, $params, $context);
		$doc = &JFactory::getDocument();
		$gstyle = JURI::base();
		$doc->addStyleSheet($gstyle . 'plugins/content/gnosisplg/css/gstyle.css');
		
		$gpbackground = $this->params->get( 'gpbackground','#9FDAEE');
		$gpfontcolor = $this->params->get( 'gpfontcolor','#000000');
		$gpwidth = $this->params->get( 'gpwidth','250px');
		
		$gpborderthickness = $this->params->get( 'gpborderthickness','1px');
		$gpborderstyle = $this->params->get( 'gpborderstyle','solid');
		$gpbordercolor = $this->params->get( 'gpbordercolor','#2BB0D7');

		static $stylecnt = 0;
		
		if ($stylecnt == 0) {
			$style='.ginfo { background: ' . $gpbackground . '; padding: 5px; border: ' . $gpborderthickness. ' ' . $gpborderstyle . ' ' . $gpbordercolor. '; color: ' .	$gpfontcolor . '}';
			$style.='.gtooltip:hover span {width: ' . $gpwidth . ';}';
			$doc->addStyleDeclaration( $style );
			$stylecnt = $stylecnt + 1;
		}
	} // end function

	// remove the [[ ]] and | and replace with the display word. Prevents recursion inside the tooltip.
	public function gnosis_sanitize($regex, $sanitizethis)
	{
		preg_match_all( $regex, $sanitizethis, $matchespop);
					
		for($y=0; $y<count($matchespop[0]); $y++) {
			if (strpos($matchespop[0][$y],'|') == false) {

				$displaywordpop 	= 	$matchespop[1][$y];

			}
			else
			{
				$gargumentspop 	= 	explode('|', $matchespop[1][$y]);
				$displaywordpop 	= 	$gargumentspop[0];


			}
		$replacedef 	= $displaywordpop;
		$sanitizethis = str_replace($matchespop[0][$y], $replacedef, $sanitizethis);
		
	
		}
		return $sanitizethis;
	}
		
	// remove the leftover [[ and ]] and |	
	public function gnosis_sanitize2($sanitizethis)
	{
		$arrSearch = array('[',']', '|');
		$sanitizethis = str_replace($arrSearch, '', $sanitizethis);

		return $sanitizethis;
	}
		
	public function gnosis_addfields($definition, $examples, $etymology) {
		$gpdefshow = $this->params->get( 'gpdefshow',1);
		$gpexshow = $this->params->get( 'gpexshow',0);
		$gpetshow = $this->params->get( 'gpetshow',0);
		
		$addfields = "";
		
		if ($gpdefshow == 1) 	{ 
			if ($definition) {$addfields .= '<b>' . JText::_('PLG_GNOSIS_DEF_TITLE') . '  </b>' 	. $definition 	. '...<br />';}
		}
		
		if ($gpexshow == 1) 	{
			if ($examples) {$addfields .= '<b>' . JText::_('PLG_GNOSIS_EXA_TITLE') . '  </b>' . $examples 	. '...<br />';}
		}
			
		if ($gpetshow == 1) 	{
			if ($etymology) {$addfields .= '<b>' . JText::_('PLG_GNOSIS_ETY_TITLE') . '  </b>' 	. $etymology 	. '...<br />';}
		}
		return $addfields;
	}
	
	
	
	// found this fantastic solution for the replacement within replacement problem here:
	// http://php.net/manual/en/function.str-replace.php
	public function stro_replace($arrSearch, $arrReplace, $content)
	{
		//return strtr( $content, array_combine($arrSearch, $arrReplace) );
		
		$one = array_combine($arrSearch, $arrReplace);
		
		    $pos1 = 0;
            $product = $content;
            while(  count( $one ) > 0  ){
                $positions = array();
                foreach(  $one as $from => $to  ){
                    if(   (  $pos2 = stripos( $product, $from, $pos1 )  ) === FALSE   ){
                        unset(  $one[ $from ]  );
                    }
                    else{
                        $positions[ $from ] = $pos2;
                    }
                }
                if(  count( $one ) <= 0  )break;
                $winner = min( $positions );
                $key = array_search(  $winner, $positions  );
                $product = (   substr(  $product, 0, $winner  ) . $one[$key] . substr(  $product, ( $winner + strlen($key) )  )   );
                $pos1 = (  $winner + strlen( $one[$key] )  );
            }
            return $product;
		
		//return str_ireplace($arrSearch, $arrReplace, $content);
	}
	
	public function stro_replace1($arrSearch, $arrReplace, $content) {
	
		return strtr( $content, array_combine($arrSearch, $arrReplace) );
	}
	
	
	public function gnosisplg_content( &$article, &$params, $context = 'com_content.article') {
		

		
		// special thanks to philou for providing the french [fr-FR] gnosisplg translation
		// include the language files
		$lang = JFactory::getLanguage();
		$lang->load('plg_content_gnosisplg', JPATH_ADMINISTRATOR);
	
		

		 //diagnostic d'exclusion
		//$regex = '/\[\[([\w\s\p{L}|]*)\]\]/iu'; // added \p{L} and the u modifier to support languages
		$regex = '/\[\[([\w\s\p{L}|\'\`\’\-]*)\]\]/iu'; // added \p{L} and the u modifier to support languages
		
		
		
		
		// get the plugin parameters
		$gpdefsize = $this->params->get( 'gpdefsize',250);
		$gpexsize = $this->params->get( 'gpexsize',250);
		$gpetsize = $this->params->get( 'gpetsize',250);
		$gpmode = $this->params->get( 'gpmode',0);
		
		
		if ($gpdefsize == '0') 	{$gpdefsize = 3000;}
		if ($gpexsize == '0') 	{$gpexsize = 3000;}
		if ($gpetsize == '0') 	{$gpetsize = 3000;}
		
		// if in tag mode, use regex to find results
		if ($gpmode == 0) {

			
			
			// Search out all tags and put them into array $matcheshm.
			preg_match_all( $regex, $article->text, $matcheshm );
		
			//process the matches
			for($x=0; $x<count($matcheshm[0]); $x++) {
			
				// empty the variables
				$displayword  	= '';
				$definition		= '';
				$pronounciation	= '';
				$examples		= '';
				$etymology		= '';
				$searchword		= '';
				$wordid			= '';
				$replace 		= '';
				
				// If the tag does not include a bar, then assign displayword.
				if (strpos($matcheshm[0][$x],'|') == false) {
				
					$displayword 	= 	$matcheshm[1][$x];
					$searchword		=	$displayword;
				}
				else
				{
					$garguments 	= 	explode('|', $matcheshm[1][$x]);
					$displayword 	= 	$garguments[0];
					$searchword		=	$garguments[1];
			
				}
				
				// if the searchword is not numeric, then search the database for the word.
				if (!is_numeric($searchword)) {
				
					$db = JFactory::getDBO();
					$query = "SELECT id, word, pronounciation, definition, examples, etymology"
						. " FROM #__gnosis"
						. " WHERE word = " . $db->quote($searchword);
					$db->setQuery($query);
					$rows = $db->loadObjectList();
				}	
				
				// if the $searchword is numeric, search the database for an Id.
				else {
					$db = JFactory::getDBO();
					$query = "SELECT id, word, pronounciation, definition, examples, etymology"
						. " FROM #__gnosis"
						. " WHERE id = " . (int) $searchword;
					$db->setQuery($query);
					$rows = $db->loadObjectList();	
				}
					

				if (!empty($rows[0])) {
					$itemrow = $rows[0];

					
					if ($gpdefsize !== 0){ $definition = substr(strip_tags($this->gnosis_sanitize($regex, $itemrow->definition)), 0, $gpdefsize); }
					else {$definition 	= strip_tags($this->gnosis_sanitize($regex, $itemrow->definition)); }
					
					if ($gpexsize !== 0){ $examples = substr(strip_tags($this->gnosis_sanitize($regex, $itemrow->examples)), 0, $gpexsize); }
					else {$examples 	= strip_tags($this->gnosis_sanitize($regex, $itemrow->examples)); }
					
					if ($gpetsize !== 0){ $etymology = substr(strip_tags($this->gnosis_sanitize($regex, $itemrow->etymology)), 0, $gpetsize); }
					else {$etymology 	= strip_tags($this->gnosis_sanitize($regex, $itemrow->etymology)); }

					
					$wordid			= $itemrow->id;
					
					$wordlink 	= 'index.php?option=com_gnosis&view=word&id=' . $wordid;
					
					$addfields = $this->gnosis_addfields($definition, $examples, $etymology);
					
					if (!is_numeric($searchword)) {				
						$gtooltip	= '<span class="ginfo">' . "<em>$searchword</em>" 	. $addfields 	. '</span>';
					}
					
					else {
						$gtooltip	= '<span class="ginfo">' . "<em>$itemrow->word</em>" 	. $addfields 	. '</span>';
					}
					$replace 	= '<a class="gtooltip" href="' . JRoute::_($wordlink) . '"' . '>' . $displayword . $gtooltip . '</a>';
					$article->text = str_replace($matcheshm[0][$x], $replace, $article->text);
				}	
				
					
			} // end for loop
		} // end if tags	
		
		
		if ($gpmode == 1) {
			static $modecnt = 0;
			static $rows = array();
			
			$article->text = $this->gnosis_sanitize($regex, $article->text);
			
			if ($modecnt == 0) {
				$db = JFactory::getDBO();
				$query = "SELECT id, word, pronounciation, definition, examples, etymology"
					. " FROM #__gnosis";
				
				$db->setQuery($query);
				$rows = $db->loadObjectList();
				$modecnt = $modecnt + 1;
			}
			
			foreach ($rows as $row) {
			
				if ($gpdefsize !== 0){ $definition = substr(strip_tags($row->definition), 0, $gpdefsize); }
				else {$definition 	= strip_tags($row->definition); }
					
				if ($gpexsize !== 0){ $examples = substr(strip_tags($row->examples), 0, $gpexsize); }
				else {$examples 	= strip_tags($row->examples); }
					
				if ($gpetsize !== 0){ $etymology = substr(strip_tags($row->etymology), 0, $gpetsize); }
				else {$etymology 	= strip_tags($row->etymology); }
			
				$addfields = $this->gnosis_addfields($definition, $examples, $etymology);
				$gtooltip	= '<span class="ginfo">' . "<em>$row->word</em>" 	. $addfields 	. '</span>';
				$wordlink 	= 'index.php?option=com_gnosis&view=word&id=' . $row->id;
				$replace 	= '<a class="gtooltip" href="' . JRoute::_($wordlink) . '"' . '>' . $row->word . $gtooltip . '</a>';
				
				$arrReplace[] =  $replace;
				$arrSearch[] = $row->word;
				
				
			}
			
			// try to solve the replacement within replacement problem with the stro_replace functions
			$article->text = $this->stro_replace1($arrSearch, $arrReplace, $article->text);
			
			// remove tags from the tooltips
			$article->text = $this->gnosis_sanitize($regex, $article->text);
			
			// remove remaining [[ or ]]
			$article->text = $this->gnosis_sanitize2($article->text);
		}
	} // end function
	

	
} // end class
?>