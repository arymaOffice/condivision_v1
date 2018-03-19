<?php
include_once("header.php");
$user_id=$userData['id'];


$videoTemplate  = '<video id="video"  controls="controls" style="width: 100%; height: auto;" >
  <source src="{{source}}" type="video/mp4" />
  <source src="{{source}}" type="video/mov" />
</video>';


?>
<div class="container">

<section class="section">
 <main class="grid">
   <?php
   if ($result = mysqli_query($conn, "SELECT  id,CONCAT(cartella,'',nome) as percorso_img,commento FROM uv_foto_utenti where matrimonio_id=".$userData['matrimonio_id']." and bloccato=0 order by id desc")) {
    if (mysqli_num_rows($result) > 0) {
    					// output data of each row
     while($row = mysqli_fetch_assoc($result)) {
      
       $ext = strtolower(substr($row['percorso_img'],-3,3));
       $is_video = ( $ext == 'mov' ||  $ext == 'mp4') ? 1 : 0;

       echo "<div class=\"crop\"><div class=\"commento\"><a href='image.php?id=".$row['id']."'>".$row['commento']."</a></div>";
       echo ($is_video == 1) ?  str_replace('{{source}}', $row['percorso_img'], $videoTemplate): "<a href='image.php?id=".$row['id']."'><img src=\"".$row['percorso_img']."\"  /></a>";
       echo "</div>" ;
     }
   } 
 }
 ?>
</main>	
</section>
</div>

 <button type="button" class="btn btn-dark btn-circle btn-lg myBTNhover" style="background-color:#b77c9c;" onclick="window.location='carica.php'" ><i class="fa fa-camera icon "></i></button>
 <button type="button" class="btn btn-dark btn-circle btn-lg myBTNhover" style="background-color:#b77c9c; right: 50%" onclick="window.location='caricaVideo.php'" ><i class="fa fa-video-camera icon "></i></button>

<?php
include_once("footer.php");

?>