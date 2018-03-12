



<div class="content">


 <div class="filtri">
<form method="get" action="" id="fm_filtri">
  
  <span style="position: relative;">
   
  <?php $data_retrive->do_select('VALUES','fl_sms','from'); ?>
    
      data  <input type="text" name="data_da" onChange="form.submit();" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
       <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
      </div>
      
       
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Data Invio</th>
       <th scope="col">Mittente</th>
       <th scope="col">Destinatario</th>
       <th scope="col">SMS</th>
    <th scope="col"></th>

      </tr>
	<?php 


	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">No records</td></tr>";		}
	$tot_res = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		


	 $colore = "class=\"tab_blue\"";  
		
	
		
			
	    /* SMS*/
		$phonefrom = $riga['from'];
		$phoneto = $riga['to'];		
		
			echo "<tr ><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".mydatetime($riga['data_creazione'])."</td>"; 
			echo "<td>".$phonefrom."</td>"; 
			echo "<td>".$phoneto."</td>";
			echo "<td><strong>".$riga['body']."</strong></td>"; 
		    echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	
 $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); 
?>
