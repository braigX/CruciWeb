<?php

// Function to parse the .env file
function loadEnv($file) {
    if (!file_exists($file)) {
        throw new Exception(".env file not found: $file");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip lines that are comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Parse key-value pairs
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove surrounding quotes if present
        $value = trim($value, '"');

        // Set as environment variable
        $_ENV[$key] = $value;
    }
}

// Load the .env file
loadEnv(__DIR__ . '/../.env');

// Define constants from .env values
define('DSN', $_ENV['DB_DSN']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
