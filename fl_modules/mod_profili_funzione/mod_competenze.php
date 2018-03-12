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
$parent_id = check($_GET['reQiD']);
include("../../fl_inc/headers.php");

?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<?php if($parent_id < 2) { echo '<h2>Salva la scheda prima di inserire le competenze.'; exit; } ?>

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<select name="categoria" id="check_Type">
 <?php 
              
		     foreach($categoria as $valores => $label){ // Recursione Indici di Categoria
			 $selected = (@$_SESSION['categoria'] == $valores) ? 'selected="selected"' : '';
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
</select>
<input type="text" name="etichetta" value="" placeholder="Descrizione Competenza" /> 
Livello
<select name="livello_atteso" id="livello_atteso">
 <?php 
              
		     foreach($livello_atteso as $valores => $label){ // Recursione Indici di Categoria
			 $selected = (@$_SESSION['livello_atteso'] == $valores)  ? 'selected="selected"' : '';
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
</select>
Priorità
<input type="text" name="priorita" value="1" size="2"/> 

<input type="hidden" name="funzione_id" value="<?php echo $parent_id; ?>" /> 
<input type="submit" value="Inserisci" />

</form>
<?php 

	
	$query = "SELECT * FROM `fl_competenze` WHERE id > 1 AND `funzione_id` = $parent_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Elemento</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
    <th>Categoria</th>
   <th>Competenze</th>
   <th>Livello Atteso</th>
   <th>Priorità</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><span class="Gletter"></span></td>
       <td><?php echo $categoria[$riga['categoria']]; ?> </td>
      <td><?php echo $riga['etichetta']; ?></td>
      <td><?php echo $livello_atteso[$riga['livello_atteso']]; ?> </td>
       <td><?php  echo "<input type=\"number\" name=\"priorita\"  class=\"updateField\" data-rel=\"".$riga['id']."\" value=\"".$riga['priorita']."\" />";?></td>
      <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>