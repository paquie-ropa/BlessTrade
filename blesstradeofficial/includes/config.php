<?php
// blesstrade.co.za database configuration and connection file

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('mysqli_connect')) {
    die("<div style='padding:20px; font-family:sans-serif; background:#fff1f2; border:1px solid #fecdd3; color:#9f1239; border-radius:8px;'>
            <strong>⚠️ PHP MySQLi Extension Missing!</strong><br>
            The MySQLi extension is not enabled in your PHP configuration.
         </div>");
}

// Database Credentials
define('DB_SERVER', 'sql311.infinityfree.com');
define('DB_USERNAME', 'if0_42022393');
define('DB_PASSWORD', 'ropapakaz2005');
define('DB_NAME', 'if0_42022393_blesstrade');

// Attempt connection to MySQL Database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$conn) {
    die("<div style='padding:20px; font-family:sans-serif; background:#fff1f2; border:1px solid #fecdd3; color:#9f1239; border-radius:8px;'>
            <strong>⚠️ Database Connection Failed!</strong><br>
            Error: " . mysqli_connect_error() . "
         </div>");
}

// Set charset
mysqli_set_charset($conn, 'utf8mb4');
?>