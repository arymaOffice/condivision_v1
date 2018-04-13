<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>

<h1>Corrispettivi <a href="./mod_inserisci.php?id=1" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a>     </h1>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query,CONNECT);
	
	
		
	?>
       
<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
    
 

    <label> PERIODO TRA</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> E IL </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>



<?php include('mod_report.php'); ?>


   <form method="get" action="" id="sezione_select">
    <p>Periodo : 


                  <select name="mese" id="mese">
               <?php 
			    foreach($mese as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($mese_sel == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
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
               
    <input type="submit" value="Mostra" class="button" /></p>
</form>

<table class="dati" summary="Dati">

	<?php

	$i = 1;


	$tot_scontrini = 0;
	$tot_euro = 0;
	$tot_buoni_battuto = 0;
	$tot_buoni_facciale = 0;
	$tot_pos = 0;
	$tot_del = 0;
	$tot_euro = 0;
	$tot_corrispettivi = 0;
	$tot_versato = 0;
	$tot_ore_lavorate = 0;
	$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
	$day = 0;
	$rows = '';
	$header_table = '';
	$costo_orario = 14.50;
	
	while ($riga = mysql_fetch_array($risultato))
	{



		$rows .= "<tr class=\"desktop\">";

		$tot_parziale = ($riga['euro']-$riga['buoni_pasto_battuto'])-$riga['pos']-$riga['delivery'];

		
		$tot_scontrini += $riga['totale_scontrini'];
		$tot_euro += $riga['euro'];
		$tot_buoni_battuto += $riga['buoni_pasto_battuto'];
		$tot_buoni_facciale += $riga['buoni_pasto_facciale'];
		$tot_pos += $riga['pos'];
		$tot_del += $riga['delivery'];
		$tot_corrispettivi += $tot_parziale;
		$tot_versato += $riga['totale_versato'];
		$tot_ore_lavorate += $riga['ore_lavorate'];
		$versato = ($riga['data_versamento'] != '0000-00-00') ? @mydate($riga['data_versamento']) : '';
		$giorni = giorni($riga['data_corrispettivo']);
		$modifica = "mod_inserisci.php?id=".$riga['id'];
		if($giorni > 2) $modifica .= '&xmod';
		if($giorni > 2 && $riga['totale_versato'] > 0) $modifica .= '&vrs';
		$modifica = "<a href=\"$modifica\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a>";
	
		$nota = trim(strip_tags(str_replace('&lt;html /&gt;','',$riga['note'])));
		if($nota != ""){ $note = "*"; } else { 	$note = ""; }
		$netto = $riga['euro']/1.10;
		$costo_lavoro = @numdec((@(@$riga['ore_lavorate']*$costo_orario)*100)/$netto,2);
		$produttivita = @numdec(@$netto/@$riga['ore_lavorate'],2);

			$rows .= "<td><p><a href=\"./mod_inserisci.php?id=".$riga['id']."\">".mydate($riga['data_corrispettivo'])."</a></p></td>";
			
			$rows .= "<td style=\"text-align: center; background:white;\"><img src=\"../../fl_set/lay/meteo/".$condizioni_meteo[$riga['condizioni_meteo']].".jpg\" alt=\"".$condizioni_meteo[$riga['condizioni_meteo']]."\" width=\"30\" heigth=\"30\" /></td>";
			$rows .= "<td>&euro; ".numdec($riga['euro'],2)."</td>";
			$rows .= "<td>&euro; ".numdec($netto,2)."</td>";
			$rows .= "<td>".$riga['totale_scontrini']." $note</td>";
			$rows .= "<td>&euro; ".@numdec(@(@$riga['euro']/$riga['totale_scontrini']),2)."</td>";
			$rows .= "<td >&euro; ".numdec($riga['buoni_pasto_battuto'],2)."</td>";
			$rows .= "<td>&euro;".numdec($riga['buoni_pasto_facciale'],2)."</td>";
			$rows .= "<td>&euro; ".numdec($riga['pos'],2)."</td>";
			//$rows .= "<td style=\" background: #FFFCC5\">".$riga['ore_lavorate']."</td>";
			//$rows .= "<td style=\" background: #FFFCC5\">".$costo_lavoro."%</td>";
			//$rows .= "<td style=\" background: #FFFCC5\">&euro; ".$produttivita."</td>";
			$rows .= "<td>&euro; ".numdec($tot_parziale,2)."</td>";
			$rows .= "<td style=\"background: #E8FFE8;\">&euro; ".numdec($riga['totale_versato'],2)."</td>";
			$rows .= "<td>$versato</td>";
			$rows .= "<td class=\"tasto noprint\">".$modifica."</td>";



		    $rows .= "</tr>";


		    $rows .= "<tr class=\"mobile\">";
			$rows .= "<td><h2><a href=\"./mod_inserisci.php?id=".$riga['id']."\">".mydate($riga['data_corrispettivo'])."</a></h2></td>";
			$rows .= "<td>Totale: &euro; ".numdec($riga['euro'],2).' SCN: '.$riga['totale_scontrini']."</td>";
			$rows .= "<td style=\"background: #E8FFE8;\">Vers. &euro; ".numdec($riga['totale_versato'],2)."</td>";
			$rows .= "</tr>";

	}
	

			$tot_netto = $tot_euro/1.10;
		$tot_costo_lavoro = @numdec(@(@(@$tot_ore_lavorate*$costo_orario)*100)/$tot_netto,2);
		$tot_produttivita = @numdec(@$tot_netto/$tot_ore_lavorate,2);



			$header_table .= "<tr class=\"mobile\">"; 
			$header_table .= "<td>TOT: &euro; ".$tot_euro."</td>";
			$header_table .= "<td>NETTO: &euro; ".numdec($tot_netto,2)."</td>";
			$header_table .= "<td>Tot SCN: $tot_scontrini</td>";
			$header_table .= "<td>SC Medio: &euro; ".@numdec(@$tot_euro/$tot_scontrini,2)."</td>";
			$header_table .= "<td style=\"background: #E8FFE8;\">Tot Vers. &euro; $tot_versato</td>";
			$header_table .= "<td style=\"color: red;\">Diff. &euro; ".numdec(($tot_corrispettivi-$tot_versato),2)."</td>";
			$header_table .= "</tr>";
			$header_table .= "<tr style=\"height: 30px;\"><td colspan=\"17\"></td></tr>";


		$header_table .= "<tr class=\"desktop\">"; 



			$header_table .= "<th>Riepilogo</th>";
			$header_table .= "<th></th>";
			$header_table .= "<th>&euro; ".$tot_euro."</th>";
			$header_table .= "<th>&euro; ".numdec($tot_netto,2)."</th>";
			$header_table .= "<th>$tot_scontrini</th>";
			$header_table .= "<th>&euro; ".@numdec(@$tot_euro/$tot_scontrini,2)."</th>";
			$header_table .= "<th>&euro; ".numdec($tot_buoni_battuto,2)."</th>";
			$header_table .= "<th> &euro; ".numdec($tot_buoni_facciale,2)."</th>";
			$header_table .= "<th>&euro; ".numdec($tot_pos,2)."</th>";
			
			/*$header_table .= "<th style=\" background: #FFFCC5\">".$tot_ore_lavorate."</th>";
			$header_table .= "<th style=\" background: #FFFCC5\">".$tot_costo_lavoro."%</th>";
			$header_table .= "<th style=\" background: #FFFCC5\">&euro; ".$tot_produttivita."</th>";*/


			$header_table .= "<th>&euro; ".numdec($tot_corrispettivi,2)."</th>";
			$header_table .= "<th style=\"background: #E8FFE8;\">&euro; $tot_versato</th>";
			$header_table .= "<th>Diff.</th>";
			$header_table .= "<th style=\"color: red;\">&euro; ".numdec(($tot_corrispettivi-$tot_versato),2)."</th>";
			$header_table .= "<th></th>";

		    $header_table .= "</tr>";
			$header_table .= "<tr style=\"height: 30px;\"><td colspan=\"17\"></td></tr>";
		    $header_table .= ' <tr>
			  <th>Data</th>
			  <th>Meteo</th>
			  <th>Incasso Lordo</th>
			  <th>Incasso Netto</th>
			  <th>N.Sc.</th>
			  <th>SC Medio</th>
	  
			  <th>BP Battuto</th>
			  <th>BP Facciale</th>
			  <th>Pos</th>
			 <!-- <th>Ore lav</th>
			  <th>Costo lavoro</th>
			  <th>Produttivit&agrave;</th>-->
			  <th>Contante</th>
			  <th>Versato</th>
			  <th>Vers. il</th>
			  <th class="noprint"></th>
	  
			</tr>';

			
			echo $header_table.$rows;


?>

</table>

<p>* Sono presenti delle note per questo corrispettivo</p>
<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
