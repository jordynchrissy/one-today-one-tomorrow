
<?php
// ========================================
// OTOT USER LOGIN
// ========================================

session_start();

require_once __DIR__ . "/includes/db.php";

$errors = [];
$email = "";

// Process the login form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validate the fields
    if (empty($email) || empty($password)) {
        $errors[] = "Please enter your email and password.";
    }

    // Check account credentials
    if (empty($errors)) {

        try {
            // Find the user by email
            $stmt = $pdo->prepare(
                "SELECT user_id, username, email, password_hash
                 FROM users
                 WHERE email = :email
                 LIMIT 1"
            );

            $stmt->execute([
                ":email" => $email
            ]);

            $user = $stmt->fetch();

            // Verify password securely
            if ($user && password_verify(
                $password,
                $user["password_hash"]
            )) {

                // Create a fresh session ID
                session_regenerate_id(true);

                // Store the logged-in user's information
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["username"] = $user["username"];

                // Redirect to the homepage
                header("Location: index.php");
                exit;

            } else {
                $errors[] = "Incorrect email or password.";
            }

        } catch (PDOException $e) {

            error_log("OTOT login error: " . $e->getMessage());

            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome Back | OTOT</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

    <main class="auth-layout">

        <!-- LEFT: Sunset welcome panel -->
        <section class="auth-welcome">

            <a href="index.php" class="auth-logo">
                otot<span>✿</span>
            </a>

            <div class="auth-welcome-content">

                <p class="auth-eyebrow">
                    ✳ YOUR LITTLE SPACE IS WAITING
                </p>

                <h1>
                    Oh, hello<br>
                    <em>again, sunshine.</em>
                </h1>

                <p>
                    Some days are sunshine, some days are rain.
                    Whatever today brings, there's always room
                    for you here.
                </p>

                <div class="auth-sticker">
                    <span>WELCOME</span>
                    <strong>BACK!</strong>
                    <span>✿</span>
                </div>

            </div>

            <p class="auth-bottom-note">
                ONE TODAY, ONE TOMORROW ♡
            </p>

        </section>

        <!-- RIGHT: Login form -->
        <section class="auth-form-section">

            <div class="auth-card">

                <p class="auth-label">
                    ✿ POSTCARD NO. 003 — WELCOME HOME
                </p>

                <h2>Your space missed you.</h2>

                <p class="auth-description">
                    Pick up right where you left off. ♡
                </p>

                <!-- Display login errors -->
                <?php if (!empty($errors)): ?>

                    <div class="auth-alert auth-error" role="alert">

                        <?php foreach ($errors as $error): ?>

                            <p>
                                <?= htmlspecialchars(
                                    $error,
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </p>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

                <!-- Login form -->
                <form
                    action="login.php"
                    method="POST"
                    class="auth-form"
                >

                    <div class="form-group">

                        <label for="email">
                            Email address ♡
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                            value="<?= htmlspecialchars(
                                $email,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >

                    </div>

                    <div class="form-group">

                        <label for="password">
                            Your password ✿
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                    </div>

                    <button type="submit" class="auth-submit">

                        Back to my little space

                        <span aria-hidden="true">↗</span>

                    </button>

                </form>

                <p class="auth-switch">
                    New around here?
                    <a href="register.php">
                        Create an account ♡
                    </a>
                </p>

                <p class="auth-footer-note">
                    ✳ EVERY DAY IS A NEW PAGE.
                </p>

            </div>

        </section>

    </main>

</body>
</html>
