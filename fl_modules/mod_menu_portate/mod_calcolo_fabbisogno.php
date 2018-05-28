<?php

require_once '../../fl_core/autentication.php';
require_once 'fl_settings.php';
unset($chat);
$nochat;

$new_button = '';
$module_title = "3 - Market List dal " . mydate($_SESSION['data_da']) . " al " . mydate($_SESSION['data_a']);

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

<p><a href='javascript:history.back();' class='button'><i class="fa fa-angle-left"></i> Indietro</a></p>

<div style="text-align: left;">
<form method="get" action="" id="fm_filtri">
 
      Periodo di lavorazione <label> dal</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo  $_SESSION['data_da_t'];  ?>"  class="calendar" size="8" />
   
    
      <label> al </label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $_SESSION['data_a_t'];  ?>" class="calendar" size="8" />

     <input type="submit" value="Procediamo!" class="button" />

</form>
</div>

<p class="noprint"><strong>FASE 3 - MARKET LIST:</strong> In questa fase il sistema ti propone la market list per il periodo selezionato. Ogni ingrediente viene conteggiato per ricetta/giorno per generare poi un'effettivo fabbisogno di acquisto per un dato giorno.
Quando pensi che il tuo fabbisogno sia definitivo, puoi "Generare un a richiesta di Approvvigionamento" il fabbisogno ingredienti e in seguito elaborare ordini ai fornitori, in base alle tue esigenze temporali di ricezione della merce!</p>


<form id="form" name="" method="GET" action="">

<table class="dati">
<tr>
<th>Cod</th>
<th>Desc</th>
<th>Upa</th>
<th>Um</th>
<th>Qtà</th>
<th>Tot</th>
<th>Fornitore</th>
<th>Approvv.to</th>
</tr>

<?php



$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc', 'id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,data_evento', "id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");
$eventi = array();
echo "<h2>" . $_SESSION['nome'] . " stai elaborando un approvvigionamento per questi eventi:</h2>";


foreach ($eventiCoinvolti as $key => $evento) {

    $coperti = $evento['numero_adulti'] + $evento['numero_operatori'];
    echo '<span class="msg green">' . $evento['titolo_ricorrenza'] . ' del ' . mydate($evento['data_evento']) . '</span>';
    $eventi[] = $evento['id'];

}


$eventiIN = implode(',', $eventi);
$TOTALE = 0;


$ricette = GQS('fl_ricettario_fabbisogno', 'id,data_impegno, DATE_FORMAT(data_impegno,\'%d/%m/%Y\') as data_evento ,ricetta_id,sum(quantita) as quantitaTOT', 'ordine_id = 0 AND data_impegno BETWEEN "' . $data_da . '" AND "' . $data_a . '" GROUP BY  data_impegno,ricetta_id ORDER BY data_impegno,ricetta_id ASC');
$righeFabbisogno = 0;
$queryInserimentoFabbisognoMateria = "INSERT INTO `fl_materieprime_fabbisogno` (`id`,`fabbisogno_id`, `materia_prima_id`, `quantita`, `data_impegno`,`data_ordine`, `data_consegna_prevista`, `data_creazione`, `data_aggiornamento`, `operatore`) VALUES ";


foreach ($ricette as $key => $value) {
   

    $ricetta_info = GQD('fl_ricettario','id,nome_tecnico,porzioni,porzioni_piatto','id='. $value['ricetta_id']);
    $materieprime = GQS('fl_materieprime as m LEFT JOIN fl_ricettario_diba ON m.id = fl_ricettario_diba.materiaprima_id', '*,m.id as matID , m.unita_di_misura AS um', 'fl_ricettario_diba.ricetta_id = '.$value['ricetta_id'].' ORDER BY m.id, fl_ricettario_diba.ricetta_id');
    $piatti = ($ricetta_info['porzioni_piatto'] > 0) ? round($value['quantitaTOT']/$ricetta_info['porzioni_piatto'],0).' piatti' : '';
    echo '<tr style="background: #ecefcc;"><td colspan="6" title="ID FABBISOGNO: '.$value['id'].'"> '.$ricetta_info['id'].' | <a href="../mod_ricettario/mod_diba.php?record_id='.$ricetta_info['id'].'" data-fancybox-type="iframe" class="fancybox_view">'.$ricetta_info['nome_tecnico'].'</a></td>
    <td colspan="1" title="Diba per '.numdec($ricetta_info['porzioni'],0).' porzioni."><h3>'.$value['quantitaTOT'].' Porzioni</h3> '.$piatti.'</td>
    <td colspan="1">'.$value['data_evento'].'</td></tr>';

   

    foreach ($materieprime as $key1 => $value1) {

       $porzioni = ($ricetta_info['porzioni'] < 1)? 1 : $ricetta_info['porzioni']; 
       $daOrdinare = round(($value1['quantita']/$ricetta_info['porzioni'])*$value['quantitaTOT'],2);
       $single_tot = ($value1['ultimo_prezzo'] / $value1['valore_di_conversione']) * $daOrdinare;
       $TOTALE += $single_tot;

       $isIn = GQD('fl_materieprime_fabbisogno','id,fabbisogno_id,SUM(`quantita`) AS quantita',' materia_prima_id = '.$value1['matID'].' AND fabbisogno_id = '.$value['id'].' GROUP BY fabbisogno_id');


       
       
       $appvto = ($isIn['id'] > 0) ? '<span class="msg green">RICHIESTO '.round($isIn['quantita'],2).' '.$value1['um'].'</span>' : '<span class="msg gray">NON GENERATO</span>';

       $integrazioni = ($isIn['id'] > 0) ? $daOrdinare - round($isIn['quantita'],2) : 0;
       if(!is_numeric($daOrdinare) || !is_numeric($isIn['quantita'])  ){
        $integrazioni = 0;
        }
       if(round($integrazioni,2) > 0) $appvto = '<span class="msg orange">DA INTEGRARE ('.$integrazioni.')</span>';
       $quantitaApprovvigionamento = ($integrazioni > 0) ? $integrazioni : $daOrdinare;
      
       if($isIn['id'] < 1 || $integrazioni > 0) {
        if($righeFabbisogno > 0) $queryInserimentoFabbisognoMateria .= ',';
        $queryInserimentoFabbisognoMateria .= "(NULL, '".$value['id']."', '".$value1['matID']."', '$quantitaApprovvigionamento',  '".$value['data_impegno']."', '', '',  NOW(), NOW(), '".$_SESSION['number']."')";
        $righeFabbisogno++;
        if(isset($_GET['generazione']) && $daOrdinare > 0) $appvto = '<span class="msg blue">RICHIESTI '.$daOrdinare.'</span>';
        if(isset($_GET['generazione']) && $integrazioni > 0) $appvto = '<span class="msg blue">RICHIESTI '.$daOrdinare.'</span>';

       }

       echo "<tr ><td title=\"CID: ".$value1['matID']."\">" . $value1['codice_articolo'] . "</td>
       <td>" . $value1['descrizione'] . "</td>
       <td> € " . numdec($value1['ultimo_prezzo'], 2) . "</td>
       <td>" . $value1['um'] . "</td>
       <td>" . $daOrdinare . "</td>
       <td>€ " . numdec($single_tot, 2) . " </td>
       <td class=\"noprint\"><selectstyle='width: 100%;'><option value='" . $value1['anagrafica_id'] . "'>" . $fornitore[@$value1['anagrafica_id']] . "</option></select></td>
       <td>" . $appvto . "</td>
       </td></tr>";
        
 

    }
}

?>
<tr>
<th></th>
<th></th>
<th></th>
<th></th>
<th>Totale ordini: </th>
<th>€ <?php echo numdec($TOTALE, 2); ?> </th>
<th></tr>
</table>

<br clear="clear">
<?php
    echo '<input type="submit" value="Genera Approvvigionamento" class="button salva green noprint" >';
    echo '<input type="hidden" value="1" name="generazione">';
    if(isset($_GET['generazione']) &&  $righeFabbisogno > 0) {
    if(mysql_query($queryInserimentoFabbisognoMateria))  { echo ' <br> '.$righeFabbisogno.' righe di fabbisogno inserite!'; } else { echo 'ERRORE CONTATTA ASSISTENZA! '.mysql_error(); }
    }

?>
</form>

<?php include "../../fl_inc/footer.php";?>

