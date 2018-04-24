<?php 

require_once('../../fl_core/autentication.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
include('../../fl_core/dataset/array_statiche.php');

$data_set = new ARY_dataInterface();
$aliquote = $data_set->data_retriever('fl_aliquote','aliquota,descrizione','WHERE id > 1');

unset($chat);
if(defined('documenti_vendita_descrittivi')) {
$text_editor = 2;
}

$tab_id = 82;
$doc_acquisto_id = check($_GET['DAiD']);
include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<?php if($doc_acquisto_id == 1) { echo "<h2>Salva i dettagli prima di inserire</h2>"; exit; } ?>

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="doc_acquisto_id" value="<?php echo $doc_acquisto_id; ?>" /> 
<input type="text" name="codice" placeholder="Codice" value="" style="width: 70px;" />
<textarea name="descrizione" placeholder="Descrizione" value="" style="min-width: 200px;" class="mceEditor" ></textarea>
<select name="valuta" id="valuta" style="width: 70px;">
 <?php 
              
		     foreach($valuta as $valores => $label){ // Recursione Indici di Categoria
			 echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
</select>

<input type="number" name="quantita" placeholder="" value="1" style="width: 50px;"  />
<input type="text" name="importo" placeholder="0.00" value="" style="width: 80px;"  />
<select name="aliquota" id="aliquota" style="width: 150px;">
 <?php 
              
		     foreach($aliquote as $valores => $label){ // Recursione Indici di Categoria
          $selected = (defined('aliquota_default') && aliquota_default == substr($label,0,2) ) ? ' selected="selected" ' : '';
			    echo "<option value=\"$label\" $selected>".ucfirst($label)."</option>\r\n"; 
		    	}
		 ?>
</select>

<input type="submit" value="Inserisci" class="button" />

</form>
<?php 

	$aliquotaMaggiore = 0;

	$query = "SELECT * FROM `fl_doc_acquisto_voci` WHERE id > 1 AND `parent_id` = $doc_acquisto_id ORDER BY id ASC";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
   <th>Codice</th>
   <th>Descrizione</th>
   <th>Prezzo</th>
   <th>UM</th>
   <th>Qtà</th>
   <th>Imponibile</th>
   <th>Aliquota</th>
   <th>Imposta</th>
   <th>Subtotale</th>
   <th></th>
   </tr>
          
 <?php
	

	$tot_imponibile = 0;
	$tot_imposta = 0;
	$totale_documenti = 0;
  

	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 


	$imponibile =  $riga['importo']*$riga['quantita'];
  $importo = $riga['importo'];
  $totaleImponibile = $valuta[$riga['valuta']].' '.numdec($imponibile,2);
  $totaleImposta = $valuta[$riga['valuta']].' '.numdec($riga['imposta'],2);

  if(defined('importi_lordi')) $imponibile = $riga['importo'];
  if(defined('importi_lordi')) $importo = $riga['subtotale']/$riga['quantita'];
  if(defined('scorporo_iva_su_totale')) $totaleImponibile = '--';
  if(defined('scorporo_iva_su_totale')) $totaleImposta = '--';

	$tot_imponibile +=  $imponibile;
	$tot_imposta += $riga['imposta'];
	$totale_documenti += $riga['subtotale'];
  if($aliquotaMaggiore < $riga['aliquota']) $aliquotaMaggiore = $riga['aliquota'];
?> 
    
     
      <tr>
      <td><span class=\"Gletter\"></span></td>
       <td><?php echo $riga['codice']; ?></td>
     
      <td>
      <?php if(defined('documenti_vendita_descrittivi')) { ?>
      <?php echo  html_entity_decode(converti_txt($riga['descrizione'])); ?>
      <?php } else { ?>
      <input type="text" value="<?php echo $riga['descrizione']; ?>" name="descrizione" class="updateField" data-rel="<?php echo $riga['id']; ?>" />
      <?php } ?>

      </td>
  <td><?php echo $valuta[$riga['valuta']]; ?> <?php echo numdec($importo,2); ?></td>
  <td><?php echo $riga['unita_di_misura']; ?></td>
  <td><?php echo numdec($riga['quantita'],3); ?></td>
  <td><?php echo $totaleImponibile; ?></td>
  <td><?php echo numdec($riga['aliquota'],2); ?> % </td>
  <td><?php echo $totaleImposta; ?></td>
  <td><?php echo $valuta[$riga['valuta']]; ?> <?php echo numdec($riga['subtotale'],2); ?></td>
  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>


</tr>

    <?php } 
	
  if(defined('scorporo_iva_su_totale')){
  $coeffAliquota = '1.'.$aliquotaMaggiore;
  $tot_imponibile = round($totale_documenti/$coeffAliquota,2,PHP_ROUND_HALF_DOWN);
  $tot_imposta = $totale_documenti-$tot_imponibile;
  $impostePeraliquote = 'IVA '.$aliquotaMaggiore.'% = € '.numdec($tot_imposta,2).'<br>';
  }


  echo "<tr><td colspan=\"8\"></td></tr>";
	echo "<tr><td colspan=\"5\"></td>";
	echo "<td>".$valuta[0]." ".numdec($tot_imponibile,2)."</td>";
	echo "<td colspan=\"\"></td><td>".$valuta[0]." ".numdec($tot_imposta,2)."</td>";
	echo "<td>".$valuta[0]." ".numdec($totale_documenti,2)."</td></tr>";



} //Chiudo la Connessione	?>
    
 </table> 
 
 <a class="button" href="javascript:location.reload();">Ricarica Voci</a>

</body></html>