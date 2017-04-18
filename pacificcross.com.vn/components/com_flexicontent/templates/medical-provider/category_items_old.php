<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

if (strtolower(JFactory::getLanguage()->getTag()) == 'vi-vn' ){
    $tagmap[0] = 'Bệnh Viện';
    $tagmap[1] = 'Phòng Khám';
    JFactory::getDocument()->addScriptDeclaration('jQuery(document).ready(function(){ jQuery(".page-title").html("Thông Tin Bệnh Viện, Phòng Khám");   });');
    
    $linktext = 'Xem tất cả danh sách bệnh viện , phòng khám';
    $notetext = 'Chú ý: ký hiệu giá trên bản đồ được thiết kế để cung cấp khái quát về chi phí khám chữa bệnh. Pacific Cross Vietnam không đảm bảo giá cả thực tế tại mỗi nơi';       
}else {
    $tagmap[0] = 'Hospital';
    $tagmap[1] = 'Clinic';
    $linktext = 'View our complete list of medical providers';
    $notetext = 'Note: price symbols on the map are designed to give a general indication of costs only. Pacific Cross Vietnam cannot guarantee actual pricing at each medical provider';       
}
$db = jfactory::getdbo();
$sql = "select (select a.value from #__flexicontent_fields_item_relations a where c.id  =  a.item_id and a.field_id = 261 ) as coun , ( select b.value from #__flexicontent_fields_item_relations b where c.id  =  b.item_id and b.field_id = 258 ) as city from #__content as c  ";
$db->setquery($sql);
$data =  $db->loadobjectlist();
$temp = '';
foreach ($data as $r) {

	if($r->coun != '' && $r->city != '' ){
		
		$lis[$r->coun][] = $r->city;
	
	}

}
$lis['Thailand'] = array_unique($lis['Thailand']);
$lis['Vietnam'] = array_unique($lis['Vietnam']);


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
JFactory::getDocument()->addStyleSheet(JURI::base(true).'/components/com_flexicontent/assets/css/style.clr.css');
JFactory::getDocument()->addStyleSheet(JURI::base(true).'/components/com_flexicontent/assets/js/scrollbar/jquery.mCustomScrollbar.css');
JFactory::getDocument()->addScript(JURI::base(true).'/components/com_flexicontent/assets/js/scrollbar/jquery.mCustomScrollbar.js');

?>


<style>

    .mCSB_container .active {
        background: #ccc;
    }    
    input.fc_text_filter {
        height: 30px;
    }
    @media screen and (min-width: 35.5em) {
        .pure-u-sm-1-3 {
            width: 10%;
            padding: 5px;
        }
        .pure-u-sm-2-3 {
            width: 30%;
            padding: 5px;
        }
    }
    @media screen and (max-width: 600px) {
        #losr {
            clear: left;
        }
    }
    .fc_buttons {
               width: 305px;
    float: left;
    margin-top: -14px;
    display: block!important;
    }
</style>
<script>
	
	
	jQuery( document ).ready(function($) {
  
	$("#adminForm_261_val").append(
                
                $("#adminForm_261_val option").remove().sort(function(a, b) {
		var at = $(a).text(), bt = $(b).text();
		if(at == '- Country -' || bt == '- Country -' ){
		return (at > bt)?1:((at < bt)?-1:0);
		}else{
		return (at < bt)?1:((at > bt)?-1:0);
		}
	}));
        <?php if (!isset($_GET['filter_261'])){ ?>
                $("#adminForm_261_val").val('');	
        <?php } ?>
	
	$("#adminForm_261_val").change( function () {   
	
		

		if ($(this).val() == '') {
		      $("#adminForm_258_val").empty();
	          $("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Vietnam'] as $r1 ) { ?>
			
			$("#adminForm_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
			
			<?php foreach ($lis['Thailand'] as $r1 ) { ?>
			
			$("#adminForm_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
		
		
                } else if( $(this).val() == 'Vietnam'  ) {
			
			$("#adminForm_258_val").empty();
	          $("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Vietnam'] as $r1 ) { ?>
			
			$("#adminForm_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
		
		} else if( $(this).val() == 'Thailand' ) {
			$("#adminForm_258_val").empty();
	          $("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach ($lis['Thailand'] as $r1 ) { ?>
			
			$("#adminForm_258_val").append('<option value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');
		
			<?php } ?>
		
		}
	
	});

            <?php if( $_GET['filter_261'] == 'Vietnam' ){ ?> 
            
                    $("#adminForm_258_val").empty();
                     $("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
                                <?php foreach ($lis['Vietnam'] as $r1 ) { ?>

                                $("#adminForm_258_val").append('<option <?php if( urldecode($_GET['filter_258']) == $r1 ){ echo 'selected="selected"'; } ?>  value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');

                                <?php } ?>
            <? }  ?>
                
                
                
                
                 <?php if( $_GET['filter_261'] == 'Thailand' ){ ?>
                        $("#adminForm_258_val").empty();
                      $("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
                            <?php foreach ($lis['Thailand'] as $r1 ) { ?>

                            $("#adminForm_258_val").append('<option <?php if( urldecode($_GET['filter_258']) == $r1 ){ echo 'selected="selected"'; } ?>  value="<?php echo $r1; ?>" ><?php echo $r1; ?></option>');

                            <?php } ?>

  
                 <?php }  ?>
});
	
</script>
   
<script>
jQuery( document ).ready(function($) {

    $('.fc_filter_set .pure-u-sm-1-3').hide();
  
    
     $('#namesr').click(function (){
          $(".fc_filter_text_search").show();
         
          $(".fc_filter_set .pure-u-sm-1-3").hide();
         
     });
     
      $('#losr').click(function (){
          
           $(".fc_filter_text_search").hide();
             $(".fc_filter_set .pure-u-sm-1-3").show();
        
         
     });
    
});
        
        
</script>
<div class="barsearch">
    <input type="radio" name="aaaa" id="namesr" checked="checked" style="width:50px;float: left;   " /> <label style="width:175px;float: left;    font-size: 18px;"><?php echo JText::_( 'FLEXI_NAME_SEARCH' ); ?></label>
    <input type="radio" name="aaaa" id="losr" style="width:50px;float: left" /> <label style="width:175px;float: left; font-size: 18px;" > <?php echo JText::_( 'FLEXI_LOCATION_SEARCH' ); ?>  </label>
    <br>
<?php include('listings_filter_form.php'); ?>
</div>

<div class="infoside sameheight" >
    <div style="padding-left: 10px;" >
    <a href="#" onclick="jQuery('.provider-list-box').show();" ><?php echo $linktext; ?></a><br>
    <small> <?php echo $notetext; ?>  </small>
    </div>
<?php
	ob_start();
	// Form for (a) Text search, Field Filters, Alpha-Index, Items Total Statistics, Selectors(e.g. per page, orderby)
	// If customizing via CSS rules or JS scripts is not enough, then please copy the following file here to customize the HTML too
	
	$filter_form_html = trim(ob_get_contents());
	ob_end_clean();
	if ( $filter_form_html ) {
		echo '<div class="group">'."\n".$filter_form_html."\n".'</div><!--group-->';
	}//if
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
		<div class="provider-list-box bkg-white pall-15px bloc<?php echo $item->id; ?>">
			<?php
				$header_shown = $this->params->get('show_comments_count', 1) || $this->params->get('show_title', 1) || $item->event->afterDisplayTitle || 0; // ...
			?>
			<?php if ($this->params->get('show_title', 1)) : ?>
				<h3 id="providerTitle_<?php echo $item->id; ?>">
					<?php if ($this->params->get('link_titles', 0)) : ?>
					<a href="<?php echo $link_url; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
					<?php
					else :
					echo $item->title;
					endif;
					?>
				</h3>
			<?php endif; ?>
            <div class="pure-g">
                <div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-left">
                    <?php // print_r($item->positions);die; ?>
                	<h4><?php echo JText::_( 'FLEXI_CONTACT_INFO' ); ?> </h4>
                	<p>
                    
					<span class="providerAddress" id="providerAddress_<?php echo $item->id;?>">
					<?php if (isset($item->positions['providerAddress'])) : ?>
					<?php foreach ($item->positions['providerAddress'] as $field) : ?>
						<?php echo $field->display; ?>,
					<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($item->positions['providerDistrict'])) : ?>
						<?php foreach ($item->positions['providerDistrict'] as $field) : ?>
							<?php echo $field->display; ?>, 
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($item->positions['providerCity'])) : ?>
						<?php foreach ($item->positions['providerCity'] as $field) : ?>
							<?php echo $field->display; ?>, 
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($item->positions['providerStateProvince'])) : ?>
						<?php foreach ($item->positions['providerStateProvince'] as $field) : ?>
							<?php echo $field->display; ?>, 
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($item->positions['providerCountry'])) : ?>
						<?php foreach ($item->positions['providerCountry'] as $field) : ?>
							<?php echo $field->display; ?><br>
						<?php endforeach; ?>
					<?php endif; ?>
					</span>
					
					<span class="providerPhone" id="providerPhone_<?php echo $item->id;?>">
					<?php if (isset($item->positions['providerPhone'])) : ?>
						<?php foreach ($item->positions['providerPhone'] as $field) : ?>
							Phone: <?php echo $field->display; ?><br>
						<?php endforeach; ?>
					<?php endif; ?>
					</span>
                                       <span style="display: none;" class="providerType" id="medicalType_<?php echo $item->id;?>">
					<?php if (isset($item->positions['MedicalType'])) : ?>
						<?php foreach ($item->positions['MedicalType'] as $field) : ?>
							<?php echo $field->display; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					</span>
                            
                            
                            
                            
                           
					
					<?php if (isset($item->positions['providerFax'])) : ?>
						<?php foreach ($item->positions['providerFax'] as $field) : ?>
							Fax: <?php echo $field->display; ?><br>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if (isset($item->positions['providerEmail'])) : ?>
						<?php foreach ($item->positions['providerEmail'] as $field) : ?>
							Email: <a href="mailto:<?php echo $field->display; ?>" title="Email <?php echo $field->display; ?>"><?php echo $field->display; ?></a><br>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (isset($item->positions['providerWebsite'])) : ?>
						<?php foreach ($item->positions['providerWebsite'] as $field) : ?>
							Website: <a href="http://<?php echo $field->display; ?>" title="Visit <?php echo $field->display; ?>" target="_blank"><?php echo $field->display; ?></a>
						<?php endforeach; ?>
					<?php endif; ?>
                    </p>
					
					
					
					<?php
					#HT
					foreach ($item->positions['providerLatitude'] as $field) {
						echo "<span style='display:none;' class='providerLatitude' id='providerLatitude_$item->id'>$field->display</span>";
					}//for
					foreach ($item->positions['providerLongitude'] as $field) {
						echo "<span style='display:none;' class='providerLongitude' id='providerLongitude_$item->id'>$field->display</span>";
					}//for
					?>
                </div><!-- end of pure-u-1 pure-u-sm-1-2 -->
                <div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-right">
                    <h4><?php echo JText::_( 'FLEXI_OPEN_HOURS' ); ?> </h4>
                    <p>
                        <?php if (isset($item->positions['providerFromDay1'])) : ?>
							<?php foreach ($item->positions['providerFromDay1'] as $field) : ?>
								<?php echo $field->display; ?> - 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerToDay1'])) : ?>
							<?php foreach ($item->positions['providerToDay1'] as $field) : ?>
								<?php echo $field->display; ?> 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerOpeningHours1'])) : ?>
							<?php foreach ($item->positions['providerOpeningHours1'] as $field) : ?>
								: <?php echo $field->display; ?> - 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerClosingHours1'])) : ?>
							<?php foreach ($item->positions['providerClosingHours1'] as $field) : ?>
								<?php echo $field->display; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						
						<?php if (isset($item->positions['providerFromDay2'])) : ?>
							<?php foreach ($item->positions['providerFromDay2'] as $field) : ?>
								<?php echo $field->display; ?> 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerToDay2'])) : ?>
							<?php foreach ($item->positions['providerToDay2'] as $field) : ?>
								- <?php echo $field->display; ?> 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerOpeningHours2'])) : ?>
							<?php foreach ($item->positions['providerOpeningHours2'] as $field) : ?>
								: <?php echo $field->display; ?> - 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerClosingHours2'])) : ?>
							<?php foreach ($item->positions['providerClosingHours2'] as $field) : ?>
								<?php echo $field->display; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<br>
                        <?php echo JText::_( 'FLEXI_EMERGENCY' ); ?> : 24/7<br>
			
			 <span class="providerAmount"  id="providerAmount_<?php echo $item->id;?>">
                                                        <?php if (isset($item->positions['providersFee'])) : ?>
                                                                <?php foreach ($item->positions['providersFee'] as $field) : ?>
                                                                        <?php echo JText::_( 'FLEXI_FEE' ); ?> : <?php echo $field->display; ?><br>
                                                                <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        </span>
                    </p>
                    <p>
                    	<?php
							$readmore_forced = $this->params->get('show_readmore', 1) == -1 || $this->params->get('intro_strip_html', 1) == 1 ;
							$readmore_shown  = $this->params->get('show_readmore', 1) && ($uncut_length > $intro_cut_text || strlen(trim($item->fulltext)) >= 1);
							$readmore_shown  = $readmore_shown || $readmore_forced;
							$footer_shown = $readmore_shown || $item->event->afterDisplayContent;
						?>
						<?php if ( $readmore_shown ) : ?>
							<p><a href="<?php echo $link_url; ?>" title="<?php echo JText::_('FLEXI_READ_MORE_ABOUT'); ?>: <?php echo $item->title; ?>" class="pure-button pure-u-1" target="_blank">
							<?php
							if ($item->params->get('readmore')) :
								echo ' ' . $item->params->get('readmore');
							else :
								echo ' ' . JText::sprintf('FLEXI_READ_MORE', $item->title);
							endif;
							?>
							</a></p>
						<?php endif; ?>
                    </p>
                </div><!-- end of pure-u-1 pure-u-sm-1-2 -->
            </div><!-- end of pure-g -->
        </div><!-- end of provider-list-box -->
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
		
		<div class="provider-list-box bkg-white pall-15px bloc<?php echo $item->id; ?>">
			<?php
				$header_shown =
					$this->params->get('show_comments_count', 1) ||
					$this->params->get('show_title', 1) || $item->event->afterDisplayTitle ||
					0; // ...
			?>
			<?php if ($this->params->get('show_title', 1)) : ?>
				<h3 id="providerTitle_<?php echo $item->id; ?>">
					<?php if ($this->params->get('link_titles', 0)) : ?>
					<a href="<?php echo $link_url; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
					<?php
					else :
					echo $item->title;
					endif;
					?>
				</h3>
			<?php endif; ?>
            <div class="pure-g">
                <div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-left">
                	<h4><?php echo JText::_( 'FLEXI_CONTACT_INFO' ); ?> </h4>
                	<p>
                        <span class="providerAddress" id="providerAddress_<?php echo $item->id; ?>">
						<?php if (isset($item->positions['providerAddress'])) : ?>
						<?php foreach ($item->positions['providerAddress'] as $field) : ?>
							<?php echo $field->display; ?>,
						<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerDistrict'])) : ?>
							<?php foreach ($item->positions['providerDistrict'] as $field) : ?>
								<?php echo $field->display; ?>, 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerCity'])) : ?>
							<?php foreach ($item->positions['providerCity'] as $field) : ?>
								<?php echo $field->display; ?>, 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerStateProvince'])) : ?>
							<?php foreach ($item->positions['providerStateProvince'] as $field) : ?>
								<?php echo $field->display; ?>, 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerCountry'])) : ?>
							<?php foreach ($item->positions['providerCountry'] as $field) : ?>
								<?php echo $field->display; ?><br>
							<?php endforeach; ?>
						<?php endif; ?>
						</span>
						<span class="providerPhone" id="providerPhone_<?php echo $item->id;?>">
						<?php if (isset($item->positions['providerPhone'])) : ?>
							<?php foreach ($item->positions['providerPhone'] as $field) : ?>
								Phone: <?php echo $field->display; ?><br>
							<?php endforeach; ?>
						<?php endif; ?>
						</span>
                            <span style="display: none;" class="providerType" id="medicalType_<?php echo $item->id;?>">
                                                 <?php if (isset($item->positions['MedicalType'])) : ?>
                                                         <?php foreach ($item->positions['MedicalType'] as $field) : ?>
                                                                 <?php echo $field->display; ?>
                                                         <?php endforeach; ?>
                                                 <?php endif; ?>
                                                 </span>
						<?php if (isset($item->positions['providerFax'])) : ?>
							<?php foreach ($item->positions['providerFax'] as $field) : ?>
								Fax: <?php echo $field->display; ?><br>
							<?php endforeach; ?>
						<?php endif; ?>

						<?php if (isset($item->positions['providerEmail'])) : ?>
							<?php foreach ($item->positions['providerEmail'] as $field) : ?>
								Email: <a href="mailto:<?php echo $field->display; ?>" title="Email <?php echo $field->display; ?>"><?php echo $field->display; ?></a><br>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerWebsite'])) : ?>
							<?php foreach ($item->positions['providerWebsite'] as $field) : ?>
								Website: <a href="http://<?php echo $field->display; ?>" title="Visit <?php echo $field->display; ?>" target="_blank"><?php echo $field->display; ?></a>
							<?php endforeach; ?>
						<?php endif; ?>
						
						<?php
						#HT
						foreach ($item->positions['providerLatitude'] as $field) {
							echo "<span style='display:none;' class='providerLatitude' id='providerLatitude_$item->id'>$field->display</span>";
						}//for
						foreach ($item->positions['providerLongitude'] as $field) {
							echo "<span style='display:none;' class='providerLongitude' id='providerLongitude_$item->id'>$field->display</span>";
						}//for
						?>
                    </p>
                </div><!-- end of pure-u-1 pure-u-sm-1-2 -->
                <div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-right">
		
                    <h4><?php echo JText::_( 'FLEXI_OPEN_HOURS' ); ?> </h4>
                    <p>
                        <?php if (isset($item->positions['providerFromDay1'])) : ?>
							<?php foreach ($item->positions['providerFromDay1'] as $field) : ?>
								<?php echo $field->display; ?> - 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerToDay1'])) : ?>
							<?php foreach ($item->positions['providerToDay1'] as $field) : ?>
								<?php echo $field->display; ?> 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerOpeningHours1'])) : ?>
							<?php foreach ($item->positions['providerOpeningHours1'] as $field) : ?>
								: <?php echo $field->display; ?> - 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerClosingHours1'])) : ?>
							<?php foreach ($item->positions['providerClosingHours1'] as $field) : ?>
								<?php echo $field->display; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<br>
						<?php if (isset($item->positions['providerFromDay2'])) : ?>
							<?php foreach ($item->positions['providerFromDay2'] as $field) : ?>
								<?php echo $field->display; ?> 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerToDay2'])) : ?>
							<?php foreach ($item->positions['providerToDay2'] as $field) : ?>
								- <?php echo $field->display; ?> 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerOpeningHours2'])) : ?>
							<?php foreach ($item->positions['providerOpeningHours2'] as $field) : ?>
								: <?php echo $field->display; ?> - 
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if (isset($item->positions['providerClosingHours2'])) : ?>
							<?php foreach ($item->positions['providerClosingHours2'] as $field) : ?>
								<?php echo $field->display; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<br>
						<?php echo JText::_( 'FLEXI_EMERGENCY' ); ?> : 
						<?php if (isset($item->positions['providerEmergencyServices'])) : ?>
							<?php foreach ($item->positions['providerEmergencyServices'] as $field) : ?>
								<?php echo $field->display; ?>
							<?php endforeach; ?> 
						<?php endif; ?>
						<?php if (isset($item->positions['providerEmergencyPhone'])) : ?>
							<?php foreach ($item->positions['providerEmergencyPhone'] as $field) : ?>
								- <?php echo $field->display; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<br>
						
						 <span class="providerAmount"  id="providerAmount_<?php echo $item->id;?>">
                                                        <?php if (isset($item->positions['providersFee'])) : ?>
                                                                <?php foreach ($item->positions['providersFee'] as $field) : ?>
                                                                       <?php echo JText::_( 'FLEXI_FEE' ); ?> : <?php echo $field->display; ?><br>
                                                                <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        </span>

                    </p>
                    <p>
                    	<?php
							$readmore_forced = $this->params->get('show_readmore', 1) == -1 || $this->params->get('intro_strip_html', 1) == 1 ;
							$readmore_shown  = $this->params->get('show_readmore', 1) && ($uncut_length > $intro_cut_text || strlen(trim($item->fulltext)) >= 1);
							$readmore_shown  = $readmore_shown || $readmore_forced;
							$footer_shown = $readmore_shown || $item->event->afterDisplayContent;
						?>
						<?php if ( $readmore_shown ) : ?>
							<p><a href="<?php echo $link_url; ?>" title="<?php echo JText::_('FLEXI_READ_MORE_ABOUT'); ?>: <?php echo $item->title; ?>" class="pure-button pure-u-1" target="_blank">
							<?php
							if ($item->params->get('readmore')) :
								echo ' ' . $item->params->get('readmore');
							else :
								echo ' ' . JText::sprintf('FLEXI_READ_MORE', $item->title);
							endif;
							?>
							</a></p>
						<?php endif; ?>
                    </p>
                </div><!-- end of pure-u-1 pure-u-sm-1-2 -->
            </div><!-- end of pure-g -->
        </div><!-- end of provider-list-box -->
		<?php endfor; ?>

<?php endif; ?>
</div><!--infoside-->
<div class="mapside sameheight">
    <div style="    float: right;    text-align: right;    position: absolute;    z-index: 100000;    right: 0px;        ">
        <li style="width: 120px; float: left;background-color: #FF5A5F;  padding-top: 5px;    text-align: center; color: #fff ;    opacity: 0.8;    padding-bottom: 5px;"><?php echo $tagmap[0]; ?></li>
        <li style="width: 120px; float: left;background-color: #00a3d9;  padding-top: 5px;    text-align: center; color: #fff ; opacity: 0.8;    padding-bottom: 5px;" ><?php echo $tagmap[1]; ?></li>
    </div>
	<!--#HT google map-->
	<div id="mygooglemap"></div>
<script src="http://maps.googleapis.com/maps/api/js?v=3&sensor=false&key=AIzaSyDM1BwTTwt5nY8RpdiSJwAZTJvK3nfG-sU"></script>
<script type="text/javascript" src="<?php echo JURI::base(true).'/components/com_flexicontent/assets/js/markerwithlabel.js';?>"></script>
<script>
var latlong_a = [];

jQuery(document).ready(function($){
	//collect map data
	
	$('.providerLatitude').each(function(index,element) {
		var id = $(element).attr('id');
		id = id.replace("providerLatitude_", "");
		var latitude = $(element).text();
		var longitude = $("#providerLongitude_"+id).text();
		
		//remove comma
		var n = latitude.indexOf(",");
		
		//alert(n);
		
		if ( n >= 0 ) {
			
			latitude = latitude.substring(0,n);
			
		}
		
		var n = longitude.indexOf(",");
		
		//alert(n);
		
		if ( n >= 0 ) {
			
			longitude = longitude.substring(0,n);
			
		}
		
		
		var title = $("#providerTitle_"+id).text();
		var address = $("#providerAddress_"+id).text();
                
                var am = $("#providerAmount_"+id).text();
                
		var type = $("#medicalType_"+id).text();
                
                var phone = $("#providerPhone_"+id).text();
		
		
		if (latitude && longitude && title ) {
			latlong_a.push({'type':type, 'am':am , 'id': id, 'latitude': latitude.trim(), 'longitude': longitude.trim(), 'title': title.trim(), 'address': address.trim(), 'phone': phone.trim()});
		}//if
	});
	
	
	function initialize() {

	
	
		if (latlong_a.length) {
	
			var first_add = latlong_a[0];
			
			
			
			var myCenter = new google.maps.LatLng(first_add.latitude,first_add.longitude);
			
			
			
			var mapProp = {
							center:myCenter,
                                                        zoom:<?php if ($_GET['filter_258'] != "" || $_GET['filter'] != ""  ){echo '12';}else {echo '6';} ?>,
							mapTypeId:google.maps.MapTypeId.ROADMAP
						};
			
			var map = new google.maps.Map(document.getElementById("mygooglemap"),mapProp);
			
			
			var marker;
			var infowindow = new google.maps.InfoWindow();
			
			
			for (var i = 0; i < latlong_a.length; i++) {
			
				var latlong_o = latlong_a[i];
				
				var apoint = new google.maps.LatLng(latlong_o.latitude,latlong_o.longitude);
                                
                                var ssas = latlong_o.am.trim();
                                
				if ( ssas.toLowerCase().indexOf("high") >= 0 || ssas.toLowerCase().indexOf("cao") >= 0){
                                    
                                    var texla = "$$$";
                                    
                                }else if ( ssas.toLowerCase().indexOf("medium") >= 0  || ssas.toLowerCase().indexOf("trung bình") >= 0 ){ 
                                    var texla = "$$";
                                }else if (  ssas.toLowerCase().indexOf("low") >= 0  || ssas.toLowerCase().indexOf("thấp") >= 0  ){ 
                                    var texla = "$";
                                }else if (  ssas.toLowerCase().indexOf("free") >= 0  || ssas.toLowerCase().indexOf("miễn phí") >= 0  ){ 
                                    var texla = "FREE";
                                }else if (  ssas.toLowerCase().indexOf("inquiry") >= 0  || ssas.toLowerCase().indexOf("không xác định") >= 0  ){ 
                                    var texla = "N/A";
                                }else { 
                                     var texla = "#"+latlong_o.id;
                                }
				
                                
                                var aaa = latlong_o.type;
                                var labelClass = '' ; 
                                if ( aaa.trim()  ==  "Medical" ) {                                    
                                     labelClass = "cuslabels";
                                }else{                                    
                                     labelClass = "cuslabels2";
                                }
                                
                               // alert (latlong_o.type);
                                
				marker = new MarkerWithLabel({
					position: apoint,
					draggable: false,
					map: map,
					labelContent: texla ,
					labelAnchor: new google.maps.Point(30, 0),
					labelClass: labelClass, // the CSS class for the label
					icon: '<?php echo JURI::base(true).'/components/com_flexicontent/assets/images/blank.png';?>'
												});

				
				marker.html = "<p><b>"+latlong_o.title+'</b></p>'+"<p>"+latlong_o.address+'</p>'+"<p>"+latlong_o.phone+'</p>';
				marker.id = latlong_o.id;
				
				google.maps.event.addListener(marker, 'mouseover', function () {
				
					infowindow.setContent(this.html);
					infowindow.open(map, this);
                                        jQuery(".provider-list-box").hide();   
                                        jQuery(".bloc"+this.id).fadeIn();
                                    
                                        
                                 

				});
				
				
			}//for
			
		}//if
		
	}//func
	
	google.maps.event.addDomListener(window, 'load', initialize);
	
});
</script>

</div><!--mapside-->
<div class="clear"></div>


<script>
equalheight = function(container){

	var currentTallest = 0,
	currentRowStart = 0,
    rowDivs = new Array(),
    $el,
    topPosition = 0;
 
	
	//alert(1);
	
	$(container).each(function() {
	
	   $el = $(this);
	   $($el).height('auto')
	   topPostion = $el.position().top;
	
	   if (currentRowStart != topPostion) {
		 for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		   rowDivs[currentDiv].height(currentTallest);
		 }
		 rowDivs.length = 0; // empty the array
		 currentRowStart = topPostion;
		 currentTallest = $el.height();
		 rowDivs.push($el);
	   } else {
		 rowDivs.push($el);
		 currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
	  }
	   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
		 rowDivs[currentDiv].height(currentTallest);
	   }
	 });
}

jQuery(window).load(function() {
	
});


jQuery(window).resize(function(){
	//equalheight('.sameheight');
});

jQuery(document).ready(function($){
	//equalheight('.sameheight');
	
	$(".infoside").mCustomScrollbar({
		theme:"3d-thick-dark",
		scrollButtons:{enable:true}
	});
	
}); 
jQuery(document).ready(function($){ 
    
    $(".fc_filter_text_search").insertBefore("#fc_filter_id_261");
    $("#fc_filter_id_289 .noUi-base").mouseup(function (e){
        if (e.which != 1) return false;
        var form=document.getElementById('adminForm'); adminFormPrepare(form, 2); return false;
    });

});

</script>


 

