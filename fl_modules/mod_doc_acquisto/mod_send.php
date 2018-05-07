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
		<?php
		if(isset($_GET['esito'])) { echo '<div id="results"><h2 class="green">'.check($_GET['esito']).'</h2></div>';

	} else {

		$id = check($_GET['send_id']);
		$documento = GRD('fl_doc_acquisto',$id);
		$tipoDoc = $tipo_doc_acquisto[$documento['tipo_doc_acquisto']];
		$persona = GRD('fl_anagrafica',$documento['anagrafica_id']);

		?>
<div class="loader"></div>

		<form id="scheda2" action="mod_opera.php" method="get" enctype="multipart/form-data" style="width: 90%; margin: 0 auto; ">
			<h1>Invia <?php echo $tipoDoc  ?></h1>


			<p><label>Email:</label> <input type="text" name="email" value="<?php echo (isset($persona['email'])) ? $persona['email'] : '';  ?>" required style=" width: 90%;" /></p>
			<p><label>Oggetto:</label><input type="text" name="oggetto" value="Invio <?php echo $tipoDoc  ?>" style=" width: 90%;" /></p>
			<label>Messaggio:</label><textarea name="messaggio">
			Gentile <?php echo $documento['ragione_sociale']  ?>,<br />

	in allegato trasmettiamo ns. ordine in formato PDF.<br /><br />

	Come da precedenti accordi, indichiamo il ns. ultimo prezzo di riferimento.<br /><br />

	<br /><br />

	Cordiali saluti.<br /><br />


	La Direzione

		</textarea>


			<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<input type="hidden" name="send" value="1" />
			<p><input type="submit" value="Invia <?php echo $tipoDoc  ?>" class="button" style=" width: 90%;" /></p>
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
