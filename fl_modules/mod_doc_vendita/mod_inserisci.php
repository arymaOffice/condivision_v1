<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

$id = check($_GET['id']);
if($id > 1) { $documento = GRD($tabella,$id); 	$_SESSION['POST_BACK_PAGE'] = "../mod_doc_vendita/?tipo_doc_vendita=".$documento['tipo_doc_vendita']; }
$new_cliente = '<a href="../mod_anagrafica/mod_inserisci_smart.php?id=1&a=crm" onclick="openedIframe()"  class="fancybox fancybox.iframe">Crea Anagrafica <i class="fa fa-plus-circle"></i></a>';
include("../../fl_inc/headers.php"); include("../../fl_inc/testata_mobile.php");
?>



<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
	<div id="container" >
		<div id="content_scheda">

			<?php if($id != 1){ ?>


			<div class="info_dati">
				<?php echo '<h1 style="display: inline-block; margin: 0 5px 0 0;"><span class="msg blue">'.$tipo_doc_vendita[$documento['tipo_doc_vendita']].'</span> no. <strong>'.$documento['numero_documento'].' del '.mydate($documento['data_documento']).'</strong></h1>'.$new_cliente;
				echo '<p>Oggetto: '.check($documento['oggetto_documento']).'</p>'; ?>
			</div>

			<?php } else { echo '<h1 style="display: inline-block;margin-right: 10px;">Nuova <span class="msg blue">'.$tipo_doc_vendita[check($_GET['tipo_doc_vendita'])].'</span></h1>'.$new_cliente; } ?>



			<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
				<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p id="esito" class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


					<?php  include('../mod_basic/action_estrai.php');  ?>
					<?php if(check($_GET['id']) == 1) { ?><input type="hidden" name="reload" value="../mod_doc_vendita/mod_inserisci.php?id=" /><?php } ?>
				</form>
			</div>
		</div>

		<script type="text/javascript">

			function openedIframe(){
				setTimeout(function() {   
					$('.fancybox-iframe').contents().find('a.salva').click(function(e){
						$.fancybox.close();
					})
				},2000);
			}
		</script>

	</body></html>
