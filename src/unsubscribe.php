<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && empty($_POST['verify_code'])) {
        $email = trim($_POST['email']);
        $_SESSION['unsubscribe_email'] = $email;
        $_SESSION['unsubscribe_code'] = generateVerificationCode();
        if (sendVerificationEmail($email, $_SESSION['unsubscribe_code'])) {
            $message = "Code sent to $email";
        } else {
            $message = "Failed to send code.";
        }
    } elseif (isset($_POST['verify_code'])) {
        $code = trim($_POST['verify_code']);
        if ($code === ($_SESSION['unsubscribe_code'] ?? '')) {
            if (unsubscribeEmail($_SESSION['unsubscribe_email'])) {
                $message = "Unsubscribed successfully.";
                unset($_SESSION['unsubscribe_code']);
            } else {
                $message = "Unsubscribe failed.";
            }
        } else {
            $message = "Incorrect code.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Unsubscribe</title></head>
<body>
    <h2>Unsubscribe</h2>
    <form method="post">
        <?php if (!isset($_SESSION['unsubscribe_code'])): ?>
            <input type="email" name="email" placeholder="Enter email" required><br>
            <input type="submit" value="Send Code">
        <?php else: ?>
            <input type="text" name="verify_code" placeholder="Enter code" required><br>
            <input type="submit" value="Unsubscribe">
        <?php endif; ?>
    </form>
    <p><?= $message ?></p>
</body>
</html>
