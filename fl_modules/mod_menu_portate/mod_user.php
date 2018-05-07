<?php 

require_once('../../fl_core/autentication.php');
if(!isset($_GET['evento_id'])) die('Manca Evento ID');	
include('fl_settings.php');


$evento_id = check($_GET['evento_id']);	
$schedaId = check($_GET['id']);	


$nochat = 1;
include("../../fl_inc/headers.php");

?>



<?php
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine;";
	$risultato = mysql_query($query, CONNECT);
	

	if(defined('MULTI_MENU')) { echo '<a target="_parent" href="../mod_menu_portate/?evento_id='.check($_GET['evento_id']).'">
	<h1 style="font-size: 200%;"><i class="fa fa-sticky-note" aria-hidden="true"></i></h1>
	Crea o gestisci i Menù</a>';
	}
	

?>

<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Descrizione del Menù</th>
    <th>Food Cost</th>
    <th>Prezzo Vendita</th>
    <th>Ultimo Aggiornamento</th>
    <th>Riepilogo</th>
    <th>Fabbisogno</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"6\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['confermato'] == 0) ? 'tab_orange' : 'tab_green';
	$statusMenu = @$stato_menu_portate[$riga['stato_menu_portate']];
	$confermato = ($riga['confermato'] == 0) ? '<span class="msg orange"><a href="mod_opera.php?conferma='.$riga['id'].'" onclick="return conferma(\'Confermare menù?\');"">Da Confermare</a></span>' : '<span class="msg green">Confermato</span>';
	$bolla = (defined('MULTI_LOCATION')) ? "<a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"./mod_bolla.php?preview&evento_id=".$riga['evento_id']."&menuId=".$riga['id']."\" title=\"Bolla\" > <i class=\"fa fa fa-truck\" aria-hidden=\"true\"></i></a>" : '';
			echo "<tr>"; 				
			echo "<td class=\"$attivo\"></td>";
			echo "<td>".$riga['descrizione_menu']." $confermato<br>$statusMenu</td>";	
			echo "<td>&euro; ".@$riga['food_cost']." <a href=\"mod_opera.php?menuId=".$riga['id']."\"><i class=\"fa fa-refresh\" aria-hidden=\"true\"></i></a></td>";	
			echo "<td>&euro; ".@$riga['prezzo_base']."</td>";	
			echo "<td>".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td><a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"./mod_configura.php?preview&evento_id=".$riga['evento_id']."&menuId=".$riga['id']."\" title=\"Ripilogo e Configurazione\" > <i class=\"fa fa-clipboard\" aria-hidden=\"true\"></i></a>$bolla</td>";
			echo "<td><a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"./mod_fabbisogno.php?evento_id=".$riga['evento_id']."&menuId=".$riga['id']."\" title=\"Calcola Fabbisogno\" > <i class=\"fa fa-cart-plus\" aria-hidden=\"true\"></i></a></td>";
			echo "<td>
			<a href=\"".ROOT.$cp_admin."fl_app/MenuElegance/?menuId=".$riga['id']."&eventoId=$evento_id&schedaId=$schedaId\" title=\"Componi Menu\" target=\"_parent\"><i class=\"fa fa-cutlery\" aria-hidden=\"true\"></i></a>
			<a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"mod_stampa.php?menuId=".$riga['id']."&evento_id=$evento_id\" title=\"Stampa Menu\" ><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
		    echo "</tr>";
	}

	
	

?>
</table>

<p>NB: Dopo la definizione del menù o qualsiasi modifica apportata il menù va confermato. Clicca su "Da Confermare" per procedere.</p>