
<?php
// ========================================
// OTOT AUTHENTICATION GUARD
// ========================================

// Start the session if it hasn't started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {

    // Redirect visitors to the login page
    header("Location: login.php");
    exit;
}
