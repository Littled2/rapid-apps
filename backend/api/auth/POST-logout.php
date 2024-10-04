<?php

require_once __DIR__ . "/../../database/demand-db.php";
require_once __DIR__ . "/../../helpers/helpers.php";

http_method_must_be("POST");

must_be_authenticated();

safely_start_session();

// Unset all session variables
$_SESSION = array();

// If you want to destroy the session cookie as well (for a complete logout)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

send_response(200, "Logout OK");

?>