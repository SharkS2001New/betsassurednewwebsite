<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';

authBootstrapSession();
header('Content-Type: application/json');

if (!authIsLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthenticated']);
    exit;
}

$checkout = trim((string) ($_GET['checkout_request_id'] ?? ''));
$paymentId = trim((string) ($_GET['payment_id'] ?? ''));
$qs = [];
if ($checkout !== '') {
    $qs[] = 'checkout_request_id=' . rawurlencode($checkout);
}
if ($paymentId !== '') {
    $qs[] = 'payment_id=' . rawurlencode($paymentId);
}
if ($qs === []) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'checkout_request_id or payment_id required']);
    exit;
}

$result = authApiRequest('GET', 'payment/mpesa/status?' . implode('&', $qs), null, authCurrentToken());

// Keep local session user fresh when payment settles
if ($result['ok'] && is_array($result['data']['data']['user'] ?? null)) {
    authStoreSession((string) authCurrentToken(), $result['data']['data']['user']);
}

http_response_code($result['status'] ?: 500);
echo $result['raw'] !== '' ? $result['raw'] : json_encode([
    'success' => false,
    'message' => 'Payment status unavailable',
]);
