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

    $files = array();
    $numero = filter_var($_GET['give_ads'],FILTER_SANITIZE_NUMBER_INT);

    if ($handle = opendir(DMS_ROOT . '5/')) {
        /* This is the correct way to loop over the directory. */
        while (false !== ($entry = readdir($handle))) {
            $files[] = $entry;
        }
        closedir($handle);

        //effettuare controllo sul file come numero da caricare

        echo json_encode(array('esito' => 1, 'type' => 'img', 'src' => DMS_ROOT . '5/'.@$files[$numero]), JSON_UNESCAPED_SLASHES);

    } else {
        echo json_encode(array('esito' => 0));

    }

    exit;

}

header("Location: " . check($_SERVER['HTTP_REFERER']));
exit;
