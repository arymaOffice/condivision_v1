<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(!isset($_GET['menuId'])) die('Manca Menu ID');
$menuId = check($_GET['menuId']);
    
include("../../fl_inc/headers.php");
?>
<h1>Riepilogo delle portate</h1>

<table class="dati">
 
  

<?php 

$queryPortate = "SELECT r.variante,r.portata,r.id,r.nome,r.prezzo_vendita,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId FROM fl_ricettario r JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
$resultPortate = @mysql_query($queryPortate,CONNECT);
$groupId = -1;
echo mysql_error();
$thTemplate = '<tr>
    <th></th>
    <th></th>
    <th>Note</th>
    <th>Variante</th>
    <th>Ordine</th>
    <th>Elimina</th>
  </tr>';

while ($row = @mysql_fetch_array($resultPortate)) {

if($groupId != $row['portata']) { 
if($row['valore'] == '1') $row['valore'] = '';
echo '<tr><th colspan="3"><h2>'.$portata[$row['portata']].'</h2></th><th colspan="3" style="text-align: right;">Ambiente: <input type="text" name="valore" class="updateField" style="border: none; width: 200px;" data-gtx="91" data-rel="'.$row['synId'].'" value="'.$row['valore'].'"></th></tr>'.$thTemplate;  
$groupId = $row['portata']; 
}

$variante = ($row['variante'] > 1) ? '<br><span class="msg orange">VARIANTE</span>' : '';
$tastoVariante = ($row['variante'] > 1) ? '<a href="../mod_ricettario/mod_inserisci.php?id='.$row['id'].'&backToMenu='.$menuId.'"><i class="fa fa-cog" aria-hidden="true"></i></a>' : '<a href="mod_opera.php?creaVarianteMenu='.$menuId.'&idPiatto='.$row['id'].'&synId='.$row['synId'].'"><i class="fa fa-files-o"></i></a>';


echo '<tr id="p'.$row['id'].'">';
echo '<td>'.$row['id'].'</td>';
echo '<td>'.converti_txt($row['nome']).' '.$variante.' </td>';
echo '<td><input type="text" name="note" class="updateField" style="border: none; width: 250px;" data-gtx="91" data-rel="'.$row['synId'].'" value="'.$row['note'].'" placeholder="Note sulla portata"></td>';
echo '<td>'.$tastoVariante.'</td>';
echo '<td><input type="text" name="priority" class="updateField" style="border: none; width: 20px;" data-gtx="91" data-rel="'.$row['synId'].'" value="'.$row['priority'].'"></td>';
echo '<td><a href="../mod_basic/action_elimina.php?gtx=91&amp;unset='.$row['synId'].'" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>';
echo '</tr>'; 

}

?>



</table>





