<?php 
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include("../../fl_inc/headers.php");

 ?>

<style>
.loader {
  border: 5px solid #f3f3f3;
  border-radius: 50%;
  border-top: 5px solid #848484;
  width: 60px;
  height: 60px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
  top:0;
  left:0;
  position:absolute;
  margin : 45% 45%;
	display:none;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>


<body style=" background: #FFFFFF;">
<?php if(isset($_GET['esito'])) { echo '<div id="results"><h2 class="green">'.check($_GET['esito']).'</h2></div>'; 

} else { 

$id = check($_GET['id']);
$lead_id = check($_GET['lead_id']);
$offerta = GRD($tabella,$id);
$persona = ($offerta['cliente_id'] > 1) ? GRD('fl_anagrafica',$offerta['cliente_id']) : GRD($tables[106],$offerta['potential_id']); 


?>
<div class="loader"></div>

<form id="scheda2" action="mod_opera.php" method="get" enctype="multipart/form-data" style="width: 90%; margin: 0 auto; ">
<h1>Invia Offerta</h1>


<p><label>Email:</label> <input type="text" name="email" value="<?php echo strtolower($persona['email']); ?>"  style=" width: 90%;" /></p>
<p><label>Oggetto:</label><input type="text" name="oggetto" value="Invio preventivo" style=" width: 90%;" /></p>
<label>Messaggio:</label><textarea name="messaggio">Gentile <?php echo $persona['nome']; ?>, le inviamo l'offerta richiesta. Cordiali Saluti. </textarea>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<p><input type="submit" value="Invia Offerta" class="button" style=" width: 90%;" /></p>
<br>

</form><?php } ?>

<script type="text/javascript">

$('input[type="submit"]').click(function(){
    if($('input[name="email"]').val() != ''){
        $('.loader').css('display','block');
        $('.loader').css('z-index','99999');
    }
});
</script>

</body></html>
