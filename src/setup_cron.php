#!/bin/bash

CRON_FILE_PATH="$(pwd)/cron.php"
PHP_PATH=$(which php)
CRON_JOB="*/5 * * * * $PHP_PATH $CRON_FILE_PATH"

(crontab -l 2>/dev/null | grep -v "$CRON_FILE_PATH" ; echo "$CRON_JOB") | crontab -

echo "✅ CRON job installed!"
