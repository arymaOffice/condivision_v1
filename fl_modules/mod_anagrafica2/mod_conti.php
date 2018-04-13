<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
unset($chat);

$tab_id = 72;
$anagrafica_id = check($_GET['anagrafica_id']);
include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(244, 244, 244) none repeat scroll 0% 0%; text-align: left; padding: 20px;">

<?php if(!isset($_GET['view'])) { ?>

<div id="results"><?php if(isset($_GET['esito'])) echo '<h2 class="red">'.check($_GET['esito']).'</h2>'; ?></div>

 <style>
 .dati { border-spacing: 0px; }
.dsh_panel input, .dsh_panel input .calendar { width: 90%; margin: 2px; }
.dsh_panel select { width: 80%; }
 </style> 
 <div class="dsh_panel big">
<h1 onClick="$('.dsh_panel_content').show();">Aggiungi Conto <a href="#" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a></h1><span class="open-close"><a href="#"><i class="fa fa-angle-up" aria-hidden="true"></i></a></span>
<div class="dsh_panel_content" style="display: none;">

<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="insert_conto" value="1" />
<input type="hidden" name="anagrafica_id" value="<?php echo $anagrafica_id; ?>" />
<input type="text" name="intestatario" value="" placeholder="Intest. Nome Cognome" />
<input type="text" name="descrizione" value="" placeholder="Es. Banca Popolare" />
<input type="text" name="estremi" value="" placeholder="Es. IBAN no. xxxx" />
<input type="submit" class="button" value="Inserisci Conto" />
</form>
</div></div>
<?php }  ?>

<?php 
	
	
	$query = "SELECT * FROM `fl_conti` WHERE `anagrafica_id` = $anagrafica_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Conto inserito</p>"; } else {
	?>
    
    <table class="dati">
    <tr>
   <th>Conto</th>
   <th>Banca</th>
    <th>Estremi</th>
     <th></th>
   </tr>
          
 <?php
	
if(mysql_affected_rows() < 1) echo "<p>Nessun elemento</p>";
	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><input type="text" value="<?php echo $riga['intestatario']; ?>" style="width: 100%;" name="intestatario" class="updateField" data-rel="<?php echo $riga['id']; ?>" /></td>
      <td><input type="text" value="<?php echo $riga['descrizione']; ?>" style="width: 100%;" name="descrizione" class="updateField" data-rel="<?php echo $riga['id']; ?>" /></td>
  	  <td><input type="text" value="<?php echo $riga['estremi']; ?>" style="width: 100%;" name="estremi" class="updateField" data-rel="<?php echo $riga['id']; ?>" /></td>
  	  <?php if($_SESSION['number'] == 1) { ?> <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td><?php } ?>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>