<?php


// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] != "POST") { // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Ci sono problemi con l'invio del form!";
    exit;
}

$name = strip_tags(trim($_POST["sendername"]));
$phone = filter_var(trim($_POST["senderphone"]),FILTER_SANITIZE_NUMBER_INT);
$email = filter_var(trim($_POST["senderemail"]), FILTER_SANITIZE_EMAIL);
$password = filter_var($_POST["password"],FILTER_SANITIZE_STRING);
$subject = filter_var(@$_POST["subject"],FILTER_SANITIZE_STRING);
$promoCode = filter_var(@$_POST["promoCode"],FILTER_SANITIZE_STRING);
$dataMatrimonio = filter_var(@$_POST["dataMatrimonio"],FILTER_SANITIZE_STRING);



$pass = md5($password);
$newPsw = $password;	

// Check that data was sent to the mailer.
if ( empty($name) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Set a 400 (bad request) response code and exit.
    http_response_code(400);
    echo "Inserisci nome, email e password!";
    var_dump($_POST);
    exit;
}

include 'config.php';

if(empty($password)){
$hash = time();
$newPsw = substr($hash,0,5);
$pass = md5($newPsw);
}

$queryCreateAccount = "INSERT INTO `fl_account` (`motivo_sospensione`,`attivo` ,`tipo` , `email` ,`nominativo` ,`user` ,`password` ,`data_creazione`,`data_scadenza`,`data_aggiornamento`,`operatore`, `aggiornamento_password`)
					 VALUES ('$subject Si sposano: $dataMatrimonio Promo: $promoCode', 0, 3,'$email','$name','$email', '$pass', NOW(), '2050-12-31', NOW(), 1,NOW() );";
$queryCreateAccount = $mysqli->query($queryCreateAccount);
$account_id = $mysqli->insert_id;

$mail_template = file_get_contents('register.html');
$mail_template = str_replace('{{email}}',$email,$mail_template);
$mail_template = str_replace('{{password}}',$newPsw,$mail_template);
$mail_template = str_replace('{{nome}}',$name,$mail_template);
$mail_template = str_replace('{{firma}}','Matrimonioincloud.it',$mail_template);
$mail_template = str_replace('{{subject}}','Matrimonioincloud.it',$mail_template);
$mail_template = str_replace('{{codeBase64}}','Iscrizione Sposi per App '.$subject,$mail_template);

if($mysqli->insert_id != 0){

	smail($email,'Accesso App di '.$appName,$mail_template);
	echo 'Grazie per esserti iscritto! Ti abbiamo inviato una mail';

}else{
	echo 'Risulti gia registrato con questa email!';

}

?>