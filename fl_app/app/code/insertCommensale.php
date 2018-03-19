

<?php 
// Only process POST reqeusts.
if ($_SERVER["REQUEST_METHOD"] != "POST") { // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "Ci sono problemi con l'invio del form!";
    exit;
}

$name = strip_tags(trim($_POST["sendername"]));
$name = str_replace(array("\r","\n"),array(" "," "),$name);
$phone = filter_var(trim($_POST["senderphone"]),FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST["senderemail"]), FILTER_SANITIZE_EMAIL);
$message = filter_var(trim(@$_POST["sendermessage"]),FILTER_SANITIZE_STRING);
$selectedValue = filter_var($_POST["opzioni"],FILTER_SANITIZE_NUMBER_INT);
$adulti = filter_var(@$_POST["adulti"],FILTER_SANITIZE_NUMBER_INT);
$bambini = filter_var(@$_POST["bambini"],FILTER_SANITIZE_NUMBER_INT);
$neonati = filter_var(@$_POST["neonati"],FILTER_SANITIZE_NUMBER_INT);


// Check that data was sent to the mailer.
if ( empty($name) OR !filter_var($email, FILTER_VALIDATE_EMAIL) OR empty($selectedValue)) {
    // Set a 400 (bad request) response code and exit.
    http_response_code(400);
    echo "Inserisci nome, email e seleziona una risposta!";
    exit;
}

include 'config.php';
//query inserimento commensale
$queryInsertComme="INSERT INTO $tavoli_commensali (app_id,tavolo_id,cognome,telefono,email,adulti,bambini,sedie,seggioloni,note_intolleranze,tipo_commensale,data_creazione,data_aggiornamento) VALUES ($account_id,0,'$name','$phone','$email','$adulti','$bambini',0,'$neonati','$message','$selectedValue',NOW(),NOW())";
$queryInsertComme=$mysqli->query($queryInsertComme);

$newPsw = '';
for($i = 0;$i <5; $i ++){
	$newPsw .= rand(0,9);
}
$pass = $newPsw; //Password da mandare all'utente e visualizzare
$newPsw = base64_encode($newPsw); //hash password per database


$queryCreateAccount = "INSERT INTO cc_users(first_name,matrimonio_id,email,password) VALUES ('$name','$account_id','$email','$newPsw')";
$queryCreateAccount = $mysqli->query($queryCreateAccount);

$mail_template = file_get_contents('mail.html');
$mail_template = str_replace('{{email}}',$email,$mail_template);
$mail_template = str_replace('{{password}}',$pass,$mail_template);
$mail_template = str_replace('{{nome}}',$name,$mail_template);
$mail_template = str_replace('{{sposi}}',$appName,$mail_template);
$mail_template = str_replace('{{codeBase64}}',$account_id,$mail_template);

if($mysqli->insert_id != 0){

	smail($email,'Accesso App di '.$appName,$mail_template);
	echo 'Grazie di aver risposto alla partecipazione. Ti abbiamo inviato una mail di conferma. LA TUA PASSWORD DI ACCESSO: '.$pass;

}else{
	echo 'non inserito';

}