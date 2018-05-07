<?php 

ini_set('display_errors',1);
error_reporting(E_ALL);
require_once('../../../fl_core/autentication.php');
include('../../../fl_core/category/cms.php');
require_once('../../../fl_core/class/ARY_dataInterface.class.php');
$data_set = new ARY_dataInterface();
?>

<ul>
<?php 
	if(isset($_GET['line'])){
	$tipo_prodotto_id = check($_GET['line']); 
	$tipologia_main = "WHERE id != 1";
	$start = 0;
	if(isset($_GET['start'])) $start = check($_GET['start']); 
	$step = 10;
	if(@$tipo_prodotto_id != "") $tipologia_main .= ' AND categoria_prodotto = '.$tipo_prodotto_id.' ';	
	$query = "SELECT * FROM `fl_prodotti` $tipologia_main ORDER BY id ASC LIMIT $start,$step;";
	$risultato = mysql_query($query, CONNECT);
	
	if(mysql_affected_rows() == 0) { 		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$contenuto = (file_exists('../../../fl_set/lay/prodotti/'.$riga['id'].'.jpg')) ?  '<img src="../../fl_set/lay/prodotti/'.$riga['id'].'.jpg" alt="" />' : $riga['label'];
	echo '<li><a href="#" id="'.$riga['id'].'" class="select_product">'.$contenuto.'</a></li>';
	}
	if(mysql_affected_rows() >= $step) echo '<a href="#" class="button loadMore" data-line="'.$tipo_prodotto_id.'" data-start="'.($start+$step).'">Altri prodotti <br> <i class="fa fa-sort-desc"></i><br></a>';
	}
	
	
	if(isset($_GET['product'])){
	$prodotto_id_id = check($_GET['product']); 
	$line = $data_set->get_record('fl_prodotti',$prodotto_id_id);

	$tipologia_main = "WHERE id != 1 AND attivo = 1";
	if(@$prodotto_id_id != "") $tipologia_main .= ' AND prodotto_id = '.$prodotto_id_id.' ';	
	$query = "SELECT id,amount,label,valore_facciale,label FROM `fl_sottoprodotti` $tipologia_main ORDER BY id ASC;";
	$risultato = mysql_query($query, CONNECT);
	echo '<h1>'.$line['label'].'</h1>
	<p>Seleziona un taglio</p>';

	if(mysql_affected_rows() == 0) { echo "<li>Nessun servizio disponibile</li>";		}
	
	while ($riga = mysql_fetch_array($risultato)) 
	{
	$page = ($line['tipo_prodotto'] == 0) ? 'parts/do/?ric&' : 'parts/do/?';
	$contenuto = (file_exists('../../../fl_set/lay/prodotti/'.$prodotto_id_id.'.jpg')) ?  '<img src="../../fl_set/lay/prodotti/'.$prodotto_id_id.'.jpg" alt="" />' : $riga['label'];

	echo '<li><a href="'.$page.'product='.$riga['id'].'">'.$contenuto.' <br><span>'.$riga['label'].' <strong> &euro; '.numdec($riga['valore_facciale'],2).'</strong> </span></a></li>';
	}}

?>
</ul>
<?php @mysql_close(CONNECT); ?>