<?php	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
  ?>


<div id="filtri" class="filtri"> 
    <h2>Filtri</h2>
    
<form method="get" action="./" id="fm_filtri">
<label>Anno Fiscale</label>
<?php while($anno >= ($anno_corrente-3)){  
	$checked = ($_SESSION['anno_fiscale']  == $anno) ? 'checked' : ''; 
	echo  '<input '.$checked.' type="radio" onClick="form.submit();" name="anno_fiscale" id="'.$anno.'" value="'.$anno.'" /><label for="'.$anno.'">'.$anno.'</label>';  
	$anno--; } ?>
</form>


<form method="get" action="./" id="fm_filtri">
    
  
   <label>Periodo  da</label>
    <input type="text" name="data_da" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
   <label> a</label>
    <input type="text" name="data_a" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="button" />
</form>

  
    
    </div>
<?php
	

	
	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	
	?>
<style>
.dati th { background: #FFDAB1 ; padding: 5px; }
</style>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th><a href="./?ordine=0&">Data</a></th>
  <th><a href="./?ordine=1&">Cliente</a></th>
  <th>Oggetto</th>
  <th>Imponibile</th>
  <th>Iva</th>
  <th>Totale</th>
  <th  class="hideMobile"></th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	$i = 1;
	$imponibile = 0;
	$imposta = 0;
	$totale = 0;
	$valuta = array("&euro;");
	$valuta_totale = 0;
	$tot_imponibile_sospeso = 0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\"><h2>No records</h2></td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			

			$destinato_a = pulisci($riga['ragione_sociale']);
       		$filename = $tipo_doc_acquisto[$riga['tipo_doc_acquisto']].' '.$riga['numero_documento']."-".$riga['data_documento'].'-'.$destinato_a.'.pdf';

			$fattura = (@$riga['fattura_elettronica'] == 1) ? '<span class="msg orange">DOCUMENTO ELETTRONICO</span>' :  "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"stampa_documento.php?did=".base64_encode($riga['id'])."\" title=\"Visualizza\"> <i class=\"fa fa-file-text\"></i> [ID: ".$riga['id']."]</a>"; 
			$stampare = (!file_exists($folder.$filename) || $riga['fattura_elettronica'] == 1) ? '' : '<input id="'.$riga['id'].'" onClick="countFields(1);" type="checkbox" name="prints[]" value="'.$folder.$filename.'" checked="checked" /><label for="'.$riga['id'].'" style="margin: 0;"><i class="fa fa-download" aria-hidden="true"></i></label>';
			$count++;


			$colore = '';
			$pagato = '';
			$delete = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			$colore = ($riga['pagato'] == 0 ) ? "class=\"tab_orange\"" : "class=\"tab_green\""; 
			$imponibile += $riga['imponibile'];
			$imposta += $riga['iva'];
			$totale_documento = $riga['imponibile']+$riga['non_imponibile']+$riga['iva'];
			$totale += $totale_documento;
			$valuta_totale = $riga['valuta'];
			$send = "<a href=\"mod_send.php?send_id=".$riga['id']."\" data-fancybox-type=\"iframe\" title=\"Invia\" class=\"  fancybox_view_small\"  ><i class=\"fa fa-paper-plane-o\"></i></a>";

			if($riga['pagato'] == 1){
			$pagato = '<span class="green msg">SALDATO</span>';
			} else {
			$pagato = '<a href="mod_opera.php?pagato='.$riga['id'].'" class="red msg">NON SALDATO</a>';
			$tot_imponibile_sospeso += $imponibile;
			}
			$rifOrdine = (isset($riga['ordine_id']) && $riga['ordine_id'] != '') ? '<br>Rif. Ordine: '.$riga['ordine_id'] : '';
			$ragione_sociale = ($riga['ragione_sociale'] != '') ? substr($riga['ragione_sociale'],0,50) : $anagrafica_id[$riga['anagrafica_id']];
			echo "<tr>"; 
		
			echo "<td $colore><span class=\"Gletter\"></span></td>";
			echo "<td><span class=\"msg green\">".$tipo_doc_acquisto[$riga['tipo_doc_acquisto']]."</span><h2 style=\"margin: 0; padding: 0; font-size: 200%;\">".$riga['numero_documento']." </h2>".mydate($riga['data_documento'])."</td>"; 
			echo "<td>$ragione_sociale $rifOrdine </td>";
			echo "<td><span class=\"printonly\">".strip_tags($riga['oggetto_documento'])."</span><input type=\"text\" value=\"".strip_tags($riga['oggetto_documento'])."\" name=\"oggetto_documento\" class=\"updateField hideMobile\" data-rel=\"".$riga['id']."\" />
			<span class=\"msg blue\">".$centro_di_costo[$riga['centro_di_costo']]."</span> <span class=\"msg gray\">".$metodo_di_pagamento[$riga['metodo_di_pagamento']]."</span>  $pagato </td>";
			echo "<td>".$valuta[$riga['valuta']]." ".numdec($riga['imponibile'],2)."</td>";
			echo "<td>".$valuta[$riga['valuta']]." ".numdec($riga['iva'],2)."</td>";
			echo "<td>".$valuta[$riga['valuta']]." ".numdec($totale_documento,2)."</td>";
			echo "<td><a href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Dettaglio Documento\"> <i class=\"fa fa-pencil\"></i> </a>  <a href=\"mod_send.php?send_id=".$riga['id']."\" data-fancybox-type=\"iframe\" title=\"Invia\" class=\"  fancybox_view_small\"  ><i class=\"fa fa-paper-plane-o\"></i></a>";
			if(defined('MAGAZZINO')) echo "<a href=\"../mod_giacenze/mod_load.php?dettagli=".$riga['numero_documento']." del ".mydate($riga['data_documento'])."&parent_id=".$riga['id']."&ordine_id=".$riga['ordine_id']."\" title=\"Dettaglio Voci\"> <i class=\"fa fa-cubes\"></i> </a>";
			echo "</td>";
			echo "<td  class=\"hideMobile strumenti\">$fattura </td>";
			echo "<td class=\"hideMobile\">$stampare</td>"; 
			echo "<td>$delete</td>"; 
		    echo "</tr>"; 

	}
    echo "<tr><td colspan=\"9\"></td></tr>";
	echo "<tr><td colspan=\"5\"></td>";
	echo "<td>".$valuta[$valuta_totale ]." ".numdec($imponibile,2)."</td>";
	echo "<td>".$valuta[$valuta_totale ]." ".numdec($imposta,2)."</td>";
	echo "<td>".$valuta[$valuta_totale ]." ".numdec($totale,2)."</td>";
	echo "<td>&euro; ".numdec($tot_imponibile_sospeso,2)." (Sospesi)</td>";
	echo "<td></td>
	<td></td>
	
	</tr>";
	echo "</table>";
	
set_val($tda_id.'_spese_netto_'.$_SESSION['anno_fiscale'],$imponibile);
set_val($tda_id.'_spese_sospeso_'.$_SESSION['anno_fiscale'],$tot_imponibile_sospeso);


?>

<?php if(isset($_GET['ordiniCreati'])) { $ordiniCreati =  check($_GET['ordiniCreati']); ?>
<div class="box" id="thanks">
<i class="fa fa-<?php echo ($ordiniCreati < 1) ? 'meh-o' : 'truck'; ?>" aria-hidden="true" style="font-size: 1000%;"></i><br>
<h1><?php echo ($ordiniCreati < 1) ? 'Qualcosa non quadra ' : 'Ottimo lavoro '; ?> <?php echo $_SESSION['nome']; ?>!</h1>
<p> Sono stati emessi <?php echo $ordiniCreati; ?> ordini!</p>
<p> <?php echo ($ordiniCreati < 1) ? 'Prova a rivedere i fornitori assegnati!' : 'Puoi procedere ad inviarli al fornitore.'; ?> </p>
</div>

<a href="#thanks" id="hidden_link" class="fancybox_view">Quanti ordini ho emesso?</a>
<script type="text/javascript">
    $(document).ready(function() {
        $("#hidden_link").fancybox().trigger('click');
    });
</script>
<?php } ?>