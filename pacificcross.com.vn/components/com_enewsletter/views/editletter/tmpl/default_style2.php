<?php

defined('_JEXEC') or die;
           header('Content-Type:text/html; charset=UTF-8');
$custome_url  =  $this->custome_url;
$db = jfactory::getDBo();
$corlorctatr = "transparent"; 

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




for ($ik = 1 ; $ik <= 1000 ; $ik++){
        $vowels[] = '_'.$ik.'_';
}
     
$app = JFactory::getApplication();

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
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jscolor.min.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/style2.css" />
                
       
               
       
<script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/url2img.js"></script>

<script>
       <?php   if ($this->optionf == 3 || $this->optionf == 4 || $this->optionf == 5 ) { ?>         
           $( document ).ready(function() {
                   if(!$("#settingintro")[0]){
                          $("#intro").hide();
                          $('<div id="settingintro" style="padding-left: 10px;" ><?php echo $db->escape($this->allsetting->weekly_update_intro); ?></div><br>').insertBefore($( "#cta" ));
                          
                   }
                   if(!$("#settingdeclo")[0]){
                          $('<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="transparent" class="mceItemTable ui-sortable-handle" style="width: 830px;"><tbody><tr><td style="padding: 1px;">  <table width="100%" class="mceItemTable"><tbody><tr style="background-color: transparent"><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; " valign="top"></td>  </tr></tbody></table></td>  </tr></tbody></table><div id="settingdeclo" ><?php echo $db->escape($this->allsetting->weekly_update_newsletter); ?></div>').insertAfter($( "#cta" ));
                   }
                   
           });
        <?php } ?>
       <?php if ( $this->optionf == 1 || $this->optionf == 5 ) { 
       
            if($this->idt == "enewsletter_threecol") {
//                $col3 = '<strong id="titlecust" style="font-size: 20px;" data-mce-style="font-size: 20px;">Title Enter Here....</strong> <br>Content  Enter Here.... </span>   ';
//                
//                  $acol =' <table style="float:left;margin-right:2%"  id="articles" width="48%" border="0" cellspacing="0" cellpadding="0" bgcolor="transparent" class="mceItemTable"><tbody><tr id-cont="article_content_12" class="edit_content" id="article_12" style="background:'.$corlorctatr.'" data-mce-style="background-color:'.$corlorctatr.'"><td style="padding: 25px;" data-mce-style="padding: 25px;">  <table width="100%" class="mceItemTable"><tbody><tr style="background-color: '.$corlorctatr.'"><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; " valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; "><br> <div noconfig="1" id="article_content_12" style="font-family: Arial; font-size: medium; max-width: 342px;    word-wrap: break-word;" data-mce-style="font-family: Arial; font-size: medium;">'.$col3.'</div>   </td>  </tr>  </tbody> </table>  </td>  </tr>  </tbody></table>  <table  style="float:left;"  id="articles" width="48%" border="0" cellspacing="0" cellpadding="0" bgcolor="transparent" class="mceItemTable"><tbody><tr id-cont="article_content_13" class="edit_content" id="article_13" style="background:'.$corlorctatr.'" data-mce-style="background-color:'.$corlorctatr.'"><td style="padding: 25px;" data-mce-style="padding: 25px;">  <table width="100%" class="mceItemTable"><tbody><tr style="background-color: '.$corlorctatr.'"><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; "><br> <div noconfig="1" id="article_content_13" style="font-family: Arial; font-size: medium;    max-width: 342px;    word-wrap: break-word;" data-mce-style="font-family: Arial; font-size: medium;">'.$col3.'</div>   </td>  </tr>  </tbody> </table>  </td>  </tr>  </tbody></table> ';
                                    
                  
                $col3 ='<div style="   width: 100%;    word-wrap: break-word;    display: inline-block;" ><strong style="font-size: 20px;" data-mce-style="font-size: 20px;">  </strong>    <br><span style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> <strong id="titlecust"  style="font-size: 20px;" data-mce-style="font-size: 20px;">Title Enter Here....</strong> <br>Content Enter Here.... </span> </div> ';
                 
                $acol =' <table  id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="transparent" class="mceItemTable"><tbody><tr id-cont="article_content_7" class="edit_content" id="article_7" style="background:'.$corlorctatr.'" data-mce-style="background-color:'.$corlorctatr.'"><td style="padding: 5px;" data-mce-style="padding: 25px;">  <table width="100%" class="mceItemTable"><tbody><tr style="background-color: '.$corlorctatr.'"><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; " valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px;"><br> <div noconfig="1" id="article_content_7" style="font-family: Arial; font-size: medium;width: 800px;    word-wrap: break-word;" data-mce-style="font-family: Arial; font-size: medium;">'.$col3.'</div>   </td>  </tr>  </tbody> </table>  </td>  </tr>  </tbody></table> ';
                  
            }else {
                 $col3 ='<div style="   width: 100%;    word-wrap: break-word;    display: inline-block;" ><strong style="font-size: 20px;" data-mce-style="font-size: 20px;">  </strong>    <br><span style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> <strong id="titlecust"  style="font-size: 20px;" data-mce-style="font-size: 20px;">Title Enter Here....</strong> <br>Content Enter Here.... </span> </div> ';
                 
                $acol =' <table  id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="transparent" class="mceItemTable"><tbody><tr id-cont="article_content_7" class="edit_content" id="article_7" style="background:'.$corlorctatr.'" data-mce-style="background-color:'.$corlorctatr.'"><td style="padding: 25px;" data-mce-style="padding: 25px;">  <table width="100%" class="mceItemTable"><tbody><tr style="background-color: '.$corlorctatr.'"><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; " valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px;"><br> <div noconfig="1" id="article_content_7" style="font-family: Arial; font-size: medium;width: 700px;    word-wrap: break-word;" data-mce-style="font-family: Arial; font-size: medium;">'.$col3.'</div>   </td>  </tr>  </tbody> </table>  </td>  </tr>  </tbody></table> ';
               
           }
       
       ?>
            $( document ).ready(function() {
                if ($("#cta img:first-child").attr('src') == 'http://rimbatest1.advisorproducts.com/images/contenttest.png' ) {
                    $("#imgedef").remove();
                  <?php   if ($this->optionf == 1) { ?>
                    $("#cta").empty();
                  <?php  $intor = ' <strong class="intro" style="border: none; background: rgb(236, 235, 224);"> Template Intro Here.. </strong>'; } ?>
                      
                        if (!$("#article_content_7")[0] && !$("#article_content_12")[0] && !$("#article_content_13")[0] ){
                            $("#cta").before('<?php echo $acol; ?>');
                            
                            <?php   if ($this->optionf == 1 && ( $this->idt == 'enewsletter' || $this->idt == 'enewsletter_site2' || $this->idt == 'enewsletter_threecol'  ) ) { ?>
                            if(!$("#settingdeclo")[0]){
                            $("#main-page-html").after('<div id="settingdeclo"  ><?php echo $db->escape($this->allsetting->weekly_update_newsletter); ?></div>');
                            }    
                            <?php } else { ?>
                                if(!$("#settingdeclo")[0]){
                                    $("#cta").after('<div id="settingdeclo" ><?php echo $db->escape($this->allsetting->weekly_update_newsletter); ?></div>');
                                }
                            <?php } ?>
                                                               
                        }                     
                     }                     
                   
               });
              
     <?php } ?>
    
     <?php if ( $this->optionf == 6 ) { ?>
         
         $( document ).ready(function() {
              $("#imgedef").remove();
         });
        <?php  }     ?>
</script>
  <div class="seving" style="color: red;display: none;        margin-top: -20px;    right: 40px;    font-size: 18px;    position: fixed;    z-index: 10;" >  <img src="<?php echo juri::base();?>components/com_enewsletter/assets/images/ajax-loader.gif" style="width:70px;   " />  </div>
  
  <div class="sed" style="color: blue;display: none;        width: 72px;  height: 70px;   background-color: #fff;  margin-top: -20px;    right: 40px;    font-size: 18px;    position: fixed;    z-index: 11; "> <img src="<?php echo juri::base();?>components/com_enewsletter/assets/images/done.png" style="width:70px;   " /> </div>
  
        <style>
           
           
              <?php if ( $this->optionf == 1 || $this->optionf == 2 || $this->optionf == 3 || $this->optionf == 4  ) {
        echo "#content-control{ display:none; }";
    } ?>
           
           <?php if ($this->idt == 'massemail' || $this->idt == 'weekly'  ) { echo "#advions-control , #cta-control , #poll-control, #intro-control , #weekly-control , #map-control , #cloud-control , #meeting-control ,#serminar-control  { display:none; } "; }?>
           .croppie-container {
                padding: 0;margin-left: -35px;
           }
           li{
                   list-style: none;
           }      
           .fa-3x{
                   color: #777;
                   border: 1px solid #ccc;
                   font-size: 26px!important;
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
            .ui-tabs-panel {
                 font-family: 'Arial'!important;
            }
            .ui-tabs-panel a{
                 text-decoration: none;
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
                    margin-top: 40px;
              }
              #weekly:hover,   #invest:hover, #address:hover, #logomail img:hover, .module:hover{
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
              .addlabel {
                  width: 24%;
                  display: block;
                  float: left;
                  margin-top: 24px;
                  text-transform: uppercase;
                  font-size: 12px;
              }
              
              .ui-widget-header {
                  border: none!important;
                  background: none!important;
              }
              .ui-tabs .ui-tabs-nav li {
                  border: none!important;
                  border-bottom:1px solid #bbb!important;
                  text-align: center;
                  background: none;
              }
              .ui-tabs .ui-tabs-nav li a{
                  width:92%;
              }
              .ui-tabs .ui-tabs-nav li.ui-tabs-active {
                  border-bottom:2px solid #019386!important;
              }
              a:focus {
                  outline: none;
              }
              .dataTables_filter {
                  position: absolute;
                  top: -128px;
                  right: 150px;
              }
              .dataTables_filter input[type=text] {
                  width: 50%;    padding: 3px;    margin-left: 18px;    border-color: #999;
              }
              .dataTables_length {
                  width: 40%;
                  float: right;
                  text-align: right;
                  margin-bottom: 14px;
                }
                input[type="checkbox"] {
                    
                }
                .upload_error {
                    color: red;
                }
                
                #form12 a.tag-cloud {
                    margin-left: 35px;
                    margin-top: -20px;
                }
                .ctaicon_mp4 , .ctaicon_pdf , .ctaicon_mp3 , .ctaicon_docx , .ctaicon_xlsx , .ctaicon_flv ,.ctaicon_pptx  {
                    padding-left: 0px;
                    background-position: 0px;
                    background-repeat: no-repeat;
                    background-size: 19px;
                        display: inline;
                }
                .ctaicon_mp4 , .ctaicon_flv{
                      background-image: none; 
                }
                 .ctaicon_docx {
                       background-image: none; 
                }
                 .ctaicon_xlsx {
                       background-image: none; 
                }
                 .ctaicon_pdf  {
                      background-image: none; 
                }
                .ctaicon_pptx  {
                       background-image: none; 
                }
                #address {
                           min-width: 100%;
                }
        </style>
         <script>
                $(function() {
                  $( "#tabs" ).tabs();
                });

                $(document).ready(function() {
                  
                        document.title = 'Enewsletter';
		// set cookie when we change tab 
		$('.alltab').click(function(){
		//	setCookie('tabSelected',this.id);
		});
    
                $('.checkall').on('click',function(){
                  if(this.checked == true)  {
                      $('#s_'+this.value).prop('checked',true);
                   }else{
                      $('#s_'+this.value).prop('checked',false);
                   }
                  });                            
                } );

  </script>
        <script type="text/javascript">
        $(document).ready(function(){
             $(function() {
                $( "#tabs" ).tabs();
                $( "#tabs1" ).tabs();
              });
           
           sort__table();
            $('.pane-img img').click(function(){
               window.location.href = "<?php echo juri::base(); ?>";
           });
        });
    </script>
   
   <script>
    function sort__table(){
        oTable = $('#articletable').dataTable( {
                            
                    "aLengthMenu": [[20,50,200,-1], [20,50,200,"All"]],
                    'iDisplayLength': 20,
                    "aoColumnDefs": [
                            { "bSortable": false, "aTargets": [ 0] }
                    ],
                              "oLanguage": {
                              "sSearch": "Search Articles:"
                            }
                    }                     
                              );
          oTable.fnSort( [ [4,'desc'] ] );          
		// Assign datatable to articles list
		
		oTable2 = $('#articletable2').dataTable( {
                     
			"aLengthMenu": [[20,50,200,-1], [20,50,200,"All"]],
      		'iDisplayLength': 20,
      		"aoColumnDefs": [
          		{ "bSortable": false, "aTargets": [ 0] }
        	],
			"oLanguage": {
			  "sSearch": "Search Articles:"
			}
		} );
	 oTable2.fnSort( [ [4,'desc'] ] );	
        
        
         oTable3 = $('#articletable6').dataTable( {                            
                            "aLengthMenu": [[20,50,200,-1], [20,50,200,"All"]],
                            'iDisplayLength': 20,
                            "aoColumnDefs": [
                            { "bSortable": false, "aTargets": [ 0] }
                            ],
                            "oLanguage": {
                              "sSearch": "Search Articles:"
                            }
                    }                     
                              );
          oTable3.fnSort( [ [4,'desc'] ] );
          
		oTable4 = $('#articletable7').dataTable( {                     
                            "aLengthMenu": [[20,50,200,-1], [20,50,200,"All"]],
                            'iDisplayLength': 20,
                            "aoColumnDefs": [
                            { "bSortable": false, "aTargets": [ 0] }
                            ],
                            "oLanguage": {
                              "sSearch": "Search Articles:"
                            }
		} );
	oTable4.fnSort( [ [4,'desc'] ] );	
    }                
    function demoUpload(a) {
		var $uploadCrop;

		function readFile(input) {
 		if (input.files && input.files[0]) {
	            var reader = new FileReader();	            
	            reader.onload = function (e) {
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	});
	            	$('.upload-demo').addClass('ready');
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
                            alert ("Sorry - you're browser doesn't support the FileReader API");                            
		    }
		}
                
		$uploadCrop = $('#upload-demo').croppie({
			viewport: {
				width: 160,
				height: 200,
				type: 'square'
			},
			boundary: {
				width: 200,
				height: 240
			}
		});

		$('#upload').on('change', function () { readFile(this); });
                 if (a != 1){
                    $('#advions-save').on('click', function (ev) {

                          if ( $("#form1 input[type='checkbox']:checked").val() == 'Yes' ){
                               $('#advions-content').show();  
                                    if ($("#upload").val() != '' ){
                                        $uploadCrop.croppie('result', {
                                        type: 'canvas',
                                        size: 'original'
                                        }).then(function (resp) {                              
                                            $( '#advions-content' ).empty();                                              
                                            $.ajax({
                                              url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savepng",
                                              type: "POST",
                                              data: "imgcode="+resp                            
                                            }).done(function( data ) {                                        
                                              //  alert(); 
                                                 if(!$("#form1 input[type='radio']:checked").val()){
                                                        $( '#advions-content' ).append('  <a href="#" ><img src="'+data+'"  width= "187" style="width:187px;"  > </a>'); 
                                                 }else{
                                                        $( '#advions-content' ).append('  <a member="'+$("#form1 input[name='who']:checked").val()+'" href="<?php echo juri::base(); ?>'+$("#form1 input[name='who']:checked").val()+'" ><img src="'+data+'"  width= "100%" width= "187" style="width:187px;" > </a>');                                                  
                                                 }
                                                 ajaxsave();  setTimeout(function(){ ajaxsave();},5000);
                                            });  
                                        });
                                    }else {
                                        $( '#advions-content a' ).attr('href','<?php echo juri::base(); ?>'+$("#form1 input[name='who']:checked").val() );
                                        $( '#advions-content a' ).attr('member',$("#form1 input[name='who']:checked").val() );
                                         ajaxsave(); 
                                    }


                               }else{                                   
                                    $('#advions-content').hide();
                                     ajaxsave(); 
                               }

                    });
                }
	}
    
    
    
       function demoUploadaSerminar(a) {
		var $uploadCrop;

		function readFile(input) {
 		if (input.files && input.files[0]) {
	            var reader = new FileReader();	            
	            reader.onload = function (e) {
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	});
	            	$('.upload-demo-serminar').addClass('ready');
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
                            alert ("Sorry - you're browser doesn't support the FileReader API");                            
		    }
		}

		$uploadCrop = $('#upload-demo-serminar').croppie({
			viewport: {
				width: 160,
				height: 200,
				type: 'square'
			},
			boundary: {
				width: 200,
				height: 240
			}
		});

		$('#upload-serminar').on('change', function () { readFile(this); });
                if (a != 1){
		$('#serminar-save').on('click', function (ev) {
                      if ( $("#form13 input[type='checkbox']:checked").val() == 'Yes' ){
                                    $('#serminar1').show();
                                    if($('#upload-serminar').val() != ''){
                                    $uploadCrop.croppie('result', {
                                    type: 'canvas',
                                    size: 'original'
                                    }).then(function (resp) {   
                                        
                                        $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savepng",
                                          type: "POST",
                                          data: "imgcode="+resp                            
                                        }).done(function( data ) { 
                                            $( '#aserminar1' ).empty();    
                                            $( '#aserminar1' ).append('  <img src="'+data+'"  width= "70%"  > <h3 class="poll-content-title2" style="margin-top: 17px; margin-bottom: 17px; font-size: 20px;"> '+$('#serminar_title').val()+' </h3>'); 
                                            ajaxsave();  setTimeout(function(){ ajaxsave();},5000);
                                        });     
                                    });              
                                    $( '#aserminar1').attr('href',$('#serminar_link').val());
                                       
                                    }else {                                           
                                        $( '#aserminar1 h3' ).text($('#serminar_title').val());                                        
                                        $( '#aserminar1').attr('href',$('#serminar_link').val());
                                        ajaxsave();
                                    }
                               }else{                                   
                                    $('#serminar1').hide();
                                    ajaxsave(); 
                      }                               
		});
                }
	}
        
        
          function demoUploadcta() {
		var $uploadCrop;
		function readFile(input) {
 		if (input.files && input.files[0]) {
	            var reader = new FileReader();	            
	            reader.onload = function (e) {
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	});
	            	$('.upload-demo-cta').addClass('ready');
	            }	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
                            alert ("Sorry - you're browser doesn't support the FileReader API");                            
		    }
		}
		$uploadCrop = $('#upload-demo-cta').croppie({
			viewport: {
				width: 160,
				height: 200,
				type: 'square'
			},
			boundary: {
				width: 200,
				height: 240
			}
		});

		$('#upload-cta').on('change', function () { readFile(this); });
                $('#cta-save').on('click', function (ev) {
                         if ( $("#form3 input[type='checkbox']:checked").val() == 'Yes' ){                               
                               $('#seminar').show(); 

                               var id = $("#form3 input[type='radio']:checked").val();
                               var type = $("#cus_or_video").val();
                               var extend = $("#extend_video").val();
                               var link = $( ".seminar-content-text2 a" ).attr('href');   
                               if (typeof link === "undefined") {
                                    var link = $( "#image-cta" ).attr('href');   
                               }

                                        $( ".seminar-content-title2" ).empty();
                                        $( ".seminar-content-title2" ).append($("#textctatit").val());
                                        $( ".seminar-content-text2" ).empty();                                     
                                        $('#seminar').css('padding','25px 4px');
                                        $('.seminar-content-title2').css('margin-bottom','25px');

                                        if ( $("#btcobactatit").val() != '' ){
                                            var btbaco ='#'+$("#btcobactatit").val();
                                        }else {
                                            var btbaco = '#FF9b0b;';
                                        }

                                         if ( $("#btcotectatit").val() != '' ){
                                            var btco = '#'+$("#btcotectatit").val();
                                        }else {
                                            var btco = '#FFFFFF;';
                                        }
                                    if (type == '') {
                                             $( ".seminar-content-text2" ).append('\
                                                  <table  style="     margin: 0 auto;  background-color: '+btbaco+'; " cellspacing="10"  ><tr><td>  <a style="       font-size: 12px;       background-color: '+btbaco+'; color: '+btco+'!important; color: '+btco+';    text-decoration: none;  margin: 0px;    margin-top: 20px;     padding-left: 10px;    padding-right: 10px;" target="_blank" href="'+link+'" class="button-or"> '+$("#textbutonctatit").val()+'</a></td></tr></table>');
                                    }else{    
                                   $( ".seminar-content-text2" ).append('\
                                        <table style="    margin: 0 auto; background-color: '+btbaco+';   " cellspacing="10"  ><tr><td> <a style="  background-color: '+btbaco+';  color: '+btco+'!important;  color: '+btco+';  font-size: 12px;       text-decoration: none;    padding-left: 10px;    margin: 0px;    margin-top: 20px;  padding-right: 10px;" target="_blank" href="<?php echo JURI::base(); ?>index.php?option=com_cta&view=form&'+type+'='+id+extend+'" class="button-or"> '+$("#textbutonctatit").val()+'</a></td></tr></table> ');
                                    }
                                                if ( $("#cobactatit").val() != '' ){
                                                      $('#seminar').css("background", '#'+$("#cobactatit").val());
                                                 }
                                                  if ($("#cotectatit").val() != ''  ){
                                                      $('#seminar').css("color", '#'+$("#cotectatit").val());
                                                 }

                                  if ( $('#upload-cta').val()!= '' && $(".cta-tab-2").hasClass('acctive')){

                                      $uploadCrop.croppie('result', {
                                            type: 'canvas',
                                            size: 'original'
                                            }).then(function (resp) {                              

                                                       $.ajax({
                                                         url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savepng",
                                                         type: "POST",
                                                         data: "imgcode="+resp                            
                                                       }).done(function( data ) {   

                                                                           $( "#image-cta" ).remove();

                                                  if (type == '') {                       
                                                        $('.seminar-content-title2').before('\
                                                  <a id="image-cta" target="_blank" href="'+link+'" > '+'  <img src="'+data+'"  width= "100%"  > '+' <br></a>');   
                                                    }else{
                                                          $('.seminar-content-title2').before('\
                                             <a id="image-cta" target="_blank" href="<?php echo JURI::base(); ?>index.php?option=com_cta&view=form&'+type+'='+id+extend+'" > '+'  <img src="'+data+'"  width= "100%"  > '+' <br> </a>');   
                                                    }

                                                 }); 
                                            });
                                     }     
                                     
                                     if ($("#cus_or_video").val() == 'cusitem_id[]'){
                                           // update rename                                          
                                           $.ajax({
                                                url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=renamecta",
                                                type: "POST",
                                                data: "id="+$('input[name=nidcta]:checked').val()+"&text="+$("#textctatit").val()                            
                                           }).done(function( data ) {   
                                                $(".nameof"+$('input[name=nidcta]:checked').val()).text($("#textctatit").val());
                                                $("#idccta"+$('input[name=nidcta]:checked').val()).attr('onclick',"$('#extend_video').val('');$('#cus_or_video').val('cusitem_id[]');$('#textctatit').val('"+$("#textctatit").val()+"');");
                                           });                                          
                                     }                                     
                               }else{                                   
                                   $('#seminar').hide();                                   
                               }        
                                ajaxsave();  setTimeout(function(){ ajaxsave();},5000);
		});
		
	}
        
        
        function clearctaimage(){
        
               $( "#image-cta" ).remove();
               $("#upload-demo-cta").empty();
               $("#upload-cta").val('');
               $("#upload-demo-cta").hide();
               demoUploadcta();
               ajaxsave();   setTimeout(function(){ ajaxsave();},5000);
        }
        
        function clearadvisor(){
        
               $( "#advions-content img" ).remove();
               $("#upload-demo").empty();
               $("#upload").val('');
               $("#upload-demo").hide();
               demoUpload(1);
               ajaxsave();   setTimeout(function(){ ajaxsave();},5000);
        }
        
        function clearctaimagewebmina(){
               $( "#aserminar1 img" ).remove();
               $("#upload-demo-serminar").empty();
               $("#upload-serminar").val('');
               $("#upload-demo-serminar").hide();
               demoUploadaSerminar(1);
               ajaxsave();   setTimeout(function(){ ajaxsave();},5000);
        }
        
        function demoUploadlogo() {
        
		var $uploadCrop;
		function readFile(input) {
 		if (input.files && input.files[0]) {
	            var reader = new FileReader();	            
	            reader.onload = function (e) {
	            	$uploadCrop.croppie('bind', {
	            		url: e.target.result
	            	});
	            	$('.upload-demo-logo').addClass('ready');
	            }
	            
	            reader.readAsDataURL(input.files[0]);
	        }
	        else {
                            alert ("Sorry - you're browser doesn't support the FileReader API"); 
		    }
		}

		$uploadCrop = $('#upload-demo-logo').croppie({
			viewport: {
				width: 280,
				height: 75,
				type: 'square'
			},
			boundary: {
				width: 280,
				height: 280
			}
		});

		$('#file2').on('change', function () { readFile(this); });                
		$('#logo-save').on('click', function (ev) {
                     if ( $("#form9 input[type='checkbox']:checked").val() == 'Yes' ){
                                    $uploadCrop.croppie('result', {
                                           type: 'canvas',
                                           size: 'original'
                                   }).then(function (resp) {                              
                                             $( '#logomail' ).empty();
                                             
                                              $.ajax({
                                                url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savepng",
                                                type: "POST",
                                                data: "imgcode="+resp                            
                                              }).done(function( data ) {                                        
                                           $( '#logomail' ).append('  <img src="'+data+'" width="300"   width= "90%" style="max-width:300px;    min-width: 260px;" > ');   
                                              ajaxsave(); setTimeout(function(){ ajaxsave();},5000);
                                        }); 
                                   });
                                   $('#logomail').show();                                 
                               }else{                                   
                                   $('#logomail').hide();                                   
                               }			
		});
	}


  
  
        function shipOff(a,b,c) {
            var bar = $('.bar');
            var percent = $('.percent');
           
            var fd = new FormData(document.getElementById(a));
            fd.append("label", "WEBUPLOAD");
            $.ajax({
              url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=uploadvideocta",
              type: "POST",
              data: fd,
              enctype: 'multipart/form-data',
              processData: false, 
              contentType: false,
              beforeSend: function() {  
                    bar.width('0%');
                    percent.html('0');
              },
              xhr: function(){
                //upload Progress
                 $(".progress").show();
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        if (percent >= parseInt($('.percent').text()) ){
                            //update progressbar
                            $('.bar').width(percent+"%");                        
                            $('.percent').text(percent);
                        }
                        
                    }, true);
                }
                return xhr;
                }
            }).done(function( data ) {
                
                 $( b ).append(data);
                 $(".progress").hide();
                 $('.warning').hide();
                 $('.upload_error').fadeOut(25000 ,function() {
                     $('.upload_error').remove();
                 });
            });
            return false;
            
  
}
  </script>
  
  <script type="text/javascript">
                    $( document ).ready(function() {
                               
                            demoUpload();
                            demoUploadcta();
                            demoUploadlogo();
                            demoUploadaSerminar();
                            dofirst();
                            $(function(argument) {
                                $('.btch').bootstrapSwitch();
                            });
                            $('#address-text').html( $('#address-content').html());  
                            
                            //$('#form12').prepend($('#dasjdasj').html());
                            
                            $('#editor2').html( $('#cloud-tag').html());
                            $('#weekly-text').html( $('#aweekly').html());
                            $('#financial-text').html( $('#afinancial').html());
                            $('#investment-text').html( $('#ainvest').html());
                            if($('#ainvest').attr('href') == '#' ||  (  $('#ainvest').attr('href').indexOf('rimbatest1') > 0 ) ){
                              $('#ainvest').attr('href' , '<?php echo JURI::base(); ?>index.php?option=com_apicontent&view=fnclist')  
                            }
                            if($('#afinancial').attr('href') == '#' ||  (  $('#afinancial').attr('href').indexOf('rimbatest1') > 0 ) ){
                              $('#afinancial').attr('href' , '<?php echo JURI::base(); ?>index.php?option=com_apicontent&view=fbclist')  
                            }
                          
                            if($('#aweekly').attr('href') == '#' ||  (  $('#aweekly').attr('href').indexOf('rimbatest1') > 0 ) ){
                              $('#aweekly').attr('href' , '<?php echo JURI::base(); ?>index.php?option=com_apicontent&view=weeklyupdate')  
                            }
                            if($('#aschedule').attr('href') == '#' ||  (  $('#aschedule').attr('href').indexOf('rimbatest1') > 0 ) ){
                              $('#aschedule').attr('href' , '<?php echo JURI::base(); ?>index.php?option=com_booknow&view=booknow')  
                            }
                              
                            
                            $('#investment-edit-link').val( $('#ainvest').attr('href') );
                            $('#financial-edit-link').val( $('#afinancial').attr('href') );
                            $('#weekly-edit-link').val( $('#aweekly').attr('href') );
                            $('#meeting-edit-link').val( $('#aschedule').attr('href') );
                            
                            $("#serminar_title").val($.trim($('#serminar1 #aserminar1 h3').html()));
                          
                            if ($(".intro")[0]){                              
                                $('#temintro-edit-text').val( $(".intro").html().replace(/<br\s?\/?>/g,"\n")); 
                            }
                            $('#htmlcodeold').html( $('#isdatahtml').html()); 
                            $('.allmodule h3').css('margin-top','17px');
                            $('.allmodule h3').css('margin-bottom','17px');
                            $('.allmodule h3').css('font-size','20px');
                          
                            $('#textctatit').val($.trim($('h3.seminar-content-title2').html())); 
                             
                              
                            <?php if( JRequest::getVar('new_tem') == 1 ){ ?>
                                       // tag
                                       $('#cloud-tag').empty().html($('#dasjdasj').html());  
                                       $( "#aserminar1 img" ).css("margin-top","7px");
                                       // color 
                                       
                                           
                                       
                                       <?php if($this->com_params->get('backgc') != ''){ ?>
                                       $('#backgc').val('#<?php echo $this->com_params->get('backgc') ?>');
                                       $('#maintextgc').val('#<?php echo $this->com_params->get('maintextgc') ?>');
                                       $('#backbargc').val('#<?php echo $this->com_params->get('backbargc') ?>');
                                       $('#linktextgc').val('#<?php echo $this->com_params->get('linktextgc') ?>');
                                      
                                       $('#intro , .intro').css('background','#<?php echo $this->com_params->get('backbargc') ?>');
                                       <?php if($this->com_params->get('uploadadvisor') != ''){ ?>
                                       // fix advisor
                                       $("#advions-content img").attr('src','<?php echo juri::base(); ?>media/com_enewsletter/images/<?php echo $this->com_params->get('uploadadvisor'); ?>');
                                       <?php }  ?>
                                       // logo
                                       <?php if($this->allsetting->logo != ''){ ?>
                                       $("#imgslogo").attr('src','<?php echo juri::base(); ?>media/com_enewsletter/logo/<?php echo $this->allsetting->logo;?>');
                                       $("#imgslogo").css('width','90%');
                                       $("#imgslogo").css('max-width','350px');
                                       $("#imgslogo").css('min-width','260px'); 
                                       <?php }  ?>
                                       // social
                                       <?php $social_links = json_decode($this->allsetting->social_links); ?>
                                             <?php if ($social_links){ ?>  
                                       $("#linkedin").val('<?php echo $social_links->linkedin;?>');
                                       $("#rss").val('<?php echo $social_links->rss;?>');
                                       $("#facebook").val('<?php echo $social_links->facebook;?>');
                                       $("#google").val('<?php echo $social_links->googleplus;?>');
                                       $("#twiter").val('<?php echo $social_links->twitter;?>');
                                       $("#social-save").trigger( "click" );                                       
                                        <?php } } ?>                                       
                                       // fix poll
                                       $(".idpoll<?php echo $this->com_params->get('poll','0') ?>").trigger( "click" );
                                       $( "#poll-save" ).trigger( "click" );
                                       
                                      
                                       // cta
                                       <?php if(    $this->com_params->get('ctavideo') != ''  ){ ?>                                          
                                                for (var i = 0 ; i < 100 ; i++ ){
                                                    if( parseInt($("#idcta"+i).val()) == <?php echo $this->com_params->get('ctavideo'); ?> ){
                                                         $("#idcta"+i).trigger( "click" );
                                                    }                                               
                                                }
                                                <?php if ($this->com_params->get('textctatit') != ''){ ?>
                                                $("#textctatit").val('<?php echo $this->com_params->get('textctatit'); ?>');
                                                 <?php } ?>
                                                $("#cobactatit").val('<?php echo $this->com_params->get('cobactatit'); ?>');
                                                $("#cotectatit").val('<?php echo $this->com_params->get('cotectatit'); ?>');
                                                $("#btcobactatit").val('<?php echo $this->com_params->get('btcobactatit'); ?>');
                                                $("#btcotectatit").val('<?php echo $this->com_params->get('btcotectatit'); ?>');
                                                $("#textbutonctatit").val('<?php echo $this->com_params->get('textbutonctatit','Start'); ?>');
                                                <?php if($this->com_params->get('uploadctaimage') != ''){ ?>
                                                $('.seminar-content-title2').before('<img id="image-cta" src="<?php echo juri::base(); ?>media/com_enewsletter/upload/<?php echo $this->com_params->get('uploadctaimage'); ?>"  width= "100%"  >'); 
                                                <?php } ?>    
                                       <?php }else if(    $this->com_params->get('ctacustom') != ''  ){ ?>
                                                for (var i = 0 ; i < 100 ; i++ ){
                                                   if( parseInt($("#idccta"+i).val()) == <?php echo $this->com_params->get('ctacustom'); ?> ){
                                                        $("#idccta"+i).trigger( "click" );
                                                   }                                               
                                               }
                                               <?php if ($this->com_params->get('textctatit') != ''){ ?>
                                               $("#textctatit").val('<?php echo $this->com_params->get('textctatit'); ?>');
                                                <?php } ?>
                                               $("#cobactatit").val('<?php echo $this->com_params->get('cobactatit'); ?>');
                                               $("#cotectatit").val('<?php echo $this->com_params->get('cotectatit'); ?>');
                                               $("#btcobactatit").val('<?php echo $this->com_params->get('btcobactatit'); ?>');
                                               $("#btcotectatit").val('<?php echo $this->com_params->get('btcotectatit'); ?>');
                                               
                                               $("#textbutonctatit").val('<?php echo $this->com_params->get('textbutonctatit','Start'); ?>');
                                                <?php if($this->com_params->get('uploadctaimage') != ''){ ?>
                                               $('.seminar-content-title2').before('<img id="image-cta" src="<?php echo juri::base(); ?>media/com_enewsletter/upload/<?php echo $this->com_params->get('uploadctaimage'); ?>"  width= "100%"  >'); 
                                                <?php } ?>    
                                       <?php } else { ?>    
                                                  $("#idcta0").trigger( "click" );
                                       <?php } ?>
                                    
                                       $("#cta-save").trigger( "click" );   
                                       // location
                                       <?php  if($this->com_params->get('useaddress2') == 1){ ?>                                           
                                                $("#useaddress2").prop('checked',true);
                                       <?php }else{ ?>
                                                $("#useaddress2").prop('checked',false);                                                
                                       <?php } ?>   
                                       $("#address-save").trigger( "click" );   
                                        
                                       <?php if($this->com_params->get('temintro') != ''){ ?>    
                                       // template intro
                                       $(".intro").text('<?php echo $this->com_params->get('temintro'); ?>');
                                       <?php } ?>
                                       <?php if($this->com_params->get('mapaddress') != ''){ ?>    
                                       // map
                                       $('#map-edit-img').val("<?php echo $this->com_params->get('mapaddress'); ?>");
                                       $(".single-slider").val('<?php echo $this->com_params->get('mapzoom'); ?>');
                                       
                                       <?php }else{ ?>
                                           
                                       $('#map-edit-img').val("<?php echo str_replace(array("\n", "\r"), '', $this->caddress) ?>");
                                       $(".single-slider").val('1');
                                       
                                       <?php } ?>    
                                       $( "#map-save" ).trigger( "click" );
                                       <?php if($this->com_params->get('cloudcheck') != ''){ ?>    
                                       // cloud
                                       $(".tag-cloud").hide();
                                       $(".tag-cloud").each(function (){
                                       
                                          <?php foreach ($this->com_params->get('cloudcheck') as $cl){ ?>
                                                if(  $(this).text() == '<?php echo $cl; ?>'  ){
                                                      $(this).show();    
                                                }
                                          <?php } ?>
                                       });
                                       <?php } ?>        
                                       <?php if($this->com_params->get('backgc') != ''){ ?>
                                       setTimeout(function(){  $("#global-save").trigger( "click" ); },7500);
                                       <?php } ?>       
                                      
                               <?php   
                               }
                             ?>     
                        
                          $('.col-2 a').css('text-decoration','none');                           
                          $('#serminar_link').val($('#aserminar1').attr('href'));                          
                          $('.edit_content').each(function(){        
                              if ($(this).attr('id-api') > 0) {
                                $("#popup #id_"+$(this).attr('id-api')).trigger("click");                              
                              }
                          });
                           
                          $('#cloud-tag a').css("margin-left",'2px');
                          
                          $("#serminar_title").blur(function(){
                              if ($(this).val() == ''){
                                  alert('Please Enter Event Title');
                                  $(this).focus();
                              }
                          });
                          
                           $("#serminar_link").blur(function(){
                                   
                                if ($(this).val() != ''){
                                    if (!/^http:\/\//.test($(this).val()) && !/^https:\/\//.test($(this).val()) ) {
                                        $(this).val("http://" + $(this).val());
                                    }
                                    if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#serminar_link").val())){
                                      
                                    } else {
                                        alert("invalid URL");
                                        $(this).focus();
                                    }                                 
                              }else{
                                   alert('Please Enter Link Address');
                                  $(this).focus();
                              }
                          });
                          
                          $("input[name=who]").each(function(){
                              if( $(this).val() == $("#advions-content a").attr('member') ){
                                  $(this).prop('checked',true);
                              }
                          });
                          
                          if($("#secondaddress")[0]){
                              $("#useaddress2").prop('checked',true);
                          }
                          
                      
                         if($("#lilinkedin")[0]){
                              $("#form10 #linkedin").val($("#lilinkedin").attr('href'));
                         }
                         if($("#lirss")[0]){
                              $("#form10 #rss").val($("#lirss").attr('href'));
                         }
                         if($("#lifacebook")[0]){
                              $("#form10 #facebook").val($("#lifacebook").attr('href'));
                         }
                         if($("#ligoogle")[0]){
                              $("#form10 #google").val($("#ligoogle").attr('href'));
                         }
                         if($("#litwitter")[0]){
                              $("#form10 #twiter").val($("#litwitter").attr('href'));
                         }
                        
                        
                         if ( parseInt($("#map img").attr('zoom')) >= -2 && parseInt($("#map img").attr('zoom')) <= 2 ) {
                            
                             $("#map-edit-img").val($("#map img").attr('data'));
                             $(".single-slider").val($("#map img").attr('zoom'));                             
                         }
                         
                         
                          $('.single-slider').jRange({
                            from: -2,
                            to: 2,
                            step: 1,
                            scale: [-2,-1,0,1,2],
                            format: '%s',
                            width: 260,
                            showLabels: true,
                            snap: true
                        });
                       
                        var stringsearch =   decodeURIComponent($("#seminar .seminar-content-text2 a").attr('href'));
                        if (stringsearch.indexOf("&video_id[]") == -1 ){
                            
                             var arr = stringsearch.split('&cusitem_id[]=');                            
                             $("#idccta"+arr[1]).prop('checked',true);
                        }else {
                             var arr = stringsearch.split('&video_id[]=');
                             arr = arr[1];
                             arr =  arr.split('|xxx');
                             for (var i = 0 ; i <= 100 ; i++ ){
                                 if ($("#idcta"+i).val() == arr[0]){
                                     $("#idcta"+i).prop('checked',true);
                                 }
                             }
                             
                        }
                      
                        $("#cobactatit").val($("#seminar").css('background-color'));
                        $("#cotectatit").val($("#seminar").css('color'));
                        $("#btcobactatit").val($("#seminar a.button-or").css('background-color'));
                        $("#btcotectatit").val($("#seminar a.button-or").css('color'));
                       
    
                       <?php if( JRequest::getVar('new_tem') == 1 ){  ?>
                             setTimeout(function(){    $('.col-2').show(); $('.left_menu_wrap').show(); },7500);
                       <?php } else { ?>
                             $('.col-2').show();
                             $('.left_menu_wrap').show();
                       <?php } ?>
                             
                       <?php if(!$this->ext_booknow_id){     ?>
                             $('#schedule').hide();
                             $('#meeting-control').hide();
                       <?php } ?>
                       <?php if(!$this->ext_zcalendar_id){     ?>
                             $('#serminar1').hide();
                             $('#serminar-control').hide();
                       <?php } ?>  
                           
                              // $("#upload-serminar").val("http://www.advisorproducts.com/components/com_enewsletter/images/imagetest.png");
                         if($("#poll-content")[0]){
                            var myString = $("#poll-content a").attr('href');
                            if (myString != '' ){
                            var arr = myString.split('&id=');
                            $(".idpoll"+arr[1]).prop('checked',true);
                            }
                            //arr[1]
                            
                         }
                         
                    });          
  </script>
   
  <script>
              function dofirst() {
                     mousmove(); 
                    $("#financial").hide();
                    $("#social").click( function() {
                            showedit('social','social');
                             $('.col-1').scrollTop('1000');
                    });
                    $("#address").click( function() {
                            showedit('address','address-content');
                             $('.col-1').scrollTop('800');
                    });
                    $("#logomail").click( function() {
                            showedit('logo','logomail img');
                             $('.col-1').scrollTop('1000');
                    });
                    $("#advions-content").click( function() {
                            showedit('advions','advions-content');
                             $('.col-1').scrollTop('400');
                    });
                    $("#seminar").click( function() {
                            showedit('cta','seminar');
                             $('.col-1').scrollTop('1000');
                    });
                    $("#map").click( function() {
                            showedit('map','map');
                             $('.col-1').scrollTop('800');
                    });
                    $("#cloud-tag").click( function() {
                            showedit('cloud','cloud-tag');
                             $('.col-1').scrollTop('1000');
                    });                    
                   
                    $("#schedule").click( function() {
                            showedit('meeting','schedule');
                              $('.col-1').scrollTop('1000');
                    });
                     $("#invest").click( function() {
                            showedit('investment','invest');
                    });
                    $("#weekly").click( function() {
                            showedit('weekly','weekly');
                              $('.col-1').scrollTop('1000');
                    });
                    $("#poll-content").click( function() {
                            showedit('poll');
                             $('.col-1').scrollTop('500');
                    });
                     $("#serminar1").click( function() {
                            showedit('serminar','serminar1');
                              $('.col-1').scrollTop('1500');
                    });
                    
                    $(".intro").click( function() {
                        
                          if ( !$(".intro input")[0]){
                                showedit('temintro');
                                 $('.col-1').scrollTop('1000');
                                var intro =  $("#intro strong").text();
                                $("#intro strong").empty();
                                $("#intro strong").prepend('<input id="derecintro"  maxlength="120" value="'+intro+'" style="width:100%;padding:7px;border:none;margin-left:-4px;"  />');
                                $('#derecintro').focus();
                                $('#derecintro').blur(function (){
                                     var thenintro =  $("#derecintro").val();
                                     $("#intro strong").empty();
                                     if (thenintro == ''){
                                         $('#intro-control .btch').bootstrapSwitch('state', false);
                                     }
                                     $("#intro strong").text(thenintro);
                                       ajaxsave();
                                }); 
                                $('#derecintro').keyup(function (){
                                        $("#temintro-edit-text").val($(this).val()); 
                                }); 
                        }
                          //  $('#temintro-edit-text').focus();
                         //   window.scrollTo(0, $('#intro-control').offset().top);
                    });
                    <?php if ( JRequest::getVar('new_tem') == 1 ) { ?>
                               $('.sed').show();
                               setTimeout(function(){$('.sed').hide();},4000);
                    <?php } ?>
                    fn_edit_content();
                     tinymce.PluginManager.add('example', function(editor, url) {
    
                        editor.addMenuItem('newdocument', {
                               text: 'Clear Editor',
                               context: 'edit',
                               onclick: function() {
                               tinyMCE.activeEditor.setContent('');
                            }
                           });
                   });

                    
              }
 
            function fn_edit_content() {
                  var dbasjdsaj = 0; 
                  $('.edit_content').on('mousedown', function(e){ 
                          $('#sortable1').unbind(); 
                       
                       if (e.which == 1){
                               
                         var idcont =  $(this).attr("id-cont");
                         var idapi =  $(this).attr("id-api");
                         var id_edit = $('#id_text_content').val();
                         if ( idcont != id_edit){
                             
                                            var a = $('#id_text_content').val();   
                                             if (a != ''){
                                                 
                                                    if ($('#inputtitcus')[0]) {
                                                        var titleyoucus = $('#inputtitcus').val();
                                                    }else {
                                                        var titleyoucus = '';
                                                    }
                                                    
                                                    var a = $('#id_text_content').val();   
                                                    var html_con =  tinyMCE.get(a+'_tex').getContent(); 
                                                   
                                                   
                                                     if(titleyoucus != '' ) {
                                                    
                                                        $("#"+a).html('<strong id="titlecust" style="font-size: 20px;    display: block;    margin-bottom: 5px;" data-mce-style="font-size: 20px;">'+titleyoucus+'</strong>'+html_con);
                                                        $("#"+a).attr('noconfig','0');
                                                       
                                                    }  else {
                                                        $("#"+a).html(html_con);
                                                       
                                                    }
                                                    $('#id_text_content').val('');   
                                             }
                                             
                                          var html_tem = $('#'+idcont).html();     
                                            
                                            
                                        
                                         
                                         if ($('#link_'+idcont).length > 0) {
                                             $('#'+idcont).empty();
                                             $('#'+idcont).html('<textarea id="'+idcont+'_tex" >  </textarea><table style="width: 100%">                                       <tr>                                            <td> <button type="button" class="button-blu content-save" id="" >Save</button> </td>                                            <td>                                                <table>            <td> Text limit  <input style="    width: 80px;    margin-left: 10px;    margin-right: 20px;    padding: 10px;    padding-top: 5px;    padding-bottom: 5px;" type="number" id="id_textlimit_content" value="" placeholder = "Words" /> </td>          <td> <input onclick="check_read_more();" type="checkbox" id="id_readmorlink_content" value="0" />  Read more link </td>                    </tr>    </table> </td>   </tr>  </table>');
                                         }else {
                                             var titlecust = $('#'+idcont+" #titlecust").text();   
                                             var titadvisorname = $('#'+idcont+" #titadvisorname").text();   
                                             $('#'+idcont+" #titlecust").remove();
                                             $('#'+idcont+" #titadvisorname").remove();
                                             html_tem = $('#'+idcont).html();    
                                             $('#'+idcont).empty();
                                             $('#'+idcont).html('<input style="    width: 100%;    display: block;    padding: 6px;" id="inputtitcus" placeholder="Title Enter Here"  /><br><input style="    width: 100%;    display: block;    padding: 6px;" id="inputtitadvisorname" placeholder="By Advisor Name.."  /><br> <textarea id="'+idcont+'_tex" >  </textarea><table style="width: 100%"><tr>  <td> <button type="button" class="button-blu content-save" id="" >Save</button> </td>  </tr>  </table>');
                                             $("#inputtitcus").val(titlecust);
                                             $("#inputtitadvisorname").val(titadvisorname);
                                             
                                         }
                 
                                           
                                           
                                            $('#'+idcont+'_tex').html(html_tem);  
                                             tinyMCE.remove();   
                                                tinymce.init({  
                                                    
                                                    invalid_elements : "script",
                                                    selector:'#'+idcont+'_tex',
                                                    height: 300,
                                                    theme: 'modern',
                                                    
                                                    fontsize_formats: "8pt 9pt 10pt 12pt 14pt 18pt 20pt 24pt 36pt" ,
                                                    relative_urls : false,
                                                    remove_script_host : false,
                                                    convert_urls : true,
                                                    
                                                    plugins: [
                                                      'advlist autolink lists link charmap preview hr anchor pagebreak',
                                                      'searchreplace wordcount visualblocks visualchars fullscreen',
                                                      'insertdatetime nonbreaking save contextmenu directionality',
                                                      'emoticons template paste textcolor colorpicker textpattern imagetools',
                                                      'example'
                                                    ],
                                                    
                                                    removed_menuitems: 'newdocument',
                                                    toolbar1: 'insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media | forecolor backcolor | mybutton2 | mybutton3 ',
                                                    image_advtab: true,
                                                
                                                    setup: function (editor) {                                                            
                                                            editor.addButton('mybutton2', {
                                                              text: 'Upload Image',
                                                              icon: 'image',
                                                              onclick: function () {
                                                                 $('#popup7_open').bPopup();
                                                              }
                                                            });
                                                            
                                                           editor.addButton('mybutton3', {
                                                              text: 'Add File',
                                                              icon: 'newdocument',
                                                              onclick: function () {
                                                                 $('#popup14_open').bPopup();
                                                              }
                                                            });
                                                            
                                                            
                                                            
                                                          },
                                                    templates: [
                                                      { title: 'Test template 1', content: 'Test 1' },
                                                      { title: 'Test template 2', content: 'Test 2' }
                                                    ],
                                                    <?php if ($this->idt == 'enewsletter_threecol') { ?>
                                                            forced_root_block : false,
                                                            force_br_newlines : true,
                                                            force_p_newlines : false,
                                                    <?php } ?>
                                                    content_css: [
                                                      '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                                                      '//www.tinymce.com/css/codepen.min.css'
                                                    ] });
                                                   
                                             $('#id_text_content').val(idcont);   
                                             var a = $('#id_text_content').val();  
                                             $('#link_'+a).css('display','none');
                                             showedit('',$(this).attr("id"));
                                             window.scrollTo(0, $('#'+idcont).offset().top);
                                    }else {                                      
                                        $( ".content-save" ).mouseup(function() { 
                                          
                                         
                                            var a = $('#id_text_content').val();  
                                            var read  = $('#id_readmorlink_content').is(":checked");
                                            if (read){                                           
                                                                       $('#link_'+a).css('display','block');
                                            }else{
                                                                       $('#link_'+a).css('display','none');
                                            }


                                            var text = $('#id_textlimit_content').val();
                                      
                                            var a = $('#id_text_content').val();   
                                            var html_con =  tinyMCE.get(a+'_tex').getContent(); 
                                            if ($('#inputtitcus')[0]) {
                                                var titleyoucus = $('#inputtitcus').val();
                                                var titadvisorname = '<div><b id="titadvisorname" style="font-size: 14px;    display: block;    margin-bottom: 5px;" >'+$('#inputtitadvisorname').val()+'</b></div>';
                                            }else {
                                                var titleyoucus = '';
                                                var titadvisorname = '';
                                            }
                                            $('#a1122332').val(html_con);   
                                             if (text >= 10) {
//                                                 $("#"+a).css('display','block');
//                                                 $("#"+a).css('max-height',text+'px');
//                                                 $("#"+a).css('position','relative');     
//                                                 $("#"+a).css('overflow','hidden');
//                                                 $("#"+a).css('display','block');
//                                                 var text = $('id_textlimit_content').val('');
                                             
                                                $.ajax({
                                                url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=limittext&limit="+text,
                                                type: "POST",
                                                data:  $('#a1122332').serialize()                                        
                                                }).done(function( data ) {                                                        
                                                     $("#"+a).html(data);
                                                     $("#"+a).attr("info",'not');
                                                    // ajaxsave();
                                                     $("#global-save").trigger( "click" ); 
                                                     
                                                });    
                                            }else{
                                           
                                                   
                                                    if(titleyoucus != '' ) {
                                                        
                                                        $("#"+a).html('<strong id="titlecust" style="font-size: 20px;    display: block;    margin-bottom: 5px;" >'+titleyoucus+'</strong>'+titadvisorname+html_con);
                                                        $("#"+a).attr('noconfig','0');                                                       
                                                       // ajaxsave();
                                                        $("#global-save").trigger( "click" ); 
                                                    }  else {
                                                        $("#"+a).html(html_con);
                                                       // ajaxsave();
                                                        $("#global-save").trigger( "click" ); 
                                                    }
                                                    
                                            }
                                            
                                          
                                           
                                           
                                            
                                           $( ".content-save" ).remove();
                                           $('#id_text_content').val('');   
                                          
//                                        alert($('#id_readmorlink_content').is(':checked')); 
                                            
                                          
                                          if (  $("#"+a).attr("info") != 'not') {
											  if ( parseInt($("#"+a).attr("finra")) == 1 ) {
												   if (confirm("The FINRA review letter for this article is no longer applicable once the article is modified. Do you want to save the modified article?")) {
													   
													    $.ajax({
														url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=saveapicontent",
														type: "POST",
														data:  $('#a1122332').serialize()+"&idcontent="+idapi                                                    
														}).done(function( data ) {  }); 
													   
												   }
											  } else {
												    $.ajax({
													url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=saveapicontent",
													type: "POST",
													data:  $('#a1122332').serialize()+"&idcontent="+idapi                                                    
													}).done(function( data ) {  }); 
											  }
                                          }                                           
                                          
                                           
                                       });
                                           
                                           
                                    }   
                                    }else {                                     
                                    }
                                    });                  
              }
           
             
              function savecid (a) {
                            var check = $('#valueidartical').val();           
                            var check1 = check.indexOf(a+',');
                         //  alert(check);
                             if ( check1 == -1){
                                 // them
                                 var count = check.split(",").length - 1;
                                 if (count == 5){
                                     alert ('Maximum 5 ARTICLE  ');
                                     $('#s_'+a).prop('checked',false);
                                     $('#id_'+a).prop('checked',false);
                                     return false;
                                 } else  {
                                      var asas = $('#valueidartical').val()+a+',';               
                                      $('#valueidartical').val(asas ); 
                                 }
                             } else {
                                 // xoa
                                 myString = $('#valueidartical').val();
                                 var n= myString.replace(a+',', ''); 
                                 $('#valueidartical').val(n);
                             }
                    }
                    
            function savecidnewtemplate (a) {
                            var check = $('#valueidarticalnewtemplate').val();           
                            var check1 = check.indexOf(a+',');
                         //  alert(check);
                             if ( check1 == -1){
                                 // them
                                 var count = check.split(",").length - 1;
                                 if (count == 3){
                                     alert ('Maximum 3 ARTICLE  ');
                                     $('#as_'+a).prop('checked',false);
                                     $('#aid_'+a).prop('checked',false);
                                     return false;
                                 } else  {
                                      var asas = $('#valueidarticalnewtemplate').val()+a+',';               
                                      $('#valueidarticalnewtemplate').val(asas ); 
                                 }
                                 $('#cimag_'+a).prop('checked', true);
                             } else {
                                 // xoa
                                 myString = $('#valueidarticalnewtemplate').val();
                                 var n= myString.replace(a+',', ''); 
                                 $('#valueidarticalnewtemplate').val(n);
                                 $('#cimag_'+a).prop('checked', false);
                                 
                             }
          } 
                    
            function   artitemple()  {
                    check = $('#valueidarticalnewtemplate').val();    
                    $('#arinewsubmit').val(check);    
                      var cimag = '';
                      var cgetin = '';
                      $( 'input[name="cimag[]"]:checked' ).each(function () {
                             cimag += $(this).val()+',';   
                      });
                                    
                      $( 'input[name="cgetin[]"]:checked' ).each(function () {
                             cgetin += $(this).val()+',';   
                      });
                      if (cimag == ''){
                          cimag = '1,';
                      }
                      
                      $('#cimag').val(cimag);    
                      $('#cgetin').val(cgetin);    
            } 
            function getcontent (){                           
                            
                            var cuscontit = $('#custom-content-title').val();
                         //   var html_cuscon =  tinyMCE.get("custom-content").getContent(); 
                            if ($.trim(cuscontit) != ''){
                                $( '#cta' ).empty();
                                $('#cta').prepend('<table id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="mceItemTable"><tbody><tr id="intro" bgcolor="#ECEBE0"><td style="padding: 10px 25px 10px 25px;" data-mce-style="padding: 10px 25px 10px 25px;">  <strong class="intro"> Template Intro Here.. </strong></td> </tr><tr id-cont="article_content_6" class="edit_content" id="article_6" style="" data-mce-style=""><td style="padding: 25px;" data-mce-style="padding: 25px;"> <table width="100%" class="mceItemTable"><tbody><tr><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; text-align: justify;"> <br> <div id="article_content_6" style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> <strong style="font-size: 20px;" data-mce-style="font-size: 20px;"> <a  style="color: #000000; text-decoration: none;" >'+cuscontit+'</a></strong> <br><p>'+html_cuscon+'</p></td></tr> </tbody></table> </td></tr>  </tbody></table>')
                                 fn_edit_content();
                                 ajaxsave();
                            }else{
                                 var check = $('#valueidartical').val();  
                            if (check != '' ){
                                  $( '#cta' ).empty().html('<span style="font-size:28px;color:red;text-align:center;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/clock-loading.gif" /> </span>');
                                  
                                    if ($('#getnamefultext').is(":checked")){                                           
                                                   var getonlyintro =    '&getonlyintro=1';                
                                    }else{
                                                   var getonlyintro =    '' ;   
                                    }
                                    
                                    var sid = '';
                                    var getin = '';
                                    $( 'input[name="sid[]"]:checked' ).each(function () {
                                             sid += $(this).val()+',';   
                                    });
                                    
                                    $( 'input[name="getin[]"]:checked' ).each(function () {
                                             getin += $(this).val()+',';   
                                    });
                              //  alert(sid);
                              //  alert(getin);
                                
                                $.ajax({
                                 url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter"+getonlyintro+"&task=getcontent<?php if ($this->idt == 'enewsletter_threecol') { echo '3';} ?>&sid="+sid+"&getin="+getin+"&id="+check,
                                 type: "GET",
                                 enctype: 'multipart/form-data',
                                 processData: false,  // tell jQuery not to process the data
                                 contentType: false   // tell jQuery not to set contentType
                               }).done(function( data ) {
                                   
                                   <?php if ($this->idt == 'enewsletter_threecol') { ?>
                                          var checknum =  $('#valueidartical').val().split(",").length - 1;
                                        // alert(checknum);                                        
                                             if (checknum > 1) {
                                                 $("#main-page-html table tbody tr td:first-child").css('padding-left','0px');
                                                 $("#main-page-html table tbody tr td:first-child").css('padding-right','0px');
                                             }else {
                                                 $("#main-page-html table tbody tr td:first-child").css('padding-left','100px');
                                                 $("#main-page-html table tbody tr td:first-child").css('padding-right','100px');
                                             }
                                    <?php } ?>
                                    $( '#cta' ).empty().html(data);
                                    $(".intro").empty().html( $('#temintro-edit-text').val() );
                                    fn_edit_content();
                                   // ajaxsave();
                                    $("#global-save").trigger( "click" ); 

                               });
                               return false;
                                }                                 
                            }
                     }
                    function mousmove(){
                        
                                $(function() {
                                      $( "#sortable" ).sortable({

                                     update         : function(event,ui){  $('#adform-button').fadeIn(); $('#popup1').bPopup();}
                                 
                                    });

                                      $( "#sortable" ).disableSelection();
                                });
                                $(function() {
                                      $( "#sortable1" ) .sortable({

                                     update         : function(event,ui){  $('#adform-button').fadeIn(); $('#popup1').bPopup();}
                                  });
                                      $( "#sortable1" ).disableSelection();
                                });

                                 $(function() {
                                     // $( "#logomail" ).draggable({  grid: [ 20, 20 ] });
                                     // $( "#address" ).draggable({ grid: [ 20, 20 ] });
                                     // $( "#social" ).draggable({ grid: [ 20, 20 ] });

                                });

                                 
                                $('#logomail').mousedown(function(e) {

                                              start = new Date(); 
                                         $('#logomail').mouseup( function(e1) {
                                              end = new Date();
                                               var diff = end - start; 
                                               if (diff > 200){
                                                    $('#adform-button').fadeIn(); $('#popup1').bPopup();   
                                                }
                                        });

                                     return false;

                                 });

                                  $('#address').mousedown(function(e) {

                                              start = new Date(); 
                                         $('#address').mouseup( function(e1) {
                                              end = new Date();
                                               var diff = end - start; 
                                               if (diff > 200){
                                                    $('#adform-button').fadeIn(); $('#popup1').bPopup();   
                                                }
                                        });

                                     return false;

                                 });

                                  $('#social').mousedown(function(e) {

                                              start = new Date(); 
                                         $('#social').mouseup( function(e1) {
                                              end = new Date();
                                               var diff = end - start; 
                                               if (diff > 200){
                                                    $('#adform-button').fadeIn(); $('#popup1').bPopup();   
                                                }
                                        });

                                     return false;

                                 });

             }
              
            
            function showedit(a ,b){
                
                $('#advions-edit').hide();
                $('#advions-control').css("border-bottom","1px solid #ddd");
                $('#advions-control').css("color","#000");
                $('#advions-content').css("border","4px solid rgb(221,221,221)");
                $('#advions-content').css("background","");
                
                $('#poll-edit').hide();
                $('#poll-control').css("border-bottom","1px solid #ddd");
                $('#poll-control').css("color","#000");
                $('#poll-content').css("border","");
                
                    <?php if ( $this->idt != 'youtubethem' && 0 == 1) { ?>     $('.edit_content').css("background","#fbe497"); <?php  } ?>
                
                $('#editcontent-edit').hide();
                $('.edit_content').css("border","#f4f4f4");
                $('.edit_content').css("background",'#'+$('#backgc').val());
                  
                $('#global-edit').hide();
                $('#global-control').css("border-bottom","1px solid #ddd");
                $('#global-control').css("color","#000");
                
                $('#cta-edit').hide();
                $('#cta-control').css("border-bottom","1px solid #ddd");
                $('#cta-control').css("color","#000");
                
                $('#meeting-edit').hide();
                $('#meeting-control').css("border-bottom","1px solid #ddd");
                $('#meeting-control').css("color","#000");
                $('#schedule').css("border","4px solid rgb(221,221,221)");
                $('#schedule').css("background","#fff");
                
                $('#investment-edit').hide();
                $('#investment-control').css("border-bottom","1px solid #ddd");
                $('#investment-control').css("color","#000");
                $('#invest').css("border","4px solid rgb(221,221,221)");
                $('#invest').css("background","#fff");
                
                $('#financial-edit').hide();
                $('#financial-control').css("border-bottom","1px solid #ddd");
                $('#financial-control').css("color","#000");
                $('#financial').css("border","4px solid rgb(221,221,221)");
                $('#financial').css("background","#fff");
                
                $('#weekly-edit').hide();
                $('#weekly-control').css("border-bottom","1px solid #ddd");
                $('#weekly-control').css("color","#000");
                $('#weekly').css("border","4px solid rgb(221,221,221)");
                $('#weekly').css("background","#fff");
                
                $('#address-edit').hide();
                $('#address-control').css("border-bottom","1px solid #ddd");
                $('#address-control').css("color","#000");
                $('#address-content').css("border","");
                $('#address-content').css("background","");
                
                $('#logo-edit').hide();
                $('#logo-control').css("border-bottom","1px solid #ddd");
                $('#logo-control').css("color","#000");
                $('#logomail img').css("border","");
                $('#logomail img').css("background","");
                
                $('#social-edit').hide();
                $('#social-control').css("border-bottom","1px solid #ddd");
                $('#social-control').css("color","#000");
                $('#social').css("border","");
                $('#social').css("background","");
                
                $('#map-edit').hide();
                $('#map-control').css("border-bottom","1px solid #ddd");
                $('#map-control').css("color","#000");
                $('#map').css("border","4px solid rgb(221,221,221)");
                $('#map').css("background","");
                
                $('#cloud-edit').hide();
                $('#cloud-control').css("border-bottom","1px solid #ddd");
                $('#cloud-control').css("color","#000");
                $('#cloud-tag').css("border","4px solid rgb(221,221,221)");
                $('#cloud-tag').css("background","#fff");
                
                $('#serminar-edit').hide();
                $('#serminar-control').css("border-bottom","1px solid #ddd");
                $('#serminar-control').css("color","#000");
                $('#serminar1').css("border","4px solid rgb(221,221,221)");
                $('#serminar1').css("background","#fff");
                $('.intro').css("border","none");
                $('.intro').css("background",'#'+$('#backbargc').val());
                // cta
                $('#seminar').css("border","4px solid rgb(221,221,221)");
             //   $('#seminar').css("background","");
                
                 $('#temintro-edit').hide();
                 if (a == 'poll'){
                      $('#poll-content').css("border","4px solid red");
                 }
                 
                 if (a == 'temintro'){
                        $('.intro').css("border","4px solid red");

                 }
                
                $('#'+a+'-edit').show();
                $('#'+a+'-control').css("border-bottom","2px solid red");
                //$('#'+a+'-control').css("color","white");
                $('#'+b).css("border","4px solid red");
              
                if (b != 'seminar'  && b != 'edit_content'  ){
                  <?php if ( $this->idt != 'youtubethem') { ?>    $('#'+b).css("background","#FFFFaa");  <?php } ?>
                }
                
                  <?php if ( $this->idt == 'apologize') { ?>    $('.edit_content').css("background","#ffffff");  <?php  } ?>
            }
               function changeoldlayout(){
                                      
                       $('#isdatahtml').html( $('#htmlcodeold').val()); 
                       dofirst();
                       
                     
              }
              function deletetemp() {
                    if (confirm("Do you want to delete ?")) {
                           arr = $('#open_temps').val();
                          
                           for (var i = 1 ; i < 1000 ; i++ ){
                               if ( arr.indexOf('_'+i+'_') >= 0){
                                   ark =  $('#open_temps').val().split('_'+i+'_'); 
                                   break;
                               }
                           }
                           
                           $('#id_user').val(i);
                           $('#changetemps').val(ark[1]);
                           $('#idt').val(ark[0]);
                           $('#task').val('deleteit');
                           $('#adform').submit();
                    }
                    return false;
               }
               function deletepoll(a){
                   if (confirm("Do you want to delete Poll ?")) {
                       $('.seving').show();
                            $.ajax({
                                url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=deletepoll",
                                type: "POST" ,
                                data: 'id='+a
                            }).done(function( data ) {   
                                window.location.reload(true);                                          
                            });   
                   }
                   return false;
               }
               function deletecta(a){
                    if (confirm("Do you want to delete Cta report ?")) {
                     
                             $('.seving').show();
                             $.ajax({
                             url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=deletectavideo",
                             type: "POST" ,
                             data: 'id='+a
                             }).done(function( data ) {   
                                   window.location.reload(true);                                           
                             });   
                    }
                    return false;
               }
              
               function opentemp(){
                
                      if( $('#open_temps').val() != '' ){
                           arr = $('#open_temps').val();
                          
                           for (var i = 1 ; i < 1000 ; i++ ){
                               if ( arr.indexOf('_'+i+'_') >= 0){
                                   ark =  $('#open_temps').val().split('_'+i+'_'); 
                                   break;
                               }
                           }
                           
                           $('#id_user').val(i);
                           $('#changetemps').val(ark[1]);
                           $('#idt').val(ark[0]);
                           $('#task').val('');
                           $('#adform').submit();
                      }else{
                           alert('Please! Select Layout');
                      }

               }
               
               
               function newtemp(){
                      $('.newtemwam').remove();
                      var sss = $("input[type='radio'][name='optionf']:checked");
                       var name =  $('#changetemps_popup').val()
                      if( $.trim(name) == '' ){
                            $('#changetemps_popup').css('border','2px solid #f00');  
                            $('#changetemps_popup').css('outline','none');   
                            $('#changetemps_popup').focus();
                      
                      }else if ( sss.length == 0 ) {
                            alert('Select Option');
                      }else if( !$('.chothumtm').hasClass('active')){
                            //alert('Please Select A Format');
                            $(".bfformat").after('<span class="newtemwam" style="color:red;"  > Please Select A Format </span>');
                            
                      }else {
                             $('#changetemps').val($.trim(name));     
                             $('#idt').val($('#sasasasa').val());   
                             $('#new_tem').val('1');   
                             $('#task').val('');
                             $('#optionf').val($("input[type='radio'][name='optionf']:checked").val());
                             $('#adform').submit();
                      }
               }
               
              function subhtml(tasks){
                       showedit();
                         var a = $('#id_text_content').val();   
                       if (a != ''){
                                                    var a = $('#id_text_content').val();   
                                                    var html_con =  tinyMCE.get(a+'_tex').getContent(); 
                                                    $("#"+a).html(html_con);
                          }
                       var val2 =  $('#isdatahtml').html();                   
                       $('#htmlcode').html(val2);       
                     
                       if (tasks == 'send'){
                        f =  document.getElementById('adform');
                        f['task'].value = '';
                        f['view'].value = 'sendmail';
                        $('#adform').submit();
                        return false;
                      }
                      if (tasks == 'history'){
                        f =  document.getElementById('adform');
                        f['task'].value = '';
                        f['view'].value = 'history';
                        $('#adform').submit();
                        return false;
                      }
                      
                       if (tasks == 'setting'){
                            f =  document.getElementById('adform');
                            f['task'].value = '';
                            f['view'].value = 'setting';
                            $('#adform').submit();
                            return false;
                      }
                      
                    if (  $('#htmlcode').html() != ''  && $('#changetemps').val() != '' ){
                      $('#adform').submit();
                    } else {
                            window.scrollTo(0, $('.logomail').offset().top);
                            $('#changetemps').css("border","1px solid red");
                            alert ('Name Template isn\'t empty');
                    }
              }
              
              function ajaxsave(){
                        showedit();
                         var a = $('#id_text_content').val();   
                         if (a != '' && a != 'article_content_7' && a != 'article_content_13' && a != 'article_content_12' ){
                               var a = $('#id_text_content').val();   
                               var html_con =  tinyMCE.get(a+'_tex').getContent(); 
                               $("#"+a).html(html_con);
                               $('#id_text_content').val('');   
                         }
                        $('#htmlcode').html('');
                        var val2 =  $('#isdatahtml').html();     
                        $('#htmlcodeold').html(val2); 
                        $('#htmlcode').html(val2);               
                                     
                    
                        if (  $('#htmlcode').html() != '' && $('#changetemps').val() != ''){                            
                                $('.seving').show(); $('.butsendmail').css('opacity','0.6');
                                   setTimeout(function(){
                                        $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savehtml",
                                          type: "POST",
                                          data: $('#adform').serialize()                                        
                                        }).done(function( data ) {                                        
                                           
                                             $('.seving').hide();
                                             $('.sed').show();
                                             $('.butsendmail').css('opacity','1');
                                               setTimeout(function(){$('.sed').hide();},2000);
                                        });     
                          }, 500);           
                    } else {
                            window.scrollTo(0, $('.logo').offset().top);
                            $('#changetemps').css("border","1px solid red");
                            alert ('Name Template isn\'t empty');
                    }
              }  
             function changectatab(a){
                $('.cta-tab-1').removeClass('acctive');
                $('.cta-tab-2').removeClass('acctive');
                if (a == 1){
                    $('.cta-tab-1').addClass('acctive');
                    $('.edit-text').show();
                    $('.edit-image').hide();
                }else {
                    $('.cta-tab-2').addClass('acctive');
                    $('.edit-text').hide();
                    $('.edit-image').show();
                }
             
             }
             
             
             function changeaddrtab(a){
                $('.addr-tab-1').removeClass('acctive');
                $('.addr-tab-2').removeClass('acctive');
                if (a == 1){
                    $('.addr-tab-1').addClass('acctive');
                    $('.infoaddress1').show();
                    $('.infoaddress2').hide();
                }else {
                    $('.addr-tab-2').addClass('acctive');
                    $('.infoaddress1').hide();
                    $('.infoaddress2').show();
                }
             
             }

              
        </script>
       <script>
       
         $( document ).ready(function() {
             
                <?php if( JRequest::getVar('new_tem') == 1 ){ ?>
                        
                       <?php if($this->com_params->get('advisorstatus') == -1 ){ ?>
                                $("#advions-content").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('pollstatus') == -1 ){ ?>
                                $("#poll-content").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('logostatus') == -1 ){ ?>
                                 $("#logomail").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('socialstatus') == -1 ){ ?>
                                $("#social").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('ctastatus') == -1 ){ ?>
                                $("#seminar").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('introstatus') == -1 ){ ?>
                                $("#intro").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('mapstatus') == -1 ){ ?>
                                $("#map").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('tagstatus') == -1 ){ ?>
                                $("#cloud-tag").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('addressstatus') == -1 ){ ?>
                                $("#address-content").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('meetingstatus') == -1 ){ ?>
                                $("#schedule").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('eventstatus') == -1 ){ ?>
                                $("#serminar1").hide();
                       <?php }  ?>

                       <?php if($this->com_params->get('weeklystatus') == -1 ){ ?>
                                $("#weekly").hide();
                       <?php }  ?>    

                <?php } ?>  
                                
                                
                if ( $("#advions-content").css('display') == 'none'){            
                     $("#openadvisor").prop('checked',false);
                }
                if ($("#social").css('display') == 'none'){            
                     $("#opensocial").prop('checked',false);
                }
                if ($("#poll-content").css('display') == 'none'){            
                     $("#openpoll").prop('checked',false);
                }
                if ($("#seminar").css('display') == 'none'){            
                     $("#opencta").prop('checked',false);
                }
                if ($("#map").css('display') == 'none'){            
                     $("#openmap").prop('checked',false);
                }
                if ($("#schedule").css('display') == 'none'){            
                     $("#openmeeting").prop('checked',false);                     
                }                
                if ($("#serminar1").css('display') == 'none'){            
                     $("#openserminar").prop('checked',false);                     
                }
                if ($("#weekly").css('display') == 'none'){            
                     $("#openweekly").prop('checked',false);
                }
                 if ($("#cloud-tag").css('display') == 'none'){            
                     $("#opencloud").prop('checked',false);
                }
                if ($("#address-content").css('display') == 'none'){            
                     $("#openaddress").prop('checked',false);
                }
                if ($("#logomail").css('display') == 'none'){            
                     $("#openlogo").prop('checked',false);
                }
                if ($("#intro").css('display') == 'none'){            
                     $("#openintro").prop('checked',false);
                }
               
                //$('.btch').bootstrapSwitch('state', true);
                
                $('.btch').on('switchChange.bootstrapSwitch', function (event, state) {

                    var ref = $(this).attr('ref');
                    var res = ref.split(",");
                    var a = res[0];
                    var b = res[1];               
                    if (state){
                            $("#"+a+" input[type='checkbox']").prop('checked',true);
                            $("#"+b).show();
                    }else{
                            $("#"+a+" input[type='checkbox']").prop('checked',false);
                            $("#"+b).hide();
                    }
                    ajaxsave()
                });
                
                
                $('#form8 .infoaddress1 input').blur(function(){
                    if($(this).val() == '' && $(this).attr('id') != 'address_address2'){
                        alert('Enter infomation');
                        $(this).focus();
                    }
                });
                
                
                $(".cta-btn-circle").click(function(){
                    if ( $(this).attr('data_href')){
                        window.location.replace($(this).attr('data_href'));
                    }else {
                        window.location.replace("<?php echo juri::base(); ?>");
                    }
                });
                cosi1();
                function cosi1(){
                        $(" .nametemplate, .nametemplateedit ").click(function(){
                            var name = $(".nametemplate").text();
                            $("<input id='edit-name' style='width:500px;    padding: 9px;' value='' maxlength='150'  />").insertAfter($('.nametemplate'));
                            $('.nametemplate').remove();
                            $('#edit-name').val(name);
                            $('#edit-name').focus();
                            $(".nametemplateedit").html('<button type="button" >Save</button>');
                            cosi2();

                        });
                }
                
                function cosi2(){
                          $("#edit-name").blur(function(){                  
                                if ( $.trim($(this).val())  != '') {
                                    $(".nametemplateedit").remove();
                                    $("<span class='nametemplate' >"+$(this).val()+"</span><span class='nametemplateedit'><i class='fa fa-pencil-square-o fa-3x' style='color: #b72121;' aria-hidden='true'></i></span>").insertAfter($(this));                                    
                                    
                                    $('.seving').show();
                                         $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savetemplatename",
                                          type: "POST",
                                          data:  "&sub="+$.trim($(this).val())+"&idt=<?php echo $this->idt; ?>&id_user="+$("#id_user").val()+"&changetemps=<?php  echo $this->changetemps_lauout ?>"                                
                                   }).done(function( data ) {  
                                           window.location.reload(true);         
                                  });
                                   $(this).remove();
                                   cosi1();
                                }else{
                                    alert ('Name Template not empty');
                                    $(this).focus();
                                }
                            });
                }
                                                
        });
        
       </script>
<script>
$(document).ready(function() {            
	
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


                $('#jform_subject').blur(function (){                    
                    if ( $.trim($(this).val())  != '') {
                                        $('.seving').show();
                                         $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savesuject",
                                          type: "POST",
                                          data:  "&sub="+$.trim($(this).val())+"&idt=<?php echo $this->idt; ?>&id_user="+$("#id_user").val()+"&changetemps=<?php  echo $this->changetemps_lauout ?>"                                
                                         }).done(function( data ) {  
                                             $('.seving').hide();
                                             $('.sed').show();
                                             setTimeout(function(){$('.sed').hide();},2000);
                                        });     
                    }
            });


	} );

    function fill_email(a){

    }
    function sendmail(){
     
            var error = 0;
          
            if($('#jform_subject').val().trim() == '') {                
                alert('Subject Line is not blank ');  
                 return false;                
            }
            
            if($('#verified_emails').val() == '') {                
                alert('Choice Email From');  
                 return false;                
            }
                        
            if ($('.checkall_group:checked').length > 0 ) {
                
            }else {
                alert('Choice List send out ');  
                 return false;
            }
            $('#fsubject').val($('#jform_subject').val().trim());
            $('#fverified_emails').val($('#verified_emails').val());
            $('#taskkk').val('send');
            $('#sendform').submit();
     
 }
 function viewpoll(a) {
     
      $('#ifrapoll').attr('src', '<?php echo juri::base(); ?>index.php?option=com_enewsletter&tmpl=component&view=poll&idpoll='+a );  
      $('#popup12_open').bPopup();
      $('#popup12_open').css("top",'100px');
      $('#ifrapoll').hide();
      $('.pageloadding').show();
      $('#ifrapoll').on('load', function(){
            $('#ifrapoll').show();
            $('.pageloadding').hide();
      });
 }
 function previewcta(a,b) {
      if (b != 2){
         $('#ifracta').attr('src', $('#atagcta'+a).attr('ref') );
      }else{
         $('#ifracta').attr('src', $('.nameof'+a).attr('ref') );  
      }
      $('#popup11_open').bPopup();
      $('#popup11_open').css("top",'100px');
      $('#ifracta').hide();
      $('.pageloadding').show();
       
       $('#ifracta').on('load', function(){
             $("#ifracta").contents().find("h2").css('display','none');
             $("#ifracta").contents().find(".butdowcta").css('display','none');
             $('.pageloadding').hide();
             $('#ifracta').show();
             
       });
    
 }
 
 function uploadpoll(){
     if( jQuery("#poll-questtion").val() == '' ){
         alert('Please Enter Question');
     }else {
                                         $('.seving').show();
                                         $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=uploadpoll",
                                          type: "POST",
                                          data: "quest="+ jQuery("#poll-questtion").val()                            
                                         }).done(function( data ) {  
                                             $('.seving').hide();                                             
                                             window.location.reload(true);
                                        });     
     }
 }
</script>

<script>
            var SqueezeBox= {close:function(a){
                  $('#popup7_open').bPopup().close();
            }
            }
            function jInsertEditorText(a) {
            
                 a  =  a.replace('"images', '"<?php echo juri::base(); ?>images');
                 a  =  a.replace('src="', ' style="width:100%; max-width:700px;"   src="');
              
                 tinymce.activeEditor.execCommand('mceInsertContent', false, a);
                
            }
            function  parentinsertfile(a){       
                
                var highlighted = tinyMCE.activeEditor.selection.getContent();
                    if (highlighted.length > 1){
                           tinymce.activeEditor.execCommand('mceInsertContent', false, "<a  href='"+a+"' > "+highlighted+"</a>");
                    }else {
                           tinymce.activeEditor.execCommand('mceInsertContent', false, "<a href='"+a+"' > "+a+"</a>");
                    } 
                 $('#popup14_open').bPopup().close();
            }
            function parentinsertfileclose(){
              
                 $('#popup14_open').bPopup().close();
            }


</script>
    
<script>
$(document).ready(function(){
      $('#changetemps_popup').bind("cut copy paste",function(e) {
          e.preventDefault();
      });
    });
</script>
        <div class="allpage" >
              <input type="hidden" id="valueidartical" value="" >
              <input type="hidden" id="valueidarticalnewtemplate" value="" >
            <div class="col-1" > 
            
              
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
                <div class="list_menu_button" style="    height: 50px;    margin-top: -25px;">
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
                        <div style="    float: left!important;       width: 123px;    font-size: 18px;    color: #666!important;"  class="logo-save btncontactus" id="btn_inactive_edittemplate_and_deletetemplate" onclick='parent.postMessage("loadMyOrders","*"); ' > Dashboard</div>
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
                
                  
                        
                       
                <style>
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
                </style>
                   
                <div class="main-1 left_menu_wrap" style="    margin-bottom: 58px;">
                    
                    <div style="background-color: #e4e4e4 !important; padding-left: 40px;"><div class="sqs-navigation-item"> <a style="    color: #000;    font-size: 16px;   text-decoration: none; text-transform: uppercase;" title="Setting" href="#"  ><img src="<?php echo juri::base();?>components/com_enewsletter/assets/images/iconsetting.png" style="width:20px;    margin-bottom: -5px;" /> Enewsletter </a> <a style="  color: #000;    font-size: 16px;    margin-left: 20px;    text-decoration: none;    text-transform: uppercase;" title="Setting" href="#" onclick="subhtml('setting')" ><img src="<?php echo juri::base();?>components/com_enewsletter/assets/images/iconenew.png" style="width:20px;    margin-bottom: -5px;" /> Settings</a>   </div>  </div>
                  
                    <form id="adform" method="post" action=""  enctype="multipart/form-data" style="text-align: center;height: 35px;" >
                        <textarea name="htmlcode" id="htmlcode" style="display: none;"></textarea>
                        <div id="block-button" style="    margin-top: 30px;    text-align: left;   "> 
                            <span class="edittem aask1" onclick=" $('#popup3_new').bPopup();  "  > NEW</span>
                            <span class="edittem aask2" onclick=" $('#popup2_open').bPopup();  " > OPEN</span>
                            <span class="edittem aask3" onclick=" subhtml('history'); "  > HISTORY </span>
                        </div>
                        <br>
                        
                      
                        
                     
                      
                        
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" id="task" name="task" value="savehtml" >
                         <input type="hidden" name="csslink" value="<?php echo JURI::base(); ?>components/com_enewsletter/assets/newsletter.css" >
                         <input type="hidden" name="view" value="editletter" >
                         
                         <input id="changetemps"  name="changetemps"  value="<?php  echo $this->changetemps_lauout ?>" type="hidden" />
                                 
                         <input type="hidden" name="tmpl" value="component" >
                         <input type="hidden" id="new_tem" name="new_tem" value="" >
                         <input type="hidden"  id="idt" name="idt" value="<?php echo $this->idt; ?>" >
                         <input type="hidden" name="filen" value="<?php echo $this->filen; ?>" >
                         <input type="hidden"  id="id_user" name="id_user" value="<?php echo str_replace($this->idt.'_', '', $this->filen); ?>" >   
                         <input type="hidden" name="optionf" id="optionf" value="" >
                         <input type="hidden" name="arinewsubmit" id="arinewsubmit" value="" >
                         <input type="hidden" name="cimag" id="cimag" value="" >
                         <input type="hidden" name="cgetin" id="cgetin" value="" >                        
                         <?php echo JHtml::_('form.token'); ?>
                        
                    </form>
                    
                   
                    
                    <div id="Formsend" class="block-2" >
                        <div class="image1" > <i class="fa fa-envelope-o fa-3x"></i>  </div>
                        <div class="text1" > From: <div id="verified_email"></div>  </div>                      
                        
                    </div>
                    
                     <div id="Formsend" class="block-2" >
                        <div class="image1" > <i class="fa fa-newspaper-o fa-3x"></i>  </div>
                        <div class="text1" > Subject: <input type="text" id="jform_subject" value="<?php if ($this->subject != ''){ echo $this->subject; } else {echo $this->changetemps_lauout ; } ?>" /> </div>     
                        <br>
                        <button class="butsendmail" onclick="if ( $('.seving').css('display') == 'none' )  { $('#popup6_open').bPopup(); }" type="button" style="      border: none;    text-align: center;    margin: -40px 2px 25px;  
    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;    min-width: 100px;    float: left;    font-size: 0;   background: url('<?php echo juri::base();?>components/com_enewsletter/assets/images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 292px;    height: 85px; " >Send Mail</button>
                    </div>
                    
                    
                    <div id="global-control" class="block-2" onclick=" showedit('global');">
                        <div class="image1" > <i class="fa fa-cog fa-3x"></i>  </div>
                        <div class="text1" > Global Setting </div>                      
                        
                    </div>
                    <div id="global-edit" class="block-3" style="display: none;"   >                         
                        <form id="form99">
                          <br>
                          <span style="width:120px;    display: inline-block;">Background Color:</span>
                            <input class="jscolor" id="backgc" value="<?php if ($this->optioncolor->backgc != ''){ echo $this->optioncolor->backgc; } else {echo 'f4f4f4'; } ?>" style="    padding: 5px;    width: 150px;    border-radius: 5px;"><br>
                           <span style="width:120px;    display: inline-block;"> Main Text Color:</span>
                            <input class="jscolor" id="maintextgc" value="<?php if ($this->optioncolor->maintextgc != ''){ echo $this->optioncolor->maintextgc; } else {echo '000000'; } ?>"  style="    padding: 5px;    width: 150px;    border-radius: 5px;" ><br>
                          
                           <span style="width:120px;    display: inline-block;"> Background Bar Color:</span>
                            <input class="jscolor" id="backbargc" value="<?php if ($this->optioncolor->backbargc != ''){ echo $this->optioncolor->backbargc; } else {echo 'ecebe0'; } ?>"  style="    padding: 5px;    width: 150px;    border-radius: 5px;" ><br>
                            
                            
                           <span style="width:120px;    display: inline-block;"> Link Color:</span>
                            <input class="jscolor" id="linktextgc" value="<?php if ($this->optioncolor->linktextgc != ''){ echo $this->optioncolor->linktextgc; } else {echo '2366bd'; } ?>"  style="    padding: 5px;    width: 150px;    border-radius: 5px;" ><br>
                            
                            <button type="button" class="button-blu" id="global-save" >Save</button>
                        </form>
                        
                        <script>
                           
                            $( "#global-save" ).click(function() {
                                
                                $("#isdatahtml > table, .weeklyupdatetable table ,.weeklyupdatetable table td ").css('background-color','#'+$('#backgc').val());
                                $("#isdatahtml > table").css('background','#'+$('#backgc').val());   
                                $(".edit_content").css('background','#'+$('#backgc').val());   
                                $(".edit_content table div  ,.edit_content > td > table > tbody > tr > td > div , #api-title ,.intro ,#settingintro ,#settingdeclo , p > font ").css('color','#'+$('#maintextgc').val());   
                                $("#address table").css('color','#'+$('#maintextgc').val());                                   
                                $("#intro , .intro , #intro > table , #intro > table td , .intro > table td , .intro > table").css('background','#'+$('#backbargc').val());   
                                $(".edit_content table div a ,  #schedule a ,#serminar1 a, #weekly a , #invest a , #cloud-tag a , #address a , a > font").css('color','#'+$('#linktextgc').val());   
                                $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savecolor",
                                          type: "POST",
                                          data:  "&backgc="+$('#backgc').val()+"&maintextgc="+$('#maintextgc').val()+"&linktextgc="+$('#linktextgc').val()+"&backbargc="+$('#backbargc').val()+"&idt=<?php echo $this->idt; ?>&changetemps=<?php  echo $this->changetemps_lauout ?>"                                
                                         }).done(function( data ) {  
                                          ajaxsave();
                                        });   
                            });

                        </script>
                    </div>
                    
                    <div id="advions-control" class="block-2" onclick=" showedit('advions','advions-content');window.scrollTo(0, $('#advions-content').offset().top);">
                        <div class="image1" > <i class="fa fa-user fa-3x"></i>  </div>
                        <div class="text1" > Advisors  <div style="float:right;" ><input id="openadvisor" data-size="small"  class="btch" ref="form1,advions-content" type="checkbox"  name="position1" checked="checked" value="Yes"  ></div></div>                      
                        
                    </div>
                    
                         <div id="advions-edit" class="block-3" style="display: none;">
                         
                        <form id="form1" name="form1">
                            <div style="display:none;">
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
                            </div>
                        <div id="upload-demo" style="display:none;"></div>                      
                        <br> Or Upload : <br>
                        <input value="" type="file"  id="upload" onchange="$('#upload-demo').show();"  name="file" >
                        <div style="display:none;">
                        <br><br>
                        Enable :  <input type="checkbox" name="position1" checked="checked" value="Yes">                        
                        </div>  
                        <br>
                        <input type="button" onclick="clearadvisor();" value="Clear Image" style="    margin-top: 11px;">
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
                        <label class="addlabel">title:</label>
                        <input value="quick poll" class="adreinput" type="text" id="poll-edit-title" placeholder="" >
                       
                        <textarea  type="text" id="poll-edit-content" value="" style="display: none;width: 295px;" >
                            

                        </textarea>
                        <input  type="text" id="poll-edit-linktrue" value="#" style="display: none;">
                        <input  type="text" id="poll-edit-linkfalse" value="#" style="display: none;">
                        <ul id="list_poll" style="    margin-left: -43px;    width: 95%;">
                                  <?php 
                                 echo $this->poll;
                                    ?>
                            
                        </ul>
                        
                        Or Create New Poll:
                        
                        <textarea  type="text" id="poll-questtion" value="" style="width: 295px;" ></textarea>
                        <br>
                        <button type="button"  id="upload_poll" onclick="uploadpoll();" >Save Poll</button> 
                        <script>
                            function chagepoll(a,b){
                                 $( "#poll-edit-content" ).val(b);
                                 $("#poll-edit-content").show();
                                 <?php  $linkback = base64_encode( juri::base().'index.php?option=com_enewsletter&view=poll');  ?>
                                 $( "#poll-edit-linktrue" ).val('https://centcom.advisorproducts.com/index.php?option=com_acepolls&returnback=<?php echo $linkback; ?>&task=updatepoll&op=t&id='+a);
                                 $( "#poll-edit-linkfalse" ).val('https://centcom.advisorproducts.com/index.php?option=com_acepolls&returnback=<?php echo $linkback; ?>&task=updatepoll&op=f&id='+a);
                                 
                            }
                        </script>
                        
                         <div style="display:none;">
                        <br>
                        Enable :     <input type="checkbox" name="position2" checked="checked" value="Yes">
                        </div>
                        <button type="button" class="button-blu" id="poll-save" >Save</button>                       
                        <script>
                           
                            $( "#poll-save" ).click(function() {
                               
                               if ( $("#form2 input[type='checkbox']:checked").val() == 'Yes' ){
                                 
                                   $( "#poll-content" ).empty();
                                   $( "#poll-content" ).append('  <div class="poll-content-title1" style="color: #F79925;      font-weight: bold;  text-transform: uppercase;    font-size: 18px; " >   '+  $( "#poll-edit-title" ).val() +'     </div> <br> ');
                                   $( "#poll-content" ).append('   <div class="poll-content-text1">  <div>'+ $( "#poll-edit-content" ).val() +'</div><br>  ');
                                   $( "#poll-content" ).append('    <a  style="    color: #F79925;    background-color: #0F4180;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;" target="_blank" href="'+ $( "#poll-edit-linktrue" ).val() +'" > TRUE </a><a style="    color: #F79925;    background-color: #0F4180;    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;"  targer="_blank"  href="'+$( "#poll-edit-linkfalse" ).val()+'"  > FALSE </a> <br><br>  </div>  ');  
                                   $('#poll-content').show();                                    
                               }else{                                   
                                   $('#poll-content').hide();
                                   
                               }
                                 ajaxsave();
                                
                            });

                        </script>
                        
                        </form>
                    </div>
                       <div id="logo-control" class="block-2" onclick="showedit('logo','logomail img');window.scrollTo(0, $('#logomail').offset().top);" >
                        <div class="image1" > <i class="fa fa-500px fa-3x"></i>  </div>
                        <div class="text1" > logo <div style="float:right;" ><input id="openlogo" data-size="small"  class="btch"  ref="form9,logomail" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                                     
                                     
                    <div id="logo-edit" class="block-3" style="display: none;"   >
                          <br>
                        <form id="form9">
                          
                        <div id="upload-demo-logo" style="display: none;"></div>
                        <br>
                        Edit Image ratio: <input style=" text-align: right;    width: 41px;    border-top: none;      width: 62px;    font-size: 20px;  border-left: none;    border-right: none;"  type="number" id="maxwidlogo"  min="1" max="280" value="280" onchange="if( $('#maxwidlogo').val() <= 280 &&  $('#maxwidlogo').val() > 0 ){ $('#upload-demo-logo .cr-viewport').css('width',$('#maxwidlogo').val()+'px') }else{ $('#maxwidlogo').val('280'); } "/>x<input  style="  text-align: right;  width: 41px;    border-top: none;    border-left: none;    border-right: none; width: 62px;    font-size: 20px;"  type="number" id="maxheilogo"  min="1" max="280" value="75" onchange="if($('#maxheilogo').val() <= 280  &&  $('#maxwidlogo').val() > 0  ){ $('#upload-demo-logo .cr-viewport').css('height',$('#maxheilogo').val()+'px') }else{ $('#maxheilogo').val('75') } "/>px <br>
                        <br>
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
                            
                        <label class="addlabel">LINKED IN: </label>
                        <input class="adreinput" value="#" type="text" id="linkedin" placeholder="" >
                        <label class="addlabel">RSS: </label>
                        <input class="adreinput" value="#" type="text" id="rss" placeholder="" >
                        <label class="addlabel">FACEBOOK: </label>
                        <input class="adreinput" value="#" type="text" id="facebook" placeholder="" >
                        <label class="addlabel">GOOGLE PLUS: </label>
                        <input class="adreinput" value="#" type="text" id="google" placeholder="" >
                        <label class="addlabel">TWITTER: </label>
                        <input class="adreinput" value="#" type="text" id="twiter" placeholder="" >
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
                                    $( "#social" ).append('<a href="'+$("#linkedin").val()+'" id="lilinkedin"><img src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/images/icons/linkedin.png" > </a> ');
                                    }
                                    if($("#rss").val() != ''){
                                    $( "#social" ).append('<a href="'+$("#rss").val()+'" id="lirss"><img src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/images/icons/rss.png" > </a> ');
                                    }
                                    if($("#facebook").val() != ''){
                                    $( "#social" ).append('<a href="'+$("#facebook").val()+'"  id="lifacebook"><img src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/images/icons/facebook.png" > </a> ');
                                    }
                                     if($("#google").val() != ''){
                                    $( "#social" ).append('<a href="'+$("#google").val()+'" id="ligoogle"><img src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/images/icons/google-plus.png"  > </a>');
                                    }
                                    if($("#twiter").val() != ''){
                                    $( "#social" ).append('<a href="'+$("#twiter").val()+'"  id="litwitter"><img src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/images/icons/twitter.png"  > </a> ');
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
                             Or Upload A New CTA Report Limit 20MB : <br>
                             <input id="file_name_video" name="file_name" type="file" value="" >
                             <div class="progress" style="display:none;">
                                 <div class="bar" style="    background: red;    width: 0%;    height: 5px;    margin-top: 10px;"></div >
                                <span class="percent">0</span>%
                            </div>
                             <input id="cus_or_video"  type="hidden" value="" >
                             <input id="extend_video"  type="hidden" value="" >
                             <?php echo JHtml::_( 'form.token' ); ?>
                          <br> <br> <div class="warning" style="color: red;display: none;" > Uploading ... </div>
                          <br> <br>
                          <ul class="tabs-cta" >
                              <li class="cta-tab-1 acctive" onclick="changectatab('1');" >Text</li>
                              <li class="cta-tab-2" onclick="changectatab('2');" >Image</li>
                          </ul>
                          <div class="edit-text" >                                
                                  <label class="addlabel">  Edit Title: </label>
                                  <input class="adreinput" type="text" id="textctatit" value="" >
                                  <label class="addlabel">   Name Button: </label>
                                  <input class="adreinput" type="text" id="textbutonctatit" value="Start" maxlength="12" >
                                  <table>
                                      <tr>
                                          <td>
                                                    Color Background: <br>
                                                    <input type="text" id="cobactatit" class="jscolor" value="fff"  >
                                                    Button Background: <br>
                                                    <input type="text" id="btcobactatit" class="jscolor" value="FF9b0b"  >
                                          </td>
                                          <td>
                                                     Color Text: <br>
                                                    <input type="text" id="cotectatit" class="jscolor" value="000"  >

                                                    Button Color Text: <br>
                                                    <input type="text" id="btcotectatit" class="jscolor" value="fff" >
                                          </td>
                                      </tr>
                                  </table>
                       
                       
                          </div>
                          <div class="edit-image" style="display: none;" >
                              
                              <div id="vanilla-uploadadvisor"></div>
                              <br>
                              <button type="button" class="vanilla-result">Crop</button>
                              <script>    
                                  
                                $(document).ready(function () {
                                        var vanilla = new Croppie(document.getElementById('vanilla-uploadadvisor'), {
                                                viewport: { width: 160, height: 200 ,type: 'square' },
                                                boundary: { width: 160, height: 200 }
                                        });
                                        vanilla.bind({ url: $("#seminar img").attr('src') });
                                        $('.vanilla-result').click(function(e) {
                                                        vanilla.result({
                                                                type: 'canvas',
                                                                size: 'viewport'
                                                        }).then(function (src) {                                                               
                                                             $.ajax({
                                                                  url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savepng",
                                                                  type: "POST",
                                                                  data: "imgcode="+src                                                                      
                                                             }).done(function( data ) {
                                                                  $("#seminar img").attr('src',data); 
                                                                  ajaxsave();  setTimeout(function(){ ajaxsave();},5000);
                                                             });
                                                        });
                                        });
                                      
                                
                                });
        
                              </script>
                              
                              
                              <div><br>Upload Image : </div><br>
                              <input value="" type="file"  id="upload-cta"  onchange="$('#upload-demo-cta').show();$('#vanilla-uploadadvisor,.vanilla-result').remove(); "  name="file" >
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
                                <ul class="tabs-cta">
                                    <li class="addr-tab-1 acctive" onclick="changeaddrtab('1');">Address 1</li>
                                    <li class="addr-tab-2" onclick="changeaddrtab('2');">Address 2</li>
                                </ul>
                                
                             <ul class="advisordetails infoaddress1" style="   margin: 0;    padding: 0;    border: none;"> 
                                <li>
                                        <label class="addlabel" >Firm<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" id="address_firm" value="<?php echo $this->address->firm ?>" >
				</li>	
                                <li>
					<label class="addlabel" >Email<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" id="address_from_email" value="<?php echo $this->address->from_email ?>" >
				</li>	
                                 <li>
					<label class="addlabel" >URL<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" id="address_url" value="<?php echo $this->address->url ?>">
				</li>	
                                <li>
					<label class="addlabel" >Address<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" id="address_address1" value="<?php echo $this->address->address1 ?>" >
				</li>				
				<li>
					<label class="addlabel" >Address 2</label>						
					<input type="text" size="30" class="inputbox adreinput" id="address_address2" value="<?php echo $this->address->address2 ?>" >
				</li>				
				<li>
					<label class="addlabel" >Phone</label>	
                                        
                                        
                                        <?php
                                          
                                        
                                            preg_match_all('!\d+!', $this->address->phone, $telformat);
                                            $strtelformat = '';
                                            foreach ( $telformat[0] as $tela ) {
                                              $strtelformat .= $tela;
                                            }                      
                                            $telformat =  "(".substr($strtelformat, 0, 3).") ".substr($strtelformat, 3, 3)."-".substr($strtelformat,6);
                                        
                                        ?>
                                        <input type="text" size="30" class="inputbox adreinput" data-mask="(___) ___-____" id="address_phone" value="<?php echo $telformat ?>" >
                                        
                                        
				</li>				
				<li>
					<label class="addlabel" >City<span class="star"> *</span></label>						
					<input type="text" size="30" class="inputbox adreinput" id="address_city" value="<?php echo $this->address->city ?>" >
				</li>	
                                <li>
					<label class="addlabel" >State<span class="star"> *</span></label>						
					<input type="text" size="30" id="address_state" class="inputbox adreinput" value="<?php echo $this->address->state ?>" >
				</li>	
				<li>
					<label class="addlabel" >Zip<span class="star"> *</span></label>						
					<input type="text" size="30" id="address_zip" class="inputbox adreinput" value="<?php echo $this->address->zip ?>" >
				</li>				
							
				
			</ul>
                             
                        <ul class="advisordetails infoaddress2" style="   margin: 0;    padding: 0;    border: none; display:none;">
                                <li>
					<label  >Use Address 2</label>						
                                        <input type="checkbox" size="30" id="useaddress2" >
				</li>	
                                <li>
					<label class="addlabel" >Address</label>						
					<input type="text" size="30" class="inputbox adreinput" id="address2_address1" value="<?php echo $this->address->second_address1 ?>" >
				</li>				
				<li>
					<label class="addlabel" >Address 2</label>						
					<input type="text" size="30" class="inputbox adreinput" id="address2_address2" value="<?php echo $this->address->second_address2 ?>" >
				</li>	
                                <li>                                  
					<label class="addlabel" >Phone</label>	
                                       
                                        <?php
                                          
                                        
                                            preg_match_all('!\d+!', $this->address->second_phone, $telformat);
                                            $strtelformat = '';
                                            foreach ( $telformat[0] as $tela ) {
                                              $strtelformat .= $tela;
                                            }                      
                                            $telformat =  "(".substr($strtelformat, 0, 3).") ".substr($strtelformat, 3, 3)."-".substr($strtelformat,6);
                                        
                                        ?>
                                        <input type="text" size="30" class="inputbox adreinput" data-mask="(___) ___-____" id="address2_phone" value="<?php echo $telformat ?>" >
                                        
                                </li>
                                <li>
					<label class="addlabel" >City</label>						
					<input type="text" size="30" class="inputbox adreinput" id="address2_city" value="<?php echo $this->address->second_city ?>" >
				</li>	
                                <li>
					<label class="addlabel" >State</label>						
					<input type="text" size="30" id="address2_state" class="inputbox adreinput" value="<?php echo $this->address->second_state ?>" >
				</li>	
				<li>
					<label class="addlabel" >Zip</label>						
					<input type="text" size="30" id="address2_zip" class="inputbox adreinput" value="<?php echo $this->address->second_zip ?>" >
				</li>	
                        </ul>       
		</fieldset>  
                             <div style="display:none;">
                                 <script>
                                        
                                        Array.prototype.forEach.call(document.body.querySelectorAll("*[data-mask]"), applyDataMask);

function applyDataMask(field) {
    var mask = field.dataset.mask.split('');
    
    // For now, this just strips everything that's not a number
    function stripMask(maskedData) {
        function isDigit(char) {
            return /\d/.test(char);
        }
        return maskedData.split('').filter(isDigit);
    }
    
    // Replace `_` characters with characters from `data`
    function applyMask(data) {
        return mask.map(function(char) {
            if (char != '_') return char;
            if (data.length == 0) return char;
            return data.shift();
        }).join('')
    }
    
    function reapplyMask(data) {
        return applyMask(stripMask(data));
    }
    
    function changed() {   
        var oldStart = field.selectionStart;
        var oldEnd = field.selectionEnd;
        
        field.value = reapplyMask(field.value);
        
        field.selectionStart = oldStart;
        field.selectionEnd = oldEnd;
    }
    
    field.addEventListener('click', changed)
    field.addEventListener('blur', changed)
}
                                        </script>
                        <br>   
                        Enable :                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                             </div>               
                        <button type="button" class="button-blu" id="address-save" >Save</button>                       
                        <script>                        
                            $( "#address-save" ).click(function() {
                               var editor_text = $('#address-text_ifr').contents().find('body').html();                         
                              
                                    //$("#address-content").empty().html( editor_text );
                                  if ($('#address_phone').val() != '(___) ___-____' ){   
                                    var tel =  '<br>Tel: '+$('#address_phone').val();
                                  } else {
                                    var tel = '';
                                  }
                                  
                                if ( $("#useaddress2").is(':checked') ){      

                                      if ($('#address2_phone').val() != '(___) ___-____' ){   
                                            var tel2 =  '<br>Tel: '+$('#address2_phone').val();
                                      } else {
                                            var tel2 = '';
                                      }
                                      if ($("#address2_address2").val() != ''){
                                          var address2 = ', '+$("#address2_address2").val();
                                      }else {
                                          var address2 = '';
                                      }

                                      var getseconadd =  '<td id="secondaddress" style="padding-left: 40px;font-size:12px;    min-width: 160px;"><p style=" margin-top: 0px;"></p>'+$("#address2_address1").val()+address2+' <br> '+$("#address2_city").val()+' '+$("#address2_state").val()+' '+$("#address2_zip").val()+' '+tel2+'<br><p></p> </td>';
                                }else{
                                      var getseconadd = '';
                                }        

                                   
                                   
                                   
                                   
                                   
                                   var htm = ' <table style="min-width: 260px;" border="0" cellspacing="0" cellpadding="0" class="mce-item-table" data-mce-selected="1"><tbody><tr valign="top"><td><p style="font-size: 12px;" data-mce-style="font-size: 12px;"><strong>'+$('#address_firm').val()+'</strong><br><span id="topaddress">'+$('#address_address1').val()+' '+$('#address_address2').val()+'<br>'+$('#address_city').val()+' '+$('#address_state').val()+' '+$('#address_zip').val()+tel+'<br>Email: </span> <a href="mailto:'+$('#address_from_email').val()+'" data-mce-href="mailto:'+$('#address_from_email').val()+'">'+$('#address_from_email').val()+'</a><br><a href="'+$('#address_url').val()+'" target="_blank" data-mce-href="'+$('#address_url').val()+'">'+$('#address_url').val()+'</a></p></td>'+getseconadd+'</tr></tbody></table> ';
                                   $("#address-content").empty().html( htm ); 
                                   $('#address').show();                                    
                             
                                //ajaxsave();
                                   $("#global-save").trigger( "click" ); 
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
                              Intro: (limit 120 character)<br>
                              <textarea id="temintro-edit-text" maxlength="120" style="    width: 95%;    min-height: 74px;"></textarea>
                         <div style="display:none;">
                             <br>
                                Enable :

                                <input type="checkbox" name="position2" checked="checked" value="Yes">
                         </div>
                                <button type="button" class="button-blu" id="temintro-save" >Save</button>
                               
                                <script>
                       
                            $( "#temintro-save" ).click(function() {
                               
                               if ( $("#form14 input[type='checkbox']:checked").val() == 'Yes' ){
                                
                               // val = $("#temintro-edit-text").val().replace(/\r\n|\r|\n/g,"<br />");
                                 val = $("#temintro-edit-text").val();
                                   $(".intro").empty().html( val.substring(0, 120) );
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
                        <label class="addlabel"> Address: </label>
                        <input class="adreinput" value="<?php echo $this->address->address1.' '.$this->address->address2.','.$this->address->city ?>" type="text" id="map-edit-img" placeholder="address, city, state " >
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
                                                  $( "#map" ).append('  <img data="'+$("#map-edit-img").val()+'"  zoom="'+$(".single-slider" ).val()+'" src="https://maps.googleapis.com/maps/api/staticmap?center='+lat+','+lng+'&zoom='+zoom+'&size=187x187&maptype=roadmap&markers=color:blue%7Clabel:S%7C'+lat+','+lng+'&key=AIzaSyBNJIeTGgrFxcrTgo0YKZoj7Y-T7IYapS8&zoom=10"    width= "100%" > ');             
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
                            <div id="checkcloud" >
                                <?php $cloud =  str_replace('<li>', '<input type="checkbox" name="cloudcheck[]" class="cloudcheck"  value="" checked="checked" />', $this->cloud);
                                    $cloud =  str_replace('</li>', '', $cloud);
                                    echo $cloud;
                             ?>
                            </div>
                             
                             <div style="display:none;">
                        &nbsp; Enable :
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                             </div>
                        <button type="button" class="button-blu" id="cloud-save" >Save</button>
                       
                        <script>
                            
                            $( "#cloud-save" ).click(function() {                  
                                                       
                                  
                                    
                                    $("#checkcloud .cloudcheck").each(function(){
                                        if ( $(this).is(':checked') ){
                                           $( "#cloud-tag a:contains('"+$(this).val()+"')" ).show();
                                        }else{
                                           $( "#cloud-tag a:contains('"+$(this).val()+"')" ).hide();
                                        }
                                    });
                               
                                ajaxsave();
                            });
                            $(document).ready(function(){
                                  var i = 0;
                                  $("#checkcloud a").each(function(){                                   
                                      $("#form12 .cloudcheck:eq("+i+")").val($(this).text());
                                      i++;
                                  });
                                  
                                  $("#cloud-tag a").each(function(){ 
                                      if ($(this).css('display') == 'none'){
                                           $("#form12 .cloudcheck[value='"+$(this).text()+"'] ").prop('checked',false);
                                      }
                                     
                                      
                                  });
                                  
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
                        <div class="text1" > events <div style="float:right;" ><input id="openserminar" data-size="small"  class="btch"  ref="form13,serminar1" type="checkbox"  name="position1" checked="checked" value="Yes"></div></div>                      
                        
                    </div>
                      <div id="serminar-edit" class="block-3" style="display: none;">                        
                         <form id="form13" > 
                            <div style="display:none;">
                             <label class="addlabel" >Title<span class="star"> *</span></label>						
                             <input type="text" size="30" class="inputbox adreinput" id="serminar_title" value="" >
                             
                             <label class="addlabel" >Link<span class="star"></span></label>						
                             <input type="text" size="30" class="inputbox adreinput" id="serminar_link" value="" >                           
                            
                             
                             <br>           
                             <br>
                             <div id="upload-demo-serminar" style="display:none;"></div>    
                             
                      
                            <br> Image Upload : <br>
                            <input value="" type="file"  id="upload-serminar" onchange="$('#upload-demo-serminar').show();"  name="file" >
                         <br>   <br> <input type="button" onclick="clearctaimagewebmina();" value="Clear Image" >
                           
                                Enable :          <input type="checkbox" name="position2" checked="checked" value="Yes">  
                           </div>
                        <button type="button" class="button-blu" id="serminar-save" >Save</button>                       
                                         
                        </form>
                    </div> 
                    <div style="display:none;" id="financial-control" class="block-2" onclick="showedit('financial');window.scrollTo(0, $('#financial').offset().top);" >
                        <div class="image1" >  <i class="fa fa-dollar fa-3x"></i> </div>
                        <div class="text1" > financial planning <div style="float:right;" ><input id="openfinancial" data-size="small"  class="btch"  ref="form6,financial" type="checkbox"  name="position1"  value="Yes"></div></div>                      
                        
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
                    <div style="display:none;" id="investment-control" class="block-2" onclick="showedit('investment','invest');window.scrollTo(0, $('#investment-control').offset().top);" >
                        <div class="image1" > <i class="fa fa-cogs fa-3x"></i>  </div>
                        <div class="text1" > Investment Indexes  <div style="float:right;" ><input id="openinvestment" data-size="small"  class="btch"  ref="form5,invest" type="checkbox"  name="position1"  value="Yes"></div></div>                      
                        
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
             
              <div class="col-2" style="display:none;" >  
                  <h2 style="     border-bottom: 1px solid #ccc;    background: #fff;    margin-top: 0px;    padding: 20px 50px;">Template Name: <span class="nametemplate"><?php if( $this->optioncolor->tplname == '' ){ echo $this->changetemps_lauout; } else { echo $this->optioncolor->tplname; }?></span> <span class="nametemplateedit"><i class="fa fa-pencil-square-o fa-3x" style="color: #b72121;" aria-hidden="true"></i></span>  <?php    $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$this->filen.'_'.$this->changetemps_lauout.'.html' ;  $date =  date ("d M Y ", filemtime($directory)); ?> <span style="float:right;" ><?php echo $date; ?></span> </h2>
                 <?php echo $this->maildata; ?>                 
            </div>
            <div id="dasjdasj" style="display: none;">
                <div style="    display: table;    padding: 10px;">
              <?php $this->cloud =  str_replace('<li>', '', $this->cloud);
                    $this->cloud =  str_replace('</li>', '', $this->cloud);
                    echo $this->cloud;
                    ?>
                </div>
            </div>                  
         
        </div>
       
      <div id="popup2_open"  style="overflow-y: auto;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;    width: 1000px;    height: 700px;" >
            <h2 style="margin-bottom: 0px;" >Open Enewsletter:</h2>
             
            <span onclick=" $('#popup2_open').bPopup().close();" class="btclose" ><img style="width: 32px;height: 32px" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/close-window-xxl.png" /></span>
              
              
              
              <div class="divchothumtm2" style="width:100%;float: left;margin-bottom:20px; height:550px; overflow-y: auto;    margin-top: 5px;">
                  <style>
                      
                      .divchothumtm2   .dataTables_filter {
                          display:none;
                      }
                      .divchothumtm2 .dataTables_length {
                          margin-bottom:20px;
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
                         oTable.fnSort( [ [3,'desc'] ] );          
		});
                </script>
                  
                  <table id="articletable4" style="text-align: center;width: 100%;margin-top: 50px;">
                          <thead style="background: #f0f0f0;">
                            
                                  <td  style=" width: 10%; ">  </td>   
                                     <td  style=" width: 35%; "> Name </td>       
                                       <td  style=" width: 15%; "> User </td>       
                                  <td   style=" width: 15%; "  >Modified  Date </td>
                               
                                  <td  style=" width: 25%; " >  </td>
                               
                            
                          </thead>
                 
                  
                  <tbody>
                   <?php 
                   
                 
                   foreach ($this->tems_user1 as $r){   
                            foreach ($vowels as $kp){
                                $strp = strpos($r,$kp); 
                                  if ($strp !== false){
                                      
                                      $this->tems_user2[] =   $r ;                                               
                                 }
                            }
                   }        
                        $this->tems_user1  = $this->tems_user2;      
                                
                   foreach ($this->tems_user1 as $r){   
                       
                         $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                            $ktmp[filemtime($directory)] = $r ;     
                   }
                   ksort($ktmp);
                 
                   $this->tems_user1 = $ktmp;
                   
                   
                   $jk == 1; 
                   foreach (  array_reverse($this->tems_user1) as $r){   
                       
                                 $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                                     if (file_exists($directory)) {
                                           $date =  date ("d M Y ", filemtime($directory));
                                           $date = '<span style="display:none;" >'.filemtime($directory).'</span>'.$date;
                                           
                                           foreach ($vowels as $kp){
                                             $strp = strpos($directory,$kp); 
                                             if ($strp !== false){
                                                 $kp =  str_replace ('_','',$kp);
                                                 $nametemplate =  jfactory::getuser($kp)->username;
                                                 break;
                                             }
                                           }
                                           
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
                      
                      <td style="text-align: left;background: #fff!important;width: 35%; " >
                          <?php  
                          $r112 = '';
                          foreach ( $this->allnametemplate  as $kkh){
                              $kkh->filen = str_replace('.html', '', $kkh->filen);
                              if ($kkh->filen == $r){
                                  $r112 = $kkh->tplname;
                                  break;
                              }
                          }
                          if ($r112 != ''){
                               echo $r112;
                          }else{
                               $str =  str_replace($vowels, " : ", $r);    $str = explode(':', $str); echo $str[1];
                          }
                         
                          ?>
                      </td>
                       <td  style="background: #fff!important;width: 15%;" >
                        <?php echo $nametemplate; ?>
                      </td>
                      <td  style="background: #fff!important; width: 15%;" >
                          <span style="color:blue"  ><?php echo $date ?></span>
                      </td>
                      <td style="background: #fff!important;width: 25%;" >
                          <button onclick=" $('#open_temps').val('<?php echo $db->escape($r); ?>');opentemp();" style="    float: right;     margin-top:5px;  width: 40%; margin-right: 15px;   color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer;">Open >></button>
                       
                       <button onclick=" $('#open_temps').val('<?php echo $db->escape($r); ?>');deletetemp();" style="    float: right;     margin-top: 5px;  width: 40%; margin-right: 15px;   color: white;    background: red;    padding: 10px 10px 10px 10px;    border-radius: 10px;cursor: pointer;">Delete</button>
                       
                      </td>
                       
                       
                      
                  </tr>
                   <?php } ?>
                  </tbody>   </table>
                 
              </div>
          
                        <select style="display:none ;border: 1px solid #ddd;padding: 7px; border-radius: 5px;        width: 185px;" id="open_temps" >
                                <option  value="">Select - Layout</option> 
                            <?php
                            
                                
                                foreach ($this->tems_user1 as $r){                                      
                                     $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;    
                                     if (file_exists($directory)) {
                                           $date =  date ("F d Y ", filemtime($directory));
                                        }
                                ?>
                                    <option  <?php if ( $this->idt.','.$this->changetemps_lauout == str_replace($vowels, ',', $r) ) {echo 'selected="selected"'; } ?> 
                                        value="<?php echo $db->escape($r); ?>">
                                     <?php 
                                     $str =  str_replace($vowels, " : ", $r);
                                     $str = explode(':', $str);
                                     echo $str[1].' - '.$date ;
                                     ?> 
                                    </option> 

                                <?php 
                            }
                            ?>                              
                        </select>
              
                  
                      
        </div>

<script>
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

function checkCookie(cokiname) {
	var username = getCookie(cokiname);
	if (username!="") {
        return true;
    }
	return false;
}//func

	
	var last_coookname;

    function addnameauto(ele, a){
         $('.newtemwam').remove();
		last_coookname = 'optionf_'+ele.value;
		
		//console.log(ele.value);
		
		if (!checkCookie(last_coookname)) {
			setCookie(last_coookname,ele.value,365);
		}
		
		var theval = getCookie(last_coookname);
		console.log(theval);
		jQuery('.chothumtm').removeClass('active');
		jQuery('.chothumtm').each(function(index, element) {
			if ($(element).attr('rel')==theval) {
				console.log('found');
				$(element).addClass('active');
				$('#sasasasa').val(theval); 
				
				//dont break
				//return false;
			}//if
		
		});
		
		
		
		var d = new Date();
        if (a == 'Your Content') {           
            $('.tgroup3').show();$('.tgroup1,.tgroup2').hide();
        }
        if (a == 'Weekly Investment Update') {
            $('.tgroup2, .tgroup3').hide();$('.tgroup1').show();
        }
        if (a == 'Weekly Financial Planning Update') {
            $('.tgroup2, .tgroup3').hide();$('.tgroup1').show();
        }
        if (a == 'Weekly Investment-Fin Planning Update') {
            $('.tgroup2, .tgroup3').hide();$('.tgroup1').show();
        }
        if (a == 'Your Content and Investment-Fin Planning Update') {
            $('.tgroup2').show();$('.tgroup1, .tgroup3').hide();
        }
        if (a == 'Select Three Articles') {
            $('.tgroup2').show();$('.tgroup1, .tgroup3').hide();            
            $('#popup10_open').bPopup();
         
        }
        
        if (a == 'Weekly Video') {
          <?php  if( $this->com_params->get('inputyoutube') != '' ){ ?>
               $('.tgroup2, .tgroup3').hide();$('.tgroup1').show();         
          <?php    }else { ?>
               $('.tgroup2, .tgroup3').hide();$('.tgroup1').hide();    
               $(".bfformat").after('<span class="newtemwam" style="color:red;"  > Please input channel in settings tab.</span>');
          <?php } ?>
            
        }
        
        
        
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
	


jQuery(document).ready(function($){

	$('.chothumtm').click(function(e) {
		e.preventDefault();
		var val = $(this).attr('rel');
		$('#sasasasa').val(val); 
		$('.chothumtm').removeClass('active');
		$(this).addClass('active');
		
		console.log(val);
		
		setCookie(last_coookname,val,365);
		
		
		getCookie(last_coookname);
		
		
	});
	
	//$('#sasasasa').change(function() {
	//	alert($(this).val());
	//});
});

</script>
        <div id="popup3_new"  style="width:600px;height:640px;overflow-y: hidden;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;     " >
              <span onclick=" $('#popup3_new').bPopup().close();" class="btclose" ><img style="width: 32px;height: 32px" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/close-window-xxl.png" /></span>
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
                            <input type="radio" name="optionf" value="1" onclick="addnameauto(this, 'Your Content');"   /> Your Content <br>
   <input type="radio" name="optionf" value="2" onclick="addnameauto(this, 'Weekly Investment Update'); "  />  Weekly Investment Update <br>
   <input type="radio" name="optionf" value="7" onclick="addnameauto(this, 'Weekly Video'); "    /> Weekly Video<br>
   <input type="radio" name="optionf" value="3" onclick="addnameauto(this, 'Weekly Financial Planning Update'); "    /> Weekly Financial Planning Update <br>
   <input type="radio" name="optionf" value="4" onclick="addnameauto(this, 'Weekly Investment-Fin Planning Update');"   /> Weekly Investment and Planning Update <br>   
   <input style="" type="radio" name="optionf" value="5" onclick=" addnameauto(this, 'Your Content and Investment-Fin Planning Update');"  /> 
 Your Content with Weekly Investment Update and Weekly Financial Planning Update <br>    
 <input style="float: left;" type="radio" name="optionf" value="6" onclick=" addnameauto(this, 'Select Three Articles');"  />  Select Three Articles <a onclick="$('#popup10_open').bPopup();" style="cursor: pointer;" >Select Content </a><br> 
                            </div>
                  </div>
                      <br><br>
                      <span class="bfformat" >Format:</span><br>
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
                               height: 210px;
                            margin-top: 10px;
                      }
                    </style>
                      <div class="divchothumtm" style="width:100%">
                            <div class="tgroup1" style="display: none;" >
                                
                              <div rel="weeklyupdate" class="chothumtm aaa3" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum3.jpg" /> <br><div style="text-align: center;    margin-top: 10px;">Right Widgets</div>  </div>
                              
                              <div rel="weeklyupdateright" class="chothumtm aaa5" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum2.jpg" /> <br><div style="text-align: center;    margin-top: 10px;">Left Widgets</div>  </div>                               
                               
                              <div rel="weekly" class="chothumtm aaa7" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum5.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">Single Column</div>  </div>
                                
                            </div>
                            <div class="tgroup2" style="display: none;" >
                                <div rel="enewsletter"  class="chothumtm  aaa1" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum1.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Right Widgets </div> </div>

                               <div rel="enewsletter_site2" class="chothumtm aaa2" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum2.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Left Widgets </div> </div>

                             

                               <div rel="enewsletter_threecol" class="chothumtm aaa4" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum4.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">3 Columns </div>  </div>
                               <div rel="massemail" class="chothumtm aaa6" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum5.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">Single Column </div>  </div>
                            </div>
                          
                            <div class="tgroup3" style="display: none;" >
                                <div rel="enewsletter" class="chothumtm  aaa1" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum1.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Right Widgets </div> </div>

                               <div rel="enewsletter_site2" class="chothumtm aaa2" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum2.jpg" /> <br> <div style="text-align: center;    margin-top: 10px;">Left Widgets </div> </div>
                               
                               <div rel="massemail" class="chothumtm aaa6" style="width:23%;margin-right: 1%;float: left;cursor: pointer;" ><img style="width: 100%;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/thum5.jpg" /> <br>  <div style="text-align: center;    margin-top: 10px;">Single Column </div>  </div>
                               
                                
                            </div>
                        </div>
                    
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
        <div id="popup1"  style="width:100px;height:70px;overflow-y: hidden;overflow-x: hidden;display: none;    background: white;    padding: 66px;    border: 5px #999 solid;    border-radius: 10px;    width: 231px;    height: 195px;font-size: 20px;" >
            <span class="button b-close" style="background:red;"><span>X</span></span>
                  Click" Save" to save this email newsletter layout as a template.<br>
                 Click "Cancel" not to save the change you made to the template layout.  <br>
                 
            <br><br>
               <button   onclick="subhtml();" id="adform-button" type="button" style=" display: none;    border: none;    text-align: center;    margin: 0;    padding: 15px;    background:red;    color: #fff;    cursor: pointer;    border-radius: 4px;float: left;    min-width: 150pxpx;    font-size: 23px;margin-right: 30px; " >Save</button>
               
               <button class="b-close"  onclick="changeoldlayout();" id="adform-button-cancel" type="button" style="   border: none;    text-align: center;    margin: 0;    padding: 15px;    background: red;    color: #fff;    cursor: pointer;    border-radius: 4px;float: left;    min-width: 150pxpx;    font-size: 23px; " >Cancel</button>
        </div>
        <textarea id="htmlcodeold" style="display: none;"> </textarea>
        <div id="popup"  style="width:1000px;height:700px;overflow-y: auto;overflow-x: hidden;" >
            <span class="button b-close" style="background: red"><span>CLOSE</span></span>
            <span onclick="getcontent();" class="button b-close choicearticle" style="right: 100px; background-color: #38a924;"><span>SAVE</span></span>
  
            <br>
            
            <div style="text-align: right;display: none;" > <input id="getnamefultext" type="checkbox"  value="1" name="ddddddd"   />Get Intro ( Show the First Paragraph Only ) </div>
            <br>
            
          <div id="tabs" >
	<ul>	
       <?php if ( $this->optionf != 2 ) {  ?>     <li style="width: 49%;" ><a href="#tabs-2" class="alltab"> Weekly Financial Planning Update </a></li> <?php }?>
	  <?php if ( $this->optionf != 3  ) {  ?> 	<li style="width: 49%;"  ><a href="#tabs-4" class="alltab">Weekly Investment Update </a></li>  <?php }?>
              <!--  <li><a href="#tabs-3" class="alltab">Custom Content</a></li>	-->	
	</ul>
	
              <div style=" <?php if ( $this->optionf == 2 ) { echo 'display:none;'; }?>"   id="tabs-2" >
		<div class="width-100" style="margin-bottom:15px;">
			
                    <table class="adminlist" id="articletable" style="width:100%">
				<thead>
					<tr>
						<th width="1%">
							
						</th>
						<th class="left1">
							TITLE
						</th>
            <th class="center" width="10%">
							IMAGE
						</th>
            <th class="left1">
                                                        INTRO 
						</th>
						<th class="left1">
							DATE
						</th>
						
					</tr>
				</thead>
				<tbody>
				<?php 
				$weekly_items = array();
				
				foreach ($this->article as $i => $item) :
						//skip weekly
						if ( strpos($item->keywords, 'weekly update')!== false ) {
							$weekly_items[] = $item;
							continue;
						}//if
				
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array($item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
                                                               
                                                                
                                                                
								echo '<input class="checkall" id="id_'.$item->article_id.'" type="checkbox" '.$checked.' onclick="savecid('.$item->article_id.');" value="'.$item->article_id.'" name="cid[]" />';?>
						</td>
					
          <td class="left1" >
						<?php 
              // Create article link
					  $articlelink='';
			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;
					  
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$item->article_id, trim($custome_url));
					  }
          			  else
          			  {
              				if($item->type == 'Featured News') 
              				{
              				  $articlelink= JURI::base().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::base().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }
                  
                  echo '<a href="'.$articlelink.'" target="_blank">'.$item->article_title.'</a>';
            ?>
						</a>
						</td>
					
					 <td class="center" style="text-align:center;" >
							<?php
                if(in_array($item->article_id,$showimages)){
  								$checked = 'checked="checked"';
  							}else{
  								$checked = '';
  							}
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
              ?>
						</td>
            
            <td class="left1" style="text-align:center;" >
						<?php echo '<input id="getin_'.$item->article_id.'" class="checkall" type="checkbox"  value="'.$item->article_id.'" name="getin[]" onclick=""  />'; ?>
						</td>
						
						<td class="left1" style="text-align:center;" >
						<?php echo date('m/d/y',strtotime($item->created)); ?>
						</td>
					</tr>						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-2-->
            <div style=" <?php if ( $this->optionf == 3 ) { echo 'display:none;'; }?>"  id="tabs-4" >
		<div class="width-100" style="margin-bottom:15px;">			
			<table class="adminlist" id="articletable2" style="width:100%">
				<thead>
					<tr>
						<th width="1%">							
						</th>
						<th class="left1">
							TITLE
						</th>
           				<th class="center" width="10%">
							IMAGE
						</th>
            			<th class="left1">
							GET INTRO 
						</th>
						<th class="left1">
							DATE
						</th>
						
					</tr>
				</thead>
				<tbody>
				<?php
                                $abc = 1;
                                foreach ($weekly_items as $i => $item) :
				$scr = '';
                                    ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array($item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
                                                                if ($abc == 1 ){
                                                                  //  $checked = 'checked="checked"';
                                                                  //  $scr = " <script> savecid('$item->article_id'); </script>";
                                                                }
								echo '<input class="checkall" id="id_'.$item->article_id.'" type="checkbox" '.$checked.' onclick="savecid('.$item->article_id.');" value="'.$item->article_id.'" name="cid[]" />'.$scr;?>
						</td>
         				<td class="left1" >
						<?php 
              		// Create article link
					  $articlelink='';
			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;
					  
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$item->article_id, trim($custome_url));
                                                
					  }
          			  else
          			  {
              				if($item->type == 'Featured News') 
              				{
              				  $articlelink= JURI::base().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::base().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }                  
                  echo '<a href="'.$articlelink.'" target="_blank">'.$item->article_title.'</a>';
            ?>
						</a>
						</td>					
                                                <td class="center" style="text-align:center;">
							<?php
                                                         if ($abc == 1 ){
                                                                    $checked = 'checked="checked"';
                                                                    $abc++;
                                                                }
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
                                                                
              ?>
						</td>            
            <td class="left1" style="text-align:center;" >
						<?php echo '<input id="getin_'.$item->article_id.'" class="checkall" type="checkbox"  value="'.$item->article_id.'" name="getin[]" />'; ?>
						</td>						
						<td class="left1" style="text-align:center;" >
						<?php echo date('m/d/y',strtotime($item->created)); ?>
						</td>					
						
					</tr>						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div>
        </div>
        </div>
        
       <div id="popup6_open"  style="overflow-y: auto;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;    width: 600px;    height: 450px;" >
           
           
           <button  onclick="sendmail();" style="     float: right;       color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer; position: absolute;    right: 15px;    top: 150px;">Send Mail >></button>
             <span onclick=" $('#popup6_open').bPopup().close();" class="btclose" style="    top: 10px;" ><img style="width: 32px;height: 32px" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/close-window-xxl.png" /></span>
         
           <form id="sendform" name="sendform" method="post" action=""  enctype="multipart/form-data"  >
                        
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden"  name="view" value="editletter" >
                         <input type="hidden" id="taskkk"  name="task" value="send" >
                         <input type="hidden"  name="verified_emails" id ='fverified_emails' value="" >
                         <input type="hidden"  name="jform[subject]" id ='fsubject' value="" >
                         <input type="hidden" name="changetemps" value="<?php echo $this->filen; ?>_<?php  echo $this->changetemps_lauout ?>" >
                         <input type="hidden" name="templatename" value="<?php  echo $this->changetemps_lauout ?>" >
                         <input type="hidden" id="apikey" name="apikey" value="<?php echo $this->allsetting->api_key  ?>">
                         <input type="hidden" id="newsletter_api" name="newsletter_api" value="<?php echo $this->allsetting->newsletter_api  ?>">
                         <?php echo JHtml::_('form.token'); ?>
                         
                         
            <h2 style="font-size: 19px;    margin-top: 5px;" >Send Test Email to:</h2>
            <input required="" style="  float: left;      width: 300px;     padding: 9px;" type="email" placeholder="Enter Test Email Address ... "  name="emailtest" value="" >
            
            <button type="submit" onclick="$('#taskkk').val('testmail');" style="          color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer;    position: absolute;        right: 18px;    top: 65px;">Test Mail >></button>
            <br><br>
            <h2 style="font-size: 19px;    margin-top: 15px;    margin-bottom: 5px;" >Select Email List:</h2>
            <div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="grouptable">
				<thead>
					<tr>
						<th width="1%">
							<!--<input type="checkbox" name="checkall_group" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />-->
						</th>
						<th class="left">
							
						</th>

			
					</tr>
				</thead>
				<tbody>
				<?php if ($this->allsetting->newsletter_api == "C" ){ 
                                        foreach ($groups as $i => $group) :
					if($group->contact_count != 0 )  {  ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php 
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
				<?php }   endforeach;  } ?>
                                        
                                        
                               <?php if ($this->allsetting->newsletter_api == "M" ){ 
                                  foreach ($groups as  $group) :?>
					
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
							<?php 
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
		</div>
           </form>                
      </div>        
        <div id="popup7_open"  style="overflow-y: auto;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;    width: 700px;    height: 630px; background: #f0f0f0;" >            
            <iframe src="/index.php?option=com_media&trabt=1&folder=enewsletter&tmpl=component"   width="100%" height="600px;"  frameBorder="0" > </iframe>   
        </div>
        
        <div id="popup11_open"  style="display: none; width:1000px;height:635px;overflow-y: auto;overflow-x: hidden;    background: #fff;    padding: 11px; border: 5px #999 solid;    border-radius: 10px;    padding-top: 30px; " >   
              <span onclick="$('#ifracta').attr('src','');" class="button b-close" style="background-color: #FF0000;"><span>CLOSE</span></span>
              <br>
              <span class="pageloadding"  style="    display: block;    width: 100%;font-size:28px;color:red;text-align:center;" ><img style="margin: 0 auto;width: 615px;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/clock-loading.gif" /> </span>
            <iframe src="" id="ifracta"   width="100%" height="600px;"  frameBorder="0" > </iframe>   
        </div>
        
        <div id="popup12_open"  style="display: none; width:800px;height:500px;overflow-y: auto;overflow-x: hidden;    background: #fff;    padding: 42px; border: 5px #999 solid;    border-radius: 10px; " >   
              <span class="button b-close" style="background-color: #FF0000;"><span>CLOSE</span></span>
              <br>
               <span class="pageloadding"  style="    display: block;    width: 100%;font-size:28px;color:red;text-align:center;" ><img style="margin: 0 auto;width: 615px;height: auto;" src="<?php echo JURI::base(true); ?>/components/com_enewsletter/assets/images/clock-loading.gif" /> </span>
              <iframe src="" id="ifrapoll"   width="100%" height="600px;"  frameBorder="0" > </iframe>   
        </div>
        
    <div id="popup10_open"  style="display: none; width:1000px;height:700px;overflow-y: auto;overflow-x: hidden;    background: #fff;    padding: 42px; border: 5px #999 solid;    border-radius: 10px;  " >
        <span class="button b-close" style="background-color: #FF0000;"><span>CLOSE</span></span>
       
            <span onclick="artitemple();" class="button b-close artitemple" style="right: 100px;    background-color: #38a924;"><span>SAVE</span></span>
            <br>          
            
          <div id="tabs1" >
	<ul>	
            <li style="width: 49%;"  ><a href="#tabs-5" class="alltab" > Weekly Financial Planning Update </a></li> 
            <li style="width: 49%;" ><a href="#tabs-6" class="alltab"  >Weekly Investment Update </a></li>           
	</ul>	
              <div   id="tabs-5" >
		<div class="width-100" style="margin-bottom:15px;">
			
                    <table class="adminlist" id="articletable6" style="width:100%">
				<thead>
					<tr>
						<th width="1%">
							
						</th>
						<th class="left1">
							TITLE
						</th>                                               
                                                <th class="left1">
							IMAGE
						</th>
                                                <th class="left1">
							INTRO
						</th>
						<th class="left1">
							DATE
						</th>						
					</tr>
				</thead>
				<tbody>
				<?php 
				$weekly_items = array();
				
				foreach ($this->article as $i => $item) :
						//skip weekly
						if ( strpos($item->keywords, 'weekly update')!== false ) {
							$weekly_items[] = $item;
							continue;
						}//if
				
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array($item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
								echo '<input class="checkall" id="aid_'.$item->article_id.'" type="checkbox" '.$checked.' onclick="savecidnewtemplate('.$item->article_id.');" value="'.$item->article_id.'" name="cid[]" />';?>
						</td>
					
          <td class="left1" >
						<?php 
              // Create article link
					  $articlelink='';
			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;
					  
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$item->article_id, trim($custome_url));
					  }
          			  else
          			  {
              				if($item->type == 'Featured News') 
              				{
              				  $articlelink= JURI::base().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::base().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }
                  
                  echo '<a href="'.$articlelink.'" target="_blank">'.$item->article_title.'</a>';
            ?>
						</a>
						</td>
            
                                                <td class="left1" style="text-align: center;" >
						<?php echo '<input id="cimag_'.$item->article_id.'" class="checkall" type="checkbox"  value="'.$item->article_id.'" name="cimag[]"  />'; ?>
						</td>
                                                
                                                <td class="left1" style="text-align: center;"  >
						<?php echo '<input id="cgetin_'.$item->article_id.'" class="checkall" type="checkbox"  value="'.$item->article_id.'" name="cgetin[]"  />'; ?>
						</td>                                               
						
						<td class="left1"  style="text-align: center;"   >
						<?php echo date('m/d/y',strtotime($item->created)); ?>
						</td>
					</tr>						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-2-->
            <div  id="tabs-6" >
		<div class="width-100" style="margin-bottom:15px;">			
			<table class="adminlist" id="articletable7" style="width:100%">
				<thead>
					<tr>
						<th width="1%">							
						</th>
						<th class="left1">
							TITLE
						</th>                                               
                                                <th class="left1">
							IMAGE
						</th>
                                                <th class="left1">
							INTRO
						</th>
						<th class="left1">
							DATE
						</th>		
						
					</tr>
				</thead>
				<tbody>
				<?php
                                $abc = 1;
                                foreach ($weekly_items as $i => $item) :
				$scr = '';
                                    ?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array($item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
                                                                if ($abc == 1 ){
                                                                  //  $checked = 'checked="checked"';
                                                                  //  $scr = " <script> savecid('$item->article_id'); </script>";
                                                                }
								echo '<input class="checkall" id="aid_'.$item->article_id.'" type="checkbox" '.$checked.' onclick="savecidnewtemplate('.$item->article_id.');" value="'.$item->article_id.'" name="cid[]" />'.$scr;?>
						</td>
         				<td class="left1" >
						<?php 
              		// Create article link
					  $articlelink='';
			  
					  $valid_format = strpos($custome_url, '{articleid}')===false?false:true;
					  
					  if($valid_format) {
						$articlelink = str_replace('{articleid}' ,$item->article_id, trim($custome_url));
                                                
					  }
          			  else
          			  {
              				if($item->type == 'Featured News') 
              				{
              				  $articlelink= JURI::base().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::base().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
              				}
              				else
              				{
              				  $articlelink='';
              				}
          				
          			  }                  
                  echo '<a href="'.$articlelink.'" target="_blank">'.$item->article_title.'</a>';
            ?>
						</a>
						</td>					
					        
                                                <td class="left1" style="text-align: center;" >
						<?php echo '<input id="cimag_'.$item->article_id.'" class="checkall" type="checkbox"  value="'.$item->article_id.'" name="cimag[]"  />'; ?>
						</td>
                                                
                                                <td class="left1" style="text-align: center;" >
						<?php echo '<input id="cgetin_'.$item->article_id.'" class="checkall" type="checkbox"  value="'.$item->article_id.'" name="cgetin[]"  />'; ?>
						</td>
                                                <td class="left1" style="text-align: center;" >
                                                <?php echo date('m/d/y',strtotime($item->created)); ?>
						</td>					
						
					</tr>						
					<?php  endforeach; ?>
				</tbody>
			</table>			
		</div>
	</div>
        </div>
        </div>        
        <div id="popup14_open"  style="overflow-y: auto;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;    width: 700px;    height: 630px; background: #f0f0f0;" >
            
            <iframe src="/index.php?option=com_enewsletter&view=uploadfile" width="100%" height="600px;"  frameBorder="0" >
                
                
            </iframe>
            
            
        </div>
        
        <?php 
        
            $textmee = $app->getUserState("com_enewsletter.meess",NULL);
            if($textmee != ''){
            ?>
                <div id="popup13_open"  style="display: none; width:400px;height:200px;overflow-y: auto;overflow-x: hidden;    background: #fff;    padding: 42px; border: 5px #999 solid;    border-radius: 10px; " >   
                     <span  class="button b-close" style="background-color: #FF0000;"><span>CLOSE</span></span>
                      <?php  echo '<h3 style=" font-size: 26px;      text-align: center;     color: #666;" >'.$textmee.'</h3>';  ?>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#popup13_open').bPopup();
                    });
                </script>
            <?php             
               $app->setUserState("com_enewsletter.meess",'');
            }        
        ?>
          <script>
              $(document).ready(function() {
                    $("#linkedin , #rss , #facebook , #google , #twiter ").blur(function() { 
                        if (!/^http:\/\//.test($(this).val()) && !/^https:\/\//.test($(this).val()) && $(this).val() != '' && $(this).val() != '#' ) {
                                $(this).val("http://" + $(this).val());
                        }    
                    });                   
               });
          </script>   
        <input value="" id="a1122332" name="content" style="display:none;" />
<?php die; ?>