
<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
include('../../fl_core/dataset/array_statiche.php');
require('../../fl_core/class/ARY_dataInterface.class.php');


unset($chat);

$tab_id = 119;

$workflow_id = check($_GET['workflow_id']);
$record_id = check($_GET['record_id']);
$nome = GRD('fl_potentials',$record_id);


$data_set = new ARY_dataInterface();
$tipologia_veicolo = $data_set->get_items_key("tipo_interesse");  
$pagamento_veicolo = $data_set->get_items_key("pagamento_vettura"); 


$valutazione = array(0=>'Non valutato','1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5);
$a0=$a1=$a2=$a3=$a4=$a5= 0;

include("../../fl_inc/headers.php");

?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<h1>Veicolo Posseduto</h1>
<?php 
  

  $query = "SELECT * FROM `fl_veicoli` WHERE workflow_id = 16 AND `parent_id` = $record_id";
  $risultato = mysql_query($query, CONNECT);
  if(mysql_affected_rows() == 0){ echo "<p>Nessun Veicolo</p>"; } else {
  ?>
    
   <table class="dati">
   <tr>
   <th>Veicolo/Status/Alimentaz./Pagamento</th>
   <th>Km/Immatricolazione</th>
   <th>Targa</th>
   </tr>
          
 <?php
  

  
  while ($riga = mysql_fetch_assoc($risultato)) 
  { 
  ?> 
    
     
    <tr>
    <td title="<?php echo strip_tags(@$riga['descrizione']); ?>"><?php echo @$riga['marca'].' '.@$riga['modello'].' '.@$riga['colore']; ?><br>
      <span class="msg orange"><?php echo @$tipologia_veicolo[$riga['tipologia_veicolo']]; ?></span>
      <span class="msg blue"><?php echo @$alimentazione[$riga['alimentazione']]; ?></span>
     <span class="msg gray"><?php echo @$pagamento_veicolo[$riga['pagamento_veicolo']]; ?></span></td>
    <td><?php echo$riga['chilometri_percorsi'].'KM - '.@ mydate($riga['anno_immatricolazione']); ?></span></td>
    <td><?php echo $riga['targa']; ?></span></td>
</tr>

    <?php } } //Chiudo la Connessione ?>
    
 </table>

<h1>Questionario</h1>





<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>


<form id="" action="mod_opera.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="action" value="addQuestion" /> 
<input type="hidden" name="workflow_id" value="<?php echo $workflow_id; ?>" /> 
<input type="hidden" name="record_id" value="<?php echo $record_id; ?>" /> 
<input type="hidden" name="nominativo" value="<?php echo $nome['nome'].' '.$nome['cognome']; ?>" /> 


<table>
<?php foreach($questions as $chiave => $valore){  
  echo '<tr><td>'.$valore.'</td><td style="text-align: right;">'; 
  foreach($valutazione as $chiave2 => $valore2){ echo '<input type="radio" name="question'.$chiave.'" id="valutazione'.$chiave.'-'.$chiave2.'" value="'.$chiave2.'" /><label for="valutazione'.$chiave.'-'.$chiave2.'">'.$valore2.'</label>'; }
  echo '</td></tr>';
  } ?>

</table>




<textarea  name="note" placeholder="Inserisci note" style="width: 80%; height: 150px;"></textarea><br>
<label style="color: #6190D5;"> <input type="checkbox" name="inviaAlert" value="1" style="display: inline-block;" /> Invia Alert </label>
<br>
<input type="submit" class="button" value="Registra Questionario" />


</form>













<?php 

  
  $query1 = "SELECT * FROM ".$tables[120]." WHERE id > 1 AND `workflow_id` = ".$workflow_id. " AND record_id = ".$record_id." ORDER BY data_aggiornamento DESC";
  $risultato1 = mysql_query($query1, CONNECT);


  while ($recall = mysql_fetch_assoc($risultato1)) 
  { 

  ?> 
   <br>   <br> 
 <h2>Recall eseguita alle  <?php echo mydatetime($recall['data_creazione']); ?> </h2>  
 <textarea class="updateField" data-gtx="120" name="note" data-rel="<?php echo $recall['id']; ?>"><?php echo $recall['note']; ?></textarea>
   
 
  <?php } ?> 


<?php 
//AND DATE(data_creazione) = '".substr($recall['data_creazione'],0,10)."'
	
	$query = "SELECT * FROM ".$tables[$tab_id]." WHERE id > 1 AND `workflow_id` = ".$workflow_id. " AND record_id = ".$record_id."  ORDER BY data_aggiornamento DESC";
	$risultato = mysql_query($query, CONNECT);

	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
		
		
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
   <th>Domanda</th>
   <th>Valutazione</th>

   <th></th>
   </tr>
          
 <?php
	

	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 

	?> 
    
     
      <tr>
      <td><span class="Gletter"></span></td>
      <td><?php echo $questions[$riga['question']]; ?><br>Rilevato alle: <?php echo mydatetime($riga['data_creazione']); ?></td>
      <td><?php 
	  foreach($valutazione as $chiave => $valore){ 
	  $checked = (isset($riga['value']) && @$riga['value'] == $chiave) ? 'checked="checked"' : '' ;
	  if($chiave > 0) echo '<input '.$checked.' type="radio" name="valutazione'.$riga['id'].'" id="valutazione'.$riga['id'].$chiave.'" value="'.$chiave.'" disabled /><label for="valutazione'.$riga['id'].$chiave.'">'.$valore.'</label>'; } ?>
      </td>

       <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td></tr>


 <?php } ?>
 
</table>
 <?php } ?> 


 

</body></html>