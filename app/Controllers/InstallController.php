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
        $tasksCompleted = [];

        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass);
            $tasksCompleted[] = "Database connection established.";
        } catch (PDOException $e) {
            $errors[] = "Database connection failed: " . $e->getMessage();
        }

        $logDir = ROOT . 'storage/logs';
        if (!is_writable($logDir)) {
            $errors[] = "The /storage/logs directory is not writable. Please check permissions.";
        } else {

            $testLogFile = $logDir . '/test.log';
            if (file_put_contents($testLogFile, "Installation test log.") === false) {
                $errors[] = "Failed to write to /storage/logs directory.";
            } else {
                unlink($testLogFile); 
                $tasksCompleted[] = "/storage/logs is writable.";
            }
        }

        if (empty($errors)) {

            $configContent = <<<CONFIG
    <?php
    define('DSN', 'mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4');
    define('DB_USER', '$dbUser');
    define('DB_PASS', '$dbPass');
    ?>
    CONFIG;

            if (file_put_contents(ROOT . 'config/config.php', $configContent) !== false) {
                $tasksCompleted[] = "Database configuration saved.";
            } else {
                $errors[] = "Failed to write database configuration to config/config.php.";
            }

            try {
                $this->createTablesAndSeedData($pdo);
                $tasksCompleted[] = "Tables created and data seeded successfully.";
            } catch (Exception $e) {
                $errors[] = "Error creating tables or seeding data, please drop all data in database and upload the .SQL file in /storage/cruciweb.sql.";
            }
        }

        require_once ROOT . 'app/Views/install-results.php';
    }

    public function submitForm2() {
        $dbHost = $_POST['db_host'] ?? null;
        $dbName = $_POST['db_name'] ?? null;
        $dbUser = $_POST['db_user'] ?? null;
        $dbPass = $_POST['db_pass'] ?? '';

        $errors = [];

        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
            $pdo = new PDO($dsn, $dbUser, $dbPass);
        } catch (PDOException $e) {
            $errors[] = "Database connection failed: " . $e->getMessage();
        }

        $logDir = ROOT . 'storage/logs';
        if (!is_writable($logDir)) {
            $errors[] = "The /storage/logs directory is not writable. Please check permissions.";
        } else {

            $testLogFile = $logDir . '/test.log';
            if (file_put_contents($testLogFile, "Installation test log.") === false) {
                $errors[] = "Failed to write to /storage/logs directory.";
            } else {
                unlink($testLogFile); 
            }
        }

        if (empty($errors)) {

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

            $this->createTablesAndSeedData($pdo);

            echo "Installation completed successfully. You can now use the application.";
        } else {
            require_once ROOT . 'app/Views/install.php';
        }
    }

    private function createTablesAndSeedData(PDO $pdo) {
        try {

            $pdo->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) DEFAULT NULL,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('anonymous', 'registered', 'admin') NOT NULL DEFAULT 'registered',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS games (
                id INT AUTO_INCREMENT PRIMARY KEY,
                creator_id INT NOT NULL,
                name VARCHAR(100) NOT NULL,
                dimensions VARCHAR(20) NOT NULL,
                difficulty ENUM('beginner', 'intermediate', 'expert') NOT NULL,
                words JSON NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (creator_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS hints (
                id INT AUTO_INCREMENT PRIMARY KEY,
                game_id INT NOT NULL,
                hints JSON NOT NULL,
                FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS user_attempts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT DEFAULT NULL,
                game_id INT NOT NULL,
                progress JSON NOT NULL,
                completed TINYINT(1) DEFAULT 0,
                started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                finished_at TIMESTAMP NULL DEFAULT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            $pdo->exec("INSERT INTO users (id, name, username, password, role) VALUES
                (11, 'Hikari Inoue', 'hikariinoue@gmail.com', '" . password_hash('Hikari@Inoue292', PASSWORD_DEFAULT) . "', 'admin'),
                (13, 'Ren Okamoto', 'renokamoto@hotmail.fr', '" . password_hash('renokamoto@32442', PASSWORD_DEFAULT) . "', 'registered')");

            $pdo->exec("INSERT INTO games (id, creator_id, name, dimensions, difficulty, words, created_at) VALUES
                (18, 13, 'Colors Game', '6x6', 'intermediate', '[[\"\",\"S\",\"K\",\"Y\",\"\",\"R\"],[\"\",\"O\",\"\",\"B\",\"L\",\"U\"],[\"\",\"G\",\"R\",\"E\",\"E\",\"N\"],[\"\",\"O\",\"\",\"\",\"R\",\"\"],[\"\",\"O\",\"\",\"\",\"O\",\"\"],[\"\",\"D\",\"\",\"\",\"I\",\"\"]]', '2025-01-01 19:51:12'),
                (19, 13, 'Food Example', '8x8', 'intermediate', '[[\"T\",\"A\",\"R\",\"T\",\"E\",\"\",\"\",\"\"],[\"\",\"M\",\"I\",\"E\",\"L\",\"\",\"V\",\"\"],[\"\",\"\",\"Z\",\"\",\"\",\"P\",\"I\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"O\",\"N\",\"P\"],[\"B\",\"E\",\"U\",\"R\",\"R\",\"M\",\"\",\"A\"],[\"\",\"\",\"\",\"\",\"\",\"M\",\"\",\"I\"],[\"\",\"G\",\"L\",\"A\",\"C\",\"E\",\"\",\"N\"],[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"]]', '2025-01-01 19:58:42'),
                (20, 13, 'Celebrities Game', '13x13', 'expert', '[[\"Z\",\"I\",\"D\",\"N\",\"E\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"L\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"M\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"A\",\"\",\"C\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"C\",\"O\",\"T\",\"I\",\"L\",\"L\",\"A\",\"R\",\"D\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"E\",\"\",\"S\",\"\",\"\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"H\",\"\",\"T\",\"\",\"H\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"P\",\"A\",\"R\",\"A\",\"D\",\"I\",\"S\",\"\"],[\"\",\"\",\"\",\"\",\"M\",\"A\",\"R\",\"C\",\"E\",\"A\",\"U\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"A\",\"D\",\"J\",\"A\",\"N\",\"I\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"M\",\"A\",\"U\",\"R\",\"E\",\"S\",\"M\",\"O\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"L\",\"\",\"\",\"\",\"\"],[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\"]]', '2025-01-01 20:04:41')");

            $pdo->exec("INSERT INTO hints (id, game_id, hints) VALUES
                (12, 18, '{\"row\":{\"1\":\"sky\",\"2\":\"blu\",\"3\":\"green\"},\"col\":{\"B\":\"so good\",\"E\":\"le roi\",\"F\":\"run\"}}'),
                (13, 19, '{\"row\":{\"1\":\"Tarte\",\"2\":\"Miel\",\"5\":\"Beurre\",\"7\":\"Glace\"},\"col\":{\"C\":\"Riz\",\"F\":\"Pomme\",\"G\":\"Vin\",\"H\":\"Pain\"}}'),
                (14, 20, '{\"row\":{\"1\":\"Zidane\",\"5\":\"Cotillard\",\"8\":\"Paradis\",\"9\":\"Marceau\",\"10\":\"Adjani\",\"11\":\"Mauresmo\"},\"col\":{\"A\":\"Elmaleh\",\"G\":\"Casta\",\"I\":\"Haenel\"}}')");

            $pdo->exec("INSERT INTO user_attempts (id, user_id, game_id, progress, completed, started_at, finished_at) VALUES
                (8, 13, 20, '[{\"row\":0,\"col\":0,\"value\":\"+\"},{\"row\":0,\"col\":1,\"value\":\"+\"},{\"row\":0,\"col\":2,\"value\":\"+\"},{\"row\":0,\"col\":3,\"value\":\"+\"},{\"row\":0,\"col\":4,\"value\":\"+\"},{\"row\":1,\"col\":4,\"value\":\"S\"},{\"row\":2,\"col\":4,\"value\":\"D\"},{\"row\":3,\"col\":4,\"value\":\"S\"},{\"row\":3,\"col\":6,\"value\":\"+\"},{\"row\":4,\"col\":0,\"value\":\"+\"},{\"row\":4,\"col\":1,\"value\":\"+\"},{\"row\":4,\"col\":2,\"value\":\"+\"},{\"row\":4,\"col\":3,\"value\":\"+\"},{\"row\":4,\"col\":4,\"value\":\"+\"},{\"row\":4,\"col\":5,\"value\":\"+\"},{\"row\":4,\"col\":6,\"value\":\"A\"},{\"row\":4,\"col\":7,\"value\":\"+\"},{\"row\":4,\"col\":8,\"value\":\"+\"},{\"row\":5,\"col\":4,\"value\":\"+\"},{\"row\":5,\"col\":6,\"value\":\"A\"},{\"row\":6,\"col\":4,\"value\":\"+\"},{\"row\":6,\"col\":6,\"value\":\"+\"},{\"row\":6,\"col\":8,\"value\":\"+\"},{\"row\":7,\"col\":5,\"value\":\"+\"},{\"row\":7,\"col\":6,\"value\":\"A\"},{\"row\":7,\"col\":7,\"value\":\"+\"},{\"row\":7,\"col\":8,\"value\":\"A\"},{\"row\":7,\"col\":9,\"value\":\"+\"},{\"row\":7,\"col\":10,\"value\":\"+\"},{\"row\":7,\"col\":11,\"value\":\"+\"},{\"row\":8,\"col\":4,\"value\":\"+\"},{\"row\":8,\"col\":5,\"value\":\"+\"},{\"row\":8,\"col\":6,\"value\":\"+\"},{\"row\":8,\"col\":7,\"value\":\"+\"},{\"row\":8,\"col\":8,\"value\":\"+\"},{\"row\":8,\"col\":9,\"value\":\"+\"},{\"row\":8,\"col\":10,\"value\":\"+\"},{\"row\":9,\"col\":4,\"value\":\"+\"},{\"row\":9,\"col\":5,\"value\":\"+\"},{\"row\":9,\"col\":6,\"value\":\"+\"},{\"row\":9,\"col\":7,\"value\":\"+\"},{\"row\":9,\"col\":8,\"value\":\"+\"},{\"row\":9,\"col\":9,\"value\":\"+\"},{\"row\":10,\"col\":4,\"value\":\"+\"},{\"row\":10,\"col\":5,\"value\":\"+\"},{\"row\":10,\"col\":6,\"value\":\"+\"},{\"row\":10,\"col\":7,\"value\":\"+\"},{\"row\":10,\"col\":8,\"value\":\"+\"},{\"row\":10,\"col\":9,\"value\":\"+\"},{\"row\":10,\"col\":10,\"value\":\"+\"},{\"row\":10,\"col\":11,\"value\":\"+\"},{\"row\":11,\"col\":8,\"value\":\"A\"}]', 0, '2025-01-01 20:14:03', NULL)");

            echo "Tables created and initial data seeded successfully.";
        } catch (PDOException $e) {
            throw($e);
        }
    }

}