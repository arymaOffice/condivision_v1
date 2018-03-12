<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];


?>
<h1>Archivio Notifiche</h1>

 <div class="filtri">
<form method="get" action="" id="fm_filtri">
  
    
   <select name="proprietario" id="proprietario" class="select2" onChange="form.submit();">
            <option value="-1">Seleziona affiliato</option>
             <option value="-2">Mostra Tutti</option>
              <option value="0">Pubblico</option>
			<?php 
              
		     foreach($destinatario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 1)  echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
     
       
       
  
       creato tra il 
       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" />

       e  <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" /> 

       <input type="submit" value="Mostra" class="button" />
 
       </form>
   
    
    
      </div>
      
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
 echo mysql_error();
		
	?>

  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
        <th style="width: 1%;"></th>
        <th scope="col" class="titolo"><a href="./?sezione=<?php echo $sezione_id; ?>&amp;ordine=0">Titolo</a></th>
         <th scope="col" class="home">Destinatario</th>
          <th scope="col" class="home">Letture</th>
        <th scope="col" class="home">Modifica</th>
        <th scope="col" class="home">Elimina</th>   
        <th scope="col" class="home">Creato il</th>   
      </tr>
	  
	<?php 
	
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"8\">Nessun Contenuto Trovato</td></tr>";		}
	while ($riga = mysql_fetch_array($risultato)) 
	{
		   $read = get_letture(1,$riga['id'],0);

			echo "<tr>"; 	
			echo "<td><span class=\"Gletter\"></span></td>"; 
			echo "<td><a href=\"mod_visualizza.php?external&action=1&amp;id=".$riga['id']."\" title=\"Visualizza\" >".ucfirst($riga['titolo'])."</a></td>";		
			echo "<td><a href=\"./?proprietario=".$riga['destinatario']."\">".@$destinatario[$riga['destinatario']]."</a></td>";
			echo "<td class=\"\">$read</td>"; 
			echo "<td><a href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"> </a></td>";
		    echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\" onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			echo "<td title=\"Aggiornato da: ". @$proprietario[$riga['proprietario']]."\">".mydate($riga['data_invio'])."</td>";
		    echo "</tr>";
			
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>
