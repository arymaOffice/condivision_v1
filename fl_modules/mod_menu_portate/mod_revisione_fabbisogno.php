<?php

require_once '../../fl_core/autentication.php';
require_once 'fl_settings.php';
unset($chat);
$nochat;

$new_button = '';
$module_title = "";

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

<!-- <div class="filtri" id="filtri">

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


    </div> -->


<form id="form" name="" method="POST" action="../mod_doc_acquisto/mod_opera.php">

<table class="dati">
<tr>
<th>Cod</th>
<th>Desc</th>
<th>Upa</th>
<th>Um</th>
<th>Qtà</th>
<th>Tot</th>

</tr>

<?php

//per test
// $data_da = '2018-05-08';
// $data_a = '2018-05-08';

// Periodo
$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc', 'id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,data_evento', "id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");

echo "<p><a href='javascript:history.back();' class='button'><i class=\"fa fa-angle-left \"></i> Indietro</a></p><h2>" . $_SESSION['nome'] . " verifica le quantità di preparazione delle ricette:</h2>
";

$eventi = array();

foreach ($eventiCoinvolti as $key => $evento) {
    $eventi[] = $evento['id'];

    // echo '<tr><td colspan="4" class="msg green">'.$evento['id'] . ' ' . $evento['titolo_ricorrenza'] . ' del ' . mydate($evento['data_evento']).'</td></tr>';



    /*
$ricette = GQS('fl_ricettario_fabbisogno','ricetta_id,quantita','evento_id = '.$evento['id']);
echo '<pre>';
print_r($ricette);
echo '</pre>';
echo '<br><br>';
 */

}

$eventiImplode = implode(',',$eventi);



$query = "SELECT SUM(fb.quantita) as quantita,fb.ricetta_id,selectSpecific.valore_di_conversione,selectSpecific.materiaprima_id,selectSpecific.codice_articolo,selectSpecific.ultimo_prezzo,selectSpecific.anagrafica_id,selectSpecific.descrizione, SUM( selectSpecific.quantita * fb.quantita ) AS totale, selectSpecific.unita_di_misura,GROUP_CONCAT(fb.evento_id SEPARATOR ',') as eventi FROM `fl_ricettario_fabbisogno` fb JOIN ( SELECT m.valore_di_conversione,materiaprima_id, m.descrizione, m.unita_di_misura, rf.quantita, ricetta_id,m.codice_articolo,m.ultimo_prezzo,m.anagrafica_id FROM fl_ricettario_diba rf JOIN fl_materieprime m ON m.id = materiaprima_id WHERE ricetta_id IN ( ( SELECT ricetta_id FROM `fl_ricettario_fabbisogno` WHERE evento_id IN ( " . $eventiImplode . ") ) ) ) AS selectSpecific ON fb.ricetta_id = selectSpecific.ricetta_id WHERE evento_id IN ( " . $eventiImplode . " ) GROUP BY fb.ricetta_id, materiaprima_id ORDER BY fb.ricetta_id ASC";

$result = mysql_query($query, CONNECT);
$ricetta_id = NULL;

while ($ingredienti = mysql_fetch_assoc($result)) {
    if($ricetta_id != $ingredienti['ricetta_id']){
        $ricetta_info = GQS('fl_ricettario','nome_tecnico,porzioni','id = '.$ingredienti['ricetta_id']);
        $eventi_name = GQS('fl_eventi_hrc',' GROUP_CONCAT(DISTINCT CONCAT(\'<span class="msg green">\', titolo_ricorrenza,\'</span>\') SEPARATOR " ") as eventiname ','id in( '.$ingredienti['eventi'].')');
  

        echo '<tr style="background: #ecefcc;"><td colspan="2">'.$ingredienti['ricetta_id'].' | <a href="../mod_ricettario/mod_diba.php?record_id='.$ingredienti['ricetta_id'].'" data-fancybox-type="iframe" class="fancybox_view">'.$ricetta_info[0]['nome_tecnico'].'</a></td><td><h3>'.$ingredienti['quantita'].' Porzioni</h3></td><td colspan="3"> '.$eventi_name[0]['eventiname'].'</td></tr>';
        $ricetta_id = $ingredienti['ricetta_id'];
    }//fine if 
    $ingredienti['totale'] = ($ricetta_info[0]['porzioni'] > 1) ?  $ingredienti['totale']/$ricetta_info[0]['porzioni'] : $ingredienti['totale'];
    $single_tot = ($ingredienti['ultimo_prezzo'] / $ingredienti['valore_di_conversione']) * $ingredienti['totale'];
    echo '<tr><td>'.$ingredienti['codice_articolo'].'</td><td>'.$ingredienti['descrizione'].'</td><td> € '.numdec($ingredienti['ultimo_prezzo'],2).'</td><td>'.$ingredienti['unita_di_misura'].'</td><td>'.$ingredienti['totale'].'</td><td> € '.numdec($single_tot,2).'</td></tr>';


}

// query esempio SELECT selectSpecific.materiaprima_id,selectSpecific.codice_articolo,selectSpecific.ultimo_prezzo,selectSpecific.anagrafica_id,selectSpecific.descrizione, SUM( selectSpecific.quantita * fb.quantita ) AS totale, selectSpecific.unita_di_misura FROM `fl_ricettario_fabbisogno` fb JOIN ( SELECT materiaprima_id, m.descrizione, m.unita_di_misura, rf.quantita, ricetta_id,m.codice_articolo,m.ultimo_prezzo,m.anagrafica_id FROM fl_ricettario_diba rf JOIN fl_materieprime m ON m.id = materiaprima_id WHERE ricetta_id IN ( ( SELECT ricetta_id FROM `fl_ricettario_fabbisogno` WHERE evento_id IN ( ".$evento['id'].") ) ) ) AS selectSpecific ON fb.ricetta_id = selectSpecific.ricetta_id WHERE evento_id IN ( ".$evento['id']." ) GROUP BY materiaprima_id

// query esempio SELECT selectSpecific.materiaprima_id,selectSpecific.codice_articolo,selectSpecific.ultimo_prezzo,selectSpecific.anagrafica_id,selectSpecific.descrizione, SUM( selectSpecific.quantita * fb.quantita ) AS totale, selectSpecific.unita_di_misura FROM `fl_ricettario_fabbisogno` fb JOIN ( SELECT materiaprima_id, m.descrizione, m.unita_di_misura, rf.quantita, ricetta_id,m.codice_articolo,m.ultimo_prezzo,m.anagrafica_id FROM fl_ricettario_diba rf JOIN fl_materieprime m ON m.id = materiaprima_id WHERE ricetta_id IN ( ( SELECT ricetta_id FROM `fl_ricettario_fabbisogno` WHERE evento_id IN ( 191,207,456,1100,1123,1465,1703,1722 ) ) ) ) AS selectSpecific ON fb.ricetta_id = selectSpecific.ricetta_id WHERE evento_id IN ( 191,207,456,1100,1123,1465,1703,1722 ) GROUP BY materiaprima_id

?>

</table>

<?php echo '<a href="mod_calcolo_fabbisogno.php" class="button noprint">Produci Market List</a>'; ?>
<a href="javascript:window.print();" class="button noprint">Stampa</a>

</div>

<?php include "../../fl_inc/footer.php";?>

