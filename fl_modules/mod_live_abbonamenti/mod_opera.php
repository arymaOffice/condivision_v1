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

mysql_close(CONNECT);
header("Location: ".check($_SERVER['HTTP_REFERER'])); 
exit;

?>
