
<?php
// ========================================
// OTOT JOURNAL — CREATE ENTRIES
// ========================================

// Make sure the user is logged in
require_once __DIR__ . "/includes/auth.php";

// Connect to the database
require_once __DIR__ . "/includes/db.php";

$errors = [];

// Retrieve the temporary success message
$success = $_SESSION["journal_success"] ?? "";

// Remove it after retrieving it
unset($_SESSION["journal_success"]);

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

            // Store the success message temporarily
            $_SESSION["journal_success"] =
                "Your journal entry has been saved! ♡";

            // Redirect to prevent duplicate submissions
            header("Location: journal.php#write-entry", true, 303);
            exit;

        } catch (PDOException $e) {

            error_log("Journal save error: " . $e->getMessage());

            $errors[] = "Couldn't save your entry. Please try again.";
        }
    }
}


/* ========================================
   RETRIEVE SAVED JOURNAL ENTRIES
======================================== */

$entries = [];

try {

    $stmt = $pdo->prepare(
        "SELECT entry_id, title, content, mood,
                created_at, updated_at
         FROM journal_entries
         WHERE user_id = :user_id
         ORDER BY created_at DESC, entry_id DESC"
    );

    $stmt->execute([
        ":user_id" => $_SESSION["user_id"]
    ]);

    $entries = $stmt->fetchAll();

} catch (PDOException $e) {

    error_log("Journal retrieval error: " . $e->getMessage());

    $errors[] = "Couldn't load your journal entries.";

}


?>


<!-- ========================================
     OTOT JOURNAL WRITING FORM
======================================== -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Journal | OTOT</title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>

<?php require __DIR__ . "/includes/header.php"; ?>

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


<!-- ========================================
     OTOT JOURNAL ARCHIVE
======================================== -->

<section class="journal-archive" id="saved-entries">

    <div class="archive-heading">

        <p class="journal-eyebrow">
            ✳ THE LITTLE THINGS WE KEEP
        </p>

        <h2>Little pieces of <em>yesterday.</em></h2>

        <p>
            A collection of moments, feelings,
            and everything in between. ♡
        </p>

        <span class="archive-count">
            <?= count($entries) ?> little moments collected
        </span>

    </div>

    <?php if (empty($entries)): ?>

        <div class="archive-empty">

            <span>💌</span>

            <h3>A little quiet here, isn't it?</h3>

            <p>
                Your saved journal entries will appear here.
                Every story starts somewhere.
            </p>

            <a href="#write-entry">
                Write your first little moment ↗
            </a>

        </div>

    <?php else: ?>

        <div class="archive-grid">

            <?php
            $moodLabels = [
                "happy" => "☀️ Happy",
                "good" => "🌷 Good",
                "okay" => "☁️ Okay",
                "sad" => "🌧️ Sad",
                "tired" => "🌙 Tired"
            ];
            ?>

            <?php foreach ($entries as $entry): ?>

                <?php
                $entryMood = array_key_exists(
                    $entry["mood"] ?? "",
                    $moodLabels
                ) ? $entry["mood"] : "neutral";
                ?>

                <article class="memory-card mood-<?= $entryMood ?>">

                    <div class="memory-top">

                        <span class="memory-date">
                            <?= date(
                                "F j, Y",
                                strtotime($entry["created_at"])
                            ) ?>
                        </span>

                        <span class="memory-mood">
                            <?= $moodLabels[$entryMood] ?? "♡ Unlabelled" ?>
                        </span>

                    </div>

                    <h3>
                        <?= htmlspecialchars(
                            $entry["title"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>
                    </h3>

                    <details class="memory-details">

                        <summary>Open this little memory ↗</summary>

                        <p class="memory-content">
                            <?= nl2br(htmlspecialchars(
                                $entry["content"],
                                ENT_QUOTES,
                                "UTF-8"
                            )) ?>
                        </p>

                    </details>

                    
                    <div class="memory-footer">

                        <a
                            href="edit-journal.php?id=<?= (int) $entry['entry_id'] ?>"
                            class="memory-edit-btn"
                        >
                            ✎ Edit memory
                        </a>

                        <form
                            action="delete-journal.php"
                            method="POST"
                            class="memory-delete-form"
                            onsubmit="return confirm('Are you sure you want to delete this little memory? This cannot be undone.');"
                        >

                            <input
                                type="hidden"
                                name="entry_id"
                                value="<?= (int) $entry['entry_id'] ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
                            >

                            <button
                                type="submit"
                                class="memory-delete-btn"
                            >
                                ♡ Delete
                            </button>

                        </form>

                    </div>


                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <p class="archive-footer">
        ♡ EVERY LITTLE MOMENT MATTERS ♡
    </p>

<script src="js/script.js" defer></script>

</section>

