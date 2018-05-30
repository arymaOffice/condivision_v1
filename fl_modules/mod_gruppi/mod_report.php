
<?php
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>



<h1>Panoramica Gruppi di lavoro </h1>


<?php

$report = '';
$templateRiga = '
<tr>
<th id="thnome"><strong>{{nome}}</strong></th>
<th>{{totali}}</th>
<th>{{scaduti}}</th>
<th>{{tomorrow}}</th>
<th>{{days2}}</th>
<th>{{days3}}</th>
<th>{{meetingTaken}}</th>
<th>{{meetingDone}}</th>
<th>{{preventivo}}</th>
<th>{{aConcorrenza}}</th>
<th>{{nonInteressati}}</th>
<th class="green strong" style="color:white">{{Vendita}}</th>
</tr>';		

if($_SESSION['usertype'] < 4) {
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);

	$query = "SELECT * FROM fl_gruppi WHERE id > 1 ORDER BY id DESC;";
	
	$res = mysql_query($query, CONNECT);
	
	while ($gruppo = mysql_fetch_array($res)) 
	{					





		$attivitaGruppo = getAttivitaGruppo($gruppo['id']);
		$report .= '<h2>'.$gruppo['nome_gruppo'].count($attivitaGruppo).'</h2>';

		if($attivitaGruppo['query'] != '') {

		$auto = ($attivitaGruppo['assegnazione_automatica']  == 1 ) ? '<span class="msg orange">AUTO</span>' : '<span class="msg blue">MAN</span>' ;



		$report .= "<span class=\"msg blue\">Attività assegnate</span> ".@$attivitaGruppo['names'].' ('.@$proprietario[@$attivitaGruppo['supervisor_id']].') '.@$auto ;
		//print_r($gruppo);

		$report .= '<table class="dati" summary="Dati" style=" width: 100%; text-align: left; ">
		<tr>
		<th>Operatore</th>
		<th>Totali</th>
		<th>Scaduti</th>
		<th>Scad. Domani</th>
		<th>Scad. 2gg</th>
		<th>Scad. >2gg</th>
		<th>App.ti presi</th>
		<th>App.ti Eseg.</th>
		<th>Preventivi</th>
		<th>Concorr</th>
		<th>No Inter.</th>
		<th>Vendita</th>
		</tr>';


		$query = "SELECT tb1.*,tb2.id,tb2.nominativo,tb2.tipo,tb2.persona_id, tb1.id AS nulla FROM `fl_synapsy` AS tb1 LEFT JOIN fl_account AS tb2 ON tb1.id2 = tb2.persona_id WHERE tb1.`type1` = $tab_id AND tb1.id1 = ".$gruppo['id']." AND tb1.type2 = $connectId ORDER BY tb1.type2 DESC";
		$res2 = mysql_query($query, CONNECT);

		while ($partecipanti = mysql_fetch_array($res2)) 
		{	

			if($partecipanti['id'] > 0) {

			$proprietaLeads = ' fl_potentials.id > 1 AND status_potential != 9 AND fl_potentials.proprietario = '.$partecipanti['id'];
			$attivitaGruppoIn = '  AND fl_potentials.source_potential IN('.$attivitaGruppo['query'].') '; //Restringo i conteggi alle sole attivita che interessano il gruppo

			$totale_leads = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn);
			$scaduti = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.' AND DATE(data_scadenza) < CURDATE() ');
			$tomorrow = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.'  AND data_scadenza  = (CURDATE() + 1 )');
			$days2 = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.'  AND data_scadenza  = (CURDATE() + 2)');
			$days3 = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.'  AND data_scadenza  >= (CURDATE( ) + 3 )');
			$preventivo = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.' AND status_potential = 7 ');
			$nonInteressati = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.' AND status_potential = 3 '); 
			$aConcorrenza = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.' AND status_potential = 6 '); 
			$Vendita = mk_count('fl_potentials',$proprietaLeads.$attivitaGruppoIn.' AND status_potential = 4 '); 
			$Vendita .= ' '.@numdec(@$Vendita/@$totale_leads*100,2).' %';

			$meetingTaken = mk_count('fl_potentials JOIN fl_appuntamenti',$proprietaLeads.$attivitaGruppoIn.' AND status_potential = 4 AND fl_potentials.id = fl_appuntamenti.potential_rel'); 
			$meetingDone = mk_count('fl_potentials JOIN fl_appuntamenti',$proprietaLeads.$attivitaGruppoIn.' AND status_potential = 4 AND fl_potentials.id = fl_appuntamenti.potential_rel AND (fl_appuntamenti.issue != 0 AND fl_appuntamenti.issue != 3)'); 


			$riga = str_replace('{{nome}}','<a href="../mod_leads/?status_potential=-1&proprietario='.$partecipanti['id']. $attivitaGruppo['link'].'"">'.$partecipanti['nominativo'].'</a>', $templateRiga);
			$riga = str_replace('{{totali}}', $totale_leads, $riga);
			
			$riga = str_replace('{{scaduti}}', $scaduti, $riga);
			$riga = str_replace('{{tomorrow}}', $tomorrow, $riga);
			$riga = str_replace('{{days2}}', $days2, $riga);
			$riga = str_replace('{{days3}}', $days3, $riga);

			$riga = str_replace('{{preventivo}}', $preventivo, $riga);
			$riga = str_replace('{{nonInteressati}}', $nonInteressati, $riga);
			$riga = str_replace('{{aConcorrenza}}', $aConcorrenza, $riga);
			$riga = str_replace('{{Vendita}}', $Vendita, $riga);
			$riga = str_replace('{{meetingTaken}}', $meetingTaken, $riga);
			$riga = str_replace('{{meetingDone}}', $meetingDone, $riga);

			$report .= $riga;
		}



	}
	$report .= '</table>';

	} else { $report .= '<h3>Nessuna attivita</h2>'; }

} 
echo $report;
}



function getAttivitaGruppo($gruppoId){

	$query = "SELECT * FROM `fl_campagne_attivita` WHERE gruppo_id =  $gruppoId";

	$gp = mysql_query($query, CONNECT);
	$attivitaIN = '';
	$attivitaName = $attivitaLink = $assegnazione_automatica = $supervisor_id = '';

	while ($activity = mysql_fetch_array($gp)) 
	{	
		if(isset($comma)) { $supervisor_id .=  ','; $assegnazione_automatica .=  ',';  $attivitaIN .=  ','; $attivitaName .=  ',';   }  else { $comma = 1; }

		$attivitaIN .= $activity['id'];
		$attivitaName .= $activity['oggetto'];
		$assegnazione_automatica .= $activity['assegnazione_automatica'];
		$supervisor_id .= $activity['supervisor_id'];
		$attivitaLink .= '&source_potential[]='.$activity['id'];

	}
	$attivitaArray = array('query'=>$attivitaIN,'names'=>$attivitaName,'supervisor_id'=>$supervisor_id,'link'=>$attivitaLink,'assegnazione_automatica'=>$assegnazione_automatica);
	return $attivitaArray;

}


if($_SESSION['usertype'] == 5 || $_SESSION['usertype'] == 0 || $_SESSION['profilo_funzione'] == 8) echo ' <a class="c-red" href="../../fl_core/services/leadsManager.php" target="blank"><i class="fa fa-user" aria-hidden="true"></i> Lancia Assegnazione automatica (TEST)</a>';

?>


