<?php
/**
 * @version 1.5 stable $Id$
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
// first define the template name
$tmpl = $this->tmpl;
$user = JFactory::getUser();

$lead_use_image = $this->params->get('lead_use_image', 1);
$lead_link_image_to = $this->params->get('lead_link_image_to', 0);
$intro_use_image = $this->params->get('intro_use_image', 1);
$intro_link_image_to = $this->params->get('intro_link_image_to', 0);

// ITEMS as MASONRY tiles
if (!empty($this->items) && ($this->params->get('lead_placement', 0)==1 || $this->params->get('intro_placement', 0)==1))
{
	flexicontent_html::loadFramework('masonry');
	flexicontent_html::loadFramework('imagesLoaded');
	
	$js = "
		jQuery(document).ready(function(){
	";
	if ($this->params->get('lead_placement', 0)==1) {
		$js .= "
			var container_lead = document.querySelector('ul.leadingblock');
			var msnry_lead;
			// initialize Masonry after all images have loaded
			if (container_lead) {
				imagesLoaded( container_lead, function() {
					msnry_lead = new Masonry( container_lead );
				});
			}
		";
	}
	if ($this->params->get('intro_placement', 0)==1) {
		$js .= "
			var container_intro = document.querySelector('ul.introblock');
			var msnry_intro;
			// initialize Masonry after all images have loaded
			if (container_intro) {
				imagesLoaded( container_intro, function() {
					msnry_intro = new Masonry( container_intro );
				});
			}
		";
	}
	$js .= "	
		});
	";
	JFactory::getDocument()->addScriptDeclaration($js);
}
?>

<?php
if (!$this->items) {
	// No items exist
	if ($this->getModel()->getState('limit')) {
		// Not creating a category view without items
		echo '<div class="noitems group">' . JText::_( 'FLEXI_NO_ITEMS_FOUND' ) . '</div>';
	}
	return;
}

$items	= & $this->items;
$count 	= count($items);
// Calculate common data outside the item loops
if ($count) {
	$_read_more_about = JText::_( 'FLEXI_READ_MORE_ABOUT' );
	$tooltip_class = FLEXI_J30GE ? 'hasTooltip' : 'hasTip';
	$_comments_container_params = 'class="fc_comments_count '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_NUM_OF_COMMENTS', 'FLEXI_NUM_OF_COMMENTS_TIP', 1, 1).'"';
}
?>


<?php
$leadnum  = $this->params->get('lead_num', 1);
$leadnum  = ($leadnum >= $count) ? $count : $leadnum;

// Handle category block (start of category items)
$doing_cat_order = $this->category->_order_arr[1]=='order';
$lead_catblock  = $this->params->get('lead_catblock', 0);
$intro_catblock = $this->params->get('intro_catblock', 0);
$lead_catblock_title  = $this->params->get('lead_catblock_title', 1);
$intro_catblock_title = $this->params->get('intro_catblock_title', 1);
if ($lead_catblock || $intro_catblock) {
	global $globalcats;
}

// ONLY FIRST PAGE has leading content items
if ($this->limitstart != 0) $leadnum = 0;

$lead_cut_text  = $this->params->get('lead_cut_text', 400);
$intro_cut_text = $this->params->get('intro_cut_text', 200);
$uncut_length = 0;
FlexicontentFields::getFieldDisplay($items, 'text', $values=null, $method='display'); // Render 'text' (description) field for all items

if ($leadnum) :
	//added to intercept more columns (see also css changes)
	$lead_cols = $this->params->get('lead_cols', 2);
	$lead_cols_classes = array(1=>'one',2=>'two',3=>'three',4=>'four');
	$classnum = $lead_cols_classes[$lead_cols];
?>

	
		<?php
		if ($lead_use_image && $this->params->get('lead_image')) {
			$img_size_map   = array('l'=>'large', 'm'=>'medium', 's'=>'small', 'o'=>'original');
			$img_field_size = $img_size_map[ $this->params->get('lead_image_size' , 'l') ];
			$img_field_name = $this->params->get('lead_image');
		}
		for ($i=0; $i<$leadnum; $i++) :
			$item = $items[$i];
			$fc_item_classes = '';
			if ($doing_cat_order)
     		$fc_item_classes .= ($i==0 || ($items[$i-1]->rel_catid != $items[$i]->rel_catid) ? '' : '');
			$fc_item_classes .= $i%2 ? 'right' : 'left'; // fceven became right and fcodd became left
			$fc_item_classes .= '';
			
			$markup_tags = '<span class="fc_mublock">';
			foreach($item->css_markups as $grp => $css_markups) {
				if ( empty($css_markups) )  continue;
				$fc_item_classes .= ' fc'.implode(' fc', $css_markups);
				
				$ecss_markups  = $item->ecss_markups[$grp];
				$title_markups = $item->title_markups[$grp];
				foreach($css_markups as $mui => $css_markup) {
					$markup_tags .= '<span class="fc_markup mu' . $css_markups[$mui] . $ecss_markups[$mui] .'">' .$title_markups[$mui]. '</span>';
				}
			}
			$markup_tags .= '</span>';
			
			$custom_link = null;
			if ($lead_use_image) :
				if (!empty($img_field_name)) {
					// render method 'display_NNNN_src' to avoid CSS/JS being added to the page
					/* $src = */FlexicontentFields::getFieldDisplay($item, $img_field_name, $values=null, $method='display_'.$img_field_size.'_src');
					$img_field = & $item->fields[$img_field_name];
					$src = str_replace(JURI::root(), '', @ $img_field->thumbs_src[$img_field_size][0] );
					if ( $lead_link_image_to && isset($img_field->value[0]) ) {
						$custom_link = ($v = unserialize($img_field->value[0])) !== false ? @ $v['link'] : @ $img_field->value[0]['link'];
					}
				} else {
					$src = flexicontent_html::extractimagesrc($item);
				}
				
				$RESIZE_FLAG = !$this->params->get('lead_image') || !$this->params->get('lead_image_size');
				if ( $src && $RESIZE_FLAG ) {
					// Resize image when src path is set and RESIZE_FLAG: (a) using image extracted from item main text OR (b) not using image field's already created thumbnails
					$w		= '&amp;w=' . $this->params->get('lead_width', 200);
					$h		= '&amp;h=' . $this->params->get('lead_height', 200);
					$aoe	= '&amp;aoe=1';
					$q		= '&amp;q=95';
					$zc		= $this->params->get('lead_method') ? '&amp;zc=' . $this->params->get('lead_method') : '';
					$ext = pathinfo($src, PATHINFO_EXTENSION);
					$f = in_array( $ext, array('png', 'ico', 'gif') ) ? '&amp;f='.$ext : '';
					$conf	= $w . $h . $aoe . $q . $zc . $f;
					
					$base_url = (!preg_match("#^http|^https|^ftp|^/#i", $src)) ?  JURI::base(true).'/' : '';
					$thumb = JURI::base(true).'/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src='.$base_url.$src.$conf;
				} else {
					// Do not resize image when (a) image src path not set or (b) using image field's already created thumbnails
					$thumb = $src;
				}
			endif;
			$link_url = $custom_link ? $custom_link : JRoute::_(FlexicontentHelperRoute::getItemRoute($item->slug, $item->categoryslug, 0, $item));
		?>
		
		<?php echo $lead_catblock ?
			'<li class="lead_catblock">'
				.($lead_catblock_title && @$globalcats[$item->rel_catid] ? $globalcats[$item->rel_catid]->title : '').
			'</li>' : ''; ?>		
		<div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-<?php echo $fc_item_classes; ?>">
			<div class="news-box">
				<div class="news-box-title">
					<?php
						$header_shown =
							$this->params->get('show_comments_count', 1) ||
							$this->params->get('show_title', 1) || $item->event->afterDisplayTitle ||
							0; // ...
					?>
					
					<?php if ($this->params->get('show_title', 1)) : ?>
						<h3>
							<?php if ($this->params->get('link_titles', 0)) : ?>
							<a href="<?php echo $link_url; ?>" title="<?php echo $item->title; ?>"><?php echo substr($item->title, 0, 65); $str = $item->title; if (strlen($str) > 65) echo '...';?></a>
							<?php
							else :
							echo $item->title;
							endif;
							?>
						</h3>
					<?php endif; ?>
					<?php if (isset($item->positions['topic-icon'])) : ?>
						<?php foreach ($item->positions['topic-icon'] as $field) : ?>
							<?php echo $field->display; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div><!-- end of news-box-title -->
			
				<div class="news-box-meta">
                    <div class="pure-g">
                        <div class="pure-u-1-2">
                            <?php if (isset($item->positions['date-created'])) : ?>
								<?php foreach ($item->positions['date-created'] as $field) : ?>
									<i class="ion ion-ios-clock"></i> <?php echo $field->display; ?>
								<?php endforeach; ?>
							<?php endif; ?>
                        </div>
                        <div class="pure-u-1-2">
                            <?php if (isset($item->positions['tags'])) : ?>
								<?php foreach ($item->positions['tags'] as $field) : ?>
									<i class="ion ion-pricetags"></i> <?php echo $field->display; ?>
								<?php endforeach; ?>
							<?php endif; ?>
                        </div>
                    </div><!-- end of pure-g --> 
                </div><!-- end of news-box-meta -->
			
				<div class="news-box-intro">
					<p>
						<?php
							//FlexicontentFields::getFieldDisplay($item, 'text', $values=null, $method='display');
							if ($this->params->get('lead_strip_html', 1)) :
								echo flexicontent_html::striptagsandcut( $item->fields['text']->display, $lead_cut_text, $uncut_length );
							else :
								echo $item->fields['text']->display;
							endif;
						?>
					</p>
					<?php
						$readmore_forced = $this->params->get('show_readmore', 1) == -1 || $this->params->get('lead_strip_html', 1) == 1 ;
						$readmore_shown  = $this->params->get('show_readmore', 1) && ($uncut_length > $lead_cut_text || strlen(trim($item->fulltext)) >= 1);
						$readmore_shown  = $readmore_shown || $readmore_forced;
						$footer_shown = $readmore_shown || $item->event->afterDisplayContent;
					?>

					<?php if ( $readmore_shown ) : ?>
						<a href="<?php echo $link_url; ?>" title="<?php echo JText::_('FLEXI_READ_MORE_ABOUT'); ?>: <?php echo $item->title; ?>" class="pure-u-1 pure-button" rel="nofollow">
							<?php echo ' ' . ($item->params->get('readmore')  ?  $item->params->get('readmore') : JText::sprintf('FLEXI_READ_MORE', $item->title)); ?>
						</a>
					<?php endif; ?>
				</div><!-- end of news-box-intro -->
			</div><!-- end of news-box -->
		</div><!-- end of pure-u-1 pure-u-sm-1-2 pure-sm-padding- -->
		<?php endfor; ?>

<?php endif; ?>

<?php
if ($this->limitstart != 0) $leadnum = 0;
if ($count > $leadnum) :
	//added to intercept more columns (see also css changes)
	$intro_cols = $this->params->get('intro_cols', 2);
	$intro_cols_classes = array(1=>'one',2=>'two',3=>'three',4=>'four');
	$classnum = $intro_cols_classes[$intro_cols];
?>

		<?php
		if ($intro_use_image && $this->params->get('intro_image')) {
			$img_size_map   = array('l'=>'large', 'm'=>'medium', 's'=>'small', 'o'=>'original');
			$img_field_size = $img_size_map[ $this->params->get('intro_image_size' , 'l') ];
			$img_field_name = $this->params->get('intro_image');
		}
		
		for ($i=$leadnum; $i<$count; $i++) :
			$item = $items[$i];
			$fc_item_classes = '';
			if ($doing_cat_order)
     		$fc_item_classes .= ($i==0 || ($items[$i-1]->rel_catid != $items[$i]->rel_catid) ? '' : '');
			$fc_item_classes .= ($i-$leadnum)%2 ? 'right' : 'left'; // fceven became right and fcodd became left
			$fc_item_classes .= '';
			
			$markup_tags = '<span class="fc_mublock">';
			foreach($item->css_markups as $grp => $css_markups) {
				if ( empty($css_markups) )  continue;
				$fc_item_classes .= ' fc'.implode(' fc', $css_markups);
				
				$ecss_markups  = $item->ecss_markups[$grp];
				$title_markups = $item->title_markups[$grp];
				foreach($css_markups as $mui => $css_markup) {
					$markup_tags .= '<span class="fc_markup mu' . $css_markups[$mui] . $ecss_markups[$mui] .'">' .$title_markups[$mui]. '</span>';
				}
			}
			$markup_tags .= '</span>';
			
			$custom_link = null;
			if ($intro_use_image) :
				if (!empty($img_field_name)) {
					// render method 'display_NNNN_src' to avoid CSS/JS being added to the page
					/* $src = */FlexicontentFields::getFieldDisplay($item, $img_field_name, $values=null, $method='display_'.$img_field_size.'_src');
					$img_field = & $item->fields[$img_field_name];
					$src = str_replace(JURI::root(), '', @ $img_field->thumbs_src[$img_field_size][0] );
					if ( $intro_link_image_to && isset($img_field->value[0]) ) {
						$custom_link = ($v = unserialize($img_field->value[0])) !== false ? @ $v['link'] : @ $img_field->value[0]['link'];
					}
				} else {
					$src = flexicontent_html::extractimagesrc($item);
				}
				
				$RESIZE_FLAG = !$this->params->get('intro_image') || !$this->params->get('intro_image_size');
				if ( $src && $RESIZE_FLAG ) {
					// Resize image when src path is set and RESIZE_FLAG: (a) using image extracted from item main text OR (b) not using image field's already created thumbnails
					$w		= '&amp;w=' . $this->params->get('intro_width', 200);
					$h		= '&amp;h=' . $this->params->get('intro_height', 200);
					$aoe	= '&amp;aoe=1';
					$q		= '&amp;q=95';
					$zc		= $this->params->get('intro_method') ? '&amp;zc=' . $this->params->get('intro_method') : '';
					$ext = pathinfo($src, PATHINFO_EXTENSION);
					$f = in_array( $ext, array('png', 'ico', 'gif') ) ? '&amp;f='.$ext : '';
					$conf	= $w . $h . $aoe . $q . $zc . $f;
					
					$base_url = (!preg_match("#^http|^https|^ftp|^/#i", $src)) ?  JURI::base(true).'/' : '';
					$thumb = JURI::base(true).'/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src='.$base_url.$src.$conf;
				} else {
					// Do not resize image when (a) image src path not set or (b) using image field's already created thumbnails
					$thumb = $src;
				}
			endif;
			$link_url = $custom_link ? $custom_link : JRoute::_(FlexicontentHelperRoute::getItemRoute($item->slug, $item->categoryslug, 0, $item));
		?>
		
		<?php echo $intro_catblock ?
			'<li class="intro_catblock">'
				.($intro_catblock_title && @$globalcats[$item->rel_catid] ? $globalcats[$item->rel_catid]->title : '').
			'</li>' : ''; ?>
		
		<div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-<?php echo $fc_item_classes; ?>">
			<div class="news-box">
                <div class="news-box-title">
					<?php
						$header_shown =
							$this->params->get('show_comments_count', 1) ||
							$this->params->get('show_title', 1) || $item->event->afterDisplayTitle ||
							0; // ...
					?>

					<?php if ($this->params->get('show_title', 1)) : ?>
						<h3>
							<?php if ($this->params->get('link_titles', 0)) : ?>
							<a href="<?php echo $link_url; ?>" title="<?php echo $item->title; ?>"><?php echo substr($item->title, 0, 65); $str = $item->title; if (strlen($str) > 65) echo '...';?></a>
							<?php
							else :
							echo $item->title;
							endif;
							?>
						</h3>
					<?php endif; ?>
					<?php if (isset($item->positions['topic-icon'])) : ?>
						<?php foreach ($item->positions['topic-icon'] as $field) : ?>
							<?php echo $field->display; ?>
						<?php endforeach; ?>
					<?php endif; ?>
                </div><!-- end of news-box-title -->

                <div class="news-box-meta">
                    <div class="pure-g">
                        <div class="pure-u-1-2">
                            <?php if (isset($item->positions['date-created'])) : ?>
								<?php foreach ($item->positions['date-created'] as $field) : ?>
									<i class="ion ion-ios-clock"></i> <?php echo $field->display; ?>
								<?php endforeach; ?>
							<?php endif; ?>
                        </div>
                        <div class="pure-u-1-2">
                            <?php if (isset($item->positions['tags'])) : ?>
								<?php foreach ($item->positions['tags'] as $field) : ?>
									<i class="ion ion-pricetags"></i> <?php echo $field->display; ?>
								<?php endforeach; ?>
							<?php endif; ?>
                        </div>
                    </div><!-- end of pure-g --> 
                </div><!-- end of news-box-meta -->

                <div class="news-box-intro">
                    <p>
						<?php
							//FlexicontentFields::getFieldDisplay($item, 'text', $values=null, $method='display');
							if ($this->params->get('intro_strip_html', 1)) :
								echo flexicontent_html::striptagsandcut( $item->fields['text']->display, $intro_cut_text, $uncut_length );
							else :
								echo $item->fields['text']->display;
							endif;
						?>
					</p>
                    <?php
						$readmore_forced = $this->params->get('show_readmore', 1) == -1 || $this->params->get('intro_strip_html', 1) == 1 ;
						$readmore_shown  = $this->params->get('show_readmore', 1) && ($uncut_length > $intro_cut_text || strlen(trim($item->fulltext)) >= 1);
						$readmore_shown  = $readmore_shown || $readmore_forced;
						$footer_shown = $readmore_shown || $item->event->afterDisplayContent;
					?>
					<?php if ( $readmore_shown ) : ?>
						<a href="<?php echo $link_url; ?>" title="<?php echo JText::_('FLEXI_READ_MORE_ABOUT'); ?>: <?php echo $item->title; ?>" class="pure-u-1 pure-button" rel="nofollow">
						<?php
						if ($item->params->get('readmore')) :
							echo ' ' . $item->params->get('readmore');
						else :
							echo ' ' . JText::sprintf('FLEXI_READ_MORE', $item->title);
						endif;
						?>
						</a>
					<?php endif; ?>
                </div><!-- end of news-box-intro -->
			</div><!-- end of news-box -->
		</div><!-- end of pure-u-1 pure-u-sm-1-2 pure-sm-padding- -->
		<?php endfor; ?>

<?php endif; ?>