<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");
$user = @check($_GET['user']);
?>

<style media="all">
.form_row, .salva { width: 100%; }
.box_div { width: 80%; }
<?php if(isset($_GET['external'])) echo '.box_div { width: 95%; }'; ?>
</style>
<div id="content">
<?php if(@check($_GET['id']) > 1) { ?>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


<div class="box_div">
<?php if(isset($_GET['external'])) echo '<h3 style="background: #F2F2F2; color: #000;">'.$user.'</h3>'; ?>



<form id="scheda" action="../mod_basic/action_modifica.php?sezione=<?php echo @check($_GET['sezione']); ?>" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');   ?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />
<input type="hidden" name="goto" value="<?php echo 'https://'.$_SERVER['HTTP_HOST']."/".$_SERVER['QUERY_STRING']; ?>" />
<script type="text/javascript">

$('#invio_scheda').val('Conferma');
$('#userbox', window.parent.document).html($('#nominativo').val());

</script>

</form>
</div>


<div class="box_div">
<h3 style="background: #F2F2F2; color: #000;"">Reimposta password di accesso</h3>

<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<div class="" style="padding: 5px 0;">

<label for="password">Nuova Password : </label>
<input name="password1" id="password1" type="password"  size="20" maxlength="15" />
<label for="password2">Conferma Password : </label>
<input name="password2" id="password2" type="password"  size="20" maxlength="15" />
</div>
<br>
<input type="hidden" name="modifica_pass" value="<?php echo check($_GET['id']); ?>">
<input type="submit" class="button" value="Imposta manualmente password" />
</form>
</div>





<div class="box_div">

<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h3 style="background: #F2F2F2; color: #000;"">Reimposta la password automaticamente</h3>
<p>Con questa funzione viene generata una password automaticamente. Il sistema invia una mail all'utente.</p>
<input type="hidden" name="modifica_pass_auto" value="<?php echo $id; ?>">
<input type="submit" class="button" value="Reimposta password" />
</form>
</div>


<div class="box_div">
<h3 style="background: #F2F2F2; color: #000;">Informazioni</h3>
<p>Email: <?php echo $email; ?></p>
<p>Ultimo Login utente: <?php 
$last_login = last_login($user);
echo mydatetime(@$last_login['data_creazione'])." (".@$last_login['ip'].")"; ?> - Password aggiornata il: <?php echo $aggiornamento_password; ?></p>
<p><a href="../mod_accessi/mod_scheda.php?cerca=<?php echo $user; ?>"  data-fancybox-type="iframe" class="fancybox_view" >Accessi</a> - 
<a href="../mod_action_recorder/mod_scheda.php?cerca=<?php echo $user; ?>"  data-fancybox-type="iframe" class="fancybox_view" >Azioni</a></p>
 
</div>

<p>
<strong>GDPR 2016/679 Informativa sul trattamento dei dati riguardanti il vostro account</strong>
L'accesso alla piattaforma è da intendersi personale ed esclusivamente riservato all'utente autorizzato. 
Ne è vietata la riproduzione sotto ogni forma. La vostra password scade ogni 90 giorni e va reimpostata obbligatoriamente.
In conformità ai requisiti del DL 196 del 30/6/2003, 
si informa che ogni accesso, riconoscibile da IP e username, sarà registrato e potrà essere monitorato.
Le attività eseguite nell'ambiente gestionale sono registrate per motivi di sicurezza.
</p>


<?php } else { echo '<p>Nessun account attivo</p>'; } ?>
<br class="clear">

</div>
