<?php
include_once("header.php");
$user_id=$userData['id'];
$matrimonio_id=$userData['matrimonio_id'];
if($matrimonio_id>0){
    header('Location: gallery.php');
}

$conferma=0;
if (isset($_POST['conferma'])){
$conferma=$_POST['conferma'];
}

$errore="";
if($_SERVER['REQUEST_METHOD'] == 'POST')  {
	if ($stmt = $mysqli->prepare("SELECT id FROM fl_app_wed WHERE codice_matrimonio=?")) {
    	$stmt->bind_param("s", $_POST['codmat']);
    	$stmt->execute();
    	$stmt->bind_result($matrimonio_id);
    	$stmt->fetch();
    	$_SESSION['matrimonio_id'] = $user_id;
    	$stmt->close();
    	
    	$stmt = $mysqli->prepare("UPDATE `cc_users` SET `matrimonio_id`=? WHERE `id`=?");
		$stmt->bind_param('dd',$matrimonio_id, 
		$user_id);
		$stmt->execute(); 
		$stmt->close();
    	
    	//UPDATE `cc_users` SET `matrimonio_id`=1 WHERE `id`=
    }
    if ($matrimonio_id<1) {
    $errore="CODICE ERRATO";
    }
}

?>



<div class="container">

<?php
if ($matrimonio_id<1) {
?>

	<h2>Associati ad un Matrimonio</h2>
	<div class="row" >
		<div class="col-md-6">
			<form action="index.php" method="post" name="foto" id="foto" enctype='multipart/form-data'>
					<div class="form-group">
  						<label for="comment">CODICE MATRIMONIO</label>
  						<input type="hidden" name="conferma" value='<?php echo $conferma;?>'/>
  						<input type="text" name="codmat" value=""/>
  						<div id="error" style="color:red;"><?php echo $errore;?></div>	
					</div>
					<button class="btn btn-info" type="submit" >Convalida </button>
			</form>
			<div style="clear:both"></div>
		</div>
	</div>
</div>	
	<?php
	}
	else {
	header("Location: gallery.php");
	}
include_once("footer.php");

?>