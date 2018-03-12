<?php
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$evento_id = check($_GET['evento_id']);
$evento = GRD('fl_eventi_hrc',$evento_id); 
$movimento_cassa = array('Extra','Abbuono');

include("../../fl_inc/headers.php");

$totale = 0;

$riga = '<tr>
<td>{{descrizione}}</td>
<td>&euro; {{cu}} </td>
<td>{{qty}}</td>
<td>&euro; {{totaleVoce}}</td>
</tr>';

$extra = GQS('fl_registro_cassa','*',' conto_id = '.$evento_id);
$items = '';

foreach ($extra as $chiave => $valore) { 

	if($valore['quantita'] < 1) $valore['quantita'] = 1;

   $item = str_replace('{{totaleVoce}}', numdec($valore['importo']*$valore['quantita'],2), $riga);
   $item = str_replace('{{qty}}', $valore['quantita'], $item);
   $item = str_replace('{{cu}}', $valore['importo'], $item);
   $item = str_replace('{{descrizione}}', $valore['descrizione'], $item);
   $totale += $valore['importo']*$valore['quantita'];
   $items .= $item;

}
/*
$extra = GQS('fl_synapsy','*', "valore > 0 AND type2 = 23 AND type1 = 6 AND id1 = $evento_id");
$items = '';

foreach ($extra as $chiave => $valore) { 

	if($valore['qty'] < 1) $valore['qty'] = 1;
   $itemInfo = GRD($tables[$valore['type2']],$valore['id2']); // Prendo info elemento da tabella di riferimento
	//var_dump($itemInfo); //imparo cosa sta in questa tabella

   $item = str_replace('{{totaleVoce}}', numdec($valore['valore']*$valore['qty'],2), $riga);
   $item = str_replace('{{qty}}', $valore['qty'], $item);
   $item = str_replace('{{cu}}', $valore['valore'], $item);
   $item = str_replace('{{descrizione}}', $itemInfo['label'], $item);
   $totale += $valore['valore']*$valore['qty'];
   $items .= $item;

}*/

$documenti_acconto = GQS('fl_doc_vendita','*',' ref_id = '.$evento_id.' AND workflow_id = '.$tab_id);
foreach($documenti_acconto  as $key => $documento_vendita) {
	
if($documento_vendita['id'] > 1){
	$acconti = GQD('fl_doc_vendita_voci','id,SUM(`subtotale`) AS totale','  `codice` = \'ACC\' AND `fattura_id` = '.$documento_vendita['id']);
	$tipoDoc =  ($documento_vendita['tipo_doc_vendita'] == 0) ? 'Fattura ' : 'Ricevuta ';
	if($acconti['totale'] > 0){
	$items.= '<tr><td>ACCONTO '.$tipoDoc.' no. '.$documento_vendita['numero_documento'].' del '.mydate($documento_vendita['data_documento']).'</td><td></td><td></td><td>&euro; -'.numdec($acconti['totale'],2).'</td></tr>';
	$totale -= $acconti['totale'];
}}
}

?>

<style type="text/css">
	@media print{
		.button a, a.button, a:link.button  {
			display: none !important
		}
	} 
</style>

<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">

<div class="head_title">
<h1>Preconto</h1>
<?php 

if($evento['anagrafica_cliente'] > 1 || $evento['anagrafica_cliente2'] > 1 ){

echo "<h3>".$evento['titolo_ricorrenza']." Evento del ".mydate($evento['data_evento'])." (".$giorni_settimana[date("w", strtotime($evento['data_evento']))].")  | Coperti: ".($evento['numero_adulti']+$evento['numero_bambini']+$evento['numero_operatori'])." </h3>";


$prezzo_operatori = ($evento['prezzo_operatori'] > 0) ? $evento['prezzo_operatori'] : ($evento['prezzo_base']/2);
?>
</div>

<h2>Cassa</h2>

<form action="../mod_cassa/mod_opera.php" method="POST">

	<input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
	<select name="movimento_cassa">
		<?php 
		foreach ($movimento_cassa as $key => $value) {
			echo '<option value="'.$key.'">'.$value.'</option>';
		}
		?>
	</select>
	<input type="text" name="descrizione" placeholder="Descrizione" value=""> &euro; 
	<input type="number" name="importo" value="0.00" step="100">
	<input type="submit" value="Registra" class="button">
</form>
		<form>
			<table class="dati">
				<tr>
					<th>Descrizione</th>
					<th>Costo unitario</th>
					<th>Quantità</th>
					<th>Totale</th>
				</tr>

				<tr>
					<td>Menù Adulto</td>
					<td>&euro; <?php echo $evento['prezzo_base']; ?></td>
					<td><?php echo $evento['numero_adulti'] ?></td>
					<td>&euro; <?php $totale += ($evento['prezzo_base']*$evento['numero_adulti']); echo numdec($evento['prezzo_base']*$evento['numero_adulti'],2); ?></td>
				</tr>
				
				<?php if($evento['numero_bambini'] > 0) { ?>
				<tr>
					<td>Menù Bambino</td>
					<td>&euro; <?php echo $evento['prezzo_bambini']; ?></td>
					<td><?php echo $evento['numero_bambini']; ?></td>
					<td>&euro; <?php $totale += ($evento['prezzo_bambini']*$evento['numero_bambini']);  echo numdec($evento['prezzo_bambini']*$evento['numero_bambini'],2); ?></td>
				</tr>
				<?php } ?>
				<?php if($evento['numero_operatori'] > 0) { ?>
				<tr>
					<td>Menù Operatore</td>
					<td>&euro; <?php echo $prezzo_operatori; ?></td>
					<td><?php echo $evento['numero_operatori']; ?></td>
					<td>&euro; <?php $totale += ($prezzo_operatori*$evento['numero_operatori']); echo numdec($prezzo_operatori *$evento['numero_operatori'],2); ?></td>
				</tr>
				<?php } ?>

				<?php if($evento['numero_serali'] > 0) { ?>
				<tr>
					<td>Ospiti Serali/Taglio Torta</td>
					<td>&euro; <?php echo $evento['prezzo_serali']; ?></td>
					<td><?php echo $evento['numero_serali']; ?></td>
					<td>&euro; <?php $totale += ($evento['prezzo_serali']*$evento['numero_serali']); echo numdec($evento['prezzo_serali']*$evento['numero_serali'],2); ?></td>
				</tr>
				<?php } ?>

				<?php if($evento['costi_siae'] > 0) { ?>
				<tr>
					<td>Anticipo costo SIAE</td>
					<td>&euro; <?php echo $evento['costi_siae']; ?></td>
					<td></td>
					<td>&euro; <?php $totale += $evento['costi_siae']; echo numdec($evento['costi_siae'],2); ?></td>
				</tr>
				<?php } ?>

				<?php echo  $items; ?>

			</table>
			<h1>Totale Conto: &euro; <?php echo  numdec($totale,2); ?></h1>
		</form>

		<a class="button" href="javascript:location.reload();">Ricarica Preconto</a>
		<a href="" class="button" onclick="window.print();  return false; " target="_parent"><i class="fa fa-print"></i> Stampa preconto </a>
	<a class="button fancybox_view" data-fancybox-type="iframe" href="mod_crea.php?tipo_doc_vendita=1&crea=6&refId=<?php echo $evento_id; ?>&anagraficaId=<?php echo $evento['anagrafica_cliente']; ?>" target="_parent"><i class="fa fa-plus-circle"></i> Crea Ricevuta </a>
<a class="button fancybox_view" data-fancybox-type="iframe" href="mod_crea.php?tipo_doc_vendita=0&crea=6&refId=<?php echo $evento_id; ?>&anagraficaId=<?php echo $evento['anagrafica_cliente']; ?>" target="_parent"><i class="fa fa-plus-circle"></i> Crea Fattura </a>



<?php 

if(defined('GESTIONE_CAPARRA')) { $movimento_caparra = array('Versamento','Restituzione'); ?>
<br>
<hr>
<h1>:::::::::::::::</h1>
<div class="head_title">
<h2>Caparra Evento</h2>

<form action="../mod_caparre/mod_opera.php" method="POST">

	<input type="hidden" name="evento_id" value="<?php echo $evento_id; ?>">
	<select name="movimento_caparra">
		<?php 
		foreach ($movimento_caparra as $key => $value) {
			echo '<option value="'.$key.'">'.$value.'</option>';
		}
		?>
	</select>
	<input type="text" name="descrizione" placeholder="Descrizione" value="Caparra Confirmatoria"> &euro; 
	<input type="number" name="importo" value="<?php echo GESTIONE_CAPARRA; ?>" step="100">
	<input type="submit" value="Registra" class="button">
</form>
</div>

<table class="dati">
	<tr><th>Movimento</th><th>Descrizione</th><th>Importo</th><th>Data</th></tr>
	<?php
	$bilancio = 0;
	$registro_caparre = GQS('fl_registro_caparre','*',' evento_id = '.$evento_id);
	foreach ($registro_caparre as $key => $value) {
			echo '<tr><td>'.$movimento_caparra[$value['movimento_caparra']].'</td><td>'.$value['descrizione'].'</td><td>&euro; '.$value['importo'].'</td><td>'.mydate($value['data_operazione']).'</td></tr>';
			$bilancio += $value['importo'];
		}
	?>
	<tr><th></th><th>Bilancio</th><th>&euro; <?php echo $bilancio; ?></th><th></th></tr>
</table>
<p>NB: Per inserire la caparra nel contratto devi inserire il segnaposto {{caparra_confirmatoria}} nel template di contratto</p>

<?php 
}


}else{
	echo "<br><h1 style=\"text-align:center\">Non c'è un'anagrafica collegata all'evento. </h1><br>	<a class=\"button\" href=\"javascript:location.reload();\">Carica Preconto</a>";
}


	mysql_close(CONNECT); ?>
</body>
</html>