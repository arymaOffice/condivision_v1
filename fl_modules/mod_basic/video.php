<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$video = check($_GET['video']);
include("../../fl_inc/headers.php");?>


<h1>Anteprima Video</h1>

<div>

<?php if(!file_exists(DMS_ROOT.'video/'.$video.'.mp4')) { echo "<h2 style=\"color: red; font-size: larger; \">Video non caricato.<h2><p>Carica il video in ".DMS_ROOT.'video/'.$video.'</p>'; } else { ?>
 <video style="width: 100%; height: 400px;" controls>
  <source src="<?php echo DMS_ROOT.'video/'.$id.'.mp4'; ?>" type="video/mp4">
Your browser does not support the video tag.
</video> 
<?php } ?>

</div>