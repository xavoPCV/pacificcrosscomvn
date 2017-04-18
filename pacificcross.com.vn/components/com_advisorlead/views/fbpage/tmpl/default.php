<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

if ($this->pages) {
?>
<style type="text/css">
ul.adminpagelist, ul.adminpagelist li {
	list-style:none;
	padding:0;
	margin:0;
}
ul.adminpagelist li {
	width:25%;
	float:left;
	display:block;
	box-sizing:border-box;
	padding:5px;
	text-align:center;
}
ul.adminpagelist li p, ul.adminpagelist li a {
	display:block;
	text-decoration:none;
}
ul.adminpagelist li p.linkthumb img {
	width:100%;
}
ul.adminpagelist li a.linktextcur {
	color:#FF0000;
}
.clr {
	clear:both;
	float:none;
}
.adminpages {
	font-family:Arial, Helvetica, sans-serif;

}
</style>
<div class="adminpages">
	<p><a href="<?php echo JURI::root(false);?>index.php?option=com_advisorlead&view=fbpage&signed_request=<?php echo $this->signed_request;?>&skipadmin=1">View Page</a></p>
<?php
	echo "<ul class='adminpagelist'>";
	foreach ($this->pages as $page) {
		$screenshot = ASSETS_URL . "/inc/page-templates/$page->template_slug/screenshot.png";
		echo "<li>
			<p class='linkthumb'><img src='$screenshot' border=0 /></p>
			<p class='linktext'>$page->name</p>";
			
		if ($page->id!=$this->page_id)
			echo "<a href='#' class='linktextact' rel='$page->id'>Enable</a>";
		else
			echo "<a href='#' class='linktextcur' rel='$page->id'>In Active</a>";
		
		
		echo "</li>";
	}//for
	echo "</ul>";
?>
	<div class="clr"></div>
<script>
window.addEvent( 'domready', function() {
	
	$$('.adminpagelist').addEvent('click:relay(a.linktextact)', function(e, target){

		e.stop();
		var pageid = this.get('rel');
		
		if (this.hasClass('linktextcur')) {
			return;
		}//if
		
		
		//in-active all link
		$$('a.linktextact').each(function(ele, j){
			ele.addClass('linktextcur');
		});
		
		
		var thislink = this;
		
		var a = new Request({
			url: "<?php echo JURI::root(false);?>index.php?option=com_advisorlead&task=pages.fbupdatepage&fb=<?php echo $this->fb_page_id;?>&id="+pageid,
			method: 'post',
			onSuccess: function( response ) {
				if (response) {
					var resp = JSON.decode( response );
					if (parseInt(resp.status)) {
					
						$$('a.linktextcur').each(function(ele, j){
							ele.set('text','Enable');
							ele.addClass('linktextact');
							ele.removeClass('linktextcur');
						});
					
					
						thislink.addClass('linktextcur');
						thislink.set('text','In Active');
						
						//alert('Change success!');
					} else {
						alert(resp.msg);
					}//if
				}//if
			}//onSuccess
		}).send();
		
		
	}); 
	
	$$('.adminpagelist').addEvent('click:relay(a.linktextcur)', function(e, target){
		e.stop();
	});
});
</script>
</div><!--adminpages-->
<?php
}//if