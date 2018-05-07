<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
$categoria_faq_id = check(@$_GET['categoria_faq']);

 ?>

<body style=" background: #FFFFFF;">

<div style="padding: 0 10px;">
<form  action="mod_faq.php" method="get">
  
      <input name="categoria_faq" type="hidden" value="<?php echo check($_GET['categoria_faq']); ?>" />
      <div class="filter_box">  
      <label>Categoria faq</label>
      <select name="categoria_faq" class="select">
      <option value="-1">Tutti</option>
			<?php 
              
		    foreach($categoria_faq as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($categoria_faq_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"".$valores."\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>      
      </select>
      
      <input name="cerca" type="text" placeholder="<?php echo check($searchbox); ?>" <?php if(!isset($_GET['cerca'])) echo 'onclick="this.value=\'\'"'; ?> value="<?php if(isset($_GET['cerca'])) echo check($_GET['cerca']); ?>"   maxlength="200" class="txt_cerca" />
      <label onClick="form.submit();"><i class="fa fa-search"></i></label></div>
    </form>
</div>
<br class="clear" />
<script>

function loadFaq(id) {
	
	if(confirm("Vuoi caricare questa Risposta?")){
		window.parent.document.getElementById('messaggio').innerHTML = document.getElementById(id).innerText;
		window.parent.$("#tabs").tabs("select",0);
		}
	return false;
} 

</script>
<div id="content_scheda">


    
<?php
	$tipologia_main .= " OR (categoria_faq < 2 && id > 1) ";
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
    $query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>

<table class="dati" summary="Dati">
  <tr>
    <th>Oggetto</th>
    <th>Risposta</th>
  </tr>
  <?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	
	$tipoColor = ($riga['tipo_faq'] == 0) ? 'purple' : 'turquoise';

			echo "<tr>"; 				
			echo "<td style=\"width: 30%;\"><h1><i class=\"icon-question\"></i> ".$riga['id']." </h1><span class=\"msg $tipoColor\">".$tipo_faq[$riga['tipo_faq']]."</span><span class=\"msg blue\">".$categoria_faq[$riga['categoria_faq']]."</span><br>
			".mydate($riga['data_creazione'])." / ".$proprietario[$riga['account_id']]."</td>";	
			echo "<td style=\"width: 70%;\">
			<h2><a href=\"#\" title=\"Modifica\" onClick=\"loadFaq('faq".$riga['id']."');\" >".$riga['oggetto']."</a></h2>
			<div id=\"faq".$riga['id']."\">".strip_tags(html_entity_decode(converti_txt($riga['risposta'])))."</div></td>";	
		    echo "</tr>";
	}

	
	

?>
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>

</div>
</body></html>
