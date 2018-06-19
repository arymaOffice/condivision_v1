<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
	$basic_filters = array('descrizione','codice_fornitore','codice_ean','formato','categoria_materia','codice_articolo','magazzino_base');

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
	$tabella = " `fl_listino_acquisto` as a LEFT JOIN fl_materieprime as b ON  a.id_materia = b.id";
	//Filtri di base (da strutturare quelli avanzati)

	/*Impostazione automatica da tabella */
	$campi = gcolums('fl_materieprime'); //Ritorna i campi della tabella

	$tipologia_main = gwhere($campi,'WHERE a.id != 1 ','b.');//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
	//$start = paginazione(CONNECT,$tabella,$step,id DESC,$tipologia_main,0);
	$query = "SELECT *,a.id AS aid, b.id AS bid,a.formato as formato FROM $tabella $tipologia_main ORDER BY id_materia DESC;";
	$risultato = mysql_query($query, CONNECT);
	$righe = '';
	$totale = 0;

	if(mysql_affected_rows() == 0) { $righe .= "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['attivo'] == 1) ? 'tab_green' : 'tab_red';
	$valore = $riga['giacenza']*$riga['prezzo_unitario'];
	$totale += $valore;
	$codice_tag = ($riga['codice_articolo'] != '') ? "<span class=\"msg blue\">".$riga['codice_articolo']."</span>" : '';

			 $righe .= "<tr>"; 				
			 $righe .= "<td class=\"$attivo\"></td>";
			 $righe .= "<td>".@$magazzino_base[$riga['magazzino_base']]."</td>";
			 $righe .= "<td>$codice_tag".$riga['descrizione']."<br>".$riga['formato']."<br></td>";	
			 $righe .= "<td>".$fornitore[$riga['fornitore']]."<br>COD: ".$riga['codice_fornitore']." - EAN: ".$riga['codice_ean']."</td>";
			 $righe .= "<td>".@$riga['categoria_materia']."</td>";
			 $righe .= "<td><input class=\"updateField\" value=\"".$riga['giacenza']."\" name=\"giacenza\" data-rel=\"".$riga['aid']."\" data-gtx=\"116\"  /></td>";		
			 $righe .= "<td><span href=\"\" class=\"msg orange\">".$riga['unita_di_misura_formato']."</span> EUR ".numdec($riga['prezzo_unitario'],2)." <br>del  ".mydate($riga['data_aggiornamento'])."</td>";	
		     $righe .= "<td>&euro; ".numdec($valore,2)."</td>";
		     $righe .= "</tr>";
	}

	
echo '<h2 style=" float: right;" class="msg green" >Valore &euro; '.$totale.'</h2>';

?>



<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Magazzino</th>
    <th>Cod. / Descrizione</th>
    <th>Fornitore</th>
    <th>Categoria</th>
    <th>Giacenza</th>
    <th>Ultima quotazione</th>
    <th>Valorizzazione</th>
  </tr>
  <?php echo $righe; ?>

</table>

<?php //$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
