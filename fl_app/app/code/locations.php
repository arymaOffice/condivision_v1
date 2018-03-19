<?php
include_once('config.php');//inclusione file configurazione

//luogi degli sposi
$queryPlaces = "SELECT p.place_id,place_name,X(place_point) lat ,Y(place_point) lon ,address,descrizione_luogo,recapito_telefonico,website,email,url FROM $fl_places p JOIN $fl_places_info pi On p.id = pi.place_id WHERE  account_id = $account_id";
$queryPlaces = $mysqli->query($queryPlaces);
$pDamostrare = '';
$markers='';
$i = 0;
if($mysqli->affected_rows>0){
	while ($row = $queryPlaces->fetch_assoc()){
		//intestazione con descrizione
		$pDamostrare .= '<p>'.$row['descrizione_luogo'].' <strong>'.$row['place_name'].'</strong></p><div class="isg-table"><div class="isg-table-row"><div><p style="text-align: left;"><i class="fa fa-map"></i> <strong>Indirizzo</strong></p> <p style="text-align: left;">'.$row['address'].'</p>';
		//creazione url raggiungi luogo con tre alternative
    if($row['url'] OR $row['place_id'] OR $row['lat'] !='' OR $row['lon'] != ''){
        if($row['url'] != ''){
      		$pDamostrare .= '<p style="text-align: left;"><a href="'.$row['url'].'" class="isg-button">RAGGIUNGI IL LUOGO</a></p> </div>';
				}elseif($row['place_id'] != ''){
				      		$pDamostrare .= '<p style="text-align: left;"><a href="https://www.google.com/maps/search/?api=1&query_place_id='.$row['place_id'].'" class="isg-button">RAGGIUNGI IL LUOGO</a></p> </div>';
				}elseif($row['lat'] != '' AND $row['lon'] != ''){
				      		$pDamostrare .= '<p style="text-align: left;"><a href="https://www.google.com/maps/search/?api=1&query='.$row['lat'].','.$row['lon'].'" class="isg-button">RAGGIUNGI IL LUOGO</a></p> </div>';
				}
    }
		$pDamostrare .= '<div>';
    if($row['recapito_telefonico']){
     $pDamostrare .= '<p style="text-align: left;"><i class="fa fa-phone-square"></i> <strong>Recapiti</strong></p> <p style="text-align: left;"><a href="tel:'.$row['recapito_telefonico'].'">'.$row['recapito_telefonico'].'</a></p>';
    }
		if($row['website']){
		 $pDamostrare .= '<p style="text-align: left;"><a target="_blank" href="'.$row['website'].'">'.$row['website'].'</a></p>';
		}
		if($row['email'] ){
		 $pDamostrare .= '<p style="text-align: left;"><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></p>';
		}
		$pDamostrare .= '</div></div></div>';

		//markers mappa

		$markers.= "var contentString".$row['place_id']." = '<div id=\"content\">".$row['place_name']."<br><a target=\"_blank\" style=\"color:blue\" href=\"https://www.google.com/maps/search/?api=1&query=".$row['lat'].','.$row['lon']."\"> Raggiungi luogo</a></div>';

			var infowindow".$row['place_id']." = new google.maps.InfoWindow({
				content: contentString".$row['place_id']."
			});

			var marker".$row['place_id']." =  new google.maps.Marker({
				position: new google.maps.LatLng('".$row['lat']."','".$row['lon']."') ,
				icon: {
			path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
			scale: 8,
			fillColor: '#5a041c',
			strokeColor: '#5a041c'
		},
				map: map,
				title: '".$row['place_name']."',
				class: \"isg-pin\",
				draggable: true
		});
		marker".$row['place_id'].".addListener('click', function() {
			infowindow".$row['place_id'].".open(map, marker".$row['place_id'].");
	});";
	}
}

?>
