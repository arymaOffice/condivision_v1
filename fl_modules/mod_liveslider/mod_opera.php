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

if (isset($_GET['nome_slider'])) { //salva slider

    $risoluzione = check($_GET['risoluzione']);
    $n_monitor = check($_GET['n_monitor']);
    $categoria_link = check($_GET['categoria_link']);
    $nome_slider = check($_GET['nome_slider']);
    $id = check($_GET['id']);

    echo $insert = "INSERT INTO fl_slider (`account_id`, `titolo`, `link`, `risoluzione`, `numero_monitor`) VALUES(" . $_SESSION['number'] . ",\"$nome_slider\",$id,$risoluzione,$n_monitor)";

    $insert = mysql_query($insert, CONNECT);

    mysql_close(CONNECT);
    header("Location: mod_inserisci.php?esito=Slider salvato con successo&success=1 ");
    exit;

}

if (isset($_GET['give_ads'])) {

    $categoria_link = GQS('fl_link_slider','categoria_link',' link_id = '.check($_GET['link_id']));
    $categoria_link = $categoria_link[0]['categoria_link'];


    $files = GQS('fl_dms d JOIN (

        SELECT id, parent_id
        FROM `fl_dms`
        WHERE `parent_id`
        IN (

        SELECT id
        FROM `fl_dms`
        WHERE `parent_id` =5
        )
        AND label = \'img\'
        )u ON d.parent_id = u.id JOIN fl_ads a ON a.folder_number = u.parent_id  ', '  CONCAT( d.parent_id ,"/",d.file ) path,','  a.categoria_ads = '.$categoria_link); //Crea un array con i file

        echo json_encode(array('esito' => 1, 'type' => 'img', 'src' => DMS_ROOT,'files'=>$files), JSON_UNESCAPED_SLASHES);
        exit;

}

header("Location: " . check($_SERVER['HTTP_REFERER']));
exit;
