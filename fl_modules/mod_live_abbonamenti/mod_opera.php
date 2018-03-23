<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


if(isset($_POST['servizi_add']) ){//aggiunta servizi all'abbonamneto

    $id_abb = check($_POST['id_abb']);

    $delete = 'DELETE FROM `fl_ser_abb` WHERE id_abb = '.$id_abb;
    $delete = mysql_query($delete,CONNECT);

    if(!isset($_POST['servizi'])){
        mysql_close(CONNECT);
        header('Location: ./mod_servizi.php?esito=1&info_txt=servizi rimossi correttamente&record_id='.$id_abb);
        exit;
    }

    $insert_value = '';

    foreach($_POST['servizi'] as $value){ //recupero array dei servizi
        $insert_value .= '('.$id_abb.','.check($value).'),';
    }
 
    $insert_value  = trim($insert_value,',');

    $insert = 'INSERT INTO `fl_ser_abb`(`id_abb`, `id_ser`) VALUES '.$insert_value;

    if(mysql_query($insert,CONNECT)){
        mysql_close(CONNECT);
        header('Location: ./mod_servizi.php?esito=1&info_txt=servizi aggiunti correttamente&record_id='.$id_abb);
        exit;
    }else{
        mysql_close(CONNECT);
        header('Location: ./mod_servizi.php?esito=0&info_txt=servizi non aggiornati&record_id='.$id_abb);
        exit;
    }
}

//mod_user.php Acquista abbonamento (uno solo per volta)

if(isset($_GET['abb'])){

    $abb_id = filter_var(base64_decode($_GET['abb']),FILTER_SANITIZE_NUMBER_INT);
    $abb_info = GQD('fl_abbonamenti as abb JOIN fl_periodi pd ON pd.id = abb.periodo ','durata,label,costo','abb.id = '.$abb_id);

    $insert = "INSERT INTO `fl_abb_user` (`id_user`, `id_abb`, `data_avvio`, `data_fine`, `data_creazione`, `data_blocco`, `note`, `prezzo`) VALUES (
        '".$_SESSION['number']."','".$abb_id."',NOW(),DATE_ADD(NOW(), INTERVAL ".$abb_info['durata']." ".$abb_info['label']."),NOW(),'0000-00-00','','".$abb_info['costo']."')";

    $insert = mysql_query($insert,CONNECT);


    if(mysql_insert_id() > 0){

        $_SESSION['scelta_abbonamento'] = 0;

        mysql_close(CONNECT);
        header('Location: /fl_modules/mod_live_abbonamenti/?action=26&esito=1&info_txt=acquisto abbonamento riuscito con successo');
        exit;

    }else{

        mysql_close(CONNECT);
        header('Location: /fl_modules/mod_live_abbonamenti/?action=26&esito=0&info_txt=acquisto abbonamento non riuscito');
        exit;
    }

}

mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
