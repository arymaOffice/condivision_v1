
<?php $_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>


<div class="filtri" id="filtri">
	<form method="get" action="" id="fm_filtri">
		<h2>Filtri</h2>

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

   <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			

			if((select_type($chiave) == 2 || select_type($chiave) == 23 ||  select_type($chiave) == 19 || select_type($chiave) == 9 || select_type($chiave) == 8 || select_type($chiave) == 12) && $chiave != 'id') {
				
								
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

 <div style="width: 50%; margin: 0; float: left;">
      <label> da</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" />
    </div>
    <div style="width: 50%; margin: 0; float: left;">
      <label> a</label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" />
    </div>
		 <input type="submit" value="<?php echo SHOW; ?>" class="button" />

		</form>

	</div>



	<?php
	
	$promoter = $proprietario;

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);

	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);

	?>




	<table class="dati" summary="Dati" style=" width: 100%;">
		<tr>
			<th scope="col"></th>
			<th scope="col">Ora/Data/Tipo</th>
			<th scope="col">Evento</th>
			<th scope="col">A</th><th scope="col">B</th><th scope="col">S</th><th scope="col">H</th>
			<th scope="col">Ordini di servizio (BEO)</th>
			<th scope="col">Aggiornamento</th>
		</tr>
		<?php 

		while ($riga = mysql_fetch_array($risultato)) 
		{

			$colore = "class=\"tab_earl_gray\""; 
	
			$potential = GRD($tables[106],$riga['lead_id']);
			$ambienti_id = '';
			$ambienti_id = explode(',',$riga['ambienti']);

			$ambienti_txt = '';
			foreach ($ambienti_id as $key => $value) {
				$ambienti_txt .= '<span class="msg orange">'.@$ambienti[$value].'</span>';
			}
		$colors = array('-1'=>'red',9=>'#7B3DA0',5=>'#4c9ed9',6=>'#DEBA0F',7=>'#A1CE11',8=>'#1DC59B',12=>'#333',10=>'#666',11=>'#ccc');
		
			$coloreEvento = @$colors[$riga['tipo_evento']];
			$add_calendar = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.$riga['titolo_ricorrenza'].'&location='.$location_evento[$riga['location_evento']].'&details=Inserito da Condivision&dates='.substr(str_replace('-','',$riga['data_evento']),0,8).'T'.substr(str_replace(':','',$riga['data_evento']),11,8).'/'.substr(str_replace('-','',$riga['data_fine_evento']),0,8).'T'.substr(str_replace(':','',$riga['data_fine_evento']),11,8).'&sf=true&pli=1';

		  $tavoli = GQD('fl_tavoli','id,evento_id',' evento_id = '.$riga['id']);
		  $colorScheda = ($tavoli['id'] > 1) ? $coloreEvento : 'gray';

		  $menuPortate = GQD('fl_menu_portate','id,evento_id',' evento_id = '.$riga['id']);
		  $colorMenu = ($menuPortate['id'] > 1) ? $coloreEvento : 'gray';

		  $schedaServizio = GQD('fl_ricorrenze_matrimonio','id,evento_id,data_aggiornamento',' evento_id = '.$riga['id']);
		  $lastUpdate = (isset($schedaServizio['data_aggiornamento'])) ? @mydatetime($schedaServizio['data_aggiornamento']) : 'Pianificazione non eseguita';
		  $visto = '';

		  $revisioni = GQD('fl_revisioni_hrc rv',"*,DATE_FORMAT(rv.data_creazione,'%d/%m/%Y %H:%i') as data,IF((SELECT count(*) FROM  fl_revisioni_check rc WHERE rv.id = rc.revisione_id AND rv.proprietario = rc.proprietario  ) > 0,true,false) as visto",'evento_id = '.$riga['id']." and rv.proprietario = ".$_SESSION['number']." ORDER BY rv.id DESC LIMIT 1");
		  if($revisioni['id'] >  1){
		  	$visto = ($revisioni['visto'] > 0) ? '<span class="c-green">Visionato</span>' : '<a class="button" href="../mod_eventi/mod_opera.php?revisione_id='.$revisioni['id'].'&evento_id='.$riga['id'].'">Conferma Visione</a>' ;
		  }
		  $ultima_revisione = ($revisioni['id'] > 1) ? 'Rev. '.$revisioni['data'].' '.$visto.'<br> '.strip_tags(converti_txt($revisioni['note'])) : $lastUpdate;

			echo "<tr ><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><h2>".mydatetime($riga['data_evento'])."</h2> <span class=\"msg\" style=\"background: $coloreEvento\">".@$tipo_evento[$riga['tipo_evento']]." ".@$centro_di_ricavo[$riga['centro_di_ricavo']]."</span><span class=\"msg gray\">".$periodo_evento[$riga['periodo_evento']]."</span></td>"; 
			echo "<td><h2>".$riga['titolo_ricorrenza']."</h2>".@$location_evento[$riga['location_evento']]."</td>"; 
			echo "<td><h2>".$riga['numero_adulti']."</h2></td>"; 
			echo "<td><h2>".$riga['numero_bambini']."</h2></td>"; 
			echo "<td><h2>".$riga['numero_sedie']."</h2></td>"; 
			echo "<td><h2>".$riga['numero_sedioloni']."</h2></td>"; 
			echo "<td>";
			if($riga['multievento'] == 1) echo "<a data-fancybox-type=\"iframe\" class=\"fancybox_view \" href=\"../mod_report/mod_prospettobeo.php?data=".substr($riga['data_evento'],0,10)."\" title=\"Prospetto\" style=\"color:   $colorMenu\"><i class=\"fa fa-map\" aria-hidden=\"true\"></i> PROSPETTO</a>";	
			if($schedaServizio['id'] > 1) echo '<a data-fancybox-type="iframe" class="fancybox_view" href="mod_servizio.php?evento_id='.$riga['id'].'" title="Stampa Ordine" style="color: '.$colorMenu.'"><i class="fa fa-file-text" aria-hidden="true"></i> BEO Servizio</a>';			
			if($schedaServizio['id'] > 1 && defined('ordine_cucina')) echo '<a data-fancybox-type="iframe" class="fancybox_view" href="mod_servizio.php?evento_id='.$riga['id'].'" title="Stampa Ordine" style="color: '.$colorMenu.'"><i class="fa fa-file-text" aria-hidden="true"></i> BEO Cucina</a>';			
			if($schedaServizio['id'] > 1 && defined('ordine_pasticceria')) echo '<a data-fancybox-type="iframe" class="fancybox_view" href="mod_servizio.php?evento_id='.$riga['id'].'" title="Stampa Ordine" style="color: '.$colorMenu.'"><i class="fa fa-file-text" aria-hidden="true"></i> BEO Pasticceria</a>';			
			
			echo "<td> $ultima_revisione</td>";
			echo "</tr>";
			
			
		}

		echo "</table>";

		$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); 
		?>
