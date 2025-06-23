<?php
require_once __DIR__ . '/functions.php';
sendGitHubUpdatesToSubscribers();
echo "[" . date('Y-m-d H:i:s') . "] CRON executed.\n";
