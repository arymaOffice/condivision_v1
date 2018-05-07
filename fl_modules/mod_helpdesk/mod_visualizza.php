<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");

?>

<div id="container" style=" text-align: left; ">

<div id="content_scheda">

<?php 

$ticket = GRD($tabella,$id);


if($ticket['account_id'] > 0) {
	
$account = GRD($tables[8],$ticket['account_id']);
$anagrafica = GRD('fl_anagrafica',@$account['anagrafica']);
$user_check = '<a data-fancybox-type="iframe" title="Modifica Account" class="fancybox" href="../mod_account/mod_visualizza.php?external&id='.$account['id'].'">'.$account['user'].'</a><br>'.$account['motivo_sospensione'];
$user_ball = ($account['attivo'] == 1)  ? "<span class=\"c-green\"><i class=\"fa fa-user\"></i></span>" : "<span class=\"c-red\"><i class=\"fa fa-user\"></i></span>"; 
$concessione = (defined('AFFILIAZIONI'))  ? @$anagrafica['numero_concessione'] : '';
echo "<p><strong>".@$anagrafica['ragione_sociale']."</strong> - P. iva ".@$anagrafica['partita_iva']."<br>".ucfirst(@$anagrafica['sede_legale'])." ".@$anagrafica['cap_sede']. " ".ucfirst($anagrafica['comune_sede']). " (".@$anagrafica['provincia_sede'].")<br><span class=\"msg gray\">".@$marchio[$anagrafica['marchio']]."</span></p>";
if($_SESSION['usertype'] < 2 && defined('ATTIVA_ACCOUNT_ANAGRAFICA')) { echo "<p>".$user_ball." ".$user_check."</p>"; }
} else { echo "<h4>".$ticket['nominativo']." ".$ticket['email_contatto']." ".$ticket['telefono_contatto']."</h4>";
}


 ?>
 
<div id="results"></div>
<div id="tabs" style="width: 100%;">
		
        <ul>
			
                <li><a href="#tabs-1">Richiesta</a></li>
                <?php if($_SESSION['usertype'] < 2) { ?> 
                <li><a href="#tabs-4">Note Interne</a></li>
				<li><a href="#tabs-5">FaQ Interne</a></li><?php } ?>
                <li><a href="#tabs-3">Files Allegati</a></li>

                 
		</ul>

<div id="tabs-1">

<div class="box">
<?php
	echo '<h2>'.$ticket['oggetto'].'</h2>';
	$color = ($ticket['stato_hd'] == 0) ? 'gray' : 'green';
	echo '<p><span class="msg purple">'.$tipologia_hd[$ticket['tipologia_hd']].'</span><span class="msg '.$color.'">'.$stato_hd[$ticket['stato_hd']].'</span></p>';
	echo html_entity_decode(converti_txt($ticket['messaggio']));

	$query = "SELECT * FROM `fl_helpdesk_posts` WHERE parent_id = $id ORDER BY data_creazione DESC;";
	$letture = "UPDATE `fl_helpdesk_posts` SET letto = 1 WHERE parent_id = $id AND letto = 0 AND account_id != ".$_SESSION['number']."";
	mysql_query($letture,CONNECT);
	
	
	$risultato = mysql_query($query,CONNECT);
		
?>
</div>  

   
<div class="box_div_0">     
	  
	<?php 
	/*Post per questo ticket */
	$i = 1;
	$idcol = 0;
	
	if(mysql_affected_rows() == 0) { echo '<h1 style="text-align: center;">Attesa Interventi</h1>';		}
	
	while ($riga = mysql_fetch_array($risultato)) 	{
	
	 $idcol = 1;			
	 $letto_sino = ($riga['letto'] == 0) ? '<i class="fa fa-eye c-gray" ></i>' : '<i class="fa fa-eye c-green"></i>';	
	 echo "<div class=\"box_div_$idcol\"><span style=\"float: right;   margin: 10px 10px;  color: white;  font-size: 28px;\">$letto_sino</span>
	 <div class=\"box\">";
	 echo "<p class=\"data_aggiornamento\">Intervento di ".@$proprietario[$riga['account_id']]." del ".@mydatetime($riga['data_creazione'])."</p>";
	 echo "".html_entity_decode(converti_txt(@$riga['messaggio']))."";
	 echo "</div></div>";
	
	 }


?>	
</div> 


<br class="clear" />
<div class="box">
<h3>Rispondi al Ticket</h3>
<form style="text-align:left" class="ajaxForm"  method="post" action="mod_opera.php" enctype="multipart/form-data">
<input type="hidden" name="parent_id" value="<?php echo $id; ?>" />
<?php if(@$ticket['stato_hd'] > 0) { ?>     
<p class="descrizione">
<?php if($_SESSION['usertype'] == 0) { ?>
<span style="float: right;"> Nuovo stato: 
<?php 
foreach($stato_hd as $valores => $label){ // Recursione Indici di Categoria
			$selected = (@$ticket['stato_hd'] == $valores) ? "checked" : "";
			echo "<input type=\"radio\" name=\"stato_hd\" id=\"stato_hd$valores\" value=\"$valores\" $selected /><label for=\"stato_hd$valores\">".ucfirst($label)."</label>\r\n";
} 
?>
</span>
<?php } else { 	
echo '<input type="hidden" name="stato_hd" value="'.$ticket['stato_hd'].'" />';
} ?></p>

<p><textarea name="messaggio" cols="150" rows="20" id="messaggio" ></textarea></p>
<p><input type="submit"  value="Invia Risposta" class="button" /></p>

<?php } else { ?>

<h3>Ticket Chiuso</h3>
<input type="hidden" name="stato_hd" value="1" />
<input type="hidden" name="messaggio" value="Riapertura Ticket" />
<p><input type="submit" value="Riapri Ticket" class="button" /></p>

<?php } ?>

</form>
</div>

</div>

<div id="tabs-3">
<iframe style="width: 100%; border: none; height: 500px;" src="../mod_dms/uploader.php?PiD=<?php echo base64_encode(FOLDER_HELP_DESK).'&workflow_id='.$tab_id.'&NAme[]=Allegati'; ?>"></iframe>
</div>

<?php if($_SESSION['usertype'] < 2) { ?>
<div id="tabs-4">
<textarea cols="3" name="note" class="updateField"  style="height: 450px; " placeholder="Note: (Non visibile all'utente) " data-rel="<?php echo $id; ?>"><?php echo strip_tags(converti_txt($ticket['note'])); ?></textarea>
</div>

<div id="tabs-5">
<iframe style="width: 100%; border: none; height: 500px;" src="../mod_faq/mod_faq.php?nochat&categoria_faq=<?php echo $ticket['tipologia_hd']; ?>"></iframe>
</div>
<?php } ?>



</div>




</div>
</body></html>



