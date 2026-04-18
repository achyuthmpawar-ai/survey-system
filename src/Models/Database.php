<?php
namespace App\Models;

class Database {
    private static ?\PDO $pdo = null;

    public static function get(): \PDO {
        if (self::$pdo === null) {
            // MySQL Configuration for XAMPP
            $host = '127.0.0.1';
            $dbname = 'slim_survey';
            $username = 'root';
            $password = ''; // Default XAMPP password is empty
            
            try {
                self::$pdo = new \PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                    ]
                );
                self::migrate();
            } catch (\PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    private static function migrate(): void {
        $db = self::$pdo;
        
        // Create tables
        $db->exec('CREATE TABLE IF NOT EXISTS surveys (
            id INT AUTO_INCREMENT PRIMARY KEY,
            topic VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            csv_filename VARCHAR(255),
            active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $db->exec('CREATE TABLE IF NOT EXISTS questions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            survey_id INT NOT NULL,
            question TEXT NOT NULL,
            correct_answer TEXT NOT NULL,
            wrong_options TEXT NOT NULL,
            FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $db->exec('CREATE TABLE IF NOT EXISTS responses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            survey_id INT NOT NULL,
            answers TEXT NOT NULL,
            score INT NOT NULL,
            total INT NOT NULL,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (survey_id) REFERENCES surveys(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $db->exec('CREATE TABLE IF NOT EXISTS admins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        // Seed default admin if not exists
        $count = $db->query('SELECT COUNT(*) FROM admins')->fetchColumn();
        if ($count == 0) {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $db->prepare('INSERT INTO admins (username, password) VALUES (?, ?)')->execute(['admin', $hash]);
        }
    }
}
