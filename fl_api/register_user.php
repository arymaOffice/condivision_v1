<?php

include '../fl_config/' . $_SERVER['SERVER_NAME'] . '/customer.php'; // info customer

function smail($destinatario, $soggetto, $messaggio)
{

    require_once '../fl_set/librerie/PHPMailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = mail_host;
    $mail->SMTPSecure = 25;
    $mail->Username = mail_user;
    $mail->Password = mail_password;
    $mail->CharSet = 'UTF-8';

    $nameFrom = mail_name;
    $from = mail_user;
    $mail->setFrom($from, $nameFrom);

    $mail->addAddress($destinatario, $destinatario);
    $mail->Subject = $soggetto;
    $mail->Body = $messaggio;
    $mail->AltBody = 'To view this email please enable HTML';

    if (!$mail->send()) {
        mail(mail_admin, ROOT . '..:: Errore funzione smail ' . mail_host . ' port ' . Port, $mail->ErrorInfo);
        return $mail->ErrorInfo;
    } else {
        return true;
    }

    $mail->clearAddresses();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { //se la richiesta è in post
    if (count($_POST) == 4) { //se mi sono stati inviati valori in post
        $name = filter_var(@$_POST['name'], FILTER_SANITIZE_STRING);
        $surname = filter_var(@$_POST['surname'], FILTER_SANITIZE_STRING);
        $email = filter_var(@$_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var(@$_POST['phone'], FILTER_SANITIZE_NUMBER_INT);

        if ($name == '' || $surname == '' || $email == '' || $phone == '') {header('Location: ../fl_app/form1x2live/index.php?col=1&esito=Compilare tutti valori');exit;}

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            // Create connection
            $conn = new mysqli($host, $login, $pwd, $db);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $ip = $_SERVER['REMOTE_ADDR'];

            $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

            // Crea una password.
            $hash = hash('sha512', time() . $random_salt);
            $password_pulita = substr(md5($hash), 0, 10);

            $password = md5($password_pulita);

            $creaAnagrafica = "INSERT INTO fl_anagrafica () VALUES ()";
            $creaAnagrafica = $conn->query($creaAnagrafica);
            $creaAnagrafica = $conn->insert_id;

            $insert = "INSERT INTO `fl_account`(`attivo`, `anagrafica`, `marchio`, `tipo`,  `email`, `nominativo`,  `user`, `password`, `ip`,data_creazione,data_scadenza,operatore,aggiornamento_password) VALUES (
                1,'$creaAnagrafica',1,1,'$email','$name','$email','$password','$ip',NOW(),DATE_ADD(NOW(), INTERVAL 30 YEAR),1,DATE_ADD(NOW(), INTERVAL 12 MONTH) ) ";
            $conn->query($insert);
            if ($conn->insert_id > 0) {
                smail($email, 'Attivazione account 1x2live', '<br><br> Ciao ' . $name . ', <br> hai completato con successo la registrazione al servizio 1x2 Live.  <br><br> Per accedervi clicca su questo link
            <a href="https://' . $_SERVER['SERVER_NAME'] . '">https://' . $_SERVER['SERVER_NAME'] . '</a> , <br><br> ed effetua il login con le seguenti credenziali <br><br>User : ' . $email . '  Password : ' . $password_pulita);

                //aggiunger controllo smail

                header('Location: ../fl_app/form1x2live/index.php?col=0&esito=Controlla la casella di posta per attivare l\'account');exit;
            } else {
                header('Location: ../fl_app/form1x2live/index.php?col=1&esito=Inserimento non riuscito');exit;
            }

        } else {
            header('Location: ../fl_app/form1x2live/index.php?col=1&esito=email non valida');exit;
        }
    } else {
        header('Location: ../fl_app/form1x2live/index.php?col=1&esito=Compilare tutti valori');exit;
    }
}
