<?php 
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
if(@!is_numeric($_GET['action'])){ exit; };
?>
<div style=" width: 90%; margin: 2px 10px 50px 25px;">

 
<?php include('../mod_basic/action_visualizza.php');  ?>
    
    
  <?php
	 $template_files = '<h2 class="titolo_barra">Files Allegati</h2>   
 <table class="dati" summary="Files Correlati" >
       <tr>
        <th scope="col">Data</th>
        <th scope="col">File</th>
        <th scope="col">Peso</th>
        <th scope="col">Tipo</th>
        <th scope="col">Download</th>
 </tr>
 [*RISULTATI*]
 </table>';


    $directorys = false;
	$risultati = "";
	$files = "../../../set/files/file_articolo_".$id."/";
	$rep = @opendir($files);
	$files_array = array();
	
	while (@$file = readdir($rep)){
		
	if($file != '..' && $file !='.' && $file !='' && $file !='_notes' && $file !='Thumbs.db'  && $file !='desktop.ini' && $file !='index.php' && $file !='Pubblica' && $file !='Staff' && $file !='Partners' && $file !='Clienti'){
			
			if (is_file($files.$file)){
			
			array_push($files_array ,$file);
			
		   $directorys = true;		  
		
			}
				
		}
	}
	
	natsort($files_array);
	
	foreach($files_array as $chiave => $file) { 

	if(substr($file,-3,3) == "jpg" || substr($file,-3,3) == "png" || substr($file,-3,3) == "png" || substr($file,-4,4) == "jpeg"){ 
	$icona_ext = '<img src="'.$files.$file.'" alt="Anterprima" class="icona_catalogo" />';
	 } else { 
	$icona_ext = ' <img src="'.ROOT.$cp_admin.$cp_set.'icons/'.ext(substr($file,-3,3)).'.png" alt="Icona" >';
	} 
	//$download_item = "scarica.php?dir=file_articolo_'.$id.'&amp;file='.$file";
       $download_item = $files.$file;
       
       
	 $risultati .= '<tr>
        <td scope="col">'.date("j/n/Y - G:i", filemtime($files.$file)).'</td>
        <td scope="col" class="titolo"><a href="'.$download_item.'">'.$file.'</a></td>
        <td scope="col">'.number_format(filesize($files.$file)/1000,1,".",",").' Kb.</td>
        <td scope="col">'.$icona_ext.'</td>
		<td scope="col"><a href="'.$download_item.'"><img src="'.ROOT.$cp_admin.$cp_set.'icons/download.gif" alt="Scarica File"  /></a></td>
     </tr>';
    
	}
	
	
	@closedir($rep);
	clearstatcache();
		
if($directorys == true) echo str_replace("[*RISULTATI*]",$risultati,$template_files);		  
		

if(!isset($_SESSION['dir_for_gallery'])){ $_SESSION['dir_for_gallery'] = 'gallery/'; }

if(isset($_GET['id'])) { $gallery .= "cms_".$id."/";  } else { $gallery = ""; }



$rep2=@opendir($gallery);

if(!@opendir($gallery)) { } else {
 
$directorys = false;
while (@$file = readdir($rep2)){
	if($file != '..' && $file !='.' && $file !='' && $file !='_notes' && $file !='Thumbs.db'  && $file !='desktop.ini' && $file !='index.php' && $file !='Pubblica' && $file !='Staff' && $file !='Partners' && $file !='Clienti'){ 
		
		if (is_file("$gallery$file")){
		
		echo '<h2 class="titolo_barra">Immagini Correlate</h2>';

  		echo '<a href="'.$gallery.$file.'" class="highslide" onclick="return hs.expand(this)" title="Immagine di Galleria"><img src="'.$gallery.'tumb/'.$file.'" class="thumbnail" alt="Immagine di Galleria" ></a>';
    
         
		}
					
	}
}


@closedir($rep);
clearstatcache();
}  


?>

</div>
