<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 
unset($chat);
include("../../fl_inc/headers.php");

$titolo = (isset($_GET['oggetto'])) ? check($_GET['oggetto']) : 'Notifica del '.date('d/m/Y H:i');
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

<div style="width: 100%;">

<div class="info_dati"><p><strong>Invia Notifica a:</strong> <?php 
foreach($_GET['destinatario'] as $destinatario_id){
echo '<span class="i-tag">'.$destinatario[$destinatario_id].'</span>'; } ?></p></div>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p><br><br><br><br><br><p><a onclick="parent.jQuery.fancybox.close()" href="#" class="button">CHIUDI FINESTRA</a></p><br><br><br><br><br><br>'; } else

{  ?>

<form id="" action="mod_opera.php" method="post" enctype="multipart/form-data" style="width: 90%; margin: 0 auto;">
<input type="hidden" name="modulo" value="<?php echo $tab_id; ?>">

<?php foreach($_GET['destinatario'] as $destinatario_id){ ?>
<input type="hidden" name="destinatario[]" value="<?php echo $destinatario_id; ?>">
<?php } ?>


<p class="input_text">
<input id="titolo" type="text" value="<?php echo $titolo; ?>" name="titolo" placeholder="Oggetto"></input>
</p>

<p class="input_text">
<textarea name="messaggio" placeholder="Scrivi un messaggio..."><?php $messaggio; ?></textarea>
</p>

<p class="input_text" style="text-align: center;">
			
            <input id="alert1" name="alert" value="1" type="checkbox">
			<label for="alert1" style="width: 31%; margin: 5px 1% 5px 0; padding: 5px;">Mostra alert</label>
           
            <input id="obbligatorio1" name="obbligatorio" value="1"  type="checkbox">
			<label for="obbligatorio1" style="width: 31%; margin: 5px 1% 5px 0; padding: 5px;">Obbliga alla lettura</label>

			<input id="invia_email1" name="invia_email" value="1" checked="checked" type="checkbox">
			<label for="invia_email1" style="width: 31%; margin: 5px 1% 5px 0; padding: 5px;">Invia email</label>
</p>

<input type="submit" value="Invia Notifica" class="button salva" />

</form>
<?php } ?>

</div></body></html>
