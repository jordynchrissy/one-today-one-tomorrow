<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION["user_id"]);
?>

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