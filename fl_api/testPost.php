<?php 

define('TESTING',1);
include('../fl_core/autentication.php'); // Variabili Modulo 

?>


<html>

<head></head>

<body>

<h3>Login</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="text" value="usr_login" name="usr_login"><br>
<input type="text" value="app" name="token"><br>
<input type="text" value="testbetitaly"  placeholder="user"  name="user"><br>
<input type="text" value="" placeholder="Password" name="password"><br>
<br>
<input type="text" value="1234567ABCD"  placeholder="fcmToken"  name="fcmToken"><br>


<input type="submit" value="Invia"><br>
</form>

<table>
	
<tr><td>
<h3>Mostra Ricevute</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">


<input type="hidden" value="14" name="getRicevute">
<input type="hidden" value="app" name="token">
<input type="text" value="2486" name="usr_id">
<br>
<input type="submit" value="Mostra"><br>
</form>





<h3>Carica Ricevuta</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="hidden" value="1" name="insertRicevuta">
<input type="hidden" value="app" name="token">

<label>Id del record. 1 = nuovo</label>
<input type="text" value="1" name="id"><br>
<label>Mandare 3 variabili con id dell utente</label>
<input type="text" value="2486" name="usr_id"><br>
<input type="text" value="2486" name="operatore"><br>
<input type="text" value="2486" name="proprietario"><br>
<input type="text" value="1" name="status_pagamento"><br>
<input type="text" value="APP test" name="user"><br>
<input type="text" value="<?php echo date('d/m/Y'); ?>" name="data_pagamento"><br>
<input type="text" value="100.00" name="importo"><br>
<p>File da allegare</p>
<input type="file" name="documento_fronte"><br>

<input type="submit" value="Invia"><br>
</form>

<h3>Cancella Ricevuta</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="hidden" value="1" name="deleteRicevuta">
<input type="hidden" value="app" name="token">
<input type="text" value="0" name="id">
<input type="text" value="1" name="operatore">
<br>
<input type="submit" value="Cancella"><br>
</form>

</td>

<td>

<h3>Mostra Attivazioni</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">


<input type="hidden" value="14" name="getAttivazioni">
<input type="hidden" value="app" name="token">
<input type="text" value="2486" name="usr_id">
<br>
<input type="submit" value="Mostra"><br>
</form>





<h3>Carica Attivazione</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="hidden" value="1" name="insertAttivazione">
<input type="hidden" value="app" name="token">

<label>Id del record. 1 = nuovo</label>
<input type="text" value="1" name="id"><br>
<label>Mandare 3 variabili con id dell utente</label>
<input type="text" value="2486" name="usr_id"><br>
<input type="text" value="2486" name="operatore"><br>
<input type="text" value="2486" name="proprietario"><br>
<input type="text" value="APP test" name="user"><br>
<input type="text" value="APP test" name="codice_fiscale"><br>
<input type="text" value="APP test" name="nome_e_cognome"><br>

<input type="file" name="documento_fronte"><br>
<input type="file" name="documento_retro"><br>
<input type="submit" value="Invia"><br>
</form>

<h3>Cancella Attivazioni</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="hidden" value="1" name="deleteAttivazione">
<input type="hidden" value="app" name="token">
<input type="text" value="0" name="id">
<input type="text" value="1" name="operatore">
<br>
<input type="submit" value="Cancella"><br>
</form>


</td>

<td>

<h3>Mostra Traferimento</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">


<input type="hidden" value="1" name="getTraferimenti">
<input type="hidden" value="app" name="token">
<input type="text" value="2486" name="usr_id">
<br>
<input type="submit" value="Mostra"><br>
</form>





<h3>insertTrasferimento</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="hidden" value="1" name="insertTrasferimento">
<input type="hidden" value="app" name="token">

<label>Id del record. 1 = nuovo</label>
<input type="text" value="1" name="id"><br>
<label>Mandare 3 variabili con id dell utente</label>
<input type="text" value="2486" name="usr_id"><br>
<input type="text" value="2486" name="operatore"><br>
<input type="text" value="2486" name="proprietario"><br>
<input type="text" value="APP test" name="user"><br>
<input type="text" value="APP test" name="codice_fiscale"><br>
<input type="text" value="APP test" name="nome_e_cognome"><br>

<input type="file" name="documento_fronte"><br>
<input type="file" name="documento_retro"><br>
<input type="file" name="documento_trasferimento"><br>

<input type="submit" value="Invia"><br>
</form>

<h3>Cancella Trasferimento</h3>

<form action="index.php" method="POST"  enctype="multipart/form-data">

<input type="hidden" value="1" name="deleteTrasferimento">
<input type="hidden" value="app" name="token">
<input type="text" value="0" name="id">
<input type="text" value="1" name="operatore">
<br>
<input type="submit" value="Cancella"><br>
</form>


</td>

</tr>

</table>

<?php echo $db; ?>



</body></html>
