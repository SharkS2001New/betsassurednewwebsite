<?php
$route = trim($_SERVER['REQUEST_URI'], '/');
$routeKey = $route === '' ? 'homepage' : $route;

$metaDataPath = __DIR__ . '../../seo-metas/meta-data.json';
$metaDataJson = file_get_contents($metaDataPath);
$metaData = json_decode($metaDataJson, true);

if (isset($metaData[$routeKey])) {
    $data = $metaData[$routeKey];

    echo "<title>{$data['title']}</title>\n";
    echo "<meta name=\"description\" content=\"{$data['description']}\">\n";
    echo "<meta name=\"keywords\" content=\"{$data['keywords']}\">\n";
    echo "<meta name=\"robots\" content=\"index, follow\">\n";

    // Open Graph
    echo "<meta property=\"og:title\" content=\"{$data['og_title']}\">\n";
    echo "<meta property=\"og:description\" content=\"{$data['og_description']}\">\n";
    echo "<meta property=\"og:url\" content=\"" . ($data['og_url'] ?? "https://www.accuratestakes.com/{$route}") . "\">\n";
    echo "<meta property=\"og:type\" content=\"" . ($data['og_type'] ?? 'website') . "\">\n";
    echo "<meta property=\"og:image\" content=\"" . ($data['og_image'] ?? '/accuratestakes.png') . "\">\n";

    // Twitter Card
    echo "<meta name=\"twitter:card\" content=\"" . ($data['twitter_card'] ?? 'summary_large_image') . "\">\n";
    echo "<meta name=\"twitter:site\" content=\"" . ($data['twitter_site'] ?? '@accuratestakes') . "\">\n";
    echo "<meta name=\"twitter:title\" content=\"{$data['twitter_title']}\">\n";
    echo "<meta name=\"twitter:description\" content=\"{$data['twitter_description']}\">\n";
    echo "<meta name=\"twitter:image\" content=\"" . ($data['twitter_image'] ?? '/accuratestakes.png') . "\">\n";
} else {
    // fallback if route not found
    echo "<title>AccurateStakes - Football Predictions</title>\n";
}
?>
