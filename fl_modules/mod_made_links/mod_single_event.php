<?php
// Controlli di Sicurezza
require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

$new_button = '';
$id = check($_GET['id']);

$link_info = GQD($tabella, '*', 'id = ' . $id);

$module_title = $categoria_link[$link_info['categoria_link']] . ' - ' . $link_info['titolo'];

$multimonitor = ($link_info['multimonitor']) ? '<span class="msg blue"> multimonitor </span>' : '';

$risoluzioni = GQS('fl_link_resolution', '*', 'id > 1');

include "../../fl_inc/headers.php";
include '../../fl_inc/testata.php';
include '../../fl_inc/menu.php';
include '../../fl_inc/module_menu.php';

?>


<style>
@media screen and (max-width: 400px) {
  .hide-mobile{
    display:none !important;
  }

  .laterale-diselezione{
    display: block;
    width: 100% !important;

  }

  .dati{
    display: block;
    width: 100% !important;

  }

}
.laterale-diselezione{
  width: 30%;
  float: left;
  margin: 52px auto  20px auto;
  padding: 8px;
}
.conferma{
  width: 100%;
  text-align: center;
  background:green !important;
  font-size: 16px;
  font-weight: bold !important;
  padding: 8px;
}

.back-monitor{
  width: 70%;
  position: absolute;
  top: 110px;
  left: 0px;
  z-index: -10;

}

#content{
  background: transparent !important;

}

.dati{
  background: transparent;
}

.dati tr:nth-child(even) {
  background: transparent !important;
}

.dati tr:nth-child(odd) {
  background: transparent !important;
}
label{
  text-align: center !important;
  font-size: 14px;
  margin:20px;
}


input[type="radio"]:checked + label, input[type="checkbox"]:checked + label {
  background-color: green;
}

input.button, .button input, .button, .button a, a.button, .salva, a.salva, a.create_new {
  background-color: #F6CD40;
}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 30%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
<h3><?php echo $link_info['sottotitolo']; ?> </h3>
<table class="dati" summary="Dati" style="width: 70%;float: left;">

  <tr><td><?php echo $multimonitor . $link_info['descrizione']; ?></td></tr>
</table>

<div class="laterale-diselezione" >
  <form id="monitor">
    <span >SELEZIONA LA DIMENSIONE DEL MONITOR CHE SI DESIDERA VISULIZZARE</span>
    <a href="#" class="button"  style="width: 100%;text-align: center;">POLLICI MONITOR <i class="fa fa-caret-right" style="margin-left:30px"></i></a>
    <div class="options" >
      <br>
      <?php foreach ($risoluzioni as $value) {

    echo '<input required="required" type="radio" id="pollici' . $value['id'] . '" name="pollici" value="' . $value['id'] . '"><label for="pollici' . $value['id'] . '">' . $value['pollici'] . '</label>';

}?>

    </div>
    <?php if ($link_info['multimonitor']) {?>
    <br>
    <br>
    <span>SELEZIONA IL NUMERO DEI MONITOR CHE SI DESIDERA VISULIZZARE</span>
    <a href="#" class="button" style="width: 100%;text-align: center;">NUMERO MONITOR <i class="fa fa-caret-right" style="margin-left:30px"></i></a>
    <br>
    <div class="options" >
      <br>
      <?php for ($i = 1; $i < 25; $i++) {
    echo '<input required="required" type="radio" id="n_monitor' . $i . '" name="n_monitor" value="' . $i . '"><label for="n_monitor' . $i . '">' . $i . '</label>';
}
    ?>
    </div>
    <?php }?>
    <input type="submit" id="conferma" class="button conferma" value="CONFERMA" >
    <br>
    <br>
    <a onclick="openNewwindow()" style="display:none" class="button conferma saveslider " href="#" >SALVA SLIDER</a>
    <br><br>
    <div>
      <div id="tbMonitor" style="min-width: 100px;width: 100%;">
      </div>
    </div>

  </div>

  <img src="<?php echo DMS_PUBLIC; ?>/monitor.png" alt="immagine monitor" class="back-monitor">

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <iframe style="height:350px;width:100%" id="iframeLink" src=""></iframe>
  </div>

</div>


  <script type="text/javascript">

  function openNewwindow(){
    $('#myModal').css('display','block');

   var link = '../mod_liveslider/mod_user.php?categoria_link=<?php echo $link_info['categoria_link']; ?>&id=<?php echo $id; ?>&risoluzione='+$('input[name="pollici"]:checked').val()+'&n_monitor='+$('input[name="n_monitor"]:checked').val();

   $('#iframeLink').prop('src',link);


  }



  $('.close').click(function(e){ $('#myModal').css('display','none'); });


    $('#conferma').click(function(e){

      e.preventDefault();

      form = $('#monitor').serialize();


      $.get('mod_opera.php',{form : form , link_id : <?php echo $link_info['link_id']; ?> ,},function(data){

        var parsed = $.parseJSON(data);
        if(parsed.esito == 1){

          $('#tbMonitor').empty();
          n_monitor = $("input[name='n_monitor']:checked");
          if(n_monitor.val() == undefined ) {valore = 1;}else{valore = n_monitor.val() }
            for (i = 0; i < valore; i++){
              $('#tbMonitor').append('<a href="#" onclick="window.open(\''+parsed.data[i]+'\',\'1x2\',\'directories=no,titlebar=no,toolbar=no,location=0,status=no,menubar=no,scrollbars=no\')"><div style="float:left;margin: 6px;"><i class="fa fa-tv fa-2x"></i></div></a>');
            }
            $('.saveslider').css('display','block');
        }else{
          alert("Seleziona i campi mancanti");
        }


      });
    });


    </script>