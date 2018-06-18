


<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$tabs_div = 2;
$tab_div_labels = array();
$tab_div_labels['logo'] = "Info Account";
$tab_div_labels['./mod_user.php?anagrafica_id=[*ID*]'] = "Modifica Password";

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");





 ?>


<body style="rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">
<?php
if($_SESSION['usertype'] == 0){
    $account = @GQD('fl_account','anagrafica', 'id = '.check($_GET['id']));
    echo '<br><br><a href="../mod_anagrafica2/mod_inserisci.php?id='.$account['anagrafica'].'" class="button">Modifica Anagrafica</a>';
}
?>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<?php   include('../mod_basic/action_estrai.php');  ?>
</form>


</div>
</div>
</body></html>
