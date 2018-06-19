<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>


   
 <div id="filtri" class="filtri"> 
<form method="get" action="" id="sezione_select">
 <h2>Filtri</h2>
 
<label>Partner: </label>
  
  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
  

       <label> Stato: </label>
       <select name="status_pagamento" id="status_pagamento">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($status_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_pagamento_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
      <label> creato tra il </label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> 
      <label>e il</label>
       <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
       <input type="submit" value="Mostra" class="button" />

      
       </form>
       </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	echo $query = "SELECT $select FROM `fl_pagamenti_old` ORDER BY id ASC";
	
	$risultato = mysql_query($query, CONNECT);
 		
	?>

 
 <h2>MODULO IN MANUTENZIONE</h2>
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th style="width: 1%;"></th>
  <th>Account</th>
      <th><a href="./?ordine=2">Partner</a></th>
                  <th>User</th>
     
        <th scope="col">Stato</th>
        
        <th scope="col">Data</th>
          <th scope="col">Importo</th>
         <th scope="col">Ricevuta</th>
         <th scope="col">Modifica</th>
        <th scope="col">Creaz./Agg.</th>   
      </tr>
	<?php 
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
			
			
			if($_SESSION['number'] == 1) {
				$query = "INSERT INTO `fl_ricevute` (`id`, `status_pagamento`, `proprietario`, `marchio`, `user`, `importo`, `data_pagamento`, `note`, `data_creazione`, `data_aggiornamento`, `operatore`) 
			VALUES ('".$riga['id']."', '".$riga['status_pagamento']."', '".$riga['proprietario']."', '".$riga['marchio']."', '".$riga['user']."', '".$riga['importo']."', '".date ('Y-m-d',$riga['data_pagamento'])."', '".$riga['note']."', '".date ('Y-m-d H:i:s',$riga['data_creazione'])."', '".date ('Y-m-d H:i:s',$riga['data_aggiornamento'])."', '".$riga['operatore']."');";
			if(mysql_query($query, CONNECT)) echo "OK <br>";
			echo mysql_error();
			}
			
			/*$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $workflow_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();

			$status_pagamento_txt = ($riga['status_pagamento'] == 2) ? "<span class=\"green msg\">".$status_pagamento[$riga['status_pagamento']]."</span>" : "<span class=\"orange msg\">".$status_pagamento[$riga['status_pagamento']]."</span>"; 
		    $note = ($riga['note'] != "") ?  "<span class=\"c-red\"><a href=\"?action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"".convert_note($riga['note'])."\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></a></span>" : "";
			
			if(isset($_GET['incomplete']) || $documenti_count > 0 && trim($riga['user']) != "" ){
			
			$tot_res++;
			
			if(isset($_GET['incomplete']) && $riga['data_creazione'] < date("Y-m-d",strtotime("-1 day")) && $documenti_count <= 1 && $riga['status_pagamento'] == 1) { 
			
			$deleted++; $tot_res--; mysql_query("DELETE FROM $tabella WHERE id = ".$riga['id']." LIMIT 1", CONNECT);  
			
			} else {
				
			$account = GRD('fl_account',$riga['proprietario']);
			$anagrafica = GRD('fl_anagrafica',@$account['anagrafica']);
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$concessione = (AFFILIAZIONI == 1)  ? " ".$anagrafica['numero_concessione'] : '';

		
			echo "<tr>";
			echo "<td ><span class=\"Gletter\"></span></td>"; 
		    echo "<td>".$user_ball." ".$user_check."</td>";
            echo "<td>".@$anagrafica['ragione_sociale']." - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo_profilo[$anagrafica['tipo_profilo']]." $concessione </span></td>";
			echo "<td>".ucfirst($riga['user'])."</td>";		
			echo "<td>".$status_pagamento_txt."</td>"; 
			echo "<td>".mydate($riga['data_pagamento'])."</td>"; 
			echo "<td> &euro; ".$riga['importo']."</td>"; 
			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"../mod_dms/uploader.php?PiD=".base64_encode(11)."&NAme[]=Ricevuta pagamento&workflow_id=$workflow_id&record_id=".$riga['id']."\" title=\"Files per questo elemento\"><i class=\"fa fa-file\"></i>($documenti_count)</a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
		    echo "<td  style=\"font-size: 8px;\"  title=\"Creato da: ". @$proprietario[$riga['proprietario']]." - ultimo intevento di: ". @$proprietario[$riga['operatore']]." il ".mydatetime($riga['data_aggiornamento'])."\">C ".mydatetime($riga['data_creazione'])."<br />A ".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		    echo "</tr>";
			
			}} else { $incomplete++; }*/
			
	}

	echo "</table>";
	
mysql_close(CONNECT);
?>	


<p>* Ci sono delle note per questo nominativo</p>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; ?>
