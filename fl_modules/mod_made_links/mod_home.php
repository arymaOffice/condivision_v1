<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>


<style>
@media screen and (max-width: 400px) {
  .hide-mobile{
    display:none !important;
  }
}
</style>

<?php 
    if($_SESSION['usertype'] != 0) echo '<style> .fa.fa-plus-circle{ display:none; } </style>';

$tipologia_main .= (isset($_GET['cat'])) ? ' AND categoria_link='.filter_var($_GET['cat'],FILTER_SANITIZE_NUMBER_INT) : '';
$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
$risultato = mysql_query($query, CONNECT);

?>

<p> <br>
<select onchange=" window.location = '?a=configuratore&cat=' + $(this).val();">
<?php 
  foreach($categoria_link as $key => $value ){
    $selected = ($_GET['cat'] == $key || (!isset($_GET['cat']) && $key == 0)) ? 'selected' : '';
    echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
  }
?>
</select>
</p>

<table class="dati" summary="Dati">

<?php
if(mysql_affected_rows() == 0) { echo "<tr><td colspan=\"5\">Nessun Elemento</td></tr>";    }

  while ($riga = mysql_fetch_array($risultato)){

    $multimonitor = ($riga['multimonitor']) ? '<span class="msg blue"> multimonitor </span>' : '';

    echo '<tr>
          <td style="width:15%" class="hide-mobile">
          <a href="mod_single_event.php?id='.$riga['id'].'" title='.$riga['titolo'].'" >
            <img style="height:100px" src="'.DMS_PUBLIC.'/monitor.png" alt="immagine monitor">
          </a>
        </td>

        <td style="width:85%">
          <a href="mod_single_event.php?id='.$riga['id'].'" title="'.$riga['titolo'].'" >
            <h3>'.$riga['titolo'].'</h3>
            <h4>'.$riga['sottotitolo'].'</h4>
              <span class="msg orange">'.$categoria_link[$riga['categoria_link']].'</span>
              '.$multimonitor.'
          </a>
        </td>';

    if($_SESSION['usertype'] == 0){
      echo   '<td><a href="mod_inserisci.php?id='.$riga['id'].'" title="Modifica" > <i class="fa fa-search"></i> </a></td>
      <td><a  href="../mod_basic/action_elimina.php?gtx='.$tab_id.'&amp;unset='.$riga['id'].
      '" title="Elimina"  onclick="return conferma_del();"><i class="fa fa-trash-o"></i></a></td>';
    }

     echo  '</tr>';

  }

?>

</table>

  <?php $start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main,0); ?>


  
