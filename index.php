<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION["user_id"]);
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

<header class="site-header">
    <div class="masthead">
        <span>✳ A LITTLE SPACE TO JUST BE</span>
        <span>VOL. 01 — EST. 2026</span>
    </div>

    <nav class="navbar" aria-label="Main navigation">
        <a class="logo" href="index.php" aria-label="OTOT home">
            otot<span>✿</span>
        </a>

        
<!-- Animated Heart Navigation -->
<div class="heart-nav">

    <button
        class="heart-toggle"
        id="heartToggle"
        type="button"
        aria-label="Open navigation menu"
        aria-expanded="false"
        aria-controls="heartMenu">
        ♥
    </button>

    <ul class="nav-links heart-menu" id="heartMenu">
        <li><a class="active" aria-current="page" href="index.php">Home</a></li>
        <li><a href="journal.php">Journal</a></li>
        <li><a href="insights.php">Insights</a></li>
        <li><a href="quiet-corner.php">Quiet Corner</a></li>
        <li><a href="settings.php">My Space</a></li>
        
        <?php if ($isLoggedIn): ?>

            <a href="logout.php">Log Out ♡</a>

        <?php else: ?>

            <a href="login.php">Log In ♡</a>
            <a href="register.php">Sign Up ✿</a>

        <?php endif; ?>

            </ul>

</div>


        <a class="nav-cta" href="journal.php">Dear Diary ↗</a>
    </nav>
</header>

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
        <div class="mood-card">
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


<!-- ========================================
     OTOT SCRAPBOOK FOOTER
======================================== -->

<footer class="site-footer">

    <div class="footer-inner">

        <!-- Little scrapbook note -->
        <div class="footer-note">

            <div class="footer-note-top">
                <span>✿ A LITTLE NOTE BEFORE YOU GO</span>
                <span>✳</span>
            </div>

            <h2>Tomorrow is <em>another page.</em></h2>

            <p>
                Whatever today looked like, there's still room
                for something beautiful tomorrow. ♡
            </p>

            <span class="footer-signature">
                with love, otot ♡
            </span>

        </div>

        <!-- Brand and navigation -->
        <div class="footer-main">

            <div class="footer-brand">
                <a href="index.php" aria-label="OTOT homepage">
                    otot<span>✿</span>
                </a>
                <p>one today, one tomorrow.</p>
            </div>

            <nav class="footer-links" aria-label="Footer navigation">
                <a href="index.php">Home</a>
                <a href="journal.php">Journal</a>
                <a href="insights.php">Insights</a>
                <a href="quiet-corner.php">Quiet Corner</a>
                <a href="settings.php">My Space</a>
            </nav>

        </div>

        <!-- Bottom strip -->
        <div class="footer-bottom">
            <span>© 2026 OTOT · Made with care ♡</span>
            <span>✿ grow gently ✿</span>
        </div>

    </div>

</footer>


<script src="js/script.js"></script>
</body>
</html>
