<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");
if(!isset($_GET['external'])) include("../../fl_inc/testata_mobile.php");

?>

<div id="container" >
<div id="content">

<h1>Gestisci Account</h1>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="tabs">
<ul>

<li><a href="#tab_account">Dati Account</a></li>
<li><a href="#tab_password">Reimposta password</a></li>
<li><a href="#tab_accessi">Accessi</a></li>
<li><a href="#tab_sicurezza">Sicurezza</a></li>
<li><a href="#tab_permessi">Permessi</a></li>

</ul>

<div id="tab_account">

<form id="scheda" action="../mod_basic/action_modifica.php?sezione=<?php echo @check($_GET['sezione']); ?>" method="post" enctype="multipart/form-data">

<?php include('../mod_basic/action_estrai.php');   ?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>
</div>

<div id="tab_password">
<div class="">
<h3>Email: <?php echo $email; ?></h3>
<p>Password aggiornata il: <?php echo $aggiornamento_password; ?></p> 
</div>

<div class="box_div" style=" float: left; width: 48%;">

<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h2>Reimposta la password di accesso</h2>
<p>Con questa funzione viene generata una password automaticamente</p>
<p>Il sistema invia una mail all'utente con la nuova password.</p>

<input type="hidden" name="modifica_pass_auto" value="<?php echo $id; ?>">
<input type="submit" class="button" value="Reimposta password" />
</form>
</div>
<div class="box_div" style=" float: left; width: 48%;">
<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<div class="form_row">
<div class="input_text">
<label for="password">Nuova Password : </label>
<input name="password1" id="password1" type="password"  size="40" maxlength="15" />
</div></div>

<div class="form_row">
<div class="input_text">
<label for="password2">Conferma Password : </label>
<input name="password2" id="password2" type="password"  size="40" maxlength="15" />
</div></div>
<br>
<input type="hidden" name="modifica_pass" value="<?php echo check($_GET['id']); ?>">
<input type="submit" class="button" value="Imposta manualmente password" />
</form>
</div>

<br class="clear">

</div>

<div id="tab_accessi">
<iframe style="width: 100%; border: none; height: 600px;" src="../mod_accessi/mod_scheda.php?cerca=<?php echo $campi['user']; ?>"></iframe>

</div>
<div id="tab_sicurezza">
<iframe style="width: 100%; border: none; height: 600px;" src="./mod_sicurezza.php?id=<?php echo $id; ?>"></iframe>

</div>
<div id="tab_permessi">
<iframe style="width: 100%; border: none; height: 800px;" src="../mod_permessi/mod_visualizza.php?external&account=<?php echo $id; ?>"></iframe>

</div>

</div>
