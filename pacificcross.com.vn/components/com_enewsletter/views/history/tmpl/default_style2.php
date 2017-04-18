<?php
defined('_JEXEC') or die;
$custome_url  =  $this->custome_url;
// Include mailchimp/constant contact library files
require(JPATH_SITE.'/components/com_enewsletter/libraries/constantcontact/src/Ctct/autoload.php');
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/MCAPI.class.php';
require_once JPATH_SITE.'/components/com_enewsletter/libraries/maichimp/inc/config.inc.php'; //contains apikey

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Components\EmailMarketing\Campaign;
use Ctct\Exceptions\CtctException;



// Get api details
$app = JFactory::getApplication();
$api_type = $app->getUserState("com_enewsletter.API");

if($api_type == 'C'){
        $APIKEY  = CONSTANT_APIKEY;
        $ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");  

        // Create object of maichimp and constant contact
        $api = new MCAPI(trim($ACCESS_TOKEN));
        $cc = new ConstantContact(trim($APIKEY));
        $campaign = new Campaign();
        $groupskk = $cc->getLists($ACCESS_TOKEN);
}


if($api_type == 'M'){
	$campaigns = $api->campaigns();
	$campaigns = $campaigns['data'];
	$lists = array();
	$list = $api->lists();

	foreach($list['data'] as $li){
		$lists[$li['id']] = $li['name'];
	}
	
	$campaign_ids = array();
	$campaign_webids = array();
	foreach($campaigns as $cm){								
		$campaign_ids[] = $cm['id'];
		$campaign_webids[$cm['id']] = $cm['web_id'];
		$campaign_listids[$cm['id']] = $cm['list_id'];
	}
	
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
           <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script>
                $(document).ready(function() {
                    oTable = $('#articletable').dataTable( {
                                "aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
                                'iDisplayLength': 50,
                                "aoColumnDefs": [
                                        { "bSortable": false, "aTargets": [ 0] }
                                ],
                                "oLanguage": {
                                  "sSearch": "Search Articles:"
                                }
                        });
                         oTable.fnSort( [ [1,'desc'] ] );          
		});
                function senddata (a,b){
                    $('#viewpage').empty();
                    $('#idh').val(b);
                    
                    $('.resent').remove();
                   // $('<button class="resent" onclick="openedit();" >Resent</button>').insertBefore('#viewpage');
                    $('#viewpage').html($('#'+a).html());
                }
                
                function savef(a){
                     var html_cuscon =  tinyMCE.get("editt").getContent();                      
                     $('#conth').val(html_cuscon);
                     $('#typeh').val(a);
                      $('#forms').submit();
                    
                }
                function showedit(){
                    window.location.href='index.php?option=com_enewsletter&view=editletter';
                }
                function openedit(){
                    var html = $("#viewpage").html();
                    $('.resent').remove();
                     $("#viewpage").html()
                     $('#viewpage').html('<textarea id="editt" > '+html+' </textarea><br><br> <button onclick="savef(1);" > Save </button>  <button onclick="savef(2);"  > Save & Resent </button><br><br>');
                     tinyMCE.remove();   
                     tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#editt',
                                                    height: 300,
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
                                                   
                    
                    
                  
                }
        </script>
        <style>
            .croppie-container {
                padding: 0;margin-left: -35px;
           }
           li{
                   list-style: none;
           }      
           .fa-3x{
                   color: #777;
                   border: 1px solid #ccc;
           }
           #date_fr{
                width:100px!important;
           }
           body{
               background: #fff;
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
                   overflow-y: scroll;
                   background: #fff;
                   overflow-x:hidden;
              }
              .block-2{
                      width: 95%!important;
              }
              #adform{
                     
                          padding-left: 65px;
              }
              .col-2{
                  width: 76%;
                  padding-left: 50px;
                   padding-right: 20px;
                    padding-top: 20px;
              }
              
             
              input[type=text] {
                      width: 96%;
                      font-size: 1em;
                      margin-left: -15px;
                      margin-bottom: 20px;
              }
              .button-blu{
                    
                          background: url('images/save.png');
                        background-repeat: no-repeat;
                        width: 165px;
                        background-size: 165px;
                         color: rgba(0, 0, 0, 0.1);
                                font-size: 1px;
                                height: 50px;
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
              }
              .edittem:hover{
                  border: solid 2px red;
              }
              .dataTables_filter input[type=text] {
                  width: 200px;
                    padding: 4px;
                    margin-left: 7px;
              }
        </style>
        <div class="allpage" >
            <form id="forms" action="index.php?option=com_enewsletter" method="post">
                <input type="hidden" name="option" id="" value="com_enewsletter" />
                <input type="hidden" name="cont" id="conth" value="" />
                <input type="hidden" name="type" id="typeh" value="" />
                <input type="hidden" name="ids" id="idh" value="" />
                <input type="hidden" name="task" value="saveht" />
                <input type="hidden" name="view" value="history" />
                
            </form>
              <input type="hidden" id="valueidartical" value="" >
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
                
                <div class="main-1 left_menu_wrap">
                     <div style="background-color: #e4e4e4 !important; padding-top: 20px;padding-bottom: 20px;padding-left: 30px;"><div class="sqs-navigation-item"><span class="icon icon-projects"></span></div>Enewsletter</div>
                  <!--    <button onclick="window.location.href='index.php?option=com_enewsletter&view=editletter'   " id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 20px 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;width: 82%; " >Go Back</button> -->
                     
                  
                  
                    <div id="block-button" style="    margin-top: 30px;    text-align: left;   "> 
                            <span class="edittem aask1" onclick=" showedit(); "  > NEW</span>
                             <span class="edittem aask2" onclick=" showedit();  " > OPEN</span>
                              <span class="edittem aask3" onclick=" showedit(); "  > HISTORY </span>
                            
                           
                        </div>
                      
                      
                        <!-- <button onclick="window.location.href='index.php?option=com_enewsletter&view=sendmail'   "  id="adform-button1" type="button" style="    border: none;    text-align: center;        margin: -38px -27px 25px;   padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;     font-size: 0;    background: url('images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 255px;    height: 47px;      " >Send Mail</button> -->
                      
                         </br></br>
                         
                    
                   
                </div>
            </div>
             
            <div class="col-2" >  
                
                <div id="viewpage">
                    
                </div>   
                
                 <table id="articletable" style="text-align: center;width: 100%;margin-top: 50px;">
                          <thead style="background: #ccc;">
                              <tr>
                                  <td> Email Subject </td>                              
                                  <td  id="date_fr"  > Date </td>
                                  <td> Send by </td>       
                                  <td> Subscribers Sent To</td>
                                  <td> Campaign Details</td>
                              </tr>
                          </thead>
                          <tbody>
                             
                                 <?php if(count($this->his) > 0){ 
                                     $k=0; foreach ($this->his as $r){
                                         $k++;
                                         $g = $r;
                                 ?>
                                    <tr >
                                        <td style="     text-align: left;    padding-left: 10px;    width: 50%;" >
                                            
                                            
                                            <a onclick=" senddata('data_<?php echo $k; ?>','<?php echo $r->id; ?>')" href="#" > <?php echo $r->subject ?> </a>
                                            
                                            
                                       
                                        </td>                                     
                                        <td><?php echo date('d M y' ,  strtotime($r->dte_send)); ?> </td>
                                        <td><?php if ($r->user_id != ''){ echo JFactory::getUser($r->user_id)->username; } ?></td>
                                        <td> <?php  
                                        
                                         if ($sr->email_id != '' || 1 == 1 ) {
						if($api_type == 'M'){
							$email_ids = array();
							$email_ids = explode(',',$g->email_id);
							$total = 0;
							foreach($email_ids as $ei){
								$members = $api->campaignMembers($ei);
                                                            if ($api->errorCode){
                                                                $total = 'Not Connected';
                                                            }else{
                                                                $total = $total+(int)$members['total'];
                                                            }
								
							}
							
						}else if($api_type == 'C' && $g->email_id != ''){
                  
							$total = 0;
							$campaign->id = $g->email_id;

                                                    try{
							   $campaign_details = $cc->getEmailCampaign($ACCESS_TOKEN, $g->email_id);
                                                    if($campaign_details->status == 'SENT'){
    							$groups_details = $cc->getEmailCampaignSummaryReport($ACCESS_TOKEN, $campaign);									//$listreport = $cc->getContactSummaryReport($ACCESS_TOKEN, $campaign);
                                                      //  print_r();die;
                                                        
                                                       $aasdds = $campaign_details->sent_to_contact_lists;
                                                       
    							$total = $groups_details->sends;
                                                        
    							}else{
    
    								$total = 0;
    							}
    					
                                                            }catch (CtctException $ex){
                                                               $total = 'Different token';
                                                                               $campaign_details = NULL;
                                                            }
                                                } else if($api_type == 'G' && $g->email_id != ''){
                                                        $total = 'GetResponse list';   
                                               } else if($api_type == 'I' && $g->email_id != ''){
                                                  $total = 'Infusionsoft list';
                                                   
                                               }else {
                                                      $total = 'Unknown';
                                               }
														
                                            echo $total;
                                                        
                                         } else {
                                             echo 'Not connected';
                                         }
						?>	
                                        
                                        
                                        
                                        <div id="data_<?php echo $k; ?>" style="display: none;"  >
                                                <h2 style="    width: 70%;    margin: 0 auto;    margin-bottom: 15px;" > <?php echo $r->subject ?> <span style="float:right;"><?php echo date('d M y' ,  strtotime($r->dte_send)); ?> </span></h2>
                                               
                                                <div style="    width: 70%; margin: 0 auto;" >
                                                <?php if (count($aasdds) > 0 ) { ?>
                                                 <b>Lists Sent To:</b> <?php foreach ($aasdds as $rk){
                                                       foreach  ($groupskk as $rk1) {
                                                           if ($rk->id == $rk1->id ){
                                                               echo $rk1->name.' ; ';
                                                               break;
                                                           }
                                                       }
                                                }}?> 
                                                </div>
                                                <br><br>
                                                
 <?php echo  preg_replace("/<style\\b[^>]*>(.*?)<\\/style>/s", "", preg_replace('/<link[^>]*>/i', '',  preg_replace('#<script(.*?)>(.*?)</script>#is', '', $r->content))); ?>  
                                                
                                        
                                        </div>
                                        
                                        </td>
                                        <td> <?php 
						 if ($sr->email_id != '' || 1 == 1  ) {
							if($api_type == 'M' ){
							
								$groups = $api->lists();
								
								$email_ids = array();
								$email_ids = explode(',',$g->email_id);
								
								for($i=0;$i<count($email_ids);$i++){
									if(in_array($email_ids[$i],$campaign_ids)){
										echo '<a href="https://us7.admin.mailchimp.com/reports/summary?id='.$campaign_webids[$email_ids[$i]].'" target="_blank" >';
										echo 'Launch - '.$lists[$campaign_listids[$email_ids[$i]]].'</a><br>';
										
									}else{
                    echo 'Not connected';
                  }
								}
	
							}else if($api_type == 'C' && $g->email_id != '' ) {
                                                           
                                                            
                                                             try{
                                                                        $campaign_details->status = 'SENT';

                                                                        if($campaign_details->status == 'SENT'){

                                                                        $totalsends = $cc->getEmailCampaign($ACCESS_TOKEN, $g->email_id);    

                                                                        $k =1;
                                                                        $list = '';
                                                                        foreach($totalsends->results as $sr){
                                                                                $list .= $k.'. '.$sr->email_address." <br>";
                                                                                $k++;
                                                                        }

                                                                        $displaylist[$g->email_id] = $list;
                                                                            echo '<a href="https://ui.constantcontact.com/rnavmap/evaluate.rnav/?activepage=report.ecampaigns&pageName=report.ecampaigns&action=sent&agent.uid='.$g->email_id.'" target="_blank" >Launch</a>';
                                                                                                        }

                                                                        if(empty($campaign_details)){
                                                                          echo 'Not connected';
                                                                       }	
                                                               }catch (CtctException $ex){
                                                                    echo 'Different token';
                                                               }
							} else{
                                                    echo '';
                                                  }
                                                 }else {
                                             echo 'Not connected';
                                         }	               
							
						?>
                                        </td>
                                    </tr>
                                     <?php }
                                     
                                     } else { ?>
                                     <br>  <span style="display: table;" > No Data </span>
                                 <?php } ?>    
                          </tbody>
                      </table>
                
            </div>
                    
         
        </div>
     
   
     
        
      
      
<?php die; ?>