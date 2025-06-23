<?php

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    return strval(rand(100000, 999999));
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($email, $subject, $message, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
    $file = __DIR__ . '/../registered_emails.txt';
    $email = trim($email);

    // Avoid duplicates
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    if (!in_array($email, $emails)) {
        return file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX) !== false;
    }

    return true; // already registered
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/../registered_emails.txt';
    $email = trim($email);

    if (!file_exists($file)) return false;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $filtered = array_filter($emails, fn($e) => trim($e) !== $email);

    return file_put_contents($file, implode(PHP_EOL, $filtered) . PHP_EOL) !== false;
}

/**
 * Fetch GitHub timeline.
 */
function fetchGitHubTimeline() {
    $url = "https://api.github.com/events"; // GitHub public timeline

    $options = [
        "http" => [
            "header" => "User-Agent: PHP\r\n"
        ]
    ];
    $context = stream_context_create($options);

    $response = @file_get_contents($url, false, $context);

    if ($response === false) return null;

    return json_decode($response, true);
}

/**
 * Format GitHub timeline data. Returns a valid HTML string.
 */
function formatGitHubData(array $data): string {
    $html = "<h2>GitHub Public Events</h2><ul>";

    foreach (array_slice($data, 0, 5) as $event) {
        $type = htmlspecialchars($event['type'] ?? 'Unknown Event');
        $repo = htmlspecialchars($event['repo']['name'] ?? 'Unknown Repo');
        $html .= "<li><strong>$type</strong> on <em>$repo</em></li>";
    }

    $html .= "</ul>";
    return $html;
}

/**
 * Send the formatted GitHub updates to registered emails.
 */
function sendGitHubUpdatesToSubscribers(): void {
    $file = __DIR__ . '/../registered_emails.txt';

    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (empty($emails)) return;

    $data = fetchGitHubTimeline();
    if (!$data) return;

    $htmlContent = formatGitHubData($data);

    foreach ($emails as $email) {
        $unsubscribeLink = "http://yourdomain.com/unsubscribe.php?email=" . urlencode($email);
        $message = $htmlContent . "<p><a href=\"$unsubscribeLink\">Unsubscribe</a></p>";

        $headers = "From: no-reply@example.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        mail($email, "GitHub Timeline Update", $message, $headers);
    }
}
