<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');
unset($chat);
$nochat;

$new_button = '';
$module_title = "Market List dal ".$data_da_t." al ".$data_a_t;

include("../../fl_inc/headers.php");
 ?>
 
<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>
<div class="content_scheda">   

<form id="form" name="" method="POST" action="../mod_doc_acquisto/mod_opera.php"> 

<table class="dati">
<tr>
<th>Cod</th>
<th>Desc</th>
<th>Upa</th>
<th>Qtà</th>
<th>Um</th>
<th>Note</th>
<th>Fornitore</th>
<th>Ordina</th>
</tr>

<?php

// Periodo
$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc','id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,data_evento',"id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");
$eventi = array();

$oggetto_per_doc_acquisto = 'ORDINE PER EVENTO/I N° ';

echo "<h2>".$_SESSION['nome']." stai elaborando un approvvigonamento per questi eventi:</h2>
";
foreach ($eventiCoinvolti as $key => $evento) {

    $oggetto_per_doc_acquisto .= $evento['id'].' '.$evento['titolo_ricorrenza'].' del '.mydate($evento['data_evento']).',';

$coperti = $evento['numero_adulti']+$evento['numero_operatori'];
echo '<span class="msg green">'.$evento['titolo_ricorrenza'].' del '.mydate($evento['data_evento']).'</span>';
$eventi[] = $evento['id'];

}

$oggetto_per_doc_acquisto = trim($oggetto_per_doc_acquisto,',');

echo '<input type="hidden" name="oggetto" value="'.$oggetto_per_doc_acquisto.'" >';

$eventiIN = implode(',', $eventi);

// query esempio SELECT selectSpecific.materiaprima_id,selectSpecific.codice_articolo,selectSpecific.ultimo_prezzo,selectSpecific.anagrafica_id,selectSpecific.descrizione, SUM( selectSpecific.quantita * fb.quantita ) AS totale, selectSpecific.unita_di_misura FROM `fl_ricettario_fabbisogno` fb JOIN ( SELECT materiaprima_id, m.descrizione, m.unita_di_misura, rf.quantita, ricetta_id,m.codice_articolo,m.ultimo_prezzo,m.anagrafica_id FROM fl_ricettario_diba rf JOIN fl_materieprime m ON m.id = materiaprima_id WHERE ricetta_id IN ( ( SELECT ricetta_id FROM `fl_ricettario_fabbisogno` WHERE evento_id IN ( 191,207,456,1100,1123,1465,1703,1722 ) ) ) ) AS selectSpecific ON fb.ricetta_id = selectSpecific.ricetta_id WHERE evento_id IN ( 191,207,456,1100,1123,1465,1703,1722 ) GROUP BY materiaprima_id


$megasuperQueryFighissima = "SELECT fb.id as fabbId,selectSpecific.materiaprima_id,selectSpecific.codice_articolo,selectSpecific.ultimo_prezzo,selectSpecific.anagrafica_id,selectSpecific.descrizione, SUM( selectSpecific.quantita * fb.quantita ) AS totale, selectSpecific.unita_di_misura FROM `fl_ricettario_fabbisogno` fb JOIN ( SELECT materiaprima_id, m.descrizione, m.unita_di_misura, rf.quantita, ricetta_id,m.codice_articolo,m.ultimo_prezzo,m.anagrafica_id FROM fl_ricettario_diba rf JOIN fl_materieprime m ON m.id = materiaprima_id WHERE ricetta_id IN ( ( SELECT ricetta_id FROM `fl_ricettario_fabbisogno` WHERE evento_id IN ( ".$eventiIN." ) ) ) ) AS selectSpecific ON fb.ricetta_id = selectSpecific.ricetta_id WHERE evento_id IN ( ".$eventiIN." ) AND fb.ordine_id = 0 GROUP BY materiaprima_id ORDER BY selectSpecific.anagrafica_id DESC ";


$risultato = mysql_query($megasuperQueryFighissima, CONNECT);

 while ($robaDaOrdinare = mysql_fetch_array($risultato)) 
{
echo '<input type="hidden" name="fabb'.$robaDaOrdinare['materiaprima_id'].'" value="'.$robaDaOrdinare['fabbId'].'" >';
    
    echo "<tr><td>".$robaDaOrdinare['codice_articolo']."</td>
    <td>".$robaDaOrdinare['descrizione']."</td>
    <td>".$robaDaOrdinare['ultimo_prezzo']."</td>
    <td><input type='text' style='width:80%;' placeholder='quantità da ordinare' name='qta".$robaDaOrdinare['materiaprima_id']."' value='".$robaDaOrdinare['totale']."'> €</td>
    <td>".$robaDaOrdinare['unita_di_misura']."</td>    
    <td><input type='text' placeholder='Nota per il fornitore' name='note".$robaDaOrdinare['materiaprima_id']."' maxlength=\"100\"></td>
    <td><select name='fornitore".$robaDaOrdinare['materiaprima_id']."' style='width: 100%;'><option value='".$robaDaOrdinare['anagrafica_id']."'>".$fornitore[@$robaDaOrdinare['anagrafica_id']]."</option></select> </td>
    <td><input type='checkbox' name='id[]' value='".$robaDaOrdinare['materiaprima_id']."' id='".$robaDaOrdinare['materiaprima_id']."' checked><label for='".$robaDaOrdinare['materiaprima_id']."'  ><i class=\"fa fa-check \"></i></label></td></tr>";

}

	
?>
</table>
<input type="hidden" value="1" name="creaOrdiniFornitore">
<div style="margin:0 auto;text-align: center;">
<input type="submit" value="Crea Ordini Fornitore" class="button noprint">
<a href="javascript:window.print();" class="button noprint">Stampa</a>
</div>

</form>
<br><br><br><br>
</div>

<?php include("../../fl_inc/footer.php"); ?>

