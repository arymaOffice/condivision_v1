<?php 

require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo 

if(check($_GET['id']) != 1) {
	/*Se esite questa array, la scheda modifica viene suddivisa all'occorenza del campo specificato o si possono aggiungere sotto schede */
	$tab_div_labels = array('id'=>"Anagrafica Elemento",'./mod_listino.php?record_id=[*ID*]'=>'Listino');
}

include("../../fl_inc/headers.php");
include("../../fl_inc/testata_mobile.php");


 ?>


<body>
<div id="container" >


<div id="content_scheda">

<form id="scheda" action="mod_opera.php" method="post" enctype="multipart/form-data">



 <select id="mySelect" class="form-control" name="note[]" multiple="multiple">
			<option selected="selected">Gluten Free</option>
			<option selected="selected">Gluten Free</option>
			<option selected="selected">Gluten Free</option>
			<option selected="selected">Gluten Free</option>
			<option selected="selected">Gluten Free</option>
			</select>



		
<input type="submit" />
</form>


</div>
</div>
</body></html>
