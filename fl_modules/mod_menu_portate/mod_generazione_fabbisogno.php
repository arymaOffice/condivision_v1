<?php 

require_once('../../fl_core/autentication.php');
require_once('fl_settings.php');
unset($chat);
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
$nochat;
$new_button = '';
$module_title = "Genera Fabbisogno periodo";

include("../../fl_inc/headers.php");
 ?>

<?php if(!isset($_GET['external'])) include('../../fl_inc/testata.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/menu.php'); ?>
<?php if(!isset($_GET['external'])) include('../../fl_inc/module_menu.php'); ?>



<div class="content_scheda">
  <?php
if(!isset($_GET['data_da']) ) { 

  echo "<p>Ciao ".$_SESSION['nome'].", indica un periodo da elaborare, grazie.</p>"; 

} ?>

<div style="text-align: left;">
<form method="get" action="" id="fm_filtri">
 
     Voglio elaborare il fabbisogno <label> dal</label>
      <input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>"  class="calendar" size="8" />
   
    
      <label> al </label>
      <input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="8" />

     <input type="submit" value="Procediamo!" class="button" />

</form>
</div>

<?php 
if(isset($_GET['data_da']) && check($_GET['data_da']) != "") { ?>


<form id="form" name="" method="POST" action="mod_opera.php">


<?php



$sediCoinvolte = ' AND location_evento = 31'; // da abilitare

$eventiCoinvolti = GQS('fl_eventi_hrc','id,titolo_ricorrenza,numero_adulti,numero_bambini,numero_operatori,numero_serali,data_evento',"id > 1  AND DATE(`data_evento`) BETWEEN '$data_da' AND '$data_a' AND stato_evento != 4 ORDER BY data_evento ASC");


foreach ($eventiCoinvolti as $key => $evento) {

$coperti = $evento['numero_adulti']+$evento['numero_operatori'];
echo '<h1 id="cMp'.base64_encode($evento['id']).'"><a href="../mod_eventi/mod_inserisci.php?id='.$evento['id'].'&external" data-fancybox-type="iframe" class="fancybox_view" title="Apri Scheda Gestione">'.$evento['id'].'</a> '.$evento['titolo_ricorrenza'].' '.mydatetime($evento['data_evento']).'</h1>';
$menuEvento = GQS('fl_menu_portate','id,descrizione_menu,confermato',"id > 1  AND evento_id = ".$evento['id']);

// Per ogni menu evento
foreach ($menuEvento as $key => $menu) { 

$confermato = ($menu['confermato'] == 0) ? '<span class="msg orange">NON CONFERMATO</span>' : '<span class="msg green">CONFERMATO</span>';
echo '<p><strong>'.$menu['descrizione_menu'].'</strong> | '.$confermato.' | <a href="mod_opera.php?cMp='.base64_encode($evento['id']).'" onclick="return conferma_del();">Cancella preparazione</a></p>';
$menuId = $menu['id'];


  $queryPortate = "SELECT r.variante,r.portata,r.id,r.nome_tecnico,r.valore_di_conversione,lasts.valore,lasts.note,lasts.priority,lasts.id AS synId FROM fl_ricettario r JOIN ( SELECT  s.id,s.id1,s.id2,s.valore,s.note,s.priority FROM fl_synapsy s WHERE s.type2 = 119 AND s.type1 = 123 and s.id1 = $menuId) lasts ON r.id = lasts.id2 ORDER BY (r.portata) ASC, lasts.priority ASC, lasts.id ASC ";
  $resultPortate = @mysql_query($queryPortate,CONNECT);
  $ricette = array();
  

   echo "
   <table class=\"dati\">
   <tr>
   <th>Codice</th>
   <th>Portata</th>
   <th>Adulti</th>
   <th>Bambini</th>
   <th>Operatori</th>
   <th>Serali</th>
   <th>Definita</th>
   <th>Preparazione</th>
   <th>Extra %</th>
   </tr>";

  while ($row = @mysql_fetch_array($resultPortate)) {

   $isAlreadyIn = GQD('fl_ricettario_fabbisogno','id,SUM(quantita) AS quantitaTot','evento_id = '.$evento['id'].' AND ricetta_id = '.$row['id']);
   $definita = (isset($isAlreadyIn['quantitaTot'])) ? $isAlreadyIn['quantitaTot'] : 0;
   $quantita = ($definita == 0) ? $evento['numero_adulti'] + $evento['numero_operatori'] : 0;
   $colore = ($definita == 0) ? 'orange' : 'green';
  
   echo "<tr>
   <td>".$row['id']."<input type=\"hidden\" name=\"ricetta_id[]\" value=\"".$row['id']."\"><input type=\"hidden\" name=\"evento_id[]\" value=\"".$evento['id']."\"></td>
   <td><a href=\"../mod_ricettario/mod_diba.php?record_id=".$row['id']."\" data-fancybox-type=\"iframe\" class=\"fancybox_view\">".$row['nome_tecnico']."</a></td>
   <td>".$evento['numero_adulti']."</td>
   <td>".$evento['numero_bambini']."</td>
   <td>".$evento['numero_operatori']."</td>
   <td>".$evento['numero_serali']."</td>
   <td><input class=\"sc-field ".$colore."\" type=\"number\" id=\"definita_".$evento['id'].'_'.$row['id']."\" step=\"1\" name=\"definita[]\" value=\"".$definita."\" style=\"width: 60px;\" disabled></td>
   <td><input class=\"sc-field\" type=\"number\" id=\"".$evento['id'].'_'.$row['id']."\" step=\"1\" name=\"quantita[]\" value=\"$quantita\" style=\"width: 60px;\">
   </td>
   <td><input class=\"sc-field\" type=\"number\" step=\"any\" min=\"-100\" max=\"100\" name=\"percentuale_extra\" id=\"extra_".$evento['id'].'_'.$row['id']."\" value=\"10\" style=\"width: 60px;\" >
   <a href=\"javascript:void(0);\" onClick=\"setExtra('".$evento['id'].'_'.$row['id']."');\" class=\"button\" >Aggiorna % </a></td>
   </tr>";

   }

   echo "</table>";
}

}
	
?>

<script type="text/javascript">
  
  function setExtra(item) { 

  var base = (parseInt($("#"+item).val()) > 0) ? parseInt($("#"+item).val()) : parseInt($("#definita_"+item).val());
  var extra = base*parseInt($("#extra_"+item).val())/100;
  $("#"+item).val(parseInt(parseInt($("#"+item).val())+extra)); 

  }
</script>
<input type="hidden" value="1" name="creaFabbisogno">
<input type="submit" value="Genera Fabbisogno" class="button noprint">
<a href="javascript:window.print();" class="button noprint">Stampa</a>

</form>

<?php } else { } ?>


<br><br><br><br>
</div>


<?php include("../../fl_inc/footer.php"); ?>

