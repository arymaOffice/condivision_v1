<?php 

require_once('../../fl_core/autentication.php');
require_once('../mod_profili_funzione/fl_settings.php');

$root = $data_set->data_retriever('fl_profili_funzione','funzione',"WHERE id != 1 AND dipendenza < 2 AND anagrafica_id = $proprietario_id",'funzione ASC');

?>
<!DOCTYPE html>
<html lang="it">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo sitename; ?></title>

<!-- Smarthphone -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"> 
<meta name="apple-mobile-web-app-capable" content="yes"> 
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> 
 
<link rel="icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" /> 
<link rel="shortcut icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/lay/a.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo ROOT.$cp_admin.$cp_set; ?>lay/a.png" />


<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>css/jquery2014.css"  media="screen, projection, tv" />
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
  
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/jquery.jOrgChart.css"/>
    <link rel="stylesheet" href="css/custom.css"/>
    <link href="css/prettify.css" type="text/css" rel="stylesheet" />

    <script type="text/javascript" src="prettify.js"></script>
    
    <!-- jQuery includes -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

    <script src="jquery.jOrgChart.js"></script>

    <script>
	
	
	
	 function show_person(id)
    {
		  $.fancybox({
            'width': '60%',
            'height': '50%',
            'autoScale': true,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'iframe',
            'href': '../mod_persone/mod_visualizza.php?id='+id
        });
    }
	
	
	
	 function openit(id)
    {
		  $.fancybox({
            'width': '60%',
            'height': '50%',
            'autoScale': true,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'iframe',
            'href': '../mod_profili_funzione/mod_visualizza.php?id='+id
        });
    }
	
	 function inserisci(id)
    {
		       
		  $.fancybox({
            'width': '60%',
            'height': '60%',
            'autoScale': true,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'iframe',
            'href': '../mod_profili_funzione/mod_inserisci.php?id=1&ANiD=<?php echo base64_encode($proprietario_id)?>&dipendenza='+id,
			afterClose : function() {
        	location.reload(); return;   
			}
        });
		
	}
    jQuery(document).ready(function() {
        $("#org").jOrgChart({
            chartElement : '#chart',
            dragAndDrop  : true
        });
    });
    </script>
    
    
<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox main JS and CSS files -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/jquery.fancybox.js?v=2.0.6"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/jquery.fancybox.css?v=2.0.6" media="screen" />

<!-- Add Button helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.2" />
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.2"></script>

<!-- Add Thumbnail helper (this is optional) -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.2" />
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.2"></script>

<!-- Add Media helper (this is optional) -->
<script type="text/javascript" src="<?php echo ROOT.$cp_admin.$cp_set; ?>jsc/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.0"></script>


<script type="text/javascript">

$(document).ready(function ()
{
   
});



</script> 

  </head>

  <body onload="prettyPrint();">
 <?php if($proprietario_id < 2 || isset($_GET['intro'])) {  ?>
<h2>Seleziona un account</h2>
<form method="get" action="" id="dms_account_sel">

<?php 			$selected = ($proprietario_id == 0) ? ' checked="checked"' : '';
?>
<input id="0" onClick="form.submit();" type="radio" name="cmy" value="0" <?php echo $selected; ?> />
		
 <?php
			
			 foreach($anagrafica as $valores => $label){ // Recursione Indici di Categoria
			$selected = ($proprietario_id == $valores) ? ' checked="checked"' : '';
			if($valores > 1){ echo '
			<input id="'.$valores.'" onClick="form.submit();" type="radio" name="cmy" value="'.base64_encode($valores).'" '.$selected.' />
			<label for="'.$valores.'"><i class="fa fa-user"></i><br>'.$label.'</label>'; }
			}
		 ?>
      
     <input type="hidden" name="a" value="amministrazione">
     <input type="hidden" name="d" value="ZG9jdW1lbnRfZGFzaGJvYXJk">
   
</form>
<?php } else { 

if(!@$thispage){ echo "Accesso Non Autorizzato"; exit;}
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
?>


   <div class="topbar">
        <div class="topbar-inner">
            <div class="container">
                <a href="#" onclick="window.print();" style="position: absolute; top: 10px; right: 80px;">Stampa</a> <a style="padding: 20px; font-size: 200%; color: white; text-decoration: none;position: absolute; top: -10px; right: 5px;"  href="../mod_profili_funzione/?a=management">x</a>
                
                <a class="brand" href="#">Organigramma <?php echo $anagrafica[$proprietario_id]; ?></a>
                <ul class="nav">
                    <?php 
					foreach($sedi_id as $chiave => $valore) { ?>
					<li><a href="./?sede=<?php echo $chiave; ?>"><?php echo $valore; ?></a></li>
                    <?php } ?>   
                </ul>
                <div class="pull-right">
                  
                    
<pre class="prettyprint lang-html" id="list-html" style="display:none"></pre>       
                </div>              
            </div>
        </div>
    </div>

     <?php 
   
    function getLi($relation,$proprietario_id) {

    $query = "SELECT * FROM fl_profili_funzione WHERE id != 1 AND dipendenza = $relation AND anagrafica_id = $proprietario_id ORDER BY id ASC";
    $risultato = mysql_query($query,CONNECT);
    while ($riga = mysql_fetch_array($risultato))  { 
    $riga['abbreviazione'] = ($riga['abbreviazione'] != '') ? $riga['abbreviazione'] : 'Profilo';
    
        echo '<li><span class="position-code">
        <a onclick="openit('.$riga['id'],');" href="#">'.$riga['abbreviazione'].'</a></span><br>
        <a onclick="show_person('.$riga['id'].');" href="#">'.$riga['funzione'].'</a>
        <a onclick="inserisci('.$riga['id'].');" href="#" class="addnew">+</a>';
        echo '<ul>';
        getLi($riga['id'],$proprietario_id);
        echo '</ul>';
        echo '</li>';
     } }


?>


<ul id="org" style="display:none">
    <?php foreach($root as $chiave => $valore) { ?>
    <li><span class="position-code"><?php echo $valore; ?></span><br>
    <ul><?php getLi($chiave,$proprietario_id); ?></ul>        
    </li>
    <?php } ?>
</ul>            
    
    <div id="chart" class="orgChart"></div>
    
    <script>
        jQuery(document).ready(function() {
            
            /* Custom jQuery for the example */
            $("#show-list").click(function(e){
                e.preventDefault();
                
                $('#list-html').toggle('fast', function(){
                    if($(this).is(':visible')){
                        $('#show-list').text('Hide underlying list.');
                        $(".topbar").fadeTo('fast',0.9);
                    }else{
                        $('#show-list').text('Show underlying list.');
                        $(".topbar").fadeTo('fast',1);                  
                    }
                });
            });
            
            $('#list-html').text($('#org').html());
            
            $("#org").bind("DOMSubtreeModified", function() {
                $('#list-html').text('');
                
                $('#list-html').text($('#org').html());
                
                prettyPrint();                
            });
			




    
	
	
	
	
	
        });
    </script>
<?php } ?>
</body>
</html>