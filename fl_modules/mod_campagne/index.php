<?php

require_once '../../fl_core/autentication.php';
include 'fl_settings.php'; // Variabili Modulo



if (!isset($_GET['external'])) {
    include '../../fl_inc/module_menu.php';
}

/* Inclusione Pagina */if (isset($_GET['action'])) {include $pagine[$_GET['action']];} else {
    $_SESSION['POST_BACK_PAGE'] = $_SERVER['REQUEST_URI'];
    include "mod_home.php";}
