<?php	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI']; ?>


<div id="filtri" class="filtri"> 
    <h2>Filtri</h2>
    
<form method="get" action="./" >
<label>Anno Fiscale</label>
<?php while($anno >= ($anno_corrente-3)){  $checked = ($_SESSION['anno_fiscale']  == $anno) ? 'checked' : ''; echo  '<input '.$checked.' type="radio" onClick="form.submit();" name="anno_fiscale" id="'.$anno.'" value="'.$anno.'" /><label for="'.$anno.'">'.$anno.'</label>';  $anno--; } ?>

</form>


<form method="get" action="./" id="fm_filtri">
<label>Pagato</label>
<input type="radio" name="pagato" id="pagato1" value="1" /><label for="pagato1">SALDATI</label>
<input type="radio" name="pagato" id="pagato0" value="0" /><label for="pagato0">SOSPESI</label>
<input type="radio" name="pagato" id="pagato" value="-1" /><label for="pagato">TUTTO</label>

 <label>Periodo  da</label>   <input type="text" name="data_da"  value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
   <label>  a </label>
   <input type="text" name="data_a" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
   <input type="submit" value="Mostra" class="button" />
  
</form>

</div>


<?php
	

	
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	?>


<style>
.dati th { font-size: 120%; }
</style> 


<script type="text/javascript">
function checkAllFields(ref)
{
var chkAll = document.getElementById('checkAll');
var checks = document.getElementsByName('prints[]');
var boxLength = checks.length;
var allChecked = false;
var totalChecked = 0;
	if ( ref == 1 )
	{
		if ( chkAll.checked == true )
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = true;
		}
		else
		{
			for ( i=0; i < boxLength; i++ )
			checks[i].checked = false;
		}
	}
	else
	{
		for ( i=0; i < boxLength; i++ )
		{
			if ( checks[i].checked == true )
			{
			allChecked = true;
			continue;
			}
			else
			{
			allChecked = false;
			break;
			}
		}
		if ( allChecked == true )
		chkAll.checked = true;
		else
		chkAll.checked = false;
	}
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	countFields(1);
}
  
  
  function countFields(ref)
{
var checks = document.getElementsByName('prints[]');
var boxLength = checks.length;
var totalChecked = 0;
	for ( j=0; j < boxLength; j++ )
	{
		if ( checks[j].checked == true )
		totalChecked++;
	}
	$('#counter').val('Stampa ' + totalChecked + ' documenti');
}

    </script>
<form action="esporta.php" method="post">
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th class="hideMobile" style="width: 1%;"></th>
  <th>Documento</th>
  <th>Cliente</th>
  <th style="width: 30%;">Dettagli</th>
  <?php if($tdv_id != 1) echo '<th>Imposta</th>'; ?>
  <?php if($tdv_id != 1) echo '<th>Iva</th>'; ?>
  <th>Totale</th>
  <th  class="hideMobile"></th>
        <th scope="col" class="hideMobile">Documento </th>          
       <th scope="col" class="hideMobile">
       <input onclick="checkAllFields(1);" id="checkAll"  name="checkAll" type="checkbox" checked  />
       <label for="checkAll" style="margin: 0;"><i class="fa fa-check" aria-hidden="true"></i></label></th>
         </tr>
</tr>
<?php 
	
	function get_imponibile($fattura_id){
		
	$query = "SELECT * FROM `fl_doc_vendita_voci` WHERE id > 1 AND `fattura_id` = $fattura_id";
	$risultato = mysql_query($query, CONNECT);
	$imponibile = 0;
	
	while ($riga = mysql_fetch_assoc($risultato)) {
		
		$imponibile  += (defined('importi_lordi')) ? $riga['importo'] : $riga['importo']*$riga['quantita'];
		
		}
		return $imponibile;
	}
	function get_imposta($fattura_id){
		
	$query = "SELECT * FROM `fl_doc_vendita_voci` WHERE id > 1 AND `fattura_id` = $fattura_id";
	$risultato = mysql_query($query, CONNECT);
	$imposta = 0;
	
	while ($riga = mysql_fetch_assoc($risultato)) {
		
		$imposta  += $riga['imposta'];
		
		}
		return $imposta;
	}

	
	$i = 1;
	$tot_imponibile = 0;
	$tot_imponibile_sospeso = 0;
	$tot_imposta = 0;
	$totale_documenti = 0;
	$valuta = array("&euro;","&euro;");
	$valuta_totale = 0;
	$count = 0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\"><h2>No records</h2></td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			$destinato_a = pulisci($riga['ragione_sociale']);
       		$filename = $riga['numero_documento']."-".$riga['data_documento'].'-'.$destinato_a.'.pdf';

			$fattura = ($riga['fattura_elettronica'] == 1) ? '<span class="msg orange">FATTURA ELETTRONICA</span>' :  "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"stampa_documento.php?did=".base64_encode($riga['id'])."\" title=\"Visualizza\"> <i class=\"fa fa-file-text\"></i> [ID: ".$riga['id']."]</a>"; 
			$stampare = (!file_exists($folder.$filename) || $riga['fattura_elettronica'] == 1) ? '' : '<input id="'.$riga['id'].'" onClick="countFields(1);" type="checkbox" name="prints[]" value="'.$folder.$filename.'" checked="checked" /><label for="'.$riga['id'].'" style="margin: 0;"><i class="fa fa-download" aria-hidden="true"></i></label>';
			$count++;
			
			$colore = '';
			$pagato = '';
			$delete = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			$modifica = "<a href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Dettaglio movimentazione\"> <i class=\"fa fa-search\"></i> </a>";
			$send =($riga['tipo_doc_vendita'] == 1) ? '' : "<a href=\"mod_send.php?send_id=".$riga['id']."\" data-fancybox-type=\"iframe\" title=\"Invia\" class=\"  fancybox_view_small\"  ><i class=\"fa fa-paper-plane-o\"></i></a>";

			$colore = ($riga['pagato'] == 0 ) ? "class=\"tab_orange\"" : "class=\"tab_green\""; 
			
			$imponibile = get_imponibile($riga['id']);
			$imposta = get_imposta($riga['id']);
			$totale_documento = $imponibile+$imposta;
			
	

			$tot_imponibile += $imponibile;
			$tot_imposta += $imposta;
			$totale_documenti += $totale_documento;
			
			if($riga['pagato'] == 1){
			if($_SESSION['usertype'] != 0) $modifica = '';
			$delete  = '';
			$pagato = '<span class="green msg">SALDATO</span>';
			} else {
			$pagato = '<a href="mod_opera.php?pagato='.$riga['id'].'" class="red msg">NON SALDATO</a>';
			if($riga['tipo_doc_vendita'] > 2) $pagato = '<a href="mod_opera.php?converti='.$riga['id'].'" class="orange msg" onclick="return conferma(\'Convertire in Fattura?\');">CONVERTI IN FATTURA</a>';
			$tot_imponibile_sospeso += $totale_documento;
			}


			echo "<tr>"; 
				
			echo "<td $colore><span class=\"Gletter\"></span></td>";
			echo "<td><span class=\"msg green\">".$tipo_doc_vendita[$riga['tipo_doc_vendita']]."</span><h2 style=\"margin: 0; padding: 0; font-size: 200%;\">".$riga['numero_documento']."</h2>
			<p style=\"margin: 0; padding: 0;\">".mydate($riga['data_documento'])."</p></td>";
			echo "<td>".substr($riga['ragione_sociale'],0,50)."</td>";
			echo "<td><span class=\"printonly\">".strip_tags($riga['oggetto_documento'])."</span><input type=\"text\" value=\"".strip_tags($riga['oggetto_documento'])."\" name=\"oggetto_documento\" class=\"updateField hideMobile\" data-rel=\"".$riga['id']."\" />
			<span class=\"msg blue\">".$centro_di_ricavo[$riga['centro_di_ricavo']]."</span> <span class=\"msg gray\">".$metodo_di_pagamento[$riga['metodo_di_pagamento']]."</span>  $pagato </td>";
			if($tdv_id != 1) echo "<td>&euro; ".numdec($imponibile,2)."</td>";
			if($tdv_id != 1) echo "<td>&euro; ".numdec($imposta,2)."</td>";
			echo "<td >&euro; ".numdec($totale_documento,2)."</td>";
			echo "<td  class=\"hideMobile strumenti\">$modifica $send $delete </td>"; 
			echo "<td  class=\"hideMobile strumenti\">$fattura</td>";
			echo "<td class=\"hideMobile\">$stampare</td>"; 

		    echo "</tr>"; 

	}
    echo "<tr class=\"hideMobile\" ><td colspan=\"8\"></td></tr>";
	echo "<tr><td colspan=\"3\"></td>";
	echo "<td class=\"hideMobile\"  colspan=\"1\"></td>";
	if($tdv_id != 1)  echo "<td>&euro; ".numdec($tot_imponibile,2)."</td>";
	if($tdv_id != 1)  echo "<td>&euro; ".numdec($tot_imposta,2)."</td>";
	echo "<td>&euro; ".numdec($totale_documenti,2)."</td>";
	echo "<td>&euro; ".numdec($tot_imponibile_sospeso,2)." (Sospesi)</td>
	<td class=\"hideMobile\" ></td><td class=\"hideMobile\" ></td>
	
	</tr>";
	echo "</table>";
echo '<p  class="hideMobile" style="text-align: right; padding: 20px 0;">
<input type="submit" id="counter" value="Scarica '.$count.' documenti " class="button">
</p></form>';


echo '<span style="float: right; clear: both;">Prossima numerazione: '.$nextNumber.'</span>';

set_val($tdv_id.'_fatturato_netto_'.$_SESSION['anno_fiscale'],$tot_imponibile);
set_val($tdv_id.'_sospesi_netto_'.$_SESSION['anno_fiscale'],$tot_imponibile_sospeso);
?>
