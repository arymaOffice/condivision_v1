<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>


<p class="show_filtri"><a href="#" class="filterToggle"><i class="fa fa-filter"></i></a></p>
<div class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>  <!--Partner: 
  
  <span style="position: relative;">
  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
--> 
    
  <!--  Stato:
    <select name="status_anagrafica" id="status_anagrafica">
      <option value="0">Mostra Tutti</option>
      <?php 
              
		     foreach($status_anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_anagrafica_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select>-->
    
    <label>  Stato account:</label>
    <select name="status_account" id="status_account">
      <option value="-1">Tutti </option>
      <?php 
            $selected = (@$stato_account_id == 1) ? " selected=\"selected\"" : "";
		    echo "<option value=\"1\" $selected>Attivi</option>\r\n"; 
			$selected = (@$stato_account_id == 0) ? " selected=\"selected\"" : "";
			echo "<option value=\"0\" $selected>Sospesi</option>\r\n"; 
			
		 ?>
    </select>
    <label> creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il</label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	

 		
	?>
<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
  <th style="width: 1%;"></th>
  <th><a href="./?ordine=1">Ragione Sociale</a></th>
  
 <?php if(defined('PRIMANOTA')) { ?> <th class="hideMobile">Prima nota</th> <?php } ?>
 <?php if(defined('F24')) { ?> <th>F24</th> <?php } ?>
   <th>Archivio Digitale</th>
    <th>Cartella Condivisa</th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			if($riga['status_anagrafica'] == 3) { 
			$colore = "class=\"tab_green\"";  
			} else if($riga['status_anagrafica'] != 4) { $colore = "class=\"tab_orange\""; 
			} else { $colore = "class=\"tab_red\""; }
			
			$account = get_account($riga['id']);
			if($account['id'] > 0)  {
			$documenti = "<a href=\"../mod_dms/?workflow_id=0&proprietario=".$account['id']."&c=NA==&\"><i class=\"fa fa-cloud-download\"></i></a>";	 
			$folder_condiviso = "<a href=\"../mod_dms/?workflow_id=0&proprietario=".$account['id']."&c=Mw%3D%3D&\"><i class=\"fa fa-cloud-download\"></i></a>";	 
			} else {
			$documenti = '';
			$folder_condiviso ='';
			}


			echo "<tr>"; 
			$nominativo = ($riga['ragione_sociale'] != '') ? ucfirst($riga['ragione_sociale']) : ucfirst($riga['nome']).' '.ucfirst($riga['cognome']);		
			$dettegli_utenza = get_tipo_utenza($riga['proprietario']);
			echo "<td $colore><span class=\"Gletter\"></span></td>"; 
			echo "<td><span class=\"color\">$nominativo</span><br><span class=\"msg orange\">".$tipo_profilo[$riga['tipo_profilo']]."</span><span class=\"msg gray\">".$pagamenti_f24[$riga['pagamenti_f24']]."</span> - P.iva ".$riga['partita_iva']."</td>";
			if(defined('PRIMANOTA'))  echo "<td><a href=\"../mod_conto/?cmy=".base64_encode($riga['id'])."\"><i class=\"fa fa-file-text-o\"></i></a></td>"; 
			if(defined('F24'))  echo "<td><a href=\"../mod_f24/?cmy=".base64_encode($riga['id'])."\"><i class=\"fa fa-file-pdf-o\"></i></a></td>"; 
			echo "<td>$documenti</td>";
			echo "<td>$folder_condiviso</td>";
			echo "<td  class=\"hideMobile\" ><a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?external&action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."&nominativo=".$riga['ragione_sociale']."\" title=\"Scheda di stampa ".ucfirst($riga['ragione_sociale'])."\"> <i class=\"fa fa-print\"></i> </a></td>";
		
		    echo "</tr>"; 
	}

	echo "</table>";
	

?>
