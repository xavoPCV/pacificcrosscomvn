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
$APIKEY  = CONSTANT_APIKEY;
$ACCESS_TOKEN = $app->getUserState("com_enewsletter.ACCESS_TOKEN");  

// Create object of maichimp and constant contact
$api = new MCAPI(trim($ACCESS_TOKEN));
$cc = new ConstantContact(trim($APIKEY));
$campaign = new Campaign();

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
        <link rel="stylesheet" href="<?php echo JURI::base(); ?>administrator/components/com_enewsletter/css/demo_table.css">
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.bpopup.min.js"></script>
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/demo/prism.css" /> 
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/crop/croppie.css" />
        <link rel="Stylesheet" type="text/css" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.range.css" />
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.range-min.js"></script>
        <script src="<?php echo JURI::base(); ?>components/com_enewsletter/assets/jquery.dataTables.js"></script>
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
		});
                function senddata (a){
                    $('#viewpage').empty();
                    $('#viewpage').html($('#'+a).html());
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
       </style>
       
        <div class="allpage" >
              <input type="hidden" id="valueidartical" value="" >
            <div class="col-1" > 
                <div class="logo">
                  
                </div>
                <div class="main-1">
                    <div class="header1" >
                       Advisor Widgets
                    </div>
                      <button onclick="window.location.href='index.php?option=com_enewsletter&view=editletter'   " id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 20px 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;width: 82%; " >Go Back</button>
                      <table id="articletable" style="text-align: center;">
                          <thead style="background: #ccc;">
                              <tr>
                                  <td> Email Subject </td>                              
                                  <td  id="date_fr"  > Date </td>
                                  <td> Campaign Details	</td>
                                  <td> Subscribers Sent To</td>
                              </tr>
                          </thead>
                          <tbody>
                             
                                 <?php if(count($this->his) > 0){ 
                                     $k=0; foreach ($this->his as $r){
                                         $k++;
                                         $g = $r;
                                 ?>
                                    <tr <?php if($k%2 == 0 ){ echo "style='background: #eee;'"; } ?>>
                                        <td><a onclick="senddata('data_<?php echo $k; ?>')" href="#" > <?php echo $r->subject ?> </a>
                                            <div id="data_<?php echo $k; ?>" style="display: none;"  > <?php echo $r->content; ?>  </div>
                                        
                                        </td>                                     
                                        <td>  <?php echo date('h:i' ,  strtotime($r->dte_send)); ?><br><?php echo date('d M y' ,  strtotime($r->dte_send)); ?> </td>
                                        <td> <?php  
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
							
						}else if($api_type == 'C'){
                  
							$total = 0;
							$campaign->id = $g->email_id;

                                                    try{
							   $campaign_details = $cc->getEmailCampaign($ACCESS_TOKEN, $g->email_id);
                                                    if($campaign_details->status == 'SENT'){
    							$groups_details = $cc->getEmailCampaignSummaryReport($ACCESS_TOKEN, $campaign);									
    							$total = $groups_details->sends;
    							}else{
    
    								$total = 0;
    							}
    					
              }catch (CtctException $ex){
                 $total = 'Not connected';
				 $campaign_details = NULL;
              }
						} else{
               $total = 'Not connected';
            }
														
							echo $total;
						?>	</td>
                                        <td> <?php 
						
							if($api_type == 'M'){
							
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
	
							}else if($api_type == 'C'){
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
								
							} else{
                echo 'Not connected';
              }
							               
							
						?>
                                        </td>
                                    </tr>
                                     <?php }
                                     
                                     } else { ?>
                          <br> <span style="    display: table;"> No Data </span>
                                 <?php } ?>    
                          </tbody>
                      </table>
                      
                    
                   
                </div>
            </div>
              <div class="top-header">
                  &nbsp;   Advisor WIDGETS
                </div>
            <div class="col-2" >  
                <div id="viewpage">
                    
                </div>   
            </div>
                    
         
        </div>
     
   
     
        <style>
              .allpage {
               width: 1600px ;
              }
              .col-1{
                      width: 27%;
              }
              .block-2{
                      width: 28%;
              }
              #adform{
                     
                          padding-left: 65px;
              }
              .col-2{
                  width: 72%;
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
        </style>
      
<?php die; ?>