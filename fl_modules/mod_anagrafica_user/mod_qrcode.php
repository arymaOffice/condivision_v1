<?php 

require_once('../../fl_core/autentication.php');
include('../../fl_core/dataset/items_rel.php');
$id = check($_GET['id']);
$profilo = GRD('fl_profili',$id);
$tipo_profilo_scheda = array('fl_contents','fl_contents','hotels','concessionarie');
$link = 'http://menutranslate.condivision.biz/'.$tipo_profilo_scheda[$profilo['tipo_profilo_scheda']].'/?client='.$id; 
include("../../fl_inc/headers.php");
 ?>
 
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%; text-align: center; padding: 20px;">

<div style="text-align: center; margin: 0 auto;">
<a href="<?php echo $link; ?>"><img src="http://chart.apis.google.com/chart?cht=qr&chs=500x500&chl=<?php echo $link; ?>" alt="Qr Code" />
<h2><?php echo $link; ?></h2></a>
<a href="<?php echo $link; ?>&language_edit=1"> Gestisci Traduzioni </a>
</div>

</body></html>