<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

  ?>

<div class="filtri"> 
<form method="get" action="" id="fm_filtri">
    <span style="position: relative;">
  
<select name="operatore" id="operatore">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($operatore as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($operatore == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>  

      Stato: <select name="status_assistenza" id="status_assistenza">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($status_assistenza as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_assistenza_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
    ricevuto tra
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    e
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="button" />      
      
       </form>
</div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query,CONNECT);
		
	?>
       

  
<table class="dati" summary="Dati">
      <tr>
        <th style="width: 1%;"></th>
  <th>Oggetto | <a href="./?ordine=1">Operatore</a></th>
        <th><a href="./?ordine=5">Status</a></th>
        <th><a href="./?ordine=0">Apertura</a></th>
        <th>Ultima attivit&agrave;</th>
       
   
 
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"4\">Nessun Record Inserito</td></tr>";		}
	
	$entrate = 0;
	$uscite = 0;
	$saldo = 0;
	$saldo_parziale = 0;
	

	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			
		
 			$manute = "SELECT * FROM `$tabella` WHERE jorel = ".$riga['id']." AND operatore != ".$_SESSION['number']." AND letto = 0;";
			mysql_query($manute,CONNECT);
			$new_mail = (mysql_affected_rows() > 0) ? "not_read" : "read";

 			echo "<tr>";
			echo "<td><span class=\"Gletter\"></span></td>";

			echo "<td><a href=\"mod_scheda.php?action=8&amp;proprietario=".$riga['proprietario']."&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".$riga['oggetto']."</a><br />".$proprietario[$riga['proprietario']]."</td>";
		    echo "<td>".$status_assistenza[$riga['status_assistenza']]."</td>";
			echo "<td  title=\"Creato da: ". @$proprietario[$riga['proprietario']]."\">".mydate($riga['data_creazione'])."</td>";
			echo "<td  class=\"$new_mail\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\"><a href=\"mod_scheda.php?action=8&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"".$riga['descrizione']."\">".mydatetime($riga['data_aggiornamento'])."</a></td>";
	
			
			
			echo "<td><a href=\"mod_scheda.php?action=1&amp;jorel=$jorel&amp;id=".$riga['id']."\" title=\"Modifica\"><i class=\"fa fa-search\"></i> </a></td>";
			echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>";
		    
		
		    echo "</tr>";
	}
	
	

	

?>
</table>
