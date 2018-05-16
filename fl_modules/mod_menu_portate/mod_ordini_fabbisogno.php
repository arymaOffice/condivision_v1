<?php

require_once '../../fl_core/autentication.php';
require_once 'fl_settings.php';
unset($chat);
$nochat;

$new_button = '';
$module_title = "4 - Ordini Fabbisogno";

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

<p><a href='javascript:history.back();' class='button'><i class="fa fa-angle-left"></i> Indietro</a></p>

<p class="noprint"><strong>FASE 4 - ORDINI FABBISOGNO:</strong> In questa fase puoi creare gli ordini ai fornitori basandoti sul fabbisogno periodico non ancora soddisfatto! Seleziona un fornitore o seleziona un periodo diverso per ordinare.</p>

<div style="text-align: left;">
<form method="get" action="" id="fm_filtri">
 
      Periodo di lavorazione <label> dal</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo  $_SESSION['data_da_t'];  ?>"  class="calendar" size="8" />
   
    

      <label> al </label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $_SESSION['data_a_t'];  ?>" class="calendar" size="8" />

    <label for="anagrafica_id">Fornitore</label>
    <select name="anagrafica_id" id="anagrafica_id">
    <option selected value="">Tutti</option>
    
    <?php foreach ($fornitore as $key => $val) {?>
        <option value="<?php echo $key; ?>"><?php echo $val; ?></option>
    <?php }?>
    </select>

    <input type="submit" value="Mostra" class="button"  />

</form>
</div>


<form id="form" name="" method="POST" action="../mod_doc_acquisto/mod_opera.php">

Eventi inclusi in questa elaborazione (Sono visualizzati anche se non hanno un fabbisogno da soddifare):

<?php

// Periodo
$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc', 'id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,data_evento', "id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");
$eventi = array();

$oggetto_per_doc_acquisto = 'ORDINE PER EVENTO/I N° ';


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

if (isset($_GET['anagrafica_id'])) {
    $cerca = check($_GET['anagrafica_id']);
    $filtriQuery .= ' AND anagrafica_id = "'.$cerca.'"';
}

$TOTALE = 0; ?>


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


$materieprime_fabbisogno  = GQS('fl_materieprime_fabbisogno mf JOIN fl_materieprime m ON m.id = mf.materia_prima_id ','*,SUM(quantita)','data_ordine="0000-00-00 00:00:00" AND data_impegno BETWEEN "'.$data_da.'" AND "'.$data_a.'"  '.$filtriQuery.' GROUP BY m.id');
$i = 0;
foreach($materieprime_fabbisogno  as $key => $value){
    //print_r($value); echo '<br><br>';

    $single_tot = ($value['ultimo_prezzo']/$value['valore_di_conversione']) * $value['quantita'];
    $TOTALE += $single_tot ;
    echo '<input type="hidden" name="fabb' . $value['materiaprima_id'] . '" value="' . $value['fabbId'] . '" >';
    echo '<input type="hidden" name="fabbisogni' . $value['materiaprima_id'] . '" value="' . $value['fabbisogni'] . '" >';

    echo "<tr><td>" . $value['codice_articolo'] . "</td>
    <td>" . $value['descrizione'] . "</td>
    <td> € " . numdec($value['ultimo_prezzo'], 2) . "</td>
    <td><input type='text' style='width:100%;' placeholder='quantità da ordinare' name='qta" . $value['materiaprima_id'] . "' value='" . $value['quantita'] . "'></td>
    <td>€ " .  numdec($single_tot,2) . " </td>
    <td>" . $value['unita_di_misura'] . "</td>
    <td><input type='text' placeholder='Nota per il fornitore' name='note" . $value['materiaprima_id'] . "' maxlength=\"100\"></td>
    <td class=\"noprint\"><select name='fornitore" . $value['materiaprima_id'] . "' style='width: 100%;'><option value='" . $value['anagrafica_id'] . "'>" . $fornitore[@$value['anagrafica_id']] . "</option></select> </td>
    <td class=\"noprint\"><input type='checkbox' name='id[]' value='" . $value['materiaprima_id'] . "' id='" . $value['materiaprima_id'] . "' checked><label for='" . $value['materiaprima_id'] . "'  ><i class=\"fa fa-check \"></i></label></td></tr>"; 
    $i ++;
    if($i > 100) break;
}

?>
<tr>
<th></th>
<th></th>
<th></th>
<th></th>
<th>€ <?php echo numdec($TOTALE,2); ?> </th>
<th></th>
<th></th>
<th>Data Consegna</th>
<th><input type="date" value="" name="data_consegna_prevista" required></th></tr>
</table>

<input type="hidden" value="1" name="creaOrdiniFornitore">
<a href="javascript:window.print();" class="button noprint">Stampa</a>
<?php if(defined('MAGAZZINO')) echo '<input type="submit" value="Crea Ordini Fornitore" class="button noprint">'; ?>


</form>

<?php include "../../fl_inc/footer.php";?>

