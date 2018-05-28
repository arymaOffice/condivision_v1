<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 
unset($chat);

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

<div style="width: 100%;">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p><br><p><br><br><br><br><p><a onclick="parent.jQuery.fancybox.close()" href="#" class="button">CHIUDI FINESTRA</a></p><br><br><br><br><br><br>'; } else

{  ?>

<form id="" action="mod_opera.php" method="post" enctype="multipart/form-data" style="width: 90%; margin: 0 auto;">

<h1><strong>Invia Messaggio Email a:</strong> <?php 
    $noway = 0;
	$destinatari = '';
	if(isset($_SESSION['destinatari'])){ 
	$query = "SELECT id,telefono,email FROM `fl_potentials` WHERE `id` IN (" . implode(',', array_map('intval', $_SESSION['destinatari'])) . ") ;";
	} else {
	$query = "SELECT $select FROM `$tabella` $tipologia_main;";
	}	
	
	$risultato = mysql_query($query, CONNECT);
	while ($riga = mysql_fetch_array($risultato)) 
	{
		if(strlen($riga['email']) > 5) { $destinatari[] =  $riga['id']; } else { $noway++;}
	}
	echo $invii = mysql_affected_rows()-$noway.mysql_error();

 ?> persone <?php if( $noway > 0) echo  '('.$noway.' senza email)'; ?></h1>
<p>da: <?php echo $_SESSION['mail']; ?></p>
<?php 

if($invii > 0) { 

foreach(@$destinatari as $destinatario_id){ ?>
<input type="hidden" name="destinatario[]" value="<?php echo $destinatario_id; ?>">
<?php }  ?>

<p class="input_text">
<input id="oggetto" type="text" value="" name="oggetto" placeholder="Oggetto"></input>
</p>

<p class="input_text">
<textarea name="messaggio" id="messaggio" placeholder="Scrivi un messaggio..." style="height: 100px;" onkeyup="$('#info').html(this.value.length+' caratteri');"><?php $messaggio; ?></textarea>
<span id="info"></span></p>

<p class="insert_tags">Inserisci tag: 
<a href="#" onclick="insertAtCaret('messaggio',' [nome] ');return false;">[nome]</a>
<a href="#" onclick="insertAtCaret('messaggio',' [modello_vettura_permuta] ');return false;">[modello_vettura_permuta] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [vettura_di_interesse] ');return false;">[vettura_di_interesse] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [prezzo_vettura] ');return false;">[prezzo_vettura] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [valore_permuta] ');return false;">[valore_permuta] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [nome_bdc] ');return false;">[nome_bdc] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [cellulare_bdc] ');return false;">[cellulare_bdc] </a>


<?php foreach($tag_sms as $chiave => $valore) {
	$infotag = GRD('fl_items',$chiave);
	if($chiave > 1) echo "<a href=\"#\" onclick=\"insertAtCaret('messaggio',' ".$infotag['descrizione']." ');return false;\"> ".$infotag['label']." </a>";
	} ?>
</p>
<input type="hidden" name="inviaEmail" value="1">

<input type="submit" value="Invia Email" class="button salva" onClick="$('#results').html('Invio in corso'); $(this).hide();" />
<br>
</form>
<?php } else {  echo "<h1 class=\"red\">Nessun destinatario con una mail valida!</h1>"; }

} 


mysql_close(CONNECT);
?>

</div></body></html>
