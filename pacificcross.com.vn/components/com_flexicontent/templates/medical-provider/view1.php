<?php if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){
    $tagmap[0]='Bệnh Viện';
    $tagmap[1]='Phòng Khám';
    $tagmap[2]='Tất Cả';
    $DirectBilling='Phương Thức Thanh Toán:';
    JFactory::getDocument()->addScriptDeclaration('jQuery(document).ready(function(){jQuery(".page-title").html("Thông Tin Bệnh Viện, Phòng Khám");});');
    $linktext='Xem tất cả danh sách bệnh viện, phòng khám';
    $notetext='Chú ý: ký hiệu giá trên bản đồ được thiết kế để cung cấp khái quát về chi phí khám chữa bệnh. Pacific Cross Vietnam không đảm bảo giá cả thực tế tại mỗi nơi';
	$directBillingText='Tải danh sách các Nhà cung cấp dịch vụ y tế có áp dụng Dịch vụ thanh toán trực tiếp';
	$directBillingLink='download.php?file=List of Medical Providers for Direct Billing Services_vn.pdf';
}else{
    $DirectBilling='Direct Billing:';
    $tagmap[0]='Hospital';
    $tagmap[1]='Clinic';
    $tagmap[2]='All';
    $linktext='View our complete list of medical providers';
    $notetext='Note: price symbols on the map are designed to give a general indication of costs only. Pacific Cross Vietnam cannot guarantee actual pricing at each medical provider';
	$directBillingText='Download the list of Medical Providers for Direct Billing Services';
	$directBillingLink='download.php?file=List of Medical Providers for Direct Billing Services_en.pdf';
}
$db=jfactory::getdbo();
$sql="select (select a.value from #__flexicontent_fields_item_relations a where c.id=a.item_id and a.field_id=261) as coun,(select b.value from #__flexicontent_fields_item_relations b where c.id=b.item_id and b.field_id=258) as city from #__content as c";
$db->setquery($sql);
$data=$db->loadobjectlist();
$temp='';
foreach($data as $r){
	if($r->coun!=''&&$r->city!=''){
		$lis[$r->coun][]=$r->city;
	}
}
$lis['Thailand']=array_unique($lis['Thailand']);
$lis['Vietnam']=array_unique($lis['Vietnam']);
asort($lis['Vietnam']);
$lis['Vietnam']=array_diff($lis['Vietnam'],['Ha Noi','Ho Chi Minh City','Da Nang']);
array_unshift($lis['Vietnam'],"Ha Noi","Ho Chi Minh City","Da Nang");

$tmpl=$this->tmpl;
$user=JFactory::getUser();

$lead_use_image=$this->params->get('lead_use_image',1);
$lead_link_image_to=$this->params->get('lead_link_image_to',0);
$intro_use_image=$this->params->get('intro_use_image',1);
$intro_link_image_to=$this->params->get('intro_link_image_to',0);

if(!empty($this->items)&&($this->params->get('lead_placement',0)==1||$this->params->get('intro_placement',0)==1)){
	flexicontent_html::loadFramework('masonry');
	flexicontent_html::loadFramework('imagesLoaded');
	$js = "jQuery(document).ready(function(){";
	if($this->params->get('lead_placement',0)==1){
		$js.="var container_lead=document.querySelector('ul.leadingblock');
			var msnry_lead;
			if(container_lead){
				imagesLoaded(container_lead,function(){
					msnry_lead=new Masonry(container_lead);
				});
			}";
	}
	if($this->params->get('intro_placement',0)==1){
		$js.="var container_intro=document.querySelector('ul.introblock');
			var msnry_intro;
			if(container_intro){
				imagesLoaded(container_intro,function(){
					msnry_intro=new Masonry(container_intro);
				});
			}";
	}
	$js.="});";
	JFactory::getDocument()->addScriptDeclaration($js);
}
JFactory::getDocument()->addStyleSheet(JURI::base(true).'/components/com_flexicontent/assets/css/style.clr.css');
JFactory::getDocument()->addStyleSheet(JURI::base(true).'/components/com_flexicontent/assets/js/scrollbar/jquery.mCustomScrollbar.css');
JFactory::getDocument()->addScript(JURI::base(true).'/components/com_flexicontent/assets/js/scrollbar/jquery.mCustomScrollbar.js');?>
<script>
jQuery(document).ready(function(){
    var getUrlParameter=function getUrlParameter(sParam){
	var sPageURL=decodeURIComponent(window.location.search.substring(1)),sURLVariables=sPageURL.split('&'),sParameterName,i;
	for(i=0;i<sURLVariables.length;i++){
            sParameterName = sURLVariables[i].split('=');
			if(sParameterName[0]===sParam){
                return sParameterName[1]===undefined?true:sParameterName[1];
            }
		}
	};
	var filter_258=getUrlParameter('filter_258');
    var filter_277=getUrlParameter('filter_277');
    var filter_274=getUrlParameter('filter_274');
    var filter_280=getUrlParameter('filter_280');
    var filter_272=getUrlParameter('filter_272');
    var filter_291=getUrlParameter('filter_291');
    
    if((filter_258!=''||filter_277!=''||filter_274!=''||filter_280!=''||filter_272!=''||filter_291!='')&&jQuery("#adminForm_filter").val()==''){
		setTimeout(function(){jQuery("#losr").trigger('click');},1000);
	}
	jQuery('.hospi').click(function(){
		jQuery('.clini').removeClass('acctive');
		jQuery('.allclihos').removeClass('acctive');
		jQuery(this).addClass('acctive');
		jQuery('.cuslabels').show();
		jQuery('.cuslabels2').hide();
	});
	jQuery('.clini').click(function(){
		jQuery('.hospi').removeClass('acctive');
		jQuery('.allclihos').removeClass('acctive');
		jQuery(this).addClass('acctive');
		jQuery('.cuslabels2').show();
		jQuery('.cuslabels').hide();
	});
	jQuery('.allclihos').click(function(){
		jQuery('.hospi').removeClass('acctive');
		jQuery('.clini').removeClass('acctive');
		jQuery(this).addClass('acctive');
		jQuery('.cuslabels2').show();
		jQuery('.cuslabels').show();
	});
});
</script>
<style>
.infoside h3{cursor:pointer}
.gm-style-iw+div{display:none}
input[type="radio"]:checked+label:not(.plupload_button):not(.btn),[name="adminForm"] input[type="radio"]:checked+label:not(.plupload_button):not(.btn),.flexicontent input[type="radio"]:checked+label:not(.plupload_button):not(.btn){background-position:0!important}
.type-search input[type=radio]:not(old){width:41%;margin:0;padding:0;font-size:10px;opacity:0;margin-top:5px}
.type-search input[type=radio]:not(old)+label{display:inline-block;margin-left:-28px;padding-left:28px;background:url('/images/radio/checks.png') no-repeat 0 0;line-height:24px}
input[type=radio]:not(old):checked+label{background:url('/images/radio/checks2.png') no-repeat 0 0}
.page-title{text-align:center}
@media screen and (min-width:330px){.type-search{left:49%;transform:translateX(-50%);position:absolute;width:75%;max-width:500px;margin-top:-17px;padding-left:35px}}
.fc_filter_text_search{margin:10px auto!important}
.barsearch{text-align:center}
.hospi,.clini,.allclihos{cursor:pointer}
.mCSB_container .active{background:#ccc}
input.fc_text_filter{height:30px}
@media screen and (min-width:35.5em) {.pure-u-sm-1-3{width:10%;padding:5px;}
.pure-u-sm-2-3{width:42%;padding:5px}
}
@media screen and (max-width:600px){#losr{clear:left}}
.fc_buttons{width:305px;float:left;margin-top:-14px;display:block!important}
.styl{width:100%;text-align:left;display:block;font-size:21px;text-transform:capitalize;cursor:pointer}
.acctive{opacity:1!important}
</style>
<script>
jQuery(document).ready(function($){
	$("#adminForm_261_val").append(
		$("#adminForm_261_val option").remove().sort(function(a,b){
			var at=$(a).text(),bt=$(b).text();
			if(at=='- Country -'||bt=='- Country -'){
				return (at > bt)?1:((at < bt)?-1:0);
			}else{
				return (at < bt)?1:((at > bt)?-1:0);
			}
		})
	);
	<?php if(!isset($_GET['filter_261'])){?>
		$("#adminForm_261_val").val('');
	<?php }?>
	$("#adminForm_261_val").change(function(){
		if($(this).val()==''){
			$("#adminForm_258_val").empty();
			$("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach($lis['Vietnam'] as $r1){?>
				$("#adminForm_258_val").append('<option value="<?php echo $r1;?>"><?php echo $r1;?></option>');
			<?php }?>
			<?php foreach($lis['Thailand'] as $r1){?>
				$("#adminForm_258_val").append('<option value="<?php echo $r1;?>"><?php echo $r1;?></option>');
			<?php }?>
		}else if($(this).val()=='Vietnam'){
			$("#adminForm_258_val").empty();
			$("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach($lis['Vietnam'] as $r1){?>
				$("#adminForm_258_val").append('<option value="<?php echo $r1;?>"><?php echo $r1;?></option>');
				<?php if ($r1 == 'Da Nang') {?>
					$("#adminForm_258_val").append('<option value="" disabled="disabled">────────────────</option>');
				<?php }
			}?>
		}else if($(this).val()=='Thailand'){
			$("#adminForm_258_val").empty();
			$("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach($lis['Thailand'] as $r1){?>
				$("#adminForm_258_val").append('<option value="<?php echo $r1;?>"><?php echo $r1;?></option>');
			<?php }?>
		}
	});
	<?php if($_GET['filter_261']=='Vietnam'){?>
		$("#adminForm_258_val").empty();
		<?php if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){?>
			$("#adminForm_258_val").append('<option value="" selected="selected">- Thành Phố -</option>');
		<?php }else{?>
			$("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
		<?php }?>
		<?php foreach($lis['Vietnam'] as $r1){?>
			$("#adminForm_258_val").append('<option <?php if(urldecode($_GET['filter_258'])==$r1){echo 'selected="selected"';}?> value="<?php echo $r1;?>"><?php echo $r1; ?></option>');
			<?php if($r1=='Da Nang'){?>
				$("#adminForm_258_val").append('<option value="" disabled="disabled">────────────────</option>');
			<?php }
		}
		if($_GET['filter_261']=='Thailand'){?>
			$("#adminForm_258_val").empty();
			$("#adminForm_258_val").append('<option value="" selected="selected">- City -</option>');
			<?php foreach($lis['Thailand'] as $r1){?>
				$("#adminForm_258_val").append('<option <?php if(urldecode($_GET['filter_258'])==$r1){echo 'selected="selected"';}?> value="<?php echo $r1;?>"><?php echo $r1;?></option>');
			<?php }?>
		<?php }?>
	<?php }?>
});
jQuery(document).ready(function($){
	var getUrlParameter=function getUrlParameter(sParam){
		var sPageURL=decodeURIComponent(window.location.search.substring(1)),sURLVariables=sPageURL.split('&'),sParameterName,i;
		for(i=0;i<sURLVariables.length;i++){
			sParameterName=sURLVariables[i].split('=');
			if(sParameterName[0]===sParam){
				return sParameterName[1]===undefined?true:sParameterName[1];
			}
		}
	};
	$('.fc_filter_set .pure-u-sm-1-3').hide();
	$('#namesr').click(function(){
		$(".fc_filter_text_search").show();
		$(".fc_filter_set .pure-u-sm-1-3").hide();
		$('.mod-languages a').each(function(){
			if($(this).attr('href')!='#'){
				$(this).attr('href',$(this).attr('href').replace(/(\?|&)?tab=([^&]$|[^&]*)/i,"")+'&tab=2');
			}
		});
	});
	$('#losr').click(function(){
		$(".fc_filter_text_search").hide();
		$(".fc_filter_set .pure-u-sm-1-3").show();
		$('.mod-languages a').each(function(){
			if($(this).attr('href')!='#'){
				$(this).attr('href',$(this).attr('href').replace(/(\?|&)?tab=([^&]$|[^&]*)/i,"")+'');
				// $(this).attr('href',$(this).attr('href').replace(/(\?|&)?tab=([^&]$|[^&]*)/i,"")+'&tab=1');
			}
		});
	});
	var tech=getUrlParameter('tab');
	if(tech==1){
		$('#losr').trigger('click');
	}
	if(tech==2){
		$('#namesr').trigger('click');
	}
	jQuery('.totalresult').text(jQuery('.provider-list-box').length);
});
</script>
<div class="barsearch">
	<div class="type-search">
		<input type="radio" name="aaaa" id="namesr" checked="checked" style="width:50px;float:left"/><label for="namesr" style="width:190px;float:left;font-size:18px"><?php echo JText::_('FLEXI_NAME_SEARCH');?></label>
		<input type="radio" name="aaaa" id="losr" style="width:50px;float:left"/><label for="losr" style="width:200px;float:left;font-size:18px"> <?php echo JText::_( 'FLEXI_LOCATION_SEARCH' );?></label>
	</div>
	<br>
	<?php include('listings_filter_form.php');?>
</div>
<div class="infoside sameheight">
	<div style="padding-left:10px">
		<span style="float:right">
			<?php if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){echo 'Tổng kết quả';}else{echo 'Result';}?>#<span class="totalresult"></span>
		</span>
		<a class="styl" onclick="jQuery('.provider-list-box').show();jQuery('.style-hide,.style-show').remove();"><?php echo $linktext;?></a><br>
		<p><a href="<?php echo $directBillingLink?>"><?php echo $directBillingText?></a></p>
		<small><?php echo $notetext;?></small>
		<br>
	</div>
	<?php ob_start();
	$filter_form_html=trim(ob_get_contents());
	ob_end_clean();
	if($filter_form_html){
		echo '<div class="group">'."\n".$filter_form_html."\n".'</div><!--group-->';
	}
	if(!$this->items){
		if($this->getModel()->getState('limit')){
			echo '<div class="noitems group">'.JText::_( 'FLEXI_NO_ITEMS_FOUND' ).'</div>';
		}
		return;
	}
	$items=&$this->items;
	$count=count($items);
	if($count){
		$_read_more_about=JText::_('FLEXI_READ_MORE_ABOUT');
		$tooltip_class=FLEXI_J30GE?'hasTooltip':'hasTip';
		$_comments_container_params='class="fc_comments_count '.$tooltip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_NUM_OF_COMMENTS','FLEXI_NUM_OF_COMMENTS_TIP',1,1).'"';
	}
	$leadnum=$this->params->get('lead_num',1);
	$leadnum=($leadnum>=$count)?$count:$leadnum;
	$doing_cat_order=$this->category->_order_arr[1]=='order';
	$lead_catblock=$this->params->get('lead_catblock',0);
	$intro_catblock=$this->params->get('intro_catblock',0);
	$lead_catblock_title=$this->params->get('lead_catblock_title',1);
	$intro_catblock_title=$this->params->get('intro_catblock_title',1);
	if($lead_catblock||$intro_catblock){
		global $globalcats;
	}
	if($this->limitstart!=0) $leadnum=0;
	$lead_cut_text=$this->params->get('lead_cut_text',400);
	$intro_cut_text=$this->params->get('intro_cut_text',200);
	$uncut_length=0;
	FlexicontentFields::getFieldDisplay($items,'text',$values=null,$method='display');
	if($leadnum):
		$lead_cols=$this->params->get('lead_cols',2);
		$lead_cols_classes=array(1=>'one',2=>'two',3=>'three',4=>'four');
		$classnum=$lead_cols_classes[$lead_cols];
		if($lead_use_image&&$this->params->get('lead_image')){
			$img_size_map=array('l'=>'large','m'=>'medium','s'=>'small','o'=>'original');
			$img_field_size=$img_size_map[$this->params->get('lead_image_size','l')];
			$img_field_name=$this->params->get('lead_image');
		}
		for($i=0;$i<$leadnum;$i++):
			$item=$items[$i];
			$fc_item_classes='';
			if($doing_cat_order) $fc_item_classes.=($i==0||($items[$i-1]->rel_catid!=$items[$i]->rel_catid)?'':'');
			$fc_item_classes.=$i%2?'right':'left';
			$fc_item_classes.='';
			$markup_tags='<span class="fc_mublock">';
			foreach($item->css_markups as $grp=>$css_markups){
				if(empty($css_markups)) continue;
				$fc_item_classes.=' fc'.implode(' fc',$css_markups);
				$ecss_markups=$item->ecss_markups[$grp];
				$title_markups=$item->title_markups[$grp];
				foreach($css_markups as $mui=>$css_markup){
					$markup_tags.='<span class="fc_markup mu'.$css_markups[$mui].$ecss_markups[$mui].'">'.$title_markups[$mui].'</span>';
				}
			}
			$markup_tags.='</span>';
			$custom_link=null;
			if($lead_use_image):
				if(!empty($img_field_name)){
					FlexicontentFields::getFieldDisplay($item,$img_field_name,$values=null,$method='display_'.$img_field_size.'_src');
					$img_field=&$item->fields[$img_field_name];
					$src=str_replace(JURI::root(),'',@$img_field->thumbs_src[$img_field_size][0]);
					if($lead_link_image_to&&isset($img_field->value[0])){
						$custom_link=($v=unserialize($img_field->value[0]))!==false?@$v['link']:@$img_field->value[0]['link'];
					}
				}else{
					$src=flexicontent_html::extractimagesrc($item);
				}
			$RESIZE_FLAG=!$this->params->get('lead_image')||!$this->params->get('lead_image_size');
			if($src&&$RESIZE_FLAG){
				$w='&amp;w='.$this->params->get('lead_width',200);
				$h='&amp;h='.$this->params->get('lead_height',200);
				$aoe='&amp;aoe=1';
				$q='&amp;q=95';
				$zc=$this->params->get('lead_method')?'&amp;zc='.$this->params->get('lead_method'):'';
				$ext=pathinfo($src,PATHINFO_EXTENSION);
				$f=in_array($ext,array('png','ico','gif'))?'&amp;f='.$ext:'';
				$conf=$w.$h.$aoe.$q.$zc.$f;
				$base_url=(!preg_match("#^http|^https|^ftp|^/#i",$src))?JURI::base(true).'/':'';
				$thumb=JURI::base(true).'/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src='.$base_url.$src.$conf;
			}else{
				$thumb=$src;
			}
			endif;
			$link_url=$custom_link?$custom_link:JRoute::_(FlexicontentHelperRoute::getItemRoute($item->slug,$item->categoryslug,0,$item));
			echo $lead_catblock?'<li class="lead_catblock">'.($lead_catblock_title&&@$globalcats[$item->rel_catid]?$globalcats[$item->rel_catid]->title:'').'</li>':'';?>
			<div class="provider-list-box bkg-white pall-15px bloc<?php echo $item->id;?>">
				<?php $header_shown=$this->params->get('show_comments_count',1)||$this->params->get('show_title',1)||$item->event->afterDisplayTitle||0;?>
				<?php if($this->params->get('show_title',1)):?>
					<h3 id="providerTitle_<?php echo $item->id;?>">
						<?php if($this->params->get('link_titles',0)):?>
							<a href="<?php echo $link_url;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
						<?php else:
							echo $item->title;
						endif;?>
					</h3>
				<?php endif;?>
				<div class="pure-g">
					<div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-left">
						<h4><?php echo JText::_('FLEXI_CONTACT_INFO');?></h4>
						<p>
							<span class="providerAddress" id="providerAddress_<?php echo $item->id;?>">
								<?php if(isset($item->positions['providerAddress'])):
									foreach($item->positions['providerAddress'] as $field):
										echo $field->display;?>,
									<?php endforeach;
								endif;
								if(isset($item->positions['providerDistrict'])):
									foreach($item->positions['providerDistrict'] as $field):
										if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){
											echo $field->display;
										}else{
											if(strpos($field->display,'District')!==false){
												echo $field->display;
											}else{
												if(is_numeric($field->display)){
													echo 'District '.$field->display;
												}else{
													echo $field->display.' District';
												}
											}
										}?>,
									<?php endforeach;
								endif;
								if(isset($item->positions['providerCity'])):
									foreach($item->positions['providerCity'] as $field):
										echo $field->display;?>,
									<?php endforeach;
								endif;
								if(isset($item->positions['providerStateProvince'])):
									foreach($item->positions['providerStateProvince'] as $field):
										echo $field->display;?>,
									<?php endforeach;
								endif;
								if(isset($item->positions['providerCountry'])):
									foreach($item->positions['providerCountry'] as $field):
										echo $field->display;?><br>
									<?php endforeach;
								endif;?>
							</span>
							<span class="providerPhone" id="providerPhone_<?php echo $item->id;?>">
								<?php if(isset($item->positions['providerPhone'])):
									foreach($item->positions['providerPhone'] as $field):?>
										Phone: <?php echo $field->display;?><br>
									<?php endforeach;
								endif;?>
							</span>
							<span style="display: none;" class="providerType" id="medicalType_<?php echo $item->id;?>">
								<?php if(isset($item->positions['MedicalType'])):?>
									<?php foreach($item->positions['MedicalType'] as $field):
										echo $field->display;
									endforeach;
								endif;?>
							</span>
							<?php if(isset($item->positions['providerFax'])):
								foreach($item->positions['providerFax'] as $field):?>
									Fax: <?php echo $field->display;?><br>
								<?php endforeach;
							endif;
							if(isset($item->positions['providerEmail'])):
								foreach($item->positions['providerEmail'] as $field):?>
									Email: <a href="mailto:<?php echo $field->display;?>" title="Email <?php echo $field->display;?>"><?php echo $field->display;?></a><br>
								<?php endforeach;
							endif;
							if(isset($item->positions['providerWebsite'])):
								foreach($item->positions['providerWebsite'] as $field):?>
									Website: <a href="http://<?php echo $field->display;?>" title="Visit <?php echo $field->display;?>" target="_blank"><?php echo $field->display;?></a>
								<?php endforeach;
							endif;
							if(isset($item->positions['providerDirectBilling'])):?>
								<br><?php echo $DirectBilling;
								foreach($item->positions['providerDirectBilling'] as $field):
									echo str_replace('</li>',', ',str_replace('<li>','',$field->display));
								endforeach;
							endif;?>
						</p>
						<?php foreach($item->positions['providerLatitude'] as $field){
							echo "<span style='display:none;' class='providerLatitude' id='providerLatitude_$item->id'>$field->display</span>";
						}
						foreach($item->positions['providerLongitude'] as $field){
							echo "<span style='display:none;' class='providerLongitude' id='providerLongitude_$item->id'>$field->display</span>";
						}?>
					</div>
					<div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-right">
						<h4><?php echo JText::_('FLEXI_OPEN_HOURS');?></h4>
						<p>
							<?php if(isset($item->positions['providerFromDay1'])):
								foreach($item->positions['providerFromDay1'] as $field):
									echo $field->display;?> - 
								<?php endforeach;
							endif;
							if(isset($item->positions['providerToDay1'])):
								foreach($item->positions['providerToDay1'] as $field):
									echo $field->display;
								endforeach;
							endif;
							if(isset($item->positions['providerOpeningHours1'])):
								foreach($item->positions['providerOpeningHours1'] as $field):?>
									: <?php echo $field->display;?> - 
								<?php endforeach;
							endif;
							if(isset($item->positions['providerClosingHours1'])):
								foreach($item->positions['providerClosingHours1'] as $field):
									echo $field->display;
								endforeach;
							endif;?><br>
							<?php if(isset($item->positions['providerFromDay2'])):
								foreach ($item->positions['providerFromDay2'] as $field):
									if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){
										if($field->display=='Monday'){
											echo 'Thứ hai';
										}elseif($field->display=='Tuesday'){
											echo 'Thứ ba';
										}elseif($field->display=='Wednesday'){
											echo 'Thứ tư';
										}elseif($field->display=='Thursday'){
											echo 'Thứ năm';
										}elseif($field->display=='Friday'){
											echo 'Thứ sáu';
										}elseif($field->display=='Saturday'){
											echo 'Thứ bảy';
										}elseif($field->display=='Sunday'){
											echo 'Chủ Nhật';
										}
									}else{
										echo $field->display;
									}
								endforeach;
							endif;
							if(isset($item->positions['providerToDay2'])):
								foreach($item->positions['providerToDay2'] as $field):?>
									- <?php echo $field->display;
								endforeach;
							endif;
							if(isset($item->positions['providerOpeningHours2'])):
								foreach($item->positions['providerOpeningHours2'] as $field):?>
									: <?php echo $field->display;?> - 
								<?php endforeach;
							endif;
							if(isset($item->positions['providerClosingHours2'])):
								foreach($item->positions['providerClosingHours2'] as $field):
									echo $field->display;
								endforeach;
							endif;?><br>
							<?php echo JText::_('FLEXI_EMERGENCY');?> : 
							<?php if(isset($item->positions['providerEmergencyServices'])):
								foreach($item->positions['providerEmergencyServices'] as $field):
									echo $field->display;
								endforeach;
							endif;?><br>
							<span class="providerAmount" id="providerAmount_<?php echo $item->id;?>">
								<?php if(isset($item->positions['providersFee'])):
									foreach($item->positions['providersFee'] as $field):
										echo JText::_('FLEXI_FEE');?> : <?php echo $field->display;?><br>
									<?php endforeach;
								endif;?>
							</span>
						</p>
						<p>
							<?php $readmore_forced=$this->params->get('show_readmore',1)==-1||$this->params->get('intro_strip_html',1)==1;
							$readmore_shown=$this->params->get('show_readmore',1)&&($uncut_length>$intro_cut_text||strlen(trim($item->fulltext))>=1);
							$readmore_shown=$readmore_shown||$readmore_forced;
							$footer_shown=$readmore_shown||$item->event->afterDisplayContent;
							if($readmore_shown):?>
								<p><a href="<?php echo $link_url;?>" title="<?php echo JText::_('FLEXI_READ_MORE_ABOUT');?>: <?php echo $item->title;?>" class="pure-button pure-u-1" target="_blank">
								<?php if($item->params->get('readmore')):
									echo ' '.$item->params->get('readmore');
								else:
									echo ' '.JText::sprintf('FLEXI_READ_MORE',$item->title);
								endif;?>
								</a></p>
							<?php endif;?>
						</p>
					</div>
				</div>
			</div>
		<?php endfor;
	endif;
	if($this->limitstart!=0) $leadnum=0;
	if($count>$leadnum):
		$intro_cols=$this->params->get('intro_cols',2);
		$intro_cols_classes=array(1=>'one',2=>'two',3=>'three',4=>'four');
		$classnum=$intro_cols_classes[$intro_cols];
		if($intro_use_image&&$this->params->get('intro_image')){
			$img_size_map=array('l'=>'large','m'=>'medium','s'=>'small','o'=>'original');
			$img_field_size=$img_size_map[$this->params->get('intro_image_size','l')];
			$img_field_name=$this->params->get('intro_image');
		}
		for($i=$leadnum;$i<$count;$i++):
			$item=$items[$i];
			$fc_item_classes='';
			if($doing_cat_order) $fc_item_classes.=($i==0||($items[$i-1]->rel_catid!=$items[$i]->rel_catid)?'':'');
			$fc_item_classes.=($i-$leadnum)%2?'right':'left';
			$fc_item_classes.='';
			$markup_tags='<span class="fc_mublock">';
			foreach($item->css_markups as $grp=>$css_markups){
				if(empty($css_markups)) continue;
				$fc_item_classes.=' fc'.implode(' fc',$css_markups);
				$ecss_markups=$item->ecss_markups[$grp];
				$title_markups=$item->title_markups[$grp];
				foreach($css_markups as $mui=>$css_markup){
					$markup_tags.='<span class="fc_markup mu'.$css_markups[$mui].$ecss_markups[$mui].'">'.$title_markups[$mui].'</span>';
				}
			}
			$markup_tags.='</span>';
			$custom_link=null;
			if($intro_use_image):
				if(!empty($img_field_name)){
					FlexicontentFields::getFieldDisplay($item,$img_field_name,$values=null,$method='display_'.$img_field_size.'_src');
					$img_field=&$item->fields[$img_field_name];
					$src=str_replace(JURI::root(),'',@$img_field->thumbs_src[$img_field_size][0]);
					if($intro_link_image_to&&isset($img_field->value[0])){
						$custom_link=($v=unserialize($img_field->value[0]))!==false?@$v['link']:@$img_field->value[0]['link'];
					}
				}else{
					$src=flexicontent_html::extractimagesrc($item);
				}
				$RESIZE_FLAG=!$this->params->get('intro_image')||!$this->params->get('intro_image_size');
				if($src&&$RESIZE_FLAG){
					$w='&amp;w='.$this->params->get('intro_width',200);
					$h='&amp;h='.$this->params->get('intro_height',200);
					$aoe='&amp;aoe=1';
					$q='&amp;q=95';
					$zc=$this->params->get('intro_method')?'&amp;zc='.$this->params->get('intro_method'):'';
					$ext=pathinfo($src,PATHINFO_EXTENSION);
					$f=in_array($ext,array('png','ico','gif'))?'&amp;f='.$ext:'';
					$conf=$w.$h.$aoe.$q.$zc.$f;
					$base_url=(!preg_match("#^http|^https|^ftp|^/#i",$src))?JURI::base(true).'/':'';
					$thumb=JURI::base(true).'/components/com_flexicontent/librairies/phpthumb/phpThumb.php?src='.$base_url.$src.$conf;
				}else{
					$thumb=$src;
				}
			endif;
			$link_url=$custom_link?$custom_link:JRoute::_(FlexicontentHelperRoute::getItemRoute($item->slug,$item->categoryslug,0,$item));
			echo $intro_catblock?'<li class="intro_catblock">'.($intro_catblock_title&&@$globalcats[$item->rel_catid]?$globalcats[$item->rel_catid]->title:'').'</li>':'' ?>
			<div class="provider-list-box bkg-white pall-15px bloc<?php echo $item->id;?>">
				<?php $header_shown=$this->params->get('show_comments_count',1)||$this->params->get('show_title',1)||$item->event->afterDisplayTitle||0;
				if($this->params->get('show_title',1)):?>
					<h3 id="providerTitle_<?php echo $item->id;?>">
						<?php if($this->params->get('link_titles',0)):?>
							<a href="<?php echo $link_url;?>" title="<?php echo $item->title;?>"><?php echo $item->title;?></a>
						<?php else:
							echo $item->title;
						endif;?>
					</h3>
				<?php endif;?>
				<div class="pure-g">
					<div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-left">
						<h4><?php echo JText::_('FLEXI_CONTACT_INFO');?></h4>
						<p>
							<span class="providerAddress" id="providerAddress_<?php echo $item->id;?>">
								<?php if(isset($item->positions['providerAddress'])):
									foreach($item->positions['providerAddress'] as $field):
										echo $field->display;?>,
									<?php endforeach;
								endif;
								if(isset($item->positions['providerDistrict'])):
									foreach($item->positions['providerDistrict'] as $field):
										if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){
											echo $field->display;
										}else{
											if(strpos($field->display, 'District')!==false){
												echo $field->display;
											}else{
												if(is_numeric($field->display)){
													echo 'District '.$field->display;
												}else{
													echo $field->display.' District';
												}
											}
										}?>, 
									<?php endforeach;
								endif;
								if(isset($item->positions['providerCity'])):
									foreach($item->positions['providerCity'] as $field):
										echo $field->display;?>, 
									<?php endforeach;
								endif;
								if(isset($item->positions['providerStateProvince'])):
									foreach($item->positions['providerStateProvince'] as $field):
										echo $field->display;?>, 
									<?php endforeach;
								endif;
								if(isset($item->positions['providerCountry'])):
									foreach($item->positions['providerCountry'] as $field):
										echo $field->display;?><br>
									<?php endforeach;
								endif;?>
							</span>
							<span class="providerPhone" id="providerPhone_<?php echo $item->id;?>">
								<?php if(isset($item->positions['providerPhone'])):
									foreach($item->positions['providerPhone'] as $field):?>
										Phone: <?php echo $field->display;?><br>
									<?php endforeach;
								endif;?>
							</span>
							<span style="display:none" class="providerType" id="medicalType_<?php echo $item->id;?>">
								<?php if(isset($item->positions['MedicalType'])):
									foreach($item->positions['MedicalType'] as $field):
										echo $field->display;
									endforeach;
								endif;?>
							</span>
							<?php if(isset($item->positions['providerFax'])):
								foreach($item->positions['providerFax'] as $field):?>
									Fax: <?php echo $field->display;?><br>
								<?php endforeach;
							endif;
							if(isset($item->positions['providerEmail'])):
								foreach($item->positions['providerEmail'] as $field):?>
									Email: <a href="mailto:<?php echo $field->display;?>" title="Email <?php echo $field->display;?>"><?php echo $field->display;?></a><br>
								<?php endforeach;
							endif;
							if(isset($item->positions['providerWebsite'])):
								foreach($item->positions['providerWebsite'] as $field):?>
									Website: <a href="http://<?php echo $field->display;?>" title="Visit <?php echo $field->display;?>" target="_blank"><?php echo $field->display;?></a>
								<?php endforeach;
							endif;
							if(isset($item->positions['providerDirectBilling'])):?>
								<br><?php echo $DirectBilling;?>
								<?php foreach($item->positions['providerDirectBilling'] as $field):
									echo str_replace('</li>',', ',str_replace('<li>','',$field->display));
								endforeach;
							endif;
							foreach($item->positions['providerLatitude'] as $field){
								echo "<span style='display:none' class='providerLatitude' id='providerLatitude_$item->id'>$field->display</span>";
							}
							foreach($item->positions['providerLongitude'] as $field){
								echo "<span style='display:none' class='providerLongitude' id='providerLongitude_$item->id'>$field->display</span>";
							}?>
						</p>
					</div>
					<div class="pure-u-1 pure-u-sm-1-2 pure-sm-padding-right">
						<h4><?php echo JText::_('FLEXI_OPEN_HOURS');?></h4>
						<p>
							<?php if(isset($item->positions['providerFromDay1'])):
								foreach($item->positions['providerFromDay1'] as $field):
									echo $field->display;?> - 
								<?php endforeach;
							endif;
							if(isset($item->positions['providerToDay1'])):
								foreach($item->positions['providerToDay1'] as $field):
									echo $field->display;
								endforeach;
							endif;
							if(isset($item->positions['providerOpeningHours1'])):
								foreach($item->positions['providerOpeningHours1'] as $field):?>
									: <?php echo $field->display;?> - 
								<?php endforeach;
							endif;
							if(isset($item->positions['providerClosingHours1'])):
								foreach($item->positions['providerClosingHours1'] as $field):
									echo $field->display;
								endforeach;
							endif;?><br>
							<?php if(isset($item->positions['providerFromDay2'])):
								foreach($item->positions['providerFromDay2'] as $field):?>
									<?php if(strtolower(JFactory::getLanguage()->getTag())=='vi-vn'){
										if($field->display=='Monday'){
											echo 'Thứ hai';
										}elseif($field->display=='Tuesday'){
											echo 'Thứ ba';
										}elseif($field->display=='Wednesday'){
											echo 'Thứ tư';
										}elseif($field->display=='Thursday'){
											echo 'Thứ năm';
										}elseif($field->display=='Friday'){
											echo 'Thứ sáu';
										}elseif($field->display=='Saturday'){
											echo 'Thứ bảy';
										}elseif($field->display=='Sunday'){
											echo 'Chủ Nhật';
										}
									}else{
										echo $field->display;
									}
								endforeach;
							endif;
							if(isset($item->positions['providerToDay2'])):
								foreach($item->positions['providerToDay2'] as $field):?>
									- <?php echo $field->display;
								endforeach;
							endif;
							if(isset($item->positions['providerOpeningHours2'])):
								foreach($item->positions['providerOpeningHours2'] as $field):?>
									: <?php echo $field->display;?> - 
								<?php endforeach;
							endif;
							if(isset($item->positions['providerClosingHours2'])):
								foreach($item->positions['providerClosingHours2'] as $field):
									echo $field->display;
								endforeach;
							endif;?><br>
							<?php echo JText::_('FLEXI_EMERGENCY');?> : 
							<?php if(isset($item->positions['providerEmergencyServices'])):
								foreach($item->positions['providerEmergencyServices'] as $field):
									echo $field->display;
								endforeach;
							endif;
							if(isset($item->positions['providerEmergencyPhone'])):
								foreach($item->positions['providerEmergencyPhone'] as $field):?>
									- <?php echo $field->display;
								endforeach;
							endif;?><br>
							<span class="providerAmount" id="providerAmount_<?php echo $item->id;?>">
								<?php if(isset($item->positions['providersFee'])):
									foreach($item->positions['providersFee'] as $field):
										echo JText::_('FLEXI_FEE');?> : <?php echo $field->display;?><br>
									<?php endforeach;
								endif;?>
							</span>
						</p>
						<p>
							<?php $readmore_forced=$this->params->get('show_readmore',1)==-1||$this->params->get('intro_strip_html',1)==1;
							$readmore_shown=$this->params->get('show_readmore',1)&&($uncut_length>$intro_cut_text||strlen(trim($item->fulltext))>=1);
							$readmore_shown=$readmore_shown||$readmore_forced;
							$footer_shown=$readmore_shown||$item->event->afterDisplayContent;
							if($readmore_shown):?>
								<p><a href="<?php echo $link_url;?>" title="<?php echo JText::_('FLEXI_READ_MORE_ABOUT');?>: <?php echo $item->title;?>" class="pure-button pure-u-1" target="_blank">
								<?php if($item->params->get('readmore')):
									echo ' '.$item->params->get('readmore');
								else:
									echo ' '.JText::sprintf('FLEXI_READ_MORE',$item->title);
								endif;?>
								</a></p>
							<?php endif;?>
						</p>
					</div>
				</div>
			</div>
		<?php endfor;
	endif;?>
</div>
<div class="mapside sameheight">
	<div><span style="margin-right:15px">Search Near By: </span><input style="margin-top:-3px" type="radio" name="nearby" value="5"/> 5km <input style="margin-top:-3px" type="radio" name="nearby" value="10"/> 10km <input style="margin-top:-3px" type="radio" name="nearby" value="20"/> 20km </div>
	<div style="float:right;text-align:right;position:absolute;z-index:100000;right:0px;">
		<li class="hospi" style="width:120px;float:left;background-color:#FF5A5F;padding-top:5px;text-align:center;color:#fff;opacity:0.8;padding-bottom:5px"><?php echo $tagmap[0];?></li>
		<li class="clini" style="width:120px;float:left;background-color:#00a3d9;padding-top:5px;text-align:center;color:#fff;opacity:0.8;padding-bottom:5px"><?php echo $tagmap[1];?></li>
		<li class="allclihos" style="width:80px;float:left;background-color:#333;padding-top:5px;text-align:center;color:#fff;opacity:0.7;padding-bottom:5px"><?php echo $tagmap[2];?></li>
	</div>
	<div id="mygooglemap"></div>
	<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=false&key=AIzaSyDM1BwTTwt5nY8RpdiSJwAZTJvK3nfG-sU"></script>
	<script type="text/javascript" src="<?php echo JURI::base(true).'/components/com_flexicontent/assets/js/markerwithlabel.js';?>"></script>
	<script>
	var latlong_a=[];
	jQuery(document).ready(function($){
		var map;
		$("input[name='nearby']").click(function (){
			if(navigator && navigator.geolocation){
				navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
			}else{
				console.log('Geolocation is not supported');
			}
		});
		function errorCallback(){}
		function successCallback(position){
			if(latlong_a.length){
				var first_add=latlong_a[0];
				var myCenter=new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
				if($("input[name='nearby']:checked").val()<15){
					var zzo=14;
				}else{
					var zzo=13;
				}
				var mapProp={
					center:myCenter,
					zoom:zzo,
					mapTypeId:google.maps.MapTypeId.ROADMAP,
					mapTypeControl: true,
					mapTypeControlOptions:{
						style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
						position:google.maps.ControlPosition.TOP_CENTER
					},
					zoomControl:true,
					zoomControlOptions:{
						position:google.maps.ControlPosition.LEFT_TOP
					},
					scaleControl:true,
					streetViewControl:true,
					streetViewControlOptions:{
						position:google.maps.ControlPosition.LEFT_TOP
					},
					styles:[{
						featureType:"poi",
						elementType:"labels",
						stylers:[{
							visibility:"off"
						}]
					}]
				};
				var map=new google.maps.Map(document.getElementById("mygooglemap"),mapProp);
				new google.maps.Circle({
					strokeColor:'#f0f0f0',
					strokeOpacity:0.9,
					strokeWeight:2,
					fillColor:'#999',
					fillOpacity:0.3,
					map:map,
					center:{lat:position.coords.latitude,lng:position.coords.longitude},
					radius:Math.sqrt(parseInt($("input[name='nearby']:checked").val())*300)*100
				});
				var marker;
				var infowindow=new google.maps.InfoWindow();
				for(var i=0;i<latlong_a.length;i++){
					var latlong_o=latlong_a[i];
					var apoint=new google.maps.LatLng(latlong_o.latitude,latlong_o.longitude);
					var ssas=latlong_o.am.trim();
					if(ssas.toLowerCase().indexOf("high")>=0||ssas.toLowerCase().indexOf("cao")>=0){
						var texla="$$$";
					}else if(ssas.toLowerCase().indexOf("medium")>=0||ssas.toLowerCase().indexOf("trung bình")>=0){
						var texla="$$";
					}else if(ssas.toLowerCase().indexOf("low")>=0||ssas.toLowerCase().indexOf("thấp")>=0){
						var texla="$";
					}else if(ssas.toLowerCase().indexOf("n/a")>=0||ssas.toLowerCase().indexOf("miễn phí")>=0){
						var texla="N/A";
					}else if(ssas.toLowerCase().indexOf("inquiry")>=0||ssas.toLowerCase().indexOf("không xác định")>=0){
						var texla="N/A";
					}else{
						var texla="#"+latlong_o.id;
					}
					var aaa=latlong_o.type;
					var labelClass='';
					if(aaa.trim()=="Medical"){
						if(ssas.toLowerCase().indexOf("n/a")>=0||ssas.toLowerCase().indexOf("miễn phí")>=0){
							labelClass="cuslabels sasas"+latlong_o.id;
						}else if(ssas.toLowerCase().indexOf("inquiry")>=0||ssas.toLowerCase().indexOf("không xác định")>=0){
							labelClass="cuslabels sasas"+latlong_o.id;
						}else{
							labelClass="cuslabels sasas"+latlong_o.id;
						}
					}else{
						if(ssas.toLowerCase().indexOf("n/a")>=0||ssas.toLowerCase().indexOf("miễn phí")>=0){
							labelClass="cuslabels2 sasas"+latlong_o.id;
						}else if(ssas.toLowerCase().indexOf("inquiry")>=0||ssas.toLowerCase().indexOf("không xác định")>=0){
							labelClass="cuslabels2 sasas"+latlong_o.id;
						}else{
							labelClass="cuslabels2 sasas"+latlong_o.id;
						}
					}
					marker=new MarkerWithLabel({
						position:apoint,
						draggable:false,
						map:map,
						labelContent:texla,
						labelAnchor:new google.maps.Point(30,0),
						labelClass:labelClass,
						icon:'<?php echo JURI::base(true).'/components/com_flexicontent/assets/images/blank.png';?>'
					});
					marker.html="<p><b>"+latlong_o.title+'</b></p>'+"<p>"+latlong_o.address+'</p>'+"<p>"+latlong_o.phone+'</p>';
					marker.id=latlong_o.id;
					google.maps.event.addListener(marker,'click',function(){
						infowindow.setContent(this.html);
						infowindow.open(map, this);
						jQuery(".provider-list-box").hide();
						jQuery(".bloc"+this.id).fadeIn();
						jQuery(".style-hide , .style-show").remove();
						jQuery('<style class="style-show"> .sasas'+this.id+'{display:block!important;}</style>').insertAfter('.provider-list');
						jQuery('<style class="style-hide"> .cuslabels2,.cuslabels{display:none!important;}</style>').insertAfter('.provider-list');
						google.maps.event.addListener(map,'click',function(){
							jQuery(".provider-list-box").show();
							jQuery(".style-hide,.style-show").remove();
							infowindow.close();
							marker.open=false;
						});
					});
				}
			}
		}
		$('.providerLatitude').each(function(index,element){
			var id=$(element).attr('id');
			id=id.replace("providerLatitude_","");
			var latitude=$(element).text();
			var longitude=$("#providerLongitude_"+id).text();
			var n=latitude.indexOf(",");
			if(n>=0){
				latitude=latitude.substring(0,n);
			}
			var n=longitude.indexOf(",");
			if(n>=0){
				longitude=longitude.substring(0,n);
			}
			var title=$("#providerTitle_"+id).text();
			var address=$("#providerAddress_"+id).text();
			var am=$("#providerAmount_"+id).text();
			var type=$("#medicalType_"+id).text();
			var phone=$("#providerPhone_"+id).text();
			if(latitude&&longitude&&title){
				latlong_a.push({'type':type,'am':am ,'id':id,'latitude':latitude.trim(),'longitude':longitude.trim(),'title':title.trim(),'address':address.trim(),'phone':phone.trim()});
			}
		});
		function initialize(){
			if(latlong_a.length){
				var first_add=latlong_a[0];
				var myCenter=new google.maps.LatLng(first_add.latitude,first_add.longitude);
				var mapProp={
					center:myCenter,
					zoom:<?php if ($_GET['filter_258']!=""||$_GET['filter']!=""){echo '12';}else{echo '6';}?>,
					mapTypeId:google.maps.MapTypeId.ROADMAP,
					mapTypeControl:true,
					mapTypeControlOptions:{
						style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
						position:google.maps.ControlPosition.TOP_CENTER
					},
					zoomControl:true,
					zoomControlOptions:{
						position:google.maps.ControlPosition.LEFT_TOP
					},
					scaleControl:true,
					streetViewControl:true,
					streetViewControlOptions:{
						position:google.maps.ControlPosition.LEFT_TOP
					},
					styles:[{
						featureType:"poi",
						elementType:"labels",
						stylers:[{
							visibility:"off"
						}]
					}]
				};
				map=new google.maps.Map(document.getElementById("mygooglemap"),mapProp);	
				var marker;
				var infowindow=new google.maps.InfoWindow();
				for(var i=0;i< latlong_a.length;i++){
					var latlong_o=latlong_a[i];
					var apoint=new google.maps.LatLng(latlong_o.latitude,latlong_o.longitude);
					var ssas=latlong_o.am.trim();
					if(ssas.toLowerCase().indexOf("high")>=0||ssas.toLowerCase().indexOf("cao")>=0){
						var texla="$$$";
					}else if(ssas.toLowerCase().indexOf("medium")>=0||ssas.toLowerCase().indexOf("trung bình")>=0){
						var texla="$$";
					}else if(ssas.toLowerCase().indexOf("low")>=0||ssas.toLowerCase().indexOf("thấp")>=0){
						var texla="$";
					}else if(ssas.toLowerCase().indexOf("n/a")>=0||ssas.toLowerCase().indexOf("miễn phí")>=0){
						var texla="N/A";
					}else if(ssas.toLowerCase().indexOf("inquiry")>=0||ssas.toLowerCase().indexOf("không xác định")>=0){
						var texla="N/A";
					}else{
						var texla="#"+latlong_o.id;
					}
					var aaa=latlong_o.type;
					var labelClass='';
					if(aaa.trim()=="Medical"){
						if(ssas.toLowerCase().indexOf("n/a")>=0||ssas.toLowerCase().indexOf("miễn phí")>=0){
							labelClass="cuslabels sasas"+latlong_o.id;
						}else if(ssas.toLowerCase().indexOf("inquiry")>=0||ssas.toLowerCase().indexOf("không xác định")>=0){
							labelClass="cuslabels sasas"+latlong_o.id;
						}else{
							labelClass="cuslabels sasas"+latlong_o.id;
						}
					}else{
						if(ssas.toLowerCase().indexOf("n/a")>=0||ssas.toLowerCase().indexOf("miễn phí")>=0){
							labelClass="cuslabels2 sasas"+latlong_o.id;
						}else if(ssas.toLowerCase().indexOf("inquiry")>=0||ssas.toLowerCase().indexOf("không xác định")>=0){
							labelClass="cuslabels2 sasas"+latlong_o.id;
						}else{
							labelClass="cuslabels2 sasas"+latlong_o.id;
						}
					}
					marker=new MarkerWithLabel({
						position:apoint,
						draggable:false,
						map:map,
						labelContent:texla,
						labelAnchor:new google.maps.Point(30,0),
						labelClass:labelClass,
						icon:'<?php echo JURI::base(true).'/components/com_flexicontent/assets/images/blank.png';?>'
					});
					marker.html="<p class='daddf'><b>"+latlong_o.title+'</b></p>'+"<p>"+latlong_o.address+'</p>'+"<p>"+latlong_o.phone+'</p>';
					marker.id=latlong_o.id;
					google.maps.event.addListener(marker,'click',function(){
						infowindow.setContent(this.html);
						infowindow.open(map,this);
						jQuery(".provider-list-box").hide();
						jQuery(".bloc"+this.id).fadeIn();
						jQuery(".style-hide,.style-show").remove();
						jQuery('<style class="style-show"> .sasas'+this.id+'{display:block!important;}</style>').insertAfter('.provider-list');
						jQuery('<style class="style-hide"> .cuslabels2,.cuslabels{display:none!important;}</style>').insertAfter('.provider-list');
						map.panTo(this.getPosition());
						google.maps.event.addListener(map,'click',function(){
							jQuery(".provider-list-box").show();
							jQuery(".style-hide,.style-show").remove();
							infowindow.close();
							marker.open=false;
						});
					});
				}
			}
		}
		google.maps.event.addDomListener(window,'load',initialize);
		jQuery(".infoside h3").click(function(){
			var suffix=jQuery(this).attr("id").match(/\d+/);
			map.panTo(new google.maps.LatLng(jQuery('#providerLatitude_'+suffix).text(),jQuery('#providerLongitude_'+suffix).text()));
			map.setZoom(16);
			jQuery('.sasas'+suffix).trigger('click');
			jQuery(".style-hide,.style-show").remove();
			jQuery('<style class="style-show"> .sasas'+suffix+'{display:block!important;}</style>').insertAfter('.provider-list');
			jQuery('<style class="style-hide"> .cuslabels2,.cuslabels{display:none!important;}</style>').insertAfter('.provider-list');
			jQuery(".provider-list-box").show();
		});
	});
	</script>
</div>
<div class="clear"></div>
<script>
equalheight=function(container){
	var currentTallest=0,currentRowStart=0,rowDivs=new Array(),$el,topPosition=0;
	$(container).each(function(){
		$el=$(this);
		$($el).height('auto')
		topPostion=$el.position().top;
		if(currentRowStart!=topPostion){
			for(currentDiv=0;currentDiv<rowDivs.length;currentDiv++){
				rowDivs[currentDiv].height(currentTallest);
			}
			rowDivs.length=0;
			currentRowStart=topPostion;
			currentTallest=$el.height();
			rowDivs.push($el);
		}else{
			rowDivs.push($el);
			currentTallest=(currentTallest<$el.height())?($el.height()):(currentTallest);
		}
		for(currentDiv=0;currentDiv<rowDivs.length;currentDiv++){
			rowDivs[currentDiv].height(currentTallest);
		}
	});
}
jQuery(window).load(function(){});
jQuery(window).resize(function(){});
jQuery(document).ready(function($){
	$(".infoside").mCustomScrollbar({
		theme:"3d-thick-dark",
		scrollButtons:{enable:true}
	});
}); 
jQuery(document).ready(function($){
	$(".fc_filter_text_search").insertBefore("#fc_filter_id_261");
	$("#fc_filter_id_289 .noUi-base").mouseup(function(e){
		if(e.which != 1) return false;
		var form=document.getElementById('adminForm');
		adminFormPrepare(form,2);
		return false;
	});
});
</script>            