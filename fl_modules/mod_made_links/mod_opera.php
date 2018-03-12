<?php

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo

if($_GET['form']){

    $params = array();
    parse_str($_GET['form'], $params);

    $link_id=check($_GET['link_id']);

    $links = array('esito' => '', 'data' => array());

    $token = GQD('fl_token', 'token', 'account_id = '.$_SESSION['number']);
    $token = $token['token'];

    $pollici = GQD('fl_link_resolution', 'risoluzione', 'id = '.$params['pollici']);

    $external = $pollici['risoluzione'];


    if(isset($params['n_monitor']) && isset($params['pollici'])){

        for($i = 1; $i <= $params['n_monitor']; $i++){
            array_push($links['data'],'http://livescore.gcsoft.it/index'.$external.'.html?id='.$link_id.'&monitor_id='.$i.'&monitor_count='.$params['n_monitor'].'&token='.$token);
        }

        $links['esito'] = 1;


    }elseif(isset($params['pollici'])){

        
        array_push($links['data'],'http://livescore.gcsoft.it/index'.$external.'.html?id='.$link_id.'&monitor_id=1&monitor_count=1'.'&token='.$token);
        $links['esito'] = 1;
        
    }else{
        $links['esito'] = 0;
        
    }

    echo json_encode($links,true);
    exit;
}


mysql_close(CONNECT);
exit;

?>
