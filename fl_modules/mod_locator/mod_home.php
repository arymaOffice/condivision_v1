<?php 

require_once('../../fl_core/autentication.php');
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo sitename; ?></title>

<!-- Smarthphone -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/a.png" />
<link href='//fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_cms.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/jquery2014.css"  media="screen, projection, tv" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set.$cp_client; ?>css/condivision3.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_print.css" media="print" />
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-1.8.3.js" type="text/javascript"></script>
<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/jquery-ui.js" type="text/javascript"></script>
<style type="text/css">
html, body, #map-canvas {
	height: 100%;
	margin: 0;
	padding: 0;
}
</style>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true&key=<?php echo API_KEY; ?>"></script>
<script type="text/javascript">
   var json = [
 <?php 
		   	$queryx = "SELECT id,nome,cognome,ragione_sociale,indirizzo_punto,comune_punto,cap_punto,lat,lon FROM `fl_anagrafica` WHERE 1 ORDER BY data_creazione DESC;";
			$res = mysql_query($queryx, CONNECT);

			while($valore = mysql_fetch_array($res)){
				echo '{';
                echo '"title": "'.$valore['ragione_sociale'].'",';
                echo '"lat": '.$valore['lat'].',';
                echo '"lng": '.$valore['lon'].',';
                echo '"description": "'.$valore['ragione_sociale'].'"';
                echo '},';

				} ?>
		        {
                "title": "",
                "lat": 0,
                "lng": 0,
                "description": ""
            }       
        ]
		


function initialize(lat,lon)
{
   var myCenter= new google.maps.LatLng(lat,lon);
 var mapProp = {
        center:myCenter,
        zoom:8,
        mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    var map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);

    var infowindow =  new google.maps.InfoWindow({
        content: ""
    });
	 var image = './icon.png';

    for (var i = 0, length = json.length; i < length; i++) {
        var data=json[i];
        var latLng = new google.maps.LatLng(data.lat, data.lng); 
        // Creating a marker and putting it on the map
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            title: data.title,
			icon: image
        });

        bindInfoWindow(marker, map, infowindow, data.description);
    } 
}

function bindInfoWindow(marker, map, infowindow, description) {
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(description);
        infowindow.open(map, marker);
    });
}

if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(mia_posizione);
}else{
  alert('La geo-localizzazione NON è possibile');
}

function mia_posizione(posizione) {
  var lat = posizione.coords.latitude;
  var lon = posizione.coords.longitude;
 initialize (lat,lon);
}


//google.maps.event.addDomListener(window, 'load', initialize);
  </script>
<script type="text/javascript">

function conferma(indirizzo,lat,lon,cap){
$('#indirizzo_punto', window.parent.document).val(indirizzo);
$('#lat', window.parent.document).val(lat);
$('#lon', window.parent.document).val(lon);
$('#cap_punto', window.parent.document).val(cap);
 window.parent.jQuery.fancybox.close();
}
</script>
</head>
<body>
<?php include('../../fl_inc/testata_mobile.php'); ?>
<div style="float: left; width: 20%; text-align: left; padding: 10px;">
  <h1 class="tab_green">Cerca affiliati</h1>
  <form action="" method="post">
    <p  class="select_text">
      <label>Regione:</label>
      <select name="regione">
        <option value="0">Seleziona...</option>
      </select>
    </p>
    <p  class="select_text">
      <label>Provincia:</label>
      <select name="provincia">
        <option value="0">Seleziona...</option>
      </select>
    </p>
    <p  class="input_text">
      <label>Città:</label>
      <input type="text" name="citta" value="Citta">
    </p>
    <p  class="input_text">
      <label>Cap:</label>
      <input type="text" name="cap" value="Cap">
    </p>
    <p  class="input_text">
      <label>Indirizzo:</label>
      <input type="text" name="indirizzo" value="Indirizzo">
    </p>
  </form>
  <a class="button salva" style="" href="#" onClick="initialize()">Cerca</a> 
  <!--<a href="#" onClick="initialize();" class="button salva">Mostra sulla mappa</a>


--></div>
<div id="map-canvas"></div>
</body>
</html>
