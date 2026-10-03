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

$cleared = clearBlogListCaches();

echo json_encode(array_merge($cleared, [
    'revalidated' => true,
    'message' => 'Blog list caches cleared. The next visit will fetch fresh posts.',
]));
