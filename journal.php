
<?php
// ========================================
// OTOT JOURNAL — CREATE ENTRIES
// ========================================

// Make sure the user is logged in
require_once __DIR__ . "/includes/auth.php";

// Connect to the database
require_once __DIR__ . "/includes/db.php";

$errors = [];
$success = "";

$title = "";
$content = "";
$mood = "";

// Create a CSRF token for the journal form
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Handle journal form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (
        !is_string($token) ||
        !hash_equals($_SESSION["csrf_token"], $token)
    ) {
        http_response_code(403);
        exit("Invalid form request. Please refresh the page.");
    }

    // Get the submitted information
    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $mood = trim($_POST["mood"] ?? "");

    // Allowed moods
    $allowedMoods = [
        "happy",
        "good",
        "okay",
        "sad",
        "tired"
    ];

    // Validate the journal entry
    if ($title === "" || $content === "") {
        $errors[] = "Please enter a title and journal entry.";
    }

    if (mb_strlen($title) > 150) {
        $errors[] = "Your title must be 150 characters or fewer.";
    }

    if ($mood !== "" && !in_array($mood, $allowedMoods, true)) {
        $errors[] = "Please select a valid mood.";
    }

    // Save the journal entry
    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare(
                "INSERT INTO journal_entries
                 (user_id, title, content, mood)
                 VALUES
                 (:user_id, :title, :content, :mood)"
            );

            $stmt->execute([
                ":user_id" => $_SESSION["user_id"],
                ":title" => $title,
                ":content" => $content,
                ":mood" => $mood !== "" ? $mood : null
            ]);

            $success = "Your journal entry has been saved! ♡";

            // Clear the form after saving
            $title = "";
            $content = "";
            $mood = "";

        } catch (PDOException $e) {

            error_log("Journal save error: " . $e->getMessage());

            $errors[] = "Couldn't save your entry. Please try again.";
        }
    }
}
?>


<!-- ========================================
     OTOT JOURNAL WRITING FORM
======================================== -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Journal | OTOT</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<section class="journal-writer" id="write-entry">

    <div class="journal-writer-heading">

        <p class="journal-eyebrow">
            ✳ A LITTLE MOMENT TO YOURSELF
        </p>

        <h2>Dear <em>today...</em></h2>

        <p>
            Whatever's on your mind, there's room for it here.
            No perfect words needed. ♡
        </p>

    </div>

    <!-- Success message -->
    <?php if (!empty($success)): ?>
        <div class="journal-message journal-success" role="status">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <!-- Error messages -->
    <?php if (!empty($errors)): ?>
        <div class="journal-message journal-error" role="alert">

            <?php foreach ($errors as $error): ?>
                <p>
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>

    <!-- Journal entry form -->
    <form action="journal.php#write-entry"
          method="POST"
          class="journal-entry-form">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION['csrf_token'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <!-- Entry title -->
        <div class="journal-field">

            <label for="journal-title">
                Give today a title ✿
            </label>

            <input
                type="text"
                id="journal-title"
                name="title"
                placeholder="A little chapter of my life..."
                maxlength="150"
                required
                value="<?= htmlspecialchars(
                    $title,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

        </div>

        <!-- Mood selection -->
        <fieldset class="journal-mood-field">

            <legend>How are we feeling today? ♡</legend>

            <div class="journal-mood-options">

                <?php
                $moods = [
                    "happy" => "☀️ Happy",
                    "good" => "🌷 Good",
                    "okay" => "☁️ Okay",
                    "sad" => "🌧️ Sad",
                    "tired" => "🌙 Tired"
                ];
                ?>

                <?php foreach ($moods as $value => $label): ?>

                    <label class="journal-mood-option">

                        <input
                            type="radio"
                            name="mood"
                            value="<?= $value ?>"
                            <?= $mood === $value ? 'checked' : '' ?>
                        >

                        <span><?= $label ?></span>

                    </label>

                <?php endforeach; ?>

            </div>

        </fieldset>

        <!-- Journal content -->
        <div class="journal-field">

            <label for="journal-content">
                What's on your mind?
            </label>

            <textarea
                id="journal-content"
                name="content"
                rows="9"
                placeholder="Dear today, I have so much to tell you..."
                required
            ><?= htmlspecialchars(
                $content,
                ENT_QUOTES,
                'UTF-8'
            ) ?></textarea>

        </div>

        <div class="journal-form-footer">

            <p>✳ YOUR WORDS, YOUR SPACE, YOUR PACE.</p>

            <button type="submit" class="journal-save-btn">
                Save this little moment ♡
            </button>

        </div>

    </form>

</section>


