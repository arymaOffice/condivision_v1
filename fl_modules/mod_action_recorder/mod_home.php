<div class="filtri"> 
  <form id="fm_cerca" action="" method="get">
      <span id="cerca">
      <input name="cerca" type="text" value="<?php if(isset($_GET['cerca'])){ echo check($_GET['cerca']);} else { echo "Ricerca user"; } ?>" onFocus="this.value='';"  maxlength="200" class="txt_cerca" />
      <input type="submit" value="Cerca" class="button" />
      </span>
    </form>
    
 </div>   	<?php 
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT * FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step";
	//echo $query;
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessuna Azione Registrata</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
        <th>Id</th>
        <th>Utente</th>
        <th>Azione</th>
        <th>Modulo</th>
        <th>Data Azione</th>
        <td>Ip Address</th>
        <th>Elimina</th>
     
      </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{  ?> 
    
     
      <tr>
      
        <td><?php echo $riga['id']; ?></td>
       
        <td><a href="./?cerca=<?php echo $riga['utente']; ?>" title="Browser Utilizzato: (<?php echo $riga['agent']; ?>)" ><?php echo $riga['utente']; ?></a></td>
         <td><?php echo $riga['kind'] ?></td>
           <td><?php echo $riga['table'] ?> (<?php echo $riga['record_id'] ?>)</td>
        <td><?php echo mydatetime($riga['data_creazione']);?></td>
     
        <td><?php echo $riga['ip'] ?></td>
       
           <td><a href="../mod_basic/elimina.php?gtx=<?php echo  $tab_id; ?>&unset=<?php echo $riga['id']; ?>" title="Elimina" onclick="conferma_del();"><i class="fa fa-trash-o"></i></a></td>     

 

    <?php }  //Chiudo la Connessione	?>
    
 </table>
 <?php 	paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); mysql_close(CONNECT);} ?>