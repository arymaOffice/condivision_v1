<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
$tab_id = 124;
$tabella = $tables[$tab_id];
$tab_div_labels = array('id'=>'Scheda Wedding','note_intolleranze'=>"Intolleranze",'note_servizio'=>"Note");

include("../../fl_inc/headers.php");
if(!isset($_GET['goto'])) include("../../fl_inc/testata_mobile.php");

 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php'); ?>
</form>



<?php if(check($_GET['id']) != 1) { 
echo "<a  href=\"../mod_basic/action_elimina.php?POST_BACK_PAGE=../mod_appuntamenti/&gtx=$tab_id&amp;unset=".$id."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>";
} 



$ifYesText = array("segnaposti", "bomboniere", "dolci", "ospiti_serali", "miniclub", "stanza_sposi", "stanze_aggiuntive");	
echo '<style>';
foreach ($ifYesText as $key => $value) {
	echo "#box_$value { clear: none; } ";
	echo "#box_$value .input_text { width: 47%; float: right; clear: none;  } ";
	echo "#box_$value .input_text label { width: auto; margin: 0;  } ";
	echo "#box_$value .input_text input { width: 58%; margin: 0;  } ";
	
}
echo '</style>';
			

?>

<script>
    
    var MycheckBox = document.getElementsByClassName("EnableDisable");
    
    for(var i = 0; i < MycheckBox.length; i++){
        var elemento = MycheckBox[i];
        elemento.nextSibling.nextSibling.value = "NO"
        elemento.addEventListener('click', function(evento){
             
             if(evento.target.checked){
                 evento.target.nextSibling.nextSibling.disabled = false;
                 evento.target.nextSibling.nextSibling.value = "";
            }else{
                 evento.target.nextSibling.nextSibling.disabled = true;
                 evento.target.nextSibling.nextSibling.value = "NO";
         }
       }, false);
    }
   
</script>

</div></div></body></html>
