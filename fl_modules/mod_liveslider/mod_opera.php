<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

// Modifica Stato se è settata $stato
if (isset($_GET['stato_prenotazione'])) {
    $stato_prenotazione = check($_GET['stato_prenotazione']);
    $id = check($_GET['id']);
    $query1 = "UPDATE fl_prenotazioni SET stato_prenotazione = $stato_prenotazione WHERE `id` = $id";
    mysql_query($query1, CONNECT);
}

if (isset($_GET['nome_slider'])) {

    $risoluzione = check($_GET['risoluzione']);
    $n_monitor = check($_GET['n_monitor']);
    $categoria_link = check($_GET['categoria_link']);
    $nome_slider = check($_GET['nome_slider']);
    $id = check($_GET['id']);

    $insert = "INSERT INTO fl_slider (`account_id`, `titolo`, `link`, `risoluzione`, `numero_monitor`) VALUES(" . $_SESSION['number'] . ",\"$nome_slider\",$id,$risoluzione,$n_monitor)";

    $insert = mysql_query($insert, CONNECT);

    mysql_close(CONNECT);
    header("Location: mod_user.php?esito=Slider salvato con successo&success=1 ");
    exit;

}

header("Location: " . check($_SERVER['HTTP_REFERER']));
exit;
