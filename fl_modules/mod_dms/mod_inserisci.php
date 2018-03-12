<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");

$id = (isset($_GET['n'])) ? 1 : check($_GET['id']);
$folder_info = folder_info($id);
?>



<div id="container" >


<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">

<h1>Dettagli Risorsa</h1>
<?php 
echo 'Proprietà della risorsa: '.$account_id[base64_decode(check($_GET['AiD']))]; //.' '.$proprietario[$folder_info['proprietario']];
if($id > 1 && (base64_decode(check($_GET['AiD'])) > 11 || $folder_info['proprietario'] == $_SESSION['number'] || $_SESSION['usertype'] == 0)) echo " | <a href=\"../mod_basic/action_elimina.php?gtx=$tab_id&amp;unset=".$id."&POST_BACK_PAGE\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>"; ?>
<?php if(@$folder_info['file'] != '') { ?>
 | <a href="mod_opera.php?ac=copy&move=<?php echo $id;?>"><i class="fa fa-files-o"></i> Copia </a> | 
<a href="mod_opera.php?ac=cut&move=<?php echo $id;?>"><i class="fa fa-scissors"></i> Taglia </a>
<?php } ?>
<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>

</form>

</div>
</div></body></html>
