<?php 

require_once('../../fl_core/autentication.php');

include('fl_settings.php'); // Variabili Modulo 

if(check($_GET['id']) != 1 &&  $tab_id != 116) {
	/*Se esite questa array, la scheda modifica viene suddivisa all'occorenza del campo specificato o si possono aggiungere sotto schede */
	$tab_div_labels = array('id'=>"Anagrafica Elemento",'./mod_listino.php?record_id=[*ID*]'=>'Formati');
}

include("../../fl_inc/headers.php");
if(isset($_GET['noBack'])) include("../../fl_inc/testata_mobile.php");


 ?>


<body>
<div id="container" >


<div id="content_scheda">

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>
<h1>Scheda</h1>
<?php   include('../mod_basic/action_estrai.php');  ?>
<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_materieprime/mod_inserisci.php?t=MQ==&id=" /><?php } ?>
<?php if (isset($_GET['noBack']) ) { ?><input type="hidden" name="info" value="1">  <?php } ?>
</form>

<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {


<?php if ($tab_id != 116) { ?>
 function loadSelectIds(where){ 
  var post = 'gtx=<?php echo $tab_id; ?>&sel=categoria_materia&filtro=';
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
  
    var data = $.parseJSON(response);
     console.log(data);
    $.each(data, function(i, value) {
        $(where).append('<option value="'+i+'">'+value+'</option>');
        });
   });
}




  loadSelectIds('#categoria_materia');

 <?php } ?>


});</script>

</div>
</div>
</body></html>
