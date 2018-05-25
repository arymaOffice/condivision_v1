<?php


$challenge=$_REQUEST['hub_challenge'];
$token=$_REQUEST['hub_verify_token'];
if ($token === 'abc123'){
	echo $challenge;
}
//richiesta access token
$id = '334959493535208';
$secret = '3579e6434131047512913d63769e1f31';





/*Access token preso da: https://developers.facebook.com/tools/explorer/ */
//$access_token= 'EAAEwpNWgVegBAGh2t8kmWsgR2KYZA0vQXtOa4sARtFNXs53Q14PoId3ooCnG54UZAQ3XaZAJChdAH6DPpOULMkxHE80l1tAbffGJEOh2AN6Tb4nkJzJJf4ZBG8X1JCSo11M07WJxMUbhrXZCKly9dZAhBjAvfzt6ckOZBJeETfbLQZDZD';




	//print_r($ids_leadgen);
	echo $endpoint = 'https://graph.facebook.com/v2.8/836894613080001/?access_token='.$id.$secret;
	// Curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$endpoint);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	$data = curl_exec($ch);
	
	echo $data;
	if(curl_errno($ch) != 0) {
		$data = json_encode(array('Rc'=>1,'RcDs'=>'G007 ERRORE DI CONNESSIONE CURL ('.curl_error($ch).')'));
	}
	echo $data = json_decode($data);
	curl_close($ch);
	mail('supporto@aryma.it','Analisi dati Lead FB su '.$_SERVER['HTTP_HOST'],$data);



exit;



?>
