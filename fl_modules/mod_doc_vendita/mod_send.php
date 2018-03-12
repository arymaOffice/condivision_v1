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
		$documento = GRD('fl_doc_vendita',$id);
		$tipoDoc = 'Fattura';
		$persona = GRD('fl_anagrafica',$documento['anagrafica_id']);

		?>
<div class="loader"></div>

		<form id="scheda2" action="mod_opera.php" method="get" enctype="multipart/form-data" style="width: 90%; margin: 0 auto; ">
			<h1>Invia <?php echo $tipoDoc  ?></h1>


			<p><label>Email:</label> <input type="text" name="email" value="<?php echo $persona['email'];  ?>" required style=" width: 90%;" /></p>
			<p><label>Oggetto:</label><input type="text" name="oggetto" value="Invio <?php echo $tipoDoc  ?>" style=" width: 90%;" /></p>
			<label>Messaggio:</label><textarea name="messaggio">
			Gentile <?php echo $documento['ragione_sociale']  ?>,<br />

	in allegato trasmettiamo nostra fattura in formato PDF.<br /><br />

	Come da precedenti accordi, e nel rispetto delle disposizioni di Legge, non verrà spedita alcuna copia cartacea.<br /><br />


	La fattura allegata va stampata e conservata per tutti i necessari adempimenti di Legge, come disposto dal DPR 633/72 (succ.modifiche) e
	dalla risoluzione del Ministero delle Finanze PROT.450217 del 30 Luglio 1990.<br /><br />

	Porgendo distinti saluti e ringraziando per la fiducia accordata, restiamo a completa disposizione per ogni ulteriore informazione.<br /><br />


	Saluti

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
