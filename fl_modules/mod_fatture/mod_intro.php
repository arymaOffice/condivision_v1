<?php

// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['last_referrer'] = ROOT.$_SERVER['PHP_SELF'];
?>

<h1>&nbsp;</h1>
<h1>Genera fattura singola </h1>   
<form method="get" action="" id="fm_intro">
  
     <p>
   
   <select name="proprietario" id="proprietario"  class="select2"  style="width: 50%;">
            <option value="-1">Seleziona affiliato</option>
			<?php 
              
		     foreach($proprietario as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? " selected=\"selected\"" : "";
		    if($valores > 1) echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n"; 
			}
		 ?>
       </select></p>
     <input type="hidden" name="external" value="1"/>
     <input type="hidden" name="action" value="21"/>
     <input type="hidden" name="id" value="1"/>
     <h2>Seleziona un periodo di fatturazione </h2>
dal <input type="text" value="<?php
$date=date_create();
date_modify($date,"-8 days");
echo date_format($date,"d/m/Y");
?>" class="calendar" name="periodo_inizio" /> <input type="text" value="<?php
$date=date_create();
date_modify($date,"-1 days");
echo date_format($date,"d/m/Y");
?>" class="calendar" name="periodo_fine" />

     <input type="button" class="button" value="Crea"  onClick="checksubmit();" /></span>
</form>


<script type="text/javascript">
 function checksubmit(){
if ($("#proprietario").attr('value') < 2) { alert('Seleziona un Affiliato'); } else { $("#fm_intro").submit(); }
 }
 
  function checksubmit2(){
if(confirm('Sei sicuro di voler procedere?') == 1){ $("#fm_intro2").submit(); }
 }

</script>

<h1>&nbsp;</h1>
<h1>&nbsp;</h1>
<h1>Genera fatture massive </h1>   
<form method="get" action="autofatturazione.php" id="fm_intro2">
  
<p>Ultimo periodo fatturato: dal <?php echo ultima_fatturazione(); ?></p> 
  
<input type="hidden" name="all" value="1"/>
<h2>Seleziona un periodo di fatturazione </h2>
dal <input type="text" value="<?php
$date=date_create();
date_modify($date,"-8 days");
echo date_format($date,"d/m/Y");
?>" class="calendar" name="periodo_inizio" /> <input type="text" value="<?php
$date=date_create();
date_modify($date,"-1 days");
echo date_format($date,"d/m/Y");
?>" class="calendar" name="periodo_fine" />

     <input type="button" class="button" value="Crea Fatture" onClick="checksubmit2();"  /></span>
</form>
