<?php 
// Controlli di Sicurezza
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");
$user = @check($_GET['user']);
?>



<style media="all">

.box_div { border: 1px solid #F4F4F4; margin: 10px 10px; padding: 4px; background: #FFFFFF; }
.box_div_0 { border: 1px solid #F4F4F4; margin: 10px 10px; padding: 0px; }
.box_div_1 { border: 1px solid #F4F4F4; background: #F4F4F4; margin: 10px 10px; padding: 0px; }
.data_aggiornamento { font-size: 9px; color: #990000; margin: 0px; padding: 0;}
.box_div_0 h3, .box_div_1 h3 { height: auto; vertical-align: middle; background: #EEEEEE; color: #666666; padding: 4px; margin: 10px 0px; }
.msg_div { border: 1px solid #F4F4F4; margin: 10px 25px; padding: 20px; }
.box_div h3, #scheda h3 { height: auto; vertical-align: middle; background: rgba(65,65,65,1); color: #FFFFFF; padding: 10px 5px; margin: 10px 0px;  }
.box_div h3.letto { background: #5D9A42; color: rgba(65,65,65,1);}
.box_div p { padding: 5px; }
.box_div p.leggi { text-align: right; padding-right: 5px; }

.form_row, .salva { width: 100%; }
.box_div { width: 80%; height: auto; }
input { padding: 5px 2px; }
.box_div input, .box_div select { width: 63%; }
<?php if(isset($_GET['external'])) echo '.box_div { width: 95%; }'; ?>
</style>

<div id="content">
<?php if(@check($_GET['id']) > 1) { ?>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


<div class="box_div">
<?php if(isset($_GET['external'])) echo '<h3 style="background: #F2F2F2; color: #000;">'.$user.'</h3>'; ?>


<div id="tabs">
<form id="scheda" action="../mod_basic/action_modifica.php?sezione=<?php echo @check($_GET['sezione']); ?>" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');   ?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />
<input type="hidden" name="goto" value="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>" />
<script type="text/javascript">

$('#invio_scheda').val('Conferma');
$('#userbox', window.parent.document).html($('#nominativo').val());

</script>

</form>
</div>
</div>


<div class="box_div">
<h3 style="background: #F2F2F2; color: #000;"">Reimposta password di accesso</h3>

<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<div class="" style="padding: 5px 0;">

<label for="password">Nuova Password : </label>
<input name="password1" id="password1" type="password"  size="20" maxlength="15" style="width: 300px;" />
<label for="password2">Conferma Password : </label>
<input name="password2" id="password2" type="password"  size="20" maxlength="15" style="width: 300px;" />
</div>
<br>
<input type="hidden" name="modifica_pass" value="<?php echo check($_GET['id']); ?>">
<input type="submit" class="button" value="Imposta manualmente password" />
</form>
</div>





<div class="box_div">

<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">
<h3 style="background: #F2F2F2; color: #000;"">Reimposta la password automaticamente</h3>
<p>Con questa funzione viene generata una password automaticamente. Il sistema invia una mail.</p>
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
<?php } else { echo '<p>Nessun account attivo</p>'; } ?>
<br class="clear">

</div>
