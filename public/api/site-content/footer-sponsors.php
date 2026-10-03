<?php

require_once __DIR__ . '/../../../config/constants.php';
require_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
require_once BASE_PATH . '/components/shared/BlogApi.shared.php';
require_once BASE_PATH . '/components/shared/FooterSponsors.shared.php';

header('Content-Type: application/json; charset=utf-8');

$method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));

if ($method === 'GET') {
    $wantAll = in_array(strtolower((string) ($_GET['all'] ?? '')), ['1', 'true'], true);
    if ($wantAll) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        echo json_encode([
            'success' => true,
            'data' => readSponsorDocument(),
        ]);
        exit;
    }

    $document = readSponsorDocument();
    header('Cache-Control: public, max-age=0, s-maxage=0, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo json_encode([
        'success' => true,
        'updated_at' => $document['updated_at'],
        'links' => publicVisibleSponsorLinks($document),
    ]);
    exit;
}

if ($method === 'PUT' || $method === 'POST') {
    if (blogCacheClearKey() === null) {
        http_response_code(503);
        echo json_encode([
            'error' => 'BLOG_CACHE_CLEAR_KEY must be set to exactly 24 characters on this host before footer links can be saved.',
        ]);
        exit;
    }

    if (!isBlogCacheClearAuthorized()) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $raw = file_get_contents('php://input');
    $body = json_decode((string) $raw, true);
    if (!is_array($body)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Invalid JSON body.']);
        exit;
    }

    $incoming = (isset($body['data']) && is_array($body['data'])) ? $body['data'] : $body;
    if (!isset($incoming['links']) || !is_array($incoming['links'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'error' => 'Body must include a links array.']);
        exit;
    }

    try {
        $saved = writeSponsorDocument($incoming);
        header('Cache-Control: no-store, no-cache, must-revalidate');
        echo json_encode([
            'success' => true,
            'message' => 'Footer sponsor links saved.',
            'data' => $saved,
        ]);
    } catch (Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage() ?: 'Failed to write footer-sponsors.json',
        ]);
    }
    exit;
}

http_response_code(405);
header('Allow: GET, PUT, POST');
echo json_encode(['error' => 'Method not allowed']);
