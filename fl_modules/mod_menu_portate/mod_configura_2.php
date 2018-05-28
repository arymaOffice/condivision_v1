<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(!isset($_GET['menuId'])) die('Manca Menu ID');
$menuId = check($_GET['menuId']);
$menuInfo = GRD($tabella,$menuId);

include("../../fl_inc/headers.php");

?>
<h1>Riepilogo delle portate</h1>
<?php if($menuInfo['confermato'] == 1) echo "NON PUOI ELIMINARE PORTATE PERCHE' IL MENU E' STATO CONFERMATO."; ?>

<table class="dati">
 
  

<?php 

 $queryPortate = "SELECT r.nome_tecnico,r.variante,r.portata,r.id,r.nome,r.valore_di_conversione,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId,lasts.id2,lasts.descrizione FROM fl_ricettario r RIGHT JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority,s.descrizione FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
$resultPortate = @mysql_query($queryPortate,CONNECT);
$groupId = -1;
echo mysql_error();
$thTemplate = '<tr>
    <th></th>
    <th></th>
    <th>Note</th>
    <th>Costo</th>
    <th>Vendita</th>
    <th>Ordine</th>
  </tr>';
$totCosto = 0;
$totVendita = 0;



while ($row = @mysql_fetch_array($resultPortate)) {

if($groupId != @$row['portata']) { 
if($row['valore'] == '1') $row['valore'] = '';
$piatto_eliminato = ($row['portata'] == NULL)? ' PIATTO ELIMINATO ex id '.$row['id2']: '' ;
echo '<tr><th colspan="3"><h2>'.$portata[$row['portata']].' '.$piatto_eliminato.'</h2></th><th colspan="3" style="text-align: right;">Ambiente: <input type="text" name="valore" class="updateField" style="border: none; width: 200px;" data-gtx="91" data-rel="'.$row['synId'].'" value="'.$row['valore'].'"></th></tr>'.$thTemplate;  
$groupId = $row['portata']; 
}

    
    if(defined('GESTIONE_VARIANTI')) {
    $variante = ($row['variante'] > 1) ? '<br><span class="msg orange">VARIANTE</span>' : '';
    $tastoVariante = ($row['variante'] > 1) ? '<a href="../mod_ricettario/mod_inserisci.php?id='.$row['id'].'&backToMenu='.$menuId.'">Modifica nome del piatto e ricetta </a>' : '<a title="crea variante a {{nome}} " href="mod_opera.php?creaVarianteMenu='.$menuId.'&idPiatto='.$row['id'].'&synId='.$row['synId'].'">Crea una variante</a>';
    } else { $variante =  $tastoVariante = ''; $tastoVariante = 'Nessuna'; }

	$query = "SELECT * FROM `fl_ricettario_diba` WHERE `ricetta_id` = ".$row['id']." ORDER BY id DESC";
	$risultato1 = mysql_query($query, CONNECT);
	$foodCost = 0;
	while ($componenti = mysql_fetch_assoc($risultato1)) 
	{ 
	$materiaprima = GRD('fl_materieprime',$componenti['materiaprima_id']);
  	//$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$materiaprima['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
    $costo =  ($materiaprima['valore_di_conversione'] > 0) ? ($materiaprima['ultimo_prezzo']*$componenti['quantita'])/$materiaprima['valore_di_conversione'] : 0;
  	$foodCost += $costo;
    } 
    $totCosto += $foodCost;
	$totVendita += $foodCost*$row['valore_di_conversione'];

    $foodSell = numdec($foodCost*$row['valore_di_conversione'],2);
    $foodCost = numdec($foodCost,2);
    $nome = ($row['descrizione'] != '') ? converti_txt($row['descrizione']) : converti_txt($row['nome_tecnico']);
    @mysql_query('UPDATE fl_synapsy SET descrizione = \''.$nome.'\' WHERE id = '.$row['synId']);
    $tastoVariante = str_replace('{{nome}}',$nome,$tastoVariante);

echo '<tr id="p'.$row['id'].'">';
echo '<td>'.$row['id'].'</td>';
echo '<td>'.$variante.' '.$nome.'<br> '.$tastoVariante.'</td>';
echo '<td><input type="text" name="note" class="updateField" style="border: none; width: 250px;" data-gtx="91" data-rel="'.$row['synId'].'" value="'.$row['note'].'" placeholder="Note sulla portata"></td>';

echo '<td>&euro; <a title="'.$nome.'" href="../mod_ricettario/mod_diba.php?record_id='.$row['id'].'" class="fancybox_view" data-fancybox-type="iframe">'.$foodCost.'</td>';
echo '<td>&euro; '.$foodSell.'</td>';
echo '<td><input type="text" name="priority" class="updateField" style="border: none; width: 20px;" data-gtx="91" data-rel="'.$row['synId'].'" value="'.$row['priority'].'"></td>';
if($menuInfo['confermato'] != 1) echo '<td><a href="../mod_basic/action_elimina.php?gtx=91&amp;unset='.$row['synId'].'" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>';
echo '</tr>'; 

}


?>

<tr>
    <th><a href="mod_opera.php?menuId=<?php echo $menuId; ?>">Aggiorna Valore Menù</a></th>
    <th></th>
    <th></th>
    <th><h2>&euro; <?php echo numdec($totCosto,2); ?></h2></th>
    <th><h2>&euro; <?php echo numdec($totVendita,2); ?></h2></th>
    <th></th>
    <th></th>
  </tr>


</table>





