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
        </style>
        
        <script>
                $(function() {
                  $( "#tabs" ).tabs();
                });

                $(document).ready(function() {
                  
                        document.title = 'Enewletter';
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
              });
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
           sort__table();
//            $(function() {
//                $( "#sponsor" ).resizable({
//                  maxHeight: 410,
//                  maxWidth: 250,
//                  minHeight: 110,
//                  minWidth: 210
//                });
//              });
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
    }                
    function demoUpload() {
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
                            // swal("Sorry - you're browser doesn't support the FileReader API");
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
                
		$('#advions-save').on('click', function (ev) {
                      if ( $("#form1 input[type='checkbox']:checked").val() == 'Yes' ){
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
                                                    $( '#advions-content' ).append('  <a href="#" ><img src="'+data+'" alt="Smiley face" width= "100%"  > </a>');                                                     
                                                   
                                                }else{
                                                     $( '#advions-content' ).append('  <a href="'+$("#form1 input[type='radio']:checked").val()+'" ><img src="'+data+'" alt="Smiley face" width= "100%"  > </a>'); 
                                                 
                                                }
                                                        ajaxsave();  setTimeout(function(){ ajaxsave();},5000);
                                        });     
                                              
                                             
                                       
                                    });
                                    $('#advions-content').show();
                              

                               }else{                                   
                                    $('#advions-content').hide();
                               }
			
                        
                  
		});
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
                            // swal("Sorry - you're browser doesn't support the FileReader API");
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
                                   if ($('#upload-cta').val()== '' ){
                                        
                                            $( ".seminar-content-title2" ).empty();
                                            $( ".seminar-content-title2" ).append($("#textctatit").val());
                                            $( ".seminar-content-text2" ).empty();
                                            $( "#image-cta" ).remove();
                                            
                                            $('#seminar').css('padding','30px');
                                            $('.seminar-content-title2').css('margin-bottom','25px');
                                            
                                            if ( $("#btcobactatit").val() != '' ){
                                                var btbaco = $("#btcobactatit").val();
                                            }else {
                                                var btbaco = '#FF9b0b;';
                                            }

                                             if ( $("#btcotectatit").val() != '' ){
                                                var btco = $("#btcotectatit").val();
                                            }else {
                                                var btco = '#FFFFFF;';
                                            }
                                        if (type == '') {
                                                $( ".seminar-content-text2" ).append('\
                                                     <a style="    color: '+btco+'!important;    background-color: '+btbaco+';    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;    padding-left: 30px;    padding-right: 30px;" target="_blank" href="'+link+'" class="button-or"> '+$("#textbutonctatit").val()+' </a>');
                                        }else{    
                                         $( ".seminar-content-text2" ).append('\
                                            <a style="    color: '+btco+'!important;    background-color: '+btbaco+';    font-size: 12px;    padding: 10px;    margin: 5px;    margin-top: 20px;    text-decoration: none;    padding-left: 30px;    padding-right: 30px;" target="_blank" href="<?php echo JURI::base(); ?>index.php?option=com_cta&view=form&'+type+'='+id+extend+'" class="button-or"> '+$("#textbutonctatit").val()+' </a>');
                                        }
                                                    if ( $("#cobactatit").val() != '' ){
                                                          $('#seminar').css("background", $("#cobactatit").val());
                                                     }
                                                      if ($("#cotectatit").val() != ''  ){
                                                          $('#seminar').css("color", $("#cotectatit").val());
                                                     }
                                     }else {
                                               $uploadCrop.croppie('result', {
                                                type: 'canvas',
                                                size: 'original'
                                                }).then(function (resp) {                              

                                                           $.ajax({
                                                             url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savepng",
                                                             type: "POST",
                                                             data: "imgcode="+resp                            
                                                           }).done(function( data ) {   
                                                                       $('.seminar-content-title2').empty();
                                                                             $('.seminar-content-text2').empty();
                                                                             $('#seminar').css('padding','0px');
                                                                             $('.seminar-content-title2').css('margin','0px');
                                                                             $('#upload-cta').val('')
                                                                               $( "#image-cta" ).remove();
                                                                               
                                                      if (type == '') {                       
                                                            $('.seminar-content-title2').after('\
                                                      <a id="image-cta" target="_blank" href="'+link+'" > '+'  <img src="'+data+'" alt="Smiley face" width= "100%"  > '+' </a>');   
                                                        }else{
                                                              $('.seminar-content-title2').after('\
                                                 <a id="image-cta" target="_blank" href="<?php echo JURI::base(); ?>index.php?option=com_cta&view=form&'+type+'='+id+extend+'" > '+'  <img src="'+data+'" alt="Smiley face" width= "100%"  > '+' </a>');   
                                                        }
                                                     }); 



                                                });

                                     }                                 
                               }else{                                   
                                   $('#seminar').hide();                                   
                               } 
                         
                    
                            ajaxsave();  setTimeout(function(){ ajaxsave();},5000);
			
		});
		
	}   
        
//        function demoUploadmap() {
//        
//		var $uploadCrop;
//
//		function readFile(input) {
// 		if (input.files && input.files[0]) {
//	            var reader = new FileReader();	            
//	            reader.onload = function (e) {
//	            	$uploadCrop.croppie('bind', {
//	            		url: e.target.result
//	            	});
//	            	$('.upload-demo-map').addClass('ready');
//	            }
//	            
//	            reader.readAsDataURL(input.files[0]);
//	        }
//	        else {
//                            alert ("Sorry - you're browser doesn't support the FileReader API"); 
//                         
//		    }
//		}
//
//		$uploadCrop = $('#upload-demo-map').croppie({
//			viewport: {
//				width: 210,
//				height: 210,
//				type: 'square'
//			},
//			boundary: {
//				width: 240,
//				height: 240
//			}
//		});
//
//		$('#file3').on('change', function () { readFile(this); });
//                
//		$('#map-save').on('click', function (ev) {
//			$uploadCrop.croppie('result', {
//				type: 'canvas',
//				size: 'original'
//			}).then(function (resp) {                              
//                                  $( '#map' ).empty();
//                                  $( '#map' ).append('  <img src="'+resp+'" alt="Smiley face" width= "100%"  > ');                                  
//                            
//			});
//		});
//	}
        
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
				width: 260,
				height: 65,
				type: 'square'
			},
			boundary: {
				width: 280,
				height: 180
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
                                           $( '#logomail' ).append('  <img src="'+data+'" alt="Smiley face" width= "100%"  > ');   
                                              ajaxsave(); setTimeout(function(){ ajaxsave();},5000);
                                        }); 
                                             
                                                                                             

                                   });

                                    $('#logomail').show();
                                 
                               }else{                                   
                                   $('#logomail').hide();                                   
                               }
			
		});
	}

   function opencustom(){
                     $('#custom-content-title').val('');
                 
                     tinyMCE.remove();   
                     tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#custom-content',
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
                                                
                      tinyMCE.activeEditor.setContent('');
                  
              }
  
  
function shipOff(a,b,c) {
  
            var fd = new FormData(document.getElementById(a));
            fd.append("label", "WEBUPLOAD");
            $.ajax({
              url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=uploadvideocta",
              type: "POST",
              data: fd,
              enctype: 'multipart/form-data',
              processData: false,  // tell jQuery not to process the data
              contentType: false   // tell jQuery not to set contentType
            }).done(function( data ) {
               
                 $( b ).append(data);
                 $('.warning').hide();
                 $('.upload_error').fadeOut(3000 ,function() {
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
                            //demoUploadmap();
                            demoUploadlogo();
                            dofirst();
                            $(function(argument) {
                                $('.col-1 [type="checkbox"]').bootstrapSwitch();
                              })
                            $('#address-text').html( $('#address-content').html());                          
                         //   $('#cta').empty().html('<img src="<?php echo JURI::base(); ?>images/contenttest.png" alt="Smiley face" textcode="abcd1" > ');
                            $('#cloud-tag').empty().html($('#dasjdasj').html());    
                              $('#form12').prepend($('#dasjdasj').html());                          
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
                              
                         //   $('#sponsor-link').attr('href','<?php echo $this->sponsor_link; ?>');
                         //   $('#ponsor-img').attr('src','<?php echo $this->sponsor_image; ?>');
                            
                            $('#investment-edit-link').val( $('#ainvest').attr('href') );
                            $('#financial-edit-link').val( $('#afinancial').attr('href') );
                            $('#weekly-edit-link').val( $('#aweekly').attr('href') );
                            $('#meeting-edit-link').val( $('#aschedule').attr('href') );
                            $('#temintro-edit-text').val( $(".intro").html().replace(/<br\s?\/?>/g,"\n"));
                            $('#htmlcodeold').html( $('#isdatahtml').html()); 
                             $('#textctatit').val($('h3.seminar-content-title2').html()); 
                         //   if ( $('#sponsor-link').attr('href') == '#' ){
                                
                                 
                         //   }
                         
             
        
                    });          
  </script>
   
  <script>
              function dofirst() {
                     mousmove(); 
                    $("#financial").hide();
                    $("#social").click( function() {
                            showedit('social','social');
                    });
                    $("#address").click( function() {
                            showedit('address','address-content');
                    });
                    $("#logomail").click( function() {
                            showedit('logo','logomail');
                    });
                    $("#advions-content").click( function() {
                            showedit('advions','advions-content');
                    });
                    $("#seminar").click( function() {
                            showedit('cta','seminar');
                    });
                    $("#map").click( function() {
                            showedit('map','map');
                    });
                    $("#cloud-tag").click( function() {
                            showedit('cloud','cloud-tag');
                    });                    
                   
                    $("#schedule").click( function() {
                            showedit('meeting','schedule');
                    });
                     $("#invest").click( function() {
                            showedit('investment','invest');
                    });
                    $("#weekly").click( function() {
                            showedit('weekly','weekly');
                    });
                    $("#poll-content").click( function() {
                            showedit('poll');
                    });
                     $("#serminar1").click( function() {
                            showedit('serminar','serminar1');
                    });
                    
                    $(".intro").click( function() {
                            showedit('temintro');
                    });
                    <?php if ( JRequest::getVar('new_tem') == 1 ) { ?>
                               $('.sed').show();
                               setTimeout(function(){$('.sed').hide();},4000);
                    <?php } ?>
                    fn_edit_content();
                    
                
                    
              }
            function check_read_more(){
                 var a = $('#id_text_content').val();  
                     var read  = $('#id_readmorlink_content').is(":checked");
                     if (read){                                           
                                                     $('#link_'+a).css('display','block');
                                                }else{
                                                     $('#link_'+a).css('display','none');
                                                }
            }  
            function fn_edit_content() {
                  var dbasjdsaj = 0; 
                  $('.edit_content').on('mousedown', function(e){ 
                          $('#sortable1').unbind(); 
                         
                       if (e.which == 1){
                         
                         var idcont =  $(this).attr("id-cont");
                         var id_edit = $('#id_text_content').val();
                         if ( idcont != id_edit){
                             
                                            var a = $('#id_text_content').val();   
                                             if (a != ''){
                                                    var a = $('#id_text_content').val();   
                                                    var html_con =  tinyMCE.get(a+'_tex').getContent(); 
                                                    $("#"+a).html(html_con);
                                                    $('#id_text_content').val('');   
                                             }
                                             
                                            var html_tem = $('#'+idcont).html();                                      
                                            $('#'+idcont).empty();
                                            $('#'+idcont).html('<textarea id="'+idcont+'_tex" >  </textarea><table style="width: 100%">                                       <tr>                                            <td> <button type="button" class="button-blu content-save" id="" >Save</button> </td>                                            <td>                                                <table>            <td> Text limit  <input style="    width: 70px;    margin-left: 10px;    margin-right: 20px;    padding: 10px;    padding-top: 5px;    padding-bottom: 5px;" type="number" id="id_textlimit_content" value="" placeholder = "px" /> </td>          <td> <input onclick="check_read_more();" type="checkbox" id="id_readmorlink_content" value="0" />  Read more link </td>                    </tr>    </table> </td>   </tr>  </table>');
                                            $('#'+idcont+'_tex').html(html_tem);  
                                              tinyMCE.remove();   
                                                tinymce.init({                                   
                                                    invalid_elements : "script",
                                                    selector:'#'+idcont+'_tex',
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
                                                   
                                             $('#id_text_content').val(idcont);                           
                                             showedit('',$(this).attr("id"));
                                             window.scrollTo(0, $('#'+idcont).offset().top);
                                    }else {                                      
                                        $( ".content-save" ).mouseup(function() { 
                                          
                                         
                                            var text = $('#id_textlimit_content').val();
                                      
                                            var a = $('#id_text_content').val();   
                                            var html_con =  tinyMCE.get(a+'_tex').getContent(); 
                                            $("#"+a).html(html_con);
                                       
                                           
                                            if (text >= 600) {
                                                 $("#"+a).css('display','block');
                                                 $("#"+a).css('max-height',text+'px');
                                                 $("#"+a).css('position','relative');     
                                                 $("#"+a).css('overflow','hidden');
                                                 $("#"+a).css('display','block');
                                                 var text = $('id_textlimit_content').val('');
                                            }else{
                                                 // 
                                                 if ( text < 600 && text !='' ){
                                                     alert('Min Height 600px ');
                                                 }else {
                                                     $("#"+a).css('max-height', '3000px');
                                                 }
                                                  
                                            }
                                            
                                            $( ".content-save" ).remove();
                                            $('#id_text_content').val('');   
                                            ajaxsave();
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
                    function getcontent (){
                           
                            
                            var cuscontit = $('#custom-content-title').val();
                            var html_cuscon =  tinyMCE.get("custom-content").getContent(); 
                            if ($.trim(cuscontit) != ''){
                                $( '#cta' ).empty();
                                $('#cta').prepend('<table id="articles" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" class="mceItemTable"><tbody><tr id="intro" bgcolor="#ECEBE0"><td style="padding: 10px 25px 10px 25px;" data-mce-style="padding: 10px 25px 10px 25px;">  <strong class="intro"> Template Intro Here.. </strong></td> </tr><tr id-cont="article_content_6" class="edit_content" id="article_6" style="background: rgb(251, 228, 151);" data-mce-style="background-color: #fbe497;"><td style="padding: 25px;" data-mce-style="padding: 25px;"> <table width="100%" class="mceItemTable"><tbody><tr><td style="padding: 0 0 0 0;  font-face: arial; font-size: 10px; text-align: justify;" valign="top" data-mce-style="padding: 0 0 0 0; width: 45%; font-face: arial; font-size: 10px; text-align: justify;"> <br> <div id="article_content_6" style="font-family: Arial; font-size: medium;" data-mce-style="font-family: Arial; font-size: medium;"> <strong style="font-size: 20px;" data-mce-style="font-size: 20px;"> <a  style="color: #000000; text-decoration: none;" >'+cuscontit+'</a></strong> <br><p>'+html_cuscon+'</p></td></tr> </tbody></table> </td></tr>  </tbody></table>')
                                 fn_edit_content();
                                 ajaxsave();
                            }else{
                                 var check = $('#valueidartical').val();  
                            if (check != '' ){
                                  $( '#cta' ).empty().html('<span style="font-size:28px;color:red;" > Please Waiting... </span>');
                                $.ajax({
                                 url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&task=getcontent&id="+check,
                                 type: "GET",
                                 enctype: 'multipart/form-data',
                                 processData: false,  // tell jQuery not to process the data
                                 contentType: false   // tell jQuery not to set contentType
                               }).done(function( data ) {
                                    $( '#cta' ).empty().html(data);
                                    $(".intro").empty().html( $('#temintro-edit-text').val() );
                                    fn_edit_content();
                                    ajaxsave();

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
                                      $( "#logomail" ).draggable({  grid: [ 20, 20 ] });
                                      $( "#address" ).draggable({ grid: [ 20, 20 ] });
                                      $( "#social" ).draggable({ grid: [ 20, 20 ] });

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
                $('#advions-control').css("border","1px solid #ddd");
                $('#advions-control').css("color","#000");
                $('#advions-content').css("border","4px solid rgb(221,221,221)");
                $('#advions-content').css("background","");
                
                $('#poll-edit').hide();
                $('#poll-control').css("border","1px solid #ddd");
                $('#poll-control').css("color","#000");
                $('#poll-content').css("border","");
                
                
                $('#editcontent-edit').hide();
                $('.edit_content').css("border","");
                $('.edit_content').css("background","#fbe497");
                
                $('#cta-edit').hide();
                $('#cta-control').css("border","none");
                $('#cta-control').css("color","#000");
                
                $('#meeting-edit').hide();
                $('#meeting-control').css("border","1px solid #ddd");
                $('#meeting-control').css("color","#000");
                $('#schedule').css("border","4px solid rgb(221,221,221)");
                $('#schedule').css("background","");
                
                $('#investment-edit').hide();
                $('#investment-control').css("border","1px solid #ddd");
                $('#investment-control').css("color","#000");
                $('#invest').css("border","");
                $('#invest').css("background","");
                
                $('#financial-edit').hide();
                $('#financial-control').css("border","1px solid #ddd");
                $('#financial-control').css("color","#000");
                
                
                $('#weekly-edit').hide();
                $('#weekly-control').css("border","1px solid #ddd");
                $('#weekly-control').css("color","#000");
                $('#weekly').css("border","");
                $('#weekly').css("background","");
                
                $('#address-edit').hide();
                $('#address-control').css("border","1px solid #ddd");
                $('#address-control').css("color","#000");
                $('#address-content').css("border","");
                $('#address-content').css("background","");
                
                $('#logo-edit').hide();
                $('#logo-control').css("border","1px solid #ddd");
                $('#logo-control').css("color","#000");
                $('#logomail').css("border","");
                $('#logomail').css("background","");
                
                $('#social-edit').hide();
                $('#social-control').css("border","1px solid #ddd");
                $('#social-control').css("color","#000");
                $('#social').css("border","");
                $('#social').css("background","");
                
                $('#map-edit').hide();
                $('#map-control').css("border","1px solid #ddd");
                $('#map-control').css("color","#000");
                $('#map').css("border","4px solid rgb(221,221,221)");
                $('#map').css("background","");
                
                $('#cloud-edit').hide();
                $('#cloud-control').css("border","1px solid #ddd");
                $('#cloud-control').css("color","#000");
                $('#cloud-tag').css("border","4px solid rgb(221,221,221)");
                $('#cloud-tag').css("background","");
                
                $('#serminar-edit').hide();
                $('#serminar-control').css("border","1px solid #ddd");
                $('#serminar-control').css("color","#000");
                $('#serminar1').css("border","4px solid rgb(221,221,221)");
                $('#serminar1').css("background","");
                
                // cta
                $('#seminar').css("border","4px solid rgb(221,221,221)");
             //   $('#seminar').css("background","");
                
                 $('#temintro-edit').hide();
                 if (a == 'poll'){
                      $('#poll-content').css("border","4px solid red");
                 }
                
                $('#'+a+'-edit').show();
                $('#'+a+'-control').css("border","2px solid red");
                //$('#'+a+'-control').css("color","white");
                $('#'+b).css("border","4px solid red");
                if (b != 'seminar'){
                    $('#'+b).css("background","#FFFFaa");
                }
            }
               function changeoldlayout(){
                                      
                       $('#isdatahtml').html( $('#htmlcodeold').val()); 
                       dofirst();
                       
                     
              }
               function opentemp(){
                
                      if( $('#open_temps').val() != '' ){
                         arr = $('#open_temps').val().split(','); 
                        
                           $('#changetemps').val(arr[1]);
                           $('#idt').val(arr[0]);
                           $('#task').val('');
                           $('#adform').submit();
                      }else{
                           alert('Please! Select Layout');
                      }

               }
               function newtemp(){
                      if( $('#changetemps_popup').val() != '' ){
                           $('#changetemps').val($('#changetemps_popup').val());     
                           $('#idt').val($('#sasasasa').val());   
                           $('#new_tem').val('1');   
                           $('#task').val('');
                           $('#adform').submit();
                      }else{
                           alert('Input Name Layout');
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
                    if (  $('#htmlcode').html() != ''  && $('#changetemps').val() != '' ){
                      $('#adform').submit();
                    } else {
                            window.scrollTo(0, $('.logo').offset().top);
                            $('#changetemps').css("border","1px solid red");
                            alert ('Name Template isn\'t empty');
                    }
              }
              function ajaxsave(){
                        showedit();
                         var a = $('#id_text_content').val();   
                          if (a != ''){
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
                                $('.seving').show();
                                   setTimeout(function(){
                                        $.ajax({
                                          url: "<?php echo JURI::base(); ?>index.php?option=com_enewsletter&view=editletter&task=savehtml",
                                          type: "POST",
                                          data: $('#adform').serialize()                                        
                                        }).done(function( data ) {                                        
                                           // alert(data);
                                         
                                             $('.seving').hide();
                                             $('.sed').show();
                                               setTimeout(function(){$('.sed').hide();},2000);
                                        });     
                          }, 500);           
                    } else {
                            window.scrollTo(0, $('.logo').offset().top);
                            $('#changetemps').css("border","1px solid red");
                            alert ('Name Template isn\'t empty');
                    }
              }  
            
//              function change_template(a){
//            
//                        if(a == 'new'){
//                                $('#changetemps').remove();
//                                 $('#block_template').append(' <input id="changetemps"  name="changetemps"  value="" placeholder="Name of new template" style="border: 1px solid #ddd;padding: 7px; border-radius: 5px;" /> ')
//                        }else{
//                               $('#task').val('');
//                               $('#adform').submit();
//                        }
//
//              }
              
        </script>
        

        <div class="allpage" >
              <input type="hidden" id="valueidartical" value="" >
            <div class="col-1" > 
                <div class="logo">
                  
                </div>
                <div class="main-1">
                    <div class="header1" >
                       Advisor Widgets
                    </div>
                  
                    <form id="adform" method="post" action=""  enctype="multipart/form-data" style="text-align: center;height: 35px;" >
                        <textarea name="htmlcode" id="htmlcode" style="display: none;"></textarea>
                        <div id="block-button" style="    margin-top: 20px;    text-align: left;    margin-left: 0px;"> 
                            <input class="edittem" onclick=" $('#popup3_new').bPopup();  " type="button" name="" value="New" style="    font-size: 18px;    padding: 5px 20px 5px 20px;    border-radius: 7px;    color: #666;cursor: pointer;"/>
                            <input class="edittem" onclick="$('#popup2_open').bPopup();   " type="button" name="" value="Open"  style="    font-size: 18px;    padding: 5px 20px 5px 20px;    border-radius: 7px;    color: #666;cursor: pointer;" />
                             <input class="edittem" onclick="subhtml('history');" type="button" name="" value="History"  style="    font-size: 18px;    padding: 5px 20px 5px 20px;    border-radius: 7px;    color: #666;cursor: pointer;" />
                        </div>
                        <br>
                        
                        <div class="seving" style="color: red;display: none;    margin-left: 106px;    margin-top: -15px;    position: absolute;" > Saving... </div>
                        <div class="sed" style="color: blue;display: none;    margin-left: 106px;    margin-top: -15px;    position: absolute;"> Saved</div>
                        
                        <br><br>
                      
                         <button onclick="subhtml('send');" id="adform-button1" type="button" style="    border: none;    text-align: center;    margin: 0 0 25px;    padding: 15px;    background: #2268be;    color: #fff;    cursor: pointer;    border-radius: 4px;min-width: 100px;float: left;     font-size: 0;    background: url('images/smail.png');    background-size: 100%;    background-repeat: no-repeat;    width: 255px;    height: 70px;    position: absolute;    top: 4px;    right: 20%;" >Send Mail</button>
                         <input type="hidden" name="option" value="com_enewsletter" >
                         <input type="hidden" id="task" name="task" value="savehtml" >
                         <input type="hidden" name="csslink" value="<?php echo JURI::base(); ?>components/com_enewsletter/assets/newsletter.css" >
                         <input type="hidden" name="view" value="editletter" >
                         
                         <input id="changetemps"  name="changetemps"  value="<?php  echo $this->changetemps_lauout ?>" type="hidden" />
                                 
                         <input type="hidden" name="tmpl" value="component" >
                         <input type="hidden" id="new_tem" name="new_tem" value="" >
                         <input type="hidden"  id="idt" name="idt" value="<?php echo $this->idt; ?>" >
                         <input type="hidden" name="filen" value="<?php echo $this->filen; ?>" >
                         <?php echo JHtml::_('form.token'); ?>
                        
                    </form>
                    <div id="advions-control" class="block-2" onclick=" showedit('advions','advions-content');window.scrollTo(0, $('#advions-content').offset().top);">
                        <div class="image1" > <i class="fa fa-user fa-3x"></i>  </div>
                        <div class="text1" > Advisors </div>                      
                        
                    </div>
                        <div id="poll-control" class="block-2" onclick="showedit('poll');window.scrollTo(0, $('#poll-content').offset().top);">
                           <div class="image1" > <i class="fa fa-bar-chart fa-3x"></i> </div>
                           <div class="text1" > poll </div>                      

                       </div>
                       <div id="logo-control" class="block-2" onclick="showedit('logo','logomail');window.scrollTo(0, $('#logomail').offset().top);" >
                        <div class="image1" > <i class="fa fa-500px fa-3x"></i>  </div>
                        <div class="text1" > logo </div>                      
                        
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
                   
                        <br><br>
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                       
                        
                        <button type="button" class="button-blu" id="advions-save" >Save</button>                           
                                     
                      
                        
                        </form>
                    </div>
                    
                    <div id="poll-edit" style="display: none;"  class="block-3" >
                        <form id="form2">
                          
                        <br><br>
                        title: <br>
                        <input value="quick poll" type="text" id="poll-edit-title" placeholder="" >
                        <input  type="text" id="poll-edit-content" value="" >
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
                                 $( "#poll-edit-linktrue" ).val('https://centcom.advisorproducts.com/index.php?option=com_acepolls&task=updatepoll&op=t&id='+a);
                                 $( "#poll-edit-linkfalse" ).val('https://centcom.advisorproducts.com/index.php?option=com_acepolls&task=updatepoll&op=f&id='+a);
                            }
                        </script>
                        <br>
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                      
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
                                     
                    <div id="logo-edit" class="block-3" style="display: none;"   >
                          <br>
                        <form id="form9">
                          
                        <div id="upload-demo-logo" style="display: none;"></div>
                        
                         Upload : <br>
                        <input value="" type="file" name="file" id="file2" onchange="$('#upload-demo-logo').show();" >
                        
                        <br><br>
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                       
                      
                        
                        <button type="button" class="button-blu" id="logo-save" >Save</button>
                          
                  
                       
                      
                        
                        </form>
                    </div>
                    
                    
                    
                    
                    <div id="social-control" class="block-2" onclick="showedit('social','social');window.scrollTo(0, $('#social').offset().top);">
                        <div class="image1" > <i class="fa fa-facebook-official fa-3x"></i>  </div>
                        <div class="text1" > social media </div>                      
                        
                    </div>
                    <div id="cta-control" class="block-2" onclick="showedit('cta','seminar');window.scrollTo(0, $('#seminar').offset().top);">
                        <div class="image1" > <i class="fa fa-youtube-play fa-3x"></i>  </div>
                        <div class="text1" > cta </div>                      
                        
                    </div>
                    <div id="address-control" class="block-2" onclick="showedit('address','address-content');window.scrollTo(0, $('#address-content').offset().top);" >
                        <div class="image1" > <i class="fa fa-building fa-3x"></i>  </div>
                        <div class="text1" > My Location </div>                      
                        
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
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                    
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
                              <br> <br>
                              <div id="upload-demo-cta" style="display:none;"></div>
                          </div>
                     
                        
                          
                          
                         <br><br>
                        
                        Enable :
                       
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                                             
                        <button type="button" class="button-blu" id="cta-save" >Save</button>                       
                        <script>
                            $ ("#file_name_video").change(function () {
                                $(".warning").show();
                                shipOff('form3','.list_cta');   
                            });
                          
                        </script>                        
                        </form>
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
                        <br>   
                        Enable :                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                                        
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
                 
                    
                    <div id="content-control" class="block-2" onclick=" $('#popup').bPopup();opencustom();" >
                        <div class="image1" ><i class="fa fa-file-text-o fa-3x"></i>  </div>
                        <div class="text1" > Article Manager </div>                      
                        
                    </div>
                    
                    <div id="intro-control" class="block-2" onclick="showedit('temintro');window.scrollTo(0, $('#intro').offset().top);" >
                        <div class="image1" > <i class="fa fa-text-width fa-3x"></i>    </div>
                        <div class="text1" > Template Intro  </div>                      
                        
                    </div>
                      <div id="weekly-control" class="block-2" onclick="showedit('weekly','weekly');window.scrollTo(0, $('#weekly').offset().top);" >
                        <div class="image1" > <i class="fa fa-list-ul fa-3x"></i>  </div>
                        <div class="text1" > weekly Update </div>                      
                        
                    </div>
                        <div id="editcontent-edit" class="block-3"  style="display: none;"    >
                               <form id="form18">
                               
                                    <textarea id="text-content" >  </textarea>
                                    <input type="hidden" id="id_text_content" value="" />
                                   
                                   
                               </form>
                               
                        
                        </div>                     
                       <div id="weekly-edit" class="block-3"  style="display: none;"  >
                        <form id="form7">                          
                        <!--
                        Link: <br>
                        <input value="#" type="text" id="weekly-edit-link" placeholder="" >
                        Text: <br>
                        <textarea id="weekly-text" style="width: 97%;height: 70px;" >dasdsadasdsadasd</textarea>
                        -->
                        <br>
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                        
                        
                        
                        <button type="button" class="button-blu" id="weekly-save" >Save</button>
                       
                        <script>
                           
                            $( "#weekly-save" ).click(function() {
                               
                               if ( $("#form7 input[type='checkbox']:checked").val() == 'Yes' ){
                               
//                                 $("#aweekly").attr("href", $("#weekly-edit-link").val() );
//                                 $("#aweekly").empty().html( $('#weekly-text').val() );
                                   $('#weekly').show();                                    
                               }else{                                   
                                   $('#weekly').hide();
                                   
                               }
                                 ajaxsave();
                                
                            });

                        </script>
                        
                        </form>
                    </div>
                    
                    
                    <div id="temintro-edit" class="block-3"  style="display: none;"  >
                        <form id="form14">
                              Intro: <br>
                              <textarea id="temintro-edit-text" style="    width: 95%;    min-height: 74px;"></textarea>
                        
                             <br>
                                Enable :

                                <input type="checkbox" name="position2" checked="checked" value="Yes">

                               

                                <button type="button" class="button-blu" id="temintro-save" >Save</button>
                               
                                <script>
                       
                            $( "#temintro-save" ).click(function() {
                               
                               if ( $("#form14 input[type='checkbox']:checked").val() == 'Yes' ){
                               
//                                 $("#aweekly").attr("href", $("#weekly-edit-link").val() );
                              // val = document.getElementById('temintro-edit-text').value;
                                val = $("#temintro-edit-text").val().replace(/\r\n|\r|\n/g,"<br />")
                            //    $(".intro").replaceWith( "<strong class='intro' ></strong>" );
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
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                  
                   
                    
                    
                     <div id="map-control" class="block-2" onclick="showedit('map','map');window.scrollTo(0, $('#map').offset().top);" >
                        <div class="image1" > <i class="fa fa-map-o fa-3x"></i>  </div>
                        <div class="text1" > map </div>                      
                        
                    </div>
                     <div id="cloud-control" class="block-2" onclick="showedit('cloud','cloud-tag');window.scrollTo(0, $('#cloud-tag').offset().top);" >
                        <div class="image1" > <i class="fa fa-cloud fa-3x"></i>  </div>
                        <div class="text1" > tag cloud </div>                      
                        
                    </div>
                      <div id="meeting-control" class="block-2" onclick="showedit('meeting','schedule');window.scrollTo(0, $('#schedule').offset().top);">
                        <div class="image1" > <i class="fa fa-calendar fa-3x"></i>  </div>
                        <div class="text1" > meeting </div>                      
                        
                    </div>
                  
                     <div id="meeting-edit" class="block-3" style="display: none;" >
                         <form id="form4">
                          
                        <!--
                        Link page: <br>
                        <input value="#" type="text" id="meeting-edit-link" placeholder="" >
                       -->
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                      
                        
                        <button type="button" class="button-blu" id="meeting-save" >Save</button>
                       
                        <script>
                           
                            $( "#meeting-save" ).click(function() {                               
                               if ( $("#form4 input[type='checkbox']:checked").val() == 'Yes' ){                               
                                 // $("#aschedule").attr("href", $("#meeting-edit-link").val() )
                                   $('#schedule').show();                                    
                               }else{                                   
                                   $('#schedule').hide();                                   
                               }  
                                ajaxsave();
                            });

                        </script>
                        
                        </form>
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
                        <br><br>
                        Enable : 
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                       
                        
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
                                                  //$("#map-logo-demo").attr("src", $("#map-edit-img").val() );
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
                    <div id="cloud-edit" class="block-3" style="display: none;">
                        <form id="form12">
                        &nbsp; Enable :
                        <input type="checkbox" name="position1" checked="checked" value="Yes">
                     
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
                      <div id="serminar-control" class="block-2" onclick="showedit('serminar','serminar1');window.scrollTo(0, $('#serminar1').offset().top);" >
                        <div class="image1" > <i class="fa fa-users fa-3x"></i>  </div>
                        <div class="text1" > seminar links </div>                      
                        
                    </div>
                    <div id="financial-control" class="block-2" onclick="showedit('financial');window.scrollTo(0, $('#financial').offset().top);" >
                        <div class="image1" >  <i class="fa fa-dollar fa-3x"></i> </div>
                        <div class="text1" > financial planning </div>                      
                        
                    </div>
                    <div id="investment-control" class="block-2" onclick="showedit('investment','invest');window.scrollTo(0, $('#investment-control').offset().top);" >
                        <div class="image1" > <i class="fa fa-cogs fa-3x"></i>  </div>
                        <div class="text1" > Investment Indexes  </div>                      
                        
                    </div>
                         <div id="investment-edit" class="block-3"  style="display: none;" >
                        <form id="form5">
                          
                        <!--
                        Link: <br>
                        <input value="#" type="text" id="investment-edit-link" placeholder="" >
                        Text: <br>
                        <textarea id="investment-text" style="width: 97%;height: 70px;" >dasdsadasdsadasd</textarea>
                        -->
                        <br>
                        Enable :
                        
                        <input type="checkbox" name="position2"  checked="checked"  value="Yes">
                        
                       
                        <button type="button" class="button-blu" id="investment-save" >Save</button>
                       
                        <script>
                           
                            $( "#investment-save" ).click(function() {
                               
                               if ( $("#form5 input[type='checkbox']:checked").val() == 'Yes' ){
                               
//                                 $("#ainvest").attr("href", $("#investment-edit-link").val() );
//                                 $("#ainvest").empty().html( $('#investment-text').val() );
                                   $('#invest').show();                                    
                               }else{                                   
                                   $('#invest').hide();
                               }
                                ajaxsave();
                            });
                        </script>                        
                        </form>
                    </div>
                    <div id="serminar-edit" class="block-3" style="display: none;">
                        
                         <form id="form13" >
                          
                      
                       
                        Enable :
                        
                        <input type="checkbox" name="position2" checked="checked" value="Yes">
                        
                      
                        
                        <button type="button" class="button-blu" id="serminar-save" >Save</button>
                       
                        <script>                           
                            $( "#serminar-save" ).click(function() {                               
                               if ( $("#form13 input[type='checkbox']:checked").val() == 'Yes' ){  
                                 

                            //       $( ".seminar-content-title2" ).empty();
                             //      $( ".seminar-content-title2" ).append($("#serminar-edit-title").val());
                             //      $( ".seminar-content-text2" ).empty();
                              //     $( ".seminar-content-text2" ).append('<a href="'+$("#serminar-edit-link").val()+'" class="button-or"> Start </a>');
                                   $('#serminar1').show();                                    
                               }else{     
                                   
                                   $('#serminar1').hide();                                   
                               }   
                                ajaxsave();
                            });
                        </script>                        
                        </form>
                    </div> 
                      <div id="financial-edit" class="block-3"  style="display: none;"  >
                        <form id="form6">                          
                        <br>
                        <!--
                        Link: <br>
                        <input value="#" type="text" id="financial-edit-link" placeholder="" >
                        Text: <br>
                        <textarea id="financial-text" style="width: 97%;height: 70px;" >dasdsadasdsadasd</textarea>
                        -->
                        <br>
                        Enable :                        
                        <input type="checkbox" name="position2"  value="Yes">                       
                                       
                        <button type="button" class="button-blu" id="financial-save" >Save</button>                       
                        <script>
                           
                            $( "#financial-save" ).click(function() {
                               
                               if ( $("#form6 input[type='checkbox']:checked").val() == 'Yes' ){
                               
//                                 $("#afinancial").attr("href", $("#financial-edit-link").val() );
//                                 $("#afinancial").empty().html( $('#financial-text').val() );
                                   $('#financial').show();                                    
                               }else{                                   
                                   $('#financial').hide();                                   
                               }
                                     ajaxsave();                            
                            });

                        </script>
                        
                        </form>
                    </div>
                </div>
            </div>
              <div class="top-header">
                  &nbsp;   Advisor WIDGETS
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
        <div id="popup2_open"  style="width:100px;height:70px;overflow-y: hidden;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;    width: 180px;    height: 158px;" >
            <h2>Open Layout:</h2>
                   
                        <select style="border: 1px solid #ddd;padding: 7px; border-radius: 5px;        width: 185px;" id="open_temps" >
                                <option  value="">Select - Layout</option> 
                            <?php
                                foreach ($this->tems_user as $r){ 
                                     
                                     $directory = JPATH_SITE."/administrator/components/com_enewsletter/templates/".$r.'.html' ;
                                    
                                     if (file_exists($directory)) {
                                           $date =  date ("F d Y ", filemtime($directory));
                                        }
                                ?>
                                    <option  <?php if ( $this->idt.','.$this->changetemps_lauout == str_replace('_'.JFactory::getUser()->id.'_', ',', $r) ) {echo 'selected="selected"'; } ?> 
                                        value="<?php echo str_replace('_'.JFactory::getUser()->id.'_', ',', $r); ?>">
                                     <?php 
                                     $str =  str_replace("_".JFactory::getUser()->id."_", " : ", $r);
                                     $str = explode(':', $str);
                                     echo $str[1].' - '.$date ;
                                     ?> </option> 

                                <?php 
                            }
                            ?>                              
                        </select>
                  <br><br>
                  <button onclick="opentemp();" style="    float: right;    margin-top: 9px;    color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer;">Save >></button>
                      
        </div>
        <div id="popup3_new"  style="width:100px;height:70px;overflow-y: hidden;overflow-x: hidden;display: none;    background: white;    padding: 26px;    border: 5px #999 solid;    border-radius: 10px;      width: 250px;    height: 220px;" >
                <h2>Setup New Templates</h2>
            
                <div id="block_template" style="    text-align: left;" >
                            
                    <span>Format:</span><br>
                        <select  style="    margin-top: 6px;    margin-bottom: 8px;border: 1px solid #ddd;padding: 7px; border-radius: 5px;" id="sasasasa" >
                            <?php
                                foreach ($this->tems as $r){ 
                            ?>
                            <option value="<?php echo str_replace('.html', '', $r->filename); ?>"> <?php echo $r->type ?> </option>
                            <?php 
                                }
                            ?> 
                        </select>
                        <br>
                        <span>Name:</span>                          
                                <input  id="changetemps_popup"  name="changetemps_popup"  placeholder="Name of new template" style="border: 1px solid #ddd;padding: 7px; border-radius: 5px;margin-top:10px;    width: 209px;" />
                        </div>
            
            
            
                <button onclick="newtemp();" style="     float: right;    margin-top: 9px;    color: white;    background: red;    padding: 10px 15px 10px 20px;    border-radius: 10px;cursor: pointer;">Save >></button>
        </div>
        <div id="popup1"  style="width:100px;height:70px;overflow-y: hidden;overflow-x: hidden;display: none;    background: white;    padding: 66px;    border: 5px #999 solid;    border-radius: 10px;    width: 231px;    height: 195px;font-size: 20px;" >
                 <span class="button b-close"><span>X</span></span>
                 Click" Save" to save this email newsletter layout as a template.<br>
                 Click "Cancel" not to save the change you made to the template layout.  <br>
                 
            <br><br>
               <button   onclick="subhtml();" id="adform-button" type="button" style=" display: none;    border: none;    text-align: center;    margin: 0;    padding: 15px;    background:red;    color: #fff;    cursor: pointer;    border-radius: 4px;float: left;    min-width: 150pxpx;    font-size: 23px;margin-right: 30px; " >Save</button>
               
               <button class="b-close"  onclick="changeoldlayout();" id="adform-button-cancel" type="button" style="   border: none;    text-align: center;    margin: 0;    padding: 15px;    background: red;    color: #fff;    cursor: pointer;    border-radius: 4px;float: left;    min-width: 150pxpx;    font-size: 23px; " >Cancel</button>
        </div>
        <textarea id="htmlcodeold" style="display: none;"> </textarea>
        <div id="popup"  style="width:1000px;height:700px;overflow-y: auto;overflow-x: hidden;" >
            <span class="button b-close"><span>X</span></span>
            <span onclick="getcontent();" class="button b-close" style="right: 50px"><span>Save</span></span>
            <br><br>
          <div id="tabs" >
	<ul>
	
		<li><a href="#tabs-2" class="alltab">Articles</a></li>
		<li><a href="#tabs-4" class="alltab">Weekly Update</a></li>
                <li><a href="#tabs-3" class="alltab">Custom Content</a></li>
		
	</ul>
	
              <div id="tabs-2" >
		<div class="width-100" style="margin-bottom:15px;">
			
                    <table class="adminlist" id="articletable" style="width:100%">
				<thead>
					<tr>
						<th width="1%">
							
						</th>
						<th class="left1">
							<?php echo 'Title'; ?>
						</th>
            <th class="center" width="10%">
							Show Image
						</th>
            <th class="left1">
							<?php echo 'Type'; ?>
						</th>
						<th class="left1">
							<?php echo 'Date Created'; ?>
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
            
            <td class="left1" >
						<?php echo $item->type; ?>
						</td>
						
						<td class="left1" >
						<?php echo $item->created; ?>
						</td>					
						
					</tr>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div><!--tabs-2-->
	<div id="tabs-4" >
		<div class="width-100" style="margin-bottom:15px;">
			
			<table class="adminlist" id="articletable2" style="width:100%">
				<thead>
					<tr>
						<th width="1%">
							<!--<input type="checkbox" name="checkall" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="groupCheckAll(this)" />-->
						</th>
						<th class="left1">
							<?php echo 'Title'; ?>
						</th>
           				<th class="center" width="10%">
							Show Image
						</th>
            			<th class="left1">
							<?php echo 'Type'; ?>
						</th>
						<th class="left1">
							<?php echo 'Date Created'; ?>
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
                                                                    $checked = 'checked="checked"';
                                                                    $scr = " <script> savecid('$item->article_id'); </script>";
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
//                if(in_array($item->article_id,$showimages)){
//  								$checked = 'checked="checked"';
//  							}else{
//  								$checked = '';
//  							}
                                                         if ($abc == 1 ){
                                                                    $checked = 'checked="checked"';
                                                                    $abc++;
                                                                }
								echo '<input id="s_'.$item->article_id.'" class="checkall" type="checkbox"  onclick="Joomla.isChecked(this.checked);" value="'.$item->article_id.'" name="sid[]" '.$checked.' />';
                                                                
              ?>
						</td>
            
            <td class="left1" >
						<?php echo $item->type; ?>
						</td>
						
						<td class="left1" >
						<?php echo $item->created; ?>
						</td>					
						
					</tr>
						
					<?php  endforeach; ?>
				</tbody>
			</table>
			
		</div>
	</div>
         <div id="tabs-3" >
                <div class="width-100" style="margin-bottom:15px;">
                     
                    Title: <input style="margin-left:0%;" type="text" id="custom-content-title" name="custom-content-title" />
                    Content :
                    <textarea id="custom-content" >
                    
                    </textarea>
                 
                </div>
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
      
<?php die; ?>