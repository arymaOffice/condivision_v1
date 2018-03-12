<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>
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
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Record</p>"; } else {
	?>
    
<table class="dati">
   <tr>
   <th style="width: 1%;"></th>
    <th>Codice</th>
        <th>Descrizione</th>
        <th>Aliquota</th>
        <th></th>
    </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{ 
	if($riga['attivo'] == 0) {  $colore = "class=\"tab_red\"";  } else { $colore = "class=\"tab_green\""; }
	?> 
    
     
      <tr>
      
     <td <?php echo $colore; ?>><span class=\"Gletter\"></span></td>
           <td><?php echo $riga['codice']; ?></td>
        <td><?php echo $riga['descrizione']; ?></td>
         <td><?php echo numdec($riga['aliquota'],2); ?>%</td>
 		<td><a href="mod_inserisci.php?id=<?php echo $riga['id']; ?>" title="Modifica"> <i class="fa fa-search"></i> </a>


    <?php }  //Chiudo la Connessione	?>
    
 </table>
 <?php 	paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); mysql_close(CONNECT);} ?>