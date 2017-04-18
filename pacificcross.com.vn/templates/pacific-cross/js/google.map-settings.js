// ==========  START GOOGLE MAP ========== //
function initialize() {
var myLatLng = new google.maps.LatLng(10.770798, 106.703000);

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
      icon: 'templates/pacific-cross/images/location.png',
	  title: '',
  });
  
  var contentString = '<div style="max-width: 300px" id="content">'+
      '<div id="bodyContent">'+
	  '<h5 class="color-primary"><strong>Pacific Cross Vietnam</strong></h5>' +
      '<p style="font-size: 12px">4th Floor, Continental Tower,' +
      '81-83-85 Ham Nghi St., District 1, HCMC, Vietnam</p>'+
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

// $('.thing').waypoint(function(direction) {
//   if (direction === 'down') {
//     // Do stuff
//   }
// }, {
//   offset: '25%'
// }).waypoint(function(direction) {
//   if (direction === 'up') {
//     // Do stuff
//   }
// }, {
//   offset: '75%'
// });

// var waypoint = new Waypoint({
//   element: document.getElementById('map-canvas'),
//   handler: function() {
//     loadScript();
//   },
//   offset: 300
// });

// ========== END GOOGLE MAP ========== //