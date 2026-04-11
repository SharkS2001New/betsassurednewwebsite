<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
include_once __DIR__ . "/../components/shared/jackpotRoutes.php";

use App\Facades\Router; 

$requestUri = $_SERVER['REQUEST_URI'];

// If request starts with /api, stop PHP routing
if (strpos($requestUri, '/api') === 0) {
    return false; // Let server / ingress handle it
}

// Initialize Routing class
$router = new Router();

// Define routes
$router->get('/', function() {
    include __DIR__ . '/../pages/homepage.php'; 
});

$router->get('/free-football-betting-tips', function() {
    include __DIR__ . '/../pages/accumulator-tips.php'; 
});

$router->get('/todays-predictions', function() {
    include __DIR__ . '/../pages/todays-predictions.php'; 
});

$router->get('/tomorrows-predictions', function() { 
    include __DIR__ . '/../pages/tomorrows-predictions.php'; 
});

$router->get('/yesterdays-predictions', function() {
    include __DIR__ . '/../pages/yesterdays-predictions.php'; 
});

$router->get('/all-predictions', function() {
    include __DIR__ . '/../pages/all-predictions.php'; 
}); 

$router->get('/both-teams-to-score', function() {
    include __DIR__ . '/../pages/both-teams-to-score.php'; 
}); 

$router->get('/home-win-tips', function() {
    include __DIR__ . '/../pages/home-win-tips.php'; 
});

$router->get('/away-win-tips', function() {
    include __DIR__ . '/../pages/away-win-tips.php';  
});

$router->get('/over-under-15-goals', function() {
    include __DIR__ . '/../pages/over-under-15-goals.php'; 
});

$router->get('/over-under-25-goals', function() {
    include __DIR__ . '/../pages/over-under-25-goals.php'; 
});

$router->get('/safe-draws', function() {
    include __DIR__ . '/../pages/safe-draws.php'; 
});

$router->get('/double-chance', function() {
    include __DIR__ . '/../pages/double-chance.php'; 
});

$router->get('/betnumbers-tips', function() {
    include __DIR__ . '/../pages/betnumbers-predictions.php'; 
});

$router->get('/cheerplex-tips', function() {
    include __DIR__ . '/../pages/cheerplex-tips.php'; 
});

$router->get('/direct-win-predictions', function() {
    include __DIR__ . '/../pages/direct-win-predictions.php'; 
});

$router->get('/must-win-teams-today', function() {
    include __DIR__ . '/../pages/must-win-teams-today.php'; 
});

$router->get('/mwanasoka-tips', function() {
    include __DIR__ . '/../pages/mwanasoka-tips.php'; 
});

$router->get('/sokafans-tips', function() {
    include __DIR__ . '/../pages/sokafans-tips.php'; 
});

$router->get('/sunpel-tips', function() {
    include __DIR__ . '/../pages/sunpel-tips.php'; 
});

$router->get('/sure-win-prediction-today', function() {
    include __DIR__ . '/../pages/sure-win-prediction-today.php'; 
});

$router->get('/jackpot-predictions', function() {
    include __DIR__ . '/../pages/jackpot-predictions.php'; 
});

$router->get('/sportpesa-mega-jackpot-predictions', function() {
    include __DIR__ . '/../pages/jackpots/sportpesa-mega-jackpot-predictions.php'; 
});

$router->get('/sportpesa-midweek-jackpot-predictions', function() {
    include __DIR__ . '/../pages/jackpots/sportpesa-midweek-jackpot-predictions.php'; 
});

$router->get('/betika-midweek-jackpot-predictions', function() {
    include __DIR__ . '/../pages/jackpots/betika-midweek-jackpot-predictions.php'; 
});

// Include `other-jackpot-predictions.php` for the listed jackpot routes
$jackpotRoutes =  getJackpotRoutes();

// Iterate over each route and define it to use `other-jackpot-predictions.php`
foreach ($jackpotRoutes as $route) {
    $router->get("/$route", function() {
        include __DIR__ . '/../pages/jackpots/other-jackpot-predictions.php';
    });
}

$router->get('/contact-us', function() {
    include __DIR__ . '/../pages/contact.php'; 
});

$router->get('/partners', function() {
    include __DIR__ . '/../pages/partners.php'; 
});

$router->get('/about-us', function() {
    include __DIR__ . '/../pages/about.php'; 
});

$router->get('/our-privacy-policy', function() {
    include __DIR__ . '/../pages/privacy-policy.php'; 
});

$router->get('/our-terms-and-conditions', function() {
    include __DIR__ . '/../pages/terms-and-conditions.php'; 
});


// Handle the incoming request
$router->handleRequest();
