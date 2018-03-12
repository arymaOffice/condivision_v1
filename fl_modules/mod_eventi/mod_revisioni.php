<?php
require_once('../../fl_core/autentication.php');
include('fl_settings.php'); // Variabili Modulo
$evento_id = check($_GET['evento_id']);

$selectRevisioni ="SELECT *,DATE_FORMAT(rv.data_creazione,'%d/%m/%Y %H:%i') as data ,IF((SELECT count(*) FROM  fl_revisioni_check rc WHERE rv.id = rc.revisione_id AND rv.proprietario = rc.proprietario  ) > 0,true,false) as visto FROM fl_revisioni_hrc rv WHERE evento_id = $evento_id ORDER BY rv.id DESC ";
$selectRevisioni = mysql_query($selectRevisioni,CONNECT);

$content = '';
$i = 0;
if(mysql_affected_rows()  > 0 ){
	while ($row = mysql_fetch_assoc($selectRevisioni)) {
	$visto = ($row['visto']) ? 'Visionato' : '<a class="button" href="mod_opera.php?revisione_id='.$row['id'].'&evento_id='.$evento_id.'">Conferma Visione</a>' ;
	$numero = mysql_affected_rows() - $i;
	$content .= '<tr><td>'.$numero.'</td><td>'.$promoter[$row['proprietario']].'</td><td>'.$row['data'].'</td>
	<td>'.$row['numero_adulti'].'</td>
	<td>'.$row['numero_bambini'].'</td>
	<td>'.$row['numero_sedie'].'</td>
	<td>'.$row['numero_sedioloni'].'</td>
	<td>'.$row['numero_serali'].'</td>
	<td>'.$row['numero_operatori'].'</td><td>'.html_entity_decode($row['note']).'</td><td>'.$visto.'</td><td><a class="button fancybox.iframe fancybox" href="mod_opera.php?revisione_id='.$row['id'].'">Report</a></td></tr>';
	$i ++;
	}

}else{
	$content = '<tr><td colspan="7">Nessuna revisione</td></tr>';
}
include("../../fl_inc/headers.php");
?>

<body style=" background: rgb(241, 241, 241) none repeat scroll 0% 0%;">
<div id="content_scheda">

<h1>Registro Revisioni</h1>

	<table class="dati">

		<tr>
			<th>Numero</th>
			<th>Creata da</th>
			<th>Data revisione</th>
			<th>A</th>
			<th>B</th>
			<th>S</th>
			<th>H</th>
			<th>L</th>
			<th>O</th>
			<th>Note</th>
			<th>Presa Visione</th>
			<th>Conferme Visione</th>
		</tr>

		<?php echo $content; ?>

	</table>

	<a class="button" href="?evento_id=<?php echo $evento_id; ?>">Ricarica revisioni</a>
	<a class="button facyboxParent" style="margin:0 26%" href="mod_insert_revisioni.php?evento_id=<?php echo $evento_id; ?>">Aggiungi revisione</a>
</div>

</body>
</html>
