<?php


require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
 
include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");


?><body style=" background: #FFFFFF;">
<div id="container" >


<div id="content_scheda">
<?php if($_SESSION['usertype'] == 0) { ?>





<?php if(isset($_GET['esito'])) { 

$class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p><p style="text-align: center;"><a title="Torna Indietro" href="javascript:history.back();">&lt;&lt;Indietro</a></p>'; }  else { ?>
<div id="results"></div>

<form  method="post" class="ajaxForm" action="mod_opera.php" enctype="multipart/form-data">


<h1><span class="intestazione">Crea nuovo Account</span></h1>

<input name="account" type="hidden" value="1">

<div>

    <input id="attivo1" type="radio" checked="checked" value="1" name="attivo"></input>
    <label for="attivo1">Attivo</label>
    <input id="attivo2" type="radio" value="0" name="attivo"></input>
    <label for="attivo2">Sospeso</label>

</div><p> L'account pu&ograve; essere attivato in seguito.</p>



<?php if(defined('MULTI_MARCHIO')) { ?>

<div class="form_row">
<div class="select_text">
<label for="account">Marchio</label>

<select name="marchio" id="marchio" class="selectred" >
<?php
foreach($marchio as $valores => $label){ // Recursione Indici di Categoria
			$selected = (isset($_GET['anagrafica_id']) && $valores == 1) ? 'selected="selected" ' : '' ;
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n";
			} ?>

</select>
</div></div><?php } else { echo '<input name="marchio" id="marchio" value="1" type="hidden">'; } ?>



<div class="form_row">
<p class="select_text">
<label for="account">Tipo di Account</label>

<select name="account" id="account" class=""  >
<?php
foreach($tipo as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($valores == 3) ? 'selected="selected" ' : '' ;
			echo "<option value=\"$valores\" $selected>".ucfirst($label)."</option>\r\n";
			} ?>

</select>
</p></div>

<div class="form_row">
<p class="select_text">
<label for="account">Seleziona Anagrafica</label>

<select name="anagrafica" id="anagrafica" class="" >
<option value="0">Nuova anagrafica</option>

<?php
foreach($anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = (isset($_GET['anagrafica_id']) && check(@$_GET['anagrafica_id']) == $valores) ? 'selected="selected" ' : '' ;
			echo "<option $selected  value=\"$valores\">".ucfirst($label)."</option>\r\n";
			} ?>

</select>
</p></div>


<div class="form_row">
<p class="select_text">
<label for="persona_id">Persona</label>

<select name="persona_id" id="persona_id" class="" >
<option value="0">Nessuno</option>

<?php
foreach($persona_id as $valores => $label){ // Recursione Indici di Categoria
			$selected = (isset($_GET['persona_id']) && check(@$_GET['persona_id']) == $valores) ? 'selected="selected" ' : '' ;
			echo "<option $selected  value=\"$valores\">".ucfirst($label)."</option>\r\n";
			} ?>

</select>
</p></div>


<div class="form_row"><p class="input_text"><label for="nominativo">Nickname</label>
<input  type="text" name="nominativo" id="nominativo"  value="<?php if(isset($_GET['nominativo'])) echo check($_GET['nominativo']); ?>"  />
</p>
</div>

<div class="form_row">
<p class="input_text">
<label for="ip_accesso">Ip accesso</label>
<input  type="text" name="ip_accesso" id="ip_accesso"  value=""  />
</p>
</div>



<div class="form_row">
<p class="input_text"><label for="email">Email: </label>
<input name="email" type="text" value="<?php if(isset($_GET['email'])) echo check($_GET['email']); ?>"  size="40" maxlength="255" />
</p></div>

<div class="form_row">
<p class="input_text"><label for="email2">Conferma Email : </label>
<input name="email2" type="text" value="<?php if(isset($_GET['email'])) echo check($_GET['email']); ?>"  size="40" maxlength="255" />
</p></div>


<h1><strong>Dati di Accesso</strong></h1>

<?php if(defined('USER_PERSONALIZZABILE')) { ?>
<div class="form_row">
<p class="input_text"><label for="user">Username: </label>
<input name="user" type="text"  size="40" maxlength="255" value="<?php if(isset($_GET['email'])) echo substr(str_replace('@','',str_replace('.',date('Y'),check($_GET['email']))),0,8); ?>"  />
</p></div>
<?php } ?>

<div class="form_row">
<p class="input_text">
<label for="password">Password : </label>
<input name="password" id="password" type="password"  size="40" maxlength="15" />

</p></div>
<div class="form_row">
<p class="input_text">
<label for="password2">Conferma : </label>
<input name="password2" id="password2" type="password"  size="40" maxlength="15" />


</p></div>


<div class="form_row">
<p class="input_text">
<input name="auto_pass" type="checkbox" id="auto_pass" /><label for="auto_pass" onclick="spegni();">Crea automaticamente la password</label> 
</p>

</div>
<p>Le credenziali di accesso verranno spedite alla casella di posta inserita.<br />
<strong>Verifica che la mail sia valida e attiva.</strong>.</p>

<p>
<strong>GDPR 2016/679 Informativa sul trattamento dei dati riguardanti il vostro account</strong>
L'accesso alla piattaforma è da intendersi personale ed esclusivamente riservato all'utente autorizzato. 
Ne è vietata la riproduzione sotto ogni forma. La vostra password scade ogni 90 giorni e va reimpostata obbligatoriamente.
In conformità ai requisiti del DL 196 del 30/6/2003, 
si informa che ogni accesso, riconoscibile da IP e username, sarà registrato e potrà essere monitorato.
Le attività eseguite nell'ambiente gestionale sono registrate per motivi di sicurezza.
</p>




<input type="hidden"  name="reload" value="./mod_visualizza.php?id="   />


<input type="submit"  value="Crea Account" class="button"   />








</form><?php }  } ?>
</div>