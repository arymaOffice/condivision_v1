<?php

$loadSelectComuni = 1;
$id = $_SESSION['anagrafica'];
if ($_SESSION['usertype'] > 1) {
    $force_id = $_SESSION['anagrafica'];
}

include 'fl_settings.php'; // Variabili Modulo
$tab_div_labels = array('id' => 'Persona', 'forma_giuridica' => "Dati Fiscali");

?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >



<div id="content_scheda">

<div class="info_dati">
<?php if ($id > 1) {
} else {echo '<h1>Nuovo ' . $tipo_profilo[$tipo_profilo_id] . '</h1>';}

?>




</div>

<?php if (isset($_GET['esito'])) {$class = (isset($_GET['success'])) ? 'green' : 'red';
    echo '<p class="esito ' . $class . '">' . check($_GET['esito']) . '</p>';}?>

<style type="text/css">

/* Personalizzati */
#box_citta,#box_indirizzo {
    width: 83%;
    float: left;
    clear: none;
}

#box_citta label {

}

#box_citta  input,#box_indirizzo input {
	width: 55%;
}

#box_provincia,#box_cap {
width: 133px;
float: right;
padding: 0px 16px 0 0;
clear: none;
}
#box_provincia label {
    width: auto;
    padding: 0;
}
#box_provincia input {
width: 45px;
}



#box_cap {
    width: 133px;
    float: right;
    padding: 0px 16px 0 0;
}
#box_cap label {
    width: auto;
    padding: 0;
}
#box_cap input {
width: 74px;
}


#box_telefono,#box_recapito_alternativo {
	width: 50%;
    clear: none;
    display: inline-block;
}



</style>
<div id="map-canvas"></div>

<form id="scheda" action="/fl_modules/mod_basic/action_modifica.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">

<?php include $_SERVER['DOCUMENT_ROOT'] . '/fl_modules/mod_basic/action_estrai.php';?>

<input type="hidden" name="goto" value="/fl_modules/mod_anagrafica2/mod_opera.php?updateStato" />

</form>

<script>


$("form#scheda :input").each(function(){

 var input = $(this).prop('required',true); // This is the jquery object of the input, do what you will


});

$('#invio').attr('id','nosend');
$('#nosend').html('Procedi <i class="fa fa-arrow-right"></i>');
$('#nosend').attr('href','#ui-id-2');

$( '#nosend' ).click( function( event ) {

    console.log($(this));

if($(this).href != '#ui-id-2' ){

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

}

});
</script>


</div></div></body></html>
