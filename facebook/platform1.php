
<?php
require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '334959493535208',
  'app_secret' => '3579e6434131047512913d63769e1f31',
  'default_graph_version' => 'v2.5',
]);
# login
// Sets the default fallback access token so we don't have to pass it to each request
$fb->setDefaultAccessToken('EAAEwpNWgVegBAPLxDgLcPcszvBwSMBZBXuR9YNF46X6KZClQx44hPuD2SKOBzBAMji4eDFUhVr1ZCoZCh54ibVQcFPW2mRZBwZAB9XG6OzVwzDggBsuvqe4zzYaeqUJqHKFTbLrvendZCaTFWEEi3tO8fTMHZAETnzKerIJ1sNCGBwZDZD');

try {
  $response = $fb->get('/me');
  $userNode = $response->getGraphUser();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

echo 'Logged in as ' . $userNode->getName();
$name =$userNode->getName();
if(isset($name)){
   header('Location:https://www.bluemotive.it/facebook/read.php');
}
 else{
   echo "riprova il login";
 }


?>
