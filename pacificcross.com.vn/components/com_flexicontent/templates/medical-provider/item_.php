<?php
/**
 * @version 1.5 stable $Id: item.php 1704 2013-08-04 08:23:12Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

#HT
JFactory::getDocument()->addStyleSheet(JURI::base(true).'/components/com_flexicontent/assets/css/style.clr.css');

// first define the template name
$tmpl = $this->tmpl; // for backwards compatiblity
$item = $this->item;  // an alias

// USE HTML5 or XHTML
$html5			= $this->params->get('htmlmode', 0); // 0 = XHTML , 1 = HTML5
if ($html5) {  /* BOF html5  */
	echo $this->loadTemplate('html5');
} else {

// Prepend toc (Table of contents) before item's description (toc will usually float right)
// By prepend toc to description we make sure that it get's displayed at an appropriate place
if (isset($item->toc)) {
	$item->fields['text']->display = $item->toc . $item->fields['text']->display;
}

// Set the class for controlling number of columns in custom field blocks
switch ($this->params->get( 'columnmode', 2 )) {
	case 0: $columnmode = 'singlecol'; break;
	case 1: $columnmode = 'doublecol'; break;
	default: $columnmode = ''; break;
}

$page_classes  = '';
$page_classes .= $this->pageclass_sfx ? ' page'.$this->pageclass_sfx : '';
$page_classes .= ' fcitems fcitem'.$item->id;
$page_classes .= ' fctype'.$item->type_id;
$page_classes .= ' fcmaincat'.$item->catid;

$latitude = FlexicontentFields::getFieldDisplay($item, 'providerLatitude');
$longitude = FlexicontentFields::getFieldDisplay($item, 'providerLongitude');

$providerAddress = FlexicontentFields::getFieldDisplay($item, 'providerAddress');
$providerCity = FlexicontentFields::getFieldDisplay($item, 'providerCity');
$providerDistrict = FlexicontentFields::getFieldDisplay($item, 'providerDistrict');
$providerStateProvince = FlexicontentFields::getFieldDisplay($item, 'providerStateProvince');
$providerCountry = FlexicontentFields::getFieldDisplay($item, 'providerCountry');

?>

<?php if ($this->params->get('show_title', 1)) : ?>
	<h1 class="page-title"><?php echo $item->title; ?></h1>
<?php endif; ?>

<?php if (isset($item->positions['description'])) : ?>
	<div class="provider-description">
		<?php foreach ($item->positions['description'] as $field) : ?>
			<?php echo $field->display; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
 <div class="span12">
     <div class="span6"> 
     
     <h2>Contact Information</h2>
<div class="provider-contact-info bkg-white pall-15px">
    <p>
        <span class="icon-border"><i class="ion ion-ios-location"></i></span>
        <?php if (isset($item->positions['providerAddress'])) : ?>
		<?php foreach ($item->positions['providerAddress'] as $field) : ?>
			<?php echo $field->display; ?>,
		<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerDistrict'])) : ?>
			<?php foreach ($item->positions['providerDistrict'] as $field) : ?>
				<?php echo $field->display; ?>,<br>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerCity'])) : ?>
			<?php foreach ($item->positions['providerCity'] as $field) : ?>
				<?php echo $field->display; ?>, 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerStateProvince'])) : ?>
			<?php foreach ($item->positions['providerStateProvince'] as $field) : ?>
				<?php echo $field->display; ?>, 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerCountry'])) : ?>
			<?php foreach ($item->positions['providerCountry'] as $field) : ?>
				<?php echo $field->display; ?><br>
			<?php endforeach; ?>
		<?php endif; ?>
    </p>
    <p>
        <span class="icon-border"><i class="ion ion-ios-telephone"></i></span>
        <?php if (isset($item->positions['providerPhone'])) : ?>
			<?php foreach ($item->positions['providerPhone'] as $field) : ?>
				Phone: <?php echo $field->display; ?><br>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (!isset($item->positions['providerFax'])) : ?>
			<br>
		<?php endif; ?>
		<?php if (isset($item->positions['providerFax'])) : ?>
			<?php foreach ($item->positions['providerFax'] as $field) : ?>
				Fax: <?php echo $field->display; ?><br>
			<?php endforeach; ?>
		<?php endif; ?>
    </p>
    <?php if (isset($item->positions['providerEmail'])) : ?>
	    <p>
	        <span class="icon-border"><i class="ion ion-ios-email"></i></span>
	        <?php if (isset($item->positions['providerEmail'])) : ?>
				<?php foreach ($item->positions['providerEmail'] as $field) : ?>
					Email: <a href="mailto:<?php echo $field->display; ?>" title="Email <?php echo $field->display; ?>"><?php echo $field->display; ?></a><br>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (isset($item->positions['providerWebsite'])) : ?>
				<?php foreach ($item->positions['providerWebsite'] as $field) : ?>
					Website: <a href="http://<?php echo $field->display; ?>" title="Visit <?php echo $field->display; ?>" target="_blank"><?php echo $field->display; ?></a>
				<?php endforeach; ?>
			<?php endif; ?>
	    </p>
    <?php endif; ?>
</div><!-- end of provider-contact-info -->


     
     </div>
     <div class="span6">
         
<h2>Opening Hours</h2>
<div class="provider-contact-info bkg-white pall-15px">
    <p>
        <span class="icon-border" style="margin-bottom: 20px;"><i class="ion ion-android-time"></i></span>
        <?php if (isset($item->positions['providerFromDay1'])) : ?>
			<?php foreach ($item->positions['providerFromDay1'] as $field) : ?>
				<?php echo $field->display; ?> - 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerToDay1'])) : ?>
			<?php foreach ($item->positions['providerToDay1'] as $field) : ?>
				<?php echo $field->display; ?> 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerOpeningHours1'])) : ?>
			<?php foreach ($item->positions['providerOpeningHours1'] as $field) : ?>
				: <?php echo $field->display; ?> - 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerClosingHours1'])) : ?>
			<?php foreach ($item->positions['providerClosingHours1'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<br>
		<?php endif; ?>
		
		<?php if (isset($item->positions['providerFromDay2'])) : ?>
			<?php foreach ($item->positions['providerFromDay2'] as $field) : ?>
				<?php echo $field->display; ?> 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerToDay2'])) : ?>
			<?php foreach ($item->positions['providerToDay2'] as $field) : ?>
				- <?php echo $field->display; ?> 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerOpeningHours2'])) : ?>
			<?php foreach ($item->positions['providerOpeningHours2'] as $field) : ?>
				: <?php echo $field->display; ?> - 
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($item->positions['providerClosingHours2'])) : ?>
			<?php foreach ($item->positions['providerClosingHours2'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<br>
		<?php endif; ?>
		
		Emergency:
		<?php if (isset($item->positions['providerEmergencyServices'])) : ?>
			<?php foreach ($item->positions['providerEmergencyServices'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?> 
		<?php endif; ?>
		<?php if (isset($item->positions['providerEmergencyPhone'])) : ?>
			<?php foreach ($item->positions['providerEmergencyPhone'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		<?php endif; ?>
    </p>
</div><!-- end of provider-contact-info -->
         <?php if (isset($item->positions['providerDirectBilling'])) : ?>
<h2>Direct Billing</h2>
	<div class="provider-contact-info bkg-white pall-15px clearfix">
	    <span class="icon-border"><i class="ion ion-compose"></i></span>
	    <?php if (isset($item->positions['providerDirectBilling'])) : ?>
            <div style="margin-left: 50px;">
				<?php foreach ($item->positions['providerDirectBilling'] as $field) : ?>
					<?php echo str_replace( '</span>' ,  ' , </span>'  , str_replace('li','span',$field->display)); ?> 
                    
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	    
	    <?php if (isset($item->positions['providerNote'])) : ?>
	    	<div>
	    		<br><br>
	    		<p><b>Note:</b></p>
	        	<?php foreach ($item->positions['providerNote'] as $field) : ?>
					<?php echo $field->display; ?>
				<?php endforeach; ?>
			</div>
	    <?php endif; ?>
	</div><!-- end of provider-contact-info -->
<?php endif; ?>
         
     </div>
 </div>


<?php if (isset($item->positions['providerLatitude'])) : ?>
<h2>Location</h2>
<!-- needs to be added to the head in production site. -->
<div class="pure-g">
    <div class="pure-u-1">
        <div class="map-wrapper">
            <div id="map-canvas">
            </div>
        </div>
    </div><!-- end of pure-u-1 -->
</div><!-- end of pure-g -->
<?php endif; ?>


<?php if (isset($item->positions['providerLanguage'])) : ?>
	<h2>Language Spoken</h2>
	<div class="provider-contact-info bkg-white pall-15px">
		<span class="icon-border" style="margin-bottom: 20px;"><i class="ion ion-speakerphone"></i></span>
		<ul>
			<?php foreach ($item->positions['providerLanguage'] as $field) : ?>
				<?php echo $field->display; ?>
			<?php endforeach; ?>
		</ul>
	</div><!-- end of provider-contact-info -->
<?php endif; ?>

<?php if (isset($item->positions['providerMedicalServices'])) : ?>
        <style>
            
            
            
        </style>
	<h2>Specialities</h2>
	<div class="provider-contact-info bkg-white pall-15px clearfix">
	    <span class="icon-border"><i class="ion ion-ios-pulse"></i></span>
            <div style="    margin-left: 61px;">
			<?php foreach ($item->positions['providerMedicalServices'] as $field) : ?>
				<?php echo str_replace( '</span>' ,  ' , </span>'  , str_replace('li','span',$field->display)); ?> 
			<?php endforeach; ?>
		</div>
	</div><!-- end of provider-contact-info -->
<?php endif; ?>


<script type="text/javascript">
	function initialize() {
		var myLatLng = new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>);

		var mapOptions = {
		      zoom: 16,
		      center: myLatLng,
		      disableDefaultUI: false,
		      scrollwheel: false,
		      navigationControl: false,
		      mapTypeControl: false,
		      scaleControl: false,
		      draggable: true,
		      mapTypeControlOptions: {
		      mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'roadatlas']
		    }
		  };

		  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
		  
		   
		  var marker = new google.maps.Marker({
		      position: myLatLng,
		      map: map,
		      icon: 'http://www.pacificcross.com/templates/pacific-cross/images/location.png',
			  title: '',
		  });
		  
		  var contentString = '<div style="max-width: 300px" id="content">'+
		      '<div id="bodyContent">'+
			  '<h4 class="color-primary"><strong><?php echo $item->title; ?></strong></h4>' +
		      '<p style="font-size: 12px"><?php echo $providerAddress; ?>,<br>' +
		      '<?php echo $providerCity; ?> <?php echo $providerDistrict; ?>, <?php echo $providerStateProvince; ?>, <?php echo $providerCountry; ?></p>'+
		      '</div>'+
		      '</div>';


		  var infowindow = new google.maps.InfoWindow({
		      content: contentString
		  });
		  
		  google.maps.event.addListener(marker, 'click', function() {
		    infowindow.open(map,marker);
		  });

		  var styledMapOptions = {
		    name: 'US Road Atlas'
		  };

		  var usRoadMapType = new google.maps.StyledMapType(styledMapOptions);

		  map.mapTypes.set('roadatlas', usRoadMapType);
		  map.setMapTypeId('roadatlas');
		}

		// google.maps.event.addDomListener(window, "load", initialize);

		function loadScript() {
		  var script = document.createElement('script');
		  script.type = 'text/javascript';
		  script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&' +
		      'callback=initialize';
		  document.body.appendChild(script);
		}

		window.onload = loadScript;
</script>

<?php } /* EOF if html5  */ ?>
