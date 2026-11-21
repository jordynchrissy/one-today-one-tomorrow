
<?php
// OTOT Database Connection

$host = "localhost";
$dbname = "otot_db";
$username = "root";
$password = ""; // Default local XAMPP setup

try {
    // Create a secure PDO database connection
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $e) {

    // Log technical details without exposing them to visitors
    error_log("OTOT database error: " . $e->getMessage());

    http_response_code(500);
    exit("Sorry! OTOT couldn't connect to the database.");
}
?>
