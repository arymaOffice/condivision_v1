<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
require('../../fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
$anagrafica = $data_set->data_retriever('fl_anagrafica','cognome,nome','WHERE id > 1 AND tipo_profilo != 2','cognome ASC');
unset($chat);
$categoria = array('Conoscenze','Capacità/Abilità','Caratteristiche');
$livello_atteso = array('A','B','C');
$tab_id = 84;
$persona_id = check($_GET['PerID']);
$parent_id = GRD('fl_persone',$persona_id);
$valutazione = array(0=>'Non valutato','1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5);
$a0=$a1=$a2=$a3=$a4=$a5= 0;

include("../../fl_inc/headers.php");

?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<?php if($parent_id < 2) { echo '<h2>Salva la scheda prima di inserire le competenze.'; exit; } ?>
<h1>Valutazione delle competenze</h1>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<input type="hidden" name="funzione_id" value="<?php echo $parent_id['profilo_funzione']; ?>" /> 
<input type="hidden" name="persona_id" value="<?php echo $persona_id; ?>" /> 

<?php 
function getVal($competenza,$persona){
	$query = "SELECT * FROM fl_competenze_val WHERE persona_id = $persona AND `competenza_id` = ".$competenza." ORDER BY data_aggiornamento DESC LIMIT 1";
	$risultato = mysql_fetch_assoc(mysql_query($query, CONNECT));
	return $risultato;	
	}
	
	$query = "SELECT * FROM `fl_competenze` WHERE id > 1 AND `funzione_id` = ".$parent_id['profilo_funzione'];
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
		
		
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
   <th>Categoria</th>
   <th>Competenze</th>
   <th>Livello Atteso</th>
   <th>Valutazione</th>
   </tr>
          
 <?php
	

	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	$ultima_valutazione = getVal($riga['id'],$persona_id);
	$u = 'a'.$ultima_valutazione['valore'];
	if(isset($ultima_valutazione['data_aggiornamento'])) $$u++;
	$span = ($ultima_valutazione['valore'] < 3) ? 'c-orange' : 'c-green';
	?> 
    
     
      <tr>
      <td><span class="Gletter"></span></td>
       <td><?php echo $categoria[$riga['categoria']]; ?><input type="hidden" name="competenze[]" value="<?php echo $riga['id']; ?>" /> </td>
      <td><?php echo $riga['etichetta']; ?><br><span class="<?php echo $span; ?>"><?php echo (isset($ultima_valutazione['data_aggiornamento'])) ? 'Valutato il: '.mydate($ultima_valutazione['data_aggiornamento']).' - Esito: '.$valutazione[$ultima_valutazione['valore']] : 'Mai valutato'; ?></span></td>
      <td><?php echo $livello_atteso[$riga['livello_atteso']]; ?> </td>
      <td><?php 
	  foreach($valutazione as $chiave => $valore){ 
	  $checked = (isset($ultima_valutazione['valore']) && @$ultima_valutazione['valore'] == $chiave) ? 'checked="checked"' : '' ;
	  if($chiave > 0) echo '<input '.$checked.' type="radio" name="valutazione'.$riga['id'].'" id="valutazione'.$riga['id'].$chiave.'" value="'.$chiave.'" /><label for="valutazione'.$riga['id'].$chiave.'">'.$valore.'</label>'; } ?>
      
      </td>
</tr>

    <?php } }
	$arr = array('0' => $a0,'1' => $a1,'2' => $a2,'3' => $a3,'4' => $a4,'5' => $a5,);
	arsort($arr);
	$name = key($arr);
	$val = 0;
	if(($a0+$a1+$a3) > 3) $val = 2;
	if(($a0+$a1+$a3) > 5) $val = 1;
	if(($a5) > 10) $val = 5;
	//Chiudo la Connessione	?>
    
 </table>
 
 <input type="submit" class="button" value="Aggiorna Scheda Valutazione" />

</form>
</body></html>