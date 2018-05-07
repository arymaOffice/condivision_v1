<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
if(@!is_numeric($_GET['action'])){ exit; };
?>


<div id="tabs" style="width: 100%;">
		
        <ul>
			
                <li><a href="#tabs-1">Richiesta</a></li>
                <li><a href="#tabs-2">Rispondi al Ticket</a></li>               
                <li><a href="#tabs-3">Files Allegati</a></li>
              
                 
		</ul>

<div id="tabs-1">
<?php include('../mod_basic/action_visualizza.php'); ?>

<?php
	
	$query = "SELECT * FROM `$tabella` WHERE jorel = $id ORDER BY data_creazione DESC;";
	$query_letture = "UPDATE `$tabella` SET letto = 1 WHERE jorel = $id AND letto = 0 AND operatore != ".$_SESSION['number']."";
	
	mysql_query($query_letture,CONNECT);
	$risultato = mysql_query($query,CONNECT);
		
	?>
       
       
	  
	<?php 
	
	$i = 1;
	$idcol = 0;
	
	if(mysql_affected_rows() == 0) { echo "<p class=\"box_div_1\"><div class=\"box_div\">Attesa Interventi</div></p>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 	{
	
	 $idcol = ($idcol == 0) ? 1 : 0;			
	 $letto_sino = ($riga['letto'] == 0) ? "Non letto" : '<img src="../../fl_set/icons/letto.png" alt="Letto" />';	
	 echo "<div class=\"box_div_$idcol\"><span style=\"float: right;\">$letto_sino</span>
	 <h3>".ucfirst($riga['oggetto'])."</h3><div class=\"box_div\">";
	 echo "<p class=\"data_aggiornamento\">Intervento di ".$proprietario[$riga['operatore']]." del ".@date("d/m/Y - H:i",$riga['data_creazione'])."</p>";
	 echo "".converti_txt(@$riga['descrizione'])."";
	 echo "</div></div>";
	
	 }

	 

?>	

</div>
<div id="tabs-2">
<?php include('../mod_basic/action_visualizza.php'); ?>

<div class="box_div_1">
<h3>Rispondi</h3>
<form style="text-align:left"  method="post" action="mod_opera.php" enctype="multipart/form-data">
<div class="box_div">
<input type="hidden" name="proprietario" value="<?php echo check($_GET['proprietario']); ?>" />
<input type="hidden" name="jorel" value="<?php echo $id; ?>" />

<?php if(@STATUS_ASSISTENZA != 3) { ?>
     
<p class="descrizione"><label for="titolo">Oggetto</label> 
<input name="oggetto" type="text" id="titolo" size="60" maxlength="100" />
<?php if($_SESSION['usertype'] == 0) { ?>
<span style="float: right;"> Nuovo stato: <select name="status_assistenza" id="status_assistenza" >
           
			<?php 
              
		    foreach($status_assistenza as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@STATUS_ASSISTENZA == $valores) ? " selected=\"selected\"" : "";
			if($valores != 0){ echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; }
			}
		 ?>
       </select></span>
<?php } ?></p>
  
<p><textarea name="descrizione" cols="150" rows="20" class="msg_area" id="descrizione" ></textarea></p>


<p><input type="submit" id="invio" value="Invia" class="button" /></p>
<?php } else { ?>
<h3>Ticket Chiuso</h3>
<p><input type="hidden" name="status_assistenza" value="1" />
<p><input type="hidden" name="letto" value="1" />
<input type="hidden" name="oggetto" value="Riapertura Ticket" />
<input type="submit" id="invio" value="Riapri Ticket" class="button" /></p>
<?php } ?>

</form></div>
</div></div>
<div id="tabs-3">
<iframe style="width: 100%; border: none; height: 500px;" src="../mod_documentazione/mod_user.php?mode=out&amp;operatore=0&modulo=0&cat=9&contenuto=<?php echo $id; ?>"></iframe></div>
</div>





