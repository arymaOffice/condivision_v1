<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 


include("../../fl_inc/headers.php");?>



<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>

<?php 
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
if($_SESSION['usertype'] > 0){
include("mod_user.php"); 
}else{
include("mod_home.php"); 
}?>


<?php include("../../fl_inc/footer.php"); ?>
