<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}

?>

<h1>Corrispettivi</h1>
<?php
	

	if(isset($_GET['ordine'])) { if(!is_numeric($_GET['ordine'])){ exit; } else { $ordine = $ordine_mod[$_GET['ordine']]; }}
	
	$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0);
						
	$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
	
	$risultato = mysql_query($query,CONNECT);
	
	
		
	?>
       
<?php if(isset($proprietario_id)) {

	$querya = "SELECT * FROM `fl_account` WHERE id = $proprietario_id LIMIT 1;";

	$risultatoa = mysql_query($querya,CONNECT);
	$rigaa = mysql_fetch_array($risultatoa);

/*
  <form method="get" action="" id="sezione_select">

 <p>
    Operatore:
      <select name="operatore" id="operatore" onchange="form.submit();">
               <?php
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select>
      <?php echo $text_home; ?> per il mese di:
             <select name="mese" id="mese">
               <?php
			   for($i=1;$i<13;$i++){
			     ($mese_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$mesi[$i].'</option>';
				} ?>
       </select>
              <select name="anno" id="anno">
               <?php
			   $time = date("Y");
			   for($i=($time-1);$i<($time+4);$i++){
			   ($anno_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
				} ?>
       </select>

    <input type="submit" value="Mostra" class="button noprint" /></p>
</form>*/


?>
 per il mese di:
<?php
			    echo $mese[$mese_sel]  ?>
 <?php
			   $time = date("Y");
			   for($i=($time-1);$i<($time+4);$i++){
			   echo ($anno_sel == $i) ?  $i : '';
                   
				} ?>
				
  
<table class="dati" summary="Dati">
      <tr>
        <th scope="col" class="tasto">Data</th>
        <th scope="col" class="codice">N. Scontrini</th>
        <th scope="col" class="codice">Corr.Netto</th>
        <th scope="col" class="codice">Iva 10%</th>
        <th scope="col" class="codice">Totale Euro</th>

      </tr>

	<?php

	$i = 1;

	if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"7\">Nessun Record Inserito</td></tr>";		}

	$tot_scontrini = 0;
	$tot_euro = 0;
	$tot_buoni_battuto = 0;
	$tot_buoni_facciale = 0;
	$tot_pos = 0;
	$tot_euro = 0;
	$tot_netto = 0;
	$tot_iva = 0;

	while ($riga = mysql_fetch_array($risultato))
	{



	if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }

		
		$tot_scontrini += $riga['totale_scontrini'];

		$tot_euro += $riga['euro'];

		$netto = $riga['euro']/1.10;
		$tot_netto +=  $netto;
		$iva = "";
		$iva = numdec(($riga['euro']-$netto),2);

		$tot_iva += $iva;

			echo "<td class=\"codice\"><a href=\"mod_inserisci.php?&amp;id=".$riga['id']."\">".mydate($riga['data_corrispettivo'])."</a></td>";
			echo "<td class=\"codice\">".$riga['totale_scontrini']."</td>";
			echo "<td class=\"codice\">".numdec($netto,2)."</td>";
			echo "<td class=\"codice\">".$iva."</td>";
			echo "<td class=\"codice\" style=\"background: #E8FFE8;\">".$riga['euro']."</td>";
		

		    echo "</tr>";
	}

	echo "<tr style=\"height: 30px;\"><td colspan=\"9\"></td></tr>";

		if($i==1){ $i=0; echo "<tr>"; } else { $i=1; echo "<tr class=\"alternate\">"; }


			echo "<td class=\"codice\">Riepilogo</td>";
			echo "<td class=\"codice\">$tot_scontrini</td>";
			echo "<td class=\"codice\">".numdec($tot_netto,2)."</td>";
			echo "<td class=\"codice\">$tot_iva</td>";
			echo "<td class=\"codice\" style=\"background: #E8FFE8;\">".numdec($tot_euro,2)."</td>";
			


		    echo "</tr>";




?>

</table>
<p class="noprint">* Sono presenti delle note per questo corrispettivo</p>
<p class="noprint"><a href="<?php echo "./?operatore=$proprietario_id&amp;mese=$mese_sel&amp;anno=$anno_sel&amp;action=0"; ?>" class="button">Versione Dettaglio</a></p>
<br />
<?php //$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,1);
 } else { echo "<div class=\"box_div\"><br /><br /><h3>Seleziona Operatore, e periodo.</h3><br />"; ?>
<form method="get" action="" id="main_select" style="text-align: right; margin: 10% 40% 10% auto; ">

 <p>
    Operatore:
      <select name="operatore" id="operatore">
               <?php
			 foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select></p>
      <p><?php echo $text_home; ?> per il mese di:
             <select name="mese" id="mese">
               <?php
			   for($i=1;$i<13;$i++){
			     ($mese_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$mesi[$i].'</option>';
				} ?>
       </select></p><p> Anno:
              <select name="anno" id="anno">
               <?php
			   $time = date("Y");
			   for($i=($time-1);$i<($time+4);$i++){
			   ($anno_sel == $i) ? $selected = 'selected="selected"' : $selected = '';
                    echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
				} ?>
       </select></p><p>

    <input type="submit" value="Mostra" class="button noprint" /></p>
</form></div>
 <?php } ?>


