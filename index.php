<?php
const GRAPHSQLI_LAB_ROOT = '';
ini_set('session.cookie_httponly', 1); //set http only flag for cookie
//Not show error
ini_set('error_reporting', 0);
ini_set('display_errors', 0);
session_start();
//Check config.php exists
if (file_exists(GRAPHSQLI_LAB_ROOT . 'config/config.php') === false) {
    $error_msg = "Config file not found. Copy config/config..php.example to config/config.php and configure to your environment";
    require_once(GRAPHSQLI_LAB_ROOT . "mvc/views/page-error-500.php");
    die();
} else {
    require_once(GRAPHSQLI_LAB_ROOT . "mvc/Bridge.php");
    $myApp = new App();
}
?>