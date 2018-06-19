<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>
<form method="get" action="" id="sezione_select">
   
 <div style="position: relative; background:  #F4F4F4; padding: 5px;"> Dati:
  
  <span style="position: relative;">
  <input type="text" id="cerca_big" name="cerca" value="<?php if(isset($_GET['cerca'])){ echo check($_GET['cerca']);} else { echo "Ricerca nome,user,cf"; } ?>" onFocus="this.value='';" style="width: 400px; " />
   <input type="hidden"  name="action" value="17" />
  
 Partner:  <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Partner"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div></span>
  

        Stato: <select name="identificato" id="identificato">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($identificato as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($identitficato_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
        
       <input type="submit" value="Mostra" class="button" />

      </div>
      
       </form>
<?php
	
	if(isset($_GET['cerca'])) {
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);

 		
	?>

 
 
  
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
      <tr>
       <th style="width: 1%;"></th>
  <th>Account</th>
      <th><a href="./?ordine=2">Partner</a></th>

 		<th><a href="./?ordine=0">Nominativo</a>/<a href="./?ordine=1">User</a></th>
        <th>Stato</th>
        <th>Note</th>
         <th>Documenti</th>
         <th>Modifica</th>
        
        <th>Agg./Creaz.</th>  
      </tr>
	  
	<?php 
	

	while ($riga = mysql_fetch_array($risultato)) 
	{					
			$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $tab_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();
			$identificato_id = '';
			 
			if($riga['identificato'] < 2) $identificato_id =  "<span class=\"orange msg\">".$identificato[$riga['identificato']]."</span>";
			if($riga['identificato'] == 2)  "<span class=\"green msg\">".$identificato[$riga['identificato']]."</span>"; 
			if($riga['identificato'] == 3)  "<span class=\"red msg\">".$identificato[$riga['identificato']]."</span>"; 
		    $note = ($riga['note'] != "") ?  "<span class=\"c-red\"><a href=\"?action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"".convert_note($riga['note'])."\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></a></span>" : "";
			
			
			$account = @GRD('fl_account',$riga['proprietario']);
			$anagrafica = @GRD('fl_anagrafica',@$account['anagrafica']);
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$concessione = (AFFILIAZIONI == 1)  ? " ".$anagrafica['numero_concessione'] : '';
			
			echo "<tr>";
			echo "<td ><span class=\"Gletter\"></span></td>"; 
		    echo "<td>".$user_ball." ".$user_check."</td>";
            echo "<td>".@$anagrafica['ragione_sociale']." - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo_profilo[$anagrafica['tipo_profilo']]." $concessione </span></td>";
			echo "<td>".ucfirst($riga['nome_e_cognome'])." <br /><span style=\"color: #cc0000;\">".ucfirst($riga['user'])."</span>"."</td>";		
			echo "<td>$identificato_id</td>"; 
			echo "<td>$note</td>"; 
			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"../mod_dms/uploader.php?workflow_id=$tab_id&record_id=".$riga['id']."\" title=\"Files per questo elemento\"><i class=\"fa fa-file\"></i>($documenti_count)</a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
		    echo "<td style=\"font-size: 8px;\" title=\"Creato da: ". @$proprietario[$riga['proprietario']]." - ultimo intevento di: ". @$proprietario[$riga['operatore']]." il ".mydatetime($riga['data_aggiornamento'])."\">A ".mydatetime($riga['data_creazione'])."<br />C ".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
		    echo "</tr>"; }
			
		

	echo "</table>";
	

?>	


<?php } else { echo '<h3>Inserire estremi per la ricerca</h3>'; } ?>