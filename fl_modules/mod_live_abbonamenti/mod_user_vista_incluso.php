<?php


include 'fl_settings.php';

$new_button = '';



$query = "SELECT *,DATE_FORMAT(data_avvio,'%d/%m/%Y') as data_avvio_f,DATE_FORMAT(data_fine,'%d/%m/%Y') as data_fine_f FROM fl_abb_user WHERE id_user = " . $_SESSION['number'];
$risultato = mysql_query($query, CONNECT);


if (mysql_affected_rows() == 0) {echo "Nessun Elemento";}

$riga = mysql_fetch_assoc($risultato);

echo '<div id="container">';

if($riga['status'] == 1){
    echo '<p>Il tuo abbonamento &egrave attivo dal '.$riga['data_avvio_f'].' e scade il '.$riga['data_fine_f'].'</p>';
}else{
    echo '<p>Per  attivare il tuo abbonamento esegui il versamento secondo le modalit&agrave previste,invia copia contabile alla seguente mail info@1x2live.it ed attendi i tempi di incasso,ricordati di inserire nella causale il proprio account.<br><br>Grazie per aver scelto 1x2live.it<br><br>Dati Bancari:  Xp Trading srl Iban IT34Y0200879631000104007703 </p>';
}


?>
</div>

<script>$('.paginazione').css('display','none');</script>
