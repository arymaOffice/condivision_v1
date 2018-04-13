<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(!isset($_GET['menuId'])) die('Manca Menu ID');
$menuId = check($_GET['menuId']);
$evento_id = check($_GET['evento_id']);
$evento = GRD($tables[6],$evento_id);
$fonitoreFiltro = (isset($_GET['fornitore'])) ? ' AND materia.anagrafica_id = '.check($_GET['fornitore']) : '';
$extra_coperti = (isset($_SESSION['extra_coperti'])) ? check($_SESSION['extra_coperti']) : 10;
$coperti = $evento['numero_adulti']+$evento['numero_operatori']+$extra_coperti;
$dati = GRD('fl_config',2); // Dati del cliente che fattura
$mittente = GRD('fl_sedi',31); // Dati del cliente che fattura
$destinatario = GRD('fl_sedi',$evento['location_evento']); // Dati del cliente che fattura
$righe = (isset($_GET['righe'])) ? check($_GET['righe']) : 5;
    
include("../../fl_inc/headers.php");
?>


<table class="dati">
  <tr>
 <th colspan="2"><img src="<?php echo LOGO; ?>" alt="<?php echo client; ?>"/></th>
 <th colspan="2">MITTENTE: <?php echo $dati['ragione_sociale']; ?> <br> 
 <?php echo $mittente['indirizzo']; ?> <br> 
<?php echo $mittente['cap']; ?> <?php echo $mittente['citta']; ?></th>
 </tr> 

 <tr>
 <th colspan="2"><h1>DOCUMENTO DI TRASPORTO</h1>
</th>
 <th colspan="2">
DESTINATARIO:<?php echo $dati['ragione_sociale']; ?> <br> 
 <?php echo $destinatario['indirizzo']; ?> <br> 
<?php echo $destinatario['cap']; ?> <?php echo $destinatario['citta']; ?></th>
 </tr> 


 <tr>
 <td>NUMERO</td>
 <td>DATA</td>
 <td>P.IVA</td>
 <td>CAUSALE</td>
 </tr> 
 <tr>

 <td><input type="text"  value="<?php echo $evento_id; ?>/<?php echo date('Y'); ?>" /></td>
 <td><input type="text" class="calendar" value="<?php echo date('d/m/Y'); ?>" /></td>
 <td><input type="text"  value="<?php echo $dati['partita_iva']; ?>" /></td>
 <td><input type="text"  value="TRASPORTO SEMILAVORATI" style="width: 100%;" /></td>
 </tr> 

</table>
<table class="dati">
 <tr>
    <th>CODICE</th>
    <th>DESCRIZIONE</th>
    <th>UM</th>
    <th>QTA</th>
  </tr>

<?php 

$queryPortate = "SELECT r.variante,r.portata,r.id,r.nome,r.valore_di_conversione,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId FROM fl_ricettario r JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
$resultPortate = @mysql_query($queryPortate,CONNECT);
$groupId = -1;
echo mysql_error();
$thTemplate = '';
$totCosto = 0;
$totVendita = 0;

while ($row = @mysql_fetch_array($resultPortate)) {

if($groupId != $row['portata']) { 
if($row['valore'] == '1') $row['valore'] = '';
//echo '<tr><th colspan="3"><h2>'.$portata[$row['portata']].'</h2></th></tr>'.$thTemplate;  
$groupId = $row['portata']; 
}

    

$nome = (isset($row['nome_tecnico']) && $row['nome_tecnico'] != '') ? converti_txt($row['nome_tecnico']) : converti_txt($row['nome']);

echo '<tr id="p'.$row['id'].'">';
echo '<td><input type="text"  style="border: none; width: 30px;" value="'.$row['id'].'" ></td>';
echo '<td style="min-width: 580px;">'.$nome.'</td>';
echo '<td>PZ</td>';
echo '<td style="text-align: center;"><input type="text" name="numero" style="border: none; width: 50px;" value="'.$coperti.'" ></td>';
echo '</tr>'; 

}


for($i=0;$i<$righe;$i++) {

echo '<tr>';
echo '<td><input type="text"  style="border: none; width: 50px;" value="" ></td>';
echo '<td><input type="text"  style="border: none; width: 100%;" value="" ></td>';
echo '<td>PZ</td>';
echo '<td style="text-align: center;"><input type="text" name="numero" style="border: none; width: 50px;" value="0" ></td>';
echo '</tr>'; 

}


?>



</table>

<table class="dati">

 <tr>
 <th>&nbsp;</th>
 <th></th>
 <th><strong>Firma:</strong></th>
 <th></th>
 </tr> 

 <tr>
 <th>Porto</th>
 <th>Trasporto a carico del</th>
 <th>Data Trasporto</th>
 <th>Orario</th>
 </tr> 

 <tr>
 <th><input type="text"  value="FRANCO" /></th>
 <th><input type="text" value="MITTENTE" /></th>
 <th><input type="text" class="calendar" value="<?php echo date('d/m/Y'); ?>" /></th>
 <th><input type="text" class="calendar" value="<?php echo date('h:i'); ?>" /></th>
 </tr> 

 <tr>
 <th>Aspetto esteriore dei beni</th>
 <th>N. Colli</th>
 <th>Peso Kg.</th>
 <th>Vettore</th>
 </tr> 

 <tr>
 <th><input type="text"  value="A VISTA" /></th>
 <th><input type="text" value="" /></th>
 <th><input type="text"  value="" /></th>
 <th><input type="text"  value="" /></th>
 </tr> 

 <tr>
 <th colspan="2"><h2>Firma del conducente:</h2></th>
 <th colspan="2"><h2>Firma del destinatario:</h2></th>
 </tr> 





</table>

<a href="javascript:window.print();" class="button no-print noprint">Stampa DDT</a>



