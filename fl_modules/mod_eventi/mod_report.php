
<?php 

$monthSelected = (isset($_GET['mese_evento'])) ? $_GET['mese_evento'] : date('n');
$tipologia_main = gwhere($campi,' AND stato_evento != 4','','id','',0,0);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica

	if(isset($_GET['tipo_evento_s'])) { 
	$tipo_evento_var = $_GET['tipo_evento_s'];
	$tipo_evento_ids = implode("','",$tipo_evento_var);
	$tipologia_main .= " AND tipo_evento IN ( '-1','$tipo_evento_ids' )"; }


	
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>


<div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
<h2>Filtri</h2>

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>
<?php if(isset($_GET['anno'])) echo '<input type="hidden" value="'.check($_GET['anno']).'" name="anno" />'; ?>
<?php if(isset($_GET['totaleOspiti'])) echo '<input type="hidden" value="1" name="totaleOspiti" />'; ?>
<br>
  Tipo Evento:
      <?php 
            
		     foreach($tipo_evento as $valores => $label){ // Recursione Indici di Categoria
			$selected = (in_array($valores,$tipo_evento_var)) ? ' checked="checked" ' : '';
			if($valores > 1) echo '<input '.$selected.' type="checkbox" name="tipo_evento_s[]" id="tipo_evento'.$valores.'" value="'.$valores.'"><label for="tipo_evento'.$valores.'">'.ucfirst($label).'</label>'; 
			
			}
		 ?>


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

<input type="submit" value="<?php echo SHOW; ?>" class="button" />

</form>

</div>



	<ul class="periodSelector" style="text-align:center;">
		
	<?php 
$totaleOspiti = (isset($_GET['totaleOspiti'])) ? '&totaleOspiti' : '';
$anno = array(2017,2018,2019,2020,2021,2022,2023,2024);
$annoSelected = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y') ;

		foreach ($anno as $key => $value) {

		$sel = ($value == @$annoSelected) ? 'selected' : '';
		$tot = mk_count('fl_eventi_hrc',' (YEAR(data_evento) = '.$value.') '.$tipologia_main );  

		echo '<li class="'.$sel.'" id="'.$key.'"><a href="./?action=24&anno='.$value.$totaleOspiti.'">'.$value.' ('.$tot.')</a></li>';
	
	}


	?>
	</ul>	

<div><?php
foreach ($colors as $key => $value) {
			$totTipo = mk_count('fl_eventi_hrc',' stato_evento != 4 AND tipo_evento = \''.$key.'\' AND (YEAR(data_evento) = '.$annoSelected.') ');  
			if($key > 0)  echo '<a href="./?'.$_SERVER['QUERY_STRING'].'&tipo_evento='.$key.'" style="color: white;"><span class="msg" style="background: '.$colors[$key]. '">'.$tipo_evento[$key].' ('.$totTipo.')</span></a>';
	}

	?></div>


	<?php
	
unset($mese[0]);
$gsett = array('D','L','M','M','G','V','S');
$nomi= array();
$periodColors = array();
$periodColors[101] = '#FF00FF';
$periodColors[102] = '#0066FF';
$year = $annoSelected;
foreach ($mese as $key => $value) {

$list=array();
$month = $key;

$tot = mk_count('fl_eventi_hrc',' (MONTH(data_evento) ='. $month.' AND YEAR(data_evento) = '.$year.') '.$tipologia_main );  
echo '<div class="box" style="min-height: 700px; float: left; width: 8%; margin: 0 2px 4px 0; padding: 0px;"><h2>'. $value.' <span style="font-size: 12px;">('.$tot.')</span></h2><table style="width: 100%;">';	


$maxDays = cal_days_in_month (CAL_GREGORIAN, $month ,$year );

for($d=1; $d<=31; $d++)
{
    $time=mktime(12, 0, 0, $month, $d, $year);          
    if (date('m', $time)==$month)      
    $dayWeek = date('w', $time);
	$red = ($dayWeek == 0) ? 'c-red' : '';
	$eventiDelGiorno = GQS('fl_eventi_hrc','numero_adulti,numero_bambini,stato_evento,ambienti,id,titolo_ricorrenza,data_evento,tipo_evento,periodo_evento',' data_evento BETWEEN \''.date('Y-m-d', $time).' 00:00:00\' AND \''.date('Y-m-d', $time).' 23:59:59\''.$tipologia_main );
	$eventi = '';
	$totaleOspiti = 0;

	foreach ($eventiDelGiorno as $key => $value) {

		if($value['id'] > 1 ) {
			$totaleOspiti += ($value['numero_adulti']+$value['numero_bambini']);
			
			if(!isset($_GET['totaleOspiti'])) {

			$data_split = explode(' ',$value['data_evento']);
			$times = substr($data_split[1],0,2);
			$ambientia = explode(',',$value['ambienti']);
			$ambiente = '';
			$stato_evento  = ($value['stato_evento'] < 2) ? 'border: red solid 2px;' : '';
			$titolo_ricorrenza = explode('-',$value['titolo_ricorrenza']);
			$titolo_ricorrenza = trim(substr(html_entity_decode(str_replace('Matrimonio ','',$titolo_ricorrenza[0])),0,11));
			$colore = ($value['stato_evento'] > 2) ? $colors['-1'] : $colors[@$value['tipo_evento']];

			if($titolo_ricorrenza == '') $titolo_ricorrenza = 'Senza nome';
			foreach ($ambientia as $a => $ambi) { $ambiente .= $ambienti[$ambi];	}
			if($value['stato_evento'] > 2) $ambiente = 'ANNULLATO';

			$eventi .= '<div><span style="background: '.$periodColors[$value['periodo_evento']].'; margin: 0; width: 10px; height: 10px;">&nbsp;</span>
			<span style="display: inline-block; width: 90%;  color: white; padding: 2px; background: '.$colore.'; '.$stato_evento.'" title="'.$ambiente.'"><a href="./mod_inserisci.php?id='.$value['id'].'" style="color: white;"> '.$titolo_ricorrenza.'</a></span></div>';
			} else { $eventi = '<h2 class="msg gray">'.$totaleOspiti.'</h2>'; }


		}
	}
    if($d <= $maxDays) echo '<tr><td>'.date('j', $time).' </td><td class="'.$red.'">'.$gsett[$dayWeek].'</td><td>'.$eventi.'</td></tr>';
}
//echo "<pre>";
//print_r($list);
//echo "</pre>";

echo '</table></div>';
}




?>
