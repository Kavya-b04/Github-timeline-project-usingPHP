# Github-timeline-project-usingPHP
his PHP-based application sends the latest GitHub Timeline updates to subscribed users via email every 5 minutes using a CRON job.

## 📌 Features

### 🔐 Email Registration & Verification
- Users enter their email via `index.php`.
- A 6-digit code is sent to verify.
- On successful verification, email is stored in `registered_emails.txt`.

### ❌ Unsubscribe Functionality
- Emails contain an unsubscribe link.
- User confirms unsubscription using a 6-digit code via `unsubscribe.php`.

### 🔄 Automated GitHub Timeline Fetch
- CRON job runs every 5 minutes (via `cron.php`).
- Fetches from [GitHub Timeline](https://github.com/timeline) *(simulated)*.
- Formats into HTML and emails all registered users.

## 🗂️ Project Structure
src/
├── index.php # UI to register email
├── unsubscribe.php # UI to unsubscribe
├── functions.php # All helper logic (mail, fetch, format)
├── cron.php # Script to send GitHub updates via mail
├── setup_cron.sh # Adds CRON job automatically
├── registered_emails.txt # List of registered verified emails
## 🛠️ Setup Instructions

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/Kavya-b04/Github-timeline-project-usingPHP.git
cd Github-timeline-project-usingPHP
cd src
php -S localhost:8000
Visit: http://localhost:8000/index.php
bash src/setup_cron.sh
php src/cron.php
```

##📋** Requirements**
PHP 7+

CRON (Linux or WSL)

Mail configured (for testing, use sendmail or mail logging)
