<?php $_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>

<h1>Calendario <?php echo @$meeting_location[$meeting_location_id]; ?></h1>  
<!--<form method="get" action="" id="">

	
 <?php
			
			 foreach($meeting_location as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($meeting_location_id == $valores) ? ' checked="checked"' : '';
			 echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="meeting_location" value="'.$valores.'" '.$selected.' />
			<label for="'.$valores.'"><i class="fa fa-user"></i><br>'.$label.'</label>'; }
			
		 ?>
      
          <?php if(isset($_GET['action'])) { ?> <input type="hidden" value="<?php echo check($_GET['action']);?>" name="action" />	<?php } ?>
 
</form>
--> <div class="filtri" id="filtri">
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
    

   <label> Persona </label> 
   <select name="proprietario" id="proprietario">
      <option value="-1">Tutti</option>
      <?php 
              
			echo '<optgroup label="Operatori BDC">';  
		     foreach($operatoribdc as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}

			echo '<optgroup label="Digital">';  
		     foreach($operatoridgt as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
	 		echo '<optgroup label="Venditori">';  
			    foreach($venditore as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 
      
    
    
 <!--  <label> Marchio </label> 
  
       <select name="marchio" id="marchio" onChange="form.submit();">

      <?php 
              
		     foreach($marchio as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($marchio_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select> -->
<?php } ?>
      <input type="submit" value="<?php echo SHOW; ?>" class="button" />

       </form>
     
      </div>
      

<div class="" style=" padding: 20px; ">     
  
<?php    
   	
	$filtro = ($meeting_location_id > 1) ? 'meeting_location = '.$meeting_location_id : '1';
	$filtro .= ($proprietario_id > 1) ? " AND (proprietario = $proprietario_id OR callcenter = $proprietario_id) " : ' ';
	$query = "SELECT `start_meeting` as `start`,`end_meeting`,`meeting_location`, COUNT('*') as `tot`,callcenter,issue  FROM `fl_appuntamenti` WHERE $filtro GROUP BY `start_meeting`,`end_meeting`,`meeting_location` ";
	//echo $query.mysql_error();
	$colors = array(0=>'#DA3235',9=>'#3DA042',10=>'#4c9ed9',11=>'#3DA042',12=>'#DA3235');
	$risultato = mysql_query($query, CONNECT);
	$meetings = '';
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$meetings .= "
					{
					title: '(".$riga['tot'].") ".@$meeting_location[$riga['meeting_location']]." ',
					start: '".str_replace(" ","T",$riga['start'])."',
					end: '".str_replace(" ","T",$riga['end_meeting'])."',
					url: './?proprietario=".$riga['callcenter']."&issue=".$riga['issue']."&data_da=".mydate($riga['start'])."',
					allDay: false,
					color: '".@$colors[$riga['meeting_location']]."'
					},";
} ?>
  
<?php    
   /* $query = "SELECT * FROM `fl_calendario` WHERE proprietario = $proprietario_id";
	
	$risultato = mysql_query($query, CONNECT);
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$meetings .= "
					{
					title: '".$riga['descrizione']."',
					start: '".str_replace(' ','T',$riga['start_date'])."',
					url: 'mod_inserisci_calendario.php?id=".$riga['id']."',
					end: '".str_replace(' ','T',$riga['end_date'])."',
					color: '#DA3235'
					},";
}*/ ?>

<br class="clear" />
<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='fullcalendar/lib/moment.min.js'></script>
<script src='fullcalendar/fullcalendar.min.js'></script>
<script src='fullcalendar/lang/it.js'></script>

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
			editable: false,
			eventLimit: true, // allow "more" link when too many events
			events: [
			
				<?php echo $meetings ; ?> 
				
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
	<div id='calendar'></div>



</div>