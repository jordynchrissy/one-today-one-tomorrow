
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/includes/db.php";

date_default_timezone_set("America/Grenada");

$isLoggedIn = isset($_SESSION["user_id"]);

$todayMood = "";

// Generate a security token
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// Retrieve today's saved mood
if ($isLoggedIn) {

    $stmt = $pdo->prepare(
        "SELECT mood FROM mood_logs
         WHERE user_id = :user_id
         AND logged_date = :logged_date"
    );

    $stmt->execute([
        ":user_id" => $_SESSION["user_id"],
        ":logged_date" => date("Y-m-d")
    ]);

    $todayMood = $stmt->fetchColumn() ?: "";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>One Today, One Tomorrow | Home</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php require __DIR__ . "/includes/header.php"; ?>

<main>
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="edition">
                <span>THE SUNSHINE ISSUE</span>
                <span>✿ NO. 001</span>
            </div>

            <p class="eyebrow">A SOFT PLACE TO LAND, ALWAYS.</p>

            <h1>
                One <span>Today,</span><br>
                <em>One Tomorrow.</em>
            </h1>

            <p class="hero-description">
                For the messy days, the magical days, and everything in between.
                A little space to feel, reflect, and bloom at your own pace.
            </p>

            <div class="hero-actions">
                <a href="#mood-checkin" class="hero-button">
                    Begin your day <span aria-hidden="true">↗</span>
                </a>
                <span class="handwritten">take your time ♡</span>
            </div>

            <div class="hero-foot">
                <span>✳ YOUR STORY, YOUR PACE</span>
                <span>SCROLL TO EXPLORE ↓</span>
            </div>
        </div>

        <div class="hero-visual"
             aria-label="Illustration of a warm sunset over turquoise water"
             role="img">

            <div class="sunset">
                <div class="sun"></div>
                <div class="water"></div>

                <div class="sunset-caption">
                    somewhere between today<br>
                    and tomorrow...
                </div>

                <span class="flower" aria-hidden="true">✿</span>
            </div>

            <div class="sticker" aria-hidden="true">
                <span>ONE DAY</span>
                <strong>AT A</strong>
                <span>TIME ✿</span>
            </div>

            <div class="postcard" aria-hidden="true">
                <small>POSTCARD NO. 001</small>
                <span>Wish you were here... ♡</span>
            </div>
        </div>
    </section>

    <section class="daily-message">
        <p class="section-label">✿ A LITTLE REMINDER</p>
        <h2>“Sit by the shore for a while.”</h2>
        <p>
            You don't have to figure everything out today.
            Take your time. You're allowed to just be.
        </p>
    </section>

    <section class="mood-section" id="mood-checkin">
        
            <div
                class="mood-card"
                data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>"
                data-today-mood="<?= htmlspecialchars($todayMood, ENT_QUOTES, 'UTF-8') ?>"
                data-csrf-token="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="mood-top">
                <span>YOUR DAILY POSTCARD</span>
                <span>✳ CHECK-IN NO. 01</span>
            </div>

            <p class="section-label">TODAY'S CHECK-IN</p>
            <h2>How is your heart today?</h2>
            <p>Whatever you're feeling, there's room for it here.</p>

            <div class="mood-options" role="group" aria-label="Choose today's mood">
                <button class="mood-btn" data-mood="Happy" aria-pressed="false">
                    ☀️<span>Happy</span>
                </button>
                <button class="mood-btn" data-mood="Good" aria-pressed="false">
                    🌷<span>Good</span>
                </button>
                <button class="mood-btn" data-mood="Okay" aria-pressed="false">
                    ☁️<span>Okay</span>
                </button>
                <button class="mood-btn" data-mood="Sad" aria-pressed="false">
                    🌧️<span>Sad</span>
                </button>
                <button class="mood-btn" data-mood="Tired" aria-pressed="false">
                    🌙<span>Tired</span>
                </button>
            </div>

            <p id="mood-response" aria-live="polite">
                Choose the mood that feels right for you.
            </p>

            <a href="journal.php" class="journal-link">
                Write about your day ↗
            </a>
        </div>
    </section>
</main>

<?php require __DIR__ . "/includes/footer.php"; ?>

<script src="js/script.js"></script>
</body>
</html>
