<?php 

require_once('../../fl_core/autentication.php');


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
            'height': '60%',
            'autoScale': true,
            'transitionIn': 'fade',
            'transitionOut': 'fade',
            'type': 'iframe',
            'href': '../mod_persone/mod_inserisci.php?id='+id
        });
    }
	
	
	
	 function openit(id)
    {
		  $.fancybox({
            'width': '60%',
            'height': '60%',
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
            'href': '../mod_profili_funzione/mod_inserisci.php?id=1&subid='+id
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
    <div class="topbar">
        <div class="topbar-inner">
            <div class="container">
                <a href="#" onclick="window.print();" style="position: absolute; top: 10px; right: 80px;">Stampa</a> <a style="padding: 20px; font-size: 200%; color: white; text-decoration: none;position: absolute; top: -10px; right: 5px;"  href="../mod_profili_funzione/?a=management">x</a>
                
                <a class="brand" href="#">Organigramma Azienda Demo SpA</a>
                <ul class="nav">
                    <li><a href="#">Dirigenziale</a></li>
                    <li><a href="#">Sede di Bari</a></li>                  
                    <li><a href="#">Sede di Ascoli</a></li>      
                </ul>
                <div class="pull-right">
                  
                    
<pre class="prettyprint lang-html" id="list-html" style="display:none"></pre>       
                </div>              
            </div>
        </div>
    </div>
    
    <ul id="org" style="display:none">
    <li> Direzione <img src="images/massimo.jpg" alt="Demo"/>
       <ul>
         <li>
         <span class="position-code"><a  onclick="openit(3);" href="#">C.MICRO-A</a></span><br>
         Mario Rossi <a  onclick="inserisci(3);" href="#" class="addnew">+</a>
         </li>
         
         <li><span class="position-code">IT-CLOUD</span><br> 
         <img src="images/michele.jpg" alt="Demo"/>
           <ul>
             <li><span class="position-code">IT-CONSULTANT</span><br><a  onclick="show_person(2);" href="#">Michele Fazio</a></li>
             <li><span class="position-code">IT-FORMATORE</span><br>Thomas Altini</li>
           </ul>
         </li>
         
         <li class=""><span class="position-code">AMMINISTRAZIONE</span><br>Mario Rossi
           <ul>
             <li><span class="position-code">Ufficio Acquisti</span><br>Michele Neri
              
             </li>
             <li>Cassieri
               <ul>
                 <li>Operatore 1</li>
                 <li>Operatore 2</li>
            
               </ul>
             </li>
              <li>Cassieri
               <ul>
                 <li>Operatore 1</li>
                 <li>Operatore 2</li>
            
               </ul>
             </li>
              <li>Cassieri
               <ul>
                 <li>Operatore 1</li>
                 <li>Operatore 2</li>
            
               </ul>
             </li>
           </ul>
         </li>
         <li>Produzione</li>
         <li class="collapsed">Gestione Co.Co.Co.
           <ul>
             <li>Oggi ci sono</li>
             <li>Domani non ci sono</li>
           </ul>
         </li>
       </ul>
     </li>
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

</body>
</html>