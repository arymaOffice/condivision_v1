<?php

// Controlli di Sicurezza
if (!@$thispage) {echo "Accesso Non Autorizzato";exit;}

$new_button = '';

if (isset($_GET['ordine'])) {if (!is_numeric($_GET['ordine'])) {exit;} else { $ordine = $ordine_mod[$_GET['ordine']];}}

$start = paginazione(CONNECT, $tabella, $step, $ordine, '', 0);

$query = "SELECT $select, an.id as anid,ac.attivo as accountAttivo FROM `$tabella` an LEFT JOIN fl_account ac ON ac.anagrafica = an.id WHERE ac.id > 1 ORDER BY $ordine LIMIT $start,$step;";

$risultato = mysql_query($query, CONNECT);
echo mysql_error();

?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <?php if (ATTIVA_ACCOUNT_ANAGRAFICA == 1) {?><th class="noprint"><a href="./?ordine=2&<?php echo $_SERVER['QUERY_STRING']; ?>">Account</a></th><?php }?>
  <th  class="desktop"><a href="./?ordine=3&<?php echo $_SERVER['QUERY_STRING']; ?>">Ragione Sociale</a></th>

  <th class="desktop"><a href="#" class="sede_legale">Sede Legale</a><?php if (!defined('ANAGRAFICA_SEMPLICE')) {?>/<a href="#" class="sede_operativa">Sede Operativa</a><?php }?> </th>
  <th>Contatti</th>
  <?php if (ALERT_DOCUMENTO_SCADUTO == 1) {?><th></th><?php }?>
  <th class="noprint"><a href="./?ordine=0&<?php echo $_SERVER['QUERY_STRING']; ?>">Recenti</a> | <a href="./?ordine=1&<?php echo $_SERVER['QUERY_STRING']; ?>">Meno recenti</a></th>

</tr>
<?php

$i = 1;

if (mysql_affected_rows() == 0) {echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";}
$tot_res = 0;
$deleted = 0;
$incomplete = 0;

while ($riga = mysql_fetch_array($risultato)) {
    $show = 1;

    if (ATTIVA_ACCOUNT_ANAGRAFICA == 1) {

        if (!isset($_GET['cerca']) && $stato_account_id != -1 && (isset($riga['attivo']) && @$riga['attivo'] != $stato_account_id)) {
            $show = 0;
        }

        if (!isset($_GET['cerca']) && $tipo_account_id != -1 && (isset($riga['tipo']) && @$riga['tipo'] != $tipo_account_id)) {
            $show = 0;
        }

    }



        $user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id=' . $riga['anid'] . '">' . $riga['user'] . '</a><br>' . $riga['motivo_sospensione'];
        $user_ball = ($riga['accountAttivo'] == 1) ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>";

        $tipo_profilo_label = $tipo[$riga['tipo']];
        if (isset($riga['account']) && @$riga['account'] != $riga['user']) {
            mysql_query("UPDATE $tabella SET account = '" . $riga['user'] . "' WHERE id = " . $riga['anid'] . " LIMIT 1");
        }

        $notifica_icon = '<a data-fancybox-type="iframe" title="Invia Notifica Account" class="fancybox_view_small" href="../mod_notifiche/mod_invia.php?destinatario[]=' . @$riga['anid'] . '"><i class="fa fa-bell" aria-hidden="true"></i></a>';
    

    if (!defined('TIPO_DA_ACCOUNT') || TIPO_DA_ACCOUNT == 0) {
        $tipo_profilo_label = $tipo_profilo[$riga['tipo_profilo']];
    }

    if ($show == 1) {

        if (isset($riga['attivo']) && $riga['attivo'] == 1) {
            $colore = "b_green";
        } else if (isset($riga['attivo']) && $riga['attivo'] == 0) {
            $colore = "b_red";
        } else {
            $colore = "b_orange";
        }

        
        $tot_res++;

        $nominativo = ($riga['ragione_sociale'] != '') ? ucfirst(checkValue($riga['ragione_sociale'])) : ucfirst(checkValue(@$riga['nome'])) . ' ' . ucfirst(checkValue(@$riga['cognome']));
        $sede_punto = (!isset($riga['comune_punto'])) ? '' : $riga['comune_punto'] . " (" . @$riga['provincia_punto'] . ") " . $riga['cap_punto'] . "<br>" . $riga['indirizzo_punto'];
        echo '<tr>';

        if (ATTIVA_ACCOUNT_ANAGRAFICA == 1) {
            echo "<td class=\"desktop $colore\">$user_ball " . $user_check . "</td>";
        }

        echo "<td><a href=\"mod_panoramica_contatto.php?id=" . $riga['anid'] . "\"><span class=\"color\"><strong>" . $riga['anid'] . "</strong> $nominativo</span><br>P. iva " . $riga['partita_iva'] . '<br>';
        if (defined('MULTI_BRAND')) {
            echo "<span class=\"msg blue\">" . $marchio[$riga['marchio']] . "</span> ";
        }

        echo " <span class=\"msg orange\">" . $tipo_profilo_label . "  </span></a></td>";
        echo "
					<td class=\"desktop info_sede_legale\">" . $riga['comune_sede'] . " (" . @$riga['provincia_sede'] . ") " . $riga['cap_sede'] . "<br>" . $riga['sede_legale'] . "</td>
					<td class=\"desktop info_sede_operativa\" >" . $sede_punto . "</td>";
        echo "<td class=\"desktop\"><i class=\"fa fa-envelope-o\"></i> <a href=\"mailto:" . checkEmail($riga['email']) . "\">" . checkEmail(@$riga['email']) . "</a>
					<br><i class=\"fa fa-phone\" style=\"padding: 5px 10px;\"></i>" . phone_format($riga['telefono']) . " - " . phone_format($riga['cellulare']) . "</td>";



        echo '<td  class="strumenti"> <a href="mod_inserisci.php?id=' . $riga['anid'] . '"><i class="fa fa-pencil"></i></a>';
        if (@PROFILO_ANAGRAFICA == 1) {
            echo '<a href="mod_inserisci.php?external&action=1&tBiD=' . base64_encode('39') . '&id=' . $riga['anid'] . '"><i class="fa fa-user"></i>' . get_scan($riga['anid']) . '</a>';
        }

        if (@PANORAMICA_ANAGRAFICA == 1) {
            echo '<a href="mod_panoramica_contatto.php?id=' . $riga['anid'] . '"><i class="fa fa-television" aria-hidden="true"></i></a>';
        } else {
            echo "<a href=\"mod_inserisci.php?id=" . $riga['anid'] . "&nominativo=" . $riga['ragione_sociale'] . "\" title=\"Gestione Cliente " . ucfirst($riga['ragione_sociale']) . " Agg. " . $riga['data_aggiornamento'] . "\"> <i class=\"fa fa-search\"></i> </a>
					<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;sezione=" . @$riga['sezione'] . "&amp;id=" . $riga['anid'] . "&nominativo=" . $riga['ragione_sociale'] . "\" title=\"Scheda di stampa " . ucfirst($riga['ragione_sociale']) . "\"> <i class=\"fa fa-print\"></i> </a>";
        }

        echo "$notifica_icon</td>";
        echo "</tr>";

    }

}

echo "</table>";

?>
<?php echo '<h2>Totale risultati: ' . $tot_res . '</h2>'; ?>