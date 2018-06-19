<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>

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
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);


 		
	?>

 

  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
      <tr>
        <th><a href="./?ordine=0">Nominativo</a>/<a href="./?ordine=1">User</a></th>
         <th>Email</th>
         <th>Telefono</th>
         <th>Posizione</th>
         <th>Modifica</th>
      </tr>
	  
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$deleted = 0;
	$incomplete = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{					

		    $note = ($riga['note'] != "") ?  "<span class=\"c-red\"><a href=\"\" title=\"".convert_note($riga['note'])."\"><i class=\"fa fa-exclamation-triangle fa-lg\"></i></a></span>" : "";
			$verifica_mail = ($riga['mail_verificata'] == 0) ?  '<span class="msg gray"><a href="mod_opera.php?verifica_mail='.base64_encode($riga['id']).'"><i class="fa fa-check-circle-o"></i> Richiedi </a></span>' : '<span class="msg green"><i class="fa fa-check-circle-o"></i> Verificata </span>';
			if(trim($riga['codice_verifica_mail']) != '' && $riga['mail_verificata'] == 0) $verifica_mail =  '<span class="msg orange"><a href="mod_opera.php?verifica_mail='.base64_encode($riga['id']).'" title="Clicca per rigenerare. Codice '.$riga['codice_verifica_mail'].'"><i class="fa fa-check-circle-o"></i> </a> </span>';

			$verifica_cel = ($riga['telefono_verificato'] == 0) ?  '<span class="msg gray">NO</span>' : '<span class="msg green"><i class="fa fa-check-circle-o"></i>  </span>';
			$verifica_posizione = ($riga['posizione_verificata'] == 0) ?  '<span class="msg gray">NO</span>' : '<span class="msg green"><i class="fa fa-check-circle-o"></i> </span>';
		
			echo "<tr>";
			$dettegli_utenza = get_tipo_utenza($riga['proprietario']);
			echo "<td>".ucfirst($riga['nome'])." ".ucfirst($riga['cognome'])."<br /><span class=\"msg blue\">".ucfirst($riga['user'])."</span>"."$note</td>";		
			echo '<td>'.$verifica_mail.'</td>'; 
			echo '<td>'.$verifica_cel.'</td>'; 
			echo '<td>'.$verifica_posizione.'</td>';			
			echo "<td><a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a></td>";
			echo "<td class=\"tasto\"><a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a></td>"; 
		
		    echo "</tr>"; 
	}

	echo "</table>";
	

?>	


