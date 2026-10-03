<?php

require_once __DIR__ . '/../../config/constants.php';
require_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
require_once BASE_PATH . '/components/shared/BlogApi.shared.php';

header('Content-Type: application/json; charset=utf-8');

if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (blogCacheClearKey() === null) {
    http_response_code(503);
    echo json_encode([
        'error' => 'BLOG_CACHE_CLEAR_KEY must be set to exactly 24 characters',
    ]);
    exit;
}

if (!isBlogCacheClearAuthorized()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$slug = trim((string) ($_GET['slug'] ?? ''));
if ($slug === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Slug is required']);
    exit;
}

try {
    $slug = rawurldecode($slug);
} catch (Throwable $e) {
    // keep raw
}

$cleared = clearBlogPostCache($slug);

echo json_encode(array_merge($cleared, [
    'revalidated' => true,
    'message' => 'Blog cache cleared. The next visit will fetch fresh content.',
]));
