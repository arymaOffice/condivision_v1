<?php

$number = $_SESSION['number'];
$id = (isset($_GET['id'])) ? check($_GET['id']) : $number;

?>
<div id="content">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<h1>Modifica Password di accesso</h1>
<p><?php if($_SESSION['aggiornamento_password'] < -90) echo "<div class=\"red\" style=\"padding: 5px;\">Devi obbligatoriamente scegliere una nuova password per accedere al sistema.</div>"; ?></p>
<form id="scheda2" action="./mod_opera.php" method="post" enctype="multipart/form-data">

<div class="form_row">
<div class="input_text">
<label for="password">Nuova Password : </label>
<input name="password1" id="password1" type="password"  size="40" maxlength="15" />
</div></div>

<div class="form_row">
<div class="input_text">
<label for="password2">Conferma Password : </label>
<input name="password2" id="password2" type="password"  size="40" maxlength="15" />
</div></div>
<br>
<input type="hidden" name="modifica_pass" value="<?php echo ($_SESSION['usertype'] > 0) ? $_SESSION['number'] : $id; ?>">
<input type="submit" class="button" value="Reimposta password" />







</form>
</div>
