<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '692590741999-6pe99avsiq5o3ljc5riljq804eiatigk.apps.googleusercontent.com';
$clientSecret = 'IObmkzpIBKRln1eTftTakJV6';
$redirectURL = 'http://www.matrimonioincloud.it/app/guestbook/';

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to Matrimonioinclud');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>