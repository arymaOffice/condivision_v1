<?php
require_once('../../fl_core/autentication.php');
include("../../fl_inc/headers.php");
$tipo = ($_GET['tipo_doc_vendita'] == 1)? 'Ricevuta' : 'Fattura';
$evento = GRD($tables[6],check($_GET['refId']));
?>
<body>
	<br>
	<h1>Crea <?php echo $tipo; ?></h1>
	<a href="#" style="width:400px;"  class="button display"><?php echo $tipo ?> acconto</a>
	<br>
	<form style="display: none" id="formImporto" method="GET" action="../mod_doc_vendita/mod_opera.php" target="_top">
		<br>
		<input type="text" name="importo" placeholder="importo acconto" style="width:400px;" > <br><br>
		<input style="width:400px;height:130px;padding-bottom: 100px;" type="text" name="descrizione" placeholder="inserisci descrizione"
		 value="Acconto per evento <?php echo $evento['titolo_ricorrenza']  ?>  data <?php echo mydate($evento['data_evento']); ?> ">
		<input type="hidden" name="tipo_doc_vendita" value="<?php echo check($_GET['tipo_doc_vendita'])?>">
		<input type="hidden" name="crea" value="<?php echo check($_GET['crea'])?>">
		<input type="hidden" name="refId" value="<?php  echo check($_GET['refId'])?>">
		<input type="hidden" name="anagraficaId" value="<?php echo check($_GET['anagraficaId'])?>">
		<br>
		<input style="width:400px;color:white;"  class="button sub" type="submit" value="Crea acconto">
	</form>
	<br>
	<a style="width:400px;" target="_top" href="../mod_doc_vendita/mod_opera.php?tipo_doc_vendita=<?php echo check($_GET['tipo_doc_vendita'])?>&crea=<?php echo check($_GET['crea'])?>&refId=<?php echo check($_GET['refId'])?>&anagraficaId=<?php echo check($_GET['anagraficaId'])?>" class="button fattura" ><?php echo $tipo?> saldo </a>
	<br><a style="width:400px;" target="_top" href="../mod_doc_vendita/mod_opera.php?tipo_doc_vendita=<?php echo check($_GET['tipo_doc_vendita'])?>&crea=<?php echo check($_GET['crea'])?>&refId=<?php echo check($_GET['refId'])?>&anagraficaId=<?php echo check($_GET['anagraficaId'])?>&accorpa_voci" class="button fattura" ><?php echo $tipo?> saldo (Accorpato)</a>
	<script type="text/javascript">

		$('.display').click(function(e){

			e.preventDefault();
			if($('#formImporto').css('display') == 'none'){
				$('#formImporto').css('display','block');
				$('.fattura').css('display','none');
			}else{
				$('#formImporto').css('display','none');
				$('.fattura').css('display','inline-block');
			}
		});



	</script>
</body>
</html>
