<?php



// require_once 'FBintegration/GeneralFbIntegration.php';



$input = json_decode(file_get_contents('php://input'), true);

$myfile = fopen("./test/testfile.txt", "w") ;
fwrite($myfile,$input );
fclose($myfile);


//$input = json_decode('{"entry":[{"changes":[{"field":"leadgen","value":{"ad_id":0,"form_id":908966049268891,"leadgen_id":909012919264204,"created_time":1512334623,"page_id":355181517907090,"adgroup_id":0}}],"id":"355181517907090","time":1512334625}],"object":"page"}', true);



// new GeneralFbIntegration($input);






?>
