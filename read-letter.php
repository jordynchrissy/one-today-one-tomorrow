
<?php

require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/includes/db.php";

date_default_timezone_set("America/Grenada");

// Retrieve and validate the letter ID
$letterId = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$letterId || $letterId < 1) {
    http_response_code(404);
    exit("Letter not found.");
}

// Only retrieve letters belonging to the user
// that have reached their unlock date
$stmt = $pdo->prepare("
    SELECT title, content, unlock_date, created_at
    FROM future_letters
    WHERE letter_id = :letter_id
    AND user_id = :user_id
    AND unlock_date <= :today
");

$stmt->execute([
    "letter_id" => $letterId,
    "user_id" => $_SESSION["user_id"],
    "today" => date("Y-m-d")
]);

$letter = $stmt->fetch();

if (!$letter) {
    http_response_code(404);
    exit("This letter is unavailable or still sealed.");
}

header("Cache-Control: no-store");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>A Special Delivery | OTOT</title>

    <link rel="stylesheet" href="css/style.css?v=15">
</head>

<body>

    <?php require __DIR__ . "/includes/header.php"; ?>

    <main class="opened-letter-page">

        <a href="quiet-corner.php#letters"
            class="back-to-mailbox">
            ← Back to your little mailbox
        </a>

        <article class="opened-letter">

            <div class="opened-letter-tape"
                aria-hidden="true"></div>

            <p class="scrapbook-label">
                ✳ YOU'VE GOT MAIL!
            </p>

            <p class="opened-letter-date">
                Written on
                <?= date("F j, Y", strtotime($letter["created_at"])) ?>
            </p>

            <h1>
                <?= htmlspecialchars($letter["title"]) ?>
            </h1>

            <div class="opened-letter-divider">
                ─── ♡ ───
            </div>

            <div class="opened-letter-content"><?= htmlspecialchars($letter["content"]) ?></div>

            <div class="opened-letter-signature">
                With love,<br>
                <em>Your yesterday self. ♡</em>
            </div>

            <div class="delivery-stamp">
                DELIVERED<br>
                <?= date("M d, Y", strtotime($letter["unlock_date"])) ?>
            </div>

        </article>

        <p class="opened-letter-note">
            A little reminder of who you once were.
        </p>

    </main>

    <script src="js/script.js"></script>

</body>
</html>

