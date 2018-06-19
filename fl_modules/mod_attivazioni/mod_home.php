<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>

<label>Partner: </label>
  
<input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
   <div id="contenuto-dinamico"><?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?> </div>
<label>      Stato:</label> <select name="identificato" id="identificato">
            <option value="0">Mostra Tutti</option>
			<?php 
              
		     foreach($identificato as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($identitficato_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
    <label>   creato tra il</label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" /> 
   <label> e il </label><input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
       <input type="submit" value="Mostra" class="button" />

     
      
       </form>  </div>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
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
         <th>Documenti</th>
         <th>Modifica</th>
        
        <th>Agg./Creaz.</th>  
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $tab_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();

			if($riga['identificato'] == 1) $identificato_id =  "<span class=\"orange msg\">".$identificato[$riga['identificato']]."</span>";
			if($riga['identificato'] == 2) $identificato_id ="<span class=\"green msg\">".$identificato[$riga['identificato']]."</span>"; 
			if($riga['identificato'] == 3) $identificato_id ="<span class=\"red msg\">".$identificato[$riga['identificato']]."</span>"; 
		    $note = ($riga['note'] != "") ?  converti_txt($riga['note']) : "";
			
			if($documenti_count > 1 && trim($riga['nome_e_cognome']) != "" && trim($riga['user']) != "" && trim($riga['codice_fiscale']) != "" ){
			
			$tot_res++;
			$account = GRD('fl_account',$riga['proprietario']);
			$anagrafica = GRD('fl_anagrafica',@$account['anagrafica']);
			$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
			$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
			$concessione = (defined('AFFILIAZIONI') && isset($anagrafica['numero_concessione']))  ? $anagrafica['id'].".".$anagrafica['numero_concessione'] : '';
			$files = ($riga['id'] < 36630) ? 'http://www.betitalynetwork.it/condivision/fl_modules/mod_documentazione/?modulo=0&cat=6&contenuto='.$riga['id'] : "../mod_dms/uploader.php?PiD=".$dmsFolder."&workflow_id=$tab_id&record_id=".$riga['id'];
			if($riga['id'] < 36630) $documenti_count = '!';
			 $is_app = ($riga['is_app'] == 1) ? '<i class="fa fa-mobile"  aria-hidden="true"></i>' : '';
			
			echo "<tr>";
			echo "<td><span class=\"Gletter\"></span></td>"; 
		    echo "<td>".$user_ball." ".$user_check."</td>";
            echo "<td>".@$anagrafica['ragione_sociale']." - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg blue\">".@$marchio[$anagrafica['marchio']]."</span><span class=\"msg orange\">".@$tipo[$account['tipo']]." $concessione </span></td>";
			echo "<td>".ucfirst($riga['nome_e_cognome'])." <br /><span style=\"color: #cc0000;\">".ucfirst($riga['user'])."</span>"."</td>";		
			echo "<td>$identificato_id   $note</td>"; 

			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"$files\" title=\"Files per questo elemento\"><i class=\"fa fa-file\"></i>($documenti_count)</a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
		    echo "<td style=\"font-size: 8px;\" title=\"Creato da: ". @$proprietario[$riga['proprietario']]." - ultimo intevento di: ". @$proprietario[$riga['operatore']]." il ".mydatetime($riga['data_aggiornamento'])."\">C ".mydatetime($riga['data_creazione'])." $is_app  <br />A ".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td class=\"tasto\" ><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
		    echo "</tr>"; 
			
			
			} else { 
			$incomplete++;
			if($riga['data_aggiornamento'] < date("Y-m-d H:i:s",strtotime("-1 days")) && $documenti_count <= 1 && $riga['identificato'] == 1) { 
			$deleted++; 
			mysql_query("DELETE FROM $tabella WHERE id = ".$riga['id']." LIMIT 1", CONNECT);  
			mysql_query("DELETE FROM fl_dms WHERE workflow_id = 41 AND record_id = ".$riga['id'], CONNECT); 
			} 

			}
	}

	echo "</table>";
	

?>	


<p>* Ci sono delle note per questo nominativo</p>
<?php
$in = ''; 
if($incomplete > 0 ) $in = "($incomplete in compilazione)";
echo '<h2>Totale risultati: '.$tot_res.' '.$in.'</h2>'; 
if($deleted > 0) echo "<p>Sono state eliminate: <strong>$deleted</strong> richieste scadute.</p>"; ?>
