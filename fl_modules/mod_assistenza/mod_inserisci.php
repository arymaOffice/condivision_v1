<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
if(@!is_numeric($_GET['action'])){ exit; };
?>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="application/x-www-form-urlencoded">
<div id="tabs" style="width: 100%;">
		
        <ul>
			
                <li><a href="#tabs-1">Richiesta</a></li>
              
                <li><a href="#tabs-2">Files Allegati</a></li>
              
                 
		</ul>
        
<div id="tabs-1">
<?php include('../mod_basic/action_estrai.php'); ?>
</div>
<div id="tabs-2">
<iframe style="width: 100%; border: none; height: 500px;" src="../mod_documentazione/mod_user.php?mode=out&amp;operatore=<?php echo $_SESSION['number']; ?>&modulo=0&cat=9&contenuto=<?php echo $id; ?>"></iframe></div>
</div>

<p>&nbsp;</p>
<p><input type="submit" id="invio" value="Salva" class="button" /><input type="reset" id="resetta2" value="Ripristina" class="button" /></p>

</form>


