<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
if(!isset($_GET['goto'])) include("../../fl_inc/testata_mobile.php");


?>






<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >

<div id="content_scheda">

<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {


	$('#nominativo').on('input',function(e){
   
     if($("#resultnominativo").length == 0) { $(this).after( '<br><span id="resultnominativo"></span>');  }
     $("#resultnominativo").load('../mod_basic/mod_field_search.php?gtx=106&w[]=cognome&w[]=nome&w[]=telefono&q[]='+$(this).val());

	});



});


function loadLead(id,nominativo,email,tel,source_potential){ 

 $("#potential_rel").val(id);
 $("#nominativo").val(nominativo);
 $("#email").val(email);
 $("#telefono").val(tel);
 $("#box_campagna_id").hide();
 $("#resultnominativo").empty();
}


</script>

<?php 

if(isset($_GET['potential_rel'])) {

$potential = GRD($tables[106], check($_GET['potential_rel']) ); 

if($potential['id'] > 1){

    	$colore = '';
		if($potential['status_potential'] == 0) { $colore = "class=\" msg tab_blue\""; echo '<style>#box_data_visita { display: none; </style>' ; }
		if($potential['status_potential'] == 1) { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 2) { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 3) { $colore = "class=\"msg tab_red\"";  } 
		if($potential['status_potential'] == 4)  { $colore = "class=\"msg tab_green\"";  }
		if($potential['status_potential'] == 5)  { $colore = "class=\"msg tab_orange\"";  }
		if($potential['status_potential'] == 6) { $colore = "class=\"msg tab_red\"";  } 
		if($potential['status_potential'] == 7)  { $colore = "class=\"msg tab_orange\"";  }

$status = '<span '.$colore.'>'.@$status_potential[$potential['status_potential']].'</span>'; 


$telefono = phone_format($potential['telefono'],'39');
$social = ' <span class="social_icons" style= "font-size: 100%;"><a href="https://www.linkedin.com/commonSearch?type=people&keywords='.$potential['nome'].'%20'.$potential['cognome'].'" target="_blank" title="Cerca questo contatto su Linkedin"><i class="fa fa-linkedin-square"></i></a>
<a href="https://www.facebook.com/search/top/?q='.$potential['nome'].'%20'.$potential['cognome'].'&init=mag_glass"  target="_blank" title="Cerca questo contatto su Facebook"><i class="fa fa-facebook-square"></i></a>
<a href="https://twitter.com/search?q='.$potential['nome'].'%20'.$potential['cognome'].'&src=typd"  target="_blank" title="Cerca questo contatto su Twitter"><i class="fa fa-twitter-square"></i></a>
</span>';
echo '<div class="info_dati"><h1 style="display: inline-block; margin: 0 0 5px;"><strong><a href="../mod_leads/mod_inserisci.php?id='.$potential['id'].'">'.$potential['nome'].' '.$potential['cognome'].' [Vai a scheda contatto]</a></strong></h1> ';
echo '<p style="margin: 5px 0px;"><i class="fa fa-phone" style="padding: 3px;"></i> <a href="tel:'.@$telefono.'" class="setAction" style="color: black;" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="2"  data-esito="2" data-note="Avvio Chiamata">'.@$telefono.'</a> <i class="fa fa-envelope-o" style="padding: 3px;"></i> <a style="color: black;" href="mailto:'.@$potential['email'].'" class="setAction" data-gtx="'.base64_encode($tab_id).'" data-id="'.base64_encode($potential['id']).'" data-azione="3"  data-esito="5"  data-note="Avvio Composizione Email">'.@$potential['email'].'</a></p>';
echo $status.$social;

echo '<textarea cols="3" name="note" class="updateField" id="noteRisalto" placeholder="Note:" data-gtx="106" data-rel="'.$potential['id'].'">'.strip_tags(converti_txt($potential['note'])).'</textarea>';

} else { echo '<h2>Il Lead associato a questo appuntamento è stato eliminato</h2>'; }
?>


<?php 

$id = check($_GET['id']);
if($id > 1) {
	$appuntamento = GRD($tabella,$id);
	if($appuntamento['tipologia_appuntamento'] == 122) {
	?>
<br class="clear" />
<a style="margin: 0;" href="mod_opera.php?profile_rel=<?php echo ($potential['id'] > 1) ? $potential['id'] : 1; ?>&id=<?php echo check($_GET['id']); ?>&issue=7" class="touch blue_push" styl><i class="fa fa-thumbs-up"></i> <br>Visita Effettuata </a>
<a style="margin: 0;" href="mod_opera.php?profile_rel=<?php echo ($potential['id'] > 1) ? $potential['id'] : 1; ?>&id=<?php echo check($_GET['id']); ?>&issue=3" class="touch red_push"><i class="fa fa-thumbs-down"></i> <br>Non presentato</a>
<?php }} ?>
<!--<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_modelli/mod_catalogo.php?tipo_modello=1&POiD=<?php echo base64_encode($potential['id']); ?>&id=1" class="touch orange_push setAction" data-gtx="<?php echo base64_encode(16); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="7"  data-esito="1"  data-note="Preventivo"><i class="fa fa-pencil-square-o"></i> <br>Preventivo</a>
<a href="<?php echo ROOT.$cp_admin; ?>fl_modules/mod_anagrafica/mod_inserisci.php?id=1&j=<?php echo base64_encode($potential['id']); ?>" class="touch green_push setAction" data-gtx="<?php echo base64_encode(16); ?>" data-id="<?php echo base64_encode($potential['id']); ?>" data-azione="6"  data-esito="5" data-note="Conversione Cliente"><i class="fa fa-check-square-o"></i> <br>Converti in cliente</a>
<a href="../mod_anagrafica/mod_inserisci.php?external&action=1&id=1&potential_id=<?php echo $potential['id']; ?>&nominativo=<?php echo $potential['nome'].' '.$potential['cognome']; ?>" class="touch green_push"><i class="fa fa-thumbs-up"></i> <br>Interested</a>
-->

<?php } ?>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php');  ?>



<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>
<?php if(isset($_GET['goto'])) { echo '<input type="hidden" name="goto" value="'.check($_GET['goto']).'" />'; } ?>
<?php if(check($_GET['id']) == 1 && !isset($_GET['potential_rel'])) { echo '<input type="hidden" name="function" value="creaLeadDaAppuntamento" />'; 

} else {
echo "<a  href=\"../mod_basic/action_elimina.php?POST_BACK_PAGE=../mod_appuntamenti/&gtx=$tab_id&amp;unset=".$id."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>";

}

if($potential['id'] > 1){

// Carico i dati del lead nel form dell'appuntamento	
echo "<script> loadLead(".$potential['id'].",'".$potential['nome']." ".$potential['cognome']."','".$potential['email']."','".$potential['telefono']."',".$potential['source_potential']."); </script>";

}
?>

</form>

</div></div></body></html>
