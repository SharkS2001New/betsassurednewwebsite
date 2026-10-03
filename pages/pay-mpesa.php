<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
include_once BASE_PATH . '/components/shared/PlanEntitlements.shared.php';

authBootstrapSession();
authRequireLogin('/login');

$user = authCurrentUser() ?? [];
$token = authCurrentToken() ?? '';

$planIds = [];
if (!empty($_GET['plans'])) {
    foreach (explode(',', (string) $_GET['plans']) as $part) {
        $n = (int) trim($part);
        if ($n > 0) {
            $planIds[] = $n;
        }
    }
}
if (!empty($_GET['plan'])) {
    $n = (int) $_GET['plan'];
    if ($n > 0) {
        $planIds[] = $n;
    }
}
$planIds = array_values(array_unique($planIds));

$planRows = [];
foreach ($planIds as $planId) {
    $result = authApiRequest('GET', 'get_auto_update_data?plan=' . $planId, null, $token);
    $row = $result['data']['data'][0] ?? null;
    if (is_array($row)) {
        $planRows[] = $row;
    }
}

$payConfigResult = authApiRequest('GET', 'payment/mpesa/config?site=bets', null, $token);
$payConfig = is_array($payConfigResult['data']['data'] ?? null) ? $payConfigResult['data']['data'] : [];

$amount = 0;
foreach ($planRows as $row) {
    $amount += (int) round((float) ($row['amount'] ?? 0));
}

$rawPhone = preg_replace('/\D+/', '', (string) ($user['phone_number'] ?? '')) ?: '';
$phoneSeed = '';
if (str_starts_with($rawPhone, '254') && strlen($rawPhone) === 12) {
    $phoneSeed = substr($rawPhone, 3);
} elseif (str_starts_with($rawPhone, '0') && strlen($rawPhone) === 10) {
    $phoneSeed = substr($rawPhone, 1);
} elseif (strlen($rawPhone) === 9) {
    $phoneSeed = $rawPhone;
}

$metaTags = <<<HTML
<title>Pay with M-Pesa | BetAssured</title>
<meta name="title" content="Pay with M-Pesa | BetAssured">
<meta name="description" content="Complete your BetAssured Premium subscription with M-Pesa.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=6">

<main class="container dash-page">
    <section class="auth-card" style="max-width:560px;">
        <h1 class="auth-title">Pay with M-Pesa</h1>
        <p class="auth-lead">
            <?php if ($planRows === []): ?>
                No plan selected. Pick a plan first.
            <?php else: ?>
                You are paying <strong>KES <?php echo number_format($amount); ?></strong> for
                <?php echo count($planRows) === 1
                    ? htmlspecialchars((string) ($planRows[0]['description'] ?? $planRows[0]['plan'] ?? 'Premium tips'), ENT_QUOTES, 'UTF-8')
                    : (count($planRows) . ' plans'); ?>.
            <?php endif; ?>
        </p>

        <?php if ($planRows === []): ?>
            <div class="auth-actions">
                <a class="auth-btn" href="/plans">Browse plans</a>
            </div>
        <?php else: ?>
            <div id="pay-alert" class="auth-alert" hidden></div>

            <form id="pay-mpesa-form" class="auth-form">
                <div class="form-group">
                    <label for="phone_number">M-Pesa number</label>
                    <input
                        id="phone_number"
                        name="phone_number"
                        type="text"
                        inputmode="numeric"
                        maxlength="9"
                        required
                        placeholder="7XXXXXXXX"
                        value="<?php echo htmlspecialchars($phoneSeed, ENT_QUOTES, 'UTF-8'); ?>"
                    >
                </div>
                <p class="dash-panel-sub" style="margin-bottom:1rem;">
                    Till: <?php echo htmlspecialchars((string) ($payConfig['till_number'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?>
                    · Brand: <?php echo htmlspecialchars((string) ($payConfig['brand'] ?? 'BetAssured'), ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <button class="auth-btn" id="pay-submit" type="submit">Pay KES <?php echo number_format($amount); ?></button>
            </form>

            <div class="auth-links">
                <a href="/plans">Change plan</a>
                <a href="/dashboard">Dashboard</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
window.BA_PAY = {
  token: <?php echo json_encode($token, JSON_UNESCAPED_SLASHES); ?>,
  site: "bets",
  planIds: <?php echo json_encode($planIds); ?>,
  apiBase: "https://api.pitchpredictions.com/api"
};
</script>
<script src="/js/pay-mpesa.js?v=1"></script>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
