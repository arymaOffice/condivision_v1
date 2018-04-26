
<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
include('../../fl_core/dataset/array_statiche.php');
require('../../fl_core/class/ARY_dataInterface.class.php');


unset($chat);
$questions = array();
$questions[0] = 'Empatia verso il consulente: ';
$questions[1] = '(Se Virtuale) è stato contattato?';
$questions[2] = '(Se Virtuale) Ha ricevuto il preventivo?';
$questions[3] = 'La spiegazione della proposta è stata chiara?';
$questions[] = 'Test drive: Le è stato proposto?';
$questions[] = 'Test drive: Lo ha eseguito?';
$questions[] = 'Finaziamento: Le è stato proposto?';
$questions[] = 'Assicurazione: Le è stato proposta?';
$questions[] = 'Ford Protect: Le è stato proposto?';
$questions[] = 'Ha fatto valutare auto in permuta?';
$questions[] = 'Ha fatto visionare auto in permuta?';
$questions[] = 'La valutazione la soddisfa?';

$tab_id = 119;

$workflow_id = check($_GET['workflow_id']);
$record_id = check($_GET['record_id']);
$nome = GRD('fl_potentials',$record_id);


$valutazione = array(0=>'No','1'=>'Si');
$valutazione0 = array(0=>'Bassa','1'=>'Normale','2'=>'Alta');
$valutazione3 = array(0=>'Poco','1'=>'Abbastanza','2'=>'Molto');

$a0=$a1=$a2=$a3=$a4=$a5= 0;

include("../../fl_inc/headers.php");

?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">


<h1>Questionario Preventivo</h1>

<a class="button" href="../mod_preventivi/mod_user.php?potential_id=<?php echo $record_id ; ?>" data-fancybox-type="iframe" class="fancybox_view">Visualizza Preventivi</a>

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>


<form id="" action="mod_registra_recall_preventivi.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="action" value="addQuestion" /> 
<input type="hidden" name="workflow_id" value="<?php echo $workflow_id; ?>" /> 
<input type="hidden" name="record_id" value="<?php echo $record_id; ?>" /> 
<input type="hidden" name="nominativo" value="<?php echo $nome['nome'].' '.$nome['cognome']; ?>" /> 


<table>
<?php foreach($questions as $chiave => $valore){  
  echo '<tr><td style="width: 70%;">'.$valore.'</td><td style="text-align: right;">'; 
  $val = $valutazione;
  if($chiave == 0) $val = $valutazione0;
  if($chiave == 3) $val = $valutazione3;
  foreach($val as $chiave2 => $valore2){ echo '<input type="radio" name="question'.$chiave.'" id="valutazione'.$chiave.'-'.$chiave2.'" value="'.$valore2.'" /><label for="valutazione'.$chiave.'-'.$chiave2.'">'.$valore2.'</label>'; }
  echo '</td></tr>';
  } ?>

</table>




<textarea  name="note" placeholder="Inserisci note" style="width: 80%; height: 150px;"></textarea><br>
<label style="color: #6190D5;"> <input type="checkbox" name="inviaAlert" value="1" style="display: inline-block;" /> Invia Alert </label>
<br>
<input type="submit" class="button" value="Registra Questionario" />


</form>













<?php 

  
  $query1 = "SELECT * FROM ".$tables[120]." WHERE id > 1 AND `workflow_id` = 69 AND record_id = ".$record_id." ORDER BY data_aggiornamento DESC";
  $risultato1 = mysql_query($query1, CONNECT);


  while ($recall = mysql_fetch_assoc($risultato1)) 
  { 

  ?> 
   <br>   <br> 
 <h2>Questionario eseguito alle  <?php echo mydatetime($recall['data_creazione']); ?> </h2>  
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
      <td><?php echo $riga['answer']; ?>
      </td>

       <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td></tr>


 <?php } ?>
 
</table>
 <?php } ?> 


 

</body></html>