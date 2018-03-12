<?php
// Controlli di Sicurezza
if(!@$thispage){ echo "Accesso Non Autorizzato"; exit; }
$_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];

?>


<div class="filtri" id="filtri">
<form method="get" action="" id="fm_filtri">
<h2> Filtri</h2>

<?php if(isset($_GET['action'])) echo '<input type="hidden" value="'.check($_GET['action']).'" name="action" />'; ?>
<?php if(isset($_GET['start'])) echo '<input type="hidden" value="'.check($_GET['start']).'" name="start" />'; ?>

<label> Periodo da</label>
<input type="text" name="data_da" onFocus="this.value='';" value="<?php  echo $data_da_t;  ?>" class="calendar" size="10" />
<label> al </label>
<input type="text" name="data_a" onFocus="this.value='';" value="<?php  echo $data_a_t;  ?>" class="calendar" size="10" />

<input type="submit" value="Mostra" class="salva" id="filter_set" />

</form>
</div>

<style>
@media screen and (max-width: 400px) {
  .hide-mobile{
    display:none !important;
  }
}
</style>

<?php 
    if($_SESSION['usertype'] != 0) echo '<style> .fa.fa-plus-circle{ display:none; } </style>';

$start = paginazione(CONNECT,$tabella,$step,$ordine,$tipologia_main);
$query = "SELECT $select FROM `$tabella` $tipologia_main ORDER BY $ordine LIMIT $start,$step;";
$risultato = mysql_query($query, CONNECT);

?>

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


  
