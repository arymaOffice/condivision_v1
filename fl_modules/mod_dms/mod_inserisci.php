<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");

$id = (isset($_GET['n'])) ? 1 : check($_GET['id']);
$folder_info = folder_info($id);
 ?>


<body style=" background: #FFFFFF;">
<div id="container" >


<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Risorsa</h1>

<?php if(@$folder_info['file'] != '') { ?>
<a href="mod_opera.php?ac=copy&move=<?php echo $id;?>"><i class="fa fa-files-o"></i> Copia </a> | 
<a href="mod_opera.php?ac=cut&move=<?php echo $id;?>"><i class="fa fa-scissors"></i> Taglia </a>
<?php } ?>
<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>
</div>
</div></body></html>
