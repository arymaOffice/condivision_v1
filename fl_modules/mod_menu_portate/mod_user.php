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
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	

	/*echo '<a target="_parent" href="../mod_menu_portate/?evento_id='.check($_GET['evento_id']).'">
	<h1 style="font-size: 200%;"><i class="fa fa-sticky-note" aria-hidden="true"></i></h1>
	Crea o gestisci i Menù</a>';

	<a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
	*/

?>

<table class="dati" summary="Dati">
  <tr>
    <th></th>
    <th>Descrizione del Menù</th>
    <th>Portate Selezionate</th>
    <th>Prezzo</th>
    <th>Creato il</th>
    <th></th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"6\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$attivo = ($riga['confermato'] == 0) ? 'tab_orange' : 'tab_green';
	$statusMenu = @$stato_menu_portate[$riga['stato_menu_portate']];

			echo "<tr>"; 				
			echo "<td class=\"$attivo\"></td>";
			echo "<td>".$riga['descrizione_menu']."</td>";	
			echo "<td>--</td>";	
			echo "<td>".$riga['prezzo_base']."</td>";	
			echo "<td>".mydate($riga['data_creazione'])."</td>";
			echo "<td>
			<a href=\"".ROOT.$cp_admin."fl_app/MenuElegance/?menuId=".$riga['id']."&eventoId=$evento_id&schedaId=$schedaId\" title=\"Componi Menu\" target=\"_parent\"><i class=\"fa fa-cutlery\" aria-hidden=\"true\"></i></a>
			<a data-fancybox-type=\"iframe\" class=\"facyboxParent\" href=\"mod_stampa.php?menuId=".$riga['id']."\" title=\"Stampa Menu\" ><i class=\"fa fa-print\" aria-hidden=\"true\"></i></a>";
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
