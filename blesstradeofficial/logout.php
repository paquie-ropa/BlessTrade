<?php
// BlessTrade Township Marketplace logout.php
// Clears session variables and returns visitor to the home portal

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session keys
$_SESSION = array();

// If session cookie is set, destroy it
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// Redirect back home
header("Location: index.php");
exit;
?>
