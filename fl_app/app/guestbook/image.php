<?php
include_once("header.php");
$foto_id=0;
if (isset($_POST['id'])){
	$foto_id=intval($_POST['id']);
}else{
	$foto_id=intval($_GET["id"]);
}


if ($foto_id==0) {
    header("Location: index.php");
}




$user_id=$userData['id'];
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$commento=htmlentities($_POST['commento']);
	//$data = date('m/d/Y h:i:s', time());
	$data = date('Y-m-d h:i:s', time());
	$stmt = $mysqli->prepare("INSERT INTO cc_commenti(user_id, 
	commento,data,immagine_id,bloccato) VALUES (?, ?, ?, ?,?)");
	$stmt->bind_param('dssdd',$user_id, 
	$_POST['commento'],
	$data,
	$foto_id,
	$blocco_commenti);
	$stmt->execute(); 
	$stmt->close();
	unset($_POST);
	unset($_REQUEST);
	header('Location: image.php?id='.$foto_id);
}


$videoTemplate  = '<video id="video"  controls="controls" style="width: 100%; height: auto;" >
  <source src="{{source}}" type="video/mp4" />
  <source src="{{source}}" type="video/mov" />
</video>';



?>
<script>
$('#commenti')[0].reset();
</script>

<div class="container">
<!-- Page Heading/Breadcrumbs -->


<div class="back">
<a href="gallery.php"><span class="glyphicon glyphicon glyphicon-arrow-left"></span></a>
</div>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">


	
	<!-- Contenedor Principal -->
   
		<?php
		if ($result = mysqli_query($conn, "SELECT id,CONCAT(cartella,'',nome) as percorso_img,commento FROM cc_immagini where  id=". $foto_id ." and bloccato=0 limit 1")) {
    	if (mysqli_num_rows($result) > 0) {
    		// output data of each row
    		while($row = mysqli_fetch_assoc($result)) {
		
		     $ext = strtolower(substr($row['percorso_img'],-3,3));
             $is_video = ( $ext == 'mov' ||  $ext == 'mp4') ? 1 : 0;

       			$contenuto = ($is_video == 1) ?  str_replace('{{source}}', $row['percorso_img'], $videoTemplate) : "<img src=\"".$row['percorso_img']."\"  style=\"max-height:400px;max-width: 100%;\" />" ;
				echo "<div class='comments-container infoto'>". $contenuto;
				echo "<h2>".$row['commento']."</h2></div>";
    		}
    		
    		?>
    				
    	 <div class="comments-container">			
    	 <ul id="comments-list" class="comments-list" style="width: 100%;">
		
		<?php
		if ($result = mysqli_query($conn, "SELECT `id`, `user_id`, `commento`, `data`, `immagine_id`, `bloccato`, `matrimonio_id`, concat(nome,' ',cognome) as nomecognome,picture as foto FROM `uv_commenti_utenti` where  immagine_id=". $foto_id ." and matrimonio_id=".$userData['matrimonio_id']." and bloccato=0 order by id DESC")) {
    		if (mysqli_num_rows($result) > 0) {
    			// output data of each row
    			while($row = mysqli_fetch_assoc($result)) {
    			?>
    			<li>
				<div class="comment-main-level">
					<!-- Avatar -->
					<div class="comment-avatar"><img src="<?php echo $row['foto']; ?>" alt="<?php echo $row['nomecognome']; ?>" ></div>
					<!-- Contenedor del Comentario -->
					<div class="comment-box">
						<div class="comment-head">
							<h6 class="comment-name"><?php echo $row['nomecognome']; ?></h6>
							<span><?php 
							$data = date('d/m/Y h:i:s', strtotime($row['data']));
							
							echo $data;?></span>
							
						</div>
						<div class="comment-content">
    			<?php
					echo html_entity_decode($row['commento']);
					?>
					</div>
					</div>
				</div>
					<?php
    			}
			} 
		}	
		?>
		</ul>
		<div class="widget-area no-padding blank" >
								<div class="status-upload">
									<form action="image.php" method="post" name="commenti" id="commenti">
										<textarea style="height: 100px;" placeholder="Lascia un commento agli sposi" id="commento" name="commento"></textarea>
										<button type="submit" class="btn btn2 btn-success pink">Commenta <i class="fa fa-paper-plane"></i></button>
									<input type="hidden" name="id" value="<?php echo $foto_id; ?>"/>
									</form>
								</div><!-- Status Upload  -->
							</div><!-- Widget Area -->
    		
    		
    		<?php
    		
    		
    		
		} else
		{
		?>
	
			<h1><b>Immagine non trovata</b></h1>
		<?php
		}
	}	
	?>

			
							</div>
					
				</div>
	</div>
	</div>

        
    </div>

<br>
<br>

<?php
include_once("footer.php");

?>