<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>

<div class="filtri" id="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>

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
	$step = 5000;
	$start = paginazione(CONNECT,'fl_attivazioni_old',$step,$ordine,$tipologia_main,1);
						
	echo $query = "SELECT $select FROM `fl_attivazioni_old`;";
	
	$risultato = mysql_query($query, CONNECT);


 		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
      <tr>
        <th><a href="./?ordine=0">Nominativo</a>/<a href="./?ordine=1">User</a></th>
     
        
        <th><a href="./?ordine=2">Partner</a></th>
        <th>Identificato</th>
        <th>Note</th>
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
			
		$queryUpdate = "INSERT INTO `fl_attivazioni` (`id`, `attivo`, `identificato`, `proprietario`, `marchio`, `user`, `nome_e_cognome`, `codice_fiscale`, `data_chiusura`, `note`, `data_creazione`, `data_aggiornamento`, `operatore`) 
		VALUES (".$riga['id'].", ".$riga['attivo'].", ".$riga['identificato'].", ".$riga['proprietario'].", ".$riga['marchio'].", '".$riga['user']."', '".$riga['nome_e_cognome']."', '".$riga['codice_fiscale']."', '".date('Y-m-d',$riga['data_chiusura'])."', '".$riga['note']."', '".date('Y-m-d',$riga['data_creazione'])."', '".date('Y-m-d H:i:s',$riga['data_aggiornamento'])."', '".$riga['operatore']."');";
		if(mysql_query($queryUpdate,CONNECT)) { $deleted++; mysql_query('DELETE FROM `fl_attivazioni_old` WHERE `id` = '.$riga['id'].' ',CONNECT); } else { echo mysql_error(); exit; } 
			
			/*$query_doc = "SELECT * FROM fl_dms WHERE workflow_id = $tab_id AND record_id = ".$riga['id']." ORDER BY label ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$documenti_count = mysql_affected_rows();

			$identificato = ($riga['identificato'] == 2) ? "<span class=\"green msg\">".$identificato[$riga['identificato']]."</span>" : "<span class=\"red msg\">".$identificato[$riga['identificato']]."</span>"; 
		    $note = ($riga['note'] != "") ?  "<span class=\"c-red\"><a href=\"?action=1&amp;sezione=".@$riga['sezione']."&amp;id=".$riga['id']."\" title=\"".convert_note($riga['note'])."\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></a></span>" : "";
			
			if(isset($_GET['incomplete']) || $documenti_count > 1 && trim($riga['nome_e_cognome']) != "" && trim($riga['user']) != "" && trim($riga['codice_fiscale']) != "" ){
			
			$tot_res++;
			
			if(isset($_GET['incomplete']) && $riga['data_creazione'] < date("Y-m-d",strtotime("-1 week")) && $documenti_count <= 1 && $riga['identificato'] == 1) { 
			
			$deleted++; $tot_res--; mysql_query("DELETE FROM $tabella WHERE id = ".$riga['id']." LIMIT 1", CONNECT);  
			
			} else {
		
			if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }	
			$dettegli_utenza = get_tipo_utenza($riga['proprietario']);
			echo "<td>".ucfirst($riga['nome_e_cognome'])." <br /><span style=\"color: #cc0000;\">".ucfirst($riga['user'])."</span>"."</td>";		
			echo "<td>".ucfirst(@$proprietario[$riga['proprietario']])."<br /><span style=\"color: #cc0000;\">".ucfirst(@$tipo[$dettegli_utenza[0]])." ".ucfirst(@$marchio[$dettegli_utenza[1]])."</a></td>"; 
			echo "<td>$identificato</td>"; 
			echo "<td>$note</td>"; 
			echo "<td><a data-fancybox-type=\"iframe\" class=\"fancybox\" href=\"../mod_dms/uploader.php?workflow_id=$tab_id&record_id=".$riga['id']."\" title=\"Carica File\"><i class=\"fa fa-file\"></i>($documenti_count)</a></td>"; 
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
			
			
		    echo "<td style=\"font-size: 8px;\" title=\"Creato da: ". @$proprietario[$riga['proprietario']]." - ultimo intevento di: ". @$proprietario[$riga['operatore']]." il ".mydatetime($riga['data_aggiornamento'])."\">A ".mydate($riga['data_creazione'])."<br />C ".mydatetime($riga['data_aggiornamento'])."</td>";
			echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
		    echo "</tr>"; }
			} else { $incomplete++; }*/
	}

	echo "</table>";
	echo $deleted. "Spostati";

?>	


<p>* Ci sono delle note per questo nominativo</p>
<?php echo '<h2>Totale risultati: '.$tot_res.'</h2>'; 
if($incomplete > 0) echo  "<div class=\"paginazione clear\"><a href=\"?identificato=1&incomplete\" title=\"Mostra Incomplete\">Incomplete: $incomplete</a></div>";
if($deleted > 0) echo "<p>Sono state eliminate: <strong>$deleted</strong> richieste scadute.</p>"; ?>
