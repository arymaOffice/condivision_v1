<?php
@require_once('../../fl_core/autentication.php');
//if(!strstr($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST'])) { echo "Non si accettano richieste da remoto"; exit; }

$address = str_replace(" ","%20",check(@$_POST['address']));
$city = str_replace(" ","%20",check(@$_POST['city']));


$status = 'ZERO_RESULTS';
$lat = 'None';
$lon = 'None';
$location_type = 'Location: No results!';
$formatted_address = '';



$url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=it&language=it&components=administrative_area:$city|country:IT";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);
$response_a = json_decode($response);


if(@$response_a->status == 'OK'){
$status = $response_a->status;
$lat = $response_a->results[0]->geometry->location->lat;
$lon = $response_a->results[0]->geometry->location->lng;
$compontents = array();
	foreach($response_a->results[0]->address_components as $chiave){
		$label =  $chiave->types[0];
		$compontents[$label] =  $chiave->long_name;
		}
$location_type = (isset($compontents['route']) && @$compontents['route'] != '') ? @$compontents['route'].", ".@$compontents['street_number'] : '';
$postal_code = (isset($compontents['postal_code']) && @$compontents['postal_code'] != '') ? @$compontents['postal_code'] : '';
$formatted_address = $response_a->results[0]->formatted_address; // ".@$compontents['street_number'];
}

/*
$url = "http://maps.google.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false&region=Italy&language=it";


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);
$response_a = json_decode($response);

if(@$response_a->status == 'OK'){
$status = $response_a->status;
$lat = $response_a->results[0]->geometry->location->lat;
$lon = $response_a->results[0]->geometry->location->lng;
$location_type = 'Location type: '.$response_a->results[0]->geometry->location_type;
$formatted_address = $response_a->results[0]->formatted_address;
}
*/

$latlon = array('status'=>$status,'formatted_address'=>$formatted_address,'lat'=>$lat,'lon'=>$lon,'location_type'=>$location_type,'postal_code'=>$postal_code);

echo json_encode($latlon);
?>