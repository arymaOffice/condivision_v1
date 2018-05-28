<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
if(isset($_GET['workflow_id'])) $module_title .= ' '.@$workflow_id[check($_GET['workflow_id'])];
?>

<h1><?php echo $module_title.' '.$new_button; ?></h1>

<br class="clear">
<div id="filtri" class="filtri">
  <h2>Filtri</h2>
  <form method="get" action="" id="fm_filtri">
   
       <select name="workflow_id" id="workflow_id">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($workflow_id as $valores => $label){ // Recursione Indici di Categoria
			 $selected = (isset($_GET['workflow_id']) && check(@$_GET['workflow_id']) == $valores) ? 'selected' : '';
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select>
     <?php 
 
	foreach ($campi as $chiave => $valore) 
	{		
			if(in_array($chiave,$basic_filters)){
			
			echo '<div class="filter_box">';
			
			if(select_type($chiave) != 9 && select_type($chiave) != 19 && select_type($chiave) != 5 && select_type($chiave) != 2 && $chiave != 'id') { $cont = (isset($_GET[$chiave])) ? check($_GET[$chiave]) : ''; echo '<label>'.$valore.'</label><input type="text" name="'.$chiave.'" value="'.$cont.'" placeholder="'.$valore.'" />'; }
			
			if((select_type($chiave) == 2 || select_type($chiave) == 19 || select_type($chiave) == 9) && $chiave != 'id') {
				echo '  <label>'.$valore.'</label>';
				echo '<select name="'.$chiave.'" class="select"><option value="-1">Tutti</option>';
				foreach($$chiave as $val => $label) { $selected = (isset($_GET[$chiave]) && check(@$_GET[$chiave]) == $val) ? 'selected' : ''; echo '<option '.$selected.' value="'.$val.'">'.$label.'</option>'; }
				echo '</select>';
			}
			echo '</div>';
			
			} 
		
	}
	 ?>    

  <input type="submit" value="<?php echo SHOW; ?>" class="button" />
  </form>
</div>
<?php


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);

	?>

  <table class="dati" summary="Dati" style=" width: 100%;">
   <tr>
   <th>Ford Protect</th>
   <th>Veicolo</th>
   <th>Targa</th>
   <th>Cliente</th>

   <th></th>
    </tr>
    <?php 
	

	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"6\">No records</td></tr>";		}

	while ($riga = mysql_fetch_array($risultato)) 
	{
		  $titolare= '';
		  $StatoPolizzaColori = array('gray','orange','red','green','violet','green','gray');
		  if($riga['workflow_id'] > 1 && $riga['parent_id'] > 1) {
		  $account = @GRD(@$tables[$riga['workflow_id']],$riga['parent_id']);
		  $mod = ($riga['workflow_id'] == 58) ? 'mod_anagrafica' : 'mod_leads';
		  $titolare = "<a href=\"../".$mod."/mod_inserisci.php?id=".$account['id']."\">".$account['nome']." ".$account['cognome']."</a>
		  <br>".$account['telefono']." ".$account['telefono_alternativo']." ".$account['email']."";
		  }

		  $venditaPolizza = GQD('fl_veicoli_protect','*',' veicolo_id = '.$riga['id']);
		  $Polizza = ($venditaPolizza['id'] > 1) ? $venditaPolizza['id'] : 1;
		  $StatoPolizza = (isset($venditaPolizza['status_vendita'])) ? $venditaPolizza['status_vendita'] : 0;
		  $StatoPolizzaColor = $StatoPolizzaColori[$StatoPolizza];

		  $StatoPolizza = "<span class=\"msg $StatoPolizzaColor\">".$status_vendita[$StatoPolizza]."</span></a>";
          $protect = ($Polizza == 1) ? "<a href=\"../mod_upsell/mod_intro.php?protect&veicolo_id=".$riga['id']."&id=".$Polizza."\"><i class=\"fa fa-life-ring c-gray\" aria-hidden=\"true\"></i></a>" : "<a href=\"../mod_upsell/mod_inserisci.php?protect&veicolo_id=".$riga['id']."&id=".$Polizza."\"><i class=\"fa fa-life-ring c-green\" aria-hidden=\"true\"></i> $StatoPolizza </a>";
         

          if($Polizza > 1) { 
          	$venduto = ($venditaPolizza['data_vendita'] != '0000-00-00') ? '<span class="msg green" title="Venduto in data '.mydate($venditaPolizza['data_vendita']).'">VENDUTO</span>' : '<span class="msg gray">VENDUTO</span>';
          	$fatturato = ($venditaPolizza['fattura_emessa'] != '') ? '<span class="msg green" title="'.$venditaPolizza['fattura_emessa'].'">FATTURA EMESSA</span>' : '<span class="msg gray">FATTURA EMESSA</span>';
          	$incassato = ($venditaPolizza['data_incasso'] != '0000-00-00') ? '<span class="msg green" title="Incassato in data '.mydate($venditaPolizza['data_incasso']).'">INCASSATO</span>' : '<span class="msg gray">INCASSATO</span>';
          	$attivato = ($venditaPolizza['data_attivazione'] != '0000-00-00') ? '<span class="msg green" title="Attivato in data '.mydate($venditaPolizza['data_attivazione']).'">ATTIVATO</span>' : '<span class="msg gray">ATTIVATO</span>';
          	$protect .= $venduto.$fatturato.$incassato.$attivato;
          }


          echo "<td>$protect</td>";

          echo "<td title=\"".strip_tags(@$riga['descrizione'])."\">".@$riga['marca']." ".@$riga['modello']." ".@$riga['colore']."
          <br><span class=\"msg orange\">".@$tipologia_veicolo[$riga['tipologia_veicolo']]."</span>
		  		<span class=\"msg blue\">".@$alimentazione[$riga['alimentazione']]."</span></td>";
	      echo "<td><strong>".$riga['kilometraggio'].'</strong> KM - '.@mydate($riga['anno_immatricolazione'])."</span><br>".$riga['telaio']."</td>";
  	      echo "<td>$titolare</td>";
          echo "<td>".@$riga['targa']."</td>";
          
          //echo "<td><input type=\"text\" value=\"".@$riga['quotazione_attuale']."\" style=\"width: 80%;\" name=\"quotazione_attuale\" class=\"updateField\" data-rel=\"".@$riga['id']."\" /> &euro; </span></td>";
    	  echo "<td>";
		 // echo "<a href=\"mod_inserisci.php?id=".$riga['id']."\" title=\"Gestione Veicolo\"> <i class=\"fa fa-search\"></i> </a>";
		 // if($_SESSION['usertype'] == 0 ) echo "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Cancella\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>"; 
		  echo "</td>";
		  echo "</tr>";
			
	}
?>
  </table>
  <?php  $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);  ?>
