
<?php 

$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

if(($proprietario_id < 2 || isset($_GET['intro'])) && defined('PRIMANOTA_MULTIPLA')) {  ?>
<h2>Seleziona un account</h2>
<form method="get" action="" id="dms_account_sel">

<?php 			$selected = ($proprietario_id == 0) ? ' checked="checked"' : '';
?>
<input id="0" onClick="form.submit();" type="radio" name="cmy" value="0" <?php echo $selected; ?> />
		
 <?php
			
			 foreach($anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? ' checked="checked"' : '';
			if($valores > 1){ echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="cmy" value="'.base64_encode($valores).'" '.$selected.' />
			<label for="'.$valores.'"><i class="fa fa-user"></i><br>'.$label.'</label>'; }
			}
		 ?>
      
     <input type="hidden" name="a" value="amministrazione">
   
</form>
<?php } else { ?>


<?php
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
    $balance_query = "SELECT SUM(avere) AS totale_avere, SUM(dare) AS totale_dare FROM `$tabella` WHERE `account_id` = $proprietario_id";
	$result = mysql_query($balance_query ,CONNECT); 
	$row = mysql_fetch_assoc($result); 
	$prog_balance = $cash_balance = $row['totale_avere']-$row['totale_dare'];
 		
	?>
    
 <h2><?php echo "Saldo attuale: &euro; ".numdec($cash_balance,2); ?></h2>


<div > 
<form method="get" action="" id="fm_filtri">
   <?php if($_SESSION['usertype'] < 2 && defined('PRIMANOTA_MULTIPLA'))  { ?>Account: 
      <select name="cmy" id="cmy" onchange="form.submit();">
      
      <option value="-1">Mostra Tutti</option>
            
			<?php 
              
		    foreach($anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"".base64_encode($valores)."\" $selected>".ucfirst($label)."</option>\r\n"; }

			}
		 ?>
       </select>
       
     <?php } ?>     
    <span style="position: relative;">
      <select name="cId" id="cId" onChange="form.submit();">
      <option value="-1">Tutti</option>
      <?php 
              
		     foreach($conto as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($conto_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"".base64_encode($valores)."\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> 
  <!--    <select name="causale" id="causale" onChange="form.submit();">
      <option value="-1">Mostra tutto</option>
      <?php 
              
		     foreach($causale_operazione as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($causale_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
    </select> -->
    da
    <input type="text" name="data_da" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
    a
    <input type="text" name="data_a"  value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />
    <input type="submit" value="Mostra" class="button" />
  
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
  <th>Data</th>
    <th>Descrizione</th>

  <th>Conto</th>
  <th>Entrate</th>
  <th>Uscite</th>
  <th>Parziale</th>
  <th  class="hideMobile"></th>
  <th  class="hideMobile"></th>
</tr>
<?php 
	
	$i = 1;
	$balance = 0;
		$dare = 0;
	$avere = 0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\"><h2>No records</h2></td></tr>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{					
			
			$colore = '';
			$pagato = '';
			$delete = "<a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$riga['id']."\" title=\"Delete\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";
			$colore = ($riga['dare'] > 0 ) ? "class=\"tab_orange\"" : "class=\"tab_green\""; 
			$balance += $riga['avere'];
			$balance -= $riga['dare'];
			$dare += $riga['dare'];
			$avere += $riga['avere'];
			$dare_txt = ($riga['dare'] > 0) ? numdec($riga['dare'],2).' &euro;' : '';
			$avere_txt = ($riga['avere'] > 0) ? numdec($riga['avere'],2).' &euro;' : '';

			echo "<tr>"; 
				
			echo "<td $colore><span class=\"Gletter\"></span></td>";
			echo "<td><input type=\"text\" name=\"data_operazione\"  class=\"updateField calendar\" data-rel=\"".$riga['id']."\" value=\"".mydate($riga['data_operazione'])."\" /></td>"; 
			echo "<td><input type=\"text\" name=\"descrizione\" style=\"width: 100%;\"  class=\"updateField\" data-rel=\"".$riga['id']."\" value=\"".strip_tags(converti_txt($riga['descrizione']))."\" /></td>";
			//echo "<td>".$causale_operazione[$riga['causale_operazione']]."</td>";
			echo "<td>".$conto[$riga['conto']]."</td>";
			echo "<td  class=\"c-green\">$avere_txt</td>";
			echo "<td class=\"c-red\">$dare_txt</td>"; 
			echo "<td>".numdec($balance,2)." &euro; </td>";
			echo "<td><a href=\"mod_inserisci.php?external&action=1&amp;id=".$riga['id']."\" title=\"Dettaglio movimentazione\"> <i class=\"fa fa-search\"></i> </a></td>";
			echo "<td>$delete</td>"; 
		    echo "</tr>"; 
			$prog_balance += $riga['dare']; 
			$prog_balance -= $riga['avere'];

	}
    echo "<tr><td colspan=\"9\"></td></tr>";
	echo "<tr><td colspan=\"4\"></td>
	<td><strong>&euro; ".numdec($avere,2)."</strong></td>
	<td><strong>&euro; ".numdec($dare,2)."</strong></td>
	<td><strong>&euro; ".numdec($balance,2)."</strong></td>
	<td></td>
	<td></td>
	
	</tr>";
	echo "</table>";
	

?>
<?php } ?>