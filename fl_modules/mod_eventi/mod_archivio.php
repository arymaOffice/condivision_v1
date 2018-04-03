
<?php 

$monthSelected = (isset($_GET['mese_evento'])) ? $_GET['mese_evento'] : date('n');

$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>


<div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
<h2>Filtri</h2>

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>


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


	<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/bxslider/jquery.bxslider.min.js"></script>
	<!-- bxSlider CSS file -->
	<link href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/bxslider/jquery.bxslider.min.css" rel="stylesheet" />
	
	<style type="text/css">
		.active-slide{
			font-weight: bold;
			border: none !important;
			background: lightgrey !important;
		}
		.bx-wrapper{
			border : none;
			max-width: 100% !important;
			padding: 10px 50px;

			float: left;
		}
		.bx-default-pager{
			display: none
		}
		.month {
			text-align: center;
			letter-spacing: 3px;
			background: rgb(234, 92, 24);
		}
		.month a { color: white !important; }
		.month:hover { background: orange !important; }
		.bxslider > .selected { background: green; }
		.month_text{
			color: white;
			height: 30px;
			font-size: 18px;
			width: 100%;			
			text-align: center;
			margin: 10px 10px 10px 0px;
			cursor: pointer
		}
		.active-slide{
			border: solid thin blue;
		}

		.bxslider li { 
			float: left;
list-style: outside none none;
position: absolute;
width: 180px;
margin-right: 20px;
	}

	</style>


	<ul class="bxslider" style="text-align:center;">
		
	<?php 


		foreach ($mese as $key => $value) {

		$sel = ($key == $monthSelected) ? 'selected' : '';
		if($key > 0) echo '<li class="month '.$sel.'" id="'.$key.'"  >
			<div class="month_text"><a href="./?action=17&mese_evento='.$key.'&data_da='.date('1/'.$key.'/'.$anno).'&data_a='.date('t/'.$key.'/'.$anno).'">'.$value.'</a></div>
		</li>';
		# code...
	}


	?>
	</ul>	


	<script type="text/javascript">
		$(document).ready(function(){
			var mySlider = $('.bxslider').bxSlider({
				minSlides: 3,
				maxSlides: 12,
				slideWidth: 180,
				slideMargin: 20,
				moveSlides: 1,
				responsive: true,
				touchEnabled: true,
				startSlide : <?php echo $monthSelected-2 ?>,
				
			});
		});
	</script>


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
			<th scope="col">Evento e Ambienti</th>
			<th scope="col">Referente Interno</th>
			<th scope="col">Amministrazione</th>
			<th scope="col">Gestione</th>
		</tr>
		<?php 

		while ($riga = mysql_fetch_array($risultato)) 
		{

			$colore = "class=\"tab_earl_gray\""; 
			if($riga['stato_evento'] == 0)  $colore = "class=\"tab_blue\"";  			
			if($riga['stato_evento'] == 1)  $colore = "class=\"tab_orange\"";  			
			if($riga['stato_evento'] == 2)  $colore = "class=\"tab_earl_gray\"";  			
			if($riga['stato_evento'] == 3)  $colore = "class=\"tab_gray\"";   
			if($riga['stato_evento'] == 4)  $colore = "class=\"tab_red\"";   

			$potential = GRD($tables[106],$riga['lead_id']);


			$ambienti_id = '';
			$ambienti_id = explode(',',$riga['ambienti']);

			$ambienti_txt = '';
			foreach ($ambienti_id as $key => $value) {
				$ambienti_txt .= ', '.@$ambienti[$value].'';
			}
			
			$coloreEvento = @$colors[$riga['tipo_evento']];
			$add_calendar = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text='.$riga['titolo_ricorrenza'].'&location='.$location_evento[$riga['location_evento']].'&details=Inserito da Condivision&dates='.substr(str_replace('-','',$riga['data_evento']),0,8).'T'.substr(str_replace(':','',$riga['data_evento']),11,8).'/'.substr(str_replace('-','',$riga['data_fine_evento']),0,8).'T'.substr(str_replace(':','',$riga['data_fine_evento']),11,8).'&sf=true&pli=1';
			//<a target=\"_blank\" href=\"$add_calendar\" style=\"color: green;\"><i class=\"fa fa-calendar-check-o\"></i></a>


			
		  $schedaWedding = GQD('fl_ricorrenze_matrimonio','id,evento_id',' evento_id = '.$riga['id']);
		  $schedaWeddingId = ($schedaWedding['id'] > 1) ? $schedaWedding['id'] : '1&auto';
		  $colorScheda = ($schedaWedding['id'] > 1) ? $coloreEvento : 'gray';
		  $titolo_ricorrenza = $tipo_evento[$riga['tipo_evento']].' '.str_replace('Matrimonio',' ', $riga['titolo_ricorrenza']);

	

			echo "<tr ><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><h2>".mydate($riga['data_evento'])."</h2>
			<span class=\"msg\" style=\"background: $coloreEvento\">".@$tipo_evento[$riga['tipo_evento']]." ".@$centro_di_ricavo[$riga['centro_di_ricavo']]."</span><span class=\"msg gray\">".$periodo_evento[$riga['periodo_evento']]."</span></td>"; 
			echo "<td><h2>$titolo_ricorrenza</h2>".@$location_evento[$riga['location_evento']]." ".$ambienti_txt."</td>"; 
			echo "<td><strong>".@$proprietario[$riga['proprietario']]."</td>"; 
			echo "<td>";    
			echo "<a style=\"color: $coloreEvento\" href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Amministrativa\" > <i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a>";

			echo "<a style=\"color: $coloreEvento\" href=\"../mod_materieprime/?mod_fabbisogno.php?evento_id=".$riga['id']."\" title=\"Calcola Fabbisogno\" > <i class=\"fa fa-clipboard\" aria-hidden=\"true\"></i></a>";
			echo "</td>";
			echo "<td><a href=\"mod_scheda_servizio.php?evento_id=".$riga['id']."&tipo_evento=".$riga['tipo_evento']."&id=$schedaWeddingId\" title=\"Gestione Operativa\" style=\"color:  $colorScheda\"><i class=\"fa fa-address-card\" aria-hidden=\"true\"></i></a></td>";
			if($riga['stato_evento'] == 4 && $_SESSION['usertype'] == 0) echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			echo "</tr>";
			
			
			
		}

		echo "</table>";

		$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); 
		?>
