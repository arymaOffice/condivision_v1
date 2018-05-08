<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
if(@!is_numeric($_GET['action']) || $_SESSION['usertype'] > 0){ exit; };
?>

<h1><span class="intestazione"><?php echo $txt_operazione[1]." ".$txt_soggetto; ?></span></h1>





<form id="scheda" action="../mod_basic/action_modifica.php?sezione=<?php echo @check($_GET['sezione']); ?>" method="post" enctype="multipart/form-data">

<?php include('../mod_basic/action_estrai.php');  ?>
<input type="hidden" name="dir_upfile" value="icone_articoli" />
<p style="margin-top: 25px;"><input type="submit" id="invio" value="Salva" class="button" /></p>

</form>



