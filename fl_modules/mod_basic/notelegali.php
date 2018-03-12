<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

 
include("../../fl_inc/headers.php");?>



<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>


 

<h1>Note Legali</h1>
<?php 
	// Controlli di Sicurezza
	if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	//if(!is_numeric($_GET['action'])){ exit; };
	?>
   <div class="box_div"> 
<h3>Note legali di utilizzo della piattaforma</h3>

    
<p>&nbsp;</p>


<p class="intestazione">Tutti i contenuti di questa applicazione sono di propriet&agrave; di  <?php echo client; ?></p>
<p class="intestazione">E' vietato divulgare le informazioni presenti nell'applicazione a terzi o diffondere le proprie credenziali di accesso.</p>
<p class="intestazione">&nbsp;</p>
<h3 class="intestazione">Cambio Password</h3>
<p class="intestazione">&nbsp;</p>
<p class="intestazione">Per motivi di sicurezza &egrave; necessario cambiare la propria password almeno ogni tre mesi,<br />
  E' possibile effettuare il cambio della propria password da <a href="../mod_account/">questa pagina</a>.</p>
<p class="intestazione">&nbsp;</p>
   </div>


<?php include("../../fl_inc/footer.php"); ?>