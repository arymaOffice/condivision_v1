<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
$id = check($_GET['id']);
$evento_id = check($_GET['evento_id']);

$evento = GRD($tabella,$evento_id); 

$tab_id = 124;
$tabella = $tables[$tab_id];

$selectDataRevisione = "SELECT DATE_FORMAT(data_creazione,'%d/%m/%Y %H:%i') as data FROM fl_revisioni_hrc WHERE evento_id = $evento_id ORDER BY id DESC  LIMIT 1";
$selectDataRevisione = mysql_query($selectDataRevisione,CONNECT);
$selectDataRevisione = mysql_fetch_assoc($selectDataRevisione);

if(isset($_GET['auto'])) {

    /*Creo scheda servizio (da aggiungere poi intelligenza determinazione scheda in base a tipo evento */
    mysql_query('INSERT INTO fl_ricorrenze_matrimonio (id,evento_id,`data_creazione`, `data_aggiornamento`, `operatore`) VALUES (NULL ,'.$evento_id.',NOW(),NOW(),'.$_SESSION['number'].')',CONNECT);
    
    $new = mysql_insert_id(); // id della scheda servizio a cui rimanderemo utente
    
    /*Creo anche un menu base per l'evento*/
    mysql_query('INSERT INTO `fl_menu_portate` (id,evento_id,`descrizione_menu`,`data_creazione`, `data_aggiornamento`, `operatore`) VALUES (NULL ,'.$evento_id.',\'Menù Evento\',NOW(),NOW(),'.$_SESSION['number'].')',CONNECT);
    

    header('Location: ./mod_scheda_servizio.php?evento_id='.$evento_id.'&id='.$new);
    mysql_close(CONNECT);
    exit;
} 

$_SESSION['last_managed'] = array('id'=>$id,'name'=>$evento['titolo_ricorrenza'],'link'=>'../mod_eventi/mod_scheda_servizio.php?evento_id='.$evento_id.'&id='.$id);



$tab_div_labels = array(
                        '../mod_menu_portate/mod_user.php?evento_id='.$evento_id.'&id=[*ID*]'=>"Menù",
                        'id'=>'Dettagli Evento',
                        '../mod_linee_prodotti/mod_user.php?categoria_prodotto=30&evento_id='.$evento_id.'&id=[*ID*]'=>"Allestimenti della sala",
                        '../mod_linee_prodotti/mod_user.php?categoria_prodotto=33&evento_id='.$evento_id.'&id=[*ID*]'=>"Servizi",
                        'mod_revisioni.php?evento_id='.$evento_id.'&id=[*ID*]'=>"Revisioni");

if($id == 1) { 
$tab_div_labels = array('id'=>'Dettagli Evento');
}
include("../../fl_inc/headers.php");
if(!isset($_GET['goto'])) include("../../fl_inc/testata_mobile.php");

 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >
<div id="content_scheda">


<?php
 setlocale(LC_TIME, 'it_IT');

 $dataRevisione = (@$selectDataRevisione['data'] != '') ? @$selectDataRevisione['data'] : 'Nessuna Revisione' ;
 $tavoli = GQD('fl_tavoli','id,evento_id',' evento_id = '.$evento_id );
 $ambiente = $evento['ambiente_principale'];
 $templateCreate = ($evento['multievento'] == 1) ? '' : '&template_id=1 ';
 
 $colorScheda = @$colors[$evento['tipo_evento']];
 echo "<div class=\"module_icon\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view\" href=\"../mod_stampe_servizio/mod_servizio.php?evento_id=$evento_id\" title=\"Stampa Ordine\" style=\"color: $colorMenu\"><i class=\"fa fa-file-text\" aria-hidden=\"true\"></i> BEO Servizio</a></div>";



if($_SERVER['HTTP_HOST'] == 'calderonimartini.condivision.cloud'){

    $tableauManager =  ($tavoli['id'] > 1) ? "https://calderonimartini.condivision.biz/fl_modules/mod_tavoli/mod_layout.php?ambiente_id=".$ambiente."&layout=1&evento=".$evento_id : "https://calderonimartini.condivision.biz/fl_modules/mod_tavoli/mod_opera.php?ambiente_id=".$ambiente."&evento=".$evento_id.$templateCreate; 
}else{

    $tableauManager = "../mod_tavoli/mod_scelta_template.php?ambiente_id=".$ambiente."&evento_id=".$evento_id ;

}


 echo "<div class=\"module_icon\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view \" href=\"$tableauManager\" title=\"Tableau Manager\" style=\"color:   $colorScheda\"><i class=\"fa fa-braille\" aria-hidden=\"true\"></i> TAVOLI E OSPITI</a></div>";
 echo "<div class=\"module_icon\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view \" style=\"color:   $colorScheda\" href=\"../mod_stampe_servizio/mod_menu_evento.php?evento_id=".$evento_id."\" title=\"Lista ingresso\" style=\"color:   $colorMenu\"><i class=\"fa fa-map\" aria-hidden=\"true\"></i> MENU TAVOLI</a></div>";         
 if($tavoli['id'] > 1) echo "<div class=\"module_icon\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view_small \"  href=\"../mod_stampe_servizio/mod_cavalieri.php?evento_id=".$evento_id."\" title=\"Stampa Cavalieri\" style=\"color:   $colorScheda\"><i class=\"fa fa-bookmark\" aria-hidden=\"true\"></i>  CAVALIERI</i></a></div>";
 if($tavoli['id'] > 1) echo "<div class=\"module_icon\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view \" href=\"../mod_stampe_servizio/mod_lista_ingresso.php?evento_id=".$evento_id."\" title=\"Lista ingresso\" style=\"color:  $colorScheda\"><i class=\"fa fa-users\" aria-hidden=\"true\"></i></i>  LISTA INGRESSO</a></div>";
 if($tavoli['id'] > 1) echo "<div class=\"module_icon\"><a data-fancybox-type=\"iframe\" class=\"fancybox_view \" href=\"../mod_stampe_servizio/mod_lista_intolleranze.php?evento_id=".$evento_id."\" title=\"Lista intolleranze\" style=\"color:  $colorScheda\"><i class=\"fa fa-leaf\" aria-hidden=\"true\"></i>  LISTA INTOLLERANZE</a></div>";
 $GeneraAllegato =  "mod_allegato1.php?evento_id=".$evento_id;

 echo "<div><a href=\"mod_inserisci.php?id=$evento_id\" class=\"button\">Vai a amministrazione</a>
 <a href=\"$GeneraAllegato\" data-fancybox-type=\"iframe\" class=\"fancybox_view button\" title=\"Visualizza/Stampa Allegato\"> Allegato Contratto</a> 

 ".$evento['titolo_ricorrenza']." ".mydatetime($evento['data_evento'])." (".$giorni_settimana[date("w", strtotime($evento['data_evento']))].")  | Coperti: ".($evento['numero_adulti']+$evento['numero_bambini']+$evento['numero_operatori'])." | Prezzo base: &euro; ".$evento['prezzo_base']." | Ultima Revisione: ".$dataRevisione." </div>";
?>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">
<?php include('../mod_basic/action_estrai.php'); 
 echo '<input type="hidden" name="info" value="../mod_eventi/mod_scheda_servizio.php?evento_id='.$evento_id.'&id=" />';

 ?>
</form>



<?php if(check($_GET['id']) != 1 && $evento['stato_evento'] == 4) { 
echo "<a  href=\"../mod_basic/action_elimina.php?POST_BACK_PAGE=../mod_eventi/?gtx=$tab_id&amp;unset=".$id."\" title=\"Elimina\"  onclick=\"return conferma_del();\"><i class=\"fa fa-trash-o\"></i> Elimina </a>";
}



$ifYesText = array("segnaposti", "bomboniere", "dolci", "ospiti_serali", "miniclub", "stanza_sposi", "stanze_aggiuntive");	
echo '<style>';
foreach ($ifYesText as $key => $value) {
	echo "#box_$value { clear: none; } ";
	echo "#box_$value .input_text { width: 47%; float: right; clear: none;  } ";
	echo "#box_$value .input_text label { width: auto; margin: 0;  } ";
	echo "#box_$value .input_text input { width: 58%; margin: 0;  } ";
	
}
echo '</style>';
			

?>

<script>
    
    var MycheckBox = document.getElementsByClassName("EnableDisable");
    
    for(var i = 0; i < MycheckBox.length; i++){
        
        var elemento = MycheckBox[i];
       
        
        elemento.addEventListener('click', function(evento){
             
             if(evento.target.checked){
                 evento.target.nextSibling.nextSibling.disabled = false;
                 evento.target.nextSibling.nextSibling.value = "SI";
            }else{
                 evento.target.nextSibling.nextSibling.disabled = true;
                 evento.target.nextSibling.nextSibling.value = "NO";
         }
       }, false);
    }
   
</script>

</div></div></body></html>
