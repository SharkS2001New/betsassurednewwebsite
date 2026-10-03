<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';

authBootstrapSession();
authRequireLogin('/login');

$token = authCurrentToken() ?? '';
$redirect = (string) ($_GET['redirect'] ?? '/dashboard');
if ($redirect === '' || $redirect[0] !== '/') {
    $redirect = '/dashboard';
}

if ($token !== '') {
    $result = authApiRequest('GET', 'user', null, $token);
    $user = $result['data'] ?? null;
    // /user may return the user object directly or wrapped
    if ($result['ok'] && is_array($user)) {
        if (isset($user['id']) || isset($user['email'])) {
            authStoreSession($token, $user);
        } elseif (isset($user['data']) && is_array($user['data'])) {
            authStoreSession($token, $user['data']);
        }
    }
}

header('Location: ' . $redirect);
exit;
