<?php 

require_once('../../fl_core/autentication.php');


include("../../fl_inc/headers.php");
$lat = check($_GET['lat']);
$lon = check($_GET['lon']);

 ?>

<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
  

    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo API_KEY; ?>"></script>
    
    
    <script type="text/javascript">
   function initialize() {
  var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lon; ?>);
  var mapOptions = {
    zoom: 16,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var contentString = '';

  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Posizione'
  });
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>



<div id="map-canvas"></div>


  </body>
</html>
