<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if($_SESSION['usertype'] > 1) { echo "NON PUOI ESEGUIRE QUESTA OPERAZIONE!"; exit;  }

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>





<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<script type="text/javascript">


$(document).ready(function() {



 $("#dare").keyup(function(event){
      var $this = $(this);
      var num = $this.val().replace(',', ".");
      $this.val(num);
 });



$( "#invio").unbind( "click" );// Disattivo invio di default


	/*Dynamic form processor ajax */
$( "#invio" ).click(function( event ) {
 
if($('#dare').val() == '0.000' || $('#dare').val() == '') { alert('Inserisci un importo!');

 } else {

$('#cf_proprietario').val('<?php echo strtoupper($proprietario[$_GET['proprietario']]); ?>');
$('#cf_causale').val('<?php echo strtoupper($causale[$_GET['causale']]); ?>');

$('#cf_rif_causale').val($('#rif_causale option:selected').html());
$('#cf_metodo_di_pagamento').val($('#metodo_di_pagamento option:selected').html());
$('#cf_dare').val($('#dare').val());
$('#cf_data_operazione').val($('#data_operazione').val());
$('#cf_informazioni_aggiuntive').val($('#informazioni_aggiuntive').val());
$('#cf_estremi_del_pagamento').val($('#estremi_del_pagamento').val());


$.fancybox({             

            'autoScale': false,
            'transitionIn': 'elastic',
            'transitionOut': 'elastic',
            'width'    : "100%",
   			'height'   : "100%",
            'speedIn': 500,
            'speedOut': 300,            
            'href' : '#anteprima'
 

        });
}

});






});

function sendConfirmation(){

var flag = confirm('Confermi operazione?'); 
if(flag==true){ 

var form = $( "#conferma" ),
url = '../mod_basic/save_data.php';
var data = new FormData(form[0]);
if($("#results2").length == 0) {  form.before('<div id="results"></div>'); }

	jQuery.ajax({
    url: url,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'POST',
    fail: function(data){   $( "#results2" ).empty().append( '<div class="esito red">Errore di caricamento</div>' );   }, 
    always: function(data){ $( "#results2" ).empty().append( '<div class="esito orange">Creazione in corso...</div>' );  }, 
    success: function(response){
    $("#container").show();
    console.log(response);
    var data = $.parseJSON(response);
    $('body,html').animate({scrollTop: 0}, 800);
    if(data.class != 'red') window.top.location.href = '../mod_depositi/?action=5&esito=Operazione conclusa correttamente&a=gestione_pvr';
    if(data.class == 'red') window.top.location.href = '../mod_depositi/?action=5&error&esito='+data.esito+'&a=gestione_pvr';  //$.fancybox.close();
    }
	});
	}
} // Funzione invio form ajax



</script>
<div id="container" >

<div id="anteprima" style="display: none;">

<h1>RIEPILOGO OPERAZIONE</h1>
<div id="results2"></div>
<div id="results2"></div>

<form id="conferma" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data" style="min-width: 1000px;">
<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
<div class="ui-widget-content">


<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_proprietario">USER:  </label><input  id="cf_proprietario" value="user" type="text" disabled=""> </p>
</div>

<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_causale">OPERAZIONE CONTABILE:  </label><input  id="cf_causale" value="DEPOSITO" type="text" disabled=""> </p>
</div>

<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_rif_causale">CAUSALE:  </label><input id="cf_rif_causale" value="RICARICA PLATFOND" type="text" disabled=""> </p>
</div>


<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_dare">IMPORTO OPERAZIONE:  </label><input  id="cf_dare" value="0.00" type="text" disabled=""> </p>
</div>

<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_data_operazione">DATA OPERAZIONE:  </label><input  id="cf_data_operazione" value="<?php echo date('d/m/Y'); ?>" type="text" disabled=""> </p>
</div>

<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_metodo_di_pagamento">METODO DI PAGAMENTO:  </label><input  id="cf_metodo_di_pagamento" value="" type="text" disabled=""> </p>
</div>




<div class="form_row" id="box_dare"><p class="input_text">
<label for="cf_estremi_del_pagamento">INFORMAZIONI AGGIUNTIVE  </label><input  id="cf_estremi_del_pagamento" value="" type="text" disabled=""> </p>
</div>

</div></div>
</form>
<hr>
<a href="../mod_depositi/?action=11&causale=84&a=gestione_pvr" class="button" style="background: #D91C1C; padding:  20px; width: 40%;">ANNULLA OPERAZIONE</a>
<a href="#" onclick="sendConfirmation();" class="button" style="background: #5D9A42; padding:  20px;  width: 40%;">CONFERMA OPERAZIONE</a>
</div>

<div id="content_scheda">




<form id="conferma" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">



<?php include('../mod_basic/action_estrai.php');  ?>


</form>



</div></body></html>
