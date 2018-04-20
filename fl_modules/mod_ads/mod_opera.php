<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

//selezione cartelle pubblicità per categoria
if (isset($_POST['pubblicita'])) {

    $categoria_ads = filter_var($_POST['categoria_ads'], FILTER_SANITIZE_NUMBER_INT);

    //delete old folders
    $delete = "DELETE FROM fl_ads WHERE categoria_ads =  " . $categoria_ads;
    mysql_query($delete, CONNECT);

    $folders = array();

    if (isset($_POST['cartelle'])) {
        foreach ($_POST['cartelle'] as $key) {

            $folder_id = filter_var($key, FILTER_SANITIZE_NUMBER_INT);
            //insert the new folder
            $insert = "INSERT INTO fl_ads (categoria_ads,folder_number) VALUES ('$categoria_ads','$folder_id')";
            mysql_query($insert, CONNECT);

        }
    }

    mysql_close(CONNECT);
    header("Location: ./?a=ads");
    exit;

}

mysql_close(CONNECT);
header("Location: " . check($_SERVER['HTTP_REFERER']));
exit;
