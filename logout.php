<?php

session_start();
require_once __DIR__ . '/includes/Logger.php';

if (isset($_SESSION['user_id'])) {
    Logger::info("User signed out. User ID: " . $_SESSION['user_id']);
}

// unset
$_SESSION = array();

// kill cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// kill session
session_destroy();

// home
header("Location: index.php");
exit;