    <?php 
	
	// Controlli di Sicurezza
	if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
	//if(!is_numeric($_GET['action'])){ exit; };
	
	

	
	?>
   <div class="box_div"> 
<h3>Contatta Assistenza Tecnica</h3>

    
<p>&nbsp;</p>
<form id="Contatta" method="post" action="action_invia.php" style="margin-left: 20px;" >  


<p class="intestazione">Utilizza questo modulo per comunicare con il responsabile tecnico del sistema.</p>
<p>&nbsp;</p>
<p class="input_text">
  <label for="nome">Chi sei </label>
  <input type ="text" value="<?php echo $_SESSION['nome']; ?>" id="nome" name="nome" class="modulo" disabled />
</p>

<p class="input_text"><label for="oggetto">Oggetto</label>
<input type ="text" value="Comunicazione" id="oggetto" name="oggetto" class="modulo" /></p>

<p class="input_text"><label for="testo">Messaggio</label>
</p>
<p class="input_text">
  <textarea name="testo" cols="50" rows="10" class="modulo"  id="testo"> </textarea>
</p>
<p>
  <input type="hidden" name="email" value="<?php echo $_SESSION['mail']; ?>" />
  <input type="hidden" name="codice_operatore" value="<?php echo $_SESSION['number']; ?>" />
 </p>
<p>&nbsp;</p>
<p>
<input name="invio" type="submit"  value="Invia" class="button" /> 
<input name="invio" type="reset"  value="Ripristina" class="button" />
</p>

</form>

</div>