
<h1><?php echo $module_title.' '.$new_button; ?></h1>


 <?php 
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>



 <div class="filtri">
     
      </div>
 
 <p style="clear: both; text-align: left;"> 
 <a href="../mod_sms/"  style=" padding: 8px 20px;  font-size: 100%;" class="button">Archivio SMS</a> 
 <a href="../mod_sms/?action=24"  style=" padding: 8px 20px;  font-size: 100%;" class="button">Report SMS</a> 
 
 </p>

      
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Oggetto</th>
       <th scope="col">Mittente</th>
        <th scope="col">Contenuto</th>
    <th scope="col">Inviati</th>
    <th scope="col"></th>

      </tr>
	<?php 


	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
		

	 $invii = CREST('fl_sms'," template = ".$riga['id']);
	 $colore = "class=\"tab_blue\"";  
		
			echo "<tr ><td $colore><span class=\"Gletter\"></span></td>";
//			echo "<td><span class=\"msg blue\">".$tipo_template[$riga['tipo_template']]."</span></td>";  
			echo "<td><strong>".$riga['oggetto']."</strong></td>"; 
			echo "<td>".@$mittente[$riga['mittente']]."</td>"; 
			echo "<td>".$riga['messaggio']."</td>"; 
			echo "<td><strong><a href=\"../mod_sms/?template=".$riga['id']."\">".$invii."</a></strong></td>"; 

			
		    echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione \"> <i class=\"fa fa-search\"></i><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	
 $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); 
?>
