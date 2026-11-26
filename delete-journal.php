
<?php
// ========================================
// OTOT JOURNAL — DELETE ENTRIES
// ========================================

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/db.php";

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method not allowed.");
}

// Validate the security token
$token = $_POST["csrf_token"] ?? "";

if (
    empty($_SESSION["csrf_token"]) ||
    !is_string($token) ||
    !hash_equals($_SESSION["csrf_token"], $token)
) {
    http_response_code(403);
    exit("Invalid form request.");
}

// Get the journal entry ID
$entryId = filter_input(
    INPUT_POST,
    "entry_id",
    FILTER_VALIDATE_INT
);

if (!$entryId || $entryId < 1) {
    header("Location: journal.php", true, 303);
    exit;
}

try {

    // Delete only entries belonging to the logged-in user
    $stmt = $pdo->prepare(
        "DELETE FROM journal_entries
         WHERE entry_id = :entry_id
         AND user_id = :user_id"
    );

    $stmt->execute([
        ":entry_id" => $entryId,
        ":user_id" => $_SESSION["user_id"]
    ]);

    if ($stmt->rowCount() > 0) {
        $_SESSION["journal_success"] =
            "Your little memory has been removed. ♡";
    } else {
        http_response_code(404);
        exit("This little memory could not be found.");
    }

    // Redirect to prevent repeated submissions
    header("Location: journal.php#write-entry", true, 303);
    exit;

} catch (PDOException $e) {

    error_log("Journal delete error: " . $e->getMessage());

    http_response_code(500);
    exit("Couldn't delete your entry. Please try again.");
}

