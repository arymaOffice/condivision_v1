
<?php
if (!@$thispage) {echo "Accesso Non Autorizzato";exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];

$sedi = (isset($_GET['sede']) && check($_GET['sede']) > 0) ? check($_GET['sede']) : @$_SESSION['sedi_id'];
$proprietariStesseSedi = getAccountSede($sedi);

?>


<h1><?php echo $module_title . ' ' . $new_button; ?></h1>



<div id="filtri" class="filtri">

<form method="get" action="" id="fm_filtri">
 <h2>Filtri</h2>

   <?php

if ($_SESSION['usertype'] == 5 || $_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8 || $_SESSION['profilo_funzione'] == 10 || $_SESSION['profilo_funzione'] == 11 || $_SESSION['profilo_funzione'] == 23 || $_SESSION['profilo_funzione'] == 14 || $_SESSION['profilo_funzione'] == 15) {

    if (isset($_GET['all'])) {
        echo '<input type="hidden" name="all" value="1" />';
    }
    ?>

   <label> BDC </label>

   <select name="proprietario" >
   <option value="-1">Tutti</option>

      <?php
echo '<optgroup label="Operatori BDC">';
    foreach ($operatoribdc as $valores => $label) { // Recursione Indici di Categoria
        $selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
        echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";
    }
    ?>


		      <?php
echo '<optgroup label="Digital">';
    foreach ($operatoridgt as $valores => $label) { // Recursione Indici di Categoria
        $selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
        echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";
    }
    ?>
    </select>


        <!--  <label> Sede</label>
   <select name="sede" id="sede">
      <option value="-1">Tutti</option>
      <?php

    foreach ($sedi_id as $valores => $label) { // Recursione Indici di Categoria
        $selected = (@check($_GET['sede']) == $valores) ? " selected=\"selected\"" : "";
        if ($valores != 0) {echo "<option value=\"$valores\" $selected>" . ucfirst($label) . "</option>\r\n";}
    }
    ?>
    </select> -->

    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  <?php }?>
</form>


    </div>


<?php

if ($_SESSION['usertype'] == 5 || $_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8 || $_SESSION['profilo_funzione'] == 10 || $_SESSION['profilo_funzione'] == 11 || $_SESSION['profilo_funzione'] == 23 || $_SESSION['profilo_funzione'] == 14 || $_SESSION['profilo_funzione'] == 15) {
    if (!isset($_GET['all'])) {
        echo '<a class="button" style="background-color:#4788ef;" href="./?' . $_SERVER['QUERY_STRING'] . '&all"><i class="fa fa-users" aria-hidden="true"></i> Mostra tutte le attività</a>';
    }

    if (isset($_GET['all'])) {
        echo ' <a class="button" style="background-color:#008E37;" href="./?' . str_replace('&all', '', $_SERVER['QUERY_STRING']) . '"><i class="fa fa-user" aria-hidden="true"></i> Mostra le tue attività</a>';
    }

    if ($_SESSION['usertype'] == 5 || $_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8) {
        echo ' <a class="button" href="./?action=24"><i class="fa fa-bullseye" aria-hidden="true"></i>  Panoramica Operatori</a>';
    }

    if ($_SESSION['usertype'] == 5 || $_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8) {
        echo ' <a class="button" href="../mod_gruppi/?action=24" ><i class="fa fa-user" aria-hidden="true"></i> Panoramica Gruppi di Lavoro</a>';
    }

}
echo '<br><br><br>';

foreach ($tipo_campagna as $key => $valore) {

    if (!isset($_GET['tipoCampagna']) || (isset($_GET['tipoCampagna']) && check($_GET['tipoCampagna']) == $key)) {

        $ids = array();
        echo '<h1>' . $valore . '</h1>';
        $tipoCampagna = $key;
        $campagne = GQS('fl_campagne', 'id,tipo_campagna,descrizione', ' id > 1 AND tipo_campagna = ' . $key);

        foreach ($campagne as $key => $value) {
            $ids[] = $campagne[$key]['id'];
        }

        if (count($ids) > 0) {

            $extra = ' AND campagna_id IN(' . implode(',', $ids) . ')';
            unset($ids);

            if (isset($_GET['ordine'])) {if (!is_numeric($_GET['ordine'])) {exit;} else { $ordine = $ordine_mod[$_GET['ordine']];}}

            $start = paginazione(CONNECT, $tabella, $step, $ordine, $tipologia_main, 0);

            $query = "SELECT $select FROM `$tabella` $tipologia_main $extra ORDER BY $ordine LIMIT $start,$step;";

            $risultato = mysql_query($query, CONNECT);

            ?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
 <th style="width: 1%;"></th>
 <th style="width: 30%;"><a href="./?ordine=1<?php echo $action; ?>">Attivita</a></th>
 <th  style="width: 20%;">Gruppo di Lavoro</th>
 <th>TOTALE ATTIVITA' LEAD</th>
 <th>TOTALE LEAD GIORNO</th>
 <th>LEAD DA ASSEGNARE</th>
 <th>LEAD IN GESTIONE BDC</th>
 <?php if ($tipoCampagna != 2) {?><th>LEAD IN GESTIONE SALES</th><?php }?>
 <?php if ($tipoCampagna != 2) {?><th>TOTALE APPUNTAMENTI</th><?php }?>
 <?php if ($tipoCampagna != 2) {?><th>TOTALE OK</th><?php }?>
 <?php if ($tipoCampagna != 2) {?><th>TOTALE KO</th><?php }?>
 <!--<th>Conv.</th>
 <th>% Conv.</th>-->
 <th></th>
</tr>



<?php

            $i = 1;

            if (mysql_affected_rows() == 0) {echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";}
            $tot_res = 0;
            $deleted = 0;
            $incomplete = 0;

            while ($riga = mysql_fetch_array($risultato)) {

                $colore = "class=\"tab_blue\"";
                $elimina = ($_SESSION['usertype'] > 0) ? '' : "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=" . $riga['id'] . "\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";

                $mieileads = '';
                $allFilter = (isset($_GET['all']) || $riga['supervisor_id'] == $_SESSION['number']) ? '&all' : '';

                if (($riga['supervisor_id'] != $_SESSION['number'] || $proprietario_id > 1) && !isset($_GET['all'])) {
                    $mieileads = ' AND (fl_potentials.proprietario = ' . $proprietario_id . ' OR fl_potentials.venditore = ' . $proprietario_id . ' )';
                }

                // Filtro per account in base alle sedi dell operatore o del filtro selezionato
                if (isset($_GET['all']) && $_SESSION['profilo_funzione'] == 11 & $_SESSION['sedi_id'] != 0 && $proprietariStesseSedi != '') {
                    $mieileads .= ' AND (fl_potentials.proprietario IN(' . $proprietariStesseSedi . ') OR fl_potentials.venditore IN(' . $proprietariStesseSedi . ') )';
                }

                $totale_leads = mk_count('fl_potentials', 'status_potential != 9 AND source_potential = ' . $riga['id'] . $mieileads);
                $totale_leads_giorno = mk_count('fl_potentials', ' data_associazione_attivita = CURDATE() AND status_potential != 9 AND source_potential = ' . $riga['id'] . $mieileads);
                $lead_da_assegnare = mk_count('fl_potentials', 'status_potential != 9 AND proprietario < 1 AND source_potential = ' . $riga['id']);

                if ($totale_leads > 0 || $lead_da_assegnare > 0 || isset($_GET['all'])) {

                    $status_potential_in = 'status_potential = 1';
                    if (in_array($_SESSION['profilo_funzione'], $menu_vendite) && $_SESSION['usertype'] > 0) {
                        $status_potential_in = 'status_potential IN (2,4,7,8)';
                    }

                    $status_potential_d = '1';
                    if (in_array($_SESSION['profilo_funzione'], $menu_vendite) && $_SESSION['usertype'] > 0 || $_SESSION['usertype'] == 5) {
                        $status_potential_d = '8';
                    }

                    //if($riga['supervisor_id'] != $_SESSION['number'] && $proprietario_id > 1 && !isset($_GET['all'])) $lead_da_assegnare = "0";

                    /* In gestione */
                    $totale_leads_dagestire = mk_count('fl_potentials', 'status_potential != 9 AND proprietario > 0 AND source_potential = ' . $riga['id'] . $mieileads);
                    if ($tipoCampagna == 2) {
                        $totale_leads_dagestire = mk_count('fl_potentials', 'status_potential = 1 AND source_potential = ' . $riga['id'] . $mieileads);
                    }

                    $totale_conversioni = mk_count('fl_potentials', 'is_customer > 0 AND source_potential = ' . $riga['id'] . $mieileads);

                    /* In sales */
                    $lead_da_gestVendita = mk_count('fl_potentials', 'status_potential < 2 AND status_potential != 9 AND venditore > 0  AND proprietario > 0 AND source_potential = ' . $riga['id'] . $mieileads, '', 0);

                    /* In direct sales
                    $lead_da_gestVenditaDirect = mk_count('fl_potentials','status_potential != 9 AND venditore > 0  AND proprietario < 1 AND source_potential = '.$riga['id'].$mieileads,'','*',0);*/

                    $totale_appuntamenti = mk_count('fl_potentials', 'status_potential = 2 AND source_potential = ' . $riga['id'] . $mieileads, '', 0);// mysql_query('SELECT fl_potentials.id FROM fl_potentials JOIN fl_appuntamenti WHERE fl_potentials.id != 1 AND fl_potentials.source_potential = ' . $riga['id'] . ' AND fl_potentials.id = fl_appuntamenti.potential_rel  AND fl_appuntamenti.issue = 0 AND DATE(fl_appuntamenti.start_meeting)  >= CURDATE() ' . $mieileads, CONNECT);
                    $totale_appuntamenti = mysql_affected_rows();
                    $totale_appuntamenti_ok = mysql_query('SELECT fl_potentials.id FROM fl_potentials JOIN fl_appuntamenti WHERE fl_potentials.id != 1 AND fl_potentials.source_potential = ' . $riga['id'] . ' AND fl_potentials.id = fl_appuntamenti.potential_rel AND (fl_appuntamenti.issue != 0 AND fl_appuntamenti.issue != 3) ' . $mieileads, CONNECT);
                    $totale_appuntamenti_ok = mysql_affected_rows();

                    $totale_appuntamenti_ko = mysql_query('SELECT fl_potentials.id FROM fl_potentials JOIN fl_appuntamenti WHERE fl_potentials.id != 1 AND fl_potentials.source_potential = ' . $riga['id'] . ' AND fl_potentials.id = fl_appuntamenti.potential_rel AND (fl_appuntamenti.issue  = 3) ' . $mieileads, CONNECT);
                    $totale_appuntamenti_ko = mysql_affected_rows();

                    $tasso_conversione = @numdec(@$totale_conversioni / @$totale_leads * 100, 2);

                    $actionView = (!isset($_GET['all']) && $proprietario_id > 1) ? 'proprietario=' . $proprietario_id . '&' : 'all&';

                    $colore = ($riga['data_fine'] < date('Y-m-d')) ? "class=\"tab_red\"" : "class=\"tab_green\"";
                    $color_tot = ($totale_leads_dagestire > 0) ? 'color: red;' : 'color: green;';
                    $color_tot2 = ($lead_da_assegnare > 0) ? 'color: red;' : 'color: green;';
                    $color_tot3 = ($lead_da_gestVendita > 0) ? 'color: red;' : 'color: green;';
                    if ($riga['data_inizio'] > date('Y-m-d')) {
                        $colore = "class=\"tab_orange\"";
                    }

                    $automatica = ($riga['assegnazione_automatica'] == 1) ? '<span class="msg orange">AUTO</span>' : '<span class="msg green">'.$assegnazione_automatica[$riga['assegnazione_automatica']].'</span>';
                    $supervisorName = ($riga['supervisor_id'] > 0) ? '<br>Supervisor: ' . $supervisor_id[$riga['supervisor_id']] : '';

                    echo "<tr>";
                    $oggetto = ucfirst($riga['oggetto']);
                    echo "<td $colore><span class=\"Gletter\"></span></td>";
                    echo "<td><span class=\"color\"><a href=\"../mod_leads/?" . $actionView . "status_potential=-1&source_potential[]=" . $riga['id'] . "\" title=\"" . ucfirst(strip_tags(converti_txt($riga['descrizione']))) . "\">$oggetto</a></span> [" . $campagna_id[$riga['campagna_id']] . "]
			<br> Dal " . @mydate($riga['data_inizio']) . " al " . @mydate($riga['data_fine']) . "
			<br>Tempo di lavorazione: " . $riga['scadenza_default_ore'] . " h $automatica</td>";

                    echo "<td><a href='../mod_gruppi/mod_inserisci.php?id=" . $riga['gruppo_id'] . "'> <span class=\"msg blue\">" . @$gruppo_id[$riga['gruppo_id']] . "</span></a> $supervisorName</td>"; //".$processo_id[$riga['processo_id']]."
                    echo "<td class=\"center fontlarge\"><a href=\"../mod_leads/?" . $actionView . "status_potential=-1&source_potential[]=" . $riga['id'] . "$allFilter\">$totale_leads</a></td>";
                    echo "<td class=\"center fontlarge\"><a href=\"../mod_leads/?" . $actionView . "status_potential=-1&source_potential[]=" . $riga['id'] . "&todayCreated$allFilter\">$totale_leads_giorno</a></td>";

                    echo '<td class="center fontlarge"><a href="../mod_leads/?daAssegnare&source_potential[]=' . $riga['id'] . $allFilter . $allFilter . '" style="' . $color_tot2 . '">' . $lead_da_assegnare . '</a></th>';
                    if ($tipoCampagna != 2) {
                        echo "<td class=\"center fontlarge\"><a href=\"../mod_leads/?" . $actionView . "status_potential=-1&source_potential[]=" . $riga['id'] . "&gestioneBdc$allFilter\" style=\"$color_tot\">$totale_leads_dagestire</a></td>";
                    }

                    if ($tipoCampagna == 2) {
                        echo "<td class=\"center fontlarge\"><a href=\"../mod_leads/?" . $actionView . "status_potential=1&source_potential[]=" . $riga['id'] . "$allFilter\" style=\"$color_tot\">$totale_leads_dagestire</a></td>";
                    }

                    /*gestione sales */if ($tipoCampagna != 2) {
                        echo '<td class="center fontlarge"><a href="../mod_leads/?status_potential=-1&source_potential[]=' . $riga['id'] . '&gestioneVendita' . $allFilter . '" style="' . $color_tot3 . '">' . $lead_da_gestVendita . '</a></td>';
                    }

                    /*gestione direct sales *///if($tipoCampagna != 2)echo '<td class="center fontlarge"><a href="../mod_leads/?status_potential=-1&source_potential[]='.$riga['id'].'&gestioneDirectSales&'.$allFilter.'" style="'.$color_tot3.'">'.$lead_da_gestVenditaDirect.'</a></th>';

                    if ($tipoCampagna != 2) {
                        echo "<td class=\"center fontlarge\"><a href=\"../mod_leads/?status_potential=2&source_potential[]=" . $riga['id'] . "\">$totale_appuntamenti</a></td>";
                    }

                    if ($tipoCampagna != 2) {
                        echo "<td class=\"center fontlarge\"><a href=\"../mod_appuntamenti/?ok&source_potential=" . $riga['id'] . "\">$totale_appuntamenti_ok </a></td>";
                    }

                    if ($tipoCampagna != 2) {
                        echo "<td class=\"center fontlarge\"><a href=\"../mod_appuntamenti/?ko&source_potential=" . $riga['id'] . "\">$totale_appuntamenti_ko </a></td>";
                    }

                    //echo "<td class=\"center fontlarge\"><a href=\"../mod_leads/?".$actionView."status_potential=4&source_potential[]=".$riga['id']."\">$totale_conversioni</a></td>";
                    //echo "<td class=\"center fontlarge\">$tasso_conversione %</td>";

                    if ($_SESSION['usertype'] == 5 || $_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8 || $_SESSION['profilo_funzione'] == 10 || $_SESSION['profilo_funzione'] == 11 || $_SESSION['profilo_funzione'] == 23 || $_SESSION['profilo_funzione'] == 14 || $_SESSION['profilo_funzione'] == 15 || $_SESSION['profilo_funzione'] == 13 || $_SESSION['usertype'] == 0) {
                        echo "<td  class=\"strumenti\"><a href=\"mod_inserisci.php?id=" . $riga['id'] . "\" title=\"Gestione  \"> <i class=\"fa fa-pencil\"></i> </a>$elimina </td>";
                    }

                    echo "</tr>";
                }}

            echo "</table>";
        }}}

//if($_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8) echo ' <a class="c-red" href="../../fl_core/services/leadsManager.php" target="blank"><i class="fa fa-user" aria-hidden="true"></i> Lancia Assegnazione automatica (TEST)</a>';

echo '<div style="">Sedi in cui operi: ';

$sediList = explode(',', $sedi);
foreach ($sediList as $value) {
    echo '<span class="msg gray">' . @$sedi_id[$value] . '</span>';
}
echo '</div>';

?>
