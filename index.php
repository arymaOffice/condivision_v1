<?php 

require_once('fl_core/autentication.php'); 
$_SESSION['POST_BACK_PAGE'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];

$autorefresh = 0;
$_SESSION['anno_fiscale'] = date('Y');
$jquery = 1;
$fancybox = 1;

include('fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
$campagne = $data_set->data_retriever('fl_campagne','descrizione',"WHERE id != 1",'id ASC');
$sedi = $data_set->data_retriever('fl_sedi','sede,citta',"WHERE id != 1 AND mostra_in_agenda = 1 AND anagrafica_id > 0",'sede ASC');
unset($sedi[0]);

include('fl_core/dataset/array_statiche.php');
include('fl_core/dataset/proprietario.php');
include("fl_inc/headers.php");
?>
<script src="./fl_set/jsc/highcharts/highcharts.js"></script>
<script src="./fl_set/jsc/highcharts/modules/exporting.js"></script>

<?php include('fl_inc/testata.php'); ?>
<?php include('fl_inc/menu.php'); ?>
<script type="text/javascript">

var user = {
    "user" : "<?php echo $_SESSION['user']; ?>",
};
localStorage.setItem("user", JSON.stringify(user));
 
</script>

<div id="corpo" class="open-corpo">



<?php 

if (isset($_GET['view'])) {  $page = ROOTPATH.'/dashboards/usertype'.base64_decode(check($_GET['view'])).'_dashboard.php'; }
else if($_SESSION['usertype'] == 0) { $page = ROOTPATH.'/dashboards/admin_dashboard.php';  } 
else  {  $page = ROOTPATH.'/dashboards/usertype'.$_SESSION['usertype'].'_dashboard.php'; } 



if(file_exists('update.php') && $_SESSION['number'] == 1 && $_SERVER['SERVER_NAME'] != '127.0.0.1' && $_SERVER['SERVER_NAME'] != 'localhost'){
include('update.php');
if(isset($sql)){
foreach($sql as $kew => $vals){
if(!is_numeric($kew)) { 
if(mysql_query($vals,CONNECT)) { $ok = 1; echo "<p>Aggiornamento Condivision Eseguito!</p>"; 
} else { $ok = 0; echo "<span style=\"color:red; text-decoration: blink;\">Errore aggiornamento! ".$kew." ".mysql_error()."</span> <br> ";  }
}
}}
unlink('update.php');
} 



@include($page); 
include("fl_inc/footer.php"); ?>
