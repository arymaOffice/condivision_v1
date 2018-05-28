<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
if(!isset($_GET['goto'])) include("../../fl_inc/testata_mobile.php");

 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p style="padding: 13px; width: 56%;"  class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div class="info_dati">
<?php 

$potential = GRD('fl_potentials', check($_GET['potential_rel']) ); 

$telefono = phone_format($potential['telefono'],'39');
$social = '<div class="social_icons" style= "font-size: 200%;"><a href="https://www.linkedin.com/commonSearch?type=people&keywords='.$potential['nome'].'%20'.$potential['cognome'].'" target="_blank" title="Cerca questo contatto su Linkedin"><i class="fa fa-linkedin-square"></i></a>
<a href="https://www.facebook.com/search/top/?q='.$potential['nome'].'%20'.$potential['cognome'].'&init=mag_glass"  target="_blank" title="Cerca questo contatto su Facebook"><i class="fa fa-facebook-square"></i></a>
<a href="https://twitter.com/search?q='.$potential['nome'].'%20'.$potential['cognome'].'&src=typd"  target="_blank" title="Cerca questo contatto su Twitter"><i class="fa fa-twitter-square"></i></a>
</div>';
echo '<h1><strong>'.$potential['nome'].' '.$potential['cognome'].'</strong></h1>'.$social;
echo '<h2>Sorgente: '.@$source_potential[$potential['source_potential']].' -  Impiego: '.@$potential['industry'].'</h2>';
echo '<p>Tel: <a href="tel:'.@$telefono.'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="2"  data-esito="2" data-note="Phone call started">'.@$telefono.'</a> mail: <a href="mailto:'.@$potential['email'].'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="3"  data-esito="5"  data-note="Start writing">'.@$potential['email'].'</a></p>';


?>
</div>

<!--<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=1" class="touch blue_push"><i class="fa fa-thumbs-up"></i> <br>Presentato</a>-->
<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=3" class="touch red_push"><i class="fa fa-thumbs-down"></i> <br>Non presentato</a>
<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=7" class="touch orange_push setAction" data-gtx="<?php echo base64_encode(16); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="7"  data-esito="1"  data-note="Preventivo"><i class="fa fa-pencil-square-o"></i> <br>Preventivo</a>
<a href="mod_opera.php?profile_rel=<?php echo $potential['id']; ?>&id=<?php echo check($_GET['id']); ?>&issue=2" class="touch green_push setAction" data-gtx="<?php echo base64_encode(16); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="6"  data-esito="5" data-note="Conversione Cliente"><i class="fa fa-check-square-o"></i> <br>Converti in cliente</a>


<!--<a href="../mod_anagrafica/mod_inserisci.php?external&action=1&id=1&potential_id=<?php echo $potential['id']; ?>&nominativo=<?php echo $potential['nome'].' '.$potential['cognome']; ?>" class="touch green_push"><i class="fa fa-thumbs-up"></i> <br>Interested</a>
-->



<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>



<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>
<?php if(isset($_GET['goto'])) { echo '<input type="hidden" name="goto" value="'.check($_GET['goto']).'" />'; } ?>
</form>
<?php 
if($id > 1) echo "<a  href=\"../mod_basic/action_elimina.php?POST_BACK_PAGE=../mod_appuntamenti/&gtx=$tab_id&amp;unset=".$id."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>";
?>
</div></div></body></html>
