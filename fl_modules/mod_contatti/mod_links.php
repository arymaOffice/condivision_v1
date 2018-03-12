<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');

unset($chat);
$tab_id = 13;
$anagrafica_id = check($_GET['anagrafica_id']);
$link_type = get_items_key("link_type");	
$link_type[0] = 'Link';
include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: left; padding: 20px;">
<form id="" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<select name="link_type" >
 <?php 
              
		     foreach($link_type as $valores => $label){ // Recursione Indici di Categoria
			if($valores != 0){ echo "<option value=\"$valores\">".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
</select>
<input type="hidden" name="anagrafica_id" value="<?php echo $anagrafica_id; ?>" />
<input type="hidden" name="account_id" value="<?php echo $_SESSION['number']; ?>" />
<input type="hidden" name="workflow_id"  value="anagrafica" />
<input type="text" name="label" placeholder="Descrizione" value="" />
<input type="text" name="link" placeholder="http://" value="" />
<input type="submit" value="Inserisci" />
</form>
<?php 
	
	
	$query = "SELECT * FROM `fl_links` WHERE `anagrafica_id` = $anagrafica_id";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Link</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
   <th>Categoria</th>
   <th>Descrizione</th>
   <th></th>
   </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_assoc($risultato)) 
	{ 
	?> 
    
     
      <tr>
      <td><span class=\"Gletter\"></span></td>
      <td><?php echo $link_type[$riga['link_type']]; ?></td>
      <td><?php echo $riga['label']; ?><br><a href="<?php echo $riga['link']; ?>"><?php echo $riga['link']; ?></a></td>
  	  <td><a href="../mod_basic/action_elimina.php?gtx=<?php echo $tab_id; ?>&amp;unset=<?php echo $riga['id'];?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>

    <?php } } //Chiudo la Connessione	?>
    
 </table>
</body></html>