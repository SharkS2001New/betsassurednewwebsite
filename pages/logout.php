<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();

$token = authCurrentToken();
if ($token) {
    authApiRequest('POST', 'logout', [], $token);
}

authClearSession();
header('Location: /login');
exit;
