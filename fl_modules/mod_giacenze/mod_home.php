<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>




<div class="filtri" id="filtri"> 
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
				echo '<div class="filter_box">';
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Non impostato</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
				echo '</div>';
			} else if( $chiave != 'id') { $valtxt = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; 
			echo '<div class="filter_box">';
			echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$valtxt.'" />'; echo '</div>';}

			
			
			} 
		
	}
	 ?>    

    
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

</div>
    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	$righe = '';
	$totale = 0;

	if(mysql_affected_rows() == 0) { $righe .= "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['attivo'] == 0) ? 'tab_green' : 'tab_red';
	//$quotazione = GQD('fl_listino_acquisto','valuta, prezzo_unitario, data_validita',' id_materia = '.$riga['id'].' ORDER BY data_validita DESC,data_creazione DESC LIMIT 1');
	$valore = $riga['quantita']*$riga['prezzo_medio'];
	$totale += $valore;

			 $righe .= "<tr>"; 				
			 $righe .= "<td class=\"$attivo\"></td>";
			 $righe .= "<td>Principale</td>";
			 $righe .= "<td>".$riga['id']."</td>";
			 $righe .= "<td>".$riga['descrizione']."<br>".$riga['formato']."<br><a href=\"\" class=\"msg blue\">".$riga['codice_articolo']."<a/></td>";	
			 $righe .= "<td>".@$categoria_materia[$riga['categoria_materia']]."</td>";
			 $righe .= "<td><input class=\"updateField\" value=\"".$riga['quantita']."\" name=\"quantita\" data-rel=\"".$riga['id']."\"  /></td>";		
			 $righe .= "<td><span href=\"\" class=\"msg orange\">".$riga['unita_di_misura']."</span> EUR ".numdec($riga['prezzo_medio'],2)." <br>del  ".mydate($riga['data_aggiornamento'])."</td>";	
		     $righe .= "<td>&euro; ".numdec($valore,2)."</td>";
		     $righe .= "</tr>";
	}

	
echo '<h2 style=" float: right;" class="msg green" >Valore &euro; '.$totale.'</h2>';

?>



<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Magazzino</th>
    <th>Cod.</th>
    <th>Descrizione</th>
    <th>Categoria</th>
    <th>Giacenza</th>
    <th>Ultima quotazione</th>
    <th>Valorizzazione</th>
  </tr>
  <?php echo $righe; ?>

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
