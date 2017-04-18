<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

defined('_JEXEC') or die('Restricted access');

$gridPath=1;
?> 
<?php if ($config->get('main_ratings')) { ?>
<script type="text/javascript">
EasyBlog.require()
.script('ratings')
.done(function($) {

    $('#fd.mod_easyblogmostpopularpost [data-rating-form]').implement(EasyBlog.Controller.Ratings);
});



</script>
<?php } ?>


  <?php foreach ($posts as $post) { 
// get content cat heal
      if ($post->category_id == 1 || $post->category_id == 4){
         $listh[] = $post;
         $checkdate = $post->created;
         $datr1[] = date('m',strtotime($checkdate));
      }
      
      if ($post->category_id == 2 || $post->category_id == 5 ){
          $listc[] = $post;
          $checkdate = $post->created;
          $datr2[] = date('m',strtotime($checkdate));
      }
      
// get content cat tavel
 } ?>





<div id="fd" class="eb eb-mod mod_easyblogmostpopularpost<?php echo $params->get('moduleclass_sfx'); ?>">

    <?php if ($posts) { ?>
    
    
    
    
<?php if (strtolower(JFactory::getLanguage()->getTag()) == 'vi-vn' ){
 
    
    $datr1 = array_unique($datr1);
    $datr2 = array_unique($datr2);
    arsort($datr1); arsort($datr2);    
?>

    
    
<ul class="tabs"><li class="tab"><div  id="litabaabc1" class="linkopen"  aria-selected="true" role="tab" aria-controls="module_125">Sức Khỏe & Du Lịch</div></li><li class="tab"><div  id="litabaabc2" class="linkclosed" aria-selected="false" role="tab" aria-controls="module_161">Công Ty</div></li></ul>
<div id="tababc1">
   <?php foreach ($datr1 as $p){  $jm++;  $k = 0; ?>
    <div class="codin mth<?php echo $p; ?>" ref="<?php echo $p; ?>" >
        
        <?php    
        
          switch ($p){
              case '01':
                  echo 'Tháng Một';
              break;              
              case '02':
                  echo 'Tháng Hai';
              break;
              case '03':
                  echo 'Tháng Ba';
              break;
              case '04':
                  echo 'Tháng Tư';
              break;
              case '05':
                  echo 'Tháng Năm';
              break;
              case '06':
                  echo 'Tháng Sáu';
              break;
              case '07':
                  echo 'Tháng Bảy';
              break;
              case '08':
                  echo 'Tháng Tám';
              break;
              case '09':
                  echo 'Tháng Chín';
              break;
              case '10':
                  echo 'Tháng Mười';
              break;
              case '11':
                  echo 'Tháng Mười Một';
              break;
              case '12':
                  echo 'Tháng Mười Hai';
              break;
          }
        
        
        ?>
        
        (<span></span>)</div>
	<div class="subcodin subtab<?php echo $p; ?>"  <?php if ($jm != 1){ ?> style="display: none;" <?php } ?> >
    <?php foreach ($listh as $post){        
          $checkdate = $post->created;        
            if ($p !=  date('m',strtotime($checkdate)) ){
                continue;
            }
            $k++;
            
         
    ?>
        
      <?php require(JModuleHelper::getLayoutPath('mod_easyblogmostpopularpost', 'default_item')); ?>
        
   <?php }?>
   <span class="totalbogint" ref="mth<?php echo $p; ?>" style="display:none;" ><?php echo $k; ?></span>  
   </div>
   
   
      <?php  } ?>
</div>
<div id="tababc2" style="display: none;">
   <?php foreach ($datr2 as $p){  $jn++;  $k = 0; ?>
    <div class="codin mth<?php echo $p; ?>" ref="<?php echo $p; ?>" >
        <?php    
        
          switch ($p){
              case '01':
                  echo 'Tháng Một';
              break;              
              case '02':
                  echo 'Tháng Hai';
              break;
              case '03':
                  echo 'Tháng Ba';
              break;
              case '04':
                  echo 'Tháng Tư';
              break;
              case '05':
                  echo 'Tháng Năm';
              break;
              case '06':
                  echo 'Tháng Sáu';
              break;
              case '07':
                  echo 'Tháng Bảy';
              break;
              case '08':
                  echo 'Tháng Tám';
              break;
              case '09':
                  echo 'Tháng Chín';
              break;
              case '10':
                  echo 'Tháng Mười';
              break;
              case '11':
                  echo 'Tháng Mười Một';
              break;
              case '12':
                  echo 'Tháng Mười Hai';
              break;
          }
        
        
        ?>
        
        
        (<span></span>)</div>
	<div class="subcodin subtab<?php echo $p; ?>"  <?php if ($jn != 1){ ?>  style="display: none;" <?php } ?> >
    <?php foreach ($listc as $post){        
          $checkdate = $post->created;        
            if ($p !=  date('m',strtotime($checkdate)) ){
                continue;
            }
            $k++;
    ?>
        
      <?php require(JModuleHelper::getLayoutPath('mod_easyblogmostpopularpost', 'default_item')); ?>
        
   <?php }?>
   <span class="totalbogint" ref="mth<?php echo $p; ?>" style="display:none;" ><?php echo $k; ?></span>  
   </div>
   
   
      <?php  } ?>
</div>




<?php } else {  
    
   
    
    
    $datr1 = array_unique($datr1);
    $datr2 = array_unique($datr2);
    arsort($datr1); arsort($datr2);
?>

<ul class="tabs"><li class="tab"><div  id="litabaabc1" class="linkopen"  aria-selected="true" role="tab" aria-controls="module_125">Health &amp; Travel</div></li><li class="tab"><div  id="litabaabc2" class="linkclosed" aria-selected="false" role="tab" aria-controls="module_161">Company</div></li></ul>
<div id="tababc1">
   <?php foreach ($datr1 as $p){ $jn++;  $k = 0; ?>
    <div class="codin mth<?php echo $p; ?>" ref="<?php echo $p; ?>" ><?php    echo date("F",strtotime("2016-".$p.'-01')); ?> (<span></span>)</div>
	<div class="subcodin subtab<?php echo $p; ?>" <?php if ($jn != 1){ ?> style="display: none;" <?php } ?>>
    <?php foreach ($listh as $post){        
          $checkdate = $post->created;        
            if ($p !=  date('m',strtotime($checkdate)) ){
                continue;
            }
            $k++;
            
         
    ?>
        
      <?php require(JModuleHelper::getLayoutPath('mod_easyblogmostpopularpost', 'default_item')); ?>
        
   <?php }?>
   <span class="totalbogint" ref="mth<?php echo $p; ?>" style="display:none;" ><?php echo $k; ?></span>  
   </div>
   
   
      <?php  } ?>
</div>
<div id="tababc2" style="display: none;">
   <?php foreach ($datr2 as $p){ $jm++;   $k = 0; ?>
    <div class="codin mth<?php echo $p; ?>" ref="<?php echo $p; ?>" ><?php    echo date("F",strtotime("2016-".$p.'-01')); ?> (<span></span>)</div>
    <div class="subcodin subtab<?php echo $p; ?>" <?php if ($jm != 1){ ?> style="display: none;" <?php } ?>>
    <?php foreach ($listc as $post){        
          $checkdate = $post->created;        
            if ($p !=  date('m',strtotime($checkdate)) ){
                continue;
            }
            $k++;
    ?>
        
      <?php require(JModuleHelper::getLayoutPath('mod_easyblogmostpopularpost', 'default_item')); ?>
        
   <?php }?>
   <span class="totalbogint" ref="mth<?php echo $p; ?>" style="display:none;" ><?php echo $k; ?></span>  
   </div>
   
   
      <?php  } ?>
</div>


<?php } ?>



    
    
    <?php } else { ?>
        <?php echo JText::_('MOD_LATESTBLOGS_NO_POST'); ?>
    <?php } ?>
</div>
<script>
jQuery( document ).ready(function() {   
    jQuery(".codin").click(function() { 
          if (jQuery(this).hasClass('subactiv')){
               jQuery(".subtab"+jQuery(this).attr('ref')).hide();
               jQuery(this).removeClass("subactiv");
          }else{
               jQuery(".subcodin").hide();
			   jQuery(".codin").removeClass("subactiv");
               jQuery(".subtab"+jQuery(this).attr('ref')).fadeIn();
               jQuery(this).addClass("subactiv");
          }
         
    
    });
    jQuery(".totalbogint").each(function(){
        jQuery( '.'+jQuery(this).attr('ref')+' span').text(jQuery(this).text());
    });
    jQuery("#litabaabc1").click(function(){
        
            jQuery("#tababc1").show();jQuery("#tababc2").hide();
            jQuery("#litabaabc1").addClass("activ");
            jQuery("#litabaabc2").removeClass("activ");
        
    });
    
    jQuery("#litabaabc2").click(function(){
        
             jQuery("#tababc2").show();jQuery("#tababc1").hide();
            jQuery("#litabaabc2").addClass("activ");
            jQuery("#litabaabc1").removeClass("activ");
        
    });
   
});


</script>