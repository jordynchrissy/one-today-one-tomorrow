<?php
require_once __DIR__ . "/includes/auth.php";

require_once __DIR__ . "/includes/db.php";

date_default_timezone_set("America/Grenada");

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}


// Retrieve letter success message
$letterSuccess = $_SESSION["letter_success"] ?? "";

unset($_SESSION["letter_success"]);

// Get today's date
$today = date("Y-m-d");

// Retrieve the user's letters
$stmt = $pdo->prepare("
    SELECT letter_id, title, unlock_date, created_at
    FROM future_letters
    WHERE user_id = :user_id
    ORDER BY unlock_date ASC, letter_id DESC
");

$stmt->execute([
    "user_id" => $_SESSION["user_id"]
]);

$futureLetters = $stmt->fetchAll();


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiet Corner | One Today, One Tomorrow</title>

    <link rel="stylesheet" href="css/style.css?v=12">
</head>



<main class="quiet-desk">

    <!-- SCRAPBOOK INTRODUCTION -->

    <section class="desk-intro">

        <span class="desk-sparkle sparkle-left">✦</span>
        <span class="desk-sparkle sparkle-right">✧</span>

        <p class="desk-eyebrow">
            A LITTLE ESCAPE FROM THE WORLD
        </p>

        <h1>The quiet <em>corner.</em></h1>

        <p class="desk-subtitle">
            Make yourself at home. ♡
        </p>

        <div class="desk-decoration">
            ─── ♡ ───
        </div>

    </section>

    <!-- SCRAPBOOK WORKSPACE -->

    <div class="scrapbook-desk">

        <!-- Letters to Tomorrow -->


<section class="desk-letter" id="letters">


<?php if (!empty($letterSuccess)): ?>

    <div class="letter-success" role="status">
        <?= htmlspecialchars($letterSuccess) ?>
    </div>

<?php endif; ?>


    <div class="letter-tape" aria-hidden="true"></div>

    <p class="scrapbook-label">
        01 / SPECIAL DELIVERY
    </p>

    <h2>Letters to <em>tomorrow.</em></h2>

    <p class="letter-greeting">
        Dear future me...
    </p>

    <!-- Decorative envelope -->

    <div class="letter-envelope" aria-hidden="true">

        <div class="envelope-back"></div>
        <div class="envelope-pocket"></div>
        <div class="envelope-flap"></div>

        <span class="envelope-heart">♥</span>

    </div>

    <p class="letter-description">
        A little piece of today, waiting for you
        somewhere in tomorrow.
    </p>

    <button type="button" class="letter-open-btn"
    id="open-letter-btn"
    onclick="document.getElementById('letter-modal').showModal()">

    Write something for later ↗

</button>


<!-- LITTLE MAILBOX -->

<div class="little-mailbox">

    <p class="scrapbook-label">
        ✳ SPECIAL DELIVERIES
    </p>

    <h3>Your little mailbox.</h3>

    <p class="mailbox-description">
        Pieces of yesterday, waiting for tomorrow.
    </p>

    <?php if (empty($futureLetters)): ?>

        <div class="mailbox-empty">
            <span>📭</span>
            <p>Your mailbox is waiting for its first letter.</p>
        </div>

    <?php else: ?>

        <div class="mailbox-list">

            <?php foreach ($futureLetters as $letter): ?>

                <?php
                $isUnlocked = $letter["unlock_date"] <= $today;
                ?>

                <article class="mailbox-letter <?= $isUnlocked ? 'unlocked' : 'locked' ?>">

                    <div class="mini-envelope" aria-hidden="true">
                        <span>♥</span>
                    </div>

                    <div class="mailbox-letter-info">

                        <h4>
                            <?= htmlspecialchars($letter["title"]) ?>
                        </h4>

                        <p>
                            <?php if ($isUnlocked): ?>

                                Ready to read! ♡

                            <?php else: ?>

                                Opens on
                                <?= date("F j, Y", strtotime($letter["unlock_date"])) ?>

                            <?php endif; ?>
                        </p>

                    </div>

                    <?php if ($isUnlocked): ?>

                        <a class="read-letter-btn"
                            href="read-letter.php?id=<?= (int) $letter["letter_id"] ?>">
                            Open ↗
                        </a>

                    <?php else: ?>

                        <span class="letter-lock"
                            aria-label="Letter locked">
                            🔒
                        </span>

                    <?php endif; ?>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <p class="mailbox-note">
        Good things take a little time. ♡
    </p>

</div>


</section>


<!-- LETTER WRITING MODAL -->

<dialog class="letter-modal" id="letter-modal">

    <button type="button" class="letter-close-btn"
    id="close-letter-btn"
    aria-label="Close letter"
    onclick="document.getElementById('letter-modal').close()">

    &times;

</button>

    <p class="scrapbook-label">
        ✳ A SPECIAL DELIVERY
    </p>

    <h2>Dear future <em>me...</em></h2>

    <p class="letter-modal-intro">
        A little piece of today, sealed with love
        for tomorrow.
    </p>

    <form class="future-letter-form"
        id="future-letter-form"
        method="POST"
        action="save-letter.php">

        <input type="hidden" name="csrf_token"
    value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

        <label for="letter-title">
            Give your letter a title
        </label>

        <input type="text"
            id="letter-title"
            name="title"
            maxlength="150"
            placeholder="To the person I'm becoming..."
            required>

        <label for="letter-content">
            Your little letter
        </label>

        <textarea
            id="letter-content"
            name="content"
            rows="8"
            maxlength="10000"
            placeholder="Dear future me..."
            required></textarea>

        <label for="unlock-date">
            When should this letter find you?
        </label>

        <input type="date"
    id="unlock-date"
    name="unlock_date"
    min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
    required>

        <button type="submit"
            class="seal-letter-btn">
            Seal with love ♡
        </button>

        <p class="letter-modal-note">
            A little surprise for another day.
        </p>

    </form>

</dialog>



        
<!-- LITTLE JOYS BINGO -->

<section class="desk-bingo" id="bingo">

    <div class="bingo-tape" aria-hidden="true"></div>

    <p class="scrapbook-label">
        02 / THE BEAUTY IN THE ORDINARY
    </p>

    <div class="bingo-heading">

        <span class="bingo-sparkle" aria-hidden="true">✦</span>

        <p class="bingo-little-title">little joys</p>

        <h2 class="bingo-title">
            <span>B</span>
            <span>I</span>
            <span>N</span>
            <span>G</span>
            <span>O</span>
        </h2>

        <p class="bingo-edition">
            THE LITTLE THINGS EDITION
        </p>

    </div>

    <p class="bingo-description">
        Life isn't a checklist, but it can be a little game.
    </p>

    <?php
    $bingoTasks = [
        "Listen to a favourite song",
        "Watch the sunset",
        "Drink some water",
        "Doodle something",
        "Enjoy a favourite snack",
        "Notice something pretty",
        "Wear something colourful",
        "Read a few pages",
        "Take a little break",
        "Send a kind message",
        "Enjoy some fresh air",
        "Discover a new song",
        "FREE SPACE ♡",
        "Watch a comfort show",
        "Write a little note",
        "Take a cute photo",
        "Stretch gently",
        "Make something creative",
        "Enjoy a quiet moment",
        "Revisit a happy memory",
        "Notice the clouds",
        "Play a favourite game",
        "Learn something new",
        "Make a little playlist",
        "Do something just for fun"
    ];
    ?>

    <div class="bingo-grid">

        <?php foreach ($bingoTasks as $index => $task): ?>

            <button
                type="button"
                class="bingo-square <?= $index === 12 ? 'free-space completed' : '' ?>"
                data-square="<?= $index ?>"
                aria-pressed="<?= $index === 12 ? 'true' : 'false' ?>"
                <?= $index === 12 ? 'disabled' : '' ?>
            >

                <span class="bingo-stamp" aria-hidden="true">
                    <?= $index === 12 ? '♡' : '✓' ?>
                </span>

                <span class="bingo-task">
                    <?= htmlspecialchars($task) ?>
                </span>

            </button>

        <?php endforeach; ?>

    </div>

    <div class="bingo-progress-info">

        <span id="bingo-count">
            0 of 24 little joys
        </span>

        <span id="bingo-wins">
            0 BINGO!
        </span>

    </div>

    <div class="bingo-progress-track">
        <div class="bingo-progress-fill"
            id="bingo-progress-fill"></div>
    </div>

    <p class="bingo-celebration"
        id="bingo-celebration"
        role="status"
        hidden>
        ✦ BINGO! Here's to the little things! ✦
    </p>

    <button type="button"
        class="bingo-reset-btn"
        id="bingo-reset-btn">
        Start a fresh card ↻
    </button>

    <p class="bingo-note">
        No deadlines. No pressure. Just little moments of joy. ♡
    </p>

</section>


        <!-- Sound Sanctuary -->

        <!-- The Permission Slip -->

        <!-- Doodle Corner -->

        <!-- Breathing Animation -->

    </div>

    <p class="desk-signoff">
        Made for the beautifully ordinary days. ♡
    </p>

</main>

<script src="js/bingo.js?v=1"></script>
