<?php


// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo
$anno = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y');
$module_menu = '';
$module_title = 'Calendario disponibilità '.$anno;
$new_button = '';
unset($filtri);
unset($searchbox);
include("../../fl_inc/headers.php");
include('../../fl_inc/testata.php');
include('../../fl_inc/menu.php');
include('../../fl_inc/module_menu.php');

require_once 'CalendarEvents.php';

?>
<style>
input[type=radio]:checked + label, input[type=checkbox]:checked + label {
	background-image: none;
	background-color: #F6CD40;
	color: white;
	cursor: pointer;
	font-size: 12px;
	padding: 6px;
	margin: 0px;
	width: 100%;
	text-align: center;
}
input[type=radio] + label, input[type=checkbox] + label, .boxbutton {
	display: inline-block;

	font-size: 12px;
	background-color: #e7e7e7;
	border-color: #ddd;
	cursor: pointer;
	color: rgb(0, 0, 0);
	padding: 6px;
	margin: 0px;
	width: 100%;
	text-align: center;
}
	td{
		text-align: center;
		vertical-align: top;
	}
	th{
		font-size: 20px;
	}
	.conferma{
		margin: 50px 34% !important;
    width: 400px;
    font-size: 16px;
	}
</style>

<form action="mod_opera.php" method="POST">

	<?php
	if(!isset($_GET['lead_id'])){
		$lead_id = 0;
		echo '<span class="msg red">Nessun contattto selezionato <b> LEAD ID: 0 </b></span>';
	}else{
		$lead_id = check($_GET['lead_id']);
		echo '<span class="msg blue">Contattto selezionato <b> LEAD ID: '.$lead_id.' </b></span>';
	}

	if(!isset($_GET['ambienti'])){
		echo '<span class="msg red">Nessun ambiente selezionato</span>';
	}else{
		foreach ($_GET['ambienti'] as  $value) {
			echo '<span class="msg orange">'.$ambienti[$value].'</span>';

		}
	}

	echo CalendarEvents::getEvents($tables[6],$anno,@$_GET['mesi'],@$_GET['ambienti'],'getCalendarButton');   //prelevo il calendario senza i giorni in cui ci sono eventi
	//calendario completo

	$date_del_lead = GQS($tabella,'DATE_FORMAT(data_evento,"%Y-%m-%d") as data_da_selezionare ','YEAR(data_evento) = '.$anno.' AND lead_id = '.$lead_id);

	foreach ($date_del_lead as $value) {

		echo "<script>$('input[value=\"".$value['data_da_selezionare']."\"]').attr('checked', 'checked');</script>";
	}
	?>
<input type="hidden" name="lead_id" value="<?php echo $lead_id;?>" >
<input type="submit" class="button conferma" value="Conferma" >
</form>
