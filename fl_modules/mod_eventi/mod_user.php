
<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo  
include("../../fl_inc/headers.php");



$monthSelected = (isset($_GET['mese_evento'])) ? $_GET['mese_evento'] : date('n');

 ?>



	<?php
	
	
	$tipologia_main = ' WHERE  lead_id = '.check($_GET['lead_id']);
	

	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY data_evento DESC;";
	

	$risultato = mysql_query($query, CONNECT);

	?>


<h1>Eventi</h1>

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

			$ambienti_id = '';
			$ambienti_id = explode(',',$riga['ambienti']);

			$ambienti_txt = '';
			foreach ($ambienti_id as $key => $value) {
				$ambienti_txt .= ', '.@$ambienti[$value].'';
			}
			
			$coloreEvento = @$colors[$riga['tipo_evento']];


			
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
			echo "<a style=\"color: $coloreEvento\" href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Amministrativa\" target=\"_parent\" > <i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a>";
			echo "</td>";
			echo "<td><a target=\"_parent\" href=\"mod_scheda_servizio.php?evento_id=".$riga['id']."&tipo_evento=".$riga['tipo_evento']."&id=$schedaWeddingId\" title=\"Gestione Operativa\" style=\"color:  $colorScheda\"><i class=\"fa fa-address-card\" aria-hidden=\"true\"></i></a></td>";
			//if($riga['stato_evento'] == 4 && $_SESSION['usertype'] == 0) echo "<td><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
			echo "</tr>";
			
			
			
		}

		echo "</table>";


		include("../../fl_inc/footer.php"); 
		?>
