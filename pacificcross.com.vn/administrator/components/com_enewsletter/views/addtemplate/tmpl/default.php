<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$document = JFactory::getDocument();						
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-1.9.1.js");
$document->addScript(JURI::base()."components/com_enewsletter/js/jquery-ui.js");
$document->addStyleSheet(JURI::base()."components/com_enewsletter/css/jquery-ui.css");

$app = JFactory::getApplication();	
$data = $app->getUserState("com_enewsletter.data");

?>

<style>
#sbox-window{width:900px !important;height:700px !important;}
fieldset.adminform label{ min-width: 210px;}
.hinthelp{ float:right;margin:0px;width:15px;}
.hinthelp:hover {cursor : pointer;}
.setupbtn {background:#e6e6e6;}
.setupbtn:hover{cursor : pointer;}
</style>
   

<script type="text/javascript">

$(document).ready(function(){

    // Check for email type (newsletter/weekly update/massemail)
    <?php if($data['type'] != ''){ ?>
      $('#<?php echo $data["type"]?>'+'radio').prop('checked',true);
      $('#<?php echo $data["type"]?>'+'placeholders').show();
      $('#emailtype').val('<?php echo $data["type"]; ?>');
    <?php }else{ ?>
      $('#newsletterradio').prop('checked',true);
      $('#emailtype').val('newsletter');
      $('#newsletterplaceholders').show();      
    <?php } ?>
    
    // Check for preview task, if preview then open popup window
    <?php if($data['task'] == 'preview'){ ?>
    	$('#preview').show();
    	SqueezeBox.open('#preview','width=750,height=170');
    	$('#sbox-btn-close').prop('onclick','closesqeeze();');
    <?php }else{ ?>
    	$('#preview').hide();
    <?php }?>
    
    // Check for template status (published/unpublished)
    <?php if($data['status'] == 'unpublished' || $data['status'] == ''){
    ?>
    	$('#unpublishradio').prop('checked',true);
      $('#emailpublished').val('unpublished'); 
    <?php }else if($data['status'] == 'published'){
    ?>
    	$('#publishradio').prop('checked',true);
       $('#emailpublished').val('published');
    <?php }?>
    
     // assigns template status value when click on template status radio option
    $('.templatestatus').click(function(){
          $('#emailpublished').val(this.value);          
     });
    
    
    // assigns template type value when click on template type radio option
     $('.templatetype').click(function(){
        if(this.value == 'N'){
          $('#newsletterplaceholders').show();
          $('#weeklyupdateplaceholders').hide();
          $('#massemailplaceholders').hide();
          $('#emailtype').val('newsletter');
          
        }else if(this.value == 'W'){
          $('#newsletterplaceholders').hide();
          $('#weeklyupdateplaceholders').show();
          $('#massemailplaceholders').hide();
          $('#emailtype').val('weeklyupdate');
        }else if(this.value == 'M'){
          $('#newsletterplaceholders').hide();
          $('#weeklyupdateplaceholders').hide();
          $('#massemailplaceholders').show();
          $('#emailtype').val('massemail');
        }
     });
});

</script>

<script type="text/javascript">
// Initialize squeezebox 
window.addEvent('domready', function() {

 SqueezeBox.initialize();
 
 SqueezeBox.assign($$('a[rel=boxed][href^=#]'), {
  size: {x: 900, y: 900}
 });
 
});
</script>

<script>
Joomla.submitbutton = function(task)
	{
    
		if (task == 'addtemplate.cancel' || document.formvalidator.isValid(document.id('user-form'))) {  
    
    if(task != 'addtemplate.cancel') {
          var desc = $('#description').val();
          
          // Check for template description value
          if(desc == ''){
            alert('Please provide description.');
            return false;
          } 
          
        // Check for template type selection
    		if($( "input:radio:checked" ).length == 0){
    			alert('Select at least one template type.');
    			return false;
    		}
        
        // Check for template content value
    		 var editor_text = $('iframe').contents().find('body').text();
    		 if(editor_text == '' && task != 'addtemplate.cancel'){
    		 	alert('Please provide template content.');
    			return false;
    		 }
     }

			Joomla.submitform(task, document.getElementById('user-form'));
		}
	}
</script>

<!-- Start newsletter set up form -->
<form action="" method="post" name="adminForm" id="user-form" class="" enctype="multipart/form-data">

<div class="width-100">
			<fieldset class="adminform">
      
        <div class="newseditor">
					<label title="" class="required">Template Description<span class="star"> *</span></label>												                        									                        
          <input type="text" size="100" class="inputbox" value="<?php echo @$data['description'];?>" id="description" name="jform[description]">
				</div>
        
        <div class="clr"></div>
        
        <label>Template Type</label>
        <?php if($data['id'] != ''){ $disabled = 'disabled';}else {$disabled = '';} ?>
        <fieldset class="radio">  
					<input type="radio" value="N" id="newsletterradio" class="templatetype" name="jfrom[emailtype]" <?php echo $disabled;?> />
					<label>Newsletter</label>  
					<input type="radio" value="W" id="weeklyupdateradio" class="templatetype" name="jfrom[emailtype]" <?php echo $disabled;?> />
          <label>Weekly Update</label> 
          <input type="radio" value="M" id="massemailradio" class="templatetype" name="jfrom[emailtype]" <?php echo $disabled;?> />
          <label>Mass Email</label>
				</fieldset>
        
        <label>Template Status</label>
        <fieldset class="radio">  
					<input type="radio" value="unpublished" id="unpublishradio" class="templatestatus" name="jfrom[status]" />
          <label>Unpublished</label> 
          <input type="radio" value="published" id="publishradio" class="templatestatus" name="jfrom[status]" />
          <label>Published</label>
				</fieldset>
          
        <label>Template Content<span class="star"> *</span></label>
				<div class="clr"></div>
				<div>
          <?php 
  				$editor = JFactory::getEditor();
  				echo $editor->display( 'jform[content]', @$data['content'], '100%', '650px', '40', '10',false);
  			?>
        </div>
           <br>
        	<div class="clr"></div>
        <div id="newsletterplaceholders"  style="display:none;" >
					<label title="" class="required"><b>Newsletter Placeholders : </b></label><div class="clr"></div>											                        
          <span style="font-size:12px;" > {$intro}, {$articletitle1}, {$articlebody1}, {$articleimage1}, {$articlelink1},  
          {$articletitle2}, {$articlebody2}, {$articleimage2}, {$articlelink2},  
          {$articletitle3}, {$articlebody3}, {$articleimage3}, {$articlelink3}, 
          {$articletitle4}, {$articlebody4}, {$articleimage4}, {$articlelink4},   
          {$articletitle5}, {$articlebody5}, {$articleimage5}, {$articlelink5}, {$trailer}, {$disclosure}  </span> <br><br>
          <div style="font-size:12px;">
          <b>Note:-</b>  <br>
            When creating HTML please include the following IDs:  <br>
            * <b>article_X (i.e. article_1)</b> to indicate TR tags for an article
            <b>&lt;TR ID="article1" &gt;{$article_1} &lt;/TR&gt;</b>.  <br>
            * <b>article_img_x (i.e. article_img_1)</b> to indicate IMG tag for an article image
            <b>&lt;IMG ID="article_img_1" src="{$articleimage1}" width=400 /&gt;</b>.  <br>
            * <b>intro (i.e. intro)</b> to indicate TR tags for an intro
            <b>&lt;TR ID="intro" &gt;{$intro} &lt;/TR&gt;</b>.  <br>
            * <b>trailer (i.e. trailer)</b> to indicate TR tags for a trailer
            <b>&lt;TR ID="trailer" &gt;{$trailer} &lt;/TR&gt;</b>.  <br>
            * <b>disclosure (i.e. disclosure)</b> to indicate TR tags for a disclosure
            <b>&lt;TR ID="disclosure" &gt;{$disclosure} &lt;/TR&gt;</b>.  <br>
          </div>
			</div>
      
      	<div class="clr"></div>
      <div id="weeklyupdateplaceholders"  style="display:none;" >
					<label title="" class="required"><b>Weekly Update Placeholders : </b></label><div class="clr"></div>									                        
          <span style="font-size:12px;" > {$intro}, {$articles}, {$disclosure} </span>  <br><br>
          <div style="font-size:12px;">
          <b>Note:-</b>  <br>
            When creating HTML please include the following IDs:  <br>
            * <b>intro (i.e. intro)</b> to indicate TR tags for an intro
            <b>&lt;TR ID="intro" &gt;{$intro} &lt;/TR&gt;</b>.  <br>
            * <b>weeklyupdate (i.e. weeklyupdate)</b> to indicate TR tags for a weeklyupdate content
            <b>&lt;TR ID="weeklyupdate" &gt;{$articles} &lt;/TR&gt;</b>.  <br>
            * <b>disclosure (i.e. disclosure)</b> to indicate TR tags for a disclosure
            <b>&lt;TR ID="disclosure" &gt;{$disclosure} &lt;/TR&gt;</b>.  <br>
          </div>
			</div>
      
      	<div class="clr"></div>
      <div id="massemailplaceholders"  style="display:none;" >
					<label title="" class="required"><b>Mass Email Placeholders : </b></label><div class="clr"></div>										                        
          <span style="font-size:12px;" > {$massemailcontent}, {$disclosure} </span> <br><br>
           <div style="font-size:12px;">
          <b>Note:-</b>  <br>
            When creating HTML please include the following IDs:  <br>
            * <b>disclosure (i.e. disclosure)</b> to indicate TR tags for a disclosure
            <b>&lt;TR ID="disclosure" &gt;{$disclosure} &lt;/TR&gt;</b>.  <br>
          </div>
			</div>
      
      </fieldset>
      
      
        
</div>
		<p>
			
		</p>
  <input type="hidden" name="jform[type]" value="newsletter" id="emailtype" >
  <input type="hidden" name="jform[status]" id="emailpublished" >
  <input type="hidden" name="jform[filename]" value="<?php echo $data['filename'];?>"  >
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_enewsletter" />
	<input type="hidden" name="jform[id]" value="<?php echo @$data['id'];?>" />
</form>
<!-- End newsletter set up form -->

<!-- Start newsletter layout for preview -->
<div style="display:none;">
	<div id="preview">
    <?php         
      
        $rsearch  = array('{$intro}','{$articletitle1}','{$articlebody1}','{$articlelink1}',
                          '{$articletitle2}', '{$articlebody2}', '{$articleimage2}', '{$articlelink2}',
                          '{$articletitle3}', '{$articlebody3}', '{$articleimage3}', '{$articlelink3}',
                          '{$articletitle4}', '{$articlebody4}', '{$articleimage4}', '{$articlelink4}',   
                          '{$articletitle5}', '{$articlebody5}', '{$articleimage5}', '{$articlelink5}', '{$disclosure}' ,
                          '{$articles}', '{$massemailcontent}','{$trailer}'
                  );
		    $rreplace = array('Template Intro Here..','Article 1 Title Here..','Article 1 Body Here..','#',
                          'Article 2 Title Here..','Article 2 Body Here..','Article 2 Image Here..','#',
                          'Article 3 Title Here..','Article 3 Body Here..','Article 3 Image Here..','#',
                          'Article 4 Title Here..','Article 4 Body Here..','Article 4 Image Here..','#',
                          'Article 5 Title Here..','Article 5 Body Here..','Article 5 Image Here..','#', 'Template Disclosure Here..',  
                          'Weekly Update Content Here..', 'Mass Email Content Here..','Template Trailer Here..'
                  );
		  $data['content']	=  str_replace($rsearch, $rreplace, $data['content']);
  
    	$dom=new SmartDOMDocument();
  		$dom->loadHTML($data['content']);
      
      // Remove non selected article image's tag from template start
    		 for($m=1;$m<=5;$m++){
    			$elements = $dom->getElementById('article_img_'.$m);
    			if(!empty($elements)){             
            $elements->parentNode->replaceChild($dom->createTextNode(sprintf("Article $m Image Here..", $m)),$elements);
    			}			
    		}
        
  		$as = $dom->getElementsByTagName("a");
  		foreach($as as $a){
  				$a->setAttribute( 'href' , '#' );
  		}
      $data['content'] = $dom->saveHTML();
      
      
  ?>
    <?php echo $data['content'];?>
  </div>
</div>

<?php 
$app->setUserState("com_enewsletter.data",'');
$app->setUserState("com_enewsletter.loaddata",'yes')
?>

