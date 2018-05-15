<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>
<!--Assigned to: 
     <select name="operatore" id="operatore" onChange="form.submit();">
      <option value="-1">All</option>
      <?php 
              
		     foreach($operatore as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($operatore_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 
-->
    Periodo da 
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    a  
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Show" class="button" />
      
       </form>
       </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	
	if(mysql_affected_rows() == 0) { echo "<h1>Niente da svolgere, prenditi un <i class=\"fa fa-coffee\"></i>!</h1>"; } else {
	
	echo '<table class="dati" summary="Dati" style=" width: 100%;">
      <tr><th></th>
	  <th><a href="./?ordine=2">Oggetto</a></th>
	  <th>Scadenza</th>  
      <th>Visualizza/Modifica</th>
      <th>Stato</th>
      
     </tr>';	
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$giorni = giorni($riga['data_conclusione']);
			$giorni = ($giorni > 0) ? $giorni.' giorni' : 'SCADUTA';
			
			if($giorni == 0) { $giorni = "Oggi"; }
			if($giorni == 1) { $giorni = "Domani"; }
			if($riga['data_conclusione'] == '0000-00-00') { $giorni = "Appena possibile"; }

			if($riga['fatto'] == 0) { $colore = "class=\"tab_orange\"";  }
			if($giorni < 0 ) { $colore = "class=\"tab_red\"";  }
		    if($riga['fatto'] == 1) { $colore = "class=\"tab_green\"";  }
			
			$importante = ($riga['importante'] == 0) ? '' : '<span class="msg blue">IMPORTANTE</span>';
			$urgente = ($riga['urgente'] == 0) ? '' : '<span class="msg red">URGENTE</span>';
			

			$assegnata_a = ($riga['operatore'] == $_SESSION['number']) ? ' a te' : ' a '.ucfirst($operatore[$riga['operatore']]);
			$assegnata_da = ($riga['proprietario'] == $_SESSION['number']) ? 'Assegnato da te ' : 'Assegnato da '.ucfirst($operatore[$riga['proprietario']]).'';
			echo "<td $colore></td>"; 			
			echo "<td><h2><a href=\"mod_visualizza.php?external&action=1&amp;id=".$riga['id']."\" title=\"Vedi\" > ".ucfirst($riga['oggetto'])."</h2></a>".$assegnata_da." ".$assegnata_a." il ".mydate($riga['data_avvio'])." $importante $urgente</td>";
			echo "<td><h3>$giorni</h3> ".mydate($riga['data_conclusione'])."</td>";  
		
			if($_SESSION['number'] ==  $riga['proprietario'] ) {
				echo "<td><a href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"> </a></td>";
			} else {
				echo "<td><a  href=\"mod_visualizza.php?external&action=1&amp;id=".$riga['id']."\" title=\"Vedi\" > <i class=\"fa fa-search\"> </a></td>";

			}
			
			echo "<td style=\"text-align: center;\">";
			if($riga['fatto'] == 0) { echo " <a href=\"mod_opera.php?pubblica=1&amp;id=". $riga['id']."\" title=\"Segna come Fatto\"><i class=\"fa fa-thumbs-up\"></i></a>";} else { echo " <a href=\"mod_opera.php?pubblica=0&amp;id=". $riga['id']."\" title=\"Concluso\" class=\"c-green\"> </a><i class=\"fa fa-check-square c-green\"></i> ";}
			echo "</td>";
		   	echo "<td class=\"hideMobile\">";
			if($_SESSION['number'] ==  $riga['proprietario'] ) echo "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			echo "</td>";
		
		    echo "</tr>";
			
	}

	echo "</table>";
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); 
}
?>	