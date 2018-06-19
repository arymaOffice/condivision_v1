<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');

$unita_di_misura_formato = array('LT','KG','PZ','CT','BT','BA');
unset($chat);
$nochat;

if(!isset($_GET['menuId'])) die('Manca Menu ID');
$menuId = check($_GET['menuId']);
$evento_id = check($_GET['evento_id']);
$evento = GRD($tables[6],$evento_id);
$extra_coperti = ($_GET['extra_coperti']) ? check($_GET['extra_coperti']) : 10;
$coperti = $evento['numero_adulti']+$evento['numero_operatori'];
include("../../fl_inc/headers.php");
 ?>
 
<body style="background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<?php if(isset($evento['id'])) { ?>
<h1><?php echo $evento['titolo_ricorrenza']; ?></h1>
<p><?php echo mydatetime($evento['data_evento']); ?></p>
<?php } else {  ?>
<h1>Menù Demo</h1>
<?php $coperti = 0; } ?>

<p><?php echo $coperti; ?></p>


<form>Aggiungi extra preparazione: <input type="number" name="extra_coperti" value="<?php echo $extra_coperti; ?>"><input type="submit">  </form>

<?php 
	
	
  $queryPortate = "SELECT r.variante,r.portata,r.id,r.nome,r.valore_di_conversione,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId FROM fl_ricettario r JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
  $resultPortate = @mysql_query($queryPortate,CONNECT);

	?>
    
   <table class="dati">
   <tr>
   <th>Codice</th>
   <th>Descrizione</th>
   <th>Approvigionamento</th>
   <th>Giacenza</th>
   <th>Fabbisogno</th>
   <th>Formato </th>
   </tr>
   </table>

 <?php
	

while ($row = @mysql_fetch_array($resultPortate)) {


$variante = ($row['variante'] > 1) ? '<br><span class="msg orange">VARIANTE</span>' : '';
$tastoVariante = ($row['variante'] > 1) ? '<a href="../mod_ricettario/mod_inserisci.php?id='.$row['id'].'&backToMenu='.$menuId.'"><i class="fa fa-cog" aria-hidden="true"></i></a>' : '<a href="mod_opera.php?creaVarianteMenu='.$menuId.'&idPiatto='.$row['id'].'&synId='.$row['synId'].'"><i class="fa fa-files-o"></i></a>';



echo ''.$row['id'].'';
echo ''.converti_txt($row['nome']).' '.$variante.' ';


}

?>


<a href="javascript:location.reload();" class="button">Aggiorna Listino</a>
<a href="javascript:window.print();" class="button">Stampa</a>

</body></html>
