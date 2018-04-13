<?php
require('../config/db.config.php');

$tavolo_id = $_POST['tavolo_id'];
$user_id = $_POST['user_id'];
$sql = "UPDATE fl_tavoli_commensali SET tavolo_id ='$tavolo_id' WHERE id='$user_id'";
mysqli_query($conn, $sql);

mysqli_close($conn);
