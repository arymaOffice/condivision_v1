<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 
$nochat = 1;
if(isset($_GET['potential_rel'])) $_SESSION['destinatari'] = array(check($_GET['potential_rel']));

include("../../fl_inc/headers.php");

$messaggio = '';

?>
<style>
.form_row, .salva { width: 100%; }
.input_text label, .labelbox, .select_text label {
    display: inline-block;
    width: 25%;
    font-size: 20px;
    margin: -23px 8px 0 0;
    position: relative;
    text-align: right;
    padding-right: 20px;
    color: #999;
}
.input_text { border: none;}
.input_text input,textarea {
    width: 100%;
    font-size: 0.9em;
    border: none;
    padding: 10px;
    border-bottom: 1px solid;
    height: 50px;
    background: none;
}
.input_text textarea { height: 180px; background: white;}
</style>
<body style="width: 100%; background: white;">

<div style="width: 100%; background: white;">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p><br><br><br><br><br><p><a onclick="parent.jQuery.fancybox.close()" href="#" class="button">CHIUDI FINESTRA</a></p><br><br><br><br><br><br>'; } else

{  ?>


<div id="results"></div>

<h1 style="clear: both;"><strong>Invia SMS a </strong> <?php 
    
	$noway = 0;
	$destinatari = array();
	
	if(isset($_SESSION['destinatari'])){ 
	$query = "SELECT id,telefono,email,nome FROM `$tabella` WHERE `id` IN (" . implode(',', array_map('intval', $_SESSION['destinatari'])) . ") ;";
	} else {
	//$query = "SELECT $select FROM `$tabella` $tipologia_main;";
		die('Funzione invio massivo non abilitata. Contatta il supporto tecnico.');
	}
	
	
	$risultato = mysql_query($query, CONNECT);
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
		if(strlen($riga['telefono']) > 5) { $destinatari[] =  $riga['id']; } else { $noway++; }
	}
	
	echo mysql_affected_rows()-$noway;

 ?> persone <?php if( $noway > 0) echo  '('.$noway.' senza numero)'; ?></h1>
<p>Mittente: <?php echo crm_number; ?></p>
<form id="caricaTemplate" class="loadData" action="../mod_template/mod_opera.php" method="post">
<select name="template" id="template" style="width: 50%; float: left; clear:  none;" >
           
           <option value="-1">Scegli..</option>
			<?php 
              
		     foreach($templateSMS as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($status_potential_id == $valores) ? " selected=\"selected\"" : "";
			 echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select>
       <input type="submit" value="Carica Template" />
</form>

<br class="clear">

<form id="inviaSMS" action="mod_opera.php" method="post">


<?php foreach($destinatari as $destinatario_id){ ?>
<input type="hidden" name="destinatario[]" value="<?php echo $destinatario_id; ?>">
<?php } ?>


<input type="hidden" name="oggetto" id="oggetto" value="">
<input type="hidden" name="templateId" id="templateId" value="0">

<p class="insert_tags" >Inserisci tag: 
<a href="#" onclick="insertAtCaret('messaggio','[nome]');return false;">[nome]</a>
<a href="#" onclick="insertAtCaret('messaggio','[cry_lead_id]');return false;">[cry_lead_id]</a>


<?php foreach($tag_sms as $chiave => $valore) {
	$infotag = GRD('fl_items',$chiave);
	if($chiave > 1) echo "<a href=\"#\" onclick=\"insertAtCaret('messaggio',' ".$infotag['descrizione']." ');return false;\"> ".$infotag['label']." </a>";
	} ?>
</p>

<p class="input_text">
<textarea name="messaggio" id="messaggio" style="width: 100%; " placeholder="Scrivi un messaggio..." style="height: 100px;" onkeyup="$('#info').html(this.value.length+' caratteri');"><?php $messaggio; ?></textarea>
<span id="info"></span></p>

<input type="hidden" name="inviaSMS" value="1">

<input type="submit" value="Invia SMS" class="button salva" onClick="$('#results').html('<span class=\'green\'>Invio in corso</span>'); $(this).hide();" />
<br>
</form>
<?php } 
unset($_SESSION['destinatari']);

mysql_close(CONNECT); ?>

</div></body></html>
