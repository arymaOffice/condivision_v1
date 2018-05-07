<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
unset($_SESSION['POST_BACK_PAGE']);

	$tab_id = 1;
	$tabella = $tables[$tab_id];
	$select = "*";
	$force_id = 2;
	$tabs_div = 0;
	$info = GRD($tabella,2);
	$tab_div_labels = array('id'=>"Profilo",'ip_autorizzato'=>"Configurazioni");

if($info['id'] < 0){
$setup = "
CREATE TABLE IF NOT EXISTS `fl_config` (
  `id` int(11) NOT NULL,
  `ragione_sociale` varchar(50) NOT NULL,
  `sede_legale` varchar(100) NOT NULL,
  `cap` varchar(5) NOT NULL,
  `provincia` tinyint(4) NOT NULL,
  `citta` varchar(20) NOT NULL,
  `partita_iva` varchar(50) NOT NULL,
  `codice_fiscale` varchar(20) NOT NULL,
  `numero_rea` varchar(10) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `informazioni_fattura` text NOT NULL,
  `informazioni_pagamento` text NOT NULL,
  `fattura_importi_zero` tinyint(1) NOT NULL,
  `ultimo_periodo_inizio` date NOT NULL,
  `ultimo_periodo_fine` date NOT NULL,
  `ip_autorizzato` varchar(20) NOT NULL,
  `piattaforma_test` tinyint(1) NOT NULL,
  `limite_transazioni_giorno` smallint(5) NOT NULL,
  `pin_client` varchar(20) NOT NULL,
  `data_aggiornamento` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
mysql_query($setup);
}
if($info['id'] < 1){
$insertData = "
INSERT INTO `fl_config` (`id`, `ragione_sociale`, `sede_legale`, `cap`, `provincia`, `citta`, `partita_iva`, `codice_fiscale`, `numero_rea`, `telefono`, `informazioni_fattura`, `informazioni_pagamento`, `fattura_importi_zero`, `ultimo_periodo_inizio`, `ultimo_periodo_fine`, `ip_autorizzato`, `piattaforma_test`, `limite_transazioni_giorno`, `pin_client`, `data_aggiornamento`) VALUES
(2, '', '', '', 12, '', '', '', '', '', '' , '', 1, NOW(), NOW(), '', 0, 1000, '6666', NOW());";
mysql_query($insertData);
}	

include("../../fl_inc/headers.php");?>

<?php include('../../fl_inc/testata.php'); ?>
<?php include('../../fl_inc/menu.php'); ?>
<?php include('../../fl_inc/module_menu.php'); ?>


<body style=" background: #FFFFFF;">



<?php 
if($_SESSION['usertype'] == 0) { 

if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>


<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<h1>Configurazione Condivision</h1>



<?php include('action_estrai.php');   ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />
<?php if(isset($_GET['external'])) { ?><input type="hidden" name="external" value="1" /><?php } ?>
</form>

<?php } else { echo '<h1>Non puoi configurare condivision da questo computer</h1>'; } include("../../fl_inc/footer.php"); ?>
