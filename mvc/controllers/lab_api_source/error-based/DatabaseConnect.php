<?php
require_once GRAPHSQLI_LAB_ROOT . 'config/config.php';

function DB_Connect()
{
    global $_Config;
    //MySQLi for low, medium and hard level lab
    $GLOBALS["__vuln_mysqli"] = mysqli_init();
    $GLOBALS["__vuln_mysqli"]->options(MYSQLI_OPT_READ_TIMEOUT, 3); //This will prevent time base SQLi by limit time response for read data from database
    if (!@($GLOBALS["__vuln_mysqli"]->real_connect($_Config['db_server'], $_Config['db_user'], $_Config['db_password'], "", $_Config['db_port']))
        || !@((bool)$GLOBALS["__vuln_mysqli"]->query("USE " . $_Config['db_database']))) {
        die("Unable to connect to the database");
    }
    // MySQL PDO Prepared Statements for impossible level lab
    try {
        $GLOBALS["__sec_pdo"] = new PDO('mysql:host=' . $_Config['db_server'] . ';dbname=' . $_Config['db_database'] . ';port=' . $_Config['db_port'] . ';charset=utf8', $_Config['db_user'], $_Config['db_password']);
        $GLOBALS["__sec_pdo"]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $GLOBALS["__sec_pdo"]->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0); //Not allow executed multi query
    } catch (PDOException $exception) {
        die($exception->getMessage() . 'at $GLOBALS["__sec_pdo"]');
    }
}