<?php 

require_once('../../fl_core/autentication.php');
$estrattoconto = 1;
include('fl_settings.php'); // Variabili Modulo 


 
include("../../fl_inc/headers.php");?>

<body style=" background: #FFFFFF;">
<div style="padding: 10px;">
<?php if($proprietario_id > 1) { ?>
<a href="mod_inserisci_user.php?id=1&causale=84&external&proprietario=<?php echo $proprietario_id;?>" class="inparent button right" style="margin-left: 5px; min-width: 10%;"><i class="fa fa-plus"></i> Deposito </a> &nbsp;&nbsp;&nbsp;
<a href="mod_inserisci_user.php?id=1&causale=86&external&proprietario=<?php echo $proprietario_id;?>" class="button right" style="margin-left: 5px;  min-width: 10%;"><i class="fa fa-plus"></i> Prelievo </a> &nbsp;&nbsp;&nbsp;
<a href="mod_inserisci_user.php?id=1&causale=88&external&proprietario=<?php echo $proprietario_id;?>" class="inparent button right" style="margin-left: 5px;  min-width: 10%;"><i class="fa fa-plus"></i> Bonus </a> &nbsp;&nbsp;&nbsp;
<?php } ?>
 </div>
<h1 style=" text-align: left;">Estratto Conto <?php echo $proprietario[$proprietario_id]; ?></h1>
 
<div class="filtri">
<form method="get" action="" id="fm_filtri">
       <input type="hidden" name="proprietario" value="<?php echo $proprietario_id; ?>"/>

        
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

       creato tra il <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="10" /> e il <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" /> 
        
       <input type="submit" value="Mostra" class="button" />

       </form>
     
</div>
      


 
<?php


	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query, CONNECT);
	//echo $query;

 		
	?>

 
 
 
 
  
  	 <table class="dati" summary="Dati" style=" width: 100%;">
        <tr>
    
       <th>Stato</th>
       <th>Data</th>
       <th>Operazione</th>
              <th>Estremi</th>

       <th>Accrediti</th>
       <th>Addebiti</th>
	<th class="noprint"></th>
  
      </tr>
	<?php 
	
	$i = 1;
	
	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"9\">Nessun Record Inserito</td></tr>";		}
	$tot_res = 0;
	$incomplete = 0;
		$dare = 0;
	$avere = 0;

	while ($riga = mysql_fetch_array($risultato)) 
	{
		
		$dare += $riga['dare'];
		$avere += $riga['avere'];
			$cred = 'c-red';
			$cgreen = 'c-green';

			
		if($riga['status_pagamento'] == 0){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';

			} else if($riga['status_pagamento'] == 1){
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"tab_green\"";
			} else {
			$elimina_id = "";  
			$modifica_id = "<a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"mod_visualizza.php?&amp;id=".$riga['id']."\" title=\"Visualizza\"> <i class=\"fa fa-search\"></i> </a>"; 
			$colore = "class=\"gray\"";
			$cred = 'c-gray';
			$cgreen = 'c-gray';
			}
			
			$pagamento = ($riga['causale'] != 85) ? $metodo_di_pagamento[$riga['metodo_di_pagamento']] : '';

		
			echo "<tr><td $colore>".$status_pagamento[$riga['status_pagamento']]."</td>"; 
			echo "<td style=\"\" title=\"Aggiornato da: ". @$proprietario[$riga['operatore']]." aggiornato il ".mydatetime($riga['data_aggiornamento'])."\">".mydatetime($riga['data_creazione'])."</td>"; 
			echo "<td>".$causale[$riga['causale']]."<br>".$riga['rif_operazione']."</td>";	
			echo "<td><strong>".$pagamento."</strong><br>".$riga['estremi_del_pagamento']."</td>";	
			echo "<td class=\"".$cgreen."\">";
			if($riga['dare'] > 0) echo " &euro; ".numdec($riga['dare'],3); 
			echo "</td><td class=\"".$cred."\">";
			if($riga['avere'] > 0) echo " &euro; ".numdec($riga['avere'],3); 
			echo "</td>"; 

			
			echo "<td class=\"noprint\">$modifica_id</td>";
		
			
		    echo "</tr>";
			
			
			
	}


	echo "<tr  style=\"background: #F4F4F4;\"><td colspan=\"7\"></td></tr>";
	echo "<tr style=\"background: white;\"><td colspan=\"3\"></td><td>Totale periodo: </td><td class=\"c-green\">&euro; ".numdec($dare,3)."</td><td class=\"c-red\">&euro; ".numdec($avere,3)."</td></tr>";
	echo "<tr  style=\"background: white;\"><td colspan=\"7\"></td></tr>";
	echo "<tr  style=\"background: white;\"><td colspan=\"7\"><br><br></td></tr>";
	echo "<tr  style=\"background: white;\"><td colspan=\"7\"></td></tr>";
	$anagra = get_anagrafica_account($proprietario_id) ;
	$fido = get_fido($anagra['anagrafica']);
	
	if($proprietario_id > 1) {

	$saldo = get_saldo($proprietario_id);
	$colore = ($saldo > 0) ? 'green' : 'red'; 
	if($saldo == 0) $colore = 'gray'; 

	echo "<tr  style=\"background: white;\"><td colspan=\"3\"></td>
	<td>Saldo Disponibile: </td>
	<td colspan=\"2\" class=\"$colore\" style=\"font-size: larger; text-align: center;\"><strong>&euro; ".numdec($saldo,3)."</strong></td>
	<td colspan=\"3\"></td></tr>";
	
	$saldofoglio = get_saldo($proprietario_id,1);

	echo "<tr style=\"background: white;\"><td colspan=\"3\"></td>
	<td>Saldo Contabile: </td>
	<td colspan=\"2\">&euro; ".numdec($saldofoglio,3)."</td>
	<td colspan=\"3\"></td></tr>";


if($fido > 0 )echo "<tr  style=\"background: white;\"><td colspan=\"3\"></td><td>Fido Concesso: </td><td class=\"\">&euro; ".numdec($fido,2)."</td><td></td></tr>";

	}
?>	
</table>

<?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1); ?>
</div>
</body>

