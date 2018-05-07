<?php if(isset($_GET['printer'])) {

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
echo '<body style=" height: auto; text-align: left;"  >';

} 
 ?>



<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	if(isset($_GET['printer']))  $step = 1000;
	if(isset($_GET['printer']))  $tipologia_main = "WHERE id != 1 AND attivo = 1";
	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = (!isset($_GET['printer'])) ? paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main) : paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	
	if(isset($_GET['printer']))	echo '<div class="box_dash1" id="printer80mm">';
			
	
    $ultimo_agg = GQD($tabella,'id,data_aggiornamento','id > 1 ORDER BY data_aggiornamento DESC LIMIT 1');
    echo "<h3 class=\"clear\">Ultimo aggiornamento: ".mydatetime($ultimo_agg['data_aggiornamento']).'</h3>';

	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			


	?>
    

	<?php if(isset($_GET['printer'])) { echo '<table style="text-align: left; width: 80mm; ">'; } else { echo '<table class="dati">'; } ?>

      <tr>
        <?php if(!isset($_GET['printer'])) echo ' <th></th>'; ?>
        <th>Prodotto</th>
        <th>Valore Facciale</th>
        <th>Commissione</th>
        <?php if(!isset($_GET['printer'])) echo '<th>Iva</th>'; ?>
 	    <?php if(!isset($_GET['printer'])) echo '<th>Ultimo Aggiornamento</th>'; ?>
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	
	if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			$colore = ($riga['attivo'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"";  
			
			if(!isset($_GET['printer'])) echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><a href=\"#\" title=\"\">".ucfirst($riga['label'])."</a></td>";		
			echo "<td>&euro; ".numdec($riga['valore_facciale'],2)."</td>";
			echo "<td>&euro; ".numdec(($riga['profilo'.$_SESSION['profilo_commissione']]*$riga['ricavo_netto'])/100,2)."</td>";
			if(!isset($_GET['printer'])) echo "<td>".$aliquota_iva[$riga['aliquota_iva']]."</td>";
			if(!isset($_GET['printer'])) echo "<td>".mydatetime($riga['data_aggiornamento'])."</td>";
		
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
  <br class="clear" />

<?php 
if(isset($_GET['printer']))  echo '<a href="#" class="button noprint" style="width: 100%;display: block;text-align: center;" onClick=" window.print();"><i class="fa fa-print"></i> Stampa </a>'; 
	if(isset($_GET['printer']))	echo '</div>';

?>
