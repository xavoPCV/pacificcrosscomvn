<?php
defined('_JEXEC') or die;
$custome_url  =  $this->custome_url;
$db = jfactory::getDBo();
$corlorctatr = "#f4f4f4"; 
use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

if ($this->allsetting->newsletter_api == "C" ){
    
            // Get api details
            //
            $APIKEY = CONSTANT_APIKEY;
            $ACCESS_TOKEN =  $this->allsetting->api_key;
            $custome_url  =  $this->allsetting->custom_link_article;
            // Get all lists of constant contact
            $error = '';

            $cc = new ConstantContact($APIKEY);
            try{  
                    $groups = $cc->getLists($ACCESS_TOKEN);
            }catch (CtctException $ex) {
                    $ccerror = $ex->getErrors();
                    $error .= $ccerror[0]['error_message'];
                    $app = JFactory::getApplication();
                    $app->enqueueMessage(JText::_($ccerror[0]['error_message'])." - ( from Constant Contact)",'error');
                    $app->redirect('index.php?option=com_enewsletter');
                   // echo 'wrong';
                    die();
            }
            
        }
  
   if ($this->allsetting->newsletter_api == "M" ){    


        $APIKEY  = CONSTANT_APIKEY;
        $ACCESS_TOKEN = $this->allsetting->api_key;
     
        $api = new MCAPI(trim($ACCESS_TOKEN));
        $groups = $api->lists();
        
        if ($api->errorCode){
                $app->enqueueMessage(JText::_($api->errorMessage),'error');
                $app->redirect('index.php');
               die();
        } 

        $groups = $groups['data'];

    
    }

?>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="administrator/components/com_enewsletter/css/jquery-ui.min.css">
        <link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/newsletter.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/bootstrap.min.js"></script>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/bootstrap-switch.js"></script>
        <link href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/bootstrap-switch.css" rel="stylesheet">
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/croppie.js"></script>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/demo/prism.js"></script>
        <link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/demo_table.css">
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.bpopup.min.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/demo/prism.css" /> 
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/croppie.css" />
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.range.css" />
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.range-min.js"></script>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.dataTables.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/style2.css" />
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jscolor.min.js"></script>



        <style>
            .croppie-container {
                padding: 0;margin-left: -35px;
           }
           
              <?php if ( $this->optionf == 1 ) {
        echo "#content-control{ display:none; }";
    } ?>
           li{
                   list-style: none;
           }      
           .fa-3x{
                   color: #777;
                   border: 1px solid #ccc;
                   font-size: 26px!important;
           }
        </style>
            <style>
                .button-blu{
                        float: left!important;
    clear: both!important;
                }
                  .btclose {
                        position: absolute;
                            right: 30px;
                            top: 30px;
                            color: red;
                            font-size: 29px;
                            font-weight: bold;
                            font-family: cursive;
                            cursor: pointer;
                }
            body{
                    margin-top: 40px;
            }
              .allpage {
               width: 100% ;
              }
              .col-1{
                      width: 380px!important;
                     margin-left: 10px;
              }
              .block-2{
                      width: 95%!important;
              }
              #adform{
                     
                          padding-left: 65px;
              }
              .col-2{
                  width: 76%;
              }
              input[type=text] {
                      width: 96%;
                      font-size: 1em;
                      margin-left: -15px;
                      margin-bottom: 20px;
              }
              .col-1  form {
                  font-size: 14px;
              }
              .bootstrap-switch {
                      height: 30px;
                            margin-top: -11px;
    margin-right: 3px;
              }
              .button-blu{
                    
                    background: url('<?php echo juri::base();?>components/com_enewsletter/assets/images/save.png');
                    background-repeat: no-repeat;
                 
                 
                    color: rgba(0, 0, 0, 0.1);
                    font-size: 1px;
                    height: 50px;   
                    float: right;
                    width: 129px;
                    background-size: 100%;
                    margin-top: 15px!important;
              }
                #weekly:hover,   #invest:hover, #address:hover, #logomail img:hover, .module:hover{
                  cursor: pointer;
                  border: none!important;
                  border-radius: 5px;
              }
              #cloud-tag{
                      padding: 0px !important;
              }
              .header1{
                  margin-bottom: 0px;
              }
              .text1{
                  color: #777;
                  font-size: 13px!important;
              }
              .edittem:hover{
                  border: solid 2px red;
              }
              .edittem {
                    font-size: 16.5px;
                    padding: 21px 10px 22px 43px; 
                    border-radius: 7px;  
                    color: #666;  
                    cursor: pointer;
                  
                    background-repeat: no-repeat; 
                    background-position: left;
              }
              #verified_emails {
                      width: 310px!important;
    padding: 10px;
    margin-left: -13px;
    margin-top: 9px;
        margin-left: 0px;
              }
              #jform_subject {
                      margin-left: 0px;
              }
              .aask1{
                    background-image: url('/components/com_enewsletter/assets/images/addnew.jpg'); 
                        background-size: 35px;
    background-position-x: 8px;
              }
              .aask2{
                    background-image: url('/components/com_enewsletter/assets/images/open.jpg'); 
                        background-size: 35px;
    background-position-x: 8px;
              }
              .aask3{
                    background-image: url('/components/com_enewsletter/assets/images/his.jpg'); 
                        background-size: 35px;
    background-position-x: 8px;
              }
              .tabs-cta {
                background: #eee;
                padding-left: 15px;
                display: flex;
              }
              .tabs-cta li:hover {
                  background: #ccc;
              }
              .tabs-cta li{
                     padding: 10px;
                    width: 75px;
                    float: left;
                    text-align: center;
                    cursor: pointer;
              }
              .tabs-cta .acctive {
                  background: #fff;
              }
              .adreinput {
                       width: 73%!important;
    margin-left: 5px!important;
    float: left;
              }
              .sqs-navigation-item a {
                      padding-top: 20px; 
                        padding-bottom: 20px; 
                            display: inline-block;
              }
              
              .sqs-navigation-item a:hover {
                     background: #f02b3b;
                        color: #fff!important;
                     
              }
               .sqs-navigation-item a:hover img {
                -webkit-filter: brightness(0) invert(1);
                filter: brightness(0) invert(1);

                     
              }
              .addlabel {
                    width: 24%;
    display: block;
    float: left;
    margin-top: 24px;
    text-transform: uppercase;
        font-size: 12px;
              }
        </style>
        
        <style>
            .tabcontrol {
                width: 96%;
    float: left;
    margin-left: 0px;
    padding-left: 12px;
            }
            .tabcontrol li:first-child {
                 border-top:1px solid #ccc;
            }
                        .tabcontrol li {
                                width: 93%;
                                text-align: left;
                                float: left;
                                padding-top: 5px;
                                padding-bottom: 5px;
                                padding-left: 10px;
                                cursor: pointer;
                                border-bottom:1px solid #ccc;
                                font-size: 17px;
                        }
                        .tabcontrol li:hover , .tabcontrol .acctive{
                                background-color: #f02b3b;
                                color: #fff;
                        }
                        .tabcontrol{
                                -webkit-transition: all 0.3s;
                                -o-transition: all 0.3s;
                                transition: all 0.3s;
                        }
                        .faketabcontrol {
                            background-color:#fff;
                        }
                        
        </style>
                    
         <script>
           
        
           $(document).ready(function(){
               
               $(".cta-btn-circle").click(function(){
                    if ( $(this).attr('data_href')){
                        window.location.replace($(this).attr('data_href'));
                    }else {
                        window.location.replace("<?php echo juri::base(); ?>");
                    }
                });
                
               $('.button-blu').click(function(){    
               
    savesetting111();
});

               function savesetting111() {
	   
		$( "#ajaxloadingplaceholder" ).dialog({
									dialogClass: "no-close dialog_style1",
									draggable: false,
									modal: true,
									title: "Saving..."
								});
		
                setTimeout(function(){ $("#ajaxloadingplaceholder").dialog( "close" ); },2000);
	
		
	}
  
                function aa(){
                      $(".blocks1,.blocks2,.blocks3,.blocks4,.blocks5,.blocks6,.blocks7,.blocks8,.blocks9,.blocks10,.blocks11,.blocks12,.blocks13,.blocks14,.blocks15,.blocks16,.blocks17,.blocks18,.blocks19,.blocks20").hide();
                }
                aa();
                $(".blocks7,.blocks9").show();
                
                $(".tabcontrol .tab1").click(function() {
                    $(".tabcontrol li").removeClass('acctive');
                    $(this).addClass('acctive');
                   // jQuery(this).detach().appendTo('.tabcontrol');
                     $('.setname').text('Setting Own Content');
                     aa();
                     $("#typecontentchoice").val('tab2');
                     $(".blocks7,.blocks9,.blocks10").show();
                });
                $(".tabcontrol .tab2").click(function() {
                    $(".tabcontrol li").removeClass('acctive');
                      $(this).addClass('acctive');
                        //  jQuery(this).detach().appendTo('.tabcontrol');
                      $('.setname').text('Weekly Investment Update');
                      aa();
                      $("#typecontentchoice").val('tab2');
                      $(".blocks1,.blocks2,.blocks3,.blocks4,.blocks5,.blocks6,.blocks7,.blocks8,.blocks9,.blocks10").show();
                      $('.others_area').show();
                      $('.setting_widgets_area, .wekllyvid').hide();
                });
                $(".tabcontrol .tab3").click(function() {
                    $(".tabcontrol li").removeClass('acctive');
                      $(this).addClass('acctive');
                          $('.setname').text('Weekly Financial Planning Update');
                       //   jQuery(this).detach().appendTo('.tabcontrol');
                       aa();
                      $("#typecontentchoice").val('tab2');
                      $(".blocks9,.blocks13,.blocks14,.blocks15,.blocks16").show();
                      $('.others_area').show();
                      $('.setting_widgets_area , .wekllyvid').hide();
                });
                $(".tabcontrol .tab4").click(function() {
                    $(".tabcontrol li").removeClass('acctive');
                      $(this).addClass('acctive');
                      //   jQuery(this).detach().appendTo('.tabcontrol');
                      $('.setname').text('Weekly Video');
                      aa();   
                        $('.wekllyvid').show();
                      $('.setting_widgets_area , .others_area').hide();
                     // $("#typecontentchoice").val('tab4');
                      $(".blocks17,.blocks9,.blocks20,.blocks11,.blocks12,.blocks17,.blocks18,.blocks19").show();
                });
                $(".tabcontrol .tab5").click(function() {
                     $(".tabcontrol li").removeClass('acctive');
                      $(this).addClass('acctive');               
                      $('.setname').text('Widgets Setting');
                      aa();                       
                    
                });
                <?php if ($this->com_params->get('typecontentchoice') != ''){ ?>
                        $(".tabcontrol .<?php echo $this->com_params->get('typecontentchoice'); ?> ").trigger( "click" );
                <?php } ?>        
                jQuery(".left_menu_wrap").show();
                
                
             
                 $('.status').bootstrapSwitch();
                 
                 
                
                
                         
           });  
         
          function checkouto(a){
              
                if (a == 1){
                    $(".specclass1").hide();
                    $(".specclass2").hide();
                }
                if (a == 2){
                    $(".specclass1").hide();
                    $(".specclass2").show();
                }
                if (a == 3){
                    $(".specclass1").show();
                    $(".specclass2").show();
                }
          }
          function showedit(a){
                
                $('#global-edit').hide();
                $('#global-control').css("border-bottom","1px solid #ddd");
                $('#global-control').css("color","#000");
                
                $('#address-edit').hide();
                $('#address-control').css("border-bottom","1px solid #ddd");
                $('#address-control').css("color","#000");
                
                $('#header-edit').hide();
                $('#header-control').css("border-bottom","1px solid #ddd");
                $('#header-control').css("color","#000");
                
                $('#weekly-edit').hide();
                $('#weekly-control').css("border-bottom","1px solid #ddd");
                $('#weekly-control').css("color","#000");
                
                $('#'+a+'-edit').show();
                $('#'+a+'-control').css("border-bottom","2px solid red");
             
              
              
               
            }
         
         </script>
<script>
$(document).ready(function() {
            
	
   var api = $('#apikey').val();
   var apitype = $('#newsletter_api').val(); 
  
    if(api){
        $.ajax({
               type : 'POST',
        			url: 'index.php?option=com_enewsletter&task=getverifiedemaillist',
              data :   'apitype='+apitype+'&apikey='+api,
        			success: function( data ){
                    $('#verified_email').html(data);       
                    
                    $('#verified_email').val('<?php echo $this->allsetting->from_email; ?>');
              }
        		}); 
      }


                $('#jform_subject').blur(function (){                    
                    if ( $.trim($(this).val())  != '') {
                                        $('.seving').show();
                                         $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savesuject",
                                          type: "POST",
                                          data:  "&sub="+$.trim($(this).val())+"&idt=<?php echo $this->idt; ?>&changetemps=<?php  echo $this->changetemps_lauout ?>"                                
                                         }).done(function( data ) {  
                                             $('.seving').hide();
                                             $('.sed').show();
                                             setTimeout(function(){$('.sed').hide();},2000);
                                        });     
                    }
            });


	} );

</script>
        <div class="allpage" >
              <input type="hidden" id="valueidartical" value="" >
              <div class="col-1"  style="" > 
                
                <?php  
                
                $config = new JConfig();		
		$database = $config->db;
                $session =& JFactory::getSession();              
                
                if ($session->get(md5($database.$database) , 0) == 1 ){ 
                    ?>
                <style>
                 .list_menu_button   .icon {
    display: block;
    float: left;
    width: 105px!important;
    height: 116px!important;
    border-radius: 2px;
    box-shadow: 0 3px 13px #ccc;
    margin: 6px 6px!important;
    text-align: center;
}
.list_menu_button   .icon a{
        text-decoration: none;
}
                    
.list_menu_button  .logo-save {
        cursor: pointer;
    margin-top: 20px !Important;
    margin-bottom: 30px !Important;
    float: right !Important;
    padding: 10px 0px !Important;
    box-sizing: border-box;
    background: #ddd !Important;
    color: #ccc !important;
    border-radius: 5px !Important;
    text-align: center !Important;
    font-weight: bold !Important;
    margin-right: 20px !Important;
    width: 70px;
    height: 38px;
    font-size: 13px;
}

            .list_menu_button     .ak-icon {
    display: block;
    width: 60px;
    height: 60px;
    text-indent: -99999px;
    overflow: hidden;
    background: transparent url(https://beta.advisorproducts.com/components/com_enewsletter/assets/images/akeeba-ui-32.png) top left no-repeat;
    margin: 4px auto 6px;
}      

.list_menu_button .ak-icon-nsetup {
    background: transparent url(https://beta.advisorproducts.com/components/com_enewsletter/assets/images/setup.jpg) top left no-repeat;
}
.list_menu_button .ak-icon-nhistory {
    background: transparent url(https://beta.advisorproducts.com/components/com_enewsletter/assets/images/history.jpg) top left no-repeat;
}
.ak-icon-nbuildnewsletter {
    background: transparent url(https://beta.advisorproducts.com/components/com_enewsletter/assets/images/newsletter.jpg) top left no-repeat!important;
}
.list_menu_button .icon:hover,.list_menu_button  .icon_advisorsetting:hover,.list_menu_button  .icon.active {
    border: none;
    box-shadow: 0px 1px 9px red;
}
                </style>
                <div class="list_menu_button">
                    <div class="div_btn" style="width: 100%;float: left;">
              
				<div class="clr"></div>
            </div><!--div_btn-->
            <div class="div_btn" style="width: 100%;float: left;">
				<style>
    .logo_row {
        display:none;
    }
     
    
</style>
  <div class="div_btn">
                <div class="logo_row1" style="">
                   
                    <div class="top_button_save_and_logout">
                        <div style="    float: left!important;       width: 123px;    font-size: 18px;    color: #666!important;"  class="logo-save btncontactus" id="btn_inactive_edittemplate_and_deletetemplate" onclick='parent.postMessage("loadMyOrders","*"); ' > &lt;&lt; Go Back</div>
					</div>                   
                </div>
				<div class="clr"></div>
            </div><!--div_btn-->
            
              </div><!--div_btn-->
        </div>
                <?php 
                } else{
                    
                     include  JPATH_SITE.DS.'modules'.DS.'mod_leftmenuedit'.DS.'menu_defautls.php'; 
                     
                } ?>
                
                        
                       
                        
                   
                <div class="main-1 left_menu_wrap" style="  display:none;  margin-bottom: 58px;">
                    
                  <div style="background-color: #e4e4e4 !important; padding-left: 40px;"><div class="sqs-navigation-item"> <a style="    color: #000;    font-size: 16px;   text-decoration: none; text-transform: uppercase;" title="Enewsletter" href="#" onclick="window.location='<?php echo juri::base(); ?>edit-enewsletter'" ><img src="<?php echo juri::base();?>components/com_enewsletter/assets/images/iconsetting.png" style="width:20px;    margin-bottom: -5px;"  /> Enewsletter </a> <a style="  color: #000;    font-size: 16px;    margin-left: 20px;    text-decoration: none;    text-transform: uppercase;" title="Setting" href="#"  ><img src="<?php echo juri::base();?>components/com_enewsletter/assets/images/iconenew.png" style="width:20px;    margin-bottom: -5px;" /> Settings</a>   </div>  </div>
                    
                    
                    <!--#HT-->
					
                    <br>
                    <ul class="tabcontrol">
                        <li class="tab5" id="setting_widgets" >Widgets</li>
                        <li class="tab1 acctive" >Own Content</li>
                        <li class="tab2"  >Weekly Investment Update</li>
                        <li class="tab3" >Weekly Financial Planning Update</li>
                        <li class="tab4" >Weekly Video</li>
                     
                    </ul>
                  
                    <h3 class="setname" style="       width: 98%;      float: left;    border-bottom: 1px solid #999;    padding-bottom: 5px;    margin-bottom: 20px;
    padding-left: 12px;">Setting</h3>
                 
                    <form id="adform" method="post" action=""  enctype="multipart/form-data" style="text-align: center;height: 35px;" >
                        <textarea name="htmlcode" id="htmlcode" style="display: none;"></textarea>
                       
                        <br>
                        
                        <div class="seving" style="color: red;display: none;     margin-top: -104px;    right: 65px;    font-size: 18px;   position: absolute;" > Saving... </div>
                        <div class="sed" style="color: blue;display: none;       margin-top: -104px;    right: 65px;    font-size: 18px;   position: absolute;"> Saved</div>
                        
                     
                      
                         <button onclick="$('#popup6_open').bPopup();" id="adform-button1" type="button" style=" display:none ;   border: none;    text-align: center;        margin: -38px -27px 25px;   padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;     font-size: 0;    background: url('images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 255px;    height: 47px;      " >Send Mail</button>
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" id="task" name="task" value="savehtml" >
                         <input type="hidden" name="csslink" value="<?php echo JURI::base(); ?>components/com_enewsletter/assets/newsletter.css" >
                         <input type="hidden" name="view" value="editletter" >
                         
                         <input id="changetemps"  name="changetemps"  value="<?php  echo $this->changetemps_lauout ?>" type="hidden" />
                                 
                         <input type="hidden" name="tmpl" value="component" >
                         <input type="hidden" id="new_tem" name="new_tem" value="" >
                         <input type="hidden"  id="idt" name="idt" value="<?php echo $this->idt; ?>" >
                         <input type="hidden" name="filen" value="<?php echo $this->filen; ?>" >
                         <input type="hidden" name="optionf" id="optionf" value="" >
                         <?php echo JHtml::_('form.token'); ?>
                        
                    </form>
                    
                   
                    
                   
                    
                     
                    
    				<div id="global-control" class="block-2"  style="display: none;" onclick=" showedit('global');">
                        <div class="image1" > <i class="fa fa-user fa-3x"></i>  </div>
                        <div class="text1" > Global Setting  <div style="float:right;" ></div></div>                      
                        
                    </div>
                    
                    <div id="global-edit" class="block-3" style="display: none;">
                         
                            <form id="form1" name="form1" method="post" action="index.php"  enctype="multipart/form-data"  >
                                Select email campaign service<span class="star"> *</span><br><br>
                                <div class="radio">
                			<input type="radio" value="C" class="newsletter_api" name="jform[newsletter_api]" checked="true" disabled="">
					<label>Constant Contact</label>
					<input type="radio" value="M" class="newsletter_api" name="jform[newsletter_api]" disabled="">
					<input type="hidden" name="jform[email_campaign_key]" id="email_campaign_key" value="C">
                                        <label>Mailchimp</label>
				</div>
                                <label class="addlabel">Login Name<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->api_login_name; ?>" type="text"  name="loginname" placeholder="" >
                                <label class="addlabel">Api Key<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->api_key; ?>" type="text" name="apikey" placeholder="" >
                                <label class="addlabel">Archive CC List<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->archive_cc_list; ?>" type="text" name="apikeycc" placeholder="" >
                                <label class="addlabel">Firm<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->firm; ?>" type="text" name="firm" placeholder="" >
                                <label class="addlabel">Url<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->url; ?>" type="text" name="url" placeholder="" >
                                <label class="addlabel">Path to quote<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->path_quote; ?>" type="text" name="path_to_quote" placeholder="" >
                                <label class="addlabel">Custom Link For Articles<span class="star"> *</span> </label>     <input class="adreinput" value="<?php echo $this->allsetting->custom_link_article; ?>" type="text" name="path_to_quote" placeholder="" >
                                <label >Allow To Use Subscriber Name </label>    <input  value="Y" type="checkbox" name="allow_to_use_name" <?php if($this->allow_to_use_name == 'Y'){ echo 'checked'; } ?> >
                                <br>
                                <label >Custom Website </label>     <input  value=""  type="checkbox" name="from_name" <?php if($this->customer_website == 'Y'){ echo 'checked'; } ?> >
                                <br> <br>
                                <b>Default Details from email campaign service.</b>
                                <br> <br>
                                
                                 <label >Email <span class="star"> *</span> </label>   
                                 <div id="verified_email"></div> 
                                
                                 <label >Name <span class="star"> *</span> </label>   
                                 
                                 <input  style="margin-left: 0px;" value="<?php echo $this->allsetting->from_name; ?>" type="text"  name="loginname" placeholder="" >
                              
                                <button type="submit" class="button-blu" id="advions-save" >Save</button>    
                            
                                <input type="hidden" name="option" value="com_enewsletter" >
                                        <input type="hidden" id="task" name="task" value="setting.global" >                      
                                        <input type="hidden" name="view" value="setting" >
                                      

                                     <?php echo JHtml::_('form.token'); ?>


                            </form>
                    </div>
                       
                    <div id="address-control" class="block-2" style="display: none;" onclick=" showedit('address');">
                        <div class="image1" > <i class="fa fa-building fa-3x"></i>  </div>
                        <div class="text1" > Address information  <div style="float:right;" ></div></div>                      
                        
                    </div>
                    <div id="address-edit" class="block-3"  style="display: none;">
                        
                            <form id="form8" method="post" action="index.php"  enctype="multipart/form-data" >      
                            <fieldset class="adminform" style="border: none;">
			
			
                            
                                <label style="width: 85px;    float: left;  " >Newsletter<span class="star"> *</span></label>						
                                <input  type="checkbox" class="inputbox" value="Y"  name="Newsletter" <?php if($this->allsetting->send_newsletter == 'Y'){ echo 'checked'; } ?> />
                                         <br>
					<label style="width: 85px;    float: left;  " >Update<span class="star"> *</span></label>						
					<input type="checkbox" class="inputbox" value="Y" name="address_from_email" <?php if($this->allsetting->send_update == 'Y'){ echo 'checked'; } ?>  >
                                        <br>
                                
					<label class="addlabel" >Address<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" name="address_address1" value="<?php echo $this->allsetting->address1 ?>" >
				
					<label class="addlabel" >Address 2</label>						
					<input type="text" size="30" class="inputbox adreinput" name="address_address2" value="<?php echo $this->allsetting->address2 ?>" >
				
					<label class="addlabel" >Phone</label>						
					<input type="text" size="30" class="inputbox adreinput" name="address_phone" value="<?php echo $this->allsetting->phone ?>" >
				
					<label class="addlabel" >City<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" name="address_city" value="<?php echo $this->allsetting->city ?>" >
				
					<label class="addlabel" >Zip<span class="star"> *</span></label>						
					<input type="text" size="30" name="address_zip" class="inputbox adreinput" value="<?php echo $this->allsetting->zip ?>" >
				
					<label class="addlabel" >State<span class="star"> *</span></label>						
					<input type="text" size="30" name="address_state" class="inputbox adreinput" value="<?php echo $this->allsetting->state ?>" >
				
		</fieldset>  
                                   
                                        <input type="hidden" name="option" value="com_enewsletter" >
                                        <input type="hidden" id="task" name="task" value="setting.saveaddress" >                      
                                        <input type="hidden" name="view" value="setting" >
                                      

                                     <?php echo JHtml::_('form.token'); ?>
                                        <button type="submit" class="button-blu" id="address-save" >Save</button>                       
                       
                        </form>
                    </div>
                   
                    <div id="header-control" class="block-2" style="display: none;" onclick=" showedit('header');">
                        <div class="image1" > <i class="fa fa-facebook-official fa-3x"></i>  </div>
                        <div class="text1" > Social information  <div style="float:right;" ></div></div>                      
                        
                    </div>
                    <div id="header-edit" class="block-3" style="display: none;">
                        
                           
                            <form id="form8" method="post" action="index.php"  enctype="multipart/form-data"  >
                                <ul  class="advisordetails" style="    padding: 0;">
				
				<li>
					
                                        <ul >
                                            <?php $social = json_decode($this->allsetting->social_links); ?>
						<li><label>Facebook:</label> <input type="text" size="60" class="inputbox" value="<?php echo $social->facebook; ?>" name="facebook"> 
												
						</li>
						<li><label>Twitter:</label> <input type="text" size="60" class="inputbox" value="<?php echo $social->twitter; ?>" name="twitter"> 
												</li>
						<li><label>LinkedIn:</label> <input type="text" size="60" class="inputbox" value="<?php echo $social->linkedin; ?>" name="linkedin"> 
												</li>
					</ul>
					<div class="clr"></div>
				</li>
					
                                </ul>
                                        <input type="hidden" name="option" value="com_enewsletter" >
                                        <input type="hidden" id="task" name="task" value="setting.social" >                      
                                        <input type="hidden" name="view" value="setting" >
                                      

                                     <?php echo JHtml::_('form.token'); ?>
                                 <button type="submit" class="button-blu" id="header-save" >Save</button>   
                            </form>
                    </div>  
                    
                     <div id="weekly-control" class="block-2" style="display: none;" onclick=" showedit('weekly');">
                        <div class="image1" > <i class="fa fa-list-ul fa-3x"></i>  </div>
                        <div class="text1" > Weekly Setting  <div style="float:right;" ></div></div>                      
                        
                    </div>
                  
                    <div id="weeklya-edit" class="block-3" style="display: block;    margin-top: -65px;">
                       
                        
                      
                            <form id="form10" method="post" action="index.php"  enctype="multipart/form-data"  >
                               
                                <div class="blocks1" >
                                    <br>
                                    <label >Automation<span class="star"> *</span> </label> 
                                     
                                    
                                     <br>  <br>
                                     
                                     <div>
                                            <input onclick="checkouto('1');" type="radio" value="N" id="auto_updateN" name="autoweeklysend" <?php if( $this->allsetting->auto_update == 'N' ){echo 'checked="checked"';}?> >
                                            <label style="    margin-right: 30px;" for="autoweeklysend2">Off</label> 
                                            <input onclick="checkouto('2');" type="radio" name="autoweeklysend" value="1" id="autoweeklysend1" <?php if($this->allsetting->auto_update == 'Y' && $this->com_params->get('autoweeklysend') == 1 ){echo 'checked="checked"';}?> >
                                            <label for="autoweeklysend1">Automatic</label> 
                                            <input onclick="checkouto('3');" style="margin-left: 40px;" type="radio" name="autoweeklysend" value="0" id="autoweeklysend0" <?php if( $this->allsetting->auto_update == 'Y' && $this->com_params->get('autoweeklysend') == 0 ){echo 'checked="checked"';}?> > 
                                            <label for="autoweeklysend0">Semi-Auto</label>
                                    </div>
                                  <br>
                                </div>
                                <div class="specclass1" style="display:<?php if( $this->allsetting->auto_update == 'Y' && $this->com_params->get('autoweeklysend') == 0 ){echo 'block;';}else{echo 'none;';} ?>  ">
                                <div class="blocks3" >
                                    <div>
                                    
                                            <label for="semiautoemail">Approval Email</label>
                                            <input style="    margin-left: 0px;"  type="text" size="50" maxlength="150" id="semiautoemail" class="inputbox" value="<?php echo $this->com_params->get('semiautoemail'); ?>" name="semiautoemail">
                                    </div>      
                               </div>      
                               </div> 
                                 <div class="specclass2" style="display:<?php if( $this->allsetting->auto_update != 'N' ){echo 'block;';}else{echo 'none;';} ?>  ">     
                               <div class="blocks2" >     
                                    
                                     <label >Subject Line<span class="star"> *</span> </label>   
                                     <br>
                                     <input style="    margin-left: 0px;" class="" value="<?php echo $this->allsetting->update_subject; ?>" type="text"  name="loginname" placeholder="" >
                                     
                                     <label >Default From Name<span class="star"> *</span> </label>   
                                     <br>
                                     <input style="    margin-left: 0px;" class="" value="<?php echo $this->allsetting->from_name; ?>" type="text"  name="from_name" placeholder="" >
                                     
                                     
                               </div>     
                               </div> 
                                     
                                     
                              
                                <div class="specclass2" style="display:<?php if( $this->allsetting->auto_update != 'N' ){echo 'block;';}else{echo 'none;';} ?>  ">     
                                <div class="blocks4" >       
                                     
                                     <label>Select the list you want to send the weekly update to </label> 
                                     <table style="width:300px;" >
				
				<tbody>
                                <?php if ($this->allsetting->newsletter_api == "C" ){ 
                                   
				foreach ($groups as $i => $group) :?>
					<?php 
                                     //   if($group->contact_count != 0 )  {
                                    ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php 
                                                        foreach ($this->group as $rl ) {
                                                            $gid[] = $rl->group_id;
                                                        }
                                                       
							if(in_array($group->id,$gid)){
								$gchecked = 'checked="checked"';
							}else{
								$gchecked = '';
							}?>
								<input id="cb2" class="checkall_group" type="checkbox" <?php echo $gchecked;?> onclick="Joomla.isChecked(this.checked);" value="<?php echo $group->id;?>" name="gid[]" />
						</td>
					
					
						<td class="" >
						<?php echo $this->escape($group->name); ?>
						</td>			
						
					</tr>
					
				<?php   endforeach;  } ?>
                                        
                                        
                               <?php if ($this->allsetting->newsletter_api == "M" ){ 
                                  foreach ($groups as  $group) :?>
					
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php 
                                                         foreach ($this->group as $rl ) {
                                                            $gid[] = $rl->group_id;
                                                        }
							if(in_array($group['id'],$gid)){
								$gchecked = 'checked="checked"';
							}else{
								$gchecked = '';
							}?>
								<input id="cb2" class="checkall_group" type="checkbox" <?php echo $gchecked;?> onclick="Joomla.isChecked(this.checked);" value="<?php echo $group['id'];?>" name="gid[]" />
						</td>
						<td class="" >
						<?php echo $this->escape($group['name']); ?>
						</td>			
						
					</tr>
				
				<?php  endforeach; 
                                
                                                        } ?>
                                        
                                        
                                    <?php if ($this->allsetting->newsletter_api == "I" ){ ?>
                                        
                                        <tr class="row">
						<td class="center">
							
								<input id="cb2" class="checkall_group" type="checkbox" checked="checked" onclick="Joomla.isChecked(this.checked);" value="all" name="gid[]" />
						</td>
						<td class="" >
						Infusionsoft 
						</td>			
						
					</tr>
                                        
                                    <?php } ?>    
                                    <?php if ($this->allsetting->newsletter_api == "G" ){ ?>
                                        
                                        <tr class="row">
						<td class="center">
							
								<input id="cb2" class="checkall_group" type="checkbox" checked="checked" onclick="Joomla.isChecked(this.checked);" value="all" name="gid[]" />
						</td>
						<td class="" >
						GetResponse
						</td>			
						
					</tr>
                                        
                                    <?php } ?>  
				</tbody>
                                    </table>
                                     <br>
                              </div>     
                               </div> 
                            <div class="blocks8">
                                          <br>
                                            <label >Content <span class="star"> *</span> </label> 
                                          <br>
                                            <input class="" value="1" type="radio"  <?php if ($this->com_params->get('contetnt_resouce') != '2'){ echo 'checked="checked"';} ?>   name="contetnt_resouce" > Weekly Investment Update    <br>
                                            <input  class="" value="2" type="radio"  <?php if ($this->com_params->get('contetnt_resouce') == '2'){ echo 'checked="checked"';} ?>   name="contetnt_resouce" > Weekly Investment Update with Financial Planning Update <br><br>
                                     
                                            <input type="radio" value="Y"  name="contetnfull" <?php if ($this->com_params->get('contetnfull') != 'N'){ echo 'checked="checked"';} ?>  >
                                            <label style="    margin-right: 30px;" for="autoweeklysend2">Full</label> 
                                            <input type="radio" name="contetnfull" value="N"  <?php if ($this->com_params->get('contetnfull') == 'N'){ echo 'checked="checked"';} ?> >
                                            <label >Intro</label> 
                             </div>       
                                     
                              <div class="blocks5">
                                    <hr> 
                                    <br>
                                     <label >Selected Layout <span class="star"> *</span> </label> 
                                   
                                   <br>
                                     <input type="checkbox"  <?php if ( $this->allsetting->template_weekly != ''){  ?> onclick='$("#templatechioce").val(""); $("#adformdds").submit();' <?php } ?> <?php if ( $this->allsetting->template_weekly == ''){ echo 'checked=""';} ?>  <?php if ( $this->allsetting->template_weekly == ''){ echo 'disabled="disabled"';} ?>   />
                                     Use setting weekly updates in Back Office
                                     <br>
                                      <br>
                                      
                                     <?php    
                                     foreach ($this->tems_user1 as $r){ 
                       
                                            $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                                            $ktmp[filemtime($directory)] = $r ;     
                                      }
                                      ksort($ktmp);
              //   print_r($ktmp);die;
                                    $this->tems_user = $ktmp;
                                   // print_r($this->tems_user);die;
                                    $jk == 1; 
                                    $checkleft = $checkright = $checkclass = '';  
                                    foreach (  array_reverse($this->tems_user) as $r){   
                                       
                                            $pos1 = strpos( $r ,  "weeklyupdate_" );
                                            if ($pos1 !== false){
                                                $checkleft = $r;
                                                 break;
                                            }  
                                    
                                    }
                                    
                                    foreach (  array_reverse($this->tems_user) as $r){   
                                                                             
                                          
                                            $pos1 = strpos( $r ,  "weeklyupdateright_" );
                                            if ($pos1 !== false){
                                                $checkright = $r;
                                                 break;
                                            }  
                                    
                                    }
                                    
                                    foreach (  array_reverse($this->tems_user) as $r){   
                                                    
                                            $pos1 = strpos( $r ,  "weekly_" );
                                            if ($pos1 !== false){
                                                $checkclass = $r;
                                                 break;
                                            }  
                                    
                                    }
                   
                        ?>
                                      <a class="butchoicetem" title="Content Left" style="        cursor: pointer; width: 80px;     margin-right: 5px;" type="button" onclick='if("<?php echo $checkleft; ?>".length > 1  ){ $("#templatechioce").val("<?php echo $checkleft; ?>");$("#adformdds").submit();} else { alert("Create weekly template first"); } ' > <img style="width: 100px;height: auto;" src="<?php echo JURI::base(true);?>/components/com_enewsletter/assets/images/thum3.jpg" /> </a>
                                     <a class="butchoicetem" title="Content Right" style="      cursor: pointer;  width: 80px;     margin-right: 5px; " type="button" onclick='if("<?php echo $checkright; ?>".length > 1  ){ $("#templatechioce").val("<?php echo $checkright; ?>");$("#adformdds").submit();} else { alert("Create weekly template first"); } ' > <img style="width: 100px;height: auto;" src="<?php echo JURI::base(true);?>/components/com_enewsletter/assets/images/thum2.jpg" />  </a>
                                     <a class="butchoicetem" title="Content Only" style="       cursor: pointer; width: 80px;     margin-right: 5px; " type="button" onclick='if("<?php echo $checkclass; ?>".length > 1  ){ $("#templatechioce").val("<?php echo $checkclass; ?>");$("#adformdds").submit();} else { alert("Create weekly template first"); } ' > <img style="width: 100px;height: auto;" src="<?php echo JURI::base(true);?>/components/com_enewsletter/assets/images/thum5.jpg" />  </a>
                                     <style>
                                         .butchoicetem:hover img {
                                             border: 1px solid red;
                                         }
                                     </style>
                                     
                                     
                                     </div>
                                     
                                    
                                
                                
                                     
                                     
                                     
                                    <div style="display:none;">
                                     <button  style="float: right; padding: 10px;    margin-top: -10px;    margin-right: 14px;" type="button" onclick="$('#popup2_open').bPopup();" > Pick Template </button> 
                                      <input readonly="" style="    margin-left: 0px;"  value="<?php $aask = str_replace('.html', '', $this->allsetting->template_weekly); $aask = explode('_'.jfactory::getuser()->id.'_', $aask ); echo $aask[1]; ?>" type="text" />
                                        <br>
                                     <br>
                                     
                                    
                                     </div>
                                     
                                   
                                      
									  
									  
                   <div class="blocks14" for="planing" >
                                    <br>
                                    <label >Automation  <span class="star"> *</span> </label> 
                                    <br><br>
                                    <div>
                                            <input onclick="$('#planing_maila').hide();" type="radio" value="1" id="planing_option1" name="planing_option"  <?php if ($this->com_params->get('planing_option') != '2' && $this->com_params->get('planing_option') != '3'){ echo 'checked="checked"';} ?> >
                                            <label style="    margin-right: 30px;" for="autoweeklysend2">Off</label> 
                                            <input onclick="$('#planing_maila').hide();" type="radio" value="2" id="planing_option2" name="planing_option"   <?php if ($this->com_params->get('planing_option') == '2'){ echo 'checked="checked"';} ?> >
                                            <label for="autoweeklysend1">Automatic</label> 
                                            <input onclick="$('#planing_maila').show();" style="margin-left: 40px;" value="3" type="radio" name="planing_option"  id="planing_option3"  <?php if ($this->com_params->get('planing_option') == '3'){ echo 'checked="checked"';} ?>  > 
                                            <label for="autoweeklysend0">Semi-Auto</label>
											<div id="planing_maila" style="  <?php if ($this->com_params->get('planing_option') != '3'){ ?> display:none; <?php } ?>"  >
											<br>
											<label >Approval Email</label>
											<input style="    margin-left: 0px;" type="text" size="50" maxlength="150" id="plan_semiautoemail" class="inputbox" value="<?php echo $this->com_params->get('plan_semiautoemail'); ?>" name="plan_semiautoemail">
											</div>											
											
                                    </div>
                   </div>


                   <div class="blocks15" for="planing" style="margin-top:20px;" >     

                         <label >Subject Line<span class="star"> *</span> </label>   
                         <br>
                         <input style="    margin-left: 0px;" class="" value="<?php echo $this->com_params->get('plan_subject'); ?>" type="text"  name="plan_subject" placeholder="" >  

                   </div>
                   
                   <div class="blocks16" for="planing" >     

                         <label >Schedule Weekly Financial Planning Update</label>   
                         <br>    <br>
                         <table border="1" width="100%" style="    text-align: center;" >
                             <tr>
                                 <td>Sun</td>
                                 <td>Mon</td>
                                 <td>Tue</td>
                                 <td>Web</td>
                                 <td>Thurs</td>
                                 <td>Fri</td>
                                 <td>Sat</td>
                                 
                             </tr>
                             <tr>
								<?php $str = explode(',', $this->com_params->get('day_send') ); ?>
                                 <td><input type="checkbox" name="day_send[]" value="7" <?php if(in_array(7,$str)){ echo 'checked="checked"'; } ?> /></td>
                                 <td><input type="checkbox" name="day_send[]" value="1" <?php if(in_array(1,$str)){ echo 'checked="checked"'; } ?>  /></td>
                                 <td><input type="checkbox" name="day_send[]" value="2" <?php if(in_array(2,$str)){ echo 'checked="checked"'; } ?>  /></td>
                                 <td><input type="checkbox" name="day_send[]" value="3" <?php if(in_array(3,$str)){ echo 'checked="checked"'; } ?>  /></td>
                                 <td><input type="checkbox" name="day_send[]" value="4" <?php if(in_array(4,$str)){ echo 'checked="checked"'; } ?>  /></td>
                                 <td><input type="checkbox" name="day_send[]" value="5" <?php if(in_array(5,$str)){ echo 'checked="checked"'; } ?>  /></td>
                                 <td><input type="checkbox" name="day_send[]" value="6" <?php if(in_array(6,$str)){ echo 'checked="checked"'; } ?>  /></td>                                 
                             </tr>
                         </table>
						     <br>
                          <label >Time: </label>   
                          <select name="hour_send">
                              <?php for($i = 0; $i <= 11 ; $i++ ){ ?>
                                <option <?php if($this->com_params->get('hour_send') == $i ){ echo 'selected="selected"';} ?> value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                              <?php } ?>
                          </select>:
                          <select  name="mu_send">
                              <?php for($i = 0; $i <= 59 ; $i++ ){ ?>
                                <option <?php if($this->com_params->get('mu_send') == $i ){ echo 'selected="selected"';} ?>  value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                              <?php } ?>
                          </select>
                           <select  name="ti_send">
                               <option <?php if($this->com_params->get('ti_send') == 'AM' ){ echo 'selected="selected"';} ?> value="AM" >AM</option>
                               <option <?php if($this->com_params->get('ti_send') == 'PM' ){ echo 'selected="selected"';} ?> value="PM" >PM</option>
                          </select>
                          <br><br>* If the schedule is set multiple days in the week, the same content will sent out.
                   </div>
                     
                    
                   <div class="blocks13" for="planing">
                                    <hr> 
                                    <br>
                                     <label >Selected Layout <span class="star"> *</span> </label> 
                                   
                                   <br>
                                   
                                     <?php    
                                     foreach ($this->tems_user1 as $r){ 
                       
                                            $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                                            $ktmp[filemtime($directory)] = $r ;     
                                      }
                                      ksort($ktmp);
            
                                    $this->tems_user = $ktmp;
                                  
                                    $jk == 1; 
                                    $checkleft = $checkright = $checkclass = '';  
                                    foreach (  array_reverse($this->tems_user) as $r){   
                                       
                                            $pos1 = strpos( $r ,  "weeklyupdate_" );
                                            if ($pos1 !== false){
                                                $checkleft = $r;
                                                 break;
                                            }  
                                    
                                    }
                                    
                                    foreach (  array_reverse($this->tems_user) as $r){   
                                                                             
                                          
                                            $pos1 = strpos( $r ,  "weeklyupdateright_" );
                                            if ($pos1 !== false){
                                                $checkright = $r;
                                                 break;
                                            }  
                                    
                                    }
                                    
                                    foreach (  array_reverse($this->tems_user) as $r){   
                                                    
                                            $pos1 = strpos( $r ,  "weekly_" );
                                            if ($pos1 !== false){
                                                $checkclass = $r;
                                                 break;
                                            }  
                                    
                                    }
                   
                        ?>
                                     <a class="butchoicetem1" title="Content Left" style="  cursor: pointer; width: 80px;     margin-right: 5px;" type="button" onclick='if("<?php echo $checkleft; ?>".length > 1  ){ $("#templatechioce_plan").val("<?php echo $checkleft; ?>");$(".butchoicetem1 img").removeClass("acctive"); $(this).children("img").addClass("acctive");} else { alert("Create weekly template first"); } ' > <img style="width: 100px;height: auto;" src="<?php echo JURI::base(true);?>/components/com_enewsletter/assets/images/thum3.jpg"  <?php if( strpos($this->com_params->get('templatechioce_plan'),'weeklyupdate_') !== false){ echo 'class="acctive"';} ?> /> </a>
                                     <a class="butchoicetem1" title="Content Right" style=" cursor: pointer;  width: 80px;     margin-right: 5px; " type="button" onclick='if("<?php echo $checkright; ?>".length > 1  ){ $("#templatechioce_plan").val("<?php echo $checkright; ?>");$(".butchoicetem1 img").removeClass("acctive"); $(this).children("img").addClass("acctive");} else { alert("Create weekly template first"); } ' > <img style="width: 100px;height: auto;" src="<?php echo JURI::base(true);?>/components/com_enewsletter/assets/images/thum2.jpg"  <?php if( strpos($this->com_params->get('templatechioce_plan'),'weeklyupdateright_') !== false){ echo 'class="acctive"';} ?> />  </a>
                                     <a class="butchoicetem1" title="Content Only" style="  cursor: pointer; width: 80px;     margin-right: 5px; " type="button" onclick='if("<?php echo $checkclass; ?>".length > 1  ){ $("#templatechioce_plan").val("<?php echo $checkclass; ?>");$(".butchoicetem1 img").removeClass("acctive"); $(this).children("img").addClass("acctive");} else { alert("Create weekly template first"); } ' > <img style="width: 100px;height: auto;" src="<?php echo JURI::base(true);?>/components/com_enewsletter/assets/images/thum5.jpg" <?php if( strpos($this->com_params->get('templatechioce_plan'),'weekly_') !== false){ echo 'class="acctive"';} ?> />  </a>
                                     
                                     <style>
                                       
										 .butchoicetem1:hover img , .butchoicetem1 .acctive {
                                             border: 1px solid red;
                                         }
                                     </style>
                                     
                                     
									</div>
                                    <div class="blocks17" for="youtube" >
                                    <br>
                                    <label >Automation  <span class="star"> *</span> </label> 
                                    <br><br>
                                    <div>
                                            <input onclick="$('#youtube_maila').hide();" type="radio" value="1" id="youtube_mail1" name="youtube_mail"  <?php if ($this->com_params->get('youtube_mail') != '2' && $this->com_params->get('youtube_mail') != '3'){ echo 'checked="checked"';} ?> >
                                            <label style="    margin-right: 30px;" for="autoweeklysend2">Off</label> 
                                            <input onclick="$('#youtube_maila').hide();" type="radio" value="2" id="youtube_mail2" name="youtube_mail"   <?php if ($this->com_params->get('youtube_mail') == '2'){ echo 'checked="checked"';} ?> >
                                            <label for="autoweeklysend1"> Automatic </label> 
                                            <input onclick="$('#youtube_maila').show();" style="margin-left: 40px;" value="3" type="radio" name="youtube_mail"  id="youtube_mail3"  <?php if ($this->com_params->get('youtube_mail') == '3'){ echo 'checked="checked"';} ?>  > 
                                            <label for="autoweeklysend0">Semi-Auto</label>
                                            <div id="youtube_maila" style="  <?php if ($this->com_params->get('youtube_mail') != '3'){ ?> display:none; <?php } ?>"  >
                                            <br>
                                            <label >Approval Email</label>
                                            <input style="    margin-left: 0px;" type="text" size="50" maxlength="150" id="youtube_semiautoemail" class="inputbox" value="<?php echo $this->com_params->get('youtube_semiautoemail'); ?>" name="youtube_semiautoemail">
                                            </div>											
											
                                    </div>
                                    </div>


                                <div class="blocks18" for="youtube" style="margin-top:20px;" >     

                                          <label >Subject Line<span class="star"> *</span> </label>   
                                          <br>
                                          <input style="    margin-left: 0px;" class="" value="<?php echo $this->com_params->get('youtube_subject'); ?>" type="text"  name="youtube_subject" placeholder="" >  

                                </div>
                                
                                <div class="blocks11">
                                    
                                    <label >Logo </label> <br><br>
                                            <?php if ($this->advisorsettings->logo):?>
								<?php echo "<img border=0 src='".JURI::base(false)."media/com_enewsletter/logo/".$this->advisorsettings->logo."' />";?>
                                            <?php endif;?>
                                            <br> 
                                          <br>
                                            <label >Youtube ( Input Channel ID or User) <span class="star"> *</span> </label> 
                                          <br>
                                        <input style="margin-left: 0px;" class="" id="youtubechange" value="<?php echo $this->com_params->get('inputyoutube') ?>" type="text" name="inputyoutube" placeholder="/channel/< Channel ID > or /user/<user> ">
                                        
                                </div>
                                <div class="blocks20" for="youtube" >
                                     <br><br> <b>Introduction</b><br>
                                     <input style="    margin-left: 0px;" class="" value="" type="text" name="youtubeintro_tit" id="youtubeintro_tit" placeholder="Introduction Title">
                                     <br>
                                      <textarea id="youtubeintro" name="youtubeintro" > </textarea> 
                                      <div style="display:none;" id="data_youtubeintro" > <?php echo  base64_decode($this->com_params->get('youtube_intro')); ?></div>
                                      
                                      <script>
                                       $("#youtubeintro_tit").val( $("#youtube_tit").text() );   
                                       $("#youtube_tit").remove();
                                       $("#youtubeintro").val($("#data_youtubeintro").html());
                                       tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#youtubeintro',
                                                    height: 200,
                                                    theme: 'modern',
                                                    plugins: [
                                                      'advlist autolink lists link  charmap   hr anchor pagebreak',
                                                      'searchreplace wordcount visualblocks visualchars  fullscreen',
                                                      'insertdatetime  nonbreaking save  contextmenu directionality',
                                                      ' template paste  textpattern '
                                                    ],
                                                    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ',
                                                  
                                                    image_advtab: true,
                                                    templates: [
                                                      { title: 'Test template 1', content: 'Test 1' },
                                                      { title: 'Test template 2', content: 'Test 2' }
                                                    ],
                                                    content_css: [
                                                      '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                                      '//www.tinymce.com/css/codepen.min.css'
                                                    ] });
                                      
                                      </script>
                                     </div>
                                
                                
                                
                                <div class="blocks12" >
                                     <br>
                                     <b>Video Description</b>
                                     <br>
                                     <textarea  name="youtubedescription" id="custom-content4" > <?php echo base64_decode($this->com_params->get('youtubedescription')); ?> </textarea>   
                                      
                                      <script>
                                       tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#custom-content4',
                                                    height: 200,
                                                    theme: 'modern',
                                                    plugins: [
                                                      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                                      'searchreplace wordcount visualblocks visualchars code fullscreen',
                                                      'insertdatetime media nonbreaking save table contextmenu directionality',
                                                      'emoticons template paste textcolor colorpicker textpattern imagetools'
                                                    ],
                                                    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                                                    toolbar2: 'print preview media | forecolor backcolor emoticons',
                                                    image_advtab: true,
                                                    templates: [
                                                      { title: 'Test template 1', content: 'Test 1' },
                                                      { title: 'Test template 2', content: 'Test 2' }
                                                    ],
                                                    content_css: [
                                                      '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                                      '//www.tinymce.com/css/codepen.min.css'
                                                    ] });
                                      
                                      </script>
                                    </div>
                                      
                                  <div class="blocks6" >
                                     <br><br> <b>Introduction</b><br>
                                     <input style="    margin-left: 0px;" class="" value="" type="text" name="introduction_tit" id="introduction_tit" placeholder="Introduction Title">
                                     <br>
                                      <textarea id="custom-content" name="topp" > </textarea> 
                                      <div style="display:none;" id="data_dislo" > <?php echo $this->allsetting->weekly_update_intro;  ?></div>
                                      
                                      <script>
                                       $("#introduction_tit").val( $("#intro_tit").text());   
                                       $("#intro_tit").remove();
                                       $("#custom-content").val($("#data_dislo").html());
                                       tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#custom-content',
                                                    height: 200,
                                                    theme: 'modern',
                                                    plugins: [
                                                      'advlist autolink lists link  charmap   hr anchor pagebreak',
                                                      'searchreplace wordcount visualblocks visualchars  fullscreen',
                                                      'insertdatetime  nonbreaking save  contextmenu directionality',
                                                      ' template paste  textpattern '
                                                    ],
                                                    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ',
                                                  
                                                    image_advtab: true,
                                                    templates: [
                                                      { title: 'Test template 1', content: 'Test 1' },
                                                      { title: 'Test template 2', content: 'Test 2' }
                                                    ],
                                                    content_css: [
                                                      '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                                      '//www.tinymce.com/css/codepen.min.css'
                                                    ] });
                                      
                                      </script>
                                     </div>
                                     <div class="blocks7" >
                                     <br>
                                     <b>Disclosure</b>
                                     <br>
                                      <textarea id="custom-content2" name="bott" > <?php echo $this->allsetting->weekly_update_newsletter  ?> </textarea>   
                                      
                                      <script>
                                       tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#custom-content2',
                                                    height: 200,
                                                    theme: 'modern',
                                                    plugins: [
                                                      'advlist autolink lists link  charmap   hr anchor pagebreak',
                                                      'searchreplace wordcount visualblocks visualchars  fullscreen',
                                                      'insertdatetime  nonbreaking save  contextmenu directionality',
                                                      ' template paste  textpattern '
                                                    ],
                                                    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent ',
                                                    image_advtab: true,
                                                    templates: [
                                                      { title: 'Test template 1', content: 'Test 1' },
                                                      { title: 'Test template 2', content: 'Test 2' }
                                                    ],
                                                    content_css: [
                                                      '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                                      '//www.tinymce.com/css/codepen.min.css'
                                                    ] });
                                      
                                      </script>
                                      </div>
                                
                                
									
									<input type="hidden" name="templatechioce_plan" id="templatechioce_plan" value="<?php echo  $this->com_params->get('templatechioce_plan'); ?>" >
                                    <input type="hidden" name="option" value="com_enewsletter" >
                                    <input type="hidden" id="task" name="task" value="setting.weekly" >                      
                                    <input type="hidden" name="view" value="setting" >
                                    <input type="hidden"  id="typecontentchoice" name="typecontentchoice" value="" >

                                    <?php echo JHtml::_('form.token'); ?>
                                    <div class="blocks9" >   
                                     <button type="submit" class="button-blu" id="weekly-save" >Save</button>   
                                    </div>
                            </form>
                    </div>
                    <br>
                    
                    
                    
                   
                    
                    <div class="blocks10" >
                        <a href="<?php echo juri::base(); ?>index.php?option=com_enewsletter&task=sendweeklyupdate" target="_blank" >   <button  type="button" style="      border: none;    text-align: center;    margin: 10px 25px 25px;  
    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;    min-width: 100px;    float: left;    font-size: 0;    background: url('images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 292px;    height: 85px; " >Send Mail</button></a>
                    </div>
                    
                     
                    <div class="blocks19" for="youtube" >
                        <a href="<?php echo juri::base(); ?>index.php?option=com_enewsletter&task=sendweeklyvideo" target="_blank" >   <button  type="button" style="      border: none;    text-align: center;    margin: 10px 25px 25px;  
    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;    min-width: 100px;    float: left;    font-size: 0;    background: url('images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 292px;    height: 85px; " >Send Mail</button></a>
                    </div>
                    
                    
                   
                    

                </div>
            </div>
             
            <div class="col-2" >    
				 <div class="setting_widgets_area">
				 <?php
				 //print_r($this->com_params);
				 //print_r($this->advisorsettings);
				 ?>
				<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(true);?>/components/com_enewsletter/assets/dropzone/css/dropzone.css" />
				<script src="<?php echo JURI::root(true);?>/components/com_enewsletter/assets/dropzone/dropzone.js" type="text/javascript"></script>
				 	<form id="setting_widgets_form" name="setting_widgets_form" method="post" enctype="multipart/form-data">
					<dl id="setting_widgets_dl">
						<dt>Template Colors</dt>
                                                <dd style="    height: 322px;    position: relative;overflow: hidden;">
                                                    <ul style="display: block;width: 50%;float: left; ">
                                <li style="    width: 100%;    float: left;"><label  style="width: 210px;float: left;">Background Color:</label> 
                                    <input style="float: left;" class="jscolor auto_save_setting_widgets" type="text" size="10" id="backgc" name="backgc" value="<?php echo $this->com_params->get('backgc','f4f4f4');?>" maxlength="7" /></li>
								<li style="    width: 100%;    float: left;"><label style="width: 210px;float: left;">Main Text Color:</label> 
                                                                    <input  style="float: left;" class="jscolor auto_save_setting_widgets" type="text" size="10"  id="maintextgc" name="maintextgc" value="<?php echo $this->com_params->get('maintextgc','000000');?>" maxlength="7" /></li>
								<li style="    width: 100%;    float: left;"><label style="width: 210px;float: left;">Background Bar Color:</label> 
                                                                    <input  style="float: left;" class="jscolor auto_save_setting_widgets" type="text" size="10"  id="backbargc" name="backbargc" value="<?php echo $this->com_params->get('backbargc','ecebe0');?>" maxlength="7" />  </li>
								<li style="    width: 100%;    float: left;" ><label style="width: 210px;float: left;">Link Color:</label> 
                                                                    <input   style="float: left;" class="jscolor auto_save_setting_widgets" type="text" size="10" id="linktextgc" name="linktextgc" value="<?php echo $this->com_params->get('linktextgc','2366bd');?>" maxlength="7" /></li>
							</ul>
                                                    <style>
                                                        #thispreview {
                                                            -ms-transform: scale(0.15, 0.15);
                                                            -webkit-transform: scale(0.15, 0.15); 
                                                            transform: scale(0.15, 0.15);
                                                            width: 45%;
                                                            float: right;
                                                            position: absolute;
                                                            top: -800px;
                                                            right: 0px;
                                                        }
                                                        
                                                        
                                                    </style>
                                                      <button type="button" class="button-blu" id="poll-save">Save</button>
                                                      <div style="width: 400px;height:322px;position: absolute;z-index: 5;background: transparent; right: 0px;top: 0px;" ></div>                       
  <div id="thispreview" >
    
    <table style="background: rgb(144, 235, 32);" width="1100" cellspacing="0" cellpadding="0" align="center" bgcolor="#ffffff"><tbody><tr><td>
<div id="main-page-html">
    <table width="100%"><tbody><tr><td width="830px" valign="top" style="padding-left: 10px;padding-right: 10px;">
                   <div id="sortable1" class="ui-sortable">
                           <table style="width:100%;margin-bottom: 50px;" class="ui-sortable-handle"><tbody><tr><td style="width: 48%;" valign="top"> 
                                    <div id="logomail">
                                        <img id="imgslogo" src="https://sample6joomla.advisorproducts.com/modules/mod_leftmenuedit/cus-edit/images/small_logo.png" border="0" style="width: 100%; max-width: 350px; min-width: 260px;"></div> 
                               </td>
                               <td style="width: 48%;" valign="top">
                                    <div id="address">   <div id="address-content"> <table border="0" cellspacing="0" cellpadding="0" class="mce-item-table" data-mce-selected="1" style="color: rgb(0, 0, 0);"><tbody><tr valign="top"><td><p style="font-size: 12px;" data-mce-style="font-size: 12px;"><strong>Financial Advisory Firm</strong><br><span id="topaddress">111 Jericho Tqke, Suite 333 <br>Jericho NY 11753<br>Tel: (516) 333-0066<br>Email: </span> <a href="mailto:agluck@advisorproducts.com" data-mce-href="mailto:agluck@advisorproducts.com" style="color: rgb(45, 61, 227); text-decoration: none;">agluck@advisorproducts.com</a><br><a href="http://advisorproducts.com" target="_blank" data-mce-href="http://advisorproducts.com" style="color: rgb(45, 61, 227); text-decoration: none;">http://advisorproducts.com</a></p></td></tr></tbody></table> </div>  </div>
                               </td>
                           </tr></tbody></table><div style="width: 100%; clear: both; border: none;" id="con-top" class="ui-sortable-handle">
                           
                           
                       </div>
                        <div id="cta" style="display: block; margin-bottom: 30px; " class="ui-sortable-handle">
					                                                   <div id="intro" bgcolor="#ECEBE0" style="padding: 10px 0px; width: 100%; float: left; background: rgb(100, 85, 182);"> 
                            <strong style="font-size: 16px; margin-left: 12px; border: none; color: rgb(0, 0, 0); background: rgb(100, 85, 182);" class="intro">This is an Introduction here...</strong>
                        </div>
                      
                        <table id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="mceItemTable">
                            <tbody>
                                
                                   
                                       
                                                                    <tr id-api="3734" id-cont="article_content_1" class="edit_content" id="article_1" style="border: rgb(244, 244, 244); background: rgb(144, 235, 32);" data-mce-style="background-color: #f4f4f4;"><td style="padding: 25px;" data-mce-style="padding: 25px;">
                                          <table width="100%" class="mceItemTable"><tbody><tr><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; text-align: justify;">
                                                          
                                                          <br>
                                                          <div finra="0" info="" id="article_content_1" style="font-family: Arial; font-size: medium; color: rgb(0, 0, 0);" data-mce-style="font-family: Arial; font-size: medium;"> 
                                                              <strong style="font-size: 20px;" data-mce-style="font-size: 20px;">
                                                                  <a href="http://sample6joomla.advisorproducts.com/news/featured-news?id=3734" style="color: rgb(45, 61, 227); text-decoration: none;" data-mce-href="http://sample6joomla.advisorproducts.com/news/featured-news?id=3734" data-mce-style="color: #000000; text-decoration: none;" id="api-title">New Employment And Inflation Data Likely To Delay Interest Rate Hike</a>
                                                              </strong>
                                                                  
                                                                  <br>
<strong style="font-size: 20px;">  </strong> <br><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><div style="color: #000000;" align="center"><a style="color: #2d3de3; text-decoration: none;" href="https://contentengine.advisorproducts.com/images/NL_Images/3349_1.png" target="_blank"> <img src="http://contentengine.advisorproducts.com/images/NL_Images/3349_1.png" alt="" width="100%" height="auto" align="middle"></a></div><p>&nbsp;</p><p>Employment data released Friday morning was just okay and likely to delay a long-anticipated hike in interest rates. Unemployment held steady at 4.9% in August, according to the U.S. Bureau of Labor Statistics (BLS). That's not quite as low as was reached in the last two economic expansions, but this expansion is still in progress. The BLS also said 151,000 new non-farm payroll jobs were created in August&nbsp;— a healthy<a style="color: #2d3de3; text-decoration: none;" href="https://sample6joomla.advisorproducts.com/images/files/BK_insurance_710.pdf"> gain but nothing spectacular</a>.<a style="color: #2d3de3; text-decoration: none;" href="https://sample6joomla.advisorproducts.com/images/files/BK_insurance_710.pdf">&nbsp;</a></p><p>&nbsp;</p><div style="color: #000000;" align="center"><a style="color: #2d3de3; text-decoration: none;" href="https://contentengine.advisorproducts.com/images/NL_Images/3349_2.png" target="_blank"> <img src="http://contentengine.advisorproducts.com/images/NL_Images/3349_2.png" alt="" width="100%" height="auto" align="middle"></a></div><p>&nbsp;</p><p>The 4.9% unemployment rate, officially known as the U-3 index of unemployment, gets all the headlines but the government's U-6 index of unemployment has more influence over the Federal Reserve's monetary policy.</p><p>The U-6 unemployment index includes people who are not in the labor force but are available for work, and those who have looked for a job in the previous 12 months but had not searched for work in the four weeks before the survey. It includes "discouraged workers" not looking for work because they believed no jobs were available, or that there were no jobs available for which they would qualify.</p><p>The downward slope of both indexes has flattened, and both must head considerably lower before the Fed will satisfy its mandate. That makes any Fed rate hikes likely to come in small quarter-point increments over a long period.</p><p>The inflation rate as measured by the Personal Consumption Expenditure index, or PCE deflator, was also released this past week. At 0.8%, it remained below the Fed's target rate of 2%. The Core PCE deflator, which is influential in determining Fed interest rate policy, came in at 1.4%&nbsp;— higher than 0.8%, but well below the Fed's target of 2%.</p>
 
                                                          </div> 
                                                          <a style="   display: none;   font-family: sans-serif;    font-size: 19px;    margin-top: 17px;    text-align: right;    text-decoration: none;" id="link_article_content_1" target="_blank" href="http://sample6joomla.advisorproducts.com/news/featured-news?id=3734"> <span style="    background-color: #000;    padding: 10px;    border-radius: 10px;    color: #fff;   ">Read More</span></a>
                                                      </td>
                                                     
                                                  </tr>
                                              </tbody>
                                          </table>
                                      </td>
                                  </tr>  
                                             
    
    </tbody></table>

					   
					   </div>
                        <div id="con-bot" class="ui-sortable-handle" style="border: none;">
                           
                           
                       </div>
                        
                       
                       
                       <div id="disclosure" class="ui-sortable-handle" style="border: none;">
                           
                           
                       </div>
                    </div>
            </td>
            <td width="210px" valign="top" style="padding-right: 5px;">
                <div class="allmodule" style="width: 195px;">
                    <div id="sortable" class="ui-sortable" style="    list-style-type: none;    margin: 0;    padding: 0;    width: 100%;">
                               <div id="social" class="module marb-50 ui-draggable ui-draggable-handle ui-sortable-handle" style="position: relative; margin-bottom: 100px; margin-top: 5px; border-radius: 3px; text-align: center;">               <a href="http://fb.com/abc2" id="lilinkedin" style="text-decoration: none;"><img src="https://sample6joomla.advisorproducts.com/images/icons/linkedin.png"> </a>                <a href="http://fb.com/abc2" id="lifacebook" style="text-decoration: none;"><img src="https://sample6joomla.advisorproducts.com/images/icons/facebook.png"> </a>                  <a href="http://twitter.com/abc" id="litwitter" style="text-decoration: none;"><img src="https://sample6joomla.advisorproducts.com/images/icons/twitter.png"> </a> </div>
                               
                               
                               <div id="advions-content" class="module ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border: 4px solid rgb(221, 221, 221); border-radius: 3px; text-align: center;"> <a href="#" style="text-decoration: none;">  <img src="http://www.advisorproducts.com/components/com_enewsletter/images/imagetest.png" width="100%"></a> </div>
                       
                        <div id="poll-content" class="module blue ui-sortable-handle" style="padding: 20px; color: rgb(255, 255, 255); margin-top: 5px; margin-bottom: 20px; border-radius: 3px; text-align: center; background-color: rgb(34, 104, 190);">  <h1 class="poll-content-title1" style="color: #F79925!important;    text-transform: uppercase;    font-size: 18px!important; ">   quick poll     </h1>    <div class="poll-content-text1">  <div>Economic growth for 2016 looks good. </div><br>  </div>    <a style="    color: #F79925;    background-color: #0F4180;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;" target="_blank" href="https://centcom.advisorproducts.com/index.php?option=com_acepolls&amp;returnback=aHR0cHM6Ly9zYW1wbGU2am9vbWxhLmFkdmlzb3Jwcm9kdWN0cy5jb20vaW5kZXgucGhwP29wdGlvbj1jb21fZW5ld3NsZXR0ZXImdmlldz1wb2xs&amp;task=updatepoll&amp;op=t&amp;id=1"> TRUE </a><a style="    color: #F79925;    background-color: #0F4180;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;" targer="_blank" href="https://centcom.advisorproducts.com/index.php?option=com_acepolls&amp;returnback=aHR0cHM6Ly9zYW1wbGU2am9vbWxhLmFkdmlzb3Jwcm9kdWN0cy5jb20vaW5kZXgucGhwP29wdGlvbj1jb21fZW5ld3NsZXR0ZXImdmlldz1wb2xs&amp;task=updatepoll&amp;op=f&amp;id=1"> FALSE </a>    </div>
                        
                        <div id="seminar" class="module default ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border: 4px solid rgb(221, 221, 221); border-radius: 3px; padding: 25px 4px; text-align: center; color: rgb(235, 0, 0); background: rgb(255, 255, 255);">
                                  <h3 class="seminar-content-title2" style="margin-top: 17px; margin-bottom: 25px; font-size: 20px;">Main Text</h3>
                                   <div class="seminar-content-text2">                                        <a style="    color: #FFFFFF!important;    background-color: #1A78EB;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;    padding-left: 30px;    padding-right: 30px;" target="_blank" href="https://sample6joomla.advisorproducts.com/index.php?option=com_cta&amp;view=form&amp;cusitem_id[]=35" class="button-or"> Name Button </a></div>
                               </div>
                               
                              
                               <div id="map" class="module ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border: 4px solid rgb(221, 221, 221); border-radius: 3px; text-align: center;">  <img data="69 Lyme Ave.  Trussville, AL 35173" zoom="1" src="https://maps.googleapis.com/maps/api/staticmap?center=33.5899197,-86.6281546&amp;zoom=15&amp;size=210x210&amp;maptype=roadmap&amp;markers=color:blue%7Clabel:S%7C33.5899197,-86.6281546&amp;key=AIzaSyBNJIeTGgrFxcrTgo0YKZoj7Y-T7IYapS8&amp;zoom=10" width="100%"> </div>
                              
                            
                        
                        <div id="schedule" class="module default ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border: 4px solid rgb(221, 221, 221); border-radius: 3px; padding: 15px; word-wrap: break-word; text-align: center; background: rgb(255, 255, 255);">
                                   <a id="aschedule" href="https://sample6joomla.advisorproducts.com/book-now" style="color: rgb(45, 61, 227); text-decoration: none;">
                                  <img style="    width: 40px;    float: left; margin-top: 17px;" src="http://www.advisorproducts.com/components/com_enewsletter/images/meeting.png" width="69"><h3 class="poll-content-title2" style="margin-top: 17px; margin-bottom: 17px; font-size: 20px;">
                                     Schedule A Meeting
                                   </h3>
                                  </a>
                               </div>
			<div id="serminar1" class="module default ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border: 4px solid rgb(221, 221, 221); border-radius: 3px; padding: 15px; word-wrap: break-word; text-align: center; background: rgb(255, 255, 255);">
                                   <a id="aserminar1" href="https://sample6joomla.advisorproducts.com/register" style="color: rgb(45, 61, 227); text-decoration: none;">
                                  <img style="width: 45px; float: left; margin-top: 7px;" src="http://www.advisorproducts.com/components/com_enewsletter/images/serminar.png" width="69"><h3 class="poll-content-title2" style="margin-top: 17px; margin-bottom: 17px; font-size: 20px;">
                                     Events 
                                   </h3>
                                  </a>
                               </div>
                        
                        <div id="weekly" class="module default ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border-radius: 3px; padding: 15px; word-wrap: break-word; text-align: center; border: 4px solid rgb(221, 221, 221); background: rgb(255, 255, 255);">
                                  
                                  <img style="    width: 51px;    float: left;    margin-top: 17px;" src="http://www.advisorproducts.com/components/com_enewsletter/images/weekly.png" width="69"><h3 class="poll-content-title2" style="margin-top: 17px; margin-bottom: 17px; font-size: 20px;">
                                  <a id="aweekly" href="https://sample6joomla.advisorproducts.com/weekly-update" style="color: rgb(45, 61, 227); text-decoration: none;">Weekly Update</a>
                                   </h3>
                                 
                        </div>
                               
                                <div id="invest" class="ui-sortable-handle" style="display: none; margin-top: 5px; margin-bottom: 20px; border-radius: 3px; padding: 15px; word-wrap: break-word; text-align: center; border: 4px solid rgb(221, 221, 221); background: rgb(255, 255, 255);">
                             
                                 <img src="http://rimbatest1.advisorproducts.com/images/Investment.png" width="69"><br><a id="ainvest" href="https://sample6joomla.advisorproducts.com/news/featured-news" style="font-size: 22px; color: rgb(45, 61, 227); text-decoration: none;">Stocks Close Slightly Lower After Fed Chair's Speech</a> 
                                
                        </div>
                        
                        
                        <div id="financial" class="ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border-radius: 3px; padding: 15px; word-wrap: break-word; text-align: center; display: none; border: 4px solid rgb(221, 221, 221); background: rgb(255, 255, 255);">
                                
                            <img src="http://rimbatest1.advisorproducts.com/images/financial.png" width="69"><br><a id="afinancial" href="https://sample6joomla.advisorproducts.com/news/financial-briefs" style="font-size: 22px; text-decoration: none;">U.S. Leading Economic Indicators Rose Again</a> 
                            
                            
                        </div>
                        
                        
                               <div id="cloud-tag" class="module default ui-sortable-handle" style="margin-top: 5px; margin-bottom: 20px; border: 4px solid rgb(221, 221, 221); border-radius: 3px; padding: 15px; word-wrap: break-word; text-align: center; background: rgb(255, 255, 255);">
                <div style="    display: table;    padding: 10px;">
              <a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=3" style="float: left; font-size: 20px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Estate Planning</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=5" style="float: left; font-size: 12px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Insurance</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=6" style="float: left; font-size: 21px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Investing</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=38" style="float: left; font-size: 15px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Economy</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=39" style="float: left; font-size: 18px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Family Finance</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=40" style="float: left; font-size: 12px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Lifestyle</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=41" style="float: left; font-size: 16px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Managing Your Business</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=42" style="float: left; font-size: 30px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Retirement</a><a href="https://sample6joomla.advisorproducts.com//index.php?option=com_apicontent&amp;view=apilist&amp;id=43" style="float: left; font-size: 24px; color: rgb(45, 61, 227); display: block; text-decoration: none; margin-left: 2px;" class="tag-cloud">Taxes</a>                </div>
            </div>
                             
                             
                         </div>
                    </div>
            </td>
        </tr><tr><td colspan="2">
                 <div id="bannerf" style="width: 100%;  margin-top:30px;">
                          
                 </div>
            </td>
        </tr></tbody></table></div>
</td>
</tr></tbody></table></div>
                                                   
                                                      <script>
                                                        
                                                      </script>
                                                  
						</dd>
                                                <dt>Advisor <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="advisorstatus" id="advisorstatus" class="status" value="1" <?php if ($this->com_params->get('advisorstatus') != -1 ){ ?> checked="checked" <?php } ?> /></span></dt>
						<dd>
<style>
.setting_widgets_area .dropzone.droppable {
	margin-bottom: 20px;
}
.setting_widgets_area .cr-boundary {
	background-color:#eee;
}
.setting_widgets_area .croppie-container {
	margin-left:0;
}
.setting_widgets_area .croppie-container .cr-slider-wrap {
	margin-bottom:20px;
}
.no-close .ui-dialog-titlebar-close {
	display: none;
}
</style>						
							<!--<p><label>Upload:</label> <input value="" id="uploadadvisor" name="file" type="file" /></p>-->
							<div class="dropzone droppable" id="uploadadvisor" style="min-height: 100px;">
								<span class="prompt_graphic"></span>
								<div class="dz-message">
									Drag & drop your file here or <span style="color: #f02b3b">select the file from your computer</span>
								</div>
							</div>
							
                                                        <div style="float:left; margin-right:5px;" id="advions-prev">
								<?php if ($this->com_params->get('uploadadvisor')):?>
								<?php echo "<img border=0 src='".JURI::base(false)."media/com_enewsletter/images/".$this->com_params->get('uploadadvisor')."' />";?>
								<?php endif;?>
							</div>
							<div style="float:left;">
								<div id="vanilla-uploadadvisor"></div>
								<div><button type="button" id="vanilla-result">Crop</button></div>
							</div>
							<div class="clr"></div>
<script>
var baseurl = '<?php echo JURI::base(false);?>';


var progress_span = '<span class="progress_span"><img src="'+baseurl+'/components/com_enewsletter/assets/images/progress.gif" border="0" /></span>';


jQuery(document).ready(function ($) {
	
	var vanilla = new Croppie(document.getElementById('vanilla-uploadadvisor'), {
						viewport: { width: 160, height: 200 },
						boundary: { width: 160, height: 200 }
					});
					
	
	var filename = '<?php echo $this->com_params->get('uploadadvisor');?>';
	
	if (filename) {
		/*
		vanilla.bind({
						url: baseurl+'<?php echo 'media/com_enewsletter/images/';?>'+filename
					});
		*/
	}//if filename	
	
	
		$('#vanilla-result').attr('disabled','disabled');
	
		
		$('#vanilla-result').click(function(e) {
		
			$(this).attr('disabled','disabled');
			$(progress_span).insertAfter($(this));
		
			vanilla.result({
				type: 'canvas',
				size: 'viewport'
			}).then(function (src) {
				//console.log(src);
				$.ajax({
						url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=setting.savecropadvisor",
						type: "POST",
						data: "imgcode="+src+"&filename="+filename,
						dataType: "json"         
				}).done(function( data ) {
					console.log(data);
					console.log(baseurl+data.filename);
					vanilla.bind(baseurl+data.filename);
					
					$('#vanilla-result').removeAttr('disabled');
					$("#vanilla-result").next().remove();
					
				});
			});
		});
	
	
	
	Dropzone.autoDiscover = false;
	
	$("#uploadadvisor").dropzone({
		url: "<?php echo JRoute::_('index.php?option=com_enewsletter&task=setting.saveuploadadvisor'); ?>",
		paramName: "uploadadvisor", // The name that will be used to transfer the file
		maxFilesize: 200, // MB
		maxFiles: 50,
		uploadMultiple: false,
		createImageThumbnails: false,
		autoProcessQueue: true,
		parallelUploads: 1,
		acceptedFiles: ".gif,.png,.jpg,.jpeg",
		init: function () {
			this.on("addedfile", function (file) {
			});
			this.on("processing", function (file) {
			});
			this.on("success", function (file, response) {
				//console.log(file);
				//this.removeFile(file);
				var result = $.parseJSON(response);
				//console.log(response);
				console.log(result);
				if (parseInt(result.status)>0) {
					alert(result.msg);
					filename = result.filenameonly;
					console.log(baseurl+result.filename);
					vanilla.bind(baseurl+result.filename);
					$('#vanilla-result').removeAttr('disabled');
				} else {
					alert(result.msg);
				}
			});
		}, accept: function (file, done) {
			if (false) {
			} else {
				done();
                                $('#advions-prev').remove();
			}
		}, error: function (file, errorMessage) {
			alert(errorMessage);
		}
	});
});
</script>
 <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
						<dt>Poll <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="pollstatus" id="pollstatus" class="status" value="1"  <?php if ($this->com_params->get('pollstatus') != -1 ){ ?> checked="checked" <?php } ?>   /></span></dt>
						<dd>
							<!--centcom-->
							<ul class="settingpoll" id="settingpoll">
							<?php
							// get poll via curl
							//echo JFactory::getApplication()->getUserState("com_enewsletter.id_site");
                          $url = 'https://centcom.advisorproducts.com/index.php?option=com_acepolls&task=getvote&ids='.JFactory::getApplication()->getUserState("com_enewsletter.id_site");
						  
						  //echo $url;
						  
                          $options = array(
                                CURLOPT_RETURNTRANSFER => true,    
                                CURLOPT_HEADER         => false,   
                                CURLOPT_FOLLOWLOCATION => true,     
                                CURLOPT_ENCODING       => "",      
                                CURLOPT_USERAGENT      => "spider", 
                                CURLOPT_AUTOREFERER    => true,   
                                CURLOPT_CONNECTTIMEOUT => 120,    
                                CURLOPT_TIMEOUT        => 120,     
                                CURLOPT_MAXREDIRS      => 10,      
                                CURLOPT_SSL_VERIFYPEER => false,     
                                CURLOPT_TIMEOUT => 7
                            );

                            $ch      = curl_init( $url );
                            curl_setopt_array( $ch, $options );
                            $content = curl_exec( $ch );
                            $err     = curl_errno( $ch );
                            $errmsg  = curl_error( $ch );
                            $header  = curl_getinfo( $ch );
                            curl_close( $ch );

                            $this_poll = $content;
							
							echo $this_poll;
							?>
							</ul>
							
							<p>Create New Poll:</p>
							<p><input size="50" type="text" id="poll-questtion" value="" /><p>
							<p><button type="button" id="upload_poll" onclick="uploadpoll();">Create Poll</button></p>
							
<script>
function uploadpoll(){
     if( jQuery("#poll-questtion").val() == '' ){
         alert('Please Enter Question');
     }else {
	 
	 	$('#upload_poll').attr('disabled','disabled');

		 jQuery.ajax({
		  url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=uploadpoll",
		  type: "POST",
		  data: "quest="+ jQuery("#poll-questtion").val(),
		  dataType: "json"                           
		 }).done(function( data ) {  
			 
			 console.log(data);
			 //alert(1);
			 
			 var html = '<li><input value="'+data.id+'" name="poll" type="radio"> '+data.title+'</li>';
			 $('#settingpoll').append(html);
			 
			 $('#upload_poll').removeAttr('disabled');
			 
			
		});     
     }
}//func
							
							
							function chagepoll(val1, val2) {
								//jQuery('#poll'+val1).value(val1);
								return;
							}
							
							function viewpoll(value) {
								return;
							}
							
							function radio_active(form, sel_name, val){
								//var f = eval("document.form1."+sel_name);
								for (i = 0; i < form.elements.length; i++){
									if(form.elements[i].name == sel_name) {
										if (form.elements[i].value == val) {
											form.elements[i].checked = true;
											break;
										}
									}
								}
							}
							
							
							radio_active(document.setting_widgets_form, 'poll', <?php echo intval($this->com_params->get('poll'));?>);
							
							
							//class="auto_save_setting_widgets"
							jQuery(document).ready(function ($) {
								$('#settingpoll').delegate('input[type=radio][name=poll]', 'change', function(e){
									//trigger
									$('.auto_save_setting_widgets').trigger('change');
								});
							});
							</script>
                                                         <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
						<dt>Logo <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="logostatus" id="logostatus" class="status" value="1" <?php if ($this->com_params->get('logostatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span> </dt>
						<dd>
							<!--<p><label>Upload:</label> <input value="" id="uploadlogo" name="logo" type="file" /></p>-->
                                                    <div class="dropzone droppable" id="uploadlogo" style="min-height: 100px;">
								<span class="prompt_graphic"></span>
								<div class="dz-message">
									Drag & drop your file here or <span style="color: #f02b3b">select the file from your computer</span>
								</div>
							</div>
							<div style="float:left; margin-right:5px;" id="logo-prev" >
								<?php if ($this->advisorsettings->logo):?>
								<?php echo "<img border=0 src='".JURI::base(false)."media/com_enewsletter/logo/".$this->advisorsettings->logo."' />";?>
								<?php endif;?>
							</div>
							<div style="float:left;">
								<div id="vanilla-uploadlogo"></div>
								<div><button type="button" id="vanilla-result-uploadlogo">Crop</button></div>
							</div>
							<div class="clr"></div>
<script>
jQuery(document).ready(function ($) {

	var vanillalogo = new Croppie(document.getElementById('vanilla-uploadlogo'), {
						viewport: { 
									width: 260,
									height: 65,
									type: 'square'
								},
						boundary: { 
									width: 260,
									height: 65
								}
					});
					
	
	var filenamelogo = '<?php echo $this->advisorsettings->logo;?>';
	
	/*
	if (filenamelogo) {
		vanillalogo.bind({
						url: baseurl+'<?php echo 'media/com_enewsletter/logo/';?>'+filenamelogo
					});
	}//if filenamelogo	
	*/
		
		$('#vanilla-result-uploadlogo').attr('disabled','disabled');
		
		$('#vanilla-result-uploadlogo').click(function(e) {
		
			$(this).attr('disabled','disabled');
			$(progress_span).insertAfter($(this));
		
			vanillalogo.result({
				type: 'canvas',
				size: 'viewport'
			}).then(function (src) {
				//console.log(src);
				$.ajax({
						url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=setting.savecroplogo",
						type: "POST",
						data: "imgcode="+src+"&filename="+filenamelogo,
						dataType: "json"         
				}).done(function( data ) {
					
					vanillalogo.bind(baseurl+data.filename);
					
					$('#vanilla-result-uploadlogo').removeAttr('disabled');
					$("#vanilla-result-uploadlogo").next().remove();
					
				});
			});
		});
	

	$("#uploadlogo").dropzone({
		url: "<?php echo JRoute::_('index.php?option=com_enewsletter&task=setting.saveuploadlogo'); ?>",
		paramName: "uploadlogo", // The name that will be used to transfer the file
		maxFilesize: 200, // MB
		maxFiles: 50,
		uploadMultiple: false,
		createImageThumbnails: false,
		autoProcessQueue: true,
		parallelUploads: 1,
		acceptedFiles: ".gif,.png,.jpg,.jpeg",
		init: function () {
			this.on("addedfile", function (file) {
			});
			this.on("processing", function (file) {
			});
			this.on("success", function (file, response) {
				//console.log(file);
				//this.removeFile(file);
				var result = $.parseJSON(response);
				//console.log(response);
				console.log(result);
				if (parseInt(result.status)) {
					alert(result.msg);
					filenamelogo = result.filenameonly;
					vanillalogo.bind(baseurl+result.filename);
					$('#vanilla-result-uploadlogo').removeAttr('disabled');
				} else {
					alert(result.msg);
				}
			});
		}, accept: function (file, done) {
			if (false) {
			} else {
                            $("#logo-prev").remove();
				done();
			}
		}, error: function (file, errorMessage) {
			alert(errorMessage);
		}
	});
});
</script>
 <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
						<dt>Social Media <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="socialstatus" id="socialstatus" class="status" value="1" <?php if ($this->com_params->get('socialstatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
						<dd>
							<?php
							$social_links = json_decode($this->advisorsettings->social_links);
							?>
							<ul>
								<li><label style="min-width: 150px;" >LINKEDIN:</label> <input size="30" class="auto_save_setting_widgets" value="<?php echo $social_links->linkedin;?>" name="linkedin" placeholder="" type="text" /></li>
								<li><label style="min-width: 150px;" >RSS:</label> <input size="30" class="auto_save_setting_widgets" value="<?php echo $social_links->rss;?>" name="rss" placeholder="" type="text" /></li>
								<li><label style="min-width: 150px;" >FACEBOOK:</label> <input size="30" class="auto_save_setting_widgets" value="<?php echo $social_links->facebook;?>" name="facebook" placeholder="" type="text" /></li>
								<li><label style="min-width: 150px;">GOOGLE PLUS:</label> <input size="30" class="auto_save_setting_widgets" value="<?php echo $social_links->googleplus;?>" name="googleplus" placeholder="" type="text" /></li>
								<li><label style="min-width: 150px;">TWITTER:</label> <input size="30" class="auto_save_setting_widgets" value="<?php echo $social_links->twitter;?>" name="twitter" placeholder="" type="text" /></li>
							</ul>
                                                     <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
						<dt>CTA <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="ctastatus" id="ctastatus" class="status" value="1" <?php if ($this->com_params->get('ctastatus') != -1 ){ ?> checked="checked" <?php } ?>   /></span></dt>
						<dd>
							<!--com_cta-->
							<ul id="ctalistasdsa">
							<?php
							if (JComponentHelper::getComponent('com_cta', true)->enabled) {
								
								JLoader::import('cta', JPATH_ROOT . '/administrator/components/com_cta/models');
                                $ctaModel = JModelLegacy::getInstance('Cta', 'CtaModel', array('ignore_request' => true));             
                                $videos = $ctaModel->getVideos();
								
								if ($videos) {
									echo "<li><h4>Videos</h4></li>";
									foreach ($videos as $video) {
										echo "<li><input type='radio' name='ctavideo' value='".$video['VideoId']."' > <span class=''>".$video['Title'].'</span></li>';
									}//for
								}
								
								$editletterModel = JModelLegacy::getInstance('editletter', 'EnewsletterModel'); 
                            	$local_video =  $editletterModel->getListcta();  
								if ($local_video) {
									echo "<li><h4>Custom</h4></li>";
									foreach (array_reverse($local_video) as $video) {
										echo "<li id='lictacustom".$video['id']."'><input type='radio' name='ctacustom' value='".$video['id']."' > <span class='".$video['file_type']."'>".$video['title'].'</span> <span class="clsdeletecta" rel="'.$video['id'].'">X</span></li>';
									}//for
								}//if
							}//if
							?>
							</ul>
							<script>
							radio_active(document.setting_widgets_form, 'ctavideo', <?php echo intval($this->com_params->get('ctavideo'));?>);
							radio_active(document.setting_widgets_form, 'ctacustom', <?php echo intval($this->com_params->get('ctacustom'));?>);
							
							jQuery(document).ready(function ($) {
							
								$('.setting_widgets_area').delegate('input[name=ctavideo]', 'click', function(e){
									$('input[name=ctacustom]').removeAttr('checked');
									
									//trigger
									$('.auto_save_setting_widgets').trigger('change');
								});
								
								$('.setting_widgets_area').delegate('input[name=ctacustom]', 'click', function(e){
									$('input[name=ctavideo]').removeAttr('checked');
									
									//trigger
									$('.auto_save_setting_widgets').trigger('change');
								});
								
								//
								$('.setting_widgets_area').delegate('.clsdeletecta', 'click', function(e){
									var id = $(this).attr('rel');
									$.ajax({
											url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=deletectavideo",
											type: "POST",
											data: 'id='+id
									}).done(function( data ) {   
										console.log(data);
										$('#lictacustom'+id).remove();
									});
								});
							});
							
							</script>
							
                                                        
							<p>Upload A New CTA Report (Limit 20MB)</p>
                                                        <div class="dropzone droppable" id="uploadctaitem" style="min-height: 50px;">
								<span class="prompt_graphic"></span>
								<div class="dz-message">
									Drag & drop your CTA file here or <span style="color: #f02b3b">select the file from your computer</span>
								</div>
							</div>
                                                        
                                                        <hr>
<script>
jQuery(document).ready(function ($) {
	$("#uploadctaitem").dropzone({
		url: "<?php echo JRoute::_('index.php?option=com_enewsletter&task=uploadvideocta&getraw=1'); ?>",
		paramName: "file_name", // The name that will be used to transfer the file
		maxFilesize: 200, // MB
		maxFiles: 50,
		uploadMultiple: false,
		createImageThumbnails: false,
		autoProcessQueue: true,
		parallelUploads: 1,
		acceptedFiles: ".flv,.mp4,.mp3,.pdf,.docx,.pptx,.xlsx",
		init: function () {
			this.on("addedfile", function (file) {
			});
			this.on("processing", function (file) {
			});
			this.on("success", function (file, response) {
				
				console.log(response);
				
				var result = $.parseJSON(response);
				//console.log(response);
				//console.log(file);
				
				if (parseInt(result.status)>0) {
					//alert(result.msg);
					
					var tmp = file.name;
					var ress = tmp.split('.');
					var extsss = ress[ress.length-1];
					
					var html = '<li id="lictacustom'+result.id+'"><input name="ctacustom" value="'+result.id+'" type="radio"> <span class="ctaicon_'+extsss+'">'+result.title+'</span> <span class="clsdeletecta" rel="'+result.id+'">X</span></li>';
					$('#ctalistasdsa').append(html);
				} else {
					alert(result.msg);
				}
			});
		}, accept: function (file, done) {
			if (false) {
			} else {
				done();
			}
		}, error: function (file, errorMessage) {
			alert(errorMessage);
		}
	});

});
</script>
							
							
							<ul>
                                                            <li><label style="    min-width: 200px;">Title:</label> <input size="30" class="auto_save_setting_widgets" type="text" name="textctatit" value="<?php echo $this->com_params->get('textctatit');?>" /></li>
								<li><label style="    min-width: 200px;" >Name Button:</label> <input size="30" class="auto_save_setting_widgets" type="text" name="textbutonctatit" value="<?php echo $this->com_params->get('textbutonctatit');?>" /></li>
								
								<li><label style="    min-width: 200px;">Color Background:</label> <input size="8" class="jscolor auto_save_setting_widgets" type="text" name="cobactatit" value="<?php echo $this->com_params->get('cobactatit');?>" maxlength="6" /></li>
								<li><label style="    min-width: 200px;">Color Text:</label> <input size="8" class="jscolor auto_save_setting_widgets" type="text" name="cotectatit" value="<?php echo $this->com_params->get('cotectatit','000000');?>" maxlength="6" /></li>
								<li><label style="    min-width: 200px;">Button Background:</label> <input size="8" class="jscolor auto_save_setting_widgets" type="text" name="btcobactatit" value="<?php echo $this->com_params->get('btcobactatit','FFAD61');?>" maxlength="6" /></li>
								<li><label style="    min-width: 200px;">Button Color Text:</label> <input size="8" class="jscolor auto_save_setting_widgets" type="text" name="btcotectatit" value="<?php echo $this->com_params->get('btcotectatit');?>" maxlength="6" /></li>
								
								
							</ul>
<img src="<?php echo JURI::root(true);?>/components/com_enewsletter/assets/images/pre4.jpg"  style="  float: right;        width: 200px;    margin-top: -380px;" >
							<div class="dropzone droppable" id="uploadctaimage" style="min-height: 100px;">
								<span class="prompt_graphic"></span>
								<div class="dz-message">
									Drag & drop your image cta here or <span style="color: #f02b3b">select the file from your computer</span>
								</div>
							</div>
							<div style="float:left; margin-right:5px;" id="ctaimg-prev">
								<?php if ($this->com_params->get('uploadctaimage')):?>
								<?php echo "<img border=0 src='".JURI::base(false)."media/com_enewsletter/upload/".$this->com_params->get('uploadctaimage')."' />";?>
								<?php endif;?>
							</div>
							<div style="float:left;">
								<div id="vanilla-uploadctaimage"></div>
								<div><button type="button" id="vanilla-result-uploadctaimage">Crop</button></div>
							</div>
							<div class="clr"></div>
							
<script>
jQuery(document).ready(function ($) {

	var vanillactaimage = new Croppie(document.getElementById('vanilla-uploadctaimage'), {
						viewport: { 
									width: 160,
									height: 200,
									type: 'square'
								},
						boundary: { 
									width: 160,
									height: 200
								}
					});
					
	
	var filenamectaimage = '<?php echo $this->com_params->get('uploadctaimage');?>';
	/*
	if (filenamectaimage) {
	
		vanillactaimage.bind({
						url: baseurl+'<?php echo 'media/com_enewsletter/upload/';?>'+filenamectaimage
					});
	}//if filenamectaimage	
	*/
		$('#vanilla-result-uploadctaimage').attr('disabled','disabled');
		
		$('#vanilla-result-uploadctaimage').click(function(e) {
		
			$(this).attr('disabled','disabled');
			$(progress_span).insertAfter($(this));
		
		
			vanillactaimage.result({
				type: 'canvas',
				size: 'viewport'
			}).then(function (src) {
				//console.log(src);
				$.ajax({
						url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=setting.savecropctaimage",
						type: "POST",
						data: "imgcode="+src+"&filename="+filenamectaimage,
						dataType: "json"         
				}).done(function( data ) {
					console.log(data);
					console.log(baseurl+data.filename);
					vanillactaimage.bind(baseurl+data.filename);
					
					$('#vanilla-result-uploadctaimage').removeAttr('disabled');
					$("#vanilla-result-uploadctaimage").next().remove();
					
				});
			});
		});
	
	
	
	$("#uploadctaimage").dropzone({
		url: "<?php echo JRoute::_('index.php?option=com_enewsletter&task=setting.saveuploadctaimage'); ?>",
		paramName: "uploadctaimage", // The name that will be used to transfer the file
		maxFilesize: 200, // MB
		maxFiles: 50,
		uploadMultiple: false,
		createImageThumbnails: false,
		autoProcessQueue: true,
		parallelUploads: 1,
		acceptedFiles: ".gif,.png,.jpg,.jpeg",
		init: function () {
			this.on("addedfile", function (file) {
			});
			this.on("processing", function (file) {
			});
			this.on("success", function (file, response) {
				//console.log(file);
				//this.removeFile(file);
				var result = $.parseJSON(response);
				//console.log(response);
				console.log(result);
				if (parseInt(result.status)>0) {
					alert(result.msg);
					
					filenamectaimage = result.filenameonly;
					console.log(baseurl+result.filename);
					vanillactaimage.bind(baseurl+result.filename);
					$('#vanilla-result-uploadctaimage').removeAttr('disabled');
					
				} else {
					alert(result.msg);
				}
			});
		}, accept: function (file, done) {
			if (false) {
			} else {
				done();
                                $("#ctaimg-prev").remove();
			}
		}, error: function (file, errorMessage) {
			alert(errorMessage);
		}
	});
});
</script>					 <button type="button" class="button-blu" id="poll-save">Save</button>		
						</dd>
						<dt>Location <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="addressstatus" id="addressstatus" class="status" value="1" <?php if ($this->com_params->get('addressstatus') != -1 ){ ?> checked="checked" <?php } ?>   /></span> </dt>
						<dd>
							<h4>Address 1</h4>
							<ul > 
                                <li>
									<label >Firm</label>						
									<input size="30" class="auto_save_setting_widgets" name="firm" value="<?php echo $this->advisorsettings->firm;?>" type="text">
								</li>	
												<li>
									<label >Email</label>						
									<input size="30" class="auto_save_setting_widgets" name="from_email" value="<?php echo $this->advisorsettings->from_email;?>" type="text">
								</li>	
												 <li>
									<label >URL</label>						
									<input size="30" class="auto_save_setting_widgets" name="url" value="<?php echo $this->advisorsettings->url;?>" type="text">
								</li>	
												<li>
									<label >Address</label>						
									<input size="30" class="auto_save_setting_widgets" name="address_address1" value="<?php echo $this->advisorsettings->address1;?>" type="text">
								</li>				
								<li>
									<label >Address 2</label>						
									<input size="30" class="auto_save_setting_widgets" name="address_address2" value="<?php echo $this->advisorsettings->address2;?>" type="text">
								</li>				
								<li>
									<label >Phone</label>	
									<input size="30" class="auto_save_setting_widgets" name="address_phone" value="<?php echo $this->advisorsettings->phone;?>" type="text">
								</li>				
								<li>
									<label >City</label>						
									<input size="30" class="auto_save_setting_widgets" name="address_city" value="<?php echo $this->advisorsettings->city;?>" type="text">
								</li>	
												<li>
									<label >State</label>						
									<input size="30" name="address_state" class="auto_save_setting_widgets" value="<?php echo $this->advisorsettings->state;?>" type="text">
								</li>	
								<li>
									<label >Zip</label>						
									<input size="30" name="address_zip" class="auto_save_setting_widgets" value="<?php echo $this->advisorsettings->zip;?>" type="text">
								</li>				
							</ul>
							<h4>Address 2</h4>
							<ul>
                                <li>
									<label>Use Address 2</label>						
									<input size="30" class="auto_save_setting_widgets" name="useaddress2" type="checkbox" value="1" <?php echo $this->com_params->get('useaddress2')?'checked="checked"':'';?> >
								</li>	
												<li>
									<label >Address</label>						
									<input size="30" class="auto_save_setting_widgets" name="address2_address1" value="<?php echo $this->advisorsettings->second_address1;?>" type="text">
								</li>				
								<li>
									<label >Address 2</label>						
									<input size="30" class="auto_save_setting_widgets" name="address2_address2" value="<?php echo $this->advisorsettings->second_address2;?>" type="text">
								</li>	
												<li>                                  
									<label >Phone</label>	
													   
										<input size="30" class="auto_save_setting_widgets" name="address2_phone" value="<?php echo $this->advisorsettings->second_phone;?>" type="text">
														
												</li>
												<li>
									<label >City</label>						
									<input size="30" class="auto_save_setting_widgets" name="address2_city" value="<?php echo $this->advisorsettings->second_city;?>" type="text">
								</li>	
												<li>
									<label >State</label>						
									<input size="30" name="address2_state" class="auto_save_setting_widgets" value="<?php echo $this->advisorsettings->second_state;?>" type="text">
								</li>	
								<li>
									<label >Zip</label>						
									<input size="30" name="address2_zip" class="auto_save_setting_widgets" value="<?php echo $this->advisorsettings->second_zip;?>" type="text">
								</li>	
							</ul>
                                                         <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
						<dt>Template Intro <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="introstatus" id="introstatus" class="status" value="1"  <?php if ($this->com_params->get('introstatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
						<dd>
							<textarea name="temintro-edit-text" class="auto_save_setting_widgets" style="width:300px;" rows="5"><?php echo $this->com_params->get('temintro');?></textarea> <img src="<?php echo JURI::root(true);?>/components/com_enewsletter/assets/images/pre3.jpg"  style="  float: right;     width: 35%;" >
                                                         <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
						<dt>Map <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="mapstatus" id="mapstatus" class="status" value="1"  <?php if ($this->com_params->get('mapstatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
						<dd>
							<ul>
								<li><label>Address</label> <input size="30" class="" value="<?php echo $this->com_params->get('mapaddress');?>" name="map-edit-img" placeholder="address, city, state " type="text" id="map-edit-img-ht" /></li>		
								<li><label>Zoom</label>	
									<select name="mapzoom" id="mapzoom-ht">
										<option value="-2" <?php echo ($this->com_params->get('mapzoom')==-2?'selected="selected"':'');?>>-2</option>
										<option value="-1" <?php echo ($this->com_params->get('mapzoom')==-1?'selected="selected"':'');?>>-1</option>
										<option value="0" <?php echo ($this->com_params->get('mapzoom')==0?'selected="selected"':'');?>>0</option>
										<option value="1" <?php echo ($this->com_params->get('mapzoom')==1?'selected="selected"':'');?>>1</option>
										<option value="2" <?php echo ($this->com_params->get('mapzoom')==2?'selected="selected"':'');?>>2</option>
									</select>
								</li>
							</ul>
							<div id="thehtmap" style="width:300px;"></div>
							
<script>
$(document).ready(function() {
	
	function showmap(val, zoom) {
		
		$("#thehtmap").empty();
		
		if (val) {
		
			var  urls = "https://maps.googleapis.com/maps/api/geocode/json?address="+val+"&key=AIzaSyAcBsiCXaeb4H4wZDMNtnjSRRCKP_B2D1M";
				$.ajax({
				  url: urls,
				  type: "GET"           
				}).done(function( data ) {                                                    
				  var lat =   data.results[0].geometry.location.lat;
				  var lng =   data.results[0].geometry.location.lng;
				  
				  zoom = zoom*2 + 13;
				  
				  $("#thehtmap").append('<img src="https://maps.googleapis.com/maps/api/staticmap?center='+lat+','+lng+'&zoom='+zoom+'&size=210x210&maptype=roadmap&markers=color:blue%7Clabel:S%7C'+lat+','+lng+'&key=AIzaSyBNJIeTGgrFxcrTgo0YKZoj7Y-T7IYapS8&zoom=10" alt="Smiley face" width="100%"> ');			
				
				});
		}//if
	}//func
	
	$("#map-edit-img-ht").change(function(e) {
		var val = $(this).val();
		showmap(val, $("#mapzoom-ht").val());
		//trigger
		$('.auto_save_setting_widgets').trigger('change');
	});
	
	$("#mapzoom-ht").change(function(e) {
		var val = $(this).val();
		showmap($("#map-edit-img-ht").val(), val);
		//trigger
		$('.auto_save_setting_widgets').trigger('change');
	});
	
	
	showmap($("#map-edit-img-ht").val(), $("#mapzoom-ht").val());
	
});
</script>
							
						 <button type="button" class="button-blu" id="poll-save">Save</button>	
						</dd>
						<dt>Tag Cloud <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="tagstatus" id="tagstatus" class="status" value="1" <?php if ($this->com_params->get('tagstatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
						<dd>
							<ul>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Estate Planning" /> Estate Planning</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Insurance" /> Insurance</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Investing" /> Investing</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Economy" /> Economy</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Family Finance" /> Family Finance</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Lifestyle" /> Lifestyle</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Managing Your Business" /> Managing Your Business</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Retirement" /> Retirement</li>
								<li><input class="auto_save_setting_widgets" type="checkbox" name="cloudcheck[]" value="Taxes" /> Taxes</li>
							</ul>
							<script>
							function checkbox_active(form, sel_name, val){
	
								val = ','+val.toString()+',';
								
								//alert(val);
								
								for (i = 0; i < form.elements.length; i++){
									if(form.elements[i].name == sel_name) {
									
										//alert(form.elements[i].value);
									
										if (val.search(','+form.elements[i].value+',')!=-1) {
											form.elements[i].checked = true;
										}
									}
								}
								
							}
							checkbox_active(document.setting_widgets_form, 'cloudcheck[]', '<?php $cloudcheck = $this->com_params->get('cloudcheck'); echo implode(',',$cloudcheck)?>');
							</script>
                                                         <button type="button" class="button-blu" id="poll-save">Save</button>
						</dd>
                                                
                                                <dt>Meetting <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="meetingstatus" id="tagstatus" class="status" value="1" <?php if ($this->com_params->get('meetingstatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
                                                <dd></dd>
                                                
                                                <dt>Event <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="eventstatus" id="tagstatus" class="status" value="1" <?php if ($this->com_params->get('eventstatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
                                                <dd></dd>
                                                
                                                <dt>Weekly Update <span style="float:right;    margin-top: 6px;" ><input type="checkbox" name="weeklystatus" id="tagstatus" class="status" value="1" <?php if ($this->com_params->get('weeklystatus') != -1 ){ ?> checked="checked" <?php } ?>  /></span></dt>
                                                <dd></dd>
					</dl>
					<div class="bigsavebutton" style="display:none;"><button type="button" id="btn_setting_widgets">Save</button></div>
					</form>
					 <div id="ajaxloadingplaceholder"></div>
				 </div><!--setting_widgets_area-->
				
<script>
    
$(document).ready(function() {
	$('.setting_widgets_area').hide();
	
	$("#setting_widgets_dl").accordion({heightStyle: 'content'});
	
	$('#setting_widgets').click(function(e) {
		e.preventDefault();
		$('.others_area, .wekllyvid').hide();
		$('.setting_widgets_area').show();
	});
        
      
        $('.status').on('switchChange.bootstrapSwitch', function (event, state) {
                      savesetting();
                     
        });
                
	
	
	function savesetting() {
	
		$( "#ajaxloadingplaceholder" ).dialog({
									dialogClass: "no-close dialog_style1",
									draggable: false,
									modal: true,
									title: "Saving..."
								});
		
	
	
		//ajax save
		var postdata = $('#setting_widgets_form').serialize();
		
		$.ajax({
				type: "POST",
				url: '<?php echo JURI::root(true);?>/index.php?option=com_enewsletter&task=setting.savewidgets',
				data: postdata,   
				dataType: 'json',
				rnd: Math.random(),
				success: function(data) {
					//alert('Success!');
					console.log(data);
					$("#ajaxloadingplaceholder").dialog( "close" );
				},
				error: function(XMLHttpRequest, textStatus, errorThrown)
				{
					alert(textStatus);
					$("#ajaxloadingplaceholder").dialog( "close" );
				}
			});
	}
	
	$('.auto_save_setting_widgets').change(function(e) {
		savesetting();
                changecorlor();
	});
        
          function changecorlor() {
                                $("#thispreview > table, #thispreview .weeklyupdatetable table , #thispreview .weeklyupdatetable table td ").css('background','#'+$('#backgc').val());
                                $("#thispreview > table").css('background','#'+$('#backgc').val());   
                                $("#thispreview .edit_content").css('background','#'+$('#backgc').val());   
                                $("#thispreview .edit_content table div  ,#thispreview .edit_content > td > table > tbody > tr > td > div ,#thispreview #api-title , #thispreview .intro ,#thispreview #settingintro ,#thispreview #settingdeclo ,#thispreview  p font ").css('color','#'+$('#maintextgc').val());   
                                $("#thispreview #address table").css('color','#'+$('#maintextgc').val());                                   
                                $("#thispreview #intro ,#thispreview  .intro").css('background','#'+$('#backbargc').val());   
                                $("#thispreview .edit_content table div a ,#thispreview   #schedule a , #thispreview #serminar1 a,#thispreview  #weekly a ,#thispreview  #invest a ,#thispreview  #cloud-tag a ,#thispreview  #address a").css('color','#'+$('#linktextgc').val());   
                                                          }
         changecorlor();
                                                          
	
	
	
	$('#btn_setting_widgets').click(function(e) {
		e.preventDefault();
		savesetting();
	});
	
});
</script>
  <div class="wekllyvid" style="display:none;">
 <?php echo $this->weeklyvideo; ?>  
</div>

			<div class="others_area">
				 
				 <h2 style="     border-bottom: 1px solid #ccc;    background: #fff;    margin-top: 0px;    padding: 20px 50px;"><?php  $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$this->allsetting->template_weekly ;
                 if (file_exists($directory) && $this->allsetting->template_weekly != ''){ ?> Name: <?php  echo  $aask[1]; ?>  <?php    
                 $date =  date ("d M Y ", filemtime($directory)); ?> <span style="float:right;" ><?php echo $date; ?></span>
                 <?php } ?>
                 </h2>
                 
                 <?php if(JRequest::getVar('editcontent')== 1 && 0 == 1 ){ echo $this->maildata; ?>  
                 
                                <textarea id="custom-content3" name="bottaa" > </textarea>
                                <table style="width: 100%">                                       <tr>                                            <td> 
                                            <button type="button" class="button-blu content-save" id="" >Save</button> </td>                                            <td>                                              </td>   </tr>  </table>
                                 <script>
                                       $(document).ready(function() {
                                            $("#cta").empty();
                                            
                                           $("#custom-content3").val($("#cta").html());
                                          
                                       tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#custom-content3',
                                                    height: 200,
                                                    theme: 'modern',
                                                    plugins: [
                                                      'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                                      'searchreplace wordcount visualblocks visualchars code fullscreen',
                                                      'insertdatetime media nonbreaking save table contextmenu directionality',
                                                      'emoticons template paste textcolor colorpicker textpattern imagetools'
                                                    ],
                                                    toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                                                    toolbar2: 'print preview media | forecolor backcolor emoticons',
                                                    image_advtab: true,
                                                    templates: [
                                                      { title: 'Test template 1', content: 'Test 1' },
                                                      { title: 'Test template 2', content: 'Test 2' }
                                                    ],
                                                    content_css: [
                                                      '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                                      '//www.tinymce.com/css/codepen.min.css'
                                                    ] });
                                      });
                                      </script>
                                      </textarea>     <?php  }else{
                                          if ($this->maildata != ''){
                                            echo $this->maildata;}
                                          else {
                                            if ($this->allsetting->template_weekly == '')  {
                                                echo '<div style="text-align:center;" >Sites is using weekly update in back office. Please choose templates to begin to use</div>';  
                                            }else {
                                                echo '<div  style="text-align:center;" >Template weekly had been deleted . Please choose another template </div>';  
                                            }
                                          }
                                          } ?>                 
            </div><!--others_area-->   
           </div><!--col-2-->   
        </div><!--allpage-->


 <div id="popup2_open"  style="overflow-y: auto;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;    width: 900px;    height: 650px;" >
            <h2 style="margin-bottom: 0px;">Choice Enewsletter:</h2>
          
            <span onclick=" $('#popup2_open').bPopup().close();" class="btclose" ><img style="width: 32px;height: 32px" src="/components/com_enewsletter/assets/images/close-window-xxl.png" /></span>
              
              
              
              <div class="divchothumtm2" style="width:100%;float: left;margin-bottom:20px; height:550px; overflow-y: auto;    margin-top: 0px;">
                  <style>
                      
                      .divchothumtm2   .dataTables_filter {
                          display:none;
                      }
                      .divchothumtm2 .dataTables_length {
                          margin-bottom:0px;
                          float: right;
                          text-align: right;
                      }
                      
                  </style>
                  <script>
                $(document).ready(function() {
                    oTable = $('#articletable4').dataTable( {
                                "aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
                                'iDisplayLength': 50,
                                "aoColumnDefs": [
                                        { "bSortable": false, "aTargets": [ 0] }
                                ],
                                "oLanguage": {
                                  "sSearch": "Search Articles:"
                                }
                        });
                          oTable.fnSort( [ [2,'desc'] ] );      
		});
                </script>
                  
                  <table id="articletable4" style="text-align: center;width: 100%;margin-top: 50px;">
                          <thead style="background: #f0f0f0;">
                            
                                  <td  style=" width: 10%; ">  </td>    
                                  <td  style=" width: 40%; "> Name </td>       
                                  <td   style=" width: 15%; "  > Date </td>
                                  
                                  <td  style=" width: 30%; " >  </td>
                               
                            
                          </thead>
                 
                  
                  <tbody>
                   <?php 
                   
                   
                   foreach ($this->tems_user as $r){ 
                       
                         $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                            $ktmp[filemtime($directory)] = $r ;     
                   }
                   ksort($ktmp);
                 
                   $this->tems_user = $ktmp;
                   
                   
                   $jk == 1; 
                   foreach (  array_reverse($this->tems_user) as $r){   
                        $pos = strpos( $r ,  "enewsletter_threecol" );
                        if ($pos !== false){
                            continue;
                        }
                       
                                 $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                                     if (file_exists($directory)) {
                                           $date =  date ("d M Y ", filemtime($directory));
                                           $date = '<span style="display:none;" >'.filemtime($directory).'</span>'.$date;
                                        }
                       
                       ?>
                  
                   
                  
                 
                  <tr style="background: #fff;"  class="chothumtm1  aak<?php echo $jk; ?>" >
                      
                      <td >
                      <style>
                      
                       .aak<?php echo $jk; ?> button {
                          display:none;
                      }
                       .aak<?php echo $jk; ?>:hover button {
                          display:block;
                      }
                      
                       .aak<?php echo $jk++; ?>:hover td {
                          border-bottom:1px solid #ccc!important;
                            border-top:1px solid #ccc!important;
                           
                      }
                      
                      
                  </style>
                  
                     <img style="float:left;width:100%;" src="data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs%3D" data-url="<?php echo str_replace('http%3A%2F%2F','https%3A%2F%2F',urlencode(juri::base())).'administrator%2Fcomponents%2Fcom_enewsletter%2Ftemplates%2F'.$r.'.html' ?>" alt="Google">
                        
                      </td>  
                     
                      <td style="text-align: left;background: #fff!important;" >
                          <?php $str =  str_replace("_".JFactory::getUser()->id."_", " : ", $r);    $str = explode(':', $str); echo $str[1] ?>
                      </td>
                       <td  style="background: #fff!important;" >
                          <span style="color:blue"  ><?php echo $date ?></span>
                      </td>
                      <td style="background: #fff!important;" >
                           <button onclick=' $("#templatechioce").val("<?php echo $r; ?>");$("#adformdds").submit();' style="    float: right;     margin-top:5px;  width: 40%; margin-right: 15px;   color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer;">Choice >></button>
                       
                     
                       
                      </td>
                       
                       
                      
                  </tr>
                   <?php } ?>
                  </tbody>   </table>
                 
              </div>
          
                        
                  
                      
        </div>
      
  <input type="hidden" id="apikey" name="apikey" value="<?php echo $this->allsetting->api_key  ?>">
  <input type="hidden" id="newsletter_api" name="newsletter_api" value="<?php echo $this->allsetting->newsletter_api  ?>">
      
   <form id="adformdds" method="post" action="index.php"  enctype="multipart/form-data" style="text-align: center;height: 35px;" >
                      
                        
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" id="task" name="task" value="setting.savetemplate" >                      
                         <input type="hidden" name="view" value="setting" >
                         <input type="hidden" name="templateskk"  id="templatechioce" value="" >
                      
                         <?php echo JHtml::_('form.token'); ?>
                        
    </form>      
        
  
      
<?php die; ?>