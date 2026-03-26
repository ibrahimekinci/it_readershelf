<?php

require_once __DIR__ . '/Logger.php';

// config
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'it_readershelf');

function getDatabaseConnection(): mysqli
{

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        // connect
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // charset utf8
        $mysqli->set_charset("utf8mb4");

        return $mysqli;
    }
    catch (mysqli_sql_exception $e) {
        // dev log
        Logger::error("Database Connection Failed", $e);

        // err msg
        die("A database connection error occurred. Please try again later.");
    }
}

$db = getDatabaseConnection();