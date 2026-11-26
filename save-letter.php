
<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/db.php";

date_default_timezone_set("America/Grenada");

// Only accept POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: quiet-corner.php");
    exit;
}

// Verify CSRF token
$token = $_POST["csrf_token"] ?? "";

if (
    !is_string($token) ||
    empty($_SESSION["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Invalid request. Please try again.");
}

// Retrieve form values
$userId = $_SESSION["user_id"];

$title = trim((string) ($_POST["title"] ?? ""));
$content = trim((string) ($_POST["content"] ?? ""));
$unlockDate = (string) ($_POST["unlock_date"] ?? "");

$tomorrow = date("Y-m-d", strtotime("+1 day"));

// Validate title and content
if (
    $title === "" ||
    $content === "" ||
    mb_strlen($title) > 150 ||
    mb_strlen($content) > 10000
) {
    exit("Please enter a valid title and letter.");
}

// Validate unlock date
$date = DateTime::createFromFormat("!Y-m-d", $unlockDate);

if (
    !$date ||
    $date->format("Y-m-d") !== $unlockDate ||
    $unlockDate < $tomorrow
) {
    exit("Please choose tomorrow or a future date.");
}

// Save the letter
try {

    $stmt = $pdo->prepare("
        INSERT INTO future_letters
            (user_id, title, content, unlock_date)
        VALUES
            (:user_id, :title, :content, :unlock_date)
    ");

    $stmt->execute([
        "user_id" => $userId,
        "title" => $title,
        "content" => $content,
        "unlock_date" => $unlockDate
    ]);

    $_SESSION["letter_success"] =
        "Your little letter has been sealed with love! ♡";

    header("Location: quiet-corner.php#letters", true, 303);
    exit;

} catch (PDOException $e) {

    error_log("Letter saving error: " . $e->getMessage());

    http_response_code(500);
    exit("Your letter couldn't be saved. Please try again.");

}
