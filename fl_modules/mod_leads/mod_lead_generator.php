<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Controlli di Sicurezza
require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
include 'filtri.php';

if (isset($_GET['ordine'])) {if (!is_numeric($_GET['ordine'])) {exit;} else { $ordine = $ordine_mod[$_GET['ordine']];}}

$start = paginazione(CONNECT, $tabella, $step, $ordine, $tipologia_main, 0);
echo '<br><br>';
echo $db;
echo $query = "SELECT $select FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step;";



$risultato = mysql_query($query, CONNECT);

//$module_menu = '';

include "../../fl_inc/headers.php";?>



<?php if (!isset($_GET['external'])) {
    include '../../fl_inc/testata.php';
}
?>
<?php if (!isset($_GET['external'])) {
    include '../../fl_inc/menu.php';
}
?>
<?php if (!isset($_GET['external'])) {
    include '../../fl_inc/module_menu.php';
}
?>


<p style="clear: both; text-align: left;">

	<a href="#" style="padding: 8px 20px;" onclick="window.location.href = 'mod_export.php?'+ window.location.search.substring(1) ;" class="button" >Esporta i risultati correnti in excel</a> </p>

<div id="results" class="green"></div>



 <table class="dati" summary="Dati" style=" width: 100%;">
    <tr>
      <?php if ($status_potential_id != 4) {?>
      <th></th>
      <?php }?>


      <th><a href="../mod_basic/action_set.php?ordine_mode=1">Nominativo</a></th>
      <th>Contatti</th>
      <th>Lead Generator</th>
      <th></th>
    </tr>
    <?php

$i = 1;
function www($url)
{
    $num = '';
    if ($url != '') {
        $num = "http://" . str_replace("http://", "", str_replace(" ", "", $url));
    }

    return $num;
}

print_r(mysql_fetch_array($risultato));
echo mysql_affected_rows();
//if (mysql_affected_rows() == 0) {echo "<tr><td colspan=\"9\">No records</td></tr>";}
$tot_res = $count = 0;
while ($riga = mysql_fetch_array($risultato)) {

    $colore = "class=\"tab_blue\"";
    if ($riga['priorita_contatto'] == 0) {$colore = "class=\"turquoise\"";}
    if ($riga['priorita_contatto'] == 1) {$colore = "class=\"orange\"";}
    if ($riga['priorita_contatto'] == 2) {$colore = "class=\"red\"";}

    $input = '';
    //if($_SESSION['usertype'] < 4) {
    $checked = ($riga['status_potential'] == 0) ? '' : ''; //checked auto
    //$count += ($riga['status_potential']==0) ? 1 : 0;
    $input = '<input onClick="countFields(1);" type="checkbox" name="leads[]" value="' . $riga['id'] . '" id="' . $riga['id'] . '"  ' . $checked . ' /><label for="' . $riga['id'] . '">' . $checkRadioLabel . '</label>';
    //}

    /*Gestione Assegnazion e scadenza */
    if ($riga['data_scadenza'] != '0000-00-00 00:00:00' && $riga['data_scadenza'] != '') {
        $date = strtotime(@$riga['data_scadenza']);
        $giorniCount = giorni(date('Y-m-d', $date));
        $giorni = '<span class="msg green">- ' . $giorniCount . ' giorni</span>';
        if ($giorniCount == 0) {
            $giorni = '<span class="msg red">SCADE OGGI</span> alle ' . date('H:i', $date);
        }

        if ($giorniCount == 1) {
            $giorni = '<span class="msg orange">Domani</span> alle ' . date('H:i', $date);
        }

        if ($giorniCount < 0) {
            $giorni = '<span class="msg red">SCADUTO</span>';
        }

    } else { $giorni = '';}

    if ($riga['data_scadenza_venditore'] != '0000-00-00 00:00:00' && $riga['data_scadenza_venditore'] != '') {
        $date = strtotime(@$riga['data_scadenza_venditore']);
        $giorniCount = giorni(date('Y-m-d', $date));
        $giorni2 = '<span class="msg green">- ' . $giorniCount . ' giorni</span>';
        if ($giorniCount == 0) {
            $giorni2 = '<span class="msg red">SCADE OGGI</span> alle ' . date('H:i', $date);
        }

        if ($giorniCount == 1) {
            $giorni2 = '<span class="msg orange">Domani</span> alle ' . date('H:i', $date);
        }

        if ($giorniCount < 0) {
            $giorni2 = '<span class="msg red">SCADUTO</span>';
        }

    } else { $giorni2 = '';}

    $gestore = $riga['proprietario'];
    //if(defined('assegnazione_automatica') && $gestore < 2 && function_exists('assegnazione_automatica')) $gestore = assegnazione_automatica($riga['id'],$riga['source_potential']);

    $bdcAction = get_nextAction(16, $riga['id'], 0); // rimettere valore ID del gestore se vuoi prendere solo azioni del gestore BDC
    $bdcAction = ($gestore > 0 && $bdcAction['id'] != null) ? '<span title="' . @$proprietario[$bdcAction['operatore']] . '"><strong>' . mydatetime($bdcAction['data_aggiornamento']) . ' </strong>' . $bdcAction['note'] . '</span><br>' : 'Inserito il ' . mydate($riga['data_creazione']);

    $sellAction = get_nextAction(16, $riga['id'], $riga['venditore']);
    $sellAction = ($riga['venditore'] > 0 && $gestore != $riga['venditore'] && $sellAction['id'] != null) ? '<span title="' . @$proprietario[$sellAction['operatore']] . '"><strong>' . mydatetime($sellAction['data_aggiornamento']) . '</strong> ' . $sellAction['note'] . '</span><br>' : 'Nessuna Azione';

    /*
    $query = 'SELECT * FROM `fl_meeting_agenda` WHERE potential_rel = '.$riga['id'].'';
    mysql_query($query);*/

    /* SMS*/
    $phone = phone_format($riga['telefono'], '39');
    $website = www($riga['website']);
    $valutazione = GQD('fl_surveys', '*', ' `workflow_id` = 16 AND `record_id` = ' . $riga['id'] . ' ORDER BY data_creazione DESC LIMIT 1');
    $valutazioneBadge = ($valutazione['id'] > 0) ? '<span class="msg green" title="' . $valutazione['note'] . '">Gradimento (' . numdec($valutazione['value'], 2) . ')</span>' : '';

    $synapsy = '';

    $query = 'SELECT * FROM `fl_synapsy` WHERE (`type1` = ' . $tab_id . ' OR `type2` = ' . $tab_id . ') AND (`id1` = ' . $riga['id'] . ' OR `id2` = ' . $riga['id'] . ')';

    $parentele = mysql_query($query);
    if (mysql_affected_rows() > 0) {
        $synapsy = '<span class="msg"><i class="fa fa-link"></i></a>';
        while ($parente = mysql_fetch_array($parentele)) {
            $record_rel = ($parente['id1'] == $riga['id']) ? $parente['id2'] : $parente['id1'];
            $nominativocorrelato = GRD('fl_potentials', $record_rel);
            $synapsy .= ' <a href="../mod_leads/mod_inserisci.php?id=' . $record_rel . '">' . $nominativocorrelato['nome'] . ' ' . $nominativocorrelato['cognome'] . '</a> <a href="mod_opera.php?disaccoppia=' . $parente['id'] . '" class="c-red">[x]</a>';}
        $synapsy .= '</span>';
    }
    if (isset($_SESSION['synapsy'])) {
        $synapLead = ($_SESSION['synapsy'] != $riga['id']) ? '<a href="mod_opera.php?connect=' . $riga['id'] . '" style="color: #E84B4E;"><i class="fa fa-link" aria-hidden="true"></i></a>' : '';
    } else {
        $synapLead = '<a href="mod_opera.php?synapsy=' . $riga['id'] . '"><i class="fa fa-link" aria-hidden="true"></i></a>';
    }

    //$query = 'SELECT * FROM `fl_sms` WHERE `to` LIKE \''.$phone.'%\' ORDER BY `data_ricezione` DESC LIMIT 1';
    //$sms = mysql_fetch_array(mysql_query($query));
    //$send = '<a href="../mod_sms/mod_inserisci.php?action=1&id=1&to='.$phone.'&from='.crm_number.'" data-fancybox-type="iframe" class="fancybox_view"><i class="fa fa-envelope"></i></a> ';

    $smsbody = (isset($sms['body']) && strlen($phone) > 4) ? '<br><span class="c-red"><strong>Ultimo SMS inviato:</strong> ' . $sms['body'] . '</span>' : '';
    $new_contract = ($riga['is_customer'] > 1) ? '../mod_anagrafica/mod_inserisci.php?id=' . $riga['is_customer'] . '&meeting_id=0' : '../mod_anagrafica/mod_inserisci.php?id=1&meeting_id=0&j=' . base64_decode($riga['id']) . '&nominativo=' . $riga['nome'] . ' ' . $riga['cognome'];
    $color_contract = ($riga['is_customer'] > 1) ? 'c-green' : '';

    $veicolo_lista = '';

    $veicoloUsato = 0; //get_veicolo(16,$riga['id']);
    $veicoloNuovo = 0; //get_veicolo(48,$riga['id']);

    if ($veicoloUsato != 0) {
        $veicolo_lista .= '<span class="msg gray">PERMUTA</span> <a href="../mod_veicoli/mod_inserisci.php?id=' . $veicoloUsato['id'] . '">' . $veicoloUsato['marca'] . ' ' . $veicoloUsato['modello'] . ' ';
    }

    if (isset($veicoloNuovo) && $veicoloNuovo != 0) {
        $veicolo_lista .= '<span class="msg blue">NUOVO</span> <a href="../mod_veicoli/mod_inserisci.php?id=' . $veicoloNuovo['id'] . '">' . $veicoloNuovo['marca'] . ' ' . $veicoloNuovo['modello'] . '';
    }

    $qualified = ($status_potential_id != 4 && $riga['nome'] != '' && strlen($riga['telefono']) > 7) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';
    $qualified .= ($status_potential_id != 4 && $riga['nome'] != '' && strlen($riga['email']) > 5 && filter_var($riga['email'], FILTER_VALIDATE_EMAIL)) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';
    $qualified .= ($status_potential_id != 4 && $veicoloUsato != 0) ? '<i class="fa fa-star" style="padding: 0; color: rgb(246, 205, 64); font-size: 80%;" aria-hidden="true"></i>' : '';

    $mainLink = ""; // LINK APERTURA SCHEDA LEAD
    $gestore = ($gestore > 0) ? $proprietario[@$gestore] : '';
    if ($gestore == '') {
        $attivitaInfo = GRD('fl_campagne_attivita', $riga['source_potential']);
        if ($attivitaInfo['assegnazione_automatica'] == 2) {
            $gestore = '';
            $mainLink = '';
        }}

    //Controllo esistenza venditore
    $venditoreAssegnato = (isset($proprietario[@$riga['venditore']])) ? $proprietario[@$riga['venditore']] : '<span class="c-red">Inserire venditore ' . $riga['venditore'] . ' da Impostazioni>Account.</span>';

    if ($venditoreAssegnato > 1 && isset($proprietario[@$riga['venditore']]) && $riga['sede'] < 2) { //Controllo se sede non assegnata e assegno sede di venditore
        $venditoreDetails = GRD('fl_account', $riga['venditore']);
        $sedeUpdate = 'UPDATE fl_potentials SET  sede = ' . $venditoreDetails['sede_principale'] . ' WHERE id = ' . $riga['id'];
        if ($venditoreDetails['sede_principale'] > 1 && mysql_query($sedeUpdate, CONNECT)) {
            $venditoreAssegnato = $venditoreAssegnato . '<br> <span class="c-green">SEDE AGGIORNATA!</span>';
        }

        if ($venditoreDetails['sede_principale'] < 2) {
            $venditoreAssegnato = $venditoreAssegnato . ' <br><span class="c-red"> IL VENDITORE NON HA UNA SEDE ASSEGNATA!</span>';
        }

    }

    if ($status_potential_id != 4) {
        echo "<tr><td $colore><span class=\"Gletter\"></span></td>";
    }

    echo "<td class=\"checkItemTd\">$input</td>";

    //if($status_potential_id != 4)  echo "<td style=\"text-align:center;\"></td>";
    // <span class=\"msg $priocolor\">".$priorita_contatto[$riga['priorita_contatto']]."</span>
    echo "<td >".$mainLink." ".$riga['nome'] . " " . $riga['cognome'] . "<br><span class=\"msg blue\">" . @$source_potential[$riga['source_potential']] . "</span><span class=\"msg orange\">" . @$sede[$riga['sede']] . "</span>  $valutazioneBadge $synapsy
			<br>" . $riga['industry'] . "</td>";

    echo "<td>
			$qualified <strong>" . @$status_potential[$riga['status_potential']] . "</strong> <br>
			<i class=\"fa fa-phone\" style=\"padding: 3px;\"> </i>  $phone <br><i class=\"fa fa-envelope-o\" style=\"padding: 3px;\"></i> " . $riga['email'] . "</a></td>";

    echo "<td><strong>" . @$proprietario[@$riga['lead_generator']] . "</strong></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "</tr>";

}
?>
  </table>



	  <?php $start = paginazione(CONNECT, $tabella, $step, $ordine, $tipologia_main, 1);?>

  </div>


<div class="results" style="position: fixed; bottom: 20px; left: 0; width: 30%; z-index: 9999;"></div>

<a style="margin:10px 40%;" href="#" onclick="window.location.href = 'mod_export.php?'+ window.location.search.substring(1) ;" class="button" >Esporta i risultati correnti in excel</a>

<?php
if ($_SERVER['HTTP_HOST'] == 'dev.bluemotive.it') {
    echo ' <a class="c-red" href="../mod_leads/mod_opera.php?reset" onclick="return conferma(\'Sei sicuro di voler resettare tutto il database?\');"><i class="fa fa-user" aria-hidden="true"></i> Resetta tutti i leads (TEST)</a>';
}

if (!isset($_GET['external'])) {
    include "../../fl_inc/footer.php";
}
?>