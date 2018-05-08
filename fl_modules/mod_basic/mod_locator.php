<?php

require_once('../../fl_core/autentication.php');

unset($nochat);

include("../../fl_inc/headers.php");
$lat = check($_GET['lat']);
$lon = check($_GET['lon']);
$indirizzo = check($_GET['indirizzo']);
$cap =   check($_GET['cap']);
$city = check($_GET['city']);
$provincia = check($_GET['provincia']);

 ?>

<!DOCTYPE html>
<html>
  <head>
    <style type="text/css">
      html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
    </style>
    <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=AIzaSyAZlP_VevSuAyrZ633RNaCbXG1MUXYyh0M"></script>


    <script type="text/javascript">
   function initialize() {

  var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lon; ?>);
  var mapOptions = {
    zoom: 16,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var contentString = '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">Posizione</h1>'+
      '<div id="bodyContent">'+
      '<p>'+ '<?php echo $indirizzo; ?>' + '</p>'+
      '</div>'+
      '</div>';

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
  <script type="text/javascript">

function conferma(indirizzo,lat,lon,cap){
<?php if(check($_GET['label_lat']) != ''){ ?>$('#<?php echo check($_GET['label_lat']); ?>', window.parent.document).val(lat);<?php } ?>
<?php if(check($_GET['label_lon']) != ''){ ?>$('#<?php echo check($_GET['label_lon']); ?>', window.parent.document).val(lon);<?php } ?>
var after_indirizzo = (indirizzo == '') ? '' : $('#<?php echo check($_GET['label_indirizzo']); ?>', window.parent.document).val(indirizzo);
var after_cap = (cap == '') ? '' :$('#<?php echo check($_GET['label_cap']); ?>', window.parent.document).val(cap);
 window.parent.jQuery.fancybox.close();
}
</script>
<div style="float: left; width: 50%; text-align: left; padding: 10px;">
  <h1 class="tab_green">Indirizzo rilevato</h1>
  <h2>INDIRIZZO: <?php  if ($indirizzo == '') {
    echo "<script type='text/javascript'>alert('Indirizzo non trovato ');</script>"; echo 'Non trovato';}else{echo $indirizzo ; }   ?></h2>
  <h2>CAP: <?php   if ($cap == '') {
    echo "<script type='text/javascript'>alert('Cap non trovato');</script>"; echo 'Non trovato';
  }else{ echo $cap ; }   ?></h2>
  <h2>CITTA: <?php echo $city; ?></h2>
  <h2>PROVINCIA: <?php echo $provincia; ?></h2>
  <a class="button salva" style="position: absolute; bottom: 0; left: 0; width: 50%;" href="#" onClick="conferma('<?php echo $indirizzo; ?>','<?php echo $lat; ?>','<?php echo $lon; ?>','<?php echo $cap; ?>');">Conferma</a>
<!--<a href="#" onClick="initialize();" class="button salva">Mostra sulla mappa</a>


--></div>

<div id="map-canvas"></div>


  </body>
</html>
