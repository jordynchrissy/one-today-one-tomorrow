
<?php
// ========================================
// OTOT — SAVE DAILY MOOD
// ========================================

session_start();

header("Content-Type: application/json");

// Use Grenada's local date
date_default_timezone_set("America/Grenada");

// Check whether the user is logged in
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please log in to save your mood."
    ]);

    exit;
}

require_once __DIR__ . "/includes/db.php";

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);

    exit;
}

// Validate the security token
$token = $_POST["csrf_token"] ?? "";

if (
    empty($_SESSION["csrf_token"]) ||
    !is_string($token) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Invalid security token."
    ]);

    exit;
}

// Retrieve the selected mood
$mood = $_POST["mood"] ?? "";

$allowedMoods = [
    "happy",
    "good",
    "okay",
    "sad",
    "tired"
];

// Validate the selected mood
if (!in_array($mood, $allowedMoods, true)) {
    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Please select a valid mood."
    ]);

    exit;
}

// Get today's date
$today = date("Y-m-d");

try {

    // Insert today's mood or update an existing mood
    $stmt = $pdo->prepare(
        "INSERT INTO mood_logs
         (user_id, mood, logged_date)
         VALUES (:user_id, :mood, :logged_date)
         ON DUPLICATE KEY UPDATE mood = :updated_mood"
    );

    $stmt->execute([
        ":user_id" => $_SESSION["user_id"],
        ":mood" => $mood,
        ":logged_date" => $today,
        ":updated_mood" => $mood
    ]);

    echo json_encode([
        "success" => true,
        "mood" => $mood,
        "message" => "Your little check-in has been saved! ♡"
    ]);

} catch (PDOException $e) {

    error_log("Mood save error: " . $e->getMessage());

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Couldn't save your mood. Please try again."
    ]);
}
