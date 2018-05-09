<?php

require_once '../../fl_core/autentication.php';
$loadSelectComuni = 1;

$id = ($_SESSION['usertype'] > 1) ? $_SESSION['anagrafica'] : check($_GET['id']);


if ($id > 1) {
    $profilo = @GRD('fl_anagrafica', @$id);
}

include 'fl_settings.php'; // Variabili Modulo
include "../../fl_inc/headers.php";

if (!isset($_GET['view'])) {
    include "../../fl_inc/testata_mobile.php";
}


$tab_div_labels = array('marchio' => 'Profilo', 'nome' => 'Dati Anagrafici', 'tipo_documento' => "Dati Documento", 'forma_giuridica' => "Dati Fiscali Sede", 'tipologia_attivita' => 'Dati Punto', 'lat' => $etichette_anagrafica['tipologia_attivita'], 'telefono' => "Contatti");

?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">
<div class="info_dati">
<?php if ($id > 1) {
    $telefono = phone_format($profilo['telefono'], '39');
    echo '<h1><strong>' . $profilo['ragione_sociale'] . '</strong> (' . @$profilo['nome'] . ' ' . @$profilo['cognome'] . ')</h1>';
    if (ALERT_DOCUMENTO_SCADUTO == 1) {
        echo '<h2>Tipo Delega: <span class="msg gray">' . @$pagamenti_f24[@$profilo['pagamenti_f24']] . '</span></h2>';
    }

    echo '<p>Telefono: <a href="tel:' . @$telefono . '">' . @$telefono . '</a></h2>';
} else {echo '<h1>Nuovo ' . $tipo_profilo[$tipo_profilo_id] . '</h1>';}

?>
</div>
<?php if (isset($_GET['esito'])) {$class = (isset($_GET['success'])) ? 'green' : 'red';
    echo '<p class="esito ' . $class . '">' . check($_GET['esito']) . '</p>';}?>
<div id="map-canvas"></div>
<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include '../mod_basic/action_estrai.php';?>
<?php 
if(!isset($_GET['error']) && isset($_GET['first']) && $_GET['id'] > 1){
    echo '<input type="hidden" name="goto" value="../mod_account/mod_inserisci.php?id=1&&anagrafica_id='.$id.'" />';
}
if($_SESSION['usertype'] > 1){
   echo "<script> $( '#marchio' ).css('display','none); </script>";
}
?>
</form>
<script>
$('label[for="status_anagrafica1"]').text('attiva');
$('label[for="status_anagrafica2"]').text('disattiva');
$( '#marchio' ).val('<?php echo $profilo['marchio']; ?>');


$('#invio').attr('id','nosend');
$('#nosend').click(function(event){
    event.preventDefault();
        //validate fields
        var fail = false;
        var fail_log = '';
        $( '#scheda' ).find( 'select, textarea, input' ).each(function(){
            if( ! $( this ).prop( 'required' )){
            } else {
                if ( ! $( this ).val() ) {
                    fail = true;
                    name = $( this ).attr( 'name' );
                    fail_log += name + " is required \n";
                }
            }
        });
        //submit if fail never got set to true
        if ( ! fail ) {
        $('#scheda').submit();
        } else {
            alert( fail_log );
        }
});
</script>
</div></div></body></html>
