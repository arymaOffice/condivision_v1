<?php

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$evento_id = check($_GET['evento_id']); 

?>

<!DOCTYPE html>
	<html lang="en">

	<head>

	<?php 	include("../../fl_inc/headers.php"); ?>
		<meta charset="utf-8">
		<style>

		body { background: #ccc6; font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif; text-align: center; }

		@page {size: 105mm 148mm; margin: 10mm;}
		section { page-break-after: always; margin: 5px; background: white; }

		@media print {

			#print { display: none; }

		}


		</style>
	</head>

	<body>


<?php

if(!isset($_GET['backtop'])) {



	?>
	<h1 style="color: white;">Seleziona margini di stampa</h1>

	<div  style="width: auto; text-align: center; margin: 10px 0 0 0; background: white; padding: 0;">
		<form action="" method="get">
		<table style="margin: 0 auto;">
				<tr><td></td> <td><input type="number" value="5" name="backtop" style="width: 50px;" ></td> <td></td></tr>
				<tr><td style="vertical-align: middle;"><input type="number" value="5" name="backleft" style="width: 50px;"></td>s <td style="height: 200px;">
					<img style="max-height: 200px; width: auto;" src="../../fl_set/lay/documento.jpg"></td> <td style="vertical-align: middle;">
					<input type="number" value="5" name="backright" style="width: 50px;"></td></tr>
					<tr><td></td> <td><input type="number" value="5" name="backbottom" style="width: 50px;"></td> <td></td></tr>
				</table>
				<input type="radio" id="formatoP" name="formato" value="P" checked="checked"><label for="formatoP">Portrait</label>

				<input type="radio" id="formatoL" name="formato" value="L"><label for="formatoL">Landscape</label>
				 font: <input type="number" name="fontsize" value="18" style="width: 60px"  >
				<input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
				<input type="checkbox" name="printAll" value="1" style="display: inline-block;" > Stampa tutti

				<select name="font">
				<option>Calibri</option>
				</select>


				<input type="submit" value="Crea Cavalieri" class="button" >

			</form>
		</div>

		<?php
		mysql_close(CONNECT);
		exit;
	} 

	$evento = GRD($tabella,$evento_id);
	$filename = 'Cavalieri-evento-'.$evento_id.'.pdf';
	$backtop = (isset($_GET['backtop'])) ? check($_GET['backtop']) : 5;
	$backright = (isset($_GET['backright'])) ? check($_GET['backright']) : 5;
	$backbottom = (isset($_GET['backbottom'])) ? check($_GET['backbottom']) : 5;
	$backleft = (isset($_GET['backleft'])) ? check($_GET['backleft']) : 5;
	$font = (isset($_GET['font'])) ? check($_GET['font']) : '';
	$fontsize = (isset($_GET['fontsize'])) ? check($_GET['fontsize']) : '18';
	$tipo_commensale = (!isset($_GET['printAll'])) ? 'AND tipo_commensale = 4 ' : '';

	?>




	<button id="print" style=" margin: 20px 0;" onclick="window.print()" class="button">Stampa</button>


	<?php
	
	$template='
		
		<section class="sheet padding-10mm" style="min-width:105mm; min-height:148mm; max-width:105mm ; max-height:148mm; margin: 0 auto; font-size: '.$fontsize.'px; ">

			<article>
			<h3 style="text-align: center;">{{logo}}</h3>
			<h3 id="t{{table_id}}" style="text-align: center; font-size: 25px; margin: {{list_margin}}px 0 30px 0">{{table_name}}</h3>
			{{guests_list}}
			<p style="font-style: italic; color: gray; font-size: smaller; margin-top: 30px;">{{total_guests}}</p>
			</article>

		</section>

	';
	
	$ItemTemplate = '<div style="text-align: center; font-weight: normal; margin-top: 10px;">{{cognome}} {{nome}}</div>';
	
	$query = "SELECT * FROM fl_tavoli  WHERE  `evento_id` = '$evento_id' ORDER BY `nome_tavolo` ASC, `numero_tavolo_utente` ASC;";
	$risultato = mysql_query($query, CONNECT);


	if(mysql_affected_rows() == 0) { echo "<h1>Nessun Tavolo Inserito</h1>";		}
	$logo = '';
	if(defined('LOGO_CAVALIERI')) $logo = '<img src="'.LOGO_CAVALIERI.'" alt="" style="width: 200px; margin-top: 20px;" />';
	

	

	while ($riga = mysql_fetch_assoc($risultato)){		


		$id_tavolo = $riga['id'];
		$table_name = urldecode(strtoupper($riga['nome_tavolo']))." ".$riga['numero_tavolo_utente']."<br>".$riga['nome_tavolo_utente'];
		$a = $b = $s = $h = 0;
		$guests_list = '';
		$total_guests = '';
		$guestCounter = 0;


		$query2 = "SELECT * FROM fl_tavoli_commensali  WHERE  `tavolo_id` = '$id_tavolo' $tipo_commensale ORDER BY id DESC;";
		$risultato2 = mysql_query($query2, CONNECT);
		
		while ($commensale = mysql_fetch_assoc($risultato2)){	
			
			$nome =  preg_replace("/\([^)]+\)/","",urldecode($commensale['nome'])); 
			$cognome =  preg_replace("/\([^)]+\)/","",urldecode($commensale['cognome']));

			$listItem = str_replace('{{cognome}}', $cognome, $ItemTemplate); 
			$listItem = str_replace('{{nome}}', $nome, $listItem); 
			$guests_list .= $listItem;

			$a += $commensale['adulti'];
			$b += $commensale['bambini'];
			$s += $commensale['sedie'];
			$h += $commensale['seggioloni'];
			$guestCounter++;

		}


		$marginTop = 50+(10-$guestCounter)*10;

		if($a > 0) $total_guests .= ' + '.$a.' A ';
		if($b > 0) $total_guests .= ' + '.$b.' B ';
		if($s > 0) $total_guests .= ' + '.$s.' S ';
		if($h > 0) $total_guests .= ' + '.$h.' H ';

		$cavaliere = str_replace('{{logo}}', $logo, $template);
		$cavaliere = str_replace('{{table_id}}', $id_tavolo, $cavaliere);
		$cavaliere = str_replace('{{list_margin}}', $marginTop, $cavaliere);
		$cavaliere = str_replace('{{table_name}}', $table_name, $cavaliere);
		$cavaliere = str_replace('{{guests_list}}', $guests_list, $cavaliere);
		$cavaliere = str_replace('{{total_guests}}', $total_guests, $cavaliere);
	
		if($guestCounter > 0) echo $cavaliere; // Stampo il cavaliere

	} // Tavolo
		
	
	


	mysql_close(CONNECT);
	exit;

?>

</body>
</html>