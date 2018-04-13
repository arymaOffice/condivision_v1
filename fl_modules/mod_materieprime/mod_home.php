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
				foreach($$chiave as $val => $label) { 
					$selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; 
					$val = (select_type($chiave) == 12) ? trim($label) : $val;
					echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; 

				}
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
?>

<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Id</th>
    <th>Descrizione</th>
    <th>UM</th>
    <th>Categoria</th>
    <th>Magazzino Base</th>
    <th>Formati</th>
    <th>Fornito da</th>
    <th>Aggironamento</th>
    <th></th>
    <th>Utilizzo</th>
  </tr>
  <?php 
	



	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['attivo'] == 1) ? 'tab_green' : 'tab_red';
	$formati = GQS($tables[116],'`id`,`attivo`,`id_materia`,`fornitore`,`codice_fornitore`,`formato`,`unita_di_misura_formato`,`valore_di_conversione`,`valuta`,`iva`,`prezzo_unitario`,`giacenza`,`giacenza_minima`,`data_validita`',' id_materia = '.$riga['id'].' AND attivo = 1 ORDER BY data_validita DESC,data_creazione DESC');
	$utilizzo = mk_count('fl_ricettario_diba','materiaprima_id = '.$riga['id'],'GROUP BY ricetta_id');
	$countFormati = count($formati);
	$listaFormati = '';
	
	if($countFormati > 0) { foreach ($formati as $formato) {
		$listaFormati .= '<span title="Formato: '.$formato['formato'].' - Approvvigionamento: '.$formato['valore_di_conversione'].' '.$riga['unita_di_misura'].'"><span class="msg blue">'.$formato['unita_di_misura_formato'].'</span><a data-fancybox-type="iframe" class="fancybox_view" href="mod_formato.php?id='.$formato['id'].'&formatoTab&id_materia='.$formato['id_materia'].'">'.$fornitore[$formato['fornitore']].'</a></span><br>';
	}} else { 
		if($riga['unita_di_misura'] == 'KP') $riga['unita_di_misura'] = 'KG';
		
		$listaFormati = "INSERT INTO ".$tables[116]."  ( `id` , `id_materia` , `attivo` , `fornitore` , `codice_fornitore` , `formato` , `unita_di_misura_formato` , `valore_di_conversione` , `valuta` , `prezzo_unitario` , `iva` , `codice_ean` , `giacenza` , `giacenza_minima` , `data_validita` , `data_scadenza` , `data_creazione` , `data_aggiornamento` , `operatore` )
													  VALUES (NULL, '".$riga['id']."', '1', '".$riga['anagrafica_id']."', '".$riga['codice_articolo']."', '".$riga['formato']."', '".$riga['unita_di_misura']."', '".$riga['valore_di_conversione']."', 'EUR', '".$riga['ultimo_prezzo']."', '10', '', 0, 0, NOW(), '', NOW(), NOW(), 1);"; 
		$listaFormati = (mysql_query($listaFormati,CONNECT)) ? '<span class="c-green">Formato creato!</span>' : '<span class="c-red">Formato da creare!</span>';
	}		
 
			echo "<tr>"; 				
			echo "<td class=\"$attivo\" id=\"".$riga['id']."\"></td>";
			echo "<td>".$riga['id']."</td>";
			echo "<td>".$riga['descrizione']." ".$riga['codice_articolo']."<br>".$riga['formato']."</td>";	
			echo "<td><a href=\"\" class=\"msg blue\">".$riga['unita_di_misura']."<a/></td>";
			echo "<td>".@$riga['categoria_materia']."</td>";
			echo "<td>".@$magazzino_base[$riga['magazzino_base']]."</td>";
			echo "<td><h2>".$countFormati."</h2></td>";
			echo "<td>".$listaFormati."</td>";
			echo "<td>".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a></td>"; 
			if($utilizzo < 1) echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			if($utilizzo > 0) echo "<td><h2 title=\"$utilizzo ricette usano questo elemento\">$utilizzo</h2></td>"; 
		    echo "</tr>";
	
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
