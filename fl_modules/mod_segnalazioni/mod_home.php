<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = ROOT.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
?>


<h1> Segnalazioni <?php  echo $tipo_segnalazione[$tipo_segnalazione_id]; ?>   </h1>

<div id="filtri" class="filtri"> 

<form method="get" action="" id="fm_filtri">
<h2 class="filter_header"> Filtri</h2>  

<label> Operatore: </label>
    <input type="text" id="operatore_text" name="operatore_text" value="<?php if(isset($_GET['operatore_text'])){ echo check($_GET['operatore_text']);} else { echo "Inserisci il Testo"; } ?>" onFocus="this.value=''; operatore.value=''" onkeydown=""  accesskey="a" tabindex="1"   onkeyup="return caricaProprietario(this.value,'contenuto-dinamico','operatore');" maxlength="200" class="txt_cerca" />
      <div id="contenuto-dinamico"> 
      <?php if(isset($_GET['operatore'])){ echo '<input type="hidden" name="operatore" value="'.$_GET['operatore'].'" />'; } ?>
    </div> 
  
   <label> Stato: </label>
    <select name="status_segnalazione" id="status_segnalazione">
      <option value="0">Mostra Tutti</option>
      <?php 
              
		     foreach($status_segnalazione as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_segnalazione_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
    </select>
    <label> creato tra il </label>
    <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    <label> e il </label>
    <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
   <input type="hidden"  value="<?php  echo $tipo_segnalazione_id;  ?>" name="tipo_segnalazione" />
    <input type="submit" value="Mostra" class="button" />
      
       </form>
       
</div>

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
  
  	 <table class="dati">
      <tr>
	   <th>#</th>
        <th><a href="./?ordine=0">Nominativo</a>/<a href="./?ordine=1">User Id</a></th>
     
        
        <th>Recapiti</th>
        <th>Stato</th>
		<th><a href="./?ordine=2">Operatore</a></th>
       
  
        <th>Modifica</th>
        
        <th><a href="./?ordine=2">Agg.</a>/Creaz.</th>  
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
		
			
			
			$tot_res++;
			if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }		
			
			echo "<td>".ucfirst(@$tipo_segnalazione[$riga['tipo_segnalazione']])."</td>"; 
			
			echo "<td>".ucfirst($riga['nome_e_cognome'])." [<span style=\"color: #cc0000;\"><a href=\"mailto:".$riga['email']."\" title=\"Email Recapito\">".ucfirst($riga['user_id'])."</a></span>]"."<br />
			<span><a href=\"./?cerca=".$riga['user_id']."\" title=\"Cerca\">Segnalazioni per questo utente</a></span></td>";		
		
		     echo "<td><a href=\"mailto:".$riga['email']."\" title=\"Email Recapito\"><i class=\"fa fa-envelope-o\"></i></a>&nbsp; ".$riga['telefono']."</td>"; 
			echo "<td>".ucfirst(@$status_segnalazione[$riga['status_segnalazione']])."</td>"; 
			echo "<td ><a href=\"./?operatore=".$riga['proprietario']."&operatore_text=".ucfirst(@$proprietario[$riga['proprietario']])."\" title=\"Mostra Gestioni per questo operatore\">".ucfirst(@$proprietario[$riga['proprietario']])."</a></td>"; 
		
		
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a>
			<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?id=".$riga['id']."\" title=\"Scheda di stampa\"> <i class=\"fa fa-print\"></i></a></td>";		
			
		    echo "<td style=\"font-size: 8px;\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]."\">A ".mydate($riga['data_creazione'])."<br />Agg. ".mydatetime($riga['data_aggiornamento'])."</td>";
            echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
		    echo "</tr>";
			
	}

	echo "</table>";
	

?>	


<p>* Ci sono delle note per questo nominativo</p>
<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main); ?>
