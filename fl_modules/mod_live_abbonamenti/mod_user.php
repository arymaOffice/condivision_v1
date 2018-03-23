<?php 
// Controlli di Sicurezza
if(!isset($_SESSION['scelta_abbonamento'])){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
$data_set =  '';
include 'fl_settings.php';

?>

    
<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
	$query = "SELECT $select,free,p.label as periodoLabel,abb.id as abbId FROM `$tabella` abb LEFT JOIN fl_periodi p ON abb.periodo = p.id WHERE abb.id != 1 AND abb.attivo = 1  AND free = 0 ORDER BY abb.$ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>
<br>
<br>
<br>
<table class="dati" summary="Dati">
  <tr>
    <th>Free</th>
    <th>Nome</th>
    <th>Durata</th>
    <th>Periodo</th>
    <th>Costo</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['attivo'] == 1) ? 'tab_green' : 'tab_red';
	$free = ($riga['free'] == 1) ? 'tab_yellow' : '';

			echo "<tr>"; 
			echo "<td style=\"width:10px\" class=\"$free\"></td>";
			echo "<td>".$riga['nome']."</td>";	
			echo "<td>".$riga['durata']."</td>";	
			echo "<td>".$riga['periodoLabel']."</td>";	
            echo "<td>".$riga['costo']."</td>";
            echo "<td><a href='/fl_modules/mod_live_abbonamenti/mod_opera.php?abb=".base64_encode($riga['abbId'])."' >Acquista</a></td>";
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>

<script>$('.paginazione').css('display','none');</script>