<?php
// This file has is one level up from public folder which has the index.php file for the routing
define('BASE_PATH', dirname(__DIR__));

// Load .env so ACCESS_TOKEN / APP_URL are available to pitch API helpers.
$envFile = BASE_PATH . '/.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = array_map('trim', explode('=', $line, 2));
        $value = trim($value, " \t\"'");
        if ($key !== '' && getenv($key) === false) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
        }
    }
}

if (file_exists(BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php')) {
    include_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
}

