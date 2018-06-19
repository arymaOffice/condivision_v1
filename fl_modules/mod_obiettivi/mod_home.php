<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Obbiettivi</h1>
   <form method="get" action="" id="sezione_select">
   
 <p>
    Punto vendita: 
      <select name="operatore" id="operatore" onchange="form.submit();">
               <?php 
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
      mese : 
             <select name="mese" id="mese">
               <?php 
			   for($i=1;$i<13;$i++){
			     ($mese_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$mese[$i].'</option>';
				} ?>
       </select>
              <select name="anno" id="anno">
               <?php 
			   $time = date("Y");
			   for($i=($time-1);$i<($time+4);$i++){
			   ($anno_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
				} ?>
       </select>
         
    <input type="submit" value="Mostra" class="button noprint" /></p>
</form>   
	<?php 
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT * FROM $tabella $tipologia_main ORDER BY $ordine LIMIT $start,$step";
	//echo $query;
	$risultato = mysql_query($query, CONNECT);
	if(mysql_affected_rows() == 0){ echo "<p>Nessun Record</p>"; } else {
	?>
    
    <table class="dati">
      <tr>
   <th style="width: 1%;"></th>
    <th>Periodo</th>
    <th>Fatturato Lordo</th>
    <th>DNR</th>
    <th>Ore Lavoro</th>
    <th>Costo Orario</th>
    <th>Produttivit&agrave;</th>
    <th></th>
     
      </tr>
          
 <?php
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{ 
	 $colore = "class=\"tab_green\""; 
	?> 
    
     
      <tr>
      
     <td <?php echo $colore; ?>><span class=\"Gletter\"></span></td>
        <td><?php echo $mese[$riga['mese']].' '.$riga['anno']; ?></td>
        
        <td>&euro; <?php echo $riga['fatturato_lordo']; ?></td>
        <td><?php echo $riga['deals_number']; ?></td>
        <td><?php echo $riga['ore_lavoro']; ?></td>
        <td><?php echo $riga['costo_orario']; ?></td>
        <td>&euro;  <?php echo @numdec(@$riga['fatturato_lordo']/@$riga['ore_lavoro'],2); ?></td>
 		<td><a href="./?action=1&amp;id=<?php echo $riga['id']; ?>" title="Modifica"> <i class="fa fa-search"></i> </a>


    <?php }  //Chiudo la Connessione	?>
    
 </table>
 <p>
    <?php 	paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); mysql_close(CONNECT);} ?>
</p>
 <p>&nbsp;</p>
 <p><strong><i class="fa fa-info-circle"></i></strong> Il <strong>DNR</strong> è il numero di <strong>deals</strong> che determina la produttività. (Es. Contratti, Fatture, Scontrini, Contatti dal web).</p>
