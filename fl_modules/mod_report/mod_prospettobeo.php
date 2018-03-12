<?php
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php"); 

$data = (isset($_GET['data'])) ? check($_GET['data']) : date('Y-m-d');



$queryIntestazione = "SELECT e.id as id ,label as nome_ambiente,titolo_ricorrenza,(numero_adulti + numero_operatori) as sum_adulti ,numero_bambini FROM `fl_eventi_hrc` e JOIN fl_items a ON a.id = e.ambienti  WHERE DATE(`data_evento`) = '$data'";
$queryIntestazione = mysql_query($queryIntestazione,CONNECT);

$rigaTemplate = '';

$queryPortate = "SELECT e.id as evento, portata , nome , GROUP_CONCAT( CONCAT(e.id,'-',(SELECT (numero_adulti + numero_operatori) FROM fl_eventi_hrc WHERE id = evento )) ) as portate ,SUM((SELECT (numero_adulti + numero_operatori) FROM fl_eventi_hrc WHERE id = evento )) as totale FROM `fl_ricettario` r JOIN fl_synapsy s ON s.id2 = r.id AND s.type2 = 119 AND s.type1 = 123 JOIN fl_menu_portate m ON m.id = s.id1 AND s.type1 = 123 AND s.type2 = 119 JOIN fl_eventi_hrc e ON e.id = m.evento_id WHERE DATE(e.data_evento) = '$data' GROUP BY r.id ORDER BY portata";

$queryPortate = mysql_query($queryPortate,CONNECT);
$idArray= array();
$portateName = array('Aperitivi','Antipasti','Primi','Secondi','Contorni','Frutta','Dessert','Torte');
$sumAdulti = 0;
$sumBambini = 0;

?>



<div id="container" style="width: 90%;">

	<div>
				<h2 style="float:  right; font-size: 18px "><a href="javascript:window.print();" ><i class="fa fa-print"></i></a></h2>
<form>
			<input type="date" name="data">
			<input type="submit" value="cerca" name="" style="border: solid thin #666;margin-left: 10px;">
		</form>


	</div>
<style type="text/css"> .dati td { border: 1px solid;  } </style>
	<table class="dati">
		<tr>

			<td ><h1>Prospetto portate <br> del <?php echo date("d/m/Y", strtotime($data)); ?></h1></td> 

			<?php  while($row = mysql_fetch_assoc($queryIntestazione)){ $rigaTemplate .='<td style="text-align: center;">{{'.$row['id'].'}}</td>'; array_push($idArray,'{{'.$row['id'].'}}')?> 

				<td> 
					<span style="margin-left: 10px;"> <?php echo $row['nome_ambiente']; ?> </span> <br> 
					<span style="margin-left: 10px;"> <?php echo $row['titolo_ricorrenza']; ?> </span> <br> 
					<span style="margin-left: 10px;font-size: 14px;">A  <?php echo $row['sum_adulti'] ?> </span> 
					<span style="font-size: 14px;">B <?php echo $row['numero_bambini'] ?></span>
				</td>  
			<?php $sumAdulti += $row['sum_adulti']; $sumBambini += $row['numero_bambini'];  } $rigaTemplate .='<td style="color:orange; text-align: center;">{{tot}}</td>'; ?>
			<td style="color:orange"><span style="margin-left: 10px;">Totale </span><br> 
				<span style="margin-left: 10px;font-size: 14px;">A  <?php echo $sumAdulti ?> </span> 
				<span style="font-size: 14px;">B <?php echo $sumBambini ?></span>
			</td>
		<tr>

		<?php while($rowPortate = mysql_fetch_assoc($queryPortate)){ ?>
			<?php if($portateName[$rowPortate['portata']] != 1){ ?>
				<tr><th style="text-align: center;font-weight: bold" colspan="<?php echo count($idArray); ?>"><h1 style="text-align: left;"><?php echo $portateName[$rowPortate['portata']] ?></h1></th><tr>
			<?php $portateName[$rowPortate['portata']] = 1;   } ?>
			<tr>
				<td><?php echo $rowPortate['nome'] ?></td> <!-- nome portata -->
				<?php $newLine = $rigaTemplate; 
					  $concatEsploso = explode(',', $rowPortate['portate']);
					  foreach ($concatEsploso as $value) {
					  	$singlePortata = explode('-',$value);
					  	$newLine = str_replace('{{'.$singlePortata[0].'}}', $singlePortata[1], $newLine);
					  }
					  $newLine = str_replace('{{tot}}', $rowPortate['totale'], $newLine);
					  $newLine = str_replace($idArray, '', $newLine);
					  echo $newLine;
				?>

			<tr>
		<?php } ?>


	
	</table>




</div></body></html>