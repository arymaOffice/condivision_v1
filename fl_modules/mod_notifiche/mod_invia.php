<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 
$nochat = 1;
include("../../fl_inc/headers.php");

$titolo = (isset($_REQUEST['oggetto'])) ? check($_REQUEST['oggetto']) : 'Notifica del '.date('d/m/Y H:i');
$messaggio = (isset($_SESSION['messaggio'])) ? $_SESSION['messaggio'] : '';
if(isset($_REQUEST['d'])) $_REQUEST['destinatario'] = $_REQUEST['d'];
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

<div style="width: 100%;">

<?php if(isset($_REQUEST['esito'])) { $class = (isset($_REQUEST['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_REQUEST['esito']).'</p><br><br><br><br><br><p><a onclick="parent.jQuery.fancybox.close()" href="#" class="button">CHIUDI FINESTRA</a></p><br><br><br><br><br><br>'; } else

{  ?>

<form id="" action="mod_opera.php" method="post" enctype="multipart/form-data" style="width: 90%; margin: 0 auto;">
<input type="hidden" name="modulo" value="<?php echo $tab_id; ?>">

<?php foreach($_REQUEST['destinatario'] as $destinatario_id){ ?>
<input type="hidden" name="destinatario[]" value="<?php echo $destinatario_id; ?>">
<?php } ?>


<p class="input_text">
<input id="titolo" type="text" value="<?php echo $titolo; ?>" name="titolo" placeholder="Oggetto"></input>
</p>

<div class="info_dati" style=" padding: 0 10px;"><p><strong>Invia Notifica a:</strong> 

<?php 

$quanti = 0;

foreach($_REQUEST['destinatario'] as $destinatario_id){
$quanti++;
if($quanti < 10) echo '<span class="">'.$destinatario[$destinatario_id].'</span> '; 
} 

if($quanti > 10) echo ' <span class=""> + altri '.($quanti-10).'</span>';  ?></p></div>


<p class="input_text">
<textarea name="messaggio" placeholder="Scrivi un messaggio..."><?php echo $messaggio; ?></textarea>
</p>

<p class="input_text" style="text-align: center;">
			
            <input id="alert1" name="alert" value="1" type="checkbox">
			<label for="alert1" style="width: 22%; margin: 5px 1% 5px 0; padding: 5px;">Mostra alert</label>
           
            <input id="obbligatorio1" name="obbligatorio" value="1"  type="checkbox">
			<label for="obbligatorio1" style="width: 22%; margin: 5px 1% 5px 0; padding: 5px;">Obbliga alla lettura</label>

			<input id="invia_email1" name="invia_email" value="1" checked="checked" type="checkbox">
			<label for="invia_email1" style="width: 22%; margin: 5px 1% 5px 0; padding: 5px;">Invia email</label>
            
            <input id="invia_sms" name="invia_sms" value="1"  type="checkbox">
			<label for="invia_sms" style="width: 22%; margin: 5px 1% 5px 0; padding: 5px;">Invia SMS</label>

</p>
<input type="submit" value="Invia " class="button salva" style=" max-width: 350px; margin: 0 auto; "  onClick="$('#results').html('Invio in corso'); $(this).hide();" />
<br><br><br>
</form>
<?php } ?>

</div></body></html>
