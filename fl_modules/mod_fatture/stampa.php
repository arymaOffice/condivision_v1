<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");

$folder = '../../../set/files/fatture/';

//Declare which files you want to merge (must be in the order you want them to be displayed
$fileArray= array();
foreach($_POST['prints'] as $chiave => $valore) { array_push($fileArray,$valore);   }
//Define the output filename
$outputName = 'stampa_'.date('d-m-Y').'.pdf';

//Pre-write the command
$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";

//Add each pdf file to the end of the command
foreach($fileArray as $file) {
    $cmd .= $folder.$file." ";
}

//Execute the command
$result = shell_exec($cmd);

sleep(3);    


?>



<body>
<script type="text/javascript">
function print_frame(frame) {
window.frames[frame].focus();
window.frames[frame].print();
}

</script>
<div id="up_menu">
<a href="<?php echo (isset($_SESSION['POST_BACK_PAGE'])) ? $_SESSION['POST_BACK_PAGE'] : 'javascript:history.back();'; ?>"> <i class="fa fa-angle-left"></i> INDIETRO </a>
<span class="welcome"><a href="#" class="button" style="font-size: 16px" onClick="print_frame('iprint');">
<i class="fa fa-print"></i> Stampa </a></span>
</div>

<iframe id="iprint" name="iprint" src="<?php echo $outputName; ?>" style="width: 100%; height: 800px;"></iframe>

</body>
</html>

