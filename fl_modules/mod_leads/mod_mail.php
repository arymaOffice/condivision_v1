<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check(@$_GET['id']);
$potential = @get_potential(@$id); 

include("../../fl_inc/headers.php"); ?>


<body style=" background: #FFFFFF;">
<div id="container" style=" text-align: left;">


<h1>Potential <?php echo (isset($_GET['nominativo'])) ? check($_GET['nominativo']) : 'Tab'; ?></h1>
<?php if(isset($_GET['close'])) { ?><script type="text/javascript"> parent.$.fancybox.close();</script><?php exit; }  ?>
<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p id="esito" class="'.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<?php if(isset($potential)) { $class = ($potential['status_potential'] != 3) ? 'green' : 'red'; if($potential['status_potential'] == 1) { $class = 'orange'; } echo '<p style="padding: 13px; width: 56%;" class="'.$class.'">'.$status_potential[$potential['status_potential']].'</p>'; }  ?>

<div class="info_dati">
<?php if($potential['in_use'] > 0 && $potential['in_use'] != $_SESSION['number']) { echo "<h1 class=\"red\"><strong>Sorry but ".$proprietario[$potential['in_use']]." in managing this contact right now!</strong></h1>"; } else { ?>

<?php 
if($id != 1){

$query = "UPDATE `fl_potentials` SET `in_use` = '".$_SESSION['number']."' WHERE id = '$id';";
mysql_query($query,CONNECT);



echo '<h1><strong>'.$potential['nome'].' '.$potential['cognome'].'</strong></h1>';
echo '<h2>Applied for: '.@$mansione[@$potential['mansione']].'</h2>';
echo '<p>Phone: <a href="tel:'.@$potential['telefono'].'">'.@$potential['telefono'].'</a> mail: <a href="mailto:'.@$potential['email'].'" >'.@$potential['email'].'</a></h2>';


?>
</div>

<?php } ?>
<form id="scheda" action="../mod_opera.php" method="post" enctype="multipart/form-data">

<input type="text" name="oggetto" value="Request of contact by mail" />

<textarea name="messaggio" />

</form>
<?php }  ?>

</div></body></html>
