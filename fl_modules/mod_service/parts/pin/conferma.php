<?php 

require_once('../../../../fl_core/autentication.php');
include('../../../../fl_core/category/cms.php');
require_once('../../../../fl_core/class/ARY_dataInterface.class.php');
//require_once('../../../../fl_core/class/ARY_encryption.php');

$data_set = new ARY_dataInterface();
$secretKey = 'f34KlhYt67H4sGKQad3654FIlLY';

 /*
$e = new Encryption(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
$user = $e->encrypt($_SESSION['number'],$secretKey);
$_SESSION['checksum'] = sha1($user.$time.str_replace('.','',$_SERVER['SERVER_ADDR']));
$encryptedData = urlencode($e->encrypt(6666, $secretKey));
/*
echo "<br>Check Referer: ".$_SERVER['HTTP_REFERER'];
echo "<br>CHecKSum: <br> ".$encryptedData;
echo "<br>";
*/
$user = '';
$encryptedData = 666; 
$url = 'https://vpn.goservizi.it/ws/conferma/?checkSum='.$encryptedData;
$fields	= array('checkSum'=>$encryptedData,'test'=>1,'user'=>$_SESSION['number'],'OrigId'=>check($_GET['OrigId']),'Time'=>check($_GET['Time']),'offercode'=>check($_GET['offercode']));

$data = get_data($url,$fields);

$results = 'confirm=1';
foreach($_GET as $chiave => $valore) {  $results .='&'.$chiave.'='.check($valore); }

echo '<h1>CONFERMA: '.$data->RcDs.'</h1>';
if($data->Rc == '000') {

echo '<a id="fancybox" class="big-button green" href="../mod_ricevute/?'.$results.'"><i class="fa fa-print" style="font-size: larger; color: white;"></i> Stampa Scontrino </a>';

}


function get_data($endpoint,$fields){	
	$fields_string = '';
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$endpoint);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
	$data = curl_exec($ch);
	if(curl_errno($ch) != 0) { 
	$data = json_encode(array('Rc'=>1,'RcDs'=>'Impossibile connettere al Cliente VPN. ('.curl_error($ch).')')); 
	} 
	$data = json_decode($data);
	curl_close($ch);
	return $data;
	}

?>
<?php @mysql_close(CONNECT); ?>