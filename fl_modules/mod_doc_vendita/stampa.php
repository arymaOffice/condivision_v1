<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");


//Declare which files you want to merge (must be in the order you want them to be displayed
require_once('pclzip.lib.php');
$archive = new PclZip('export_'.date('d-m-Y').'.zip');

foreach($_POST['prints'] as $chiave => $valore) { 
	$file = check($valore);
	$v_list = $archive->add($file,PCLZIP_OPT_REMOVE_PATH, 'doc');
	}








?>



<body>
<script type="text/javascript">
function print_frame(frame) {
window.frames[frame].focus();
window.frames[frame].print();
}

</script>
<div id="up_menu">
<a href="<?php echo (isset($_SESSION['POST_BACK_PAGE'])) ? $_SESSION['POST_BACK_PAGE'] : 'javascript:history.back();'; ?>"> <i class="fa fa-angle-left"></i> Indietro </a>
<span class="welcome"><a href="#" style="font-size: 16px" onClick="print_frame('iprint');">
<i class="fa fa-print"></i> Stampa </a></span>
</div>

<iframe id="iprint" name="iprint" src="<?php echo $outputName; ?>" style="width: 100%; height: 800px;"></iframe>

</body>
</html>

