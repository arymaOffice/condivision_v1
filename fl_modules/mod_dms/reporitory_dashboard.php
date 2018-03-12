<?php 	

$proprietario_id = $_SESSION['number'];
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
$workflow_id = 123;
?>

<script src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/dropzone.css">
<div class="dashboard_content">
 
    <?php
   	require('../../fl_core/class/ARY_dataInterface.class.php');

   	$data_set = new ARY_dataInterface();
    $main_folder = $data_set->data_retriever('fl_dms','label','WHERE id != 1 AND resource_type = 0 AND parent_id = 5','label ASC');
	unset($main_folder[0]);
	foreach($main_folder as $chiave => $valore){
		echo '<div class="box_dash_auto">';
		echo '<h3><span class="subcolor"><a href="./fl_modules/mod_dms/?c='.base64_encode($chiave).'">'.$valore.'</a></span><span class="newfolder"><a href="./fl_modules/mod_dms/mod_inserisci.php?n&PiD='.base64_encode($chiave).'"><i class="fa fa-plus-circle"></i> Crea cartella</a></h3>';
	    $sub_folders  = $data_set->data_retriever('fl_dms','label','WHERE id != 1 AND resource_type = 0 AND parent_id = '.$chiave.'','label ASC');
		unset($sub_folders[0]);
		foreach($sub_folders as $chiave2 => $valore2){
			$sub = '';
			 $sub_folders2  = $data_set->data_retriever('fl_dms','label','WHERE id != 1 AND resource_type = 0 AND parent_id = '.$chiave2.'','label ASC');
				unset($sub_folders2[0]);
				foreach($sub_folders2 as $chiave3 => $valore3){
				$sub .= '<option value="'.base64_encode($chiave3).'"> '.strtoupper($valore3).' </option>';
				}
		$sub = (count($sub_folders2) > 0) ? '<select name="PiD" class="folder_select">'.$sub.'</select>' : '<input type="hidden" name="PiD" value="'.base64_encode($chiave2).'">';
		echo '<div class="dashboard_div"> <h3>    
      <a href="./fl_modules/mod_dms/?c='.base64_encode($chiave2).'"> '.strtoupper($valore2).' </a></h3>
	  
	 </div>';
			   
		}
		echo '<br class="clear" /></div>';
	}
	?>
</div>


