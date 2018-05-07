
<div class="box_div">

<?php  

	$where = "WHERE cat = 17";
  	
	$query = "SELECT * FROM $tabella $where ORDER BY cat ASC ";
	$risultato = mysql_query($query, CONNECT);
	
	?>
    <div id="class"></div>
    <table class="dati" summary="Dati">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Utente</th>
        <th>File</th>
        <th>Categoria</th>
        <th>Ultima Modifica</th>
        <th>Categoria</th>
        <th scope="col" class="tasto">Elimina</th>
    </tr>
          
    <?php
	
	if(mysql_affected_rows() == 0){echo "<tr><td colspan=\"5\">Nessun File Caricato</td></tr>";}
      echo mysql_affected_rows();
	
	 $destination_doc_dir = "mobilmat/"; 
	
	 if(!is_writable($dir_documenti.$destination_doc_dir)) echo "<p>$destination_doc_dir - Directory non scrivibile.</p>";
	
	while ($riga = mysql_fetch_array($risultato)) {

	$download_item = "scarica.php?dir=".$dir_documenti.$doc_dir."&amp;file=".$riga['file'];
	//copy($dir_documenti.$doc_dir.$riga['file'],$dir_documenti.$destination_doc_dir.$riga['file']);
	 ?>
    
     
      <tr>
      
      <td><?php echo $riga['relation']; ?></td>
        <td><?php echo @$proprietario[$riga['relation']]; ?></td>
       
        <td ><a href="<?php echo $download_item; ?>" title="Apri File"><?php echo $riga['titolo']; ?></a><br /><span style="font-size: 9px;"><?php echo $dir_documenti.$doc_dir.$riga['file']; ?></span></td>
       
        <td ><?php echo date("d-m-Y h:i",$riga['data']); ?></td>
      
       	<td><?php echo $cat_multi_mod[$riga['cat']]; ?></td>
   
        <td><?php echo '<a href="../mod_basic/elimina.php?gtx='.$tab_id.'&unset='.$riga['id'].'&file='.$dir_documenti.$doc_dir.$riga['file'].'" title="Elimina" class="button" onclick="conferma_del();">X</a>'; ?></td>
                  
      </tr>
  
	


    <?php } mysql_close(CONNECT);  ?>

 

 </table>

