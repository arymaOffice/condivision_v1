<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
$id = check($_GET['id']);
$tab_id = 68;
unset($chat);
$item_type = array('Criticità'); 

include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">
<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<select name="type" >
 <?php 
              
		     foreach($item_type as $valores => $label){ // Recursione Indici di Categoria
			 echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n";
			}
		 ?>
</select>
<select name="priority" >
 <?php 
              
		     foreach($priority as $valores => $label){ // Recursione Indici di Categoria
			 echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n";
			}
		 ?>
</select>

<input type="hidden" name="elemento"  value="<?php echo $id; ?>" />

<input type="text" name="note" placeholder="note" value="" />
<input type="submit" value="Inserisci" />
</form>
<?php 
	
	
	$query = "SELECT * FROM `fl_parametri` WHERE `elemento` = $id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Parametro</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
   <th>Categoria</th>
   <th>Priorità</th>
   <th>Note</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><span class="Gletter"></span></td>
      <td><?php echo $item_type[$riga['type']]; ?></td>
      <td><?php echo $priority[$riga['priority']]; ?></td>
	  <td><?php echo $riga['note']; ?></td>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>