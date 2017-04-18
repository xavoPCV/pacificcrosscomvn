<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$cparams = JComponentHelper::getParams('com_media');

jimport('joomla.html.html.bootstrap');
$document   = JFactory::getDocument();
//$document->addScript($this->baseurl.'/templates/pacific-cross/js/google.map-settings.js', 'text/javascript', true, true);
?>
<script>
	jQuery( document ).ready(function() {
			jQuery('label').attr('title','') ;
		<?php
		
if(isset($_GET['ddmess'])){
?>
		jQuery('#jform_contact_message').text('<?php echo $_GET['ddmess']; ?> \n ');
<?php		
}
		
		?>
		
	});
</script>
<div class="<?php echo $this->pageclass_sfx?>" itemscope itemtype="http://schema.org/Person">
	<?php if ($this->contact->name && $this->params->get('show_name')) : ?>
		<h1 class="page-title" itemprop="name"><?php echo $this->contact->name; ?></h1>
	<?php endif;  ?>

	<div class="pure-g contact-detail" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	    <?php echo $this->loadTemplate('address'); ?>
	</div><!-- end of pure-g -->

	<div class="pure-g">
		
		<div class="pure-u-1 pure-u-md-1-2 contact-map">
	        <div class="pure-g">
				<?php if ($this->contact->misc && $this->params->get('show_misc')) : ?>
			        <div class="pure-u-1">
			            <div>
						<?php if ($this->params->get('presentation_style') == 'plain'):?>
							<?php echo '<h3 class="module-title">' . JText::_('COM_CONTACT_OTHER_INFORMATION') . '</h3>';  ?>
						<?php endif; ?>
							<?php //echo $this->contact->misc; ?>
						</div>
			        </div><!-- end of pure-u-1 -->
				<?php endif; ?>

	            <div class="pure-u-1">
	                <div class="map-wrapper">
	                    <div id="map-canvas">
	                    </div>
	                </div>
	            </div><!-- end of pure-u-1 -->
			</div><!-- end of pure-g -->
	    </div><!-- end of pure-u-1 -->
		
		<?php if ($this->params->get('show_email_form') && ($this->contact->email_to || $this->contact->user_id)) : ?>
			<div class="pure-u-1 pure-u-md-1-2">
				<?php  echo $this->loadTemplate('form');  ?>
			</div><!-- end of pure-u-1 -->
		<?php endif; ?>
		
		
	</div><!-- end of pure-g -->
	<?php if ($this->contact->mobile && $this->params->get('show_mobile')) :?>
		<span class="contact-latitude" itemprop="latitude" style="display:none;">
			<?php echo $this->contact->mobile; ?>
		</span>
	<?php endif; ?>
	<?php if ($this->contact->webpage && $this->params->get('show_webpage')) : ?>
		<span class="contact-longitude" itemprop="longitude" style="display:none;">
			<?php echo $this->contact->webpage; ?>
		</span>
	<?php endif; ?>
</div>

<script type="text/javascript">
function initialize() {
var myLatLng = new google.maps.LatLng(<?php echo $this->contact->mobile; ?>, <?php echo $this->contact->webpage; ?>);
var mapOptions = { zoom: 16, center: myLatLng, disableDefaultUI: false, scrollwheel: false, navigationControl: false, mapTypeControl: false, scaleControl: false, draggable: true, mapTypeControlOptions: { mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'roadatlas'] } };
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
  var marker = new google.maps.Marker({ position: myLatLng, map: map, icon: 'templates/pacific-cross/images/location.png',title: ''});
  var contentString = '<div style="max-width: 300px" id="content">'+
      '<div id="bodyContent">'+
	  '<h4 class="color-primary"><strong><?php echo $this->contact->name; ?></strong></h4>' +
      '</div>'+
      '</div>';
  var infowindow = new google.maps.InfoWindow({ content: contentString });
  google.maps.event.addListener(marker, 'click', function() { infowindow.open(map,marker); });
  var styledMapOptions = { name: 'US Road Atlas' };
  var usRoadMapType = new google.maps.StyledMapType(styledMapOptions);
  map.mapTypes.set('roadatlas', usRoadMapType);
  map.setMapTypeId('roadatlas');
}
function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
      'callback=initialize';
  document.body.appendChild(script);
}
window.onload = loadScript;
</script>
