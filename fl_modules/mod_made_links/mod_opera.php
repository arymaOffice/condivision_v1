<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

if ($_GET['form']) {

    $params = array();
    parse_str($_GET['form'], $params);

    $link_id = check($_GET['link_id']); //link id esterno per il servizio
    $id = check($_GET['id']);//id del link nel nostro db

    $links = array('esito' => '', 'data' => array()); //links mostrati

    //token che bailita la visione del sdervizio
    $token = GQD('fl_token', 'token', 'account_id = ' . $_SESSION['number']);
    $token = urlencode($token['token']);

    $pollici = GQD('fl_link_resolution', 'risoluzione', 'id = ' . $params['pollici']);

    $external = $pollici['risoluzione'];

    //inserimento per statistiche risoluzione monitor
    mysql_query('INSERT INTO fl_data_link_res (id_link,id_risoluzione,id_account) VALUES (' . $id . ',' . $params['pollici'] . ',' . $_SESSION['number'] . ')', CONNECT);

    if (isset($params['n_monitor']) && isset($params['pollici'])) {
        $params['n_monitor'] = check($params['n_monitor']);
        //inserimento per statistiche numero monitor
        mysql_query('INSERT INTO fl_data_link_mon (id_link,n_monitor,id_account) VALUES (' . $id . ',' . $params['n_monitor'] . ',' . $_SESSION['number'] . ')', CONNECT);

        for ($i = 1; $i <= $params['n_monitor']; $i++) {
            array_push($links['data'], 'https://service.1x2live.it/index' . $external . '.html?id=' . $link_id . '&monitor_id=' . $i . '&monitor_count=' . $params['n_monitor'] . '&token=' . $token);
        }

        $links['esito'] = 1;

    } elseif (isset($params['pollici'])) {

        //inserimento per statistiche numero monitor
        mysql_query('INSERT INTO fl_data_link_mon (id_link,n_monitor,id_account) VALUES (' . $id . ',1,' . $_SESSION['number'] . ')', CONNECT);

        array_push($links['data'], 'https://service.1x2live.it/index' . $external . '.html?id=' . $link_id . '&monitor_id=1&monitor_count=1' . '&token=' . $token);
        $links['esito'] = 1;

    } else {
        $links['esito'] = 0;

    }

    echo json_encode($links, true);
    exit;
}

mysql_close(CONNECT);
exit;
