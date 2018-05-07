<?php 

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
if(!is_numeric($_GET['action'])){ exit; };



?>

<form id="form_<?php echo $txt_soggetto; ?>" class="admin_form" method="post" action="mod_opera.php" enctype="multipart/form-data">

<?php if(isset($_GET['id'])){ ?>
<p><input name="relation" id="relation" type="hidden" value="<?php echo $_GET['id'];  ?>" />Link per risorsa: <?php echo $_GET['id'];  ?>- Cat: <input name="cat" id="cat" type="hidden" value="<?php echo $cat;  ?>" /><?php echo $cat_multi_mod[$cat];  ?></p>
<p>&nbsp;</p>
<?php } ?>    
<p><label for="titolo">Titolo:</label></p>
<p><input name="titolo" id="titolo" type="text" class="form_color" size="60" maxlength="255" value="<?php if(isset($riga['titolo'])) { echo $riga['titolo']; } ?>" />
  </p>
<p>&nbsp;</p>
<p><label for="link">Link a sito web:</label></p>
<p><input name="link" type="text" class="inputForm" id="link" value="http://" size="50" maxlength="255" />
</p>
<p>&nbsp;</p><p><label for="lang">Lingua Contenuti:</label>
  <select name="lang" id="lang">
    <?php foreach($languages as $chiave => $valore){
    
   echo "<option value=\"$chiave\" $selected>$valore</option>";
   
   } ?>
  </select>
</p>
<p>&nbsp;</p>
<p><input type="submit" id="invio" value="Invia" /><input type="reset" id="resetta2" value="Ripristina" /></p>

</form>