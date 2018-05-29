<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include('../../fl_inc/module_menu.php'); ?>


<script type="text/javascript">


$(document).ready(function() {



$(document).on('click',".ajaxLoad",function (event) {

var href = $(this).attr('href');
var button = $(this);
var posting = $.get(href+'&ajax'); 
button.html( 'Attendi...' );
posting.fail(function( data ) {   $(this).html( 'Saving Error' );    });
posting.always(function( data ) {  $(this).html( 'Saving action' );  });
posting.done(function( response ) {  //button.hide();
   
   var data = $.parseJSON(response);
   button.html( data.esito );

   $('#'+data.aggiorna).html(data.valore);

   if(data.aggiorna == 'posizione') {
      var valore = data.valore;
      var split = valore.split(',');

   	var lat = split[0];
   	var lon = split[1];
   	var url = '../mod_basic/mod_map.php?lat='+lat+'&lon='+lon;

	$.fancybox.open({
            'autoScale': true,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'speedIn': 500,
            'speedOut': 300,
            'autoDimensions': true,
			'type' : 'iframe',
			'href' : url,			
            'centerOnScroll': true 
				
		});
   }//fine posiszione

   if(data.aggiorna == 'versione'){

      $('#versioni').css('display','block');
      $('.modal-content').append(data.valore);
   }//fine versione

   if(data.aggiorna == 'quotazione'){
      $('#versioni').css('display','none');
      $('.modal-content').empty();
      $('#btquotazione'+data.id).html(data.esito);
      $('#quotazione'+data.id).val(data.valore);
   }

   if(data.aggiorna == 'btquotazione'){
    $('#quotazione'+data.id).val('0');
   }

    
});

event.preventDefault();
});//fine .ajaxLoad

$('.close').click(function(){
   $('#versioni').css('display','none');
});

});
</script>  

<div id="versioni" style="display: none;position: fixed;
    z-index: 10000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.69); /* Black w/ opacity */">
     <div class="modal-content" style="background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;">
    <span class="close" style="color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;cursor:pointer">&times;</span>
    <h3>Seleziona versione auto</h3>
    </div>
    </div>


<?php /* Inclusione Pagina */ if(isset($_GET['action'])) { include($pagine[$_GET['action']]); } else {
	$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
 
 include("mod_home.php"); }

if(isset($_SESSION['synapsy'])) {
	echo '<div class="info_alert right"><i class="fa fa-share-alt"></i> '.$_SESSION['synapsy_info'].'</a><a href="mod_opera.php?unset" onClick="return conferma(\'Annullare?\');" class="elimina">x</a></div>';
} 
 ?>