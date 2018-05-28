<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo

unset($chat);
$jquery = 1;
$fancybox = 1;

$record_id = check($_GET['record_id']);
$connectId = check($_GET['connectId']);
$connectTableId = $tables[$connectId];
include "../../fl_inc/headers.php";

?>


<h1>Associazioni tra elementi</h1>

<?php if ($record_id == 1) {echo "<h2>Salva i dettagli prima di associare qualcuno</h2>";exit;}?>


<form action="mod_opera.php" method="get">
<input type="hidden" name="id1" value="<?php echo $record_id; ?>">
<input type="hidden" name="type2" value="85">
<select name="id2" placeholder="Seleziona qualcuno">
<option>Seleziona una persona</option>
<?php foreach ($persone as $key => $value) {
    # code...
    echo "<option value=\"$key\">$value</option>";
}?>
</select>

<select name="valore" placeholder="Seleziona qualcuno">
<option>Valore di assegnazione </option>
<option value="0">0</option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
</select>


<input type="submit" value="Iscrivi al gruppo" />
</form>

<div id="results"><?php if (isset($_GET['esito'])) {
    echo '<h2 class="red">' . check($_GET['esito']) . '</h2>';
}
?></div>

<p>Il sistema assegna i leads per il valore impostato in modo ciclico. <br>Es. 3 operatori hanno un valore rispettivamente di 2,1,1 significa che il primo operatore gestira il 50% del carico di lavoro e gli altri due il 25%. (Ovvero il totale viene diviso in 4 e poi ad ognuno assegnato il rispettivo valore di gestione.</p>
<?php

$query = "SELECT s.*,a.nominativo,a.tipo,a.attivo FROM `fl_synapsy` s LEFT JOIN fl_account a ON a.persona_id = s.id2  WHERE `type1` = $tab_id AND id1 = $record_id  AND type2 = $connectId GROUP BY id2 ORDER BY nominativo ASC";
$risultato = mysql_query($query, CONNECT);
if (mysql_affected_rows() == 0) {echo "<p>Nessun Elemento</p>";} else {
    ?>

    <table class="dati">

 <?php

    while ($riga = mysql_fetch_assoc($risultato)) {

        $tipoAccount = (isset($riga['tipo'])) ? ' (' . $tipo[$riga['tipo']] . ')' : '(Nessun account) ';
        $valore = $riga['valore'];
        $bold = ($valore > 0) ? 'font-weight: bold;' : '';
        $attivo = (!isset($riga['attivo']) || $riga['attivo'] == 0) ? ' color: red' : '';
        ?>



      <tr>
      <td style="<?php echo $bold . $attivo; ?>"><?php echo $riga['nominativo'].' '.$tipoAccount; ?></td>
      <td>
      <input type="text" style="width: 250px;" name="valore" class="updateField" data-gtx="118" data-rel="<?php echo $riga['id']; ?>" value="<?php echo $valore; ?>" min="0" max="5" />

  	  <td><a href="../mod_basic/action_elimina.php?gtx=118&amp;unset=<?php echo $riga['id']; ?>" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>
</tr>


    <?php }} //Chiudo la Connessione    ?>

 </table>

</body></html>