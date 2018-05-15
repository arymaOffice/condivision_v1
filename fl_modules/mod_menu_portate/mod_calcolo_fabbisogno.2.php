<?php

require_once '../../fl_core/autentication.php';
require_once 'fl_settings.php';
unset($chat);
$nochat;

$new_button = '';
$module_title = "Market List dal " . mydate($_SESSION['data_da']) . " al " . mydate($_SESSION['data_a']);

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

<?php if (isset($_GET['action'])) {
    echo '<input type="hidden" value="' . check($_GET['action']) . '" name="action" />';
}
?>
<?php if (isset($_GET['start'])) {
    echo '<input type="hidden" value="' . check($_GET['start']) . '" name="start" />';
}
?>

   <label for="anagrafica_id">Fornitore</label>
    <select name="anagrafica_id" id="anagrafica_id">
    <option selected value="">Tutti</option>

    <?php foreach ($fornitore as $key => $val) {?>
        <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
    <?php }?>
    </select>
    <br>
    <br>
    <input type="submit" value="Mostra" class="salva" id="filter_set" />

</form>


    </div>
<h1 style="color:red;">VERSIONE IN BETA, FABBISOGNO NON AGGIORNATO CORRETTAMENTE</h1>

<form id="form" name="" method="POST" action="../mod_doc_acquisto/mod_opera.php">

<table class="dati">
<tr>
<th>Cod</th>
<th>Desc</th>
<th>Upa</th>
<th>Qtà</th>
<th>Tot</th>
<th>Um</th>
<th>Note</th>
<th class="noprint">Fornitore</th>
<th class="noprint">Ordina</th>
</tr>

<?php

// Periodo
$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc', 'id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,data_evento', "id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");
$eventi = array();

$oggetto_per_doc_acquisto = 'ORDINE PER EVENTO/I N° ';

echo "<p><a href='javascript:history.back();' class='button'><i class=\"fa fa-angle-left \"></i> Indietro</a></p>
<h2>" . $_SESSION['nome'] . " stai elaborando un approvvigionamento per questi eventi:</h2>
";
foreach ($eventiCoinvolti as $key => $evento) {

    $oggetto_per_doc_acquisto .= $evento['id'] . ' ' . $evento['titolo_ricorrenza'] . ' del ' . mydate($evento['data_evento']) . ',';

    $coperti = $evento['numero_adulti'] + $evento['numero_operatori'];
    echo '<span class="msg green">' . $evento['titolo_ricorrenza'] . ' del ' . mydate($evento['data_evento']) . '</span>';
    $eventi[] = $evento['id'];

}

$oggetto_per_doc_acquisto = trim($oggetto_per_doc_acquisto, ',');

echo '<input type="hidden" name="oggetto" value="' . $oggetto_per_doc_acquisto . '" >';

$eventiIN = implode(',', $eventi);


$filtriQuery = '';

if (isset($_GET['cerca'])) {
    $cerca = check($_GET['cerca']);
    $filtriQuery .= ' AND (selectSpecific.codice_articolo LIKE "%' . $cerca . '%"  OR selectSpecific.descrizione LIKE "%' . $cerca . '%" )';
}
if (isset($_GET['anagrafica_id'])) {
    $cerca = check($_GET['anagrafica_id']);
    $filtriQuery .= ' AND selectSpecific.anagrafica_id = "' . $cerca . '"';
}
if (isset($_GET['categoria_materia'])) {
    $cerca = check($_GET['categoria_materia']);
    $filtriQuery .= ' AND selectSpecific.categoria_materia LIKE "%' . $cerca . '%" ';
}
$TOTALE = 0;

/*

SELECT ricetta_id,sum(quantita) FROM fl_ricettario_fabbisogno WHERE data_impegno BETWEEN $data_da AND $data_a

SELECT * FROM `fl_ricettario_diba` WHERE ricetta_id =742

 */

//filtri query esclusi

$ricette = GQS('fl_ricettario_fabbisogno', ' ricetta_id,sum(quantita) as quantitaTOT', 'ordine_id = 0 AND data_impegno BETWEEN "' . $data_da . '" AND "' . $data_a . '" GROUP BY ricetta_id ');

$ricette_id = array(); 

foreach ($ricette as $key => $value) {
    if(!in_array($value['ricetta_id'],$ricette_id)){
        $ricette_id[] = $value['ricetta_id'];
    }
    $ricetta_info = GQD('fl_ricettario','nome_tecnico','id='. $value['ricetta_id']);
    echo '<tr><td colspan="9">'.$ricetta_info['nome_tecnico'].'</td></tr>';
    $materieprime = GQS('fl_materieprime', '*', 'id IN(SELECT materiaprima_id FROM fl_ricettario_diba WHERE ricetta_id = '.$value['ricetta_id'].')');
    foreach ($materieprime as $key1 => $value1) {
       // print_r($value1);

       $single_tot = ($value1['ultimo_prezzo'] / $value1['valore_di_conversione']) * $ricette['quantitaTOT'];
       $TOTALE += $single_tot;
   
       echo "<tr><td>" . $value1['codice_articolo'] . "</td>
       <td>" . $value1['descrizione'] . "</td>
       <td> € " . numdec($value1['ultimo_prezzo'], 2) . "</td>
       <td><input type='text' style='width:100%;' placeholder='quantità da ordinare' name='qta" . $value1['id'] . "' value='" . $value1['quantitaTOT'] . "'></td>
       <td>€ " . numdec($single_tot, 2) . " </td>
       <td>" . $value1['unita_di_misura'] . "</td>
       <td><input type='text' placeholder='Nota per il fornitore' name='note" . $value1['id'] . "' maxlength=\"100\"></td>
       <td class=\"noprint\"><select name='fornitore" . $value1['id'] . "' style='width: 100%;'><option value='" . $value1['anagrafica_id'] . "'>" . $fornitore[@$value1['anagrafica_id']] . "</option></select> </td>
       <td class=\"noprint\"><input type='checkbox' name='id[]' value='" . $value1['id'] . "' id='" . $value1['id'] . "' checked><label for='" . $value1['id'] . "'  ><i class=\"fa fa-check \"></i></label></td></tr>";

    }
}
$ricette_id = implode(",",$ricette_id);
echo '<input type="hidden" name="ricette_id" value="'.$ricette_id.'">';
//da rivedere backend
?>
<tr>
<th></th>
<th></th>
<th></th>
<th></th>
<th>€ <?php echo numdec($TOTALE, 2); ?> </th>
<th></th>
<th></th>
<th class="noprint"></th>
<th class="noprint"></th></tr>
</table>
<input type="hidden" value="1" name="creaOrdiniFornitore">
<div style="margin:0 auto;text-align: center;">
<?php if (defined('MAGAZZINO')) {
    echo '<input type="submit" value="Crea Ordini Fornitore" class="button noprint">';
}
?>
<a href="javascript:window.print();" class="button noprint">Stampa</a>
</form>
</div>

<?php include "../../fl_inc/footer.php";?>

