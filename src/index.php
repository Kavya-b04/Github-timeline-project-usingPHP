<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && empty($_POST['verify_code'])) {
        $email = trim($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email address.";
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['verify_code'] = generateVerificationCode();
            if (sendVerificationEmail($email, $_SESSION['verify_code'])) {
                $message = "✅ Code sent to <strong>$email</strong>.";
            } else {
                $message = "❌ Failed to send email.";
            }
        }
    } elseif (isset($_POST['verify_code'])) {
        $code = trim($_POST['verify_code']);
        if ($code === ($_SESSION['verify_code'] ?? '')) {
            if (registerEmail($_SESSION['email'])) {
                $message = "🎉 Registered: <strong>{$_SESSION['email']}</strong>";
                unset($_SESSION['verify_code']);
            } else {
                $message = "⚠️ Registration failed.";
            }
        } else {
            $message = "❌ Incorrect code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h2>Register for GitHub Updates</h2>
    <form method="post">
        <?php if (!isset($_SESSION['verify_code'])): ?>
            <input type="email" name="email" placeholder="Enter email" required><br>
            <input type="submit" value="Send Code">
        <?php else: ?>
            <input type="text" name="verify_code" placeholder="Enter code" required><br>
            <input type="submit" value="Verify">
        <?php endif; ?>
    </form>
    <p><?= $message ?></p>
</body>
</html>
