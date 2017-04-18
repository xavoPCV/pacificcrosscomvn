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
            oTable = $('#articletable').dataTable( {
			"aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
      		'iDisplayLength': 50,
      		"aoColumnDefs": [
          		{ "bSortable": false, "aTargets": [ 0] }
        	],
			"oLanguage": {
			  "sSearch": "Search Articles:"
			}
		} );
		
		
		// Assign datatable to groups list
		$('#grouptable').dataTable( {
                                        "aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
                      'iDisplayLength': 50,
                      "aoColumnDefs": [
                          { "bSortable": false, "aTargets": [ 0] }
                        ],
                                        "oLanguage": {
                                          "sSearch": "Search Lists:"
			}
		} );
		
		// Assign datatable to articles list
		
		oTable2 = $('#articletable2').dataTable( {
			"aLengthMenu": [[50,100,200,-1], [50,100,200,"All"]],
      		'iDisplayLength': 50,
      		"aoColumnDefs": [
          		{ "bSortable": false, "aTargets": [ 0] }
        	],
			"oLanguage": {
			  "sSearch": "Search Articles:"
			}
		} );
		
		// set cookie when we change tab 
		$('.alltab').click(function(){
			setCookie('tabSelected',this.id);
		});
    
    $('.checkall').on('click',function(){
      if(this.checked == true)  {
	  
          $('#s_'+this.value).prop('checked',true);
       }else{
          $('#s_'+this.value).prop('checked',false);
       }
		});
    
    api = $('#apikey').val();
    apitype = $('#newsletter_api').val(); 
  
    if(api){
        $.ajax({
               type : 'POST',
        			url: 'http://localhost/joomla25/administrator/index.php?option=com_enewsletter&task=getverifiedemaillist',
              data :   'apitype='+apitype+'&apikey='+api,
        			success: function( data ){
                    $('#verified_email').html(data);              
              }
        		}); 
      }

	} );
	

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
                  
                   
                        <textarea name="htmlcode" id="htmlcode" style="display: none;"></textarea>
                        
                         <button onclick="history.go(-1);" id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 20px 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left; " >Go Back</button>
                         <button onclick="$('#adform').submit();"  id="adform-button" type="button" style="    border: none;    text-align: center;    margin: 0 0 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left; " >Send</button>
                        
                    
                          
                    
                    
                    
                    
                </div>
            </div>
              <div class="top-header">
                    ENEWSLETTER WIDGETS
                </div>
            <div class="col-2" > 
                 <form id="adform" method="post" action="" >
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" name="task" value="send" >          
                         <input type="hidden" name="view" value="sendmail" >
                         <input type="hidden" id="apikey" name="apikey" value="<?php echo $this->allsetting[0]->api_key  ?>">
                         <input type="hidden" id="newsletter_api" name="newsletter_api" value="<?php echo $this->allsetting[0]->newsletter_api  ?>">
                         <?php echo JHtml::_('form.token'); ?>
              <div id="tabs">
	<ul>
		<li><a href="#tabs-1" class="alltab">Personalize</a></li>
		<li><a href="#tabs-2" class="alltab">Articles</a></li>
		<li><a href="#tabs-4" class="alltab">Weekly Update</a></li>
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
							<label title="" >Subject Line <span class="star"> *</span></label>
							<div>
							<input type="text" size="100" maxlength="200" class="inputbox" value="<?php echo htmlentities(@$data['subject']);?>" id="jform_subject" name="jform[subject]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 200 characters.</span> 
							</div>						
							
						</li>
            
						<li style="clear:both;">
							<label title="" >Title <span class="star"> *</span></label>					
							<input type="text" size="100" maxlength="60" class="inputbox" value="<?php echo @$data['title'];?>" id="jform_campaigntitle" name="jform[title]" style="float:none;" >
							<br />
              <label title="" >&nbsp;</label>
							<span>Length should be less than 60 characters. The title text will appear in the body of the E-Newsletter.</span>
						</li>						
						
						<li>
							<label title="" >Test Email Address</label>
              <?php if($advisoremail){ $aemail = $advisoremail;}else{ $aemail = @$loggeduser->email; }?>
							<input type="text" size="30" class="inputbox" value="<?php echo @$aemail; ?>" id="jform_test_email" name="jform[emailaddress]" />
						</li>

										
					</ul>
					
					<div class="clr"></div>
					<label>Newsletter Intro</label>
					<div class="clr"></div>
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[intro]',@$data['intro'], '100%', '250', '40', '10', false, null, null, null, $params );
					?>	
					
					<div class="clr"></div>
					<label>Trailer</label>
					<div class="clr"></div>
					<?php 
						$editor = JFactory::getEditor();
						echo $editor->display( 'jform[trailer]',@$data['trailer'], '100%', '250', '40', '10', false);
					?>
				
										
				</div>				
				
			</fieldset>
			
			
		</div>
	</div>

	<div id="tabs-2">
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="articletable">
				<thead>
					<tr>
						<th width="1%">
							
						</th>
						<th class="left">
							<?php echo 'Title'; ?>
						</th>
            <th class="center" width="10%">
							Show Image
						</th>
            <th class="left">
							<?php echo 'Type'; ?>
						</th>
						<th class="left">
							<?php echo 'Date Created'; ?>
						</th>
						<th class="center">
							<?php echo 'FINRA Status'; ?>
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
								if(in_array(@$item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
								echo '<input class="checkall" type="checkbox" '.$checked.' onclick="Joomla.isChecked(this.checked);" value="'.@$item->article_id.'" name="cid[]" />';?>
						</td>
					
          <td class="left" >
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
              				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
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
					
					 <td class="center">
							<?php
                if(in_array($item->article_id,$showimages)){
  								$checked = 'checked="checked"';
  							}else{
  								$checked = '';
  							}
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
              ?>
						</td>
            
            <td class="left" >
						<?php echo $item->type; ?>
						</td>
						
						<td class="left" >
						<?php echo $item->created; ?>
						</td>					
						<td class="center">
							<?php if ($item->finra_status):?>
							<a href="<?php echo 'index.php?option=com_apicontent&task=fnclist.getFinra&fid='.$item->article_id;?>" target="_blank"><img src="<?php echo $this->baseurl;?>/components/com_apicontent/edit_finra_icon.gif" height="30" width="30"></a>
							<?php else:?>
							<span style="color:red;">pending review</span>
							<?php endif;?>
						</td>
					</tr>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-2-->
	<div id="tabs-4">
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="articletable2">
				<thead>
					<tr>
						<th width="1%">
							<!--<input type="checkbox" name="checkall" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />-->
						</th>
						<th class="left">
							<?php echo 'Title'; ?>
						</th>
           				<th class="center" width="10%">
							Show Image
						</th>
            			<th class="left">
							<?php echo 'Type'; ?>
						</th>
						<th class="left">
							<?php echo 'Date Created'; ?>
						</th>
						<th class="center">
							<?php echo 'FINRA Status'; ?>
						</th>	
					</tr>
				</thead>
				<tbody>
				<?php foreach ($weekly_items as $i => $item) :
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td class="center">
								<?php
								if(in_array(@$item->article_id,$cid)){
									$checked = 'checked="checked"';
								}else{
									$checked = '';
								}
								echo '<input class="checkall" type="checkbox" '.$checked.' onclick="" value="'.@$item->article_id.'" name="cid[]" />';?>
						</td>
         				<td class="left" >
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
              				  $articlelink= JURI::root().'index.php?option=apicontent&view=fnclist&id='.$item->article_id ;
              				}
              				else if($item->type == 'Financial Briefs')
              				{
              				  $articlelink = JURI::root().'index.php?option=apicontent&view=fbclist&id='.$item->article_id;
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
					
					 <td class="center">
							<?php
                if(in_array($item->article_id,$showimages)){
  								$checked = 'checked="checked"';
  							}else{
  								$checked = '';
  							}
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
              ?>
						</td>
            
            <td class="left" >
						<?php echo $item->type; ?>
						</td>
						
						<td class="left" >
						<?php echo $item->created; ?>
						</td>					
						<td class="center">
							<?php if ($item->finra_status):?>
							<a href="<?php echo 'index.php?option=com_apicontent&task=fnclist.getFinra&fid='.$item->article_id;?>" target="_blank"><img src="<?php echo $this->baseurl;?>/components/com_apicontent/edit_finra_icon.gif" height="30" width="30"></a>
							<?php else:?>
							<span style="color:red;">pending review</span>
							<?php endif;?>
						</td>
					</tr>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-4-->
	
	<div id="tabs-3" >
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="grouptable">
				<thead>
					<tr>
						<th width="1%">
							<input type="checkbox" name="checkall_group" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />
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
	
	$templates = $app->getUserState("com_enewsletter.newslettertemplatefiles");
	$NEWSLETTER = file_get_contents(JPATH_SITE."/administrator/components/com_enewsletter/templates/".$templates[$data['ndefaultemail']]);
  
  
	$content = enewsletterHelper::replaceTemplateCode('newsletter', $data, NULL, $NEWSLETTER);
  
	
	$dom=new SmartDOMDocument();
	//$dom->loadHTML($content);
	
	$mock = new SmartDOMDocument;
	$body = $dom->getElementsByTagName('body')->item(0);
	foreach ($body->childNodes as $child){
		$mock->appendChild($mock->importNode($child, true));
	}//for
	$mock = $mock->saveHTML();

	echo $mock;
	?> 
</div><!--preview-->
</div>
                
              
                 
            </div>
 
   