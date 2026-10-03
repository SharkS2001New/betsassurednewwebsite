<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';

authBootstrapSession();
header('Content-Type: application/json');

if (!authIsLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode(is_string($raw) ? $raw : '', true);
if (!is_array($body)) {
    $body = $_POST;
}

$planIds = [];
if (!empty($body['plan_ids']) && is_array($body['plan_ids'])) {
    foreach ($body['plan_ids'] as $id) {
        $n = (int) $id;
        if ($n > 0) {
            $planIds[] = $n;
        }
    }
}
if (!empty($body['plan_id'])) {
    $n = (int) $body['plan_id'];
    if ($n > 0) {
        $planIds[] = $n;
    }
}
$planIds = array_values(array_unique($planIds));
$phone = (string) ($body['phone_number'] ?? '');

if ($planIds === [] || $phone === '') {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'plan_ids and phone_number are required']);
    exit;
}

$result = authApiRequest('POST', 'payment/mpesa/stk-push', [
    'plan_ids' => $planIds,
    'phone_number' => $phone,
    'site' => 'bets',
], authCurrentToken());

http_response_code($result['status'] ?: 500);
echo $result['raw'] !== '' ? $result['raw'] : json_encode([
    'success' => false,
    'message' => 'Payment service unavailable',
]);
