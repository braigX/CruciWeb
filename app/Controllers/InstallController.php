<?php

class InstallController {
    public function showForm() {
        require_once ROOT . 'app/Views/install.php';
    }

    public function submitForm() {
        $dbHost = $_POST['db_host'] ?? null;
        $dbName = $_POST['db_name'] ?? null;
        $dbUser = $_POST['db_user'] ?? null;
        $dbPass = $_POST['db_pass'] ?? '';

        $errors = [];

        // Validate database connection
        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $e) {
            $errors[] = "Database connection failed: " . $e->getMessage();
        }

        // Check if /storage/logs is writable
        $logDir = ROOT . 'storage/logs';
        if (!is_writable($logDir)) {
            $errors[] = "The /storage/logs directory is not writable. Please check permissions.";
        } else {
            // Test creating a log file
            $testLogFile = $logDir . '/test.log';
            if (file_put_contents($testLogFile, "Installation test log.") === false) {
                $errors[] = "Failed to write to /storage/logs directory.";
            } else {
                unlink($testLogFile); // Remove the test log file
            }
        }

        // Output results
        if (empty($errors)) {
            // Save database credentials to the config file
            $configContent = <<<CONFIG
<?php
define('DSN', 'mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4');
define('DB_USER', '$dbUser');
define('DB_PASS', '$dbPass');
?>
CONFIG;

            if (file_put_contents(ROOT . 'config/config.php', $configContent) === false) {
                $errors[] = "Failed to write database configuration to config/config.php.";
            }
        }

        if (empty($errors)) {
            echo "Installation completed successfully. You can now use the application.";
        } else {
            require_once ROOT . 'app/Views/install.php';
        }
    }
}
