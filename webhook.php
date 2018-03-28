<?php
$verify_token = "abcd123";
$hub_verify_token = null;

if(isset($_REQUEST['hub_challenge'])) {
$challenge = $_REQUEST['hub_challenge'];
$hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
echo $challenge;
}


require_once 'FBintegration/GeneralFbIntegration.php';


$input = json_decode(file_get_contents('php://input'), true);

//$input = json_decode('{"entry":[{"changes":[{"field":"leadgen","value":{"ad_id":0,"form_id":908966049268891,"leadgen_id":909012919264204,"created_time":1512334623,"page_id":355181517907090,"adgroup_id":0}}],"id":"355181517907090","time":1512334625}],"object":"page"}', true);



new GeneralFbIntegration($input);






?>
