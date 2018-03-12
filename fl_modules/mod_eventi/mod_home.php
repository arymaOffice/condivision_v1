<?php 


	$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; 

     	
	$tipologia_main = gwhere($campi,'WHERE id != 1 AND stato_evento != 4','','id','',0,0);//Imposta i filtri della query prendendo i dati GET e se sono tra i filtri li applica
	$query = "SELECT * FROM `".$tabella."` ".$tipologia_main;
	
	$risultato = mysql_query($query, CONNECT);
	$meetings = '';

	unset($tipo_evento[0]);
		
	foreach ($colors as $key => $value) {
			if($key > 0) echo '<a href="./?'.$_SERVER['QUERY_STRING'].'&tipo_evento='.$key.'" style="color: white;"><span class="msg" style="background: '.$colors[$key]. '">'.$tipo_evento[$key].'</span></a>';
	}
	
	$x = 0;

	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$titolo = $riga['titolo_ricorrenza'];
	$id = $riga['id'];
	$data_split = explode(' ',$riga['data_evento']);
	$data_end_split = explode(' ',$riga['data_fine_evento']);
	$data = $data_split[0];
	$day = $data_split[1];
	$data_end = $data_end_split[0];
	$day_end = $data_end_split[1];
	$start = $data.'T'.$day;
	$end = $data_end.'T'.$day_end;
	$virgola = ($x>0) ? ',' : '';
	$colore = ($riga['stato_evento'] > 2) ? $colors['-1'] : $colors[@$riga['tipo_evento']];

	$meetings .= "	$virgola
					{
					title: '".str_replace("&rsquo;", "\'", str_replace("'", "", $titolo))."',
					start: '".$start."',
					end: '".$end."',
					url: 'mod_inserisci.php?id=$id',
					allDay: false,
					color: '".@$colore."'
					}";


	$x++;

	} ?>



<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lib/moment.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lang/it.js'></script>


<script>
	
	$(document).ready(function() {

		var searchParams = new URLSearchParams(window.location.search);
		var bool = false ;
		if (searchParams.get('j') == true) {
			var decode =  atob(searchParams.get('j'));
			var potential_rel = decode ;
			var bool = true ;
		}


		$('#calendar').fullCalendar({
			lang: 'it',
			header: {
				left: 'prevYear,prev,today,next,nextYear',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			selectable: true,
			select: function(start, end, allDay) {

				if (start) {
					var potential = (bool !== false ) ? '&lead_id='+potential_rel : '' ;
					var date = start.format("YYYY-MM-DD 13:00") ;
					<?php if( $lead_id > 1) { 
						echo "window.location.href = 'mod_inserisci.php?id=1&data_evento=' + date + '&lead_id=' + ".$lead_id; 
					} else { echo " alert('Per inserire un evento devi selezionare un contatto.'); "; } ?>
				}

			},
			editable: true,
			defaultDate: "<?php echo date('Y-m-d'); ?>",

			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: [ <?php echo $meetings; ?>]
		});

	});


</script>



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


		 <input type="submit" value="<?php echo SHOW; ?>" class="button" />

</form>
     
</div>
      

  



  

<div id="calendar"></div>



