<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");
?>
  	<?php 
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT * FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step";
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ if(isset($_GET['cerca'])){ echo "<h2>".check($_GET['cerca'])."</h2>"; } echo "<p>Nessun Accesso Registrato</p>";} else {
	?>
    
    <table class="dati">
      <tr>
      
        <th>Data Accesso</th>
         <th>User</th>
        <td>Ip Address</th>
        <th>Lingua</th>
      
      </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{  ?> 
    
     
      <tr>
       
         
        <td><?php echo mydatetime($riga['data_creazione']);?></td>
        
         <td><?php echo $riga['utente']; ?></td>
       
        <td><a href="<?php echo $riga['referrer']; ?>" title="Provenienza Utente: (<?php echo $riga['referrer']; ?>)" target="_blank"><?php echo $riga['ip'] ?></a></td>
       
        <td><?php echo substr($riga['lang'],0,2); ?></td>
       
   

    <?php }  //Chiudo la Connessione	?>
    
 </table>
 <?php 	paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); mysql_close(CONNECT);} ?>