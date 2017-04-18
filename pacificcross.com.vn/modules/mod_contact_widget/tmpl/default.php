<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_countup
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();

JHtml::_('bootstrap.framework');



?>


  <script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
<style>
    .a12{
        border-radius: 3px 0px 0px 3px; clear: none; clip: auto; counter-increment: none; counter-reset: none; direction: inherit; float: none; font-family: inherit; font-style: inherit; font-variant: normal; font-weight: inherit; height: auto; letter-spacing: normal; line-height: inherit; list-style: inherit inside none; max-height: none; max-width: none; opacity: 1; padding: 0px; table-layout: auto; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: inherit; white-space: normal; width: 30px; word-spacing: normal; display: inline-block; margin: 0px; cursor: pointer; box-sizing: content-box; color: rgb(255, 255, 255); overflow: hidden; position: fixed; z-index: 999999999; font-size: 14px; text-align: center; border-width: 1px 0px 1px 1px; border-style: solid; border-color: rgb(255, 255, 255); box-shadow: rgb(148, 136, 136) 0px 0px 3px 0px, white 0px 0px 3px 0px; min-height: 100px; min-width: 10px; right: 0px; top: 199.15px; background: none 0px 0px repeat scroll rgb(222, 20, 37);
    }
    .a13{
        clear: none; clip: auto; color: inherit; counter-increment: none; counter-reset: none; cursor: auto; direction: inherit; display: inline; float: none; font-family: inherit; font-size: inherit; font-style: inherit; font-variant: normal; font-weight: inherit; letter-spacing: normal; line-height: inherit; max-height: none; max-width: none; opacity: 1; table-layout: auto; text-align: inherit; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: visible; white-space: normal; word-spacing: normal; border-collapse: collapse; border-spacing: 0px; height: auto;  margin: 0px; list-style: none outside none; outline: none medium; overflow: hidden; position: fixed; width: 300px; z-index: 2147483645; border: 3px solid rgb(255, 255, 255); border-radius: 5px; box-shadow: rgb(148, 136, 136) 0px 0px 6px 0px, white 0px 0px 10px 0px; padding: 0px; box-sizing: content-box; background-image: none; background-attachment: scroll; background-color: white; background-position: 0px 0px; background-repeat: repeat;bottom: 10px;right: 0px;
    }
    .a14{
        border: medium none black; border-radius: 0px; clear: none; clip: auto; counter-increment: none; counter-reset: none; direction: inherit; float: none; font-family: inherit; font-style: inherit; font-variant: normal; font-weight: inherit; height: auto; letter-spacing: normal; line-height: inherit; list-style: inherit inside none; margin: 0px; max-height: none; max-width: none; opacity: 1; overflow: visible; position: static; table-layout: auto; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: inherit; white-space: normal; width: auto; word-spacing: normal; z-index: auto; color: rgb(255, 255, 255); padding: 5px 10px; box-shadow: rgb(148, 136, 136) 0px 0px 2px 0px, rgb(26, 135, 199) 0px 0px 2px 0px; text-align: justify; font-size: 14px; display: block; cursor: move; background: rgb(222, 20, 37);
    }
    #captchaResult {
    display: inline-block;
    width: 50px;
}
	
	.label{border:1px solid #000}.table{border-collapse:collapse!important}.table td,.table th{background-color:#fff!important}.table-bordered th,.table-bordered td{border:1px solid #ddd!important}}@font-face{font-family:'Glyphicons Halflings';src:url(../fonts/glyphicons-halflings-regular.eot);src:url(../fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'),url(../fonts/glyphicons-halflings-regular.woff) format('woff'),url(../fonts/glyphicons-halflings-regular.ttf) format('truetype'),url(../fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format('svg')}.glyphicon{position:relative;top:1px;display:inline-block;font-family:'Glyphicons Halflings';font-style:normal;font-weight:400;line-height:1;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.glyphicon-asterisk:before{content:"\2a"}.glyphicon-plus:before{content:"\2b"}.glyphicon-euro:before,.glyphicon-eur:before{content:"\20ac"}.glyphicon-minus:before{content:"\2212"}.glyphicon-cloud:before{content:"\2601"}.glyphicon-envelope:before{content:"\2709"}.glyphicon-pencil:before{content:"\270f"}.glyphicon-glass:before{content:"\e001"}.glyphicon-music:before{content:"\e002"}.glyphicon-search:before{content:"\e003"}.glyphicon-heart:before{content:"\e005"}.glyphicon-star:before{content:"\e006"}.glyphicon-star-empty:before{content:"\e007"}.glyphicon-user:before{content:"\e008"}.glyphicon-film:before{content:"\e009"}.glyphicon-th-large:before{content:"\e010"}.glyphicon-th:before{content:"\e011"}.glyphicon-th-list:before{content:"\e012"}.glyphicon-ok:before{content:"\e013"}.glyphicon-remove:before{content:"\e014"}.glyphicon-zoom-in:before{content:"\e015"}.glyphicon-zoom-out:before{content:"\e016"}.glyphicon-off:before{content:"\e017"}.glyphicon-signal:before{content:"\e018"}.glyphicon-cog:before{content:"\e019"}.glyphicon-trash:before{content:"\e020"}.glyphicon-home:before{content:"\e021"}.glyphicon-file:before{content:"\e022"}.glyphicon-time:before{content:"\e023"}.glyphicon-road:before{content:"\e024"}.glyphicon-download-alt:before{content:"\e025"}.glyphicon-download:before{content:"\e026"}.glyphicon-upload:before{content:"\e027"}.glyphicon-inbox:before{content:"\e028"}.glyphicon-play-circle:before{content:"\e029"}.glyphicon-repeat:before{content:"\e030"}.glyphicon-refresh:before{content:"\e031"}.glyphicon-list-alt:before{content:"\e032"}.glyphicon-lock:before{content:"\e033"}.glyphicon-flag:before{content:"\e034"}.glyphicon-headphones:before{content:"\e035"}.glyphicon-volume-off:before{content:"\e036"}.glyphicon-volume-down:before{content:"\e037"}.glyphicon-volume-up:before{content:"\e038"}.glyphicon-qrcode:before{content:"\e039"}.glyphicon-barcode:before{content:"\e040"}.glyphicon-tag:before{content:"\e041"}.glyphicon-tags:before{content:"\e042"}.glyphicon-book:before{content:"\e043"}.glyphicon-bookmark:before{content:"\e044"}.glyphicon-print:before{content:"\e045"}.glyphicon-camera:before{content:"\e046"}.glyphicon-font:before{content:"\e047"}.glyphicon-bold:before{content:"\e048"}.glyphicon-italic:before{content:"\e049"}.glyphicon-text-height:before{content:"\e050"}.glyphicon-text-width:before{content:"\e051"}.glyphicon-align-left:before{content:"\e052"}.glyphicon-align-center:before{content:"\e053"}.glyphicon-align-right:before{content:"\e054"}.glyphicon-align-justify:before{content:"\e055"}.glyphicon-list:before{content:"\e056"}.glyphicon-indent-left:before{content:"\e057"}.glyphicon-indent-right:before{content:"\e058"}.glyphicon-facetime-video:before{content:"\e059"}.glyphicon-picture:before{content:"\e060"}.glyphicon-map-marker:before{content:"\e062"}.glyphicon-adjust:before{content:"\e063"}.glyphicon-tint:before{content:"\e064"}.glyphicon-edit:before{content:"\e065"}.glyphicon-share:before{content:"\e066"}.glyphicon-check:before{content:"\e067"}.glyphicon-move:before{content:"\e068"}.glyphicon-step-backward:before{content:"\e069"}.glyphicon-fast-backward:before{content:"\e070"}.glyphicon-backward:before{content:"\e071"}.glyphicon-play:before{content:"\e072"}.glyphicon-pause:before{content:"\e073"}.glyphicon-stop:before{content:"\e074"}.glyphicon-forward:before{content:"\e075"}.glyphicon-fast-forward:before{content:"\e076"}.glyphicon-step-forward:before{content:"\e077"}.glyphicon-eject:before{content:"\e078"}.glyphicon-chevron-left:before{content:"\e079"}.glyphicon-chevron-right:before{content:"\e080"}.glyphicon-plus-sign:before{content:"\e081"}.glyphicon-minus-sign:before{content:"\e082"}.glyphicon-remove-sign:before{content:"\e083"}.glyphicon-ok-sign:before{content:"\e084"}.glyphicon-question-sign:before{content:"\e085"}.glyphicon-info-sign:before{content:"\e086"}.glyphicon-screenshot:before{content:"\e087"}.glyphicon-remove-circle:before{content:"\e088"}.glyphicon-ok-circle:before{content:"\e089"}.glyphicon-ban-circle:before{content:"\e090"}.glyphicon-arrow-left:before{content:"\e091"}.glyphicon-arrow-right:before{content:"\e092"}.glyphicon-arrow-up:before{content:"\e093"}.glyphicon-arrow-down:before{content:"\e094"}.glyphicon-share-alt:before{content:"\e095"}.glyphicon-resize-full:before{content:"\e096"}.glyphicon-resize-small:before{content:"\e097"}.glyphicon-exclamation-sign:before{content:"\e101"}.glyphicon-gift:before{content:"\e102"}.glyphicon-leaf:before{content:"\e103"}.glyphicon-fire:before{content:"\e104"}.glyphicon-eye-open:before{content:"\e105"}.glyphicon-eye-close:before{content:"\e106"}.glyphicon-warning-sign:before{content:"\e107"}.glyphicon-plane:before{content:"\e108"}.glyphicon-calendar:before{content:"\e109"}.glyphicon-random:before{content:"\e110"}.glyphicon-comment:before{content:"\e111"}.glyphicon-magnet:before{content:"\e112"}.glyphicon-chevron-up:before{content:"\e113"}.glyphicon-chevron-down:before{content:"\e114"}.glyphicon-retweet:before{content:"\e115"}.glyphicon-shopping-cart:before{content:"\e116"}.glyphicon-folder-close:before{content:"\e117"}.glyphicon-folder-open:before{content:"\e118"}.glyphicon-resize-vertical:before{content:"\e119"}.glyphicon-resize-horizontal:before{content:"\e120"}.glyphicon-hdd:before{content:"\e121"}.glyphicon-bullhorn:before{content:"\e122"}.glyphicon-bell:before{content:"\e123"}.glyphicon-certificate:before{content:"\e124"}.glyphicon-thumbs-up:before{content:"\e125"}.glyphicon-thumbs-down:before{content:"\e126"}.glyphicon-hand-right:before{content:"\e127"}.glyphicon-hand-left:before{content:"\e128"}.glyphicon-hand-up:before{content:"\e129"}.glyphicon-hand-down:before{content:"\e130"}.glyphicon-circle-arrow-right:before{content:"\e131"}.glyphicon-circle-arrow-left:before{content:"\e132"}.glyphicon-circle-arrow-up:before{content:"\e133"}.glyphicon-circle-arrow-down:before{content:"\e134"}.glyphicon-globe:before{content:"\e135"}.glyphicon-wrench:before{content:"\e136"}.glyphicon-tasks:before{content:"\e137"}.glyphicon-filter:before{content:"\e138"}

.input-group-addon {
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    color: #555;
    text-align: center;
    background-color: #eee;
    border: 1px solid #ccc;
    border-radius: 4px;
}
	
		.input-group-addon, .input-group-btn {
    width: 1%;
    white-space: nowrap;
    vertical-align: middle;
}
	.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: 'Glyphicons Halflings';
    font-style: normal;
    font-weight: 400;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
	.input-group {
    position: relative;
    display: table;
    border-collapse: separate;
}
	
	.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
	.input-group .form-control {
    position: relative;
    z-index: 2;
    float: left;
    width: 100%;
    margin-bottom: 0;
}
	.input-group-addon, .input-group-btn, .input-group .form-control {
    display: table-cell;
}
</style>
<script>
  function showhidew(a){
      if(a==1){
          jQuery('#dropifiContactTab').hide();
          jQuery('#dropifiContactContent').show();
      }else{
           jQuery('#dropifiContactTab').show();
           jQuery('#dropifiContactContent').hide();
      }
  }
//   jQuery(function() {
//    jQuery( "#dropifiContactContent" ).draggable({ handle: "p#dropifiTextContent" });
//    
//    jQuery( "div, p" ).disableSelection();
//  });
  </script>
 








  <div class="a12" id="dropifiContactTab" onclick="showhidew(1);">
     <div id="dropifi2012_version_inner_label" style="width:30px;margin-right:auto;margin-left:auto;padding-left:8px;"><img id="dropifi_widget_v1_imageText" style="padding:0px;display:inherit !important;border:0px;margin-top:5px;margin-bottom:8px;box-shadow: 0 0 0 transparent;cursor:pointer" src="<?php echo juri::base().'/images/contactimg.png' ?>" ></div></div>
 
 
 
 
 <div class="a13" style="display: none;" id="dropifiContactContent"  >
     
     
     <div class="a14" style="" id="dropifiContentBar">
         <p style="border: medium none black; border-radius: 0px; clear: none; clip: auto; color: rgb(255, 255, 255); counter-increment: none; counter-reset: none; direction: inherit; float: none; font-family: inherit; font-size: 14px; font-style: inherit; font-variant: normal; font-weight: normal; height: auto; letter-spacing: normal; line-height: inherit; list-style: inherit inside none; margin: 0px; max-height: none; max-width: none; opacity: 1; padding: 0px 5px 0px 0px; position: static; table-layout: auto; text-align: left; text-decoration: inherit; text-transform: none; unicode-bidi: normal; vertical-align: baseline; visibility: inherit; white-space: normal; word-spacing: normal; z-index: auto; overflow: hidden; cursor: move; display: inline-block; width: 90%; background-image: none; background-attachment: scroll; background-color: transparent; background-position: 0px 0px; background-repeat: repeat;" id="dropifiTextContent">Contact Us</p>
         
         <p style="background-attachment:scroll;background-color:transparent;background-image:none;background-position:0 0;background-repeat:repeat;border-color:black;border-radius:0;border-style:none;border-width:medium;clear:none;clip:auto;color:inherit;counter-increment:none;counter-reset:none;cursor:auto;direction:inherit;display:inline;float:none;font-family:inherit;font-size:inherit;font-style:inherit;font-variant:normal;font-weight:inherit;height:auto;letter-spacing:normal;line-height:inherit;list-style-image:none;list-style-position:inside;list-style-type:inherit;margin:0;max-height:none;max-width:none;opacity:1;overflow:visible;padding:0;position:static;table-layout:auto;text-align:inherit;text-decoration:inherit;text-transform:none;unicode-bidi:normal;vertical-align:baseline;visibility:inherit;white-space:normal;width:auto;word-spacing:normal;z-index:auto;float:right;cursor:pointer;position:relative;top:0px;margin-right:-5px;display:block;   font-weight: bold!important;" onclick="showhidew(2); " > X
         </p></div>
        <div id="dropifiIframeContainer" style="height: 270px; background-image: url(&quot;&quot;);" >
            
   <div id="contentInner" style="font-family:SansSerif">
		<form style="font-family:SansSerif" id="fileupload" name="fileupload" action="index.php" method="POST" enctype="multipart/form-data">
			<div id="formContent">
                            <p id="header"> </p>
                            <p class="input-group"><span class="input-group-addon glyphicon glyphicon-envelope"></span>
                                <input id="widgetField_name" type="text" name="cs.name" class="form-control" placeholder="your name" required=""><span style="display:none" id="widgetError_email">your name is required</span> </p>
                            
                            <p class="input-group"><span class="input-group-addon glyphicon glyphicon-envelope"></span>
                                <input id="widgetField_email" type="email" name="cs.email" class="form-control" placeholder="your email" required=""><span style="display:none" id="widgetError_email">your email is required</span> </p>
                           
                            <!-- <p class="input-group">
                                <span class="input-group-addon glyphicon glyphicon-pencil"></span>
                              
                                <select id="widgetField_subject" name="cs.subject" class="form-control" required="">
                                     <option  value="No Type" id="optCustomer Support">--Choice Product--</option>
                                    <?php
                                    
                                    $grouptype = explode('|', $grouptype);
                                    foreach ($grouptype as $r) { ?>
                                         <option value="<?php echo $r ?>" ><?php echo $r ?></option>
                                    <?php } ?>
                                   
                              -->  
                                  
                                </select>
                                <span style="display:none" id="widgetError_subject">the subject of the message is required</span> 
                            
                            </p><p>
                                <textarea style="    height: 70px;" id="widgetField_message" name="cs.message" rows="3" class="form-control" cols="20" placeholder="how can we help you?" required=""></textarea>
                                <span style="display:none" id="widgetError_message">your message is required</span> 
                            </p>
                           <?php if($fileupload == 1 && 1 == 10){ ?>
                            <p><span id="fileContent"><input id="widgetField_attachment" name="uploadfile" type="file"></span></p>
                          <?php } ?>
                           <?php if($captcha == 1){ ?>
                            <p style="    float: left;"><span>What's the result of </span><span id="captchaText"><?php $a = rand(1,10);echo $a ?> + <?php $b = rand(1,10);echo $b  ?> = </span>
                           <input id="captchaResult" name="cs.captchaResult" type="text" class="form-control" required=""></p>
                           <?php } else { $a=0;$b=0; ?>
                            <input id="captchaResult" name="cs.captchaResult" type="hidden" value="<?php echo $a+$b; ?>">
                           <?php } ?>
                           <input type="hidden" id="captchaCode" name="cs.captchaCode" value="<?php echo base64_encode($a+$b); ?>">
                           <input type="hidden" id="url" name="mail_resent" value="<?php echo $email ?>">
                           <input type="hidden"  name="option" value="com_contact">
                           <input type="hidden"  name="task" value="savewcontact">
                           
                            <?php echo JHtml::_('form.token'); ?>
                          <p class="dropifi_submit_input"> <input id="sendMail" style="background-color:#0D0B0B;color:#ffffff;border-color:#0D0B0B;    width: 100%;" class="btn btn-primary" name="sendMail" value="Send Message" type="submit"> </p></div>
			
		</form>
	</div>
    
    
    </div></div>
  
  
   