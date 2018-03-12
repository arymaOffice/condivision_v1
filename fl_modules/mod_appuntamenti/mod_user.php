<?php 
require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 
if(isset($_GET['j'])) { $potential_id =  base64_decode(check($_GET['j'])); $potential = GRD($tables[106], $potential_id);  }
if(isset($_GET['potential_rel'])) { $potential_id =  check($_GET['potential_rel']); $potential = GRD($tables[106], $potential_id);  }
unset($chat);
unset($text_editor);

include("../../fl_inc/headers.php");
if(!isset($_GET['history'])) { include("../../fl_inc/testata_mobile.php"); }




?>

<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lib/moment.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lang/it.js'></script>

<style>
	.tap_label { width: 100%; padding: 10px;  }
</style>


<?php if(!isset($_GET['history'])) { ?>

<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >
<div id="content_scheda">



<div class="info_dati">
<?php 
if(isset($_GET['j'])) {
echo '<h1>Fissa appuntamento per '.$potential['nome'].' '.$potential['cognome'].'</h1>';
echo '<h2>Professione: '.@$potential['industry'].'</h2>';
echo '<p>Tel: '.@$potential['telefono'].' mail: <a href="mailto:'.@$potential['email'].'" >'.@$potential['email'].'</a></h2>';
}
?>
</div>

  


<?php if(($meeting_location_id < 1 || isset($_GET['intro']))) {  ?>
<h2>Dove desidera fissare l'appuntamento?</h2>
<form method="get" action="" id="meeting_location_set">

	
 <?php
			
			 foreach($meeting_location as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($meeting_location_id == $valores) ? ' checked="checked"' : '';
			if($valores > 1){ echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="meeting_location" value="'.$valores.'" '.$selected.' />
			<label style="width: 100%; padding: 20px; font-size: larger;" for="'.$valores.'"><i class="fa fa-user"></i> '.$label.'</label>'; }
			}
		 ?>
      
<input type="hidden" value="<?php echo check($_GET['j']);?>" name="j" />	
   
</form>
<?php } else { ?>


<h1 style="text-align: center;"><i class="fa fa-calendar-check-o"></i> Nuovo Appuntamento <?php if($meeting_location_id > 0) echo ' in '.$meeting_location[$meeting_location_id ]; ?> [<a href="mod_user.php?intro&j=<?php echo check($_GET['j']);?>">Cambia sede</a>]</h1>  

 <div class="" style=" text-align: center;">     
     
       
    
    
    <form id="add_meeting" class="ajaxForm" action="./mod_opera.php" method="get" style=" display: inline-block; ">

         Con chi? <select name="proprietario" class="select2" id="proprietario"  <?php if($_SESSION['usertype'] != 0 && $_SESSION['usertype'] != 3 && $_SESSION['usertype'] != 5) {  echo 'disabled'; } ?>>
     <?php 
              
			foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$_SESSION['proprietario_id'] == $valores) ? " selected=\"selected\"" : "";
			if($valores > 1)  echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}

		 ?>


    <input type="hidden" name="profile_rel" value="<?php echo $potential['id']; ?>" />
    <input type="hidden" name="set_meeting" value="1" />
    <input type="hidden" name="meeting_location" value="<?php echo $meeting_location_id ; ?>" />

    <input type="text" name="start_meeting" value="<?php  echo $domanistessaora;  ?>"  class="datetimepicker" size="20" />   
  
     Sms  
    
    <!-- <select name="sms" id="sms">
     <option value="0">Non inviare</option>
     <option value="it">Italiano</option>
     </select>
     -->
     <input type="hidden" name="sms" value="0">

     
     <textarea cols="3"  style="width:  100%;" type="text" name="note" value="" placeholder="Note appuntamento"></textarea>
     <input type="submit" id="addmet" value="Crea Appuntamento" class="button"  />
  
    </form>
 
      </div>

      <br> 
<div id="results" class=""></div>

<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lib/moment.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/fullcalendar.min.js'></script>
<script src='<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fullcalendar/lang/it.js'></script>

<?php    
    
	
	$tipologia_main = "WHERE id != 1 ";
	if($_SESSION['usertype'] != 0 && $_SESSION['usertype'] != 3) $tipologia_main = "WHERE id != 1 AND marchio = ".$_SESSION['marchio']." AND (proprietario = ".$_SESSION['number']." OR proprietario < 2) ";
	if(isset($proprietario_id) && @$proprietario_id > 0) {  $tipologia_main .= " AND proprietario = $proprietario_id ";	 }
	if(isset($meeting_location_id) && @$meeting_location_id > -1) {  $tipologia_main .= " AND meeting_location = $meeting_location_id ";	 } 
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine";
	
	$risultato = mysql_query($query, CONNECT);
$meetings = '';
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$potential = GRD($tables[106],$riga['potential_rel']);
	$meetings .= "
					{
					title: '".$potential['nome']." ".$potential['cognome']."',
					start: '".str_replace(" ","T",$riga['start_meeting'])."',
					end: '".str_replace(" ","T",$riga['end_meeting'])."'
					},";
} ?>
<?php    
    $query = "SELECT * FROM `fl_calendario` WHERE proprietario = $proprietario_id";
	
	$risultato = mysql_query($query, CONNECT);
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$meetings .= "
					{
					title: '".$riga['descrizione']."',
					start: '".str_replace(' ','T',$riga['start_date'])."',
					url: 'mod_inserisci_calendario.php?id=".$riga['id']."',
					end: '".str_replace(' ','T',$riga['end_date'])."',";
					if(@$riga['all_day'] == 1) $meetings .= "allDay: true,";
					$meetings .= "color: '#DA3235'
					
					},";
} ?>
<br class="clear" />


		<script>
	$(document).ready(function() {

		$('#calendar').fullCalendar({
			 lang: 'it',
			 header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '<?php echo date('Y-m-d'); ?>',
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
			
				<?php echo $meetings ; ?> 
					{
					title: 'Chiusi',
					start: '2016-05-01'
				}
				/*{
					title: 'Long Event',
					start: '2015-02-07',
					end: '2015-02-10'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2015-02-09T16:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2015-02-16T16:00:00'
				},
				{
					title: 'Conference',
					start: '2015-02-11',
					end: '2015-02-13'
				},
				{
					title: 'Meeting',
					start: '2015-02-12T10:30:00',
					end: '2015-02-12T12:30:00'
				},
				{
					title: 'Lunch',
					start: '2015-02-12T12:00:00'
				},
				{
					title: 'Meeting',
					start: '2015-02-12T14:30:00'
				},
				{
					title: 'Happy Hour',
					start: '2015-02-12T17:30:00'
				},
				{
					title: 'Dinner',
					start: '2015-02-12T20:00:00'
				},
				{
					title: 'Birthday Party',
					start: '2015-02-13T07:00:00'
				},
				{
					title: 'Click for Google',
					url: 'http://google.com/',
					start: '2015-02-28'
				}*/
			]
		});
		
	});


</script>
<style>

	#calendar {
			margin: 20px;
	}
</style>

<div style="background: white; padding: 20px;">

	<div id='calendar'></div>



 <?php   } } else { echo '<h2>Appuntamenti per questo lead</h2> '; $tipologia_main  = "WHERE potential_rel = ". $potential_id." "; ?> 

<style>

	#calendar {
			margin: 20px;
	}
 body { background: white !important;  }
</style>
   
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
 	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); 
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>
<!--<div class="day_selector">
<a class="msg blue" href="?j=<?php echo base64_encode($potential['id']); ?>&data_da=<?php echo mydate($prev_date); ?>">Precedente</a>
<span class="msg blue"><?php echo $data_da_t; ?></span>
<a class="msg blue" href="?j=<?php echo base64_encode($potential['id']); ?>&data_da=<?php echo mydate($next_date); ?>">Successivo</a>
</div>
-->

  	 <table class="dati" summary="Dati" style=" width: 95%;">
        <tr>
     <th scope="col"></th>
       <th scope="col">Data/Ora</th>
       <th scope="col">Lead</th>
       <th scope="col">Sede</th>
       <th scope="col">Venditore</th>
       <th scope="col">Stato</th>
    

      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun appuntamento</td></tr>";		}
	$tot_res = 0;
	$meetings = '';
	while ($riga = mysql_fetch_array($risultato)) 
	{
		$colore = '';
		if($riga['issue'] == 0) { $colore = "class=\"tab_blue\"";  }
		if($riga['issue'] == 1) { $colore = "class=\"tab_orange\"";  }
		if($riga['issue'] == 2) { $colore = "class=\"tab_green\"";  }
		if($riga['issue'] == 3 || $riga['issue'] == 4)  { $colore = "class=\"tab_red\"";  } 
		if($riga['issue'] > 4 ) { $colore = "class=\"tab_orange\"";  }
		if($riga['issue'] == 6 ) { $colore = "class=\"tab_blue\"";  }
		$alarm = (strlen($riga['note']) != '') ? "<i class=\"fa fa-exclamation-triangle c-red\" aria-hidden=\"true\"  title=\"".stripcslashes(converti_txt($riga['note']))."\"></i> <i class=\"fa fa-clock-o\"></i>" : "<i title=\"".stripcslashes(converti_txt($riga['note']))."\" class=\"fa fa-clock-o\"></i>";	
		$add_calendar = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=Appuntamento con: '.$potential['nome'].' '.$potential['cognome'].'&location='.$meeting_location[$riga['meeting_location']].'&details=Trovi questo appuntamento anche sul CRM&dates='.substr(str_replace('-','',$riga['start_meeting']),0,8).'T'.substr(str_replace(':','',$riga['start_meeting']),11,8).'/'.substr(str_replace('-','',$riga['start_meeting']),0,8).'T'.substr(str_replace(':','',$riga['start_meeting']),11,8).'&sf=true&pli=1';

		$meetings .= "
					{
					title: '".$potential['nome']." ".$potential['cognome']."',
					start: '".str_replace(" ","T",$riga['start_meeting'])."',
					end: '".str_replace(" ","T",$riga['end_meeting'])."'
					},";

		    $potential = GRD($tables[106],$riga['potential_rel']);
			echo "<tr><td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><h2><strong> $alarm </strong> ".mydatetime($riga['start_meeting'])."</h2></td>"; 

			echo "<td>".$potential['nome']." ".$potential['cognome']."</td>";
			echo "<td>".$meeting_location[$riga['meeting_location']]."</td>"; 
			echo "<td>".@$proprietario[@$riga['proprietario']]."</td>"; 
			echo "<td>".$issue[$riga['issue']]."</td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."&potential_rel=".$riga['potential_rel']."\" title=\"Dettaglio Appuntamento\" target=\"_parent\"> <i class=\"fa fa-search\"></i> </a><a target=\"_blank\" href=\"$add_calendar\" style=\"color: green;\"><i class=\"fa fa-calendar-check-o\"></i></a></td>";
	
				
		    echo "</tr>";
			
			
			
	}

	echo "</table>";
	
} ?>

<?php  // Appuntamenti per il lead ?> 
</div>

</div></div></body></html>