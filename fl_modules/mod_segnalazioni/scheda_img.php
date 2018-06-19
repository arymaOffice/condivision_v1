<?php
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Libreria di Immagini</title>


<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_layout.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_colors.css" media="screen, projection, tv" />
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/fl_print.css" media="print" />

</head>

<body>
<p>&nbsp;</p>
<p>
  <?php 
echo "<h2>Libreria Immagini</h2>
<p>Per inserire un'immagine &egrave; sufficiente trascinarla nel testo.</p>" ?>
  
  
</p>
<form id="form_Immagine " class="admin_form" method="post" action="../mod_grafica/mod_opera.php" enctype="multipart/form-data">

<div id="load_box">

<p><label for="upfile">Allega Immagine (max 2.0 Mb, .jpg, .gif, .png):</label></p>
<p><input name="upfile" type="file" class="inputForm" id="upfile" size="50"  />
  <br />
  
    <input type="checkbox" name="sovrascrivi" value="1" /><label for="sovrascrivi">Sovrascrivi</label>
</p> 
<input name="user" id="user" value="img_articoli" type="hidden" />


<p id="loading">
          <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="500" height="25" accesskey="l" tabindex="5" title="Loading">
            <param name="movie" value="../../fl_set/img/loading.swf" />
            <param name="quality" value="high" />
            <param name="wmode" value="transparent" />
            <embed src="../../fl_set/img/loading.swf" width="500" height="25" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" wmode="transparent"></embed>
          </object>
          <br />

</p>

<p>
  <input type="hidden" name="img_articoli"  value="1" /> <input type="hidden" name="id" id="id" value="_cms" /></p>
<p>&nbsp;</p>
</div>
<p><input type="submit" id="invio" value="Invia" class="button" onclick="javascript:changevisibility();" /><input type="reset" id="resetta2" value="Ripristina" class="button" /></p>

</form>

<p>&nbsp;</p>
<div id="image_sele">

<?php

$noDir = "<h3>Nessun File Inserito.</h3><br />"; 
$gallery = $dir_articoli_img;
@$rep=opendir($gallery);

//echo $gallery;
if(!@opendir($gallery)) { echo $noDir; } else {
$directorys = false;
while (@$file = readdir($rep)){
	if($file != '..' && $file !='.' && $file !='' && $file !='_notes' && $file !='Thumbs.db'  && $file !='desktop.ini' && $file !='index.php' && $file !='Pubblica' && $file !='Staff' && $file !='Partners' && $file !='Clienti'){ 
		
		if (is_file("$gallery$file")){
			$directorys = true;
		
	echo "<p class=\"icona_libreria\"><img src=\"$gallery$file\" alt=\"Immagine Articolo\" /><br />$file</p>"; 
	
		}
				
	}
}

if ($directorys == false) {
print("$noDir");
}
@closedir($rep);
clearstatcache();
}?><p style="clear: both;"></p></div>
</body>
</html>
