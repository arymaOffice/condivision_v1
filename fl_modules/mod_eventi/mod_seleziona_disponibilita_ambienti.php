<?php


// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo
$anno = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y');
$module_title = 'Calendario disponibilità '.$anno;
$new_button = '';
unset($filtri);

include("../../fl_inc/headers.php");
include('../../fl_inc/testata.php');
include('../../fl_inc/menu.php');
include('../../fl_inc/module_menu.php');

require_once 'CalendarEvents.php';

?>
<style>
input[type=radio]:disabled + label, input[type=checkbox]:disabled + label {
	background-image: none;
	color: white;
	cursor: pointer;
	font-size: 12px;
	padding: 6px;
	margin: 5px;
	width: 100px;
	text-align: left;
}


/* range di colori per evento */


input[type=checkbox]:disabled + label.col1{

	background-color: #aaa;


}

input[type=checkbox]:disabled + label.col2{

	background-color:#e06f0e;
	
}

input[type=checkbox]:disabled + label.col3{
	background-color:#138c16;
}

input[type=checkbox]:disabled + label.col4{
	background-color:#72efcc;
}

input[type=checkbox]:disabled + label.col5{
	background-color:#7281ef;
}

input[type=checkbox]:disabled + label.col6{
	background-color:#051586;
}

input[type=checkbox]:disabled + label.col7{
	background-color:#8c1ce0;
}

input[type=checkbox]:disabled + label.col8{
	background-color:#e01c52;
}

input[type=checkbox]:disabled + label.col9{
	background-color:#000000;
}

input[type=checkbox]:disabled + label.col10{
	background-color:#cc9598;
}







input[type=radio]:checked + label, input[type=checkbox]:checked + label {
	background-image: none;
	background-color: #F6CD40;
	color: white;
	cursor: pointer;
	font-size: 12px;
	padding: 6px;
	margin: 5px;
	width: 100px;
	text-align: left;
}
input[type=radio] + label, input[type=checkbox] + label, .boxbutton {
	display: inline-block;
	font-size: 12px;
	background-color: rgba(255, 255, 255, 0);
	border-color: #ddd;
	cursor: pointer;
	color: rgb(0, 0, 0);
	padding: 6px;
	margin: 5px;
	width: 100px;
	text-align: left;
}

.conferma{
	margin: 50px 34% !important;
	width: 400px;
	font-size: 16px;
}

.giorni_rossi{
	padding-left: 10px;
}

.grid-container {
	grid-row-gap: 20px;
	display: grid;
	grid-template-columns: auto auto auto;
	padding: 10px;
}
.grid-item {
	background-color: rgba(255, 255, 255, 0.8);
	border: 1px solid rgba(194, 194, 194, 0.8);
	padding: 5px;
	text-align: left;
	font-size: 12px;
	margin: 2px;


}

#environments{
	width: 16% !important;
	text-align: center !important;
	font-size: 10px !important;
	margin: 3px !important;
	display: inline;
}

@media  screen and (max-width: 400px){
	.grid-container {
		display: block !important;
	}
}


</style>

<ul class="periodSelector" style="text-align:center;">
		
	<?php 
$totaleOspiti = (isset($_GET['totaleOspiti'])) ? '&totaleOspiti' : '';
$anni = array(2017,2018,2019,2020,2021,2022,2023,2024);
$annoSelected = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y') ;

		foreach ($anni as $key => $value) {

		$sel = ($value == @$annoSelected) ? 'selected' : '';
		$tot = mk_count('fl_eventi_hrc',' (YEAR(data_evento) = '.$value.') ');  

		echo '<li class="'.$sel.'" id="'.$key.'"><a href="mod_seleziona_disponibilita_ambienti.php?closed&anno='.$value.$totaleOspiti.'">'.$value.' ('.$tot.')</a></li>';
	
	}


	?>
	</ul>	

<form action="mod_opera.php" method="POST" class="ajaxForm">

	<?php
	$lead_id = time();

	/*if(!isset($_GET['lead_id'])){
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
	}*/

	echo CalendarEvents::getEvents($tables[6],$anno,@$_GET['mesi'],@$_GET['ambienti'],'getCalendarColumn');   //prelevo il calendario senza i giorni in cui ci sono eventi
	//calendario completo

	$date_del_lead = GQS($tabella,'id,DATE_FORMAT(data_evento,"%Y-%m-%d") as data_da_selezionare,ambienti,ambiente_principale,ambiente_1,ambiente_2,notturno ','YEAR(data_evento) = '.$anno);

	$last_id = 1;
	$color = 0;

	foreach ($date_del_lead as $value) {

		/*
		$ambienti_esplosi = explode(',',$value['ambienti']);
		foreach ($ambienti_esplosi as $numero) {
			echo "<script>$('input[value=\"".$value['data_da_selezionare'].".".$numero."\"]').attr('disabled', 'disabled');</script>";
			echo "<script>$('label[for=\"".$value['data_da_selezionare'].".".$numero."\"]').addClass('');</script>";
		}*/

		if($value['id'] != $last_id){ $last_id = $value['id']; $color ++;}
		if($color == 11){ $color = 1; }


		echo "<script>$('input[value=\"".$value['data_da_selezionare'].".".$value['ambiente_principale']."\"]').attr('disabled', 'disabled');</script>";
		echo "<script>$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_principale']."\"]').addClass('col".$color."');</script>";
		
		echo "<script>$('input[value=\"".$value['data_da_selezionare'].".".$value['ambiente_1']."\"]').attr('disabled', 'disabled');</script>";
		echo "<script>$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_1']."\"]').addClass('col".$color."');</script>";

		echo "<script>$('input[value=\"".$value['data_da_selezionare'].".".$value['ambiente_2']."\"]').attr('disabled', 'disabled');</script>";
		echo "<script>$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_2']."\"]').addClass('col".$color."');</script>";


		echo "<script>$('input[value=\"".$value['data_da_selezionare'].".".$value['notturno']."\"]').attr('disabled', 'disabled');</script>";
		echo "<script>$('label[for=\"".$value['data_da_selezionare'].".".$value['notturno']."\"]').addClass('col".$color."');</script>";
		

	}
	?>
	<div id="results"></div>
	<input type="hidden" name="lead_id" value="<?php echo $lead_id;?>" >
	<input type="submit" class="button conferma" value="Conferma" >
</form>
