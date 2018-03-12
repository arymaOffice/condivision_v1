<?php 

	ini_set('display_errors',1);
    error_reporting(E_ALL);

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 



$id = check($_GET['id']);

$profilo_funzione = GRD('fl_profili_funzione',$id); 
$processo = GRD('fl_processi',$profilo_funzione['processo_id']); 
$persone = GQS('fl_persone','*',' profilo_funzione = '.$id);

include("../../fl_inc/headers.php"); ?>


<body style=" background: #FFFFFF;">




<?php 
if(empty($persone)) echo '<h1>Nessuna persona associata a questo profilo</h1>';
foreach($persone as $chiave => $persona) {



echo '<div class="info_dati">
<p class="user_photo"><span class="user_corda"><i class="fa fa-user" aria-hidden="true" style="font-size: 1000%;"></i></span></p>
<h2><strong>'.$persona['nome'].' '.$persona['cognome'].'</strong></h2>';
echo '<h3><span class="msg blue">'.$processo['processo'].'</span>'.@$profilo_funzione['funzione'].'</h3>';
echo '<p>Tel: <a href="tel:'.$persona['cellulare'].'"  >'.$persona['cellulare'].'</a> mail: <a href="mailto:'.@$persona['emails'].'">'.@$persona['emails'].'</a></p>
<p><i class="fa fa-trophy"></i> Obiettivi Raggiunti: 3/5</p></div>
';
?>



<iframe style="width: 100%; border: none; height:1200px;" src="mod_report_competenze.php?PerID=<?php echo $persona['id']; ?>"></iframe>

<?php } ?>

</body></html>
