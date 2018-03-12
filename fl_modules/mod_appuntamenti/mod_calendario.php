<?php 
		$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; 

		$potential_rel = (isset($_GET['potential_rel'])) ? "potential_rel=".check($_GET['potential_rel']).'&' : '';	

		$filtro = ($meeting_location_id > 1) ? 'meeting_location = '.$meeting_location_id : '1';
		//$filtro .= ($proprietario_id > 1) ? " AND (proprietario = $proprietario_id OR callcenter = $proprietario_id) " : ' ';
		$query = "SELECT *,`start_meeting` as `start`, COUNT('*') as `tot`  FROM `fl_appuntamenti` WHERE $filtro GROUP BY `start_meeting`,`end_meeting`,`meeting_location` ";
	
		$colors = array(122=>'#380fa8',123=>'#3DA042',124=>'#4c9ed9',125=>'#E3CC23');
		unset($tipologia_appuntamento[0]);
		
		foreach ($colors as $key => $value) {
			echo '<span class="msg" style="background: '.$colors[$key]. '">'.$tipologia_appuntamento[$key].'</span>';
		}
	

		$risultato = mysql_query($query, CONNECT);
		$meetings = '';
		while ($riga = mysql_fetch_array($risultato)) 
		{
			$coloreSelezionato = (isset($colors[$riga['tipologia_appuntamento']])) ? @$colors[$riga['tipologia_appuntamento']] : $colors[122];
			$meetings .= "
			{
				title: '".str_replace("&rsquo;", "\'", str_replace("'", "",$riga['nominativo']))."',
				start: '".str_replace(" ","T",$riga['start'])."',
				end: '".str_replace(" ","T",$riga['end_meeting'])."',
				url: './mod_inserisci.php?potential_rel=".$riga['potential_rel']."&id=".$riga['id']."&b=Appuntamenti',
				allDay: false,
				color: '".@$coloreSelezionato."'
			},";
		} ?>



<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lib/moment.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lang/it.js'></script>

<script>
		
		$(document).ready(function() {

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
									var date = start.format("YYYY-MM-DD HH:mm") ;
									var dateend = end.format("YYYY-MM-DD HH:mm") ;
									window.location.href = '../mod_appuntamenti/mod_inserisci.php?<?php echo $potential_rel; ?>id=1&start_meeting=' + date + '&end_meeting='+ dateend
								}
								
							},
							editable: true,
							defaultDate: '<?php echo date('Y-m-d'); ?>',
							defaultView: 'agendaWeek',	
							editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: [
			
			<?php echo $meetings ; ?> 


			]
		});

					});


</script>


<div class="filtri" id="filtri">
	<form method="get" action="" id="fm_filtri">
		<h2>Filtri</h2>
		<input type="hidden" value="<?php echo $issue_id; ?>" name="issue" />
		<?php if(isset($_GET['arrived'])) { ?> <input type="hidden" value="1" name="arrived" />	<?php } ?>
		<?php if(isset($_GET['today'])) { ?> <input type="hidden" value="1" name="today" />	<?php } ?>
		<?php if(isset($_GET['action'])) { ?> <input type="hidden" value="<?php echo check($_GET['action']);?>" name="action" />	<?php } ?>

		<label> Sede</label> 
		<select name="meeting_location" id="meeting_location">
			<option value="-1">Tutti</option>
			<?php 

		     foreach($meeting_location as $valores => $label){ // Recursione Indici di Categoria
		     	$selected = (@$meeting_location_id == $valores) ? " selected=\"selected\"" : "";
		     	if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
		     }
		     ?>
		 </select> 

		 <?php if($_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8) { ?>


		 <label> Persona</label> 
		 <select name="proprietario" id="proprietario">
		 	<option value="-1">Tutti</option>
		 	<?php 

		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
		     	$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
		     	echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
		     }


		     ?>
		 </select> 


		 <?php } ?>
		 <input type="submit" value="<?php echo SHOW; ?>" class="button" />

		</form>
</div>

<div id="calendar"></div>



