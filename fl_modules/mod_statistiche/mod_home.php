<?php
include 'fl_settings.php';

//evento più visto dati
$tb_evento = '';
$evento_plus = "SELECT titolo , count( * ) as quante  FROM `fl_data_link` dl JOIN fl_link_slider ls ON ls.id = dl.id_link  GROUP BY `id_link` ORDER BY quante DESC LIMIT 0 , 10";
$evento_plus = mysql_query($evento_plus, CONNECT);
while ($row = mysql_fetch_assoc($evento_plus)) {
    $tb_evento .= '<tr><td>' . $row['titolo'] . '</td><td>' . $row['quante'] . '</td></tr>';
}

//risoluzione più utilizzata dati
$tb_risoluzione = '';
$risoluzione_plus = "SELECT pollici , count( * ) as quante  FROM `fl_data_link_res` dl JOIN fl_link_resolution ls ON ls.id = dl.id_risoluzione  GROUP BY `id_risoluzione` ORDER BY quante DESC  LIMIT 0 , 10";
$risoluzione_plus = mysql_query($risoluzione_plus, CONNECT);
while ($row = mysql_fetch_assoc($risoluzione_plus)) {
    $tb_risoluzione .= '<tr><td>' . $row['pollici'] . '</td><td>' . $row['quante'] . '</td></tr>';
}

//numero monitor più utilizzata dati
$tb_n_monitor = '';
$n_monitor_plus = "SELECT n_monitor , count( * ) as quante  FROM `fl_data_link_mon`  GROUP BY `n_monitor` ORDER BY quante DESC  LIMIT 0 , 10";
$n_monitor_plus = mysql_query($n_monitor_plus, CONNECT);
while ($row = mysql_fetch_assoc($n_monitor_plus)) {
    $tb_n_monitor .= '<tr><td>' . $row['n_monitor'] . '</td><td>' . $row['quante'] . '</td></tr>';
}


//abbonamento più scelto dati
$tb_abb = '';
$abb_plus = "SELECT nome , count( * ) as quante  FROM `fl_abb_user`  au JOIN fl_abbonamenti a ON a.id = au.id_abb   GROUP BY `id_abb` ORDER BY quante DESC  LIMIT 0 , 10";
$abb_plus = mysql_query($abb_plus, CONNECT);
while ($row = mysql_fetch_assoc($abb_plus)) {
    $tb_abb .= '<tr><td>' . $row['nome'] . '</td><td>' . $row['quante'] . '</td></tr>';
}
?>


<div class="col-sm-5" style="border:1x solid">
  <h2>Evento pi&ugrave; visto</h2>
  <table class="dati">
    <?php echo $tb_evento; ?>
  </table>

</div>
<div class="col-sm-5" style="border:1x solid">
<h2>Risoluzione pi&ugrave; utilizzata</h2>
<table class="dati">
    <?php echo $tb_risoluzione; ?>
  </table>
</div>

<div class="col-sm-5" style="border:1x solid">
<h2>Numero di monitor scelto</h2>
<table class="dati">
    <?php echo $tb_n_monitor; ?>
  </table>
</div>

<div class="col-sm-5" style="border:1x solid">
<h2>Abbonamento pi&ugrave; scelto</h2>
<table class="dati">
    <?php echo $tb_abb; ?>
  </table>
</div>



