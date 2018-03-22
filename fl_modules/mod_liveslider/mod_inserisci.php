<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$risoluzione = check(@$_GET['risoluzione']);
$n_monitor = (@$_GET['n_monitor'] == '' || @$_GET['n_monitor'] == 'undefined' ) ? 1 : check(@$_GET['n_monitor']) ;
$categoria_link = check(@$_GET['categoria_link']);
$id = check(@$_GET['id']);
$style='';
include("../../fl_inc/headers.php");?>

<body style=" background: #FFFFFF;">
<div id="container" >
<div id="content_scheda">

<form id="scheda" action="./mod_opera.php" method="GET" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'" style="padding: 20px;">'.check($_GET['esito']).'</p>'; 
$style="display:none";
}  ?>
<div id="formSlider" style="<?php echo $style; ?>">
<h1 style="text-align:center;" >Salva il tuo slider</h1>
<h2 style="text-align:center;">Come vuoi chiamarlo ?</h2>
<input type="text" style="margin: 10px 15px;" name="nome_slider" required  placeholder="Nome slider">
<input type="hidden" name="risoluzione" value="<?php echo $risoluzione; ?>">
<input type="hidden" name="n_monitor" value="<?php echo $n_monitor; ?>">
<input type="hidden" name="categoria_link" value="<?php echo $categoria_link; ?>">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="submit"  value="Salva slider" class="button" style="margin: 10px 15px;">
</form>

<div>
</div>
</div>
</body></html>
