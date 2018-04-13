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
$fonitoreFiltro = (isset($_GET['fornitore'])) ? ' AND materia.anagrafica_id = '.check($_GET['fornitore']) : '';
$extra_coperti = (isset($_SESSION['extra_coperti'])) ? check($_SESSION['extra_coperti']) : 10;
$alert = ($extra_coperti < 1) ? '<span class="msg red">NON STAI CALCOLANDO ALCUN EXTRA</span>' : '';
$coperti = $evento['numero_adulti']+$evento['numero_operatori']+$extra_coperti;
include("../../fl_inc/headers.php");
 ?>
 
<body style="background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 5px;">
<div class="content_scheda">
<?php if($evento_id > 1) { ?>
<h1><?php echo $evento['titolo_ricorrenza']; ?></h1>
<p><?php echo mydatetime($evento['data_evento']); ?></p>
<?php } else {  ?>
<h1>Menù Demo</h1>
<?php } ?>
<p>Coperti Evento: <?php echo $evento['numero_adulti']+$evento['numero_operatori']; ?> | Extra pianificato: <?php echo $extra_coperti; ?> | <strong>TOTALE:</strong> <span class="msg green"><?php echo $coperti; ?></span>  <?php echo $alert; ?></p>


<?php 
	
	
  $queryPortate = "SELECT r.variante,r.portata,r.id,r.nome,r.valore_di_conversione,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId FROM fl_ricettario r JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
  $resultPortate = @mysql_query($queryPortate,CONNECT);

	?>
    
   <table class="dati">

   <tr>
   <th>Codice</th>
   <th>Descrizione</th>
   <th>UM</th>
   <th>Fabbisogno</th>
   <th>Giacenza</th>
   <th></th>
   <th></th>
   <th>Da ordinare</th>
   </tr>

 <?php

$fornitore = $data_set->data_retriever('fl_anagrafica','ragione_sociale','WHERE tipo_profilo = 3',' ragione_sociale ASC'); //Crea un array con i valori X2 della tabella X1
	
$ricette = array();
while ($row = @mysql_fetch_array($resultPortate)) {
$ricette[] = $row['id'];
}

$ricetteIN = implode(',', $ricette);

$superQuery = "SELECT materia.anagrafica_id,materia.codice_articolo, ricetta.id AS id, ingrediente.id AS iid, ingrediente.materiaprima_id, SUM( ingrediente.quantita ) AS quantita, materia.descrizione, materia.unita_di_misura, materia.valore_di_conversione
FROM `fl_ricettario` AS ricetta
LEFT JOIN fl_ricettario_diba AS ingrediente ON ingrediente.ricetta_id = ricetta.id
LEFT JOIN fl_materieprime AS materia ON ingrediente.materiaprima_id = materia.id
WHERE ingrediente.id > 1 AND ricetta.id
IN (".$ricetteIN.") $fonitoreFiltro
GROUP BY ingrediente.materiaprima_id
ORDER BY materia.descrizione ASC
";

$diba = @mysql_query($superQuery,CONNECT);


while ($ingrediente = @mysql_fetch_array($diba)) {  
 
  $fabbisogno = $ingrediente['quantita']*$coperti;
  $arr = 3;
  $note = '';
  if($ingrediente['unita_di_misura'] == 'KP')  {  $note = '('.round($fabbisogno).' pz)'; $fabbisogno = ($ingrediente['quantita']/$ingrediente['valore_di_conversione'])*$coperti; }
  if($ingrediente['unita_di_misura'] == 'PZ')  { $fabbisogno = round($fabbisogno);  $arr = 0; }
 
  ?>


   <tr>
   <td><?php echo $ingrediente['codice_articolo']; ?></td>
   <td><?php echo $ingrediente['descrizione']; ?></td>
   <td><?php echo $ingrediente['unita_di_misura']; ?></td>
   <td><?php echo numdec($fabbisogno,$arr).' '.$note; ?></td>
   <td>0</td>
   <td><?php echo ($ingrediente['anagrafica_id'] > 1) ? '<a href="mod_fabbisogno.php?evento_id='.$evento_id.'&menuId='.$menuId.'&fornitore='.$ingrediente['anagrafica_id'].'">'.$fornitore[$ingrediente['anagrafica_id']].'</a>' : ''; ?></td>
   <td><?php echo ($ingrediente['anagrafica_id2'] > 1) ? $fornitore[$ingrediente['anagrafica_id2']] : ''; ?></td>
   <td><?php echo numdec($fabbisogno,$arr); ?></td>

   </tr>


<?php } ?>

  </table>


<form action="mod_opera.php" method="GET" class="no-print">Extra preparazione: <input type="number" name="extra_coperti" value="<?php echo $extra_coperti; ?>">
  <input type="submit" value="Aggiorna Fabbisogno" class="button">
<a href="javascript:window.print();" class="button">Stampa</a>
<a href="mod_fabbisogno.php?evento_id=<?php echo $evento_id; ?>&menuId=<?php echo $menuId; ?>"  class="button">Vedi Tutti</a></form>
</div>
</body></html>
