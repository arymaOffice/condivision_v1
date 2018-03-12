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
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Accesso Registrato</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
        <th>Id</th>
         <th>Operazione</th>
        <th>Utente</th>
        <th>Data Accesso</th>
        <td>Ip Address</th>
        <th>Lingua</th>
        <th>Elimina</th>
     
      </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{  ?> 
    
     
      <tr>
      
        <td><?php echo $riga['id']; ?></td>
           <td><?php echo $riga['pagina']; ?></td>
    
        <td><a href="./?cerca=<?php echo $riga['utente']; ?>" title="Browser Utilizzato: (<?php echo $riga['agent']; ?>)" ><?php echo $riga['utente']; ?></a></td>
       
        <td><?php echo mydatetime($riga['data_creazione']);?></td>
       
        <td><a href="<?php echo $riga['referrer']; ?>" title="Provenienza Utente: (<?php echo $riga['referrer']; ?>)" target="_blank"><?php echo $riga['ip'] ?></a></td>
       
        <td><?php echo substr($riga['lang'],0,2); ?></td>
       
        <td><a href="../mod_basic/elimina.php?gtx=$tab_id&unset=<?php echo $riga['id']; ?>" title="Elimina" onclick="conferma_del();"><i class="fa fa-trash-o"></i></a></td> 

    <?php }  //Chiudo la Connessione	?>
    
 </table>
 <?php 	paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); mysql_close(CONNECT);} ?>