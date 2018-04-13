<?php


// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo

$lead_id = 0;
if(isset($_GET['lead_id'])) $lead_id = check($_GET['lead_id']);

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


input[type=checkbox]:disabled + label.col0{

	background-color: #c7cbcc;


}

input[type=checkbox]:disabled + label.col2{

	background-color:#485b93;
	
}

input[type=checkbox]:disabled + label.col3{
	background-color:#90559f;
}

input[type=checkbox]:disabled + label.col4{
	background-color: #53b398;
}

input[type=checkbox]:disabled + label.col5{
	background-color:#1a4186;
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

 
}
#rightTitle{

	position: absolute !important;
    top: 14px;
    right: 20px;
}  


</style>


<?php

if($lead_id == 0) { 
} else {
$lead_info = GQD('fl_leads_hrc','*','id='.$lead_id);
echo '<a href="../mod_leads/mod_inserisci.php?id='.$lead_info['id'].'" target="_parent" id="rightTitle"><i class="fa fa-user" aria-hidden="true"></i> Stai assegnando delle date a '.$lead_info['nome'].' </a>'; 
}

?>
<ul class="periodSelector" style="text-align:center;">
		
	<?php 


$totaleOspiti = (isset($_GET['totaleOspiti'])) ? '&totaleOspiti' : '';
$anni = array(2017,2018,2019,2020,2021,2022,2023,2024);
$annoSelected = (isset($_GET['anno'])) ? check($_GET['anno']) : date('Y') ;

		foreach ($anni as $key => $value) {

		$sel = ($value == @$annoSelected) ? 'selected' : '';
		$tot = mk_count('fl_eventi_hrc','stato_evento != 4 AND (YEAR(data_evento) = '.$value.') ');  

		echo '<li class="'.$sel.'" id="'.$key.'"><a href="mod_seleziona_disponibilita_ambienti.php?closed&anno='.$value.$totaleOspiti.'&lead_id='.$lead_id.'">'.$value.' ('.$tot.')</a></li>';
	
	}


	?>
	</ul>	


	
	<?php 


		foreach ($mese as $key => $value) {

		echo '<input type="radio" value="'.$key.'" id="a'.$key.'" name="meseSel"><label for="a'.$key.'">'.$value.'</label>';

		
	}


	?>	

<form action="mod_opera.php" method="POST" class="ajaxForm">

	<?php

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

	$date_del_lead = GQS($tabella,'id,DATE_FORMAT(data_evento,"%Y-%m-%d") as data_da_selezionare,ambienti,ambiente_principale,ambiente_1,ambiente_2,notturno, stato_evento','YEAR(data_evento) = '.$anno .' AND stato_evento != 4');

	$last_id = 1;
	$color = 0;
	echo '<script>$(document).ready(function(){';
	foreach ($date_del_lead as $value) {

		/*
		$ambienti_esplosi = explode(',',$value['ambienti']);
		foreach ($ambienti_esplosi as $numero) {
			echo "<script>$('input[value=\"".$value['data_da_selezionare'].".".$numero."\"]').attr('disabled', 'disabled');</script>";
			echo "<script>$('label[for=\"".$value['data_da_selezionare'].".".$numero."\"]').addClass('');</script>";
		}*/

		if($value['id'] != $last_id){ $last_id = $value['id']; $color ++;}
		$color = $value['ambiente_principale']; 
		if($value['stato_evento'] == 0) $color = 0;

		

		echo "$('input[value=\"".$value['data_da_selezionare'].".".$value['ambiente_principale']."\"]').attr('disabled', 'disabled');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_principale']."\"]').addClass('col".$color."');";
		
		echo "$('input[value=\"".$value['data_da_selezionare'].".".$value['ambiente_1']."\"]').attr('disabled', 'disabled');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_1']."\"]').addClass('col".$color."');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_1']."\"]').attr('title', 'Aperitivo');";

		echo "$('input[value=\"".$value['data_da_selezionare'].".".$value['ambiente_2']."B\"]').attr('disabled', 'disabled');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_2']."B\"]').addClass('col".$color."');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['ambiente_2']."B\"]').attr('title', 'Taglio Torta');";


		echo "$('input[value=\"".$value['data_da_selezionare'].".".$value['notturno']."\"]').attr('disabled', 'disabled');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['notturno']."\"]').addClass('col".$color."');";
		echo "$('label[for=\"".$value['data_da_selezionare'].".".$value['notturno']."\"]').attr('title', 'After Wedding');";
		

		

	}
	echo '

	// Applico la stessa cosa al click del tastino
	$("input[name=\'meseSel\']").click(function( event ) { 	
	var select =  $(this).val();
    localStorage.setItem("selectedM", select);	
    var selectedM = localStorage.getItem(\'selectedM\');
	orderItems();
	});

	function orderItems(){

		var selectedM = localStorage.getItem(\'selectedM\');
		if(selectedM > 0)  { 
		$("input[id=\'a\'+selectedM+\'\']").attr(\'checked\', true); 
		$(\'.grid-item\').fadeOut(100);
		$(\'.a\'+selectedM+\'\').fadeIn(500);
		selectedM++;
		$(\'.a\'+selectedM+\'\').fadeIn(500);

		} else { 

		$(\'#a0\').attr(\'checked\', true); 
		$(\'.grid-item\').show(500);
	
		}

	}	

	$(\'#a0\').attr(\'checked\', true); 



	}); </script>';
	?>
	<div id="results"></div>
	<input type="hidden" name="lead_id" value="<?php echo $lead_id;?>" >
	<input type="submit" class="button " value="Assegna Disponibilità <?php echo $anno; ?>" style="width: 100%; padding: 10px; position: fixed;

bottom: 0;">
</form>



<?php mysql_close(CONNECT); ?>