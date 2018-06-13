<?php
require_once '../../fl_core/autentication.php';

include 'fl_settings.php';
include "../../fl_inc/headers.php";

$query = "SELECT *,DATE_FORMAT(data_avvio,'%d/%m/%Y') as data_avvio_f,DATE_FORMAT(data_fine,'%d/%m/%Y') as data_fine_f FROM fl_abb_user WHERE id_user = " . $_SESSION['number'];
$risultato = mysql_query($query, CONNECT);


if (mysql_affected_rows() == 0) {echo "Nessun Elemento";}

$riga = mysql_fetch_assoc($risultato);
?>

<p>Il tuo abbonamento &egrave attivo dal <?php echo $riga['data_avvio_f'] ?> e scade il <?php echo $riga['data_fine_f'] ?>
<br> per completare l'attivazione &egrave necessario completare il pagamento <a href="" class="button" >Paga ora</a>
</p>



<script>$('.paginazione').css('display','none');</script>
