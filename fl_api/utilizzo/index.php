<?php 

header("Access-Control-Allow-Origin: *");
session_start();

ini_set('display_errors',1); // Debug 1
error_reporting(E_ALL);

require_once 'restServices.php';

$CLIENT_ID = '103';
$CLIENT_SECRET = 're56fdsfw285hfw5k3923k2ASYLWJ8tr3';

$restService = new restServices($CLIENT_ID, $CLIENT_SECRET); //Avvio del client e recupero TOKEN
$_SESSION['accessToken'] = $restService->accessToken; // Salvo eventualmente access token



/* Realizza le chiamate possibili con uno switch o come preferisci */
if(isset($_POST['get_attivita'])) $esito = $restService->get_attivita($_POST); // POST del form o array di dati da inviare

if(isset($_POST['get_leads'])) $esito = $restService->get_leads($_POST); // POST del form o array di dati da inviare

if(isset($_POST['insert_lead'])) $esito = $restService->insert_lead($_POST); // POST del form o array di dati da inviare



echo json_encode($esito); // Ritorna oggetto con esito convertire in JSON per uso via Ajax



?>