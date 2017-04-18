<?php
defined('_JEXEC') or die;



use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

// Get api details
//
$APIKEY = CONSTANT_APIKEY;
$ACCESS_TOKEN =  $this->allsetting[0]->api_key;
$custome_url  =  $this->allsetting[0]->custom_link_article;
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


?> 
<link rel="stylesheet" href="administrator/components/com_enewsletter/css/jquery-ui.min.css">
<link rel="stylesheet" href="administrator/components/com_enewsletter/css/demo_table.css">

        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
         <script src="administrator/components/com_enewsletter/js/jquery.dataTables.js"></script>

    
        <script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  
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

	} );
	

  </script>
  <script>
      function checkform(){
  
        
          if ($('#changetemps').val() != ''){
               $('#adform').submit();
               
          }else{
               alert('You must setup template frist ! ');  
                return false;
          }
          
         
      }
  </script>
      
   <link rel="stylesheet" href="<?php echo JURI::base(); ?>components/com_enewsletter/assets/newsletter_1.css">

        <div class="allpage" >
            <div class="col-1" > 
                <div class="logo">
                  
                </div>
                <div class="main-1">
                    <div class="header1" >
                       Enewsletter Widgets
                    </div>
                  
                   
                        
                        
                         <button onclick="window.location.href='index.php?option=com_enewsletter&view=editletter'   " id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 20px 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;width: 82%; " >Go Back</button>
                         <button onclick="checkform();"  id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 0 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;     background: url('images/sendmail.png');    font-size: 0;    width: 261px;    height: 65px;    background-size: 100%;    background-repeat: no-repeat;" >Send</button>
                        
                    
                          
                    
                    
                    
                    
                </div>
            </div>
              <div class="top-header">
                    ENEWSLETTER WIDGETS
                </div>
            <div class="col-2" > 
                 <form id="adform" method="post" action="" >
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" name="task" value="send" >   
                         
                         <div style="    position: absolute;    z-index: 10000;    top: 145px;    left: 15px;">
                         <span>Templates:</span>
                         <?php /* ?>
                        <select id="changetemps" name="changetemps" >
                            <?php
                                foreach ($this->tems as $r){ 
                            ?>
                            <option  value="<?php echo $r->filename ?>"> <?php echo $r->type ?> </option>
                            <?php 
                                }
                            ?> 
                        </select>
                         <?php */ ?>
                        
                            <select style="border: 1px solid #ddd;padding: 7px; border-radius: 5px;    max-width: 173px;" id="changetemps" name="changetemps" onchange="change_template(this.value)">
                                <option  value="">Select - Layout</option> 
                            <?php
                                foreach ($this->tems as $r){ 
                                ?>
                                    <option  <?php if ( $this->changetemps_lauout == $r ) {echo 'selected="selected"'; } ?> value="<?php echo $r ?>"> <?php echo str_replace("_".JFactory::getUser()->id."_", " : ", $r); ?> </option> 

                                <?php 
                            }
                            ?> 
                             
                        </select>
                      
                         </div>
                       
                         <input type="hidden" name="view" value="sendmail" >
                         <input type="hidden" id="apikey" name="apikey" value="<?php echo $this->allsetting[0]->api_key  ?>">
                         <input type="hidden" id="newsletter_api" name="newsletter_api" value="<?php echo $this->allsetting[0]->newsletter_api  ?>">
                         
                         <?php echo JHtml::_('form.token'); ?>
              <div id="tabs">
	<ul>
		<li><a href="#tabs-1" class="alltab">Personalize</a></li>
	
		<li><a href="#tabs-3" class="alltab">Lists</a></li>
	</ul>
	
	
	<div id="tabs-1">
	<div class="width-100">
			<fieldset class="adminform">
				<legend><?php echo JText::_('Personalize'); ?></legend>
				<div>
									
					<ul class="adminformlist">
          
            <li>	
							<label title="">From Email Address <span class="star"> *</span></label>					
							<div id="verified_email">
              </div>
						</li> 
            
            <li id="emailtemplatediv" >
            <?php  
              if(count($emailtemplates) > 1){   
                 
                  ?>
                  <label style="float:left;" >Email Template</label>
                  <div id="emailtemplatelistsdiv" >            
                      <select id="templateselect" class="ntemplate"  name="jform[ndefaultemail]" >
                      <option value="" >Select Template</option>
                      <?php   foreach($emailtemplates as $et=>$desc){ 
                       
                        if($et == $default_template){
                            $selected = 'selected';
                        }else{
                            $selected = '';
                        }
                      ?>
                          <option value="<?php echo $et;?>" <?php echo $selected;?> > <?php echo $desc;?> </option>
                      <?php  } ?>
                      </select>              
              </div>
              <br /><br />
              <?php  }else{?>
                  <input type="hidden" name="jform[ndefaultemail]" class="ntemplate" value="<?php echo $default_template;?>" >
              <?php }?>
            </li>
        
            
            <li style="clear:both;">
                <br><label title="" >Subject Line <span class="star"> *</span></label>
							<div>
							<input type="text" size="100" maxlength="200" class="inputbox" value="<?php echo htmlentities(@$data['subject']);?>" id="jform_subject" name="jform[subject]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 200 characters.</span> 
							</div>						
							
						</li>
            
						<li style="clear:both;">
                                                    <br>
                                                    <label title="" >Title <span class="star"> *</span></label><br>					
							<input type="text" size="100" maxlength="60" class="inputbox" value="<?php echo @$data['title'];?>" id="jform_campaigntitle" name="jform[title]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 60 characters. The title text will appear in the body of the E-Newsletter.</span>
						</li>						
						
						<li>
                                                    <br><label title="" >Test Email Address</label>
              <?php if($advisoremail){ $aemail = $advisoremail;}else{ $aemail = @$loggeduser->email; }?>
							<input type="text" size="30" class="inputbox" value="<?php echo @$aemail; ?>" id="jform_test_email" name="jform[emailaddress]" />
						</li>

										
					</ul>
					
					
                                        <div style="display: none;" >
					<label>Trailer</label>
					<div class="clr"></div>
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[trailer]',@$data['trailer'], '100%', '250', '40', '10', false);
					?>
				
                                        </div>					
				</div>				
				
			</fieldset>
			
			
		</div>
	</div>

	<div id="tabs-3" >
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="grouptable">
				<thead>
					<tr>
						<th width="1%">
							<!--<input type="checkbox" name="checkall_group" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />-->
						</th>
						<th class="left">
							<?php echo 'Lists'; ?>
						</th>

			
					</tr>
				</thead>
				<tbody>
				<?php foreach ($groups as $i => $group) :?>
					<?php 
              if($group->contact_count != 0 )  {
          ?>
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
					
					
						<td class="left" >
						<?php echo $this->escape($group->name); ?>
						</td>			
						
					</tr>
					<?php }?>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
		</div>
		
		<br /><br />		
		
		
	</div>		
	
</div><!--tabs-->
	
</form>
</div>

<div style="display:none;">
	
	<div id="preview">


	<?php 
	
	//$templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
	//$NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
  
  
	//$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, NULL, $NEWSLETTER);
  
	
	//$dom=new SmartDOMDocument();
	//$dom->loadHTML($content);
	//$mock = new SmartDOMDocument;
        //$mock->loadHTML(); 

	$mock = new SmartDOMDocument;
        
	$body = $dom->getElementsByTagName('body');
	foreach ($body->childNodes as $child){
		$mock->appendChild($mock->importNode($child, true));
	}//for
	$mock = $mock->saveHTML();

	//echo $mock;
	?> 
</div><!--preview-->
</div>
                
              
                 
            </div>
 
     <?php die; ?>