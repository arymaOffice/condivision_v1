<?php

include '../fl_config/' . $_SERVER['HTTP_HOST'] . '/customer.php'; // info customer


function smail($destinatario,$soggetto,$messaggio){
        
    require_once('../fl_set/librerie/PHPMailer/PHPMailerAutoload.php');
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = mail_host;
    $mail->SMTPSecure = 25; 
    $mail->Port = Port; 
    $mail->Username = mail_user;
    $mail->Password = mail_password;
    $mail->CharSet = 'UTF-8';
    
    $nameFrom = mail_name; 
    $from =  mail_user; 
    $mail->setFrom($from, $nameFrom);
        
    
    $mail->addAddress($destinatario,$destinatario);
    $mail->Subject = $soggetto;
    $mail->Body = $messaggio; 
    $mail->AltBody = 'To view this email please enable HTML';

    
    if(!$mail->send()) {
    mail(mail_admin,ROOT.'..:: Errore funzione smail '.mail_host.' port '.Port,$mail->ErrorInfo);
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
            $password_pulita = str_replace('.', '', microtime(true));
            echo smail($email,'Attivazione account 1x2live','Password : '.$password_pulita);
            $password = md5($password_pulita);

            $insert = "INSERT INTO `fl_account`(`attivo`, `anagrafica`, `marchio`, `tipo`,  `email`, `nominativo`,  `user`, `password`, `ip`) VALUES (
                1,1,1,1,'$email','$name','$email','$password','$ip') ";
            if ($conn->query($insert)) {

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
