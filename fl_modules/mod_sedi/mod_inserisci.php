<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>


<script type="text/javascript">
/*Avvio*/
$(document).ready(function() {

function loadProvince(from,where){ 

  var post = 'sel=provincia&filtro=regione&valore='+$(from).val();
  if(from == 0) post = 'sel=provincia&filtro=regione';
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
    console.log(post);
	if(from != 0) $(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).append('<option = "'+value+">"+value+"</option>");
		   $(where).focus();
        });
   });
}

function loadComuni(from,where){ 
  var post = 'sel=comune&filtro=provincia&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
   console.log(post);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).append('<option = "'+value+">"+value+"</option>");
		   $(where).focus();
        });
   });
}

function loadCap(from,where){ 
  var post = 'sel=cap&filtro=comune&valore='+$(from).val();
  var url = '../mod_basic/mod_selectLoader.php';
  var posting = $.post(url,post); 
  posting.fail(function( data ) {     });
  posting.always(function( data ) {    });
  posting.done(function( response ) {
    //console.log(response);
	$(where).empty();
    var data = $.parseJSON(response);
	  $.each(data, function(i, value) {
           $(where).val(value);
		   $(where).focus();
        });
   });
}




loadProvince(0,'#provincia');

$('#provincia').change(function(){
 	loadComuni('#provincia','#citta');
});

$('#citta').change(function(){
 	loadCap('#citta','#cap');
});




});</script>



</div></body></html>
