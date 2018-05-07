<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1>Registro Commissioni</h1>   

 
<div class="filtri" id="filtri"> 

	<h2>Filtri</h2>

	
<form method="get" action="" id="fm_filtri">
  Status:
   <select name="status_fatturazione" id="status_fatturazione">
            <option value="-1">Tutto</option>
			<?php 
              
		     foreach($status_fatturazione as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_fatturazione_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
          Tipo:
    
        <select name="tipo_operazione" id="tipo_operazione">
            <option value="-1">Tutto</option>
			<?php 
              
		     foreach($tipo_operazione as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($tipo_operazione_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
       Operatore:
      <select name="operatore" id="operatore"  class="select2" >
            <option value="-1">Tutti</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($userid == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>




       Prodotto: <select name="rif_operazione" id="rif_operazione">
            <option value="-1">Mostra Tutti</option>
			<?php 
              
		     foreach($rif_operazione as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($rif_operazione_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>


        
       <input type="submit" value="Mostra" class="button" />

       </form>
     
 
      </div>
      

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
       <tr>
       <th></th>
       <th>Utente</th>
       <th>Data</th>
       <th>Tipo Operazione</th>
       <th>Prodotto</th>
       <th>Importo</th>
       <th>Commissione</th>
       <th>IVA</th>
       <th class="noprint">Modifica</th>
       <th>Creazione</th>   
      </tr>
	<?php 
	
	$i = 1;
	$tot_comm =  0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$incomplete = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
			
			
			if($riga['status_fatturazione'] == 0){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "style=\" background: #FF3A17; color: #FFF;\"";
			} else if($riga['status_fatturazione'] == 1){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "style=\" background: #009900; color: #FFF;\"";
			} else {
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			}
	
		
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
     		echo "<td>".$proprietario[$riga['proprietario']]."</td>";
						echo "<td>".mydate($riga['data_operazione'])."</td>"; 
			echo "<td>".$tipo_operazione[$riga['tipo_operazione']]."</td>";	

			echo "<td>".$riga['prodotto_desc']." [".$riga['rif_operazione']."]</td>";	
			echo "<td> &euro; ".numdec($riga['imponibile'],2)."</td>"; 
			echo "<td> &euro; ".numdec($riga['commissione'],3)."</td>"; 
			echo "<td> ".numdec($riga['aliquota_iva'],2)." %</td>"; 
	
			
			echo "<td class=\"noprint\">$modifica_id</td>";
			
			
			echo "<td style=\"\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">C ".mydatetime($riga['data_creazione'])."</td>";
				
		    echo "</tr>";
			$tot_comm += $riga['commissione'];
			
			
	}
	
	echo "<tr><td colspan=\"6\"></td>"; 
	echo "<td colspan=\"1\"> &euro; ".numdec($tot_comm,3)."</td>"; 
	echo "<td colspan=\"2\"></td></tr>"; 


	echo "</table>";
	

?>	


<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); if($incomplete > 0) echo  "<div class=\"paginazione clear\"><a href=\"?status_commissione=1&incomplete\" title=\"Mostra Incomplete\">Incomplete: $incomplete</a></div>"; ?>
