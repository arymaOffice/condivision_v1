      	
	<?php 
	
	// Inclusione Modulo
	
	echo "<h1><span class=\"intestazione\">$txt_soggetto</span></h1><img src=\"../../fl_inc/icons/37.png\" align=\"right\" alt=\"Stampa\" />"; 
		
	//Funzione Connect 
	$connect = connect($host, $login, $pwd, $db);

	$start = paginazione($connect,$tabella,$step,$ordine);
	
	$query = "SELECT * FROM $tabella WHERE utente != '' ORDER BY $ordine LIMIT $start,$step";
	
	$risultato = mysql_query($query, $connect);
	
	?>
    
    <table class="dati" summary="Dati">
      <tr>
        <th scope="col" class="id">Id</th>
        <th scope="col" class="titolo">Utente</th>
        <th scope="col" class="titolo">Data Accesso</th>
        <th scope="col" class="titolo">Ip Address</th>
        <th scope="col" class="tasto">Lingua</th>
        <th scope="col" class="tasto">Elimina</th>
       
      </tr>
          
    <?php
	
	if(mysql_affected_rows() == 0){echo "<p>Nessuna Accesso Registrato</p>";}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{  ?> 
    
     
      <tr>
      
        <td><?php echo $riga['id']; ?></td>
       
        <td class="titolo"><a href="#" title="Browser Utilizzato: (<?php echo $riga['agent']; ?>)" ><?php echo $riga['utente']; ?></a></td>
       
        <td class="titolo"><?php echo date("H:i",$riga['date']);?> del <?php echo date("d-m-Y",$riga['date']);?></td>
       
        <td class="titolo"><a href="<?php echo $riga['referrer']; ?>" title="Provenienza Utente: (<?php echo $riga['referrer']; ?>)" target="_blank"><?php echo $riga['ip'] ?></a></td>
       
        <td><?php echo substr($riga['lang'],0,2); ?></td>
       
        <td><a href="#" title="Elimina" class="button" onclick="elimina('<?php echo $riga['id']; ?>','0');">X</a></td>
       
      
        
  	


    <?php } mysql_close($connect); //Chiudo la Connessione	?>
    
 </table>