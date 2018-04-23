<?php
require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

include "../../fl_inc/headers.php";

$data = (isset($_GET['data'])) ? check($_GET['data']) : date('Y-m-d');

$queryIntestazione = "SELECT e.id as id ,a.nome_ambiente,titolo_ricorrenza,(numero_adulti + numero_operatori) as sum_adulti ,numero_bambini FROM `fl_eventi_hrc` e JOIN fl_ambienti a ON a.id = e.ambienti  WHERE stato_evento != 4 AND  DATE(`data_evento`) = '$data'";
$queryIntestazione = mysql_query($queryIntestazione, CONNECT);

$rigaTemplate = '';

$queryPortate = "SELECT e.id as evento, portata ,priority, nome,nome_tecnico , GROUP_CONCAT( CONCAT(e.id,'-',(SELECT (numero_adulti + numero_operatori) FROM fl_eventi_hrc WHERE id = evento )) ) as portate ,SUM((SELECT (numero_adulti + numero_operatori) FROM fl_eventi_hrc WHERE id = evento )) as totale FROM `fl_ricettario` r JOIN fl_synapsy s ON s.id2 = r.id AND s.type2 = 119 AND s.type1 = 123 JOIN fl_menu_portate m ON m.id = s.id1 AND s.type1 = 123 AND s.type2 = 119 JOIN fl_eventi_hrc e ON e.id = m.evento_id WHERE stato_evento != 4 AND DATE(e.data_evento) = '$data' GROUP BY r.id ORDER BY portata ASC, priority ASC";

$queryPortate = mysql_query($queryPortate, CONNECT);
$idArray = array();
$portateName = array('', 'Aperitivi', 'Antipasti', 'Primi', 'Secondi', 'Frutta', 'Dessert', 'Torte');
$sumAdulti = 0;
$sumBambini = 0;

$counter_colspan = 0;

?>



<div id="container" style="width: 90%;">

	<div>
		<h2 style="float:  right; font-size: 18px "><a href="javascript:window.print();" ><i class="fa fa-print"></i></a></h2>
		<form class="noprint" style="float: right;padding: 10px;">
			<input type="date" name="data" class="noprint">
			<input type="submit" value="crea" name="" style="border: solid thin #666;margin-left: 10px;" class="noprint">
		</form>
	</div>

<style type="text/css"> .dati td { border: 1px solid; font-size:large;  } </style>
<p style="text-align: left;">Prospetto portate del <?php echo date("d/m/Y", strtotime($data));
echo ' Stampa delle  ' . date('H:i d/m/Y'); ?></p>
	<table class="dati">
		<tr>
			<th><strong>Portate</strong></th>
			<?php while ($row = mysql_fetch_assoc($queryIntestazione)) {$counter_colspan++;
    $rigaTemplate .= '<td style="text-align: center;">{{' . $row['id'] . '}}</td>';
    array_push($idArray, '{{' . $row['id'] . '}}');
    $revisisone = GQD('fl_revisioni_hrc', 'DATE_FORMAT(data_creazione , "%H:%i %d/%m/%Y") as dataRevisione ', ' evento_id =" ' . $row['id'] . '" ORDER BY data_creazione DESC');
    $nomi_sposi = GQD('fl_ricorrenze_matrimonio', 'nomi_sposi_menu', ' evento_id =" ' . $row['id'] . '"');?>

				<th style="text-align: center;">
					<span style=""> <?php echo $row['nome_ambiente']; ?> </span> <br>
					<span style="margin-left: 10px;"><strong> <?php echo $row['titolo_ricorrenza']; ?> </strong></span> <br>
					<span style="margin-left: 10px;font-size: 14px;">A  <?php echo $row['sum_adulti'] ?> </span>
					<span style="font-size: 14px;">B <?php echo $row['numero_bambini'] ?></span><br>
					<span style="font-size: 14px;"><?php echo $nomi_sposi['nomi_sposi_menu'] ?></span>
					<?php if (isset($revisisone['dataRevisione'])) {?><br><span style="font-size: 14px;">Ult. Rev. <?php echo $revisisone['dataRevisione']; ?></span> <?php }?>
				</th>

			<?php $sumAdulti += $row['sum_adulti'];
    $sumBambini += $row['numero_bambini'];}
$rigaTemplate .= '<td style="color:orange;text-align: center;font-weight:bold;">{{tot}}</td>';?>

			<th style="color:orange;text-align:center;font-weight:bold;"><h2 style="margin-left: 10px;">Totale </h2><br>
				<span style="margin-left: 10px;font-size: 14px;font-weight:bold;">A  <?php echo $sumAdulti ?> </span>
				<span style="font-size: 14px;font-weight:bold;">B <?php echo $sumBambini ?></span>
			</th>
		</tr>

		<?php while ($rowPortate = mysql_fetch_assoc($queryPortate)) {?>




			<tr>
				<td style="font-size: small;"><?php echo $rowPortate['nome_tecnico'] ?></td> <!-- nome portata -->
				<?php $newLine = $rigaTemplate;
    $concatEsploso = explode(',', $rowPortate['portate']);
    foreach ($concatEsploso as $value) {
        $singlePortata = explode('-', $value);
        $newLine = str_replace('{{' . $singlePortata[0] . '}}', $singlePortata[1], $newLine);
    }
    $newLine = str_replace('{{tot}}', $rowPortate['totale'], $newLine);
    $newLine = str_replace($idArray, '', $newLine);
    echo $newLine;
    ?>

			</tr>
		<?php }?>



	</table>




</div></body></html>