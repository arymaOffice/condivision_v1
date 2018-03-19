<?php



$code = 21;//filter_var(trim(base64_decode($_GET["matrimonio_id"])),FILTER_SANITIZE_NUMBER_INT);

session_start();
$_SESSION['matrimonio_id'] = $code;
if(!isset($_SESSION['matrimonio_id'])) header('Location: http://www.matrimonioincloud.it');


//connessione mysqli
	$mysqli = new mysqli("vps6875.mondoserver.com", "usr_matricloud", "w07Imz&1", "db_matrimonioincloud");

	if(!$mysqli){
		echo "Failed to connect to the db";
		exit;
	}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/* DATI DA RECUPERARE DA TABELLA fl_ */
$appName = 'Vito Michele & Alessadra';
$brand = 'Condivision Wedding';
$appSubTitle = '10.07.2018';
$socialSposo = 'https://www.facebook.com/michelefazio1982';
$socialSposa = 'https://www.facebook.com/alessandra.cocca';

$endpointIMMAGINI = "../../condivision/fl_config/www.matrimonioincloud.it/files/";
$account_id = $_SESSION['matrimonio_id'];//account test
$dms = 'fl_dms';
$tavoli_commensali = 'fl_tavoli_commensali';
$fl_places = 'fl_places';
$fl_places_info = 'fl_places_info';
$fl_wishlist = 'fl_wishlist';
$fl_wishlist_immagini = 'fl_wishlist_immagini';
$fl_app_modules = 'fl_app_modules';


$color2 = '#C394AE';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* Configurazione Casella Mail */
define("mail_host","mail.aryma.it");
define("mail_name","Matrimonio in cloud");
define("mail_user","info@aryma.it");
define("mail_password","pappero");
define("reply_to",'info@aryma.it');
define("mail_admin","support@aryma.it");

function smail($destinatario,$soggetto,$messaggio,$from='',$nameFrom='',$allegato='',$allname=''){
	if(!defined('SMTPSecure')) define('SMTPSecure','tls');
	if(!defined('Port')) define('Port','587');

	require_once('../vendor/autoload.php');
	try {
		$mail = new PHPMailer;

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
$mail->isSMTP();                                      // Set mailer to use SMTP


$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;

$mail->SMTPAuth = true;
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = mail_host;


$mail->Username = mail_user;
$mail->Password = mail_password;
$mail->setFrom(mail_user, mail_name);
//if($from != '') { $mail->setFrom($from, $nameFrom);  } else { $mail->setFrom(mail_user, mail_name); }
($from != '') ? $mail->addReplyTo($from, $nameFrom) : $mail->addReplyTo(mail_user, mail_name);

$mail->addAddress($destinatario,$destinatario);
$mail->Subject = $soggetto;
$mail->Body = $messaggio;
$mail->isHTML(true);
$mail->AltBody = 'To view this email please enable HTML';
if($allegato != '') $mail->addAttachment($allegato, $allname);


if(!$mail->send()) {
	mail(mail_admin,'..:: Errore funzione smail '.mail_host.' port '.Port,$mail->ErrorInfo);
	return $mail->ErrorInfo;
} else {
	return true;
}
} catch (phpmailerException $e) {
    $errors[] = $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
    $errors[] = $e->getMessage(); //Boring error messages from anything else!
}

$mail->clearAddresses();
$mail->clearAttachments();

}

function getModuleData($matrimonio_id){
	global $fl_app_modules;
	global $mysqli;

	//definizione titoli
	$querySubtitle = "SELECT module_type as id,UPPER(module_title) as title,subtitle,UPPER(call_to_action) as call_to_action,content FROM $fl_app_modules WHERE workflow_id = 130 AND record_id = $matrimonio_id AND active=1";
	$querySubtitle = $mysqli->query($querySubtitle);
	while($row= $querySubtitle->fetch_assoc()){
		$GLOBALS['mod_'.$row['id'].'_title'] =  $row['title'];
		$GLOBALS['mod_'.$row['id'].'_subtitle'] =  $row['subtitle'];
		$GLOBALS['mod_'.$row['id'].'_call_to_action'] =  $row['call_to_action'];
		$GLOBALS['mod_'.$row['id'].'_content'] =  html_entity_decode($row['content']);
	}
}

getModuleData($account_id);


?>
