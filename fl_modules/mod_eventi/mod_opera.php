<?php

$meeting_page = 1;
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo

if(isset($_GET['mese_evento'])) $_SESSION['mese_evento'] = check($_GET['mese_evento']);
if(isset($_GET['anno_evento'])) $_SESSION['anno_evento'] = check($_GET['anno_evento']);



//aggiunta revisione ad un evento esistente
if(isset($_POST['evento_id']) && isset($_POST['numero_adulti']) && isset($_POST['numero_bambini']) && isset($_POST['numero_operatori']) && isset($_POST['note']) ){

	//recupero valori
	$evento_id = check($_POST['evento_id']);
	$numero_adulti = check($_POST['numero_adulti']);
	$numero_bambini = check($_POST['numero_bambini']);
	$numero_sedie = check($_POST['numero_sedie']);
	$numero_sedioloni = check($_POST['numero_sedioloni']);
	$numero_serali = check($_POST['numero_serali']);
	$numero_operatori = check($_POST['numero_operatori']);
	$note = check($_POST['note']);

	//da aggiungere tipo variazione

	$insertRevisione = "INSERT INTO fl_revisioni_hrc (evento_id,numero_adulti,numero_bambini,numero_sedie,numero_sedioloni,numero_serali,numero_operatori,note,data_creazione,data_aggiornamento,proprietario) VALUES (
		$evento_id,'$numero_adulti','$numero_bambini','$numero_sedie','$numero_sedioloni','$numero_serali','$numero_operatori','$note',NOW(),NOW(),'".$_SESSION['number']."')";
		$insertRevisione = mysql_query($insertRevisione,CONNECT);

		if(mysql_affected_rows() > 0 ){
			//aggiornamento evento
			$updateDatiEvento = "UPDATE ".$tables[6]." SET numero_adulti = '$numero_adulti',numero_bambini = '$numero_bambini',numero_sedie = '$numero_sedie',numero_sedioloni = '$numero_sedioloni',numero_operatori = '$numero_operatori',numero_serali = '$numero_serali' WHERE id = $evento_id";
			$updateDatiEvento = mysql_query($updateDatiEvento,CONNECT);
			echo '<script type="text/javascript"> parent.$.fancybox.close();</script>'; exit;
		}else{
			header("Location: mod_insert_revisioni.php?esito=Valori%20inseriti%20non%20corretti ".mysql_error()."&evento_id=".$evento_id); exit;

		}
	}



	//conferma presa visione revisione
	if(isset($_GET['revisione_id']) && isset($_GET['evento_id'])){stampa_calendario.
		$revisione_id = check($_GET['revisione_id']);
		$evento_id = check($_GET['evento_id']);

		$insertPresavisione="INSERT INTO fl_revisioni_check (revisione_id,proprietario) VALUES ($revisione_id,".$_SESSION['number'].")";
		$insertPresavisione = mysql_query($insertPresavisione,CONNECT);
		mysql_close(CONNECT);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit;
	}

	//report presa visione revisione
	if(isset($_GET['revisione_id'])){

		$revisione_id = check($_GET['revisione_id']);

		$selectReport="SELECT proprietario,DATE_FORMAT(data_creazione,'%d/%m/%Y %H:%i') as quando FROM fl_revisioni_check WHERE revisione_id = $revisione_id";
		$selectReport = mysql_query($selectReport,CONNECT);
		include("../../fl_inc/headers.php");
		echo "<h3 style='text-align:center'>Conferme Visione</h3>";
		if(mysql_affected_rows() > 0 ){
			while ($row= mysql_fetch_assoc($selectReport)) {
				echo "<p style=\"padding: 20px;\">Visionata il ".$row['quando']." da ".$promoter[$row['proprietario']]."<p>";
			}
			exit;
		}else{
			echo "<p style=\"background:white;padding: 20px;\">Nessuno ha visionato la revisione<p>";
			exit;

		}
	}



	//selezione date per lead_id
	if(isset($_POST['lead_id']) && isset($_POST['date_disponibilita'])){

		$lead_id = check($_POST['lead_id']);
		$values = '';
		foreach ($_POST['date_disponibilita'] as $key => $value) {
			$values .= "($lead_id,'".check($value)."'),";
		}
		$values = trim($values,',');
		$sql = "INSERT INTO fl_disponibilita_date (`lead_id`, `data_disponibile`) VALUES $values";
		$insertDate = mysql_query($sql,CONNECT);
		mysql_close(CONNECT);
		echo json_encode(array('action'=>'popup','class'=>'green','url'=>"mod_disponibilita_note.php?lead_id=$lead_id",'esito'=>"Salvato Correttamente!")); 
		exit;
	}

	//selezione ambienti e  date per lead_id
	if(isset($_POST['lead_id'])){

		$lead_id = (isset($_POST['lead_id'])) ? check($_POST['lead_id']) : '' ;
		$sql = "DELETE FROM fl_disponibilita_date WHERE `lead_id` = $lead_id";
		$delete = mysql_query($sql,CONNECT);

		$date_con_ambiente = array();
		foreach ($_POST as $param_name => $param_val) {
			if(preg_match("/^data-(.*)/i", $param_name)){
				$date_con_ambiente[] = $param_val;
			}
		}



		$values = '';

		foreach ($date_con_ambiente as $value) {

			foreach($value as $single_value){
				$explode = explode('.',$single_value);
				$values .= "($lead_id,'".$explode[0]."','".$explode[1]."'),";

			}
			
		}

		$values = trim($values,',');
		$sql = "INSERT INTO fl_disponibilita_date (`lead_id`, `data_disponibile`,ambiente_id) VALUES $values";
		$insertDate = mysql_query($sql,CONNECT);
		mysql_close(CONNECT);
		echo json_encode(array('action'=>'popup','class'=>'green','url'=>"mod_disponibilita_note.php?lead_id=$lead_id&ambiente",'esito'=>"Salvato Correttamente!")); 

		exit;
	}



	mysql_close(CONNECT);
	header("Location: ".$_SESSION['POST_BACK_PAGE']);
	exit;
	?>
