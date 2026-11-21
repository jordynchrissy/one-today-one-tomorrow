
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>One Today, One Tomorrow | Home</title>

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- HEADER AND NAVIGATION -->
    <header class="site-header">
        <nav class="navbar" aria-label="Main navigation">

            <a href="index.php" class="logo">
                otot<span>✳</span>
            </a>

            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="journal.php">Journal</a></li>
                <li><a href="insights.php">Insights</a></li>
                <li><a href="quiet-corner.php">Quiet Corner</a></li>
                <li><a href="settings.php">My Space</a></li>
            </ul>

        </nav>
    </header>

    <main>

        <!-- HERO / WELCOME SECTION -->
        <section class="hero" id="home">

            <div class="hero-content">

                <p class="eyebrow">
                    ✿ YOUR LITTLE CORNER OF THE INTERNET
                </p>

                <h1>
                    One Today,<br>
                    <em>One Tomorrow.</em>
                </h1>

                <p class="hero-description">
                    A gentle space to pause, reflect,
                    and take life one day at a time.
                </p>

                <a href="#mood-checkin" class="hero-button">
                    Begin your day ♡
                </a>

            </div>

            <div class="hero-art" aria-hidden="true">
                <div class="sun"></div>
                <div class="ocean"></div>
                <span class="hero-flower">✿</span>
            </div>

        </section>

        <!-- DAILY MESSAGE -->
        <section class="daily-message">

            <p class="section-label">A LITTLE REMINDER</p>

            <h2>
                "Sit by the shore for a while."
            </h2>

            <p>
                You don't have to figure everything out today.
                Take your time. You're allowed to just be.
            </p>

        </section>

        <!-- MOOD CHECK-IN SECTION -->
        <section class="mood-section" id="mood-checkin">

            <div class="mood-card">

                <p class="section-label">
                    ✳ TODAY'S CHECK-IN
                </p>

                <h2>How is your heart today?</h2>

                <p>
                    Whatever you're feeling, there's room for it here.
                </p>

                <div class="mood-options">

                    <button class="mood-btn" type="button"
                            data-mood="Happy" aria-pressed="false">
                        ☀️
                        <span>Happy</span>
                    </button>

                    <button class="mood-btn" type="button"
                            data-mood="Good" aria-pressed="false">
                        🌷
                        <span>Good</span>
                    </button>

                    <button class="mood-btn" type="button"
                            data-mood="Okay" aria-pressed="false">
                        ☁️
                        <span>Okay</span>
                    </button>

                    <button class="mood-btn" type="button"
                            data-mood="Sad" aria-pressed="false">
                        🌧️
                        <span>Sad</span>
                    </button>

                    <button class="mood-btn" type="button"
                            data-mood="Tired" aria-pressed="false">
                        🌙
                        <span>Tired</span>
                    </button>

                </div>

                <p id="mood-response" aria-live="polite">
                    Choose the mood that feels right for you.
                </p>

                <a href="journal.php" class="journal-link">
                    Write about your day →
                </a>

            </div>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="site-footer">
        <p>One Today, One Tomorrow ✿</p>
        <p>Made with care, for the days ahead. ♡</p>
    </footer>

    <!-- JavaScript -->
    <script src="js/script.js"></script>

</body>
</html>
