# SurveyLite — Anonymous Survey System (Slim Framework 4)
Project Description:
A professional anonymous survey system built with **Slim Framework 4**, **PHP-DI**, and **MySQL**. Admins upload CSV files to generate web-based questionnaires with unique URLs. Participants answer anonymously without any login required.

## Features

- ✅ **CSV-to-Survey Generator** — Upload CSV files to instantly create questionnaires
- ✅ **Unique URLs** — Each survey gets its own shareable URL (`/survey/{slug}`)
- ✅ **Toggle Active/Inactive** — Enable or disable surveys from the admin dashboard
- ✅ **Anonymous Participation** — No login required for users taking surveys
- ✅ **Auto-Grading** — Automatic scoring with detailed answer review
- ✅ **Results Dashboard** — View all responses with expandable details
- ✅ **CSV Export** — Download survey results as CSV
- ✅ **Session-Based Authentication** — Secure admin dashboard

## Tech Stack

- **Slim Framework 4.12** — PSR-7 compliant micro-framework
- **Slim PSR-7** — PSR-7 implementation for HTTP messages
- **PHP-DI 7.0** — Dependency injection container
- **Slim Twig-View 3.3** — Template rendering (using plain PHP templates)
- **PHP 8.0+** with PDO and MySQL
- **MySQL** — Database via XAMPP (Windows) or MAMP (macOS)

## Project Structure

```
slim-survey/
├── public/
│   ├── index.php          # Application entry point with routes
│   └── .htaccess          # Apache URL rewriting
├── src/
│   ├── Controllers/
│   │   ├── HomeController.php    # Public survey pages
│   │   └── AdminController.php   # Admin dashboard & auth
│   ├── Middleware/
│   │   └── AuthMiddleware.php    # Admin authentication
│   └── Models/
│       ├── Database.php          # MySQL connection + migrations
│       └── CsvParser.php         # CSV parsing logic
├── templates/
│   ├── layout.php         # Shared CSS and HTML head
│   ├── home.php           # Public survey listing
│   ├── survey.php         # Questionnaire form
│   ├── result.php         # Score display after submission
│   ├── login.php          # Admin login
│   ├── dashboard.php      # Admin panel
│   └── results.php        # Results viewer with details
├── storage/               # CSV uploads stored here
├── vendor/                # Composer dependencies
├── sample.csv             # Example CSV for testing
├── composer.json
├── .gitignore
└── README.md
```

---

# Installation Guide

## Prerequisites

- **PHP 8.0 or higher**
- **Composer** (dependency manager)
- **XAMPP** (Windows) or **MAMP** (macOS)
- **PHP Extensions**: `pdo_mysql`, `mysqli`, `mbstring`, `session`

---

## Installation on Windows (XAMPP)

### **Step 1: Extract Project Files**

Extract the ZIP file to XAMPP's `htdocs` directory:
```
C:\xampp\htdocs\slim-survey
```

### **Step 2: Install Composer Dependencies**

Open **Command Prompt** or **PowerShell**:

```bash
cd C:\xampp\htdocs\slim-survey
composer install
```

This will download:
- slim/slim (Framework)
- slim/psr7 (HTTP messages)
- php-di/php-di (Dependency injection)
- slim/twig-view (Templates)

### **Step 3: Start XAMPP**

- Open **XAMPP Control Panel**
- Start **Apache**
- Start **MySQL**

### **Step 4: Create MySQL Database**

Open **phpMyAdmin**: http://localhost/phpmyadmin

**Click on "SQL" tab and run:**

```sql
CREATE DATABASE slim_survey CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**OR**

- Click "New" in left sidebar
- Database name: `slim_survey`
- Collation: `utf8mb4_unicode_ci`
- Click "Create"

### **Step 5: Verify Database Configuration**

Check `src/Models/Database.php` has these settings:

```php
$host = 'localhost';
$dbname = 'slim_survey';
$username = 'root';
$password = ''; // XAMPP default is empty
```

### **Step 6: Start PHP Development Server**

Open **Command Prompt**:

```bash
cd C:\xampp\htdocs\slim-survey\public
php -S localhost:8000
```

### **Step 7: Access the Application**

Open browser: **http://localhost:8000**

### **Step 8: Login to Admin Dashboard**

```
URL: http://localhost:8000/admin/login
Username: admin
Password: admin123
```

The database tables will be created automatically on first access.

---

## Installation on macOS (MAMP)

### **Step 1: Extract Project Files**

Extract the ZIP file to MAMP's `htdocs` directory:
```
/Applications/MAMP/htdocs/slim-survey
```

### **Step 2: Install Composer Dependencies**

Open **Terminal**:

```bash
cd /Applications/MAMP/htdocs/slim-survey
composer install
```

If you don't have Composer installed:

```bash
# Install Composer first
brew install composer

# Then install dependencies
composer install
```

### **Step 3: Start MAMP**

- Open **MAMP** application
- Click **Start Servers**
- Wait for Apache and MySQL to turn green

### **Step 4: Create MySQL Database**

Open **phpMyAdmin**: http://localhost:8888/phpMyAdmin
(or http://localhost/phpMyAdmin depending on your MAMP configuration)

**Click on "SQL" tab and run:**

```sql
CREATE DATABASE slim_survey CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**OR**

- Click "New" in left sidebar
- Database name: `slim_survey`
- Collation: `utf8mb4_unicode_ci`
- Click "Create"

### **Step 5: Update Database Configuration (If Needed)**

**Edit:** `src/Models/Database.php`

**Default MAMP settings:**

```php
$host = 'localhost';
$dbname = 'slim_survey';
$username = 'root';
$password = 'root'; // MAMP default password is 'root'
```

**If using MAMP with custom port (8889):**

```php
$host = 'localhost:8889'; // or just 'localhost' if default port
$dbname = 'slim_survey';
$username = 'root';
$password = 'root';
```

### **Step 6: Start PHP Development Server**

Open **Terminal**:

```bash
cd /Applications/MAMP/htdocs/slim-survey/public
php -S localhost:8000
```

**Alternative - Use MAMP's PHP:**

```bash
cd /Applications/MAMP/htdocs/slim-survey/public
/Applications/MAMP/bin/php/php8.2.0/bin/php -S localhost:8000
```
(Adjust PHP version number to match your MAMP installation)

### **Step 7: Access the Application**

Open browser: **http://localhost:8000**

### **Step 8: Login to Admin Dashboard**

```
URL: http://localhost:8000/admin/login
Username: admin
Password: admin123
```

The database tables will be created automatically on first access.

---

## Usage Guide

### **Admin Workflow**

1. **Login**: Navigate to `/admin/login`
   - Default credentials: `admin` / `admin123`

2. **Create Survey**: 
   - Upload a CSV file with format: `Question, CorrectAnswer, WrongOption1, WrongOption2, ...`
   - Enter a survey topic name
   - Click "Create Survey"

3. **Manage Surveys**:
   - View all surveys in the dashboard
   - Toggle surveys active/inactive
   - View response statistics

4. **View Results**:
   - Click "View Results" for any survey
   - See individual responses with scores
   - Expand details to see all answers
   - Download results as CSV

### **User Workflow**

1. Visit the home page to see available surveys
2. Click "Take Survey" on any active survey
3. Answer all questions (options are shuffled)
4. Submit and view your score immediately
5. Review correct/incorrect answers

### **CSV Format**

Your CSV file should follow this structure:

```csv
Question,CorrectAnswer,WrongOption1,WrongOption2,WrongOption3
CPU stands for Central Processing Unit.,TRUE,FALSE
RAM is non-volatile memory.,FALSE,TRUE
Which data structure follows LIFO?,Stack,Queue,Array,Linked List
HTML is used for structuring web pages.,TRUE,FALSE
```

**Rules:**
- First row can be a header (will be auto-skipped if detected)
- Minimum 3 columns: Question, Correct Answer, at least 1 wrong option
- You can have multiple wrong options (columns 3, 4, 5, ...)

---

## Routes

### **Public Routes**
- `GET /` — Home page with active surveys
- `GET /survey/{slug}` — View questionnaire
- `POST /survey/{slug}/submit` — Submit answers

### **Admin Routes (Protected)**
- `GET /admin/login` — Login form
- `POST /admin/login` — Process login
- `GET /admin/logout` — Logout
- `GET /admin/dashboard` — Admin panel (requires auth)
- `POST /admin/surveys/create` — Create new survey (requires auth)
- `POST /admin/surveys/{id}/toggle` — Enable/disable survey (requires auth)
- `GET /admin/surveys/{id}/results` — View results (requires auth)
- `GET /admin/surveys/{id}/download` — Download CSV (requires auth)

---

## Database Schema

### **Tables**

**surveys**
- `id` (INT PRIMARY KEY AUTO_INCREMENT)
- `topic` (VARCHAR 255) — Survey title
- `slug` (VARCHAR 255 UNIQUE) — URL-friendly identifier
- `csv_filename` (VARCHAR 255) — Original CSV filename
- `active` (TINYINT 1) — 1 = active, 0 = inactive
- `created_at` (TIMESTAMP)

**questions**
- `id` (INT PRIMARY KEY AUTO_INCREMENT)
- `survey_id` (INT FK)
- `question` (TEXT)
- `correct_answer` (TEXT)
- `wrong_options` (TEXT JSON array)

**responses**
- `id` (INT PRIMARY KEY AUTO_INCREMENT)
- `survey_id` (INT FK)
- `answers` (TEXT JSON) — All Q&A with correctness
- `score` (INT)
- `total` (INT)
- `submitted_at` (TIMESTAMP)

**admins**
- `id` (INT PRIMARY KEY AUTO_INCREMENT)
- `username` (VARCHAR 100 UNIQUE)
- `password` (VARCHAR 255 hashed)

---

## Security

- ✅ **Password Hashing** — Bcrypt for admin passwords
- ✅ **Session-Based Auth** — Server-side session management
- ✅ **Middleware Protection** — Admin routes require authentication
- ✅ **SQL Injection Prevention** — Prepared statements with PDO
- ✅ **XSS Prevention** — `htmlspecialchars()` on all output

---

## Troubleshooting

### **Windows - Issue: "Database connection failed"**

```bash
# Check XAMPP MySQL is running
# Open XAMPP Control Panel and start MySQL

# Verify database exists in phpMyAdmin
http://localhost/phpmyadmin
```

### **macOS - Issue: "Database connection failed"**

```bash
# Check MAMP MySQL is running
# Open MAMP and click "Start Servers"

# Update Database.php with MAMP password
# Change password from '' to 'root'
```

### **Issue: "Class not found" or "Composer errors"**

```bash
# Re-run composer
composer dump-autoload
```

### **Issue: File upload not working**

**Windows:**
```bash
# Check storage folder exists
mkdir storage
```

**macOS:**
```bash
# Create and set permissions
mkdir storage
chmod 777 storage
```

### **Issue: "session_start() already active" warning**

Check that `public/index.php` has session start at the top:

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```

### **Issue: Page shows "404 Not Found" on survey links**

You're using PHP built-in server, which should work fine. If issues persist:

```bash
# Make sure you're in the public folder
cd public
php -S localhost:8000

# NOT from the root folder
```

---

## PHP Configuration (Optional)

If file uploads fail, check your `php.ini`:

**Windows:** `C:\xampp\php\php.ini`  
**macOS:** `/Applications/MAMP/bin/php/php8.x.x/conf/php.ini`

```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Restart Apache after changes.

---

## Testing the System

### **1. Create First Survey:**
```
- Login to admin dashboard (admin/admin123)
- Click "Create New Survey"
- Topic: "Computer Science Basics"
- Upload the sample.csv file included in the project
- Click "Create Survey"
```

### **2. Take Survey as User:**
```
- Go to home page (http://localhost:8000)
- Click "Take Survey"
- Answer all questions
- Submit and see your score
```

### **3. View Results:**
```
- Login to admin dashboard
- Click "View Results" next to your survey
- Expand details to see individual answers
- Click "Download CSV" to export results
```

---

## Database Tables Verification

After first access, verify tables were created:

**Windows:** http://localhost/phpmyadmin  
**macOS:** http://localhost:8888/phpMyAdmin

Click on `slim_survey` database. You should see 4 tables:
- ✅ `admins`
- ✅ `questions`
- ✅ `responses`
- ✅ `surveys`

---

## Project Features Implemented

✅ **Step 0**: Selected Slim Framework 4  
✅ **Step 1**: CSV line-by-line reading (`CsvParser.php`)  
✅ **Step 2**: Separated questions, answers, and distractors  
✅ **Step 3**: HTML questionnaire population from CSV  
✅ **Step 3** (duplicate): Database creation and data storage  
✅ **Step 4**: Survey links generation based on database  
✅ **Step 5**: HTML questionnaire from database data  
✅ **Step 6**: Admin authentication system  
✅ **Step 7**: All functionalities tested  
✅ **Step 8**: Project ready for repository  
✅ **Step 9**: Complete installation guide provided  

---

## Default Admin Credentials

```
Username: admin
Password: admin123
```

**Important:** Change these credentials in production by manually updating the `admins` table in phpMyAdmin.

---

## License

MIT License

## Support

For issues or questions, please contact the project maintainer.

---

## Quick Start Summary

### **Windows (XAMPP):**
```bash
# 1. Extract to C:\xampp\htdocs\slim-survey
# 2. Install dependencies
cd C:\xampp\htdocs\slim-survey
composer install

# 3. Create database in phpMyAdmin
# Database name: slim_survey

# 4. Start server
cd public
php -S localhost:8000

# 5. Visit http://localhost:8000
```

### **macOS (MAMP):**
```bash
# 1. Extract to /Applications/MAMP/htdocs/slim-survey
# 2. Install dependencies
cd /Applications/MAMP/htdocs/slim-survey
composer install

# 3. Create database in phpMyAdmin
# Database name: slim_survey

# 4. Update password in src/Models/Database.php to 'root'

# 5. Start server
cd public
php -S localhost:8000

# 6. Visit http://localhost:8000
```

**That's it! Your survey system is ready to use.** 🎉
