<?php
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>
<style>
.paginazione{
	display: flex;
}
</style>
<?php
$start = paginazione(CONNECT, $tabella, $step, $ordine, $tipologia_main);
$query = "SELECT $select, s.titolo as sliderTitle , s.id as sliderId , lr.pollici, lr.risoluzione , s.numero_monitor , ls.titolo as linkTitle,lc.descrizione, ls.link_id FROM `$tabella` s LEFT JOIN fl_link_slider ls ON ls.id = s.link LEFT JOIN fl_link_cat lc ON lc.id = ls.categoria_link LEFT JOIN fl_link_resolution lr ON lr.id = s.risoluzione  WHERE s.id != 1 AND s.account_id = ".$_SESSION['number']." ORDER BY s.$ordine LIMIT $start,$step;";
$risultato = mysql_query($query, CONNECT);

if (mysql_affected_rows() == 0) {echo "Nessun Elemento";}

while ($riga = mysql_fetch_array($risultato)) {

    $slider_info = GQS($tabella . ' s LEFT JOIN fl_link_resolution ls ON ls.id = s.risoluzione ', 'titolo,numero_monitor,pollici,ls.risoluzione,link', 's.id = ' . $riga['sliderId']);

    $token = GQD('fl_token', 'token', 'account_id = ' . $_SESSION['number']);
    $token = urlencode($token['token']);


    $external = $slider_info[0]['risoluzione'];

    $monitor_links = '';

    for ($i = 1; $i <= $slider_info[0]['numero_monitor']; $i++) {

        $link = 'http://livescore.gcsoft.it/index' . $external . '.html?id=' . $riga['link_id'] . '&monitor_id=' . $i . '&monitor_count=' . $slider_info[0]['numero_monitor'] . '&token=' . $token;

        $monitor_links .= '<a href="' . $link . '" target="_blank"><div style="float:left;margin: 6px;"><i class="fa fa-tv fa-4x"></i></div></a>';

    }

    echo '<div class="dashboard_div" style="margin-top:20px;">
		<div class="col_dx_content" style="width: 70%; float: left; padding:0;"><h2 style="margin: 5px 0;">' . $riga['sliderTitle'] . '</h2><span class="msg blue">' . $riga['descrizione'] . '</span><span class="msg gray">' . $riga['pollici'] . '</span><span class="msg"><a href="../mod_basic/action_elimina.php?gtx=' . $tab_id . '&amp;unset=' . $riga['sliderId'] . '" title="Elimina" onclick="return conferma_del();"><i class="fa fa-trash-o"></i> Elimina </a></span><br><br clear="all">'.$monitor_links.'
</div></div>';
}

?>


<?php $start = paginazione(CONNECT, $tabella, $step, $ordine, $tipologia_main, 0);?>
