
<?php
session_start();

require_once __DIR__ . "/includes/db.php";

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    // Validate username
    if (strlen($username) < 3 || strlen($username) > 50) {
        $errors[] = "Username must be between 3 and 50 characters.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Validate password
    if (strlen($password) < 8) {
        $errors[] = "Password must contain at least 8 characters.";
    }

    // Check matching passwords
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Register if validation passed
    if (empty($errors)) {

        try {
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $pdo->prepare(
                "INSERT INTO users (username, email, password_hash)
                 VALUES (:username, :email, :password_hash)"
            );

            $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password_hash" => $hashedPassword
            ]);

            $success = "Your OTOT account was created successfully! ♡";

        } catch (PDOException $e) {

            // MySQL duplicate username/email error
            if ($e->getCode() === "23000") {
                $errors[] = "That username or email is already registered.";
            } else {
                error_log("Registration error: " . $e->getMessage());
                $errors[] = "Something went wrong. Please try again.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account | OTOT</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

    <main class="auth-layout">

        <!-- Decorative welcome panel -->
        <section class="auth-welcome">

            <a href="index.php" class="auth-logo">
                otot<span>✿</span>
            </a>

            <div class="auth-welcome-content">

                <p class="auth-eyebrow">
                    ✳ YOUR LITTLE CORNER OF THE INTERNET
                </p>

                <h1>
                    A fresh page,<br>
                    <em>just for you.</em>
                </h1>

                <p>
                    Every little beginning deserves a little sunshine.
                    Create your space to reflect, feel, and grow
                    one day at a time.
                </p>

                <div class="auth-sticker">
                    <span>GROW</span>
                    <strong>GENTLY</strong>
                    <span>✿</span>
                </div>

            </div>

            <p class="auth-bottom-note">
                ONE TODAY, ONE TOMORROW ♡
            </p>

        </section>

        <!-- Registration form -->
        <section class="auth-form-section">

            <div class="auth-card">

                <p class="auth-label">
                    ✿ POSTCARD NO. 002 — NEW BEGINNINGS
                </p>

                <h2>Let's get you settled.</h2>

                <p class="auth-description">
                    A little space to call your own starts here.
                </p>

                <!-- Error messages -->
                <?php if (!empty($errors)): ?>
                    <div class="auth-alert auth-error" role="alert">
                        <?php foreach ($errors as $error): ?>
                            <p>
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Success message -->
                <?php if (!empty($success)): ?>
                    <div class="auth-alert auth-success" role="status">
                        <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                        <p><a href="login.php">Continue to login →</a></p>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST" class="auth-form">

                    <div class="form-group">
                        <label for="username">Your name ✿</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="What should we call you?"
                            minlength="3"
                            maxlength="50"
                            autocomplete="username"
                            required
                            value="<?= htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email address ♡</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            autocomplete="email"
                            required
                            value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Create a password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="At least 8 characters"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm password</label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="One more time!"
                            minlength="8"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="auth-submit">
                        Create my little space <span aria-hidden="true">↗</span>
                    </button>

                </form>

                <p class="auth-switch">
                    Already have an account?
                    <a href="login.php">Log in here ♡</a>
                </p>

                <p class="auth-footer-note">
                    ✳ ONE DAY AT A TIME, ALWAYS.
                </p>

            </div>

        </section>

    </main>

</body>
</html>
