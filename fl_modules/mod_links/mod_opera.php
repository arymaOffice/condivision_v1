<?php

// Controllo Login
require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo


//Inserisci Aggiorna
if (isset($_POST['link'])) {

    if ($_POST['link'] == "") {
        @mysql_close(CONNECT);
        $chiave = ucfirst($chiave);
        header("Location: ./?&action=9&&ok=1&esito=Inserire valore per il campo $chiave");
        exit;
    }

    $link = check($_POST['link']);
    $categoria_id = check($_POST['categoria_id']);
    $link_cat_id = check($_POST['link_cat_id']);
    $marchio_id = check($_POST['marchio_id']);
    $descrizione = check($_POST['descrizione']);
    $codice = check($_POST['codice']);

    $query = "INSERT INTO $tabella ( `categoria_id`, `link_cat_id`, `marchio_id`, `codice`, `descrizione`, `link`) VALUES ('$categoria_id','$link_cat_id','$marchio_id','$codice','$descrizione','$link')"; 

    if (mysql_query($query, CONNECT)) {

        @mysql_close(CONNECT);
        header("Location: ./");
        exit;
    } else {
        @mysql_close(CONNECT);
        header("Location: ./?action=9&ok=1&esito=ERROR 1101: Errore di Inserimento!&articoli=1&id=$relation");
        exit;}
}
