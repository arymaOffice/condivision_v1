<?php 

require_once('../../fl_core/autentication.php');
if(!isset($_GET['evento_id'])) die('Manca Evento ID');	

include('fl_settings.php');

$nochat = 1;
include("../../fl_inc/headers.php");



	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
			
	?>


	 <table class="dati" summary="Dati">
      <tr>
        <th></th>
        <th>Id</th>
        <th>Foto</th>
   <?php if(defined('DISEGNO_PRODOTTI')) { ?>  <th>Disegno</th> <?php } ?>
   
 <th>Prodotto/Linea</th>
		<th>Produttore</th>
        <th></th>
  		<th></th>
      </tr>


	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	
	$prodotto_da = explode(',',$riga['produttore']);
	$produttori = '';
	foreach ($prodotto_da as $key => $value) {
		$produttori .= '<span class="msg green">'.$produttore[$value].'</span>';
	}

	$foto = (!file_exists($folder.$riga['id'].'.jpg')) ? '<i class="fa fa-camera" style="float: left;"></i>' : '<img src="'.$folder.$riga['id'].'.jpg" class="tumb" /> ';
		if(file_exists($folder.$riga['id'].'.jpg')) $foto .= '<br><a onclick="return conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder.$riga['id']).'.jpg">Elimina Foto</a>';
	$load = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
	'.$foto.'
	</form>';

	if(defined('DISEGNO_PRODOTTI')) { $foto2 = (!file_exists($folder2.$riga['id'].'.jpg')) ? '<i class="fa fa-camera" style="float: left;"></i>' : '<img src="'.$folder2.$riga['id'].'.jpg" class="tumb" /> ';
		if(file_exists($folder2.$riga['id'].'.jpg')) $foto .= '<br><a onclick="return conferma(\'Eliminare la foto?\');" href="../mod_basic/elimina.php?delFile='.urldecode($folder2.$riga['id']).'.jpg">Elimina Disegno</a>';
	$load2 = '
	<form action="mod_opera.php" method="post" class="dropzone"  id="my-awesome-dropzone" enctype="multipart/form-data">
    <input type="hidden" name="id" value="'.$riga['id'].'">
    <input type="hidden" name="folder" value="'.$folder2.'">
	'.$foto2.'
	</form>';}


	 echo "<tr>"; 		
			
			$colore = ($riga['attivo'] == 0) ? "class=\"tab_red\"" : "class=\"tab_green\"";  
			
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td>".$riga['id']."  </td>";
			echo "<td> $load </td>";
			if(defined('DISEGNO_PRODOTTI')) { echo "<td> $load2 </td>"; }
			echo "<td>
				<a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\">".ucfirst($riga['label'])."</a>
				<br><span class=\"msg blue\">".$prodotto_id[$riga['prodotto_id']]."</span></td>";		

			echo "<td>".$produttori."</td>";			

			
			echo "<td><a href=\"mod_inserisci.php?item_rel=0&amp;action=1&amp;id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>"; 
			echo "<td><a  href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
	
			
				
		    echo "</tr>";
	}

	

?>	

</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>
