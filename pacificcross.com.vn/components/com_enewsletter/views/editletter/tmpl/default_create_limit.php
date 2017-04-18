<?php
defined('_JEXEC') or die;
$custome_url  =  $this->custome_url;




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
        <link rel="stylesheet" href="<?php echo JURI::base(); ?>administrator/components/com_enewsletter/css/demo_table.css">
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.bpopup.min.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/demo/prism.css" /> 
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/croppie.css" />
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.range.css" />
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.range-min.js"></script>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.dataTables.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/style2.css" />
       
<script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/url2img.js"></script>


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
            body{
                    margin-top: 40px;
            }
              .allpage {
               width: 100% ;
              }
              .col-1{
                      width: 380px;
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
                #weekly:hover,   #invest:hover, #address:hover, #logomail:hover, .module:hover{
                  cursor: pointer;
                  border: red 3px solid!important;
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
                    font-size: 18px;
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
                    background-image: url('<?php echo juri::base();?>components/com_enewsletter/assets/images/addnew.jpg'); 
                       background-size: 35px;
    					background-position-x: 8px;
              }
              .aask2{
                    background-image: url('<?php echo juri::base();?>components/com_enewsletter/assets/images/open.jpg'); 
                       background-size: 35px;
    					background-position-x: 8px;
              }
              .aask3{
                    background-image: url('<?php echo juri::base();?>components/com_enewsletter/assets/images/his.jpg'); 
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
        </style>
     
       
   


  <script>
             
               function newtemp(){
                   
                      var sss = $("input[type='radio'][name='optionf']:checked");
                      var name =  $('#changetemps_popup').val()
                      if( $.trim(name) == '' ){
                            alert('Input Name Layout');
                          
                      }else if( !$('.chothumtm').hasClass('active')){
                            alert('Choice Format');
                      }else if ( sss.length == 0 ) {
                             alert('Select Option');
                      }else {
                             $('#changetemps').val($.trim(name));     
                             $('#idt').val($('#sasasasa').val());   
                             $('#new_tem').val('1');   
                             $('#task').val('');
                             $('#optionf').val($("input[type='radio'][name='optionf']:checked").val());
                             $('#adform').submit();
                      }
               }
                function lisennoappprove(){
                     $('.newtemwam').remove();
                      $(".bfformat").after('<span class="newtemwam" style="  display: block; color:red;    width: 100%;    float: left;"  > Only the single-column format is available to you. To use all of the powerful new features in the email newsletter, please call Advisor Products to upgrade at (516) 333-0066 ext. 333. </span>');
               }
             

              
        </script>
       <script>
       
         $( document ).ready(function() {
              $(function(argument) {
                                $('.btch').bootstrapSwitch();
                              })
                $('#popup3_new').bPopup();
                $('.btch').bootstrapSwitch('state', true);
                
                $('.btch').on('switchChange.bootstrapSwitch', function (event, state) {

                    var ref = $(this).attr('ref');
                    var res = ref.split(",");
                    var a = res[0];
                    var b = res[1];
               // alert(b);
                    if (state){
                            $("#"+a+" input[type='checkbox']").prop('checked',true);
                            $("#"+b).show();
                    }else{
                            $("#"+a+" input[type='checkbox']").prop('checked',false);
                            $("#"+b).hide();
                    }
                    ajaxsave()
                });
        });

       </script>
<script>
$(document).ready(function() {
       $(".cta-btn-circle").click(function(){
                    if ( $(this).attr('data_href')){
                        window.location.replace($(this).attr('data_href'));
                    }else {
                        window.location.replace("<?php echo juri::base(); ?>");
                    }
                });      
	
    api = $('#apikey').val();
    apitype = $('#newsletter_api').val(); 
  
    if(api){
        $.ajax({
               type : 'POST',
        			url: 'index.php?option=com_enewsletter&task=getverifiedemaillist',
              data :   'apitype='+apitype+'&apikey='+api,
        			success: function( data ){
                    $('#verified_email').html(data);              
              }
        		}); 
      }

	} );

    function fill_email(a){

    }
 
</script>
    <script>
    function addnameauto(a){
            var d = new Date();
            var aas = parseInt(d.getMonth()) + 1;
             if (aas < 10){
                 aas = '0'+aas;
             } 
             if (d.getDate() < 10){
                 var aab = '0'+d.getDate();
             } else {
                 var aab = d.getDate();
             }
             var timen = aas+'-'+aab+'-'+d.getFullYear()+' '+d.getHours()+' '+d.getMinutes();
         if ( $('#changetemps_popup').val().length  < 1 || $('#changetemps_popup').val().indexOf(timen) >= 0 ) {            
                $('#changetemps_popup').val(a+' '+timen );
         }
            
    }

</script>

        <div class="allpage" >
              <input type="hidden" id="valueidartical" value="" >
            <div class="col-1" > 
                   <?php  include  JPATH_SITE.DS.'modules'.DS.'mod_leftmenuedit'.DS.'menu_defautls.php'; ?>   
                        
                       
                        
                   
                <div class="main-1 left_menu_wrap" style="    margin-bottom: 58px;">
                    
                    <div style="background-color: #e4e4e4 !important; padding-top: 20px;padding-bottom: 20px;padding-left: 30px;"><div class="sqs-navigation-item"><span class="icon icon-projects"></span></div>Enewsletter</div>
                  
                    <form id="adform" method="post" action=""  enctype="multipart/form-data" style="text-align: center;height: 35px;" >
                        <textarea name="htmlcode" id="htmlcode" style="display: none;"></textarea>
                        <div id="block-button" style="    margin-top: 30px;    text-align: left;   "> 
                            <span class="edittem aask1" onclick=" $('#popup3_new').bPopup();  "  > NEW</span>
                             <span class="edittem aask2" > OPEN</span>
                              <span class="edittem aask3"   > HISTORY </span>
                            
                           
                        </div>
                        <br>
                        
                        <div class="seving" style="color: red;display: none;    margin-left: 5px;    margin-top: -4px;    position: absolute;" > Saving... </div>
                        <div class="sed" style="color: blue;display: none;    margin-left: 5px;    margin-top: -4px;    position: absolute;"> Saved</div>
                        
                     
                      
                         <button  id="adform-button1" type="button" style=" display:none;   border: none;    text-align: center;        margin: -38px -27px 25px;   padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;     font-size: 0;    
                                  background: url('<?php echo juri::base();?>components/com_enewsletter/assets/images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 255px;    height: 47px;      " >Send Mail</button>
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
                    
                   
                    
                    <div id="Formsend" class="block-2" >
                        <div class="image1" > <i class="fa fa-envelope-o fa-3x"></i>  </div>
                        <div class="text1" > From: <div id="verified_email"></div>  </div>                      
                        
                    </div>
                    
                     <div id="Formsend" class="block-2" >
                        <div class="image1" > <i class="fa fa-cc fa-3x"></i>  </div>
                        <div class="text1" > Subject: <input type="text" id="jform_subject" /> </div>                      
                           <button  type="button" style="      border: none;    text-align: center;    margin: -40px 2px 25px;  
    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;    min-width: 100px;    float: left;    font-size: 0;    background: url('images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 292px;    height: 85px; " >Send Mail</button>
                    </div>
                    
                    <div id="advions-control" class="block-2" onclick=" showedit('advions','advions-content');window.scrollTo(0, $('#advions-content').offset().top);">
                        <div class="image1" > <i class="fa fa-user fa-3x"></i>  </div>
                        <div class="text1" > Advisors  <div style="float:right;" ><input id="openadvisor" data-size="small"  class="btch" ref="form1,advions-content" type="checkbox"  name="position1" checked="checked" value="Yes"  ></div></div>                      
                        
                    </div>
                    
                         <div id="advions-edit" class="block-3" style="display: none;">
                         
                        <form id="form1" name="form1">
                            <br>
                            Select Person:<br>
                             <?php 
                             
                                $i=0;
                                foreach ($this->team as $r){
                                    if($r->path != ''){
                                        $link = $r->path;
                                    }else{
                                          $link = $r->link;
                                    }                                   
                                    ?>
                                    <li> <input type="radio" name="who" id="w<?php echo $i; ?>" value="<?php echo $link; ?>" >  <?php echo $r->title; ?> </li>

                                <?php $i++;} ?>
                            <br><br>
                            
                           
                        <div id="upload-demo" style="display:none;"></div>
                       
                      
                        <br> Or Upload : <br>
                        <input value="" type="file"  id="upload" onchange="$('#upload-demo').show();"  name="file" >
                        <div style="display:none;">
                          
                        <br><br>
                        Enable :  <input type="checkbox" name="position1" checked="checked" value="Yes">
                        
                        </div>
                        
                        <button type="button" class="button-blu" id="advions-save" >Save</button>                           
                                     
                      
                        
                        </form>
                    </div>
                        <div id="poll-control" class="block-2" onclick="showedit('poll');window.scrollTo(0, $('#poll-content').offset().top);" >
                           <div class="image1" > <i class="fa fa-bar-chart fa-3x"></i> </div>
                           <div class="text1" > poll <div style="float:right;" ><input id="openpoll" data-size="small"  class="btch"  ref="form2,poll-content" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      

                       </div>
                      <div id="poll-edit" style="display: none;"  class="block-3" >
                        <form id="form2">
                          
                        <br><br>
                        title: <br>
                        <input value="quick poll" type="text" id="poll-edit-title" placeholder="" >
                        <textarea  type="text" id="poll-edit-content" value="" style="display: none;width: 295px;" >
                            

                        </textarea>
                        <input  type="text" id="poll-edit-linktrue" value="#" style="display: none;">
                        <input  type="text" id="poll-edit-linkfalse" value="#" style="display: none;">
                        <ul id="list_poll" style="    margin-left: -43px;    width: 95%;">
                                  <?php 
                                 echo $this->poll;
                                    ?>
                            
                        </ul>
                        <script>
                            function chagepoll(a,b){
                                 $( "#poll-edit-content" ).val(b);
                                 $("#poll-edit-content").show();
                                 $( "#poll-edit-linktrue" ).val('https://centcom.advisorproducts.com/index.php?option=com_acepolls&task=updatepoll&op=t&id='+a);
                                 $( "#poll-edit-linkfalse" ).val('https://centcom.advisorproducts.com/index.php?option=com_acepolls&task=updatepoll&op=f&id='+a);
                                 
                            }
                        </script>
                        
                         <div style="display:none;">
                        <br>
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                         </div>
                      
                        <button type="button" class="button-blu" id="poll-save" >Save</button>
                       
                        <script>
                           
                            $( "#poll-save" ).click(function() {
                               
                               if ( $("#form2 input[type='checkbox']:checked").val() == 'Yes' ){
                                 
                                   $( "#poll-content" ).empty();
                                   $( "#poll-content" ).append('  <h1 class="poll-content-title1" style="color: #F79925!important;    text-transform: uppercase;    font-size: 18px!important; " >   '+  $( "#poll-edit-title" ).val() +'     </h1> ');
                                   $( "#poll-content" ).append('   <div class="poll-content-text1">  <div>'+ $( "#poll-edit-content" ).val() +'</div><br>  ');
                                   $( "#poll-content" ).append('    <a  style="    color: #F79925;    background-color: #0F4180;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;" target="_blank" href="'+ $( "#poll-edit-linktrue" ).val() +'" > TRUE </a><a style="    color: #F79925;    background-color: #0F4180;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;"  targer="_blank"  href="'+$( "#poll-edit-linkfalse" ).val()+'"  > FALSE </a>  </div>  ');  
                                   $('#poll-content').show();                                    
                               }else{                                   
                                   $('#poll-content').hide();
                                   
                               }
                                 ajaxsave();
                                
                            });

                        </script>
                        
                        </form>
                    </div>
                       <div id="logo-control" class="block-2" onclick="showedit('logo','logomail');window.scrollTo(0, $('#logomail').offset().top);" >
                        <div class="image1" > <i class="fa fa-500px fa-3x"></i>  </div>
                        <div class="text1" > logo <div style="float:right;" ><input id="openlogo" data-size="small"  class="btch"  ref="form9,logomail" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                    
                    
               
                    
                  
                                     
                    <div id="logo-edit" class="block-3" style="display: none;"   >
                          <br>
                        <form id="form9">
                          
                        <div id="upload-demo-logo" style="display: none;"></div>
                        
                         Upload : <br>
                        <input value="" type="file" name="file" id="file2" onchange="$('#upload-demo-logo').show();" >
                         <div style="display:none;">
                        <br><br>
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                         </div>
                      
                        
                        <button type="button" class="button-blu" id="logo-save" >Save</button>
                          
                  
                       
                      
                        
                        </form>
                    </div>
                    
                    
                    
                    
                    <div id="social-control" class="block-2" onclick="showedit('social','social');window.scrollTo(0, $('#social').offset().top);">
                        <div class="image1" > <i class="fa fa-facebook-official fa-3x"></i>  </div>
                        <div class="text1" > social media <div style="float:right;" ><input id="opensocial" data-size="small"  class="btch"  ref="form10,social" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                       <div id="social-edit" class="block-3" style="display: none;"  >
                        <form id="form10">
                            
                        LINKED IN: <br>
                        <input value="#" type="text" id="linkedin" placeholder="" >
                        RSS: <br>
                        <input value="#" type="text" id="rss" placeholder="" >
                        FACEBOOK: <br>
                        <input value="#" type="text" id="facebook" placeholder="" >
                        GOOGLE PLUS: <br>
                        <input value="#" type="text" id="google" placeholder="" >
                        TWITTER: <br>
                        <input value="#" type="text" id="twiter" placeholder="" >
                         <div style="display:none;">
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                         </div>
                        <button type="button" class="button-blu" id="social-save" >Save</button>
                       
                        <script>
                           
                            $( "#social-save" ).click(function() {
                               
                               if ( $("#form10 input[type='checkbox']:checked").val() == 'Yes' ){
                                    $( "#social" ).empty();
                                    if($("#linkedin").val() != ''){
                                    $( "#social" ).append('               <a href="'+$("#linkedin").val()+'" id="lilinkedin"><img src="<?php echo JURI::base(); ?>images/icons/linkedin.png" alt="Smiley face"> </a> ');
                                    }
                                      if($("#rss").val() != ''){
                                    $( "#social" ).append('                <a href="'+$("#rss").val()+'" id="lirss"><img src="<?php echo JURI::base(); ?>images/icons/rss.png" alt="Smiley face"> </a> ');
                                    }
                                    if($("#facebook").val() != ''){
                                    $( "#social" ).append('               <a href="'+$("#facebook").val()+'"  id="lifacebook"><img src="<?php echo JURI::base(); ?>images/icons/facebook.png" alt="Smiley face" > </a> ');
                                    }
                                     if($("#google").val() != ''){
                                    $( "#social" ).append('                <a href="'+$("#google").val()+'" id="ligoogle"><img src="<?php echo JURI::base(); ?>images/icons/google-plus.png" alt="Smiley face" > </a>');
                                    }
                                    if($("#twiter").val() != ''){
                                    $( "#social" ).append('                 <a href="'+$("#twiter").val()+'"  id="litwitter"><img src="<?php echo JURI::base(); ?>images/icons/twitter.png" alt="Smiley face"  > </a> ');
                                   }  
                                    $('#social').show();
                               }else{
                                   
                                    $('#social').hide();
                                   
                               }
                                 ajaxsave();
                                
                            });

                        </script>
                        
                        </form>
                    </div>
                    <div id="cta-control" class="block-2" onclick="showedit('cta','seminar');window.scrollTo(0, $('#seminar').offset().top);">
                        <div class="image1" > <i class="fa fa-youtube-play fa-3x"></i>  </div>
                        <div class="text1" > cta <div style="float:right;" ><input id="opencta" data-size="small"  class="btch"  ref="form3,seminar" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                      <div id="cta-edit" class="block-3" style="display: none;" >
                        <form id="form3">
                            Select CTA:<br> 
                            <div class="list_cta">
                     <?php foreach ($this->ctainput as $r){
                            echo $r;                         
                     } ?>
                                </div>
                            <br> 
                             Or Upload A New CTA Report: <br>
                             <input id="file_name_video" name="file_name" type="file" value="" >
                             <input id="cus_or_video"  type="hidden" value="" >
                             <input id="extend_video"  type="hidden" value="" >
                             <?php echo JHtml::_( 'form.token' ); ?>
                             <br>  <br> <div class="warning" style="color: red;display: none;" > Waiting Upload CTA ... </div>
                          <br> <br>
                          
                       
                          <ul class="tabs-cta" >
                              <li class="cta-tab-1 acctive" onclick="changectatab('1');" >Text</li>
                              <li class="cta-tab-2" onclick="changectatab('2');" >Image</li>
                          </ul>
                          <div class="edit-text" >
                                
                                    Edit Title: <br>
                                  <input type="text" id="textctatit" value="" >
                                    Name Button: <br>
                                  <input type="text" id="textbutonctatit" value="Start" >
                                  <table>
                                      <tr>
                                          <td>
                                                    Color Background: <br>
                                                    <input type="text" id="cobactatit" value="" placeholder="#ffffff or red , blue" >
                                                    Button Background: <br>
                                                    <input type="text" id="btcobactatit" value="" placeholder="#ffffff or red , blue" >
                                          </td>
                                          <td>
                                                     Color Text: <br>
                                                    <input type="text" id="cotectatit" value="" placeholder="#ffffff or red , blue" >

                                                    Button Color Text: <br>
                                                    <input type="text" id="btcotectatit" value="" placeholder="#ffffff or red , blue" >
                                          </td>
                                      </tr>
                                  </table>
                       
                       
                          </div>
                          <div class="edit-image" style="display: none;" >
                             
                              Upload Image : <br>
                              <input value="" type="file"  id="upload-cta"  onchange="$('#upload-demo-cta').show();"  name="file" >
                             <br>
                              <br> <input type="button" onclick="clearctaimage();" value="Clear Image" >
                              <br> <br>
                              <div id="upload-demo-cta" style="display:none;"></div>
                          </div>
                     
                        
                          
                         <div style="display:none;">  
                         <br><br>
                        
                        Enable :
                       
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                         </div>                   
                        <button type="button" class="button-blu" id="cta-save" >Save</button>                       
                        <script>
                            $ ("#file_name_video").change(function () {
                                $(".warning").show();
                                shipOff('form3','.list_cta');   
                            });
                          
                        </script>                        
                        </form>
                    </div>
                    <div id="address-control" class="block-2" onclick="showedit('address','address-content');window.scrollTo(0, $('#address-content').offset().top);" >
                        <div class="image1" > <i class="fa fa-building fa-3x"></i>  </div>
                        <div class="text1" > My Location <div style="float:right;" ><input id="openaddress" data-size="small"  class="btch"  ref="form8,address-content" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
               
                 
                    
                    
                     
                    
                      <div id="address-edit" class="block-3" style="display: none;" >
                        <form id="form8">      
                            <fieldset class="adminform" style="border: none;">
			
			
                             <ul class="advisordetails" style="   margin: 0;    padding: 0;    border: none;"> 
                                <li>
					<label>Firm<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox" id="address_firm" value="<?php echo $this->address->firm ?>" >
				</li>	
                                <li>
					<label>Email<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox" id="address_from_email" value="<?php echo $this->address->from_email ?>" >
				</li>	
                                 <li>
					<label>URL<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox" id="address_url" value="<?php echo $this->address->url ?>">
				</li>	
                                <li>
					<label>Address<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox" id="address_address1" value="<?php echo $this->address->address1 ?>" >
				</li>				
				<li>
					<label>Address 2</label>						
					<input type="text" size="30" class="inputbox" id="address_address2" value="<?php echo $this->address->address2 ?>" >
				</li>				
				<li>
					<label>Phone</label>						
					<input type="text" size="30" class="inputbox" id="address_phone" value="<?php echo $this->address->phone ?>" >
				</li>				
				<li>
					<label>City<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox" id="address_city" value="<?php echo $this->address->city ?>" >
				</li>				
				<li>
					<label>Zip<span class="star"> *</span></label>						
					<input type="text" size="30" id="address_zip" class="inputbox" value="<?php echo $this->address->zip ?>" >
				</li>				
				<li>
					<label>State<span class="star"> *</span></label>						
					<input type="text" size="30" id="address_state" class="inputbox" value="<?php echo $this->address->state ?>" >
				</li>				
				
			</ul>
		</fieldset>  
                             <div style="display:none;">
                        <br>   
                        Enable :                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                             </div>               
                        <button type="button" class="button-blu" id="address-save" >Save</button>                       
                        <script>                        
                            $( "#address-save" ).click(function() {
                               var editor_text = $('#address-text_ifr').contents().find('body').html();                         
                               if ( $("#form8 input[type='checkbox']:checked").val() == 'Yes' ){
                                    //$("#address-content").empty().html( editor_text ); 
                                   var htm = ' <table border="0" cellspacing="0" cellpadding="0" class="mce-item-table" data-mce-selected="1"><tbody><tr valign="top"><td><p style="font-size: 12px;" data-mce-style="font-size: 12px;"><strong>'+$('#address_firm').val()+'</strong><br><span id="topaddress">'+$('#address_address1').val()+' '+$('#address_address2').val()+'<br>'+$('#address_city').val()+' '+$('#address_zip').val()+' '+$('#address_state').val()+' <br> '+$('#address_phone').val()+'<br></span> <a href="mailto:'+$('#address_from_email').val()+'" data-mce-href="mailto:'+$('#address_from_email').val()+'">'+$('#address_from_email').val()+'</a><br><a href="'+$('#address_url').val()+'" target="_blank" data-mce-href="'+$('#address_url').val()+'">'+$('#address_url').val()+'</a></p></td></tr></tbody></table> ';
                                   $("#address-content").empty().html( htm ); 
                                   $('#address').show();                                    
                               }else{                                   
                                   $('#address').hide();                                   
                               }
                                ajaxsave();
                            });

                        </script>
                        
                        </form>
                    </div>
                 
                    
                    <div id="content-control" class="block-2" onclick=" $('#popup').bPopup();//opencustom();" >
                        <div class="image1" ><i class="fa fa-file-text-o fa-3x"></i>  </div>
                        <div class="text1" > Article Manager </div>                      
                        
                    </div>
                       <div id="editcontent-edit" class="block-3"  style="display: none;"    >
                               <form id="form18">
                               
                                    <textarea id="text-content" >  </textarea>
                                    <input type="hidden" id="id_text_content" value="" />
                                
                               </form>
                               
                        
                        </div>   
                    <div id="intro-control" class="block-2" onclick="showedit('temintro');window.scrollTo(0, $('#intro').offset().top);" >
                        <div class="image1" > <i class="fa fa-text-width fa-3x"></i>    </div>
                        <div class="text1" > Template Intro  <div style="float:right;" ><input id="openintro" data-size="small"  class="btch"  ref="form14,intro" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                       <div id="temintro-edit" class="block-3"  style="display: none;"  >
                        <form id="form14">
                              Intro: <br>
                              <textarea id="temintro-edit-text" style="    width: 95%;    min-height: 74px;"></textarea>
                         <div style="display:none;">
                             <br>
                                Enable :

                                <input type="checkbox" name="position2" checked="checked" value="Yes">
                         </div>
                               

                                <button type="button" class="button-blu" id="temintro-save" >Save</button>
                               
                                <script>
                       
                            $( "#temintro-save" ).click(function() {
                               
                               if ( $("#form14 input[type='checkbox']:checked").val() == 'Yes' ){

                                val = $("#temintro-edit-text").val().replace(/\r\n|\r|\n/g,"<br />")

                                   $(".intro").empty().html( val );
                                   $('.intro').show();                                    
                               }else{                                   
                                   $('.intro').hide();
                                   
                               }
                                 ajaxsave();
                                
                            });

                             </script>
                        </form>                        
                    </div>
                    
                      <div id="weekly-control" class="block-2" onclick="showedit('weekly','weekly');window.scrollTo(0, $('#weekly').offset().top);" >
                        <div class="image1" > <i class="fa fa-list-ul fa-3x"></i>  </div>
                        <div class="text1" > weekly Update <div style="float:right;" ><input id="openweekly" data-size="small"  class="btch"  ref="form7,weekly" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                                       
                       <div id="weekly-edit" class="block-3"  style="display: none;"  >
                        <form id="form7">                          
                      <div style="display:none;">
                        <br>
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                      </div>
                        
                        
                        <button type="button" class="button-blu" id="weekly-save" >Save</button>
                       
                        <script>
                           
                            $( "#weekly-save" ).click(function() {
                               
                               if ( $("#form7 input[type='checkbox']:checked").val() == 'Yes' ){
                               
                                   $('#weekly').show();                                    
                               }else{                                   
                                   $('#weekly').hide();
                                   
                               }
                                 ajaxsave();
                                
                            });

                        </script>
                        
                        </form>
                    </div>
                    
                    
                 
                    
                     <div id="map-control" class="block-2" onclick="showedit('map','map');window.scrollTo(0, $('#map').offset().top);" >
                        <div class="image1" > <i class="fa fa-map-o fa-3x"></i>  </div>
                        <div class="text1" > map <div style="float:right;" ><input id="openmap" data-size="small"  class="btch"  ref="form11,map" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                    
                       <div id="map-edit" class="block-3" style="display: none;"  >
                        <form id="form11">
                        <br>                         
                        <div id="formuploadmap" > 
                         Address: <br>
                        <input value="" type="text" id="map-edit-img" placeholder="address, county, city, state, country" >
                        </div>
                        <br>
                        Zoom:
                        <br><br>
                        <input class="single-slider" type="hidden" value="0" />
                         <div style="display:none;">
                        <br><br>
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                         </div>
                        <button type="button" class="button-blu" id="map-save" >Save</button>
                        <script>
                           
                            $( "#map-save" ).click(function() {                               
                               if ( $("#form11 input[type='checkbox']:checked").val() == 'Yes' ){
                                 if( $("#map-edit-img").val() != '' ){
                                             var  urls = "https://maps.googleapis.com/maps/api/geocode/json?address="+$("#map-edit-img").val()+"&key=AIzaSyAcBsiCXaeb4H4wZDMNtnjSRRCKP_B2D1M";
                                                $.ajax({
                                                  url: urls,
                                                  type: "GET"           
                                                }).done(function( data ) {                                                    
                                                  var lat =   data.results[0].geometry.location.lat;
                                                  var lng =   data.results[0].geometry.location.lng;
                                                  var zoom = $(".single-slider" ).val()*2 + 13;
                                                 
                                                  $( "#map" ).empty();
                                                  $( "#map" ).append('  <img src="https://maps.googleapis.com/maps/api/staticmap?center='+lat+','+lng+'&zoom='+zoom+'&size=210x210&maptype=roadmap&markers=color:blue%7Clabel:S%7C'+lat+','+lng+'&key=AIzaSyBNJIeTGgrFxcrTgo0YKZoj7Y-T7IYapS8&zoom=10" alt="Smiley face"      width= "100%" > ');             
                                          });  
                                       }
                                   $('#map').show();
                                    ajaxsave();
                                    setTimeout(function(){ ajaxsave();},5000);
                               }else{
                                   $('#map').hide();
                               }   
                                ajaxsave();
                            });
                        </script>                        
                        </form>
                    </div>
                    
                     <div id="cloud-control" class="block-2" onclick="showedit('cloud','cloud-tag');window.scrollTo(0, $('#cloud-tag').offset().top);" >
                        <div class="image1" > <i class="fa fa-cloud fa-3x"></i>  </div>
                        <div class="text1" > tag cloud <div style="float:right;" ><input id="opencloud" data-size="small"  class="btch"  ref="form12,cloud-tag" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                    <div id="cloud-edit" class="block-3" style="display: none;">
                        <form id="form12">
                             <div style="display:none;">
                        &nbsp; Enable :
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                             </div>
                        <button type="button" class="button-blu" id="cloud-save" >Save</button>
                       
                        <script>
                           
                            $( "#cloud-save" ).click(function() {                  
                               if ( $("#form12 input[type='checkbox']:checked").val() == 'Yes' ){                                  
                                    $('#cloud-tag').show();
                               }else{                                   
                                    $('#cloud-tag').hide();                                   
                               }    
                                ajaxsave();
                            });
                            
                        </script>                        
                        </form>
                    </div>
                      <div id="meeting-control" class="block-2" onclick="showedit('meeting','schedule');window.scrollTo(0, $('#schedule').offset().top);">
                        <div class="image1" > <i class="fa fa-calendar fa-3x"></i>  </div>
                        <div class="text1" > meeting <div style="float:right;" ><input id="openmeeting" data-size="small"  class="btch"  ref="form4,schedule" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                  
                     <div id="meeting-edit" class="block-3" style="display: none;" >
                         <form id="form4">
 <div style="display:none;">
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
 </div>
                        
                        <button type="button" class="button-blu" id="meeting-save" >Save</button>
                       
                        <script>
                           
                            $( "#meeting-save" ).click(function() {                               
                               if ( $("#form4 input[type='checkbox']:checked").val() == 'Yes' ){                               
                                   $('#schedule').show();                                    
                               }else{                                   
                                   $('#schedule').hide();                                   
                               }  
                                ajaxsave();
                            });

                        </script>
                        
                        </form>
                    </div>

                   
                  
                    
                      <div id="serminar-control" class="block-2" onclick="showedit('serminar','serminar1');window.scrollTo(0, $('#serminar1').offset().top);" >
                        <div class="image1" > <i class="fa fa-users fa-3x"></i>  </div>
                        <div class="text1" > seminar links <div style="float:right;" ><input id="openserminar" data-size="small"  class="btch"  ref="form13,serminar1" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                      <div id="serminar-edit" class="block-3" style="display: none;">                        
                         <form id="form13" > 
                              <div style="display:none;">
                        Enable :                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">  
                              </div>
                        <button type="button" class="button-blu" id="serminar-save" >Save</button>                       
                        <script>                           
                            $( "#serminar-save" ).click(function() {                               
                               if ( $("#form13 input[type='checkbox']:checked").val() == 'Yes' ){  
                                   $('#serminar1').show();                                    
                               }else{                                        
                                   $('#serminar1').hide();                                   
                               }   
                                ajaxsave();
                            });
                        </script>                        
                        </form>
                    </div> 
                    <div id="financial-control" class="block-2" onclick="showedit('financial');window.scrollTo(0, $('#financial').offset().top);" >
                        <div class="image1" >  <i class="fa fa-dollar fa-3x"></i> </div>
                        <div class="text1" > financial planning <div style="float:right;" ><input id="openfinancial" data-size="small"  class="btch"  ref="form6,financial" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                       <div id="financial-edit" class="block-3"  style="display: none;"  >
                        <form id="form6">        
                             <div style="display:none;">
                        <br>
                        <br>
                        Enable :                        
                        <input type="checkbox" name="position2"  value="Yes">  
                             </div>
                        <button type="button" class="button-blu" id="financial-save" >Save</button>                       
                        <script>                           
                            $( "#financial-save" ).click(function() {
                               
                               if ( $("#form6 input[type='checkbox']:checked").val() == 'Yes' ){
                                   $('#financial').show();                                    
                               }else{                                   
                                   $('#financial').hide();                                   
                               }
                                     ajaxsave();                            
                            });

                        </script>
                        
                        </form>
                    </div>
                    <div id="investment-control" class="block-2" onclick="showedit('investment','invest');window.scrollTo(0, $('#investment-control').offset().top);" >
                        <div class="image1" > <i class="fa fa-cogs fa-3x"></i>  </div>
                        <div class="text1" > Investment Indexes  <div style="float:right;" ><input id="openinvestment" data-size="small"  class="btch"  ref="form5,invest" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                         <div id="investment-edit" class="block-3"  style="display: none;" >
                        <form id="form5">
                             <div style="display:none;">
                        <br>
                        Enable :                        
                        <input type="checkbox" name="position2"  checked="checked"  value="Yes">    
                             </div>
                        <button type="button" class="button-blu" id="investment-save" >Save</button>                       
                        <script>                           
                            $( "#investment-save" ).click(function() {
                               
                               if ( $("#form5 input[type='checkbox']:checked").val() == 'Yes' ){
                                   $('#invest').show();                                    
                               }else{                                   
                                   $('#invest').hide();
                               }
                                ajaxsave();
                            });
                        </script>                        
                        </form>
                    </div>
                  
                   
                </div>
            </div>
             
            <div class="col-2" >  
                 <?php echo $this->maildata; ?>                 
            </div>
            <div id="dasjdasj" style="display: none;">
                <div style="    display: table;    padding: 10px;
">
              <?php $this->cloud =  str_replace('<li>', '', $this->cloud);
                    $this->cloud =  str_replace('</li>', '', $this->cloud);
                    echo $this->cloud;
                    ?>
                </div>
            </div>                  
         
        </div>
       
       
            <div id="popup3_new"  style="width:600px;height:700px;overflow-y: hidden;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;     " >
              <span onclick=" $('#popup3_new').bPopup().close();" class="btclose" ><img style="width: 32px;height: 32px" src="/components/com_enewsletter/assets/images/close-window-xxl.png" /></span>
                <h2>Create Enewsletter</h2>
                <div class="createstep1">                   
                    <div id="block_template" style="    text-align: left;" >
                            <span>Name:</span>                          
                               <input  id="changetemps_popup"  name="changetemps_popup"  onkeypress="return ( event.charCode >= 48 && event.charCode <= 57) || ( event.charCode >= 97 && event.charCode <= 122) || ( event.charCode >= 65 && event.charCode <= 90 ) || (event.charCode == 32) || (event.charCode == 39) || (event.charCode == 38 )"  placeholder="Name of Enewsletter" style="border: 1px solid #ddd;padding: 7px; border-radius: 5px;margin-top:10px;    width: 290px;" />
                               <br> <div style="font-size: 12px;       margin-top: 12px;   padding-left: 55px;color: #aaa;" >*-!@#$%^*()"`~_+ is not allowed </div>    
                     </div>
                    <br>
                   <div id="block_template" style="    text-align: left;" >
                            <span>Content:</span>         <br>        
                            <div style="margin-left:67px;    margin-top: -17px;">
                            <input type="radio" name="optionf" value="1" onclick="addnameauto('Your Content');$('.tgroup3').show();$('.tgroup1, .tgroup2').hide(); $('.tgroup3 .aaa6').trigger('click');"   /> Your Content <br>
   <input type="radio" name="optionf" value="2" onclick="addnameauto('Weekly Investment Update');$('.tgroup2,.tgroup3').hide();$('.tgroup1').show(); $('.tgroup1 .aaa7').trigger('click');"  />  Weekly Investment Update <br>
   <input type="radio" name="optionf" value="3" onclick="addnameauto('Weekly Financial Planning Update');$('.tgroup2,.tgroup3').hide();$('.tgroup1').show(); $('.tgroup1 .aaa7').trigger('click');"    /> Weekly Financial Planning Update <br>
   <input type="radio" name="optionf" value="4" onclick="addnameauto('Weekly Investment-Fin Planning Update');$('.tgroup2,.tgroup3').hide();$('.tgroup1').show();  $('.tgroup1 .aaa7').trigger('click');"   /> Invesment and Planning Update <br>
   <input style="float: left;" type="radio" name="optionf" value="5" onclick="addnameauto('Your Content and Investment-Fin Planning Update');$('.tgroup2').show();$('.tgroup1,.tgroup3').hide();  $('.tgroup2 .aaa6').trigger('click');"  /> <div style="float: left;    width: 90%;    margin-left: 4px;"  > Your Content with Weekly Investment Update and Weekly Financial Planning Update </div> <br>   
                            </div>
                  </div>
                      <br><br>
                        <span  >Format:</span><br>
                 
                    <style>
                      .chothumtm:hover ,.chothumtm1:hover{
                              box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
                      }
                      .divchothumtm .img , .divchothumtm2 .img {
                           border: 2px solid #ccc;
                      }
                      .divchothumtm .active   {
                              border: 2px solid red!important;
                      }
                      .divchothumtm2 .active {
                       
                          border-bottom: 2px solid red!important;
                      }
                      .chothumtm1 {
                          border-bottom: 2px solid #ccc;
                      }
                      .chothumtm {
                           width: 23%;
                            margin-right: 1%;
                            float: left;
                            cursor: pointer;
                               height: 245px;
                            margin-top: 10px;
                      }
                    </style>
                      <div class="divchothumtm" style="width:100%">
                            <div class="tgroup1" style="display: none;" >
                                
                              <div onclick="lisennoappprove();" class="chothumtm aaa3" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;opacity: 0.5;" src="/components/com_enewsletter/assets/images/thum3.jpg" /> <br><div style="text-align: center;    margin-top: 10px;">Right Widgets</div>  </div>
                              
                              <div onclick="lisennoappprove();" class="chothumtm aaa5" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;opacity: 0.5;" src="/components/com_enewsletter/assets/images/thum2.jpg" /> <br><div style="text-align: center;    margin-top: 10px;">Left Widgets</div>  </div>                               
                               
                              <div onclick=" $('#sasasasa').val('weekly'); $('.chothumtm').removeClass('active');$('.aaa7').addClass('active');  " class="chothumtm aaa7" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="/components/com_enewsletter/assets/images/thum5.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">Single Column</div>  </div>
                                
                            </div>
                            <div class="tgroup2" style="display: none;" >
                                <div onclick="lisennoappprove();" class="chothumtm  aaa1" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;opacity: 0.5;" src="/components/com_enewsletter/assets/images/thum1.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Right Widgets </div> </div>

                               <div onclick="lisennoappprove();" class="chothumtm aaa2" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;opacity: 0.5;" src="/components/com_enewsletter/assets/images/thum2.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Left Widgets </div> </div>

                             

                               <div onclick="lisennoappprove();" class="chothumtm aaa4" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;opacity: 0.5;" src="/components/com_enewsletter/assets/images/thum4.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">3 Columns </div>  </div>
                               <div onclick=" $('#sasasasa').val('massemail'); $('.chothumtm').removeClass('active');$('.aaa6').addClass('active');  " class="chothumtm aaa6" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="/components/com_enewsletter/assets/images/thum5.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">Single Column </div>  </div>
                                
                            </div>
                          <div class="tgroup3" style="display: none;" >
                                <div onclick="lisennoappprove();opacity: 0.5;" class="chothumtm  aaa1" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;" src="/components/com_enewsletter/assets/images/thum1.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Right Widgets </div> </div>

                               <div onclick="lisennoappprove();opacity: 0.5;" class="chothumtm aaa2" style="width:23%;margin-right: 1%;float: left;cursor: pointer;background-color: #efefef;" ><img style="width: 100%;height: auto;" src="/components/com_enewsletter/assets/images/thum2.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Left Widgets </div> </div>
                               
                               <div onclick=" $('#sasasasa').val('massemail'); $('.chothumtm').removeClass('active');$('.aaa6').addClass('active');  " class="chothumtm aaa6" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="/components/com_enewsletter/assets/images/thum5.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">Single Column </div>  </div>
                               
                                
                            </div>
                        </div>
                      <div class="bfformat"></div>
                    <div style="clear: both;">
                     <button onclick="newtemp();" style="     float: right;    margin-top: 9px;    color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer;bottom: 40px;    position: absolute;    right: 40px;">Save >></button>                 
                    </div>                    
                  
                </div>
                  
                   
                        <select  style=" display:none ;   margin-top: 6px;    margin-bottom: 8px;border: 1px solid #ddd;padding: 7px; border-radius: 5px;" id="sasasasa" >
                            <?php
                                foreach ($this->tems as $r){ 
                            ?>
                            <option value="<?php echo str_replace('.html', '', $r->filename); ?>"> <?php echo $r->type ?> </option>
                            <?php 
                                }
                            ?> 
                        </select>
                     
                       
            
            
            
               
        </div>
     
        <textarea id="htmlcodeold" style="display: none;"> </textarea>
       
        
     
       
<?php die; ?>