<?php
require_once __DIR__ . '/vendor/autoload.php';
if (!session_id()) {
    session_start();
}

$fb = new Facebook\Facebook([
  'app_id' => '334959493535208',
  'app_secret' => '3579e6434131047512913d63769e1f31',
  'default_graph_version' => 'v2.5',
]);
$helper = $fb->getRedirectLoginHelper();

$permissions = ['email','publish_actions','ads_management','ads_read','manage_pages','user_actions.books','pages_show_list','publish_pages','read_page_mailboxes','read_insights']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://ford.bluemotive.it/facebook/fb-callback.php', $permissions);
$_SESSION['FBRLH_' . 'state'];
echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
 ?>
