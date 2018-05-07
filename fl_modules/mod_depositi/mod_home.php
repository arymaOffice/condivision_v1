<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];;
?>
<h1><?php  if(isset($_GET['estratto'])) { echo 'Estratto Conto'; } else { echo 'Gestione Depositi e Prelievi'; } ?></h1>   
 

 <?php if($proprietario_id > 1) { ?>
<div style="margin: 10px 0;">
<h3 style=" text-align: left; float: left; padding: 0 10px;">Affiliato: <?php echo $proprietario[$proprietario_id]; ?></h3>

  <!--<a href="./?action=1&id=1&causale=84&external&proprietario=<?php echo $proprietario_id;?>" class="inparent button right" style="margin-left: 5px; min-width: 10%;"><i class="fa fa-plus"></i> Deposito </a> &nbsp;&nbsp;&nbsp;
  <a href="./?action=1&id=1&causale=86&external&proprietario=<?php echo $proprietario_id;?>" class="button right" style="margin-left: 5px;  min-width: 10%;"><i class="fa fa-plus"></i> Prelievo </a> &nbsp;&nbsp;&nbsp;
  <a href="./?action=1&id=1&causale=88&external&proprietario=<?php echo $proprietario_id;?>" class="inparent button right" style="margin-left: 5px;  min-width: 10%;"><i class="fa fa-plus"></i> Bonus </a> &nbsp;&nbsp;&nbsp;
--></div>

<br clear="all">
 <?php } else if(isset($_GET['estratto'])) { echo '<h2 style="margin: 0; color: green;">Seleziona un affiliato per caricare estratto conto</h2>'; } ?>


 <div >
<form method="get" action="" id="fm_filtri">
  
    
   <select name="proprietario" id="proprietario" class="select2" onChange="form.submit();">
            <option value="-1">Seleziona affiliato</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 1)  echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
           <select name="causale" id="causale" onChange="form.submit();">
            <option value="-1">Operazione</option>
			<?php 
              
		    foreach($causale as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($causale_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 0 && $valores != 85 && $valores != 89)  echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
       
       <select name="status_pagamento" id="status_pagamento">
            <option value="-1">Stato</option>
			<?php 
              
		     foreach($status_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_pagamento_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       
             <select name="metodo_di_pagamento" id="metodo_di_pagamento">
            <option value="-1">Metodo</option>
			<?php 
              
		     foreach($metodo_di_pagamento as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($metodo_di_pagamento_id == $valores) ? " selected=\"selected\"" : "";
		    echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       creato tra il 
       <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" />
       <select name="ora_da" style="width: 70px;">
		  <?php 
   		for($x = 0; $x < 24; $x++){ 
			$limitx = "style=\"background: white;\"";
			$x = str_pad($x, 2, "0", STR_PAD_LEFT);					
			$selected = ($ora_da_id == "$x:00:59") ? " selected=\"selected\"" : "";
			echo "<option value=\"$x:00:59\" $selected $limitx>".ucfirst($x).":00</option>\r\n";
			$selected = ($ora_da_id == "$x:30:59") ? " selected=\"selected\"" : "";
			echo "<option value=\"$x:30:59\" $selected $limitx>".ucfirst($x).":30</option>\r\n";
			$selected = ($ora_da_id == "$x:45:59") ? " selected=\"selected\"" : "";
			echo "<option value=\"$x:45:59\" $selected $limitx>".ucfirst($x).":45</option>\r\n";
			
			}
		 ?>
       </select>
       e  <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" /> 

<select name="ora_a" style="width: 70px;">
		  <?php 
   		for($x = 0; $x < 24; $x++){ 
			$limitx = "style=\"background: white;\"";
			$x = str_pad($x, 2, "0", STR_PAD_LEFT);			
			$selected = ($ora_a_id == "$x:00:59") ? " selected=\"selected\"" : "";
			echo "<option value=\"$x:00:59\" $selected $limitx>".ucfirst($x).":00</option>\r\n";
			$selected = ($ora_a_id == "$x:15:59") ? " selected=\"selected\"" : "";
			echo "<option value=\"$x:30:59\" $selected $limitx>".ucfirst($x).":30</option>\r\n";
			$selected = ($ora_a_id == "$x:59:59") ? " selected=\"selected\"" : "";
			echo "<option value=\"$x:59:59\" $selected $limitx>".ucfirst($x).":59</option>\r\n";
			
			}
		 ?>
       </select>
       <input type="submit" value="Mostra" class="button" />
 <?php if(isset($_GET['estratto'])) echo '<input type="hidden" value="1" name="estratto" />'; ?>
 
       </form>
   
  

 
    </div>



 

<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";

	$risultato = mysql_query($query, CONNECT);
	
	
	$query2 = "SELECT $select, SUM(dare) AS tot_dare, SUM(avere) as tot_avere FROM `$tabella` $tipologia_main;";
	$risultato2 = mysql_query($query2, CONNECT);
	$riga2 = mysql_fetch_array($risultato2);
	//echo $query2;
	//
	if((isset($_GET['estratto']) && $proprietario_id > 1) || !isset($_GET['estratto']) ) {		
	?>

 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th>Stato</th>
       <th>Utente</th>
       <th>Data</th>
       <th>Operazione</th>
       <th>Info Operazione</th>
       <th>Accrediti</th>
       <th>Addebiti</th>
       <th class="noprint">Modifica</th>
       <th class="noprint">Elimina</th>          
        </tr>
	<?php 
	
	$i = 1;
	$dare = 0;
	$avere = 0;
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$totale_foglio = 0;
	while ($riga = mysql_fetch_array($risultato)) 
	{
			$query_doc = "SELECT * FROM fl_files WHERE relation = ".$riga['proprietario']." AND cat = 8 AND contenuto = ".$riga['id']." ORDER BY titolo ASC";
 			$risultato2 = mysql_query($query_doc, CONNECT);	
			$conferma = '';
			$dare += $riga['dare'];
			$avere += $riga['avere'];
			$cred = 'c-red';
			$cgreen = 'c-green';
			
			
			if($riga['status_pagamento'] == 0){
			$elimina_id = "<a href=\"../mod_basic/elimina.php?gtx=$tab_id&unset=".$riga['id']."\" title=\"Elimina\" onclick=\"conferma_del();\"><i class=\"fa fa-trash-o\"></i></a>";   
			$modifica_id = "<a href=\"mod_inserisci_user.php?id=".$riga['id']."&causale=".$riga['causale']."\" title=\"Modifica\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			if($riga['dare'] > 0 || $riga['avere'] > 0) $conferma = "<a class=\"button green\" href=\"./?action=21&set=1&amp;id=".$riga['id']."&causale=".$riga['causale']."\" title=\"Conferma\"> CONFERMA </a>";
			$cred = 'c-gray';
			$cgreen = 'c-gray';
			} else if($riga['status_pagamento'] == 1){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?id=".$riga['id']."&causale=".$riga['causale']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "style=\" background: #008233; color: #FFF;\"";
			} else {
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?id=".$riga['id']."&causale=".$riga['causale']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';

			}
			$pagamento = ($riga['causale'] != 85) ? @$metodo_di_pagamento[@$riga['metodo_di_pagamento']] : '';
			$data_valuta = ($riga['data_valuta'] != '0000-00-00') ? '<br><strong>Valuta al</strong>: '.mydate($riga['data_valuta']) : '';
			echo "<tr><td $colore>".$status_pagamento[$riga['status_pagamento']]."</td>"; 
			echo "<td>".@$proprietario[@$riga['proprietario']]."</td>";	
			echo "<td style=\"\" title=\"Aggiornato da: ". @$proprietario[@$riga['operatore']]." aggiornato il ".mydatetime($riga['data_aggiornamento'])."\">".mydatetime($riga['data_creazione'])." $data_valuta</td>"; 
			echo "<td>".@$causale[@$riga['causale']]." ".$riga['rif_operazione']."</td>";	
			echo "<td><strong>".$pagamento."</strong><br>".$riga['estremi_del_pagamento']."</td>";	
			echo "<td class=\"".$cgreen."\">";
			if($riga['dare'] > 0) echo " &euro; ".numdec($riga['dare'],2); 
			echo "</td><td class=\"".$cred."\">";
			if($riga['avere'] > 0) echo " &euro; ".numdec($riga['avere'],2); 
			echo "</td>"; 
			
			echo "<td class=\"noprint\">$modifica_id</td>";
			
			echo "<td class=\"tasto noprint\">$elimina_id</td>"; 
			
			echo "<td class=\"noprint\">$conferma</td>";
	
		    echo "</tr>";
			
			
			
	}
		echo "<tr  style=\"background: #F4F4F4;\"><td colspan=\"10\"></td></tr>";

	echo "<tr style=\"background: white;\"><td colspan=\"4\"></td><td>Totale periodo: </td><td class=\"c-green\">&euro; ".numdec($riga2['tot_dare'],2)."</td><td class=\"c-red\">&euro; ".numdec($riga2['tot_avere'],2)."</td><td colspan=\"3\"></td></tr>";
	echo "<tr><td colspan=\"10\"></td></tr>";
 
	echo "<tr  style=\"background: white;\"><td colspan=\"10\"><br><br></td></tr>";
	
	if($proprietario_id > 1 && isset($_GET['estratto'])) {
	$anagra = get_anagrafica_account($proprietario_id) ;
	$fido = get_fido($anagra['anagrafica']);
	echo "<tr  style=\"background: white;\"><td colspan=\"10\"></td></tr>";

	$saldo = get_saldo($proprietario_id);
	$colore = ($saldo > 0) ? 'green' : 'red'; 
	if($saldo == 0) $colore = 'gray'; 

	echo "<tr  style=\"background: white;\"><td colspan=\"4\"></td>
	<td>Saldo Disponibile: </td>
	<td colspan=\"2\" class=\"$colore\" style=\"font-size: larger; text-align: center;\"><strong>&euro; ".numdec($saldo,2)."</strong></td>
	<td colspan=\"4\"></td></tr>";
	
	$saldofoglio = get_saldo($proprietario_id,1);

	echo "<tr style=\"background: white;\"><td colspan=\"4\"></td>
	<td>Saldo Contabile: </td>
	<td colspan=\"2\">&euro; ".numdec($saldofoglio,2)."</td>
	<td colspan=\"4\"></td></tr>";


if($fido > 0 ) echo "<tr  style=\"background: white;\"><td colspan=\"4\"></td><td>Fido Concesso: </td><td class=\"\">&euro; ".numdec($fido,2)."</td>
<td  style=\"background: white;\"></td></tr>";
	}
	echo "</table>";
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);

	echo '
	<span style="float: right; margin-right: 4px;">
    <form method="get" action="../../fl_core/imposta.php" id="res">
	
    Mostra per pagina: <input name="step" type="text" value="'.$step.'"  onFocus="this.value=\'\';" size="2" maxlength="4" /> 
    </form>
    
    </span>';
	} 


?>	



