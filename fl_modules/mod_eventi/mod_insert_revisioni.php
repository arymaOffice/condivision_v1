<?php
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$evento_id = check($_GET['evento_id']);
$evento = GRD($tabella,$evento_id); 
include("../../fl_inc/headers.php");
?>

<style type="text/css">
	#note_ifr{
		height: 100px !important
	}
	#note_tbl{
		height: 100px !important
	}
	.left { float: left; text-align: right; }
</style>
<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
	<div id="container" >
		<div id="content_scheda">
			<?php if(isset($_GET['esito']))  echo  '<div class="msg red">'.$_GET['esito'].'</div>'; ?>
			<form action="mod_opera.php" method="POST">
				<h1>Crea Revisione</h1>
				<div class="box left">
				Adulti (A): <input type="number" name="numero_adulti" placeholder="Adulti" value="<?php echo $evento['numero_adulti'];?>"><br><br>
				Bambini (B): <input type="number" name="numero_bambini" placeholder="Bambini" value="<?php echo $evento['numero_bambini'];?>"><br><br>
				Sedie (S): <input type="number" name="numero_sedie" placeholder="Sedie" value="<?php echo $evento['numero_sedie'];?>"><br><br>
				</div>
				<div class="box left">
				Sedioloni (H): <input type="number" name="numero_sedioloni" placeholder="Sedioloni" value="<?php echo $evento['numero_sedioloni'];?>"><br><br>
				Ospiti Serali (L): <input type="number" name="numero_serali" placeholder="Ospiti Serali" value="<?php echo $evento['numero_serali'];?>"><br><br>
				Operatori (O): <input type="number" name="numero_operatori" placeholder="Operatori" value="<?php echo $evento['numero_operatori'];?>"><br><br>
			    </div>
				<textarea name="note" ></textarea><br><br>
				<input type="hidden" name="evento_id" value="<?php echo $evento_id ?>">
				<input type="submit" value="Salva revisione" style="width: 100%;" class="button">
				<p>Le modifiche agli ospiti aggiorna automaticamente l'evento</p>
			</form>
		</div>
	</div>
</body>
</html>