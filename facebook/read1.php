<?php
require_once __DIR__ . '/vendor/autoload.php';
use Facebook\GraphNodes\GraphNode;
use facebook\GraphEdge\GraphEdge;
use string Facebook\FacebookRequest;
use FacebookAds\Object\Lead;

$fb = new Facebook\Facebookapp([
  'app_id' => '334959493535208',
  'app_secret' => '3579e6434131047512913d63769e1f31',
  'default_graph_version' => 'v2.5',
]);
  //$fb->setDefaultAccessToken('EAAEwpNWgVegBAJlxiIEuSaZBV4R0TqL51pT5Q3WzD6ZA3A7xIGZAFrczEJFRZCiix5EgxliLjb1i5PU9m1rdZC0cvxvu6XyAcPGD6UyvPZC2NZAa0qZCxXYbMZCTwfQhzHTp212ZBfdhKZCLZBRz254ZCcEkkeZCnZCZBnJ6maxgmQO8qzG0bQZDZD');
$request = new FacebookRequest(
  $fb,
  'GET',
	'EAAEwpNWgVegBAJlxiIEuSaZBV4R0TqL51pT5Q3WzD6ZA3A7xIGZAFrczEJFRZCiix5EgxliLjb1i5PU9m1rdZC0cvxvu6XyAcPGD6UyvPZC2NZAa0qZCxXYbMZCTwfQhzHTp212ZBfdhKZCLZBRz254ZCcEkkeZCnZCZBnJ6maxgmQO8qzG0bQZDZD',
  '/812890898813706/',
  array(
    'fields' => 'leads'
  )
);
$response = $request->execute();
$data_array = $response->getGraphObject()->asArray();
$counter = array_map("count", $data_array);
    $count = $counter['data'];

    for ($x = 0; $x <= $count; $x++)
            {
            	//$names[$x] = $data_array['data'][$x]->name;
            $ids[$x] = $data_array['data'][$x]->id;
            }

							//print_r($names);
						print_r($ids);*/


/*  try {

  $response = $fb->getClient()->sendRequest($request);

  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
	// Send the request to Graph
	try {
	  $response = $fb->getClient()->sendRequest($request);
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}
echo "qui";
	$graphNode = $response->getGraphNode();
print_r($graphNode);
	echo 'User name: ' . $graphNode['name'];*/
 ?>
