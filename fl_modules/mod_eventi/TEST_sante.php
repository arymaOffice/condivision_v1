<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include("../../fl_inc/headers.php");
if(!isset($_GET['goto'])) include("../../fl_inc/testata_mobile.php");

?>





<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">


<div id="container" >

<div id="content_scheda">


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">


<div class="form_row" id="box_segnaposti"><p class="input_text"><label for="segnaposti">Segnaposti</label>
<input type="checkbox" data-control="segnaposti" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="segnaposti" id="segnaposti"  value=""  /> </p>
</div>

<div class="form_row" id="box_bomboniere"><p class="input_text"><label for="bomboniere">Bomboniere</label>
<input type="checkbox" data-control="bomboniere" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="bomboniere" id="bomboniere"  value=""  /> </p>
</div>

<div class="form_row" id="box_dolci"><p class="input_text"><label for="dolci">Dolci</label>
<input type="checkbox" data-control="dolci" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="dolci" id="dolci"  value=""  /> </p>
</div>

<div class="form_row" id="box_ospiti_serali"><p class="input_text"><label for="ospiti_serali">Ospiti serali</label>
<input type="checkbox" data-control="ospiti_serali" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="ospiti_serali" id="ospiti_serali"  value=""  /> </p>
</div>

<div class="form_row" id="box_miniclub"><p class="input_text"><label for="miniclub">Miniclub</label>
<input type="checkbox" data-control="miniclub" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="miniclub" id="miniclub"  value=""  /> </p>
</div>

<div class="form_row" id="box_stanza_sposi"><p class="input_text"><label for="stanza_sposi">Stanza sposi</label>
<input type="checkbox" data-control="stanza_sposi" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="stanza_sposi" id="stanza_sposi"  value=""  /> </p>
</div>

<div class="form_row" id="box_stanze_aggiuntive"><p class="input_text"><label for="stanze_aggiuntive">Stanze aggiuntive</label>
<input type="checkbox"  data-control="stanze_aggiuntive" class="EnableDisable" style="display: inline; width: auto; margin: 0; border: 1px solid gray;">
<input type="text" name="stanze_aggiuntive" id="stanze_aggiuntive"  value=""  /> </p>
</div>

<div class="form_row" id="box_note_servizio">
<h3><label for="note_servizio">Note servizio</label></h3>
<p class="input_text"><br /><textarea  name="note_servizio"  class="mceEditor"  id="note_servizio" rows="20" cols="100"  onkeyup="$('#infonote_servizio').html(this.value.length+' caratteri');"></textarea><span id="infonote_servizio"></span></p>
</div>



<p class="savetabs"><a href="#" id="invio" class="button salva"> Salva <i class="fa fa-check"></i></a></p>



</form>



<style>#box_segnaposti { clear: none; } #box_segnaposti .input_text { width: 47%; float: right; clear: none;  } #box_segnaposti .input_text label { width: auto; margin: 0;  } #box_segnaposti .input_text input { width: 58%; margin: 0;  } #box_bomboniere { clear: none; } #box_bomboniere .input_text { width: 47%; float: right; clear: none;  } #box_bomboniere .input_text label { width: auto; margin: 0;  } #box_bomboniere .input_text input { width: 58%; margin: 0;  } #box_dolci { clear: none; } #box_dolci .input_text { width: 47%; float: right; clear: none;  } #box_dolci .input_text label { width: auto; margin: 0;  } #box_dolci .input_text input { width: 58%; margin: 0;  } #box_ospiti_serali { clear: none; } #box_ospiti_serali .input_text { width: 47%; float: right; clear: none;  } #box_ospiti_serali .input_text label { width: auto; margin: 0;  } #box_ospiti_serali .input_text input { width: 58%; margin: 0;  } #box_miniclub { clear: none; } #box_miniclub .input_text { width: 47%; float: right; clear: none;  } #box_miniclub .input_text label { width: auto; margin: 0;  } #box_miniclub .input_text input { width: 58%; margin: 0;  } #box_stanza_sposi { clear: none; } #box_stanza_sposi .input_text { width: 47%; float: right; clear: none;  } #box_stanza_sposi .input_text label { width: auto; margin: 0;  } #box_stanza_sposi .input_text input { width: 58%; margin: 0;  } #box_stanze_aggiuntive { clear: none; } #box_stanze_aggiuntive .input_text { width: 47%; float: right; clear: none;  } #box_stanze_aggiuntive .input_text label { width: auto; margin: 0;  } #box_stanze_aggiuntive .input_text input { width: 58%; margin: 0;  } </style>

<script>
    
    var MycheckBox = document.getElementsByClassName("EnableDisable");
    
    for(var i = 0; i < MycheckBox.length; i++){
        var elemento = MycheckBox[i];
        elemento.nextSibling.nextSibling.value = "NO"
        elemento.addEventListener('click', function(evento){
             
             if(evento.target.checked){
                 evento.target.nextSibling.nextSibling.focusable = true;
                 evento.target.nextSibling.nextSibling.value = "";
            }else{
                 evento.target.nextSibling.nextSibling.readonly = true;
                 evento.target.nextSibling.nextSibling.value = "NO";
         }
       }, false);
    }
   
</script>

</div></div></body></html>

