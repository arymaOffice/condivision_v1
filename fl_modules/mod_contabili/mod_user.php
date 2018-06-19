<?php

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

       
<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>  
  
    <label> creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il</label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="salva" id="filter_set" />
  
</form>

     
    </div>
<?php
	
	
	$file =  '';
	if(file_exists('./uploads/'.$_SESSION['number'].'_report_settimane.xls')) { $file = './uploads/'.$_SESSION['number'].'_report_settimane.xls'; } 
	if(file_exists('./uploads/'.$_SESSION['number'].'_report_settimane.xlsx')) { $file = './uploads/'.$_SESSION['number'].'_report_settimane.xlsx'; } 
	$file = ($file != '') ? "<p class=\"msg green\"><a href=\"$file\"><i class=\"fa fa-file\" aria-hidden=\"true\"></i> SCARICA ESTRATTO CONTO </a></p>" : '';

	echo $file;



	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>


<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
 <th style="width: 1%;"></th>
 <th>Punto Gioco</th>
 <th>Settimana</th>
 <th>Periodo</th>
 <th>Importo</th>
 <th>Allega Ricevuta</th>

</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"6\">Nessun Record Inserito</td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $tab_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			$file_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"../mod_dms/uploader.php?workflow_id=$tab_id&record_id=".$riga['id']."&NAme[]=Ricevuta Versamento\" title=\"Carica File\"><i class=\"fa fa-cloud-upload\"></i></a>"; 
 

			$anagrafica = GRD('fl_anagrafica',$_SESSION['anagrafica']);
			$concessione = (AFFILIAZIONI == 1)  ? " ".$anagrafica['numero_concessione'] : '';
				
			echo "<tr>"; 
			echo "<td ><span class=\"Gletter\"></span></td>"; 
            echo "<td>".@$anagrafica['ragione_sociale']." - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst($anagrafica['sede_legale'])." ".$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo_profilo[$anagrafica['tipo_profilo']]." $concessione </span></td>";
		    echo "<td><span class=\"color\">".$riga['numero_settimana']."</td>";
			echo "<td class=\"hideMobile\">dal ".mydate($riga['periodo_inizio'])." al ".mydate($riga['periodo_fine'])."</td>"; 
			echo "<td><span class=\"color\">&euro; ".$riga['importo']."</td>";
			echo "<td>$file_id</td>"; 
		    echo "</tr>"; 

	}
		

	echo "</table>";
	
?>
