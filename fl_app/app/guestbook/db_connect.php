<?php
$mysqli = new mysqli("server1.aryma.it", "usr_matricloud", "w07Imz&1", "db_matrimonioincloud");
$conn = mysqli_connect("server1.aryma.it", "usr_matricloud", "w07Imz&1", "db_matrimonioincloud");


if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

if (!$mysqli) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

?>