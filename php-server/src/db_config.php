<?php
// Load database credentials from environment variables
define('DB_HOST', getenv('DB_HOST') ?: 'db'); // Fallback to 'db' if DB_HOST is not set
define('DB_USER', getenv('MYSQL_USER') ?: 'app_user'); // Use 'app_user' as a default
define('DB_PASS', getenv('MYSQL_PASSWORD') ?: 'secure_user_password'); // Use 'secure_user_password' as a default
define('DB_NAME', getenv('MYSQL_DATABASE') ?: 'my_app_db'); // Use 'my_app_db' as a default

function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection error: " . $e->getMessage());
        die("Connection failed: Please try again later.");
    }
}
?>
