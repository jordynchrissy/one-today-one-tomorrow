
<?php
// ========================================
// OTOT JOURNAL — EDIT ENTRIES
// ========================================

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/db.php";

$errors = [];

// Get the journal entry ID
$entryId = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$entryId || $entryId < 1) {
    header("Location: journal.php");
    exit;
}

// Retrieve the entry belonging to this user
$stmt = $pdo->prepare(
    "SELECT * FROM journal_entries
     WHERE entry_id = :entry_id
     AND user_id = :user_id"
);

$stmt->execute([
    ":entry_id" => $entryId,
    ":user_id" => $_SESSION["user_id"]
]);

$entry = $stmt->fetch();

// Prevent access to other users' entries
if (!$entry) {
    http_response_code(404);
    exit("This little memory could not be found.");
}

// Load the existing journal information
$title = $entry["title"];
$content = $entry["content"];
$mood = $entry["mood"] ?? "";

// Create a CSRF token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Handle the updated journal entry
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        http_response_code(403);
        exit("Invalid form request.");
    }

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $mood = trim($_POST["mood"] ?? "");

    $allowedMoods = [
        "happy",
        "good",
        "okay",
        "sad",
        "tired"
    ];

    // Validate the updated information
    if ($title === "" || $content === "") {
        $errors[] = "Please complete your journal entry.";
    }

    if (mb_strlen($title) > 150) {
        $errors[] = "Your title is too long.";
    }

    if ($mood !== "" &&
        !in_array($mood, $allowedMoods, true)) {
        $errors[] = "Please select a valid mood.";
    }

    // Update the existing entry
    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare(
                "UPDATE journal_entries
                 SET title = :title,
                     content = :content,
                     mood = :mood
                 WHERE entry_id = :entry_id
                 AND user_id = :user_id"
            );

            $stmt->execute([
                ":title" => $title,
                ":content" => $content,
                ":mood" => $mood !== "" ? $mood : null,
                ":entry_id" => $entryId,
                ":user_id" => $_SESSION["user_id"]
            ]);

            $_SESSION["journal_success"] =
                "Your little memory has been updated! ♡";

            header(
                "Location: journal.php#write-entry",
                true,
                303
            );

            exit;

        } catch (PDOException $e) {

            error_log("Journal update error: " . $e->getMessage());

            $errors[] = "Couldn't update your entry.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit a Little Memory | OTOT</title>

    <link rel="stylesheet" href="css/style.css?v=2">
</head>

<body>

    <main>

        <section class="journal-writer">

            <div class="journal-writer-heading">

                <p class="journal-eyebrow">
                    ✳ A LITTLE REVISION
                </p>

                <h2>Revisit a little <em>moment.</em></h2>

                <p>
                    Sometimes we find better words for yesterday.
                    That's okay, too. ♡
                </p>

            </div>

            <!-- Error messages -->
            <?php if (!empty($errors)): ?>
                <div class="journal-message journal-error">

                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

            <!-- Edit journal form -->
            <form
                action="edit-journal.php?id=<?= (int) $entryId ?>"
                method="POST"
                class="journal-entry-form"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>"
                >

                <!-- Entry title -->
                <div class="journal-field">

                    <label for="title">
                        Give this moment a name
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        maxlength="150"
                        value="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>

                <!-- Mood selection -->
                <fieldset class="journal-mood-field">

                    <legend>How did you feel?</legend>

                    <div class="journal-mood-options">

                        <?php
                        $moodOptions = [
                            "happy" => "☀️ Happy",
                            "good" => "🌷 Good",
                            "okay" => "☁️ Okay",
                            "sad" => "🌧️ Sad",
                            "tired" => "🌙 Tired",
                            "" => "♡ Unlabelled"
                        ];
                        ?>

                        <?php foreach ($moodOptions as $value => $label): ?>

                            <label class="journal-mood-option">

                                <input
                                    type="radio"
                                    name="mood"
                                    value="<?= htmlspecialchars($value) ?>"
                                    <?= $mood === $value ? "checked" : "" ?>
                                >

                                <span><?= htmlspecialchars($label) ?></span>

                            </label>

                        <?php endforeach; ?>

                    </div>

                </fieldset>

                <!-- Journal content -->
                <div class="journal-field">

                    <label for="content">
                        What's on your mind?
                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="8"
                        required
                    ><?= htmlspecialchars($content, ENT_QUOTES, 'UTF-8') ?></textarea>

                </div>

                <!-- Form buttons -->
                <div class="journal-form-footer">

                    <a href="journal.php#saved-entries"
                       class="memory-edit-btn">
                        ← Back to my memories
                    </a>

                    <button
                        type="submit"
                        class="journal-save-btn"
                    >
                        Save my changes ♡
                    </button>

                </div>

            </form>

        </section>

    </main>

</body>

</html>


