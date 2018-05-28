<?php 

require_once('../../fl_core/autentication.php');

$id = check($_GET['id']);


include('fl_settings.php'); // Variabili Modulo 

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");
 ?>




<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="container" >



<div id="content_scheda">

<?php if(isset($_GET['esito'])) { $class = (isset($_GET['success'])) ? 'green' : 'red'; echo '<p class="esito '.$class.'">'.check($_GET['esito']).'</p>'; }  ?>

<form id="scheda" action="../mod_basic/action_modifica.php" method="post" enctype="multipart/form-data">

<p class="insert_tags">Inserisci tag: 
<a href="#" onclick="insertAtCaret('messaggio',' [nome] ');return false;">[nome]</a>
<a href="#" onclick="insertAtCaret('messaggio',' [modello_vettura_permuta] ');return false;">[modello_vettura_permuta] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [vettura_di_interesse] ');return false;">[vettura_di_interesse] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [prezzo_vettura] ');return false;">[prezzo_vettura] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [valore_permuta] ');return false;">[valore_permuta] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [nome_bdc] ');return false;">[nome_bdc] </a>
<a href="#" onclick="insertAtCaret('messaggio',' [cellulare_bdc] ');return false;">[cellulare_bdc] </a>

<?php foreach($tag_sms as $chiave => $valore) {
	$infotag = GRD('fl_items',$chiave);
	if($chiave > 1) echo "<a href=\"#\" onclick=\"insertAtCaret('messaggio',' ".$infotag['descrizione']." ');return false;\">".$infotag['label']."</a>";
	} ?>
</p>
<?php include('../mod_basic/action_estrai.php');  ?>

<input type="hidden" name="dir_upfile" value="icone_articoli" />


</form>



</div></body></html>
