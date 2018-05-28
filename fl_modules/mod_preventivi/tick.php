<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
echo 'data: {"data1": '. rand(1,10) . ',"data2": '. rand(1,10) . ',"data3": '.
rand(1,10) . ',"data4": '. rand(1,10) . '}' . PHP_EOL;
echo PHP_EOL;
?>