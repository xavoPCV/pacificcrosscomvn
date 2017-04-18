<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$catid = JRequest::getInt('catid');

$gg = 1;
$ccc = 0;
$answer_array = array();

foreach ($list as $item) :

	
	//if ($ccc==2) break;
	$ccc++;
?>
	<li id="liid<? echo $gg; $gg++;?>" class="NavLeftUL_item">
    <a href="#" class="NavLeftUL_anchor" onclick="return false;"><span class="catTitle"><?php echo $item->title;?><span class="NavLeftUL_navIcon fa fa-chevron-right"></span></span></a>
    <?php
		#HT
		$max_len = 10;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')->from('#__faq_faqs')->where('faq_category_id = '.$item->id)->where('`state` = 1')->order('faq_date desc');
		$db->setQuery($query, 0, $max_len);
		$rows_faq = $db->loadObjectList();
		if ($rows_faq) {
			echo '<ul class="NavLeftUL_sublist level1">';
			$back1 = $gg;
			?>
			<li id="backliid<?php echo $back1;?>" class="NavLeftUL_backItem"><a href="#" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span>Back<span class="NavLeftUL_navBackIcon fa fa-mail-reply"></span></span></a></li>
			<?php
			foreach ($rows_faq as $row_faq) {
			
				
				$answer_array[$row_faq->id] = $row_faq->faq_answer;
			
				?>
                <li id="liid<? echo $gg; $gg++;?>" class="NavLeftUL_item">
        		<a href="#answer<?php echo $row_faq->id;?>" class="NavLeftUL_anchor faq_question" onclick="return false;"><span class="catTitle"><?php echo $row_faq->faq_question;?><span class="NavLeftUL_navIcon fa fa-chevron-right"></span></span></a>
                	<ul class="NavLeftUL_sublist level2">
						<?php
						$back2 = $gg;
						?>
    					<li id="backliid<?php echo $back2;?>" class="NavLeftUL_backItem"><a href="#" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span>Back<span class="NavLeftUL_navBackIcon fa fa-mail-reply"></span></span></a></li>
						<li id="liid<?php echo $gg; $gg++;?>" class="NavLeftUL_item NavLeftUL_endpoint">
							<div id="answer<?php echo $row_faq->id;?>_liid" class="answer_placeholder"></div>
    					</li><!--NavLeftUL_item-->
    				</ul><!--level2-->
				</li><!--NavLeftUL_item-->
			<?php
			}//for
			?>
            
            <?php
			echo "</ul><!--level1-->";
		}//if
		?>
	</li><!--NavLeftUL_item-->
<?php endforeach; ?>




<!--<a href="#" class="NavLeftUL_anchor" onclick="return false;"><span class="catTitle"><?php //echo strip_tags($row_faq->faq_answer);?><?php //echo $row_faq->faq_answer;?><span class="NavLeftUL_endpointIcon"></span></span></a>-->