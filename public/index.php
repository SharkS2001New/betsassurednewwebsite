<?php
require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/constants.php';
include_once __DIR__ . "/../components/shared/jackpotRoutes.php";

use App\Facades\Router; 

// Initialize Routing class
$router = new Router();

// Define routes
$router->get('/', function() {
    include __DIR__ . '/../pages/homepage.php'; 
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

$router->get('/about-us', function() {
    include __DIR__ . '/../pages/about.php'; 
});

$router->get('/privacy-policy', function() {
    include __DIR__ . '/../pages/privacy-policy.php'; 
});

$router->get('/terms-and-conditions', function() {
    include __DIR__ . '/../pages/terms-and-conditions.php'; 
});


// Handle the incoming request
$router->handleRequest();
