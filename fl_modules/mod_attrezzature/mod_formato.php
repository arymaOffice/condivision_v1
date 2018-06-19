<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 
include("../../fl_inc/headers.php");

?>


<body>

<div id="container" >

<div id="content_scheda">

<?php 

if(isset($_GET['id_materia'])) {
$id_materia = check($_GET['id_materia']);
$materiaPrima = GRD($tables[115], $id_materia); 
echo '<div class="info_dati"><h1 style="display: inline-block; margin: 0 0 5px;"><strong><a href="./mod_inserisci.php?id='.$materiaPrima['id'].'">'.$id_materia.' '.$materiaPrima['descrizione'].' [Vai a scheda anagrafica]</a></strong></h1>';

}
?>


<form id="scheda" method="post" enctype="multipart/form-data">
<h1>Modifica Formato</h1>
<?php   include('../mod_basic/action_estrai.php');  ?>
<input type="hidden" name="info" value="1"> 
</form>



</div>
</div>
</body></html>
