<?php
// This file has is one level up from public folder which has the index.php file for the routing
define('BASE_PATH', dirname(__DIR__));

if (file_exists(BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php')) {
    include_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
}

