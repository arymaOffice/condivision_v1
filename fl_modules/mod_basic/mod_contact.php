    <?php 
	
	// Controlli di Sicurezza
	if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	//if(!is_numeric($_GET['action'])){ exit; };
	?>
<h1>Supporto Tecnico</h1>

<div style="width: 50%;">
<p>Utilizza questo modulo per comunicare con il responsabile tecnico del sistema, segnalare messaggi di errore o inviare suggerimenti.</p>

<p>Scarica qui il software per assistenza remota: <a href="https://download.anydesk.com/AnyDesk.exe?_ga=2.219701337.1385108571.1518432411-1364973123.1518432411">Any Desk</a></p>
    
<form id="Contatta" method="post" action="action_invia.php" style="" >  


<p class="input_text">
<label for="nome">Nome </label>
<input type ="text" value="<?php echo $_SESSION['nome']; ?>" id="nome" name="nome" class="modulo" disabled />
<input type="hidden" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" id="referer" name="referer" class="modulo" disabled />
</p>

<p class="input_text"><label for="oggetto">Oggetto</label>
<input type ="text" value="Comunicazione" id="oggetto" name="oggetto" class="modulo" /></p>

<p class="input_text"><label for="testo">Messaggio</label>
</p>
<p class="input_text">
  <textarea name="testo" cols="50" rows="10" class="modulo"  id="testo"> </textarea>
</p>

  <input type="hidden" name="email" value="<?php echo $_SESSION['mail']; ?>" />
  <input type="hidden" name="codice_operatore" value="<?php echo $_SESSION['number']; ?>" />


<p style="text-align: center;">
<input name="invio" type="submit"  value="Invia Richiesta" class="button" /> 

</p>

</form>

</div>