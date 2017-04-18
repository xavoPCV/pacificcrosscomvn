<?php
defined('_JEXEC') or die;

?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="administrator/components/com_enewsletter/js/jquery-ui.min.js"></script>
<div style="width:100%;height:380px;overflow-y: scroll;">
    <style>
        .imgOutline:hover {
             background: #f9f9f9!important;
        }
    </style>
    <?php if (count( $this->file) > 0) { foreach($this->file as $r ) {     $file_array = explode('.', $r); $fn =   strtolower($file_array[count($file_array)-1]); $fn = substr($fn, 0 , 3); ?>
    <div onclick="$('.showname').html('<?php echo $r; ?>');$('#showname').val('<?php echo juri::base().'images/files/'.$r; ?>');" class="imgOutline" style="cursor: pointer;width: 100px; height: 130px;float: left; border: 1px solid #ccc;padding: 5px;margin-left: 15px;  margin-top: 10px;  background: #fff;">
			<div class="imgTotal">
				<div align="center" class="imgBorder">
					<a style="display: block; width: 100%; height: 50px" title="  <?php echo $r; ?>">
						<img src="/media/media/images/mime-icon-32/<?php echo $fn;  ?>.png" alt="<?php echo $r; ?>"></a>
				</div>
			</div>
                     <div class="controls" style=" border-top: 1px solid #ccc;padding-top: 5px;    word-break: break-all;    font-size: 12px;">
                               <?php echo $r; ?>
			</div>
	   </div>
    
    <?php  } }else { ?>
        No file here
    <?php } ?>
     
</div>
<br>
<?php
 $session =& JFactory::getSession();   
 $shoc = $session->get('thongbao');
 if ($shoc != '' ) {
     echo '<li>'.$shoc.'</li><br>';
 }
 $session->set('thongbao','');
?>
<script>
    function send(){
        if ($('#showname').val() != '' ) {
        window.parent.parentinsertfile($('#showname').val(),$('#linkname').val());
        }
    }
   


</script>
<br>
<div>
      <fieldset id="uploadform">
         <legend>File select </legend>
         <div style="float: right;">  <button onclick="send()"> Insert </button>  <button onclick=" window.parent.parentinsertfileclose()"> Cancel </button> </div>
         <ul>
             <li class="showname" > </li>
         </ul>
         <input type="hidden" value="" id="showname" />
      </fieldset>   
	
	
	
</div>
<br>
<div>
    <form action="index.php?option=com_enewsletter&view=uploadfile" method="post"  enctype="multipart/form-data" >
        <fieldset id="uploadform">
         <legend>Upload files (pdf,docx,pptx,mp3,xlsx)  (Maximum Size: 25 MB)</legend>
         <input type="file" name="fileu"  /> <input type="submit" value="Start Upload"  />
         <input type="hidden" name="option" value="com_enewsletter" >
         <input type="hidden" id="task" name="task" value="uploadf" >
         <input type="hidden" name="view" value="uploadfile" >
         <input type="hidden" name="tmpl" value="component" >
           <?php echo JHtml::_('form.token'); ?>         
        </fieldset> 
    </form>
   
</div>

<?php die; ?>