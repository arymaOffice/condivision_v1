<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo
include "../../fl_inc/headers.php";
include "../../fl_inc/testata_mobile.php";




?>

<body style=" background: #FFFFFF;">
<div id="container" >
<div id="content_scheda">
<form  action="mod_opera.php" method="POST" enctype="multipart/form-data">
<?php if (isset($_GET['esito'])) {$class = (isset($_GET['success'])) ? 'green' : 'red';
    echo '<p class="' . $class . '">' . check($_GET['esito']) . '</p>';}?>


<h1 style="text-align:center;" >Pubblicit&agrave per categoria</h1>

<div style="text-align:center;">

<select name="categoria_ads" required>
    <?php foreach($link_cat as $key => $value){ $selected = ($key == $_GET['categoria_ads']) ? 'selected' :'';  echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';} ?>
</select>

<br>
<br>

<?php foreach($cartelle_pubblicita as $key => $value){ echo '<input type="checkbox" name="cartelle[]" id="folder'.$key.'" value="'.$key.'"><label for="folder'.$key.'" style="display:inline-block;margin:10px;">'.$value.'</label>';} ?>
<br>
<br>

<input type="hidden" value="1" name="pubblicita">
<input type="hidden" value="<?php echo filter_var($_GET['categoria_ads'],FILTER_SANITIZE_NUMBER_INT);?>" name="categoria_ads">

<input type="submit" value="Salva" class="button" style="width: 400px;">
</div>
</form>
</div>
</div>
</body>
</html>
