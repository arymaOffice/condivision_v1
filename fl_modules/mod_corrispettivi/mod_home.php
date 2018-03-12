<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>

<h1>Registro Corrispettivi <a href="./mod_inserisci.php?id=1" class="" style="color: gray"> <i class="fa fa-plus-circle"></i>  </a></h1>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,3000;";
	
	$risultato = mysql_query($query,CONNECT);
	
	
		
	?>
       
<?php if(isset($proprietario_id)) { ?>


<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
    
    Account:
        <select name="operatore" id="operatore" onchange="form.submit();">
               <?php 
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>


    <label> PERIODO TRA</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> E IL </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>



   <form method="get" action="" class="form_box">
   
 <p>
    Punto vendita: 
      <select name="operatore" id="operatore" onchange="form.submit();">
               <?php 
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
     
             <select name="mese" id="mese">
              <option value="-1">Periodo</option>
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
           
    <input type="submit" value="Mostra" class="button noprint" /></p>
</form>



<?php include('mod_report.php'); ?>
  
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
		/*$prev_date = date('Y-m-d', strtotime($date .' '.$day.' day'));
		$prev_m = date('m', strtotime($date .' '.$day.' day'));
		$prev_a = date('Y', strtotime($date .' '.$day.' day'));
		mysql_query('UPDATE fl_corrispettivi SET anno = \''.$prev_a.'\', mese = \''.$prev_m.'\', note = \'\', data_corrispettivo = \''.$prev_date.'\' WHERE id = '.$riga['id'],CONNECT);
		$day--;*/
		
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
		$modificas = ($_SESSION['number'] != 137) ? "<a href=\"./mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\" > <i class=\"fa fa-search\"></i> </a>" : '';
		$eliminas = ($_SESSION['number'] != 137) ? "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>" : '';
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
			$rows .= "<td class=\"tasto noprint\">$modificas</td>";
			$rows .= "<td class=\"tasto noprint\">$eliminas</td>";


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
			  <th class="noprint">Modifica</th>
			  <th class="noprint">Elimina</th>
	  
			</tr>';

			
			echo $header_table.$rows;


?>

</table>
<p>* Sono presenti delle note per questo corrispettivo</p>
<p><a href="<?php echo "./?operatore=$proprietario_id&amp;mese=$mese_sel&amp;anno=$anno_sel&amp;action=14"; ?>" class="button">Produci Riepilogo </a></p>
<br />
<?php //$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
} else { //echo "<div class=\"box_div\"><br /><br /><h3>Seleziona punto vendita, e periodo.</h3><br />"; ?>

<h2>Seleziona un account</h2>
<form method="get" action="" id="dms_account_sel">

<?php 			$selected = ($proprietario_id == 0) ? ' checked="checked"' : '';
?>
<input id="0" onClick="form.submit();" type="radio" name="operatore" value="0" <?php echo $selected; ?> />
		
 <?php
			
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? ' checked="checked"' : '';
			if($valores > 1){ echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="operatore" value="'.base64_encode($valores).'" '.$selected.' />
			<label for="'.$valores.'"><i class="fa fa-user"></i><br>'.$label.'</label>'; }
			}
		 ?>
      
     
</form>


<!--<form method="get" action="" id="main_select" style="text-align: right; margin: 10% 40% 10% auto; ">

 <p>
    Punto vendita:
      <select name="operatore" id="operatore">
               <?php
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select></p>
      <p> Periodo:
             <select name="mese" id="mese">
                 <?php 
			    foreach($mese as $valores => $label){ // Recursione Indici di Categoria
			$selected = (date('m') == $valores) ? " selected=\"selected\"" : "";
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
       </select></p>
 <p>Condizioni Meteo: <select name="condizioni_meteo" id="condizioni_meteo">
              <option value="-1" selected="selected">Mostra Tutti</option> <?php 
			 foreach($condizioni_meteo as $valores => $label){ // Recursione Indici di Categoria
			$selected = "";
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n";
			}
		 ?>
       </select></p>  
    <input type="submit" value="Mostra" class="button noprint" /></p>
</form></div>-->
 <?php }


 ?>




