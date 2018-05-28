<?php

require_once '../../fl_core/autentication.php';
require_once 'fl_settings.php';
unset($chat);
$nochat;

$new_button = '';
$module_title = "2 - Verifica Fabbisogno Ricette";

$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc', 'id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,data_evento', "id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");
$eventi = array();

foreach ($eventiCoinvolti as $key => $evento) {
    $eventi[] = $evento['id'];
}

$eventiImplode = implode(',', $eventi);
if (isset($_GET['eventi'])) {
    $eventiImplode = implode(',', $_GET['eventi']);
}

include "../../fl_inc/headers.php";
?>

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

<div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>

      <?php foreach ($eventiCoinvolti as $key => $evento) {
    $checked = (in_array($evento['id'], $_GET['eventi'])) ? 'checked' : '';
    echo '<input  type="checkbox" name="eventi[]" value="' . $evento['id'] . '" id="' . $key . '" ' . $checked . ' /><label for="' . $key . '">' . mydate($evento['data_evento']) . ' ' . $evento['titolo_ricorrenza'] . '</label>';
}
?>


    <input type="submit" value="Mostra" class="salva" id="filter_set" />

</form>
</div>

<p><a href='javascript:history.back();' class='button'><i class="fa fa-angle-left"></i> Indietro</a></p>

<div style="text-align: left;">
<form method="get" action="" id="fm_filtri">

      Periodo di lavorazione <label> dal</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php echo $_SESSION['data_da_t']; ?>"  class="calendar" size="8" />

      <label> al </label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php echo $_SESSION['data_a_t']; ?>" class="calendar" size="8" />

      <input type="submit" value="Procediamo!" class="button" />

</form>
</div>



<p class="noprint"><strong>FASE 2 - VERIFICA:</strong> In questa fase, procedi a rivedere e verificare il calcolo di produzione che il sistema esegue basandosi sulle porzioni che hai pianificato, sommandole per ricetta, e considerando la quantita di porzioni che hai specificato nel ricettario.
<br>Usalo per monitorare la corretta compilazione delle tue ricette e verificale cliccando sul nome della ricetta per aprire la distinta base. In fondo al foglio prosegui alla fase successiva.</p>




<?php
$dati_query = " rc.nome_tecnico,rc.porzioni,rc.porzioni_piatto,SUM(fb.quantita) as quantita,fb.ricetta_id,selectSpecific.valore_di_conversione,selectSpecific.materiaprima_id,selectSpecific.codice_articolo,selectSpecific.ultimo_prezzo,selectSpecific.anagrafica_id,selectSpecific.descrizione, SUM( selectSpecific.quantita * fb.quantita ) AS totale, selectSpecific.unita_di_misura,GROUP_CONCAT(fb.evento_id SEPARATOR ',') as eventi ";

$dati_query_ricettario_diba = " m.valore_di_conversione,materiaprima_id, m.descrizione, m.unita_di_misura, rf.quantita, ricetta_id,m.codice_articolo,m.ultimo_prezzo,m.anagrafica_id ";

$query_ricette = " SELECT ricetta_id FROM `fl_ricettario_fabbisogno` WHERE evento_id IN ( " . $eventiImplode . ") ";

$query_materieprime = "SELECT $dati_query_ricettario_diba  FROM fl_ricettario_diba rf
JOIN fl_materieprime m ON m.id = materiaprima_id
WHERE ricetta_id IN ( ( $query_ricette ) )";

$query = "SELECT $dati_query FROM `fl_ricettario_fabbisogno` fb

JOIN ( $query_materieprime ) AS selectSpecific ON fb.ricetta_id = selectSpecific.ricetta_id

JOIN fl_ricettario rc ON rc.id = selectSpecific.ricetta_id

WHERE evento_id IN ( " . $eventiImplode . " )
GROUP BY fb.ricetta_id, materiaprima_id
ORDER BY rc.portata ASC,rc.priorita ASC,rc.categoria_ricetta ASC 
";

$coun = 0;
$result = mysql_query($query, CONNECT);
$ricetta_id = NULL;

while ($ingredienti = mysql_fetch_assoc($result)) {

    if($ricetta_id != $ingredienti['ricetta_id']){

    $eventi_name = GQS('fl_eventi_hrc', ' GROUP_CONCAT(DISTINCT CONCAT(\'<span class="msg green">\', DATE_FORMAT(data_evento,\'%d/%m/%Y\') , \' \' ,titolo_ricorrenza,\'</span>\') SEPARATOR " ") as eventiname ', 'id in( ' . $ingredienti['eventi'] . ')');

    $piatti = ($ingredienti['porzioni_piatto'] > 0) ? round($ingredienti['quantita'] / $ingredienti['porzioni_piatto'], 0) . ' Porzioni' : '';

    if ($count > 0) {
        echo '</table>';
    }

    $count++;

    echo '<table class="dati" style="page-break-inside: avoid;">

        <tr style="background: #ecefcc;">
        <td colspan="2"><h2>' . $ingredienti['ricetta_id'] . ' |
        <a href="../mod_ricettario/mod_diba.php?record_id=' . $ingredienti['ricetta_id'] . '" data-fancybox-type="iframe" class="fancybox_view">' . $ingredienti['nome_tecnico'] . '</a>
        </h3>' . $eventi_name[0]['eventiname'] . '</td>
        <td colspan="2"><h3>' . $ingredienti['quantita'] . ' Coperti</h3>' . $piatti . '</td></tr>

        <tr>
        <th style="width: 20%;">Cod</th>
        <th style="width: 60%;">Desc</th>
        <th>Um</th>
        <th>Qtà</th>
        </tr>
        ';
        $ricetta_id = $ingredienti['ricetta_id'];
    } //fine if

    $ingredienti['totale'] = ($ingredienti['porzioni'] > 1) ? $ingredienti['totale'] / $ingredienti['porzioni'] : $ingredienti['totale'];
    //$single_tot = ($ingredienti['ultimo_prezzo'] / $ingredienti['valore_di_conversione']) * $ingredienti['totale'];<td> € '.numdec($ingredienti['ultimo_prezzo'],2).'</td><td> € '.numdec($single_tot,2).'</td>
    echo '<tr><td>' . $ingredienti['codice_articolo'] . '</td><td>' . $ingredienti['descrizione'] . '</td><td>' . $ingredienti['unita_di_misura'] . '</td><td>' . $ingredienti['totale'] . '</td></tr>';

}


?>
</table>


<?php echo '<p><a href="mod_calcolo_fabbisogno.php" class="button salva green noprint">Produci Market List</a></p>'; ?>



<?php include "../../fl_inc/footer.php";?>

