<?php
require_once __DIR__ . "/includes/auth.php";

require_once __DIR__ . "/includes/db.php";

// Get the current user's ID
$userId = $_SESSION["user_id"];

// Count journal entries
$stmt = $pdo->prepare(
    "SELECT COUNT(*) FROM journal_entries
     WHERE user_id = :user_id"
);

$stmt->execute(["user_id" => $userId]);

$totalEntries = $stmt->fetchColumn();

// Count daily mood check-ins
$stmt = $pdo->prepare(
    "SELECT COUNT(*) FROM mood_logs
     WHERE user_id = :user_id"
);

$stmt->execute(["user_id" => $userId]);

$totalCheckins = $stmt->fetchColumn();


// Retrieve mood distribution
$stmt = $pdo->prepare(
    "SELECT mood, COUNT(*) AS total
     FROM mood_logs
     WHERE user_id = :user_id
     GROUP BY mood"
);

$stmt->execute(["user_id" => $userId]);

$moodResults = $stmt->fetchAll();

// Initialise all moods
$moodCounts = [
    "happy" => 0,
    "good" => 0,
    "okay" => 0,
    "sad" => 0,
    "tired" => 0
];

// Store the database results
foreach ($moodResults as $row) {
    $moodCounts[$row["mood"]] = (int) $row["total"];
}

// Calculate percentages
$moodPercentages = [];

foreach ($moodCounts as $mood => $count) {
    $moodPercentages[$mood] = $totalCheckins > 0
        ? round(($count / $totalCheckins) * 100)
        : 0;
}


// Use Grenada's local timezone
date_default_timezone_set("America/Grenada");

// Retrieve moods from the last seven days
$today = date("Y-m-d");
$startDate = date("Y-m-d", strtotime("-6 days"));

$stmt = $pdo->prepare(
    "SELECT mood, logged_date
     FROM mood_logs
     WHERE user_id = :user_id
     AND logged_date BETWEEN :start_date AND :end_date
     ORDER BY logged_date ASC"
);

$stmt->execute([
    "user_id" => $userId,
    "start_date" => $startDate,
    "end_date" => $today
]);

$weeklyResults = $stmt->fetchAll();

// Organise moods by date
$moodsByDate = [];

foreach ($weeklyResults as $row) {
    $moodsByDate[$row["logged_date"]] = $row["mood"];
}

// Prepare the seven-day calendar
$weeklyMoods = [];

for ($i = 6; $i >= 0; $i--) {

    $date = date("Y-m-d", strtotime("-$i days"));

    $weeklyMoods[] = [
        "day" => date("D", strtotime($date)),
        "date" => date("j", strtotime($date)),
        "mood" => $moodsByDate[$date] ?? null
    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Insights | One Today, One Tomorrow</title>

<link rel="stylesheet" href="css/style.css?v=6">
</head>

<?php require __DIR__ . "/includes/header.php"; ?>


<body>

<main class="insights-page">

    <!-- INTRODUCTION -->
    <section class="insights-intro">
        <p class="insights-eyebrow">✳ YOUR LITTLE JOURNEY</p>

        <h1>Look how far <em>you've come.</em></h1>

        <p>A little reflection on your days, feelings,
            and everything in between.</p>
    </section>

    <!-- STATISTICS -->
    <section class="insights-stats">

        <article class="stat-card stat-pink">
            <span class="stat-icon">💌</span>
            <h2><?= (int) $totalEntries ?></h2>
            <h3>Little memories</h3>
            <p>Moments you've chosen to keep.</p>
        </article>

        <article class="stat-card stat-orange">
            <span class="stat-icon">☀️</span>
            <h2><?= (int) $totalCheckins ?></h2>
            <h3>Daily check-ins</h3>
            <p>Days you've made time for yourself.</p>
        </article>

        <article class="stat-card stat-teal">
            <span class="stat-icon">🌱</span>
            <h2><?= count($moodsByDate) ?>/7</h2>
            <h3>This week's check-ins</h3>
            <p>Little moments of reflection.</p>
        </article>

    </section>


<!-- WEEKLY MOOD CALENDAR -->

<section class="weekly-mood-card">

    <p class="insights-eyebrow">✳ SEVEN LITTLE DAYS</p>

    <h2>A week of <em>feelings.</em></h2>

    <p>One day at a time, one feeling at a time.</p>

    <?php
    $moodEmojis = [
        "happy" => "☀️",
        "good" => "🌷",
        "okay" => "☁️",
        "sad" => "🌧️",
        "tired" => "🌙"
    ];
    ?>

    <div class="weekly-mood-grid">

        <?php foreach ($weeklyMoods as $day): ?>

            <div class="weekly-day">

                <span class="weekly-day-name">
                    <?= htmlspecialchars($day["day"]) ?>
                </span>

                <div class="weekly-mood-icon mood-<?= htmlspecialchars($day["mood"] ?? "empty") ?>"
                     aria-label="<?= htmlspecialchars($day["mood"] ?? "No check-in") ?>">

                    <?= $moodEmojis[$day["mood"] ?? ""] ?? "—" ?>

                </div>

                <span class="weekly-day-date">
                    <?= htmlspecialchars($day["date"]) ?>
                </span>

            </div>

        <?php endforeach; ?>

    </div>

    <p class="weekly-note">
        It's okay to miss a day. Life happens. ♡
    </p>

</section>


<!-- EMOTIONAL PALETTE -->

<section class="emotional-palette">

    <p class="insights-eyebrow">✳ A LITTLE BIT OF EVERYTHING</p>

    <h2>Your emotional <em>palette.</em></h2>

    <p>A colourful collection of all your feelings.</p>

    <?php if ($totalCheckins == 0): ?>

        <div class="palette-empty">
            <span>🎨</span>
            <h3>Your canvas is waiting.</h3>
            <p>Your mood check-ins will gradually
                bring this little palette to life.</p>

            <a href="index.php">Log your first mood ♡</a>
        </div>

    <?php else: ?>

        <div class="palette-chart">

            <?php foreach ($moodPercentages as $mood => $percentage): ?>

                <div class="palette-row">

                    <div class="palette-label">
                        <?= $moodEmojis[$mood] ?? "" ?>
                        <?= htmlspecialchars(ucfirst($mood)) ?>
                    </div>

                    <div class="palette-track">

                        <div class="palette-fill palette-<?= htmlspecialchars($mood) ?>"
                             style="width: <?= (int) $percentage ?>%;">
                        </div>

                    </div>

                    <span class="palette-percentage">
                        <?= (int) $percentage ?>%
                    </span>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <p class="palette-note">
        There is room for every feeling here. ♡
    </p>

</section>


</main>

    <script src="js/script.js" defer></script>
</body>
</html>

