<?php

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
if($_SESSION['usertype'] > 1) exit;
?>


       
<?php  if(!isset($_GET['numero_settimana'])) { 
	$query = "SELECT id,numero_settimana, YEAR(data_creazione) AS anno FROM `$tabella` GROUP BY anno DESC, numero_settimana ASC;";
	$risultato = mysql_query($query, CONNECT);
	echo '<h2>Seleziona Settimana</h2>';
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
		if($riga['numero_settimana'] > 0) echo '<p><a class="button" href="./?numero_settimana='.$riga['numero_settimana'].'&anno='.$riga['anno'].'">Settimana '.$riga['numero_settimana'].'/'.$riga['anno'].'</a></p>';					
	}
  ?>

<?php } else { ?>

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
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
?>
<div id="results" class="green"></div>

<table class="dati" summary="Dati" style=" width: 100%;">
<tr>
 <th style="width: 1%;"></th>
  <th>Account</th>
 <th>Punto Gioco</th>
 <th>Settimana</th>
 <th>Periodo</th>
 <th>Importo</th>
 <th>Estratto</th>
  <th>Ricevuta</th>

 <th></th>
</tr>
<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"6\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $tab_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			$file_id = ($documenti_count < 1) ? '<i class="fa fa-cloud-upload"></i>' : "<a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"../mod_dms/uploader.php?workflow_id=$tab_id&record_id=".$riga['id']."&NAme[]=Ricevuta Versamento\" title=\"Carica File\"><i class=\"fa fa-cloud-upload\"></i></a>"; 

			$account = GRD('fl_account',$riga['proprietario']);
			$anagrafica = GRD('fl_anagrafica',@$account['anagrafica']);
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$concessione = (AFFILIAZIONI == 1)  ? " ".$anagrafica['numero_concessione'] : '';
			$file =  '';
			if(file_exists('./uploads/'.$riga['proprietario'].'_report_settimane.xls')) { $file = './uploads/'.$riga['proprietario'].'_report_settimane.xls'; } 
			if(file_exists('./uploads/'.$riga['proprietario'].'_report_settimane.xlsx')) { $file = './uploads/'.$riga['proprietario'].'_report_settimane.xlsx'; } 
			if(file_exists('./uploads/'.$riga['proprietario'].'_report_settimane.pdf')) { $file = './uploads/'.$riga['proprietario'].'_report_settimane.pdf 0'; } 
			$file = ($file != '') ? "<a href=\"$file\"><i class=\"fa fa-file\" aria-hidden=\"true\"></i></a>" : '';
			$elimina = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
				
			echo "<tr>"; 
			echo "<td ><span class=\"Gletter\"></span></td>"; 
		    echo "<td>".$user_ball." ".$user_check."</td>";
            echo "<td>".$account['anagrafica'].' '.$anagrafica['ragione_sociale']." - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo[$account['tipo']]." $concessione </span></td>";
		    echo "<td><span class=\"color\">".$riga['numero_settimana']."</td>";
			echo "<td class=\"hideMobile\">dal ".mydate($riga['periodo_inizio'])." al ".mydate($riga['periodo_fine'])."</td>"; 
			echo "<td><span class=\"color\">&euro; ".$riga['importo']."</td>";
			echo "<td>$file</td>";
			echo "<td>$file_id</td>"; 

			echo "<td  class=\"strumenti\">";
			echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Cliente \"> <i class=\"fa fa-search\"></i> </a>
			$elimina </td>";
		
		    echo "</tr>"; 
			}
		

	echo "</table>";
	
}
?>
