<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
include_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
include_once BASE_PATH . '/components/shared/DashboardGames.shared.php';
include_once BASE_PATH . '/components/shared/DashboardGamesTable.shared.php';
include_once BASE_PATH . '/components/shared/PlanEntitlements.shared.php';

authBootstrapSession();
authRequireLogin('/login');

$user = authCurrentUser() ?? [];
$token = authCurrentToken() ?? '';
$catalog = authVipJackpotCatalog();
$selectedName = trim((string) ($_GET['name'] ?? ''));
if ($selectedName === '') {
    $selectedName = $catalog[0]['name'];
}

$selectedPlanId = 61;
foreach ($catalog as $item) {
    if (strcasecmp($item['name'], $selectedName) === 0) {
        $selectedName = $item['name'];
        $selectedPlanId = (int) $item['plan_id'];
        break;
    }
}

$unlocked = authUserHasPlan($user, $selectedPlanId);
$games = $token !== '' ? dashboardFetchVipJackpotGames($token, $selectedName, 20) : [];
if (!$unlocked) {
    $games = dashboardMaskLockedGames($games);
}

$metaTags = <<<HTML
<title>VIP Jackpots | BetAssured</title>
<meta name="title" content="VIP Jackpots | BetAssured">
<meta name="description" content="Premium VIP jackpot tips from Pitch Predictions admin selections.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=6">

<main class="container dash-page">
    <nav class="dash-tip-nav" aria-label="Tip categories">
        <a class="dash-tip-nav-btn is-free" href="/dashboard#free-tips">Free tips</a>
        <a class="dash-tip-nav-btn is-vip" href="/vip-tips">VIP tips</a>
        <a class="dash-tip-nav-btn is-vvip" href="/vvip-tips">VVIP tips</a>
        <a class="dash-tip-nav-btn is-jackpot is-active" href="/vip-jackpots">VIP Jackpots</a>
    </nav>

    <section class="dash-panel">
        <div class="dash-panel-head">
            <div>
                <h1 class="dash-panel-title">VIP Jackpots</h1>
                <p class="dash-panel-sub">Admin-curated premium jackpot tickets — not the public jackpot pages.</p>
            </div>
            <a class="dash-link" href="/plans">View plans</a>
        </div>

        <div class="dash-jackpot-picker">
            <?php foreach ($catalog as $item): ?>
                <?php
                    $has = authUserHasPlan($user, (int) $item['plan_id']);
                    $active = strcasecmp($item['name'], $selectedName) === 0;
                ?>
                <a
                    class="dash-jackpot-chip<?php echo $active ? ' is-active' : ''; ?><?php echo $has ? ' is-owned' : ''; ?>"
                    href="/vip-jackpots?name=<?php echo rawurlencode($item['name']); ?>"
                >
                    <span><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <small><?php echo $has ? 'Unlocked' : 'KES ' . number_format((int) $item['amount']); ?></small>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php
    dashboardRenderGamesPanel(
        'vip-jackpot-games',
        $selectedName,
        $unlocked ? 'Unlocked VIP jackpot tips' : 'Premium required · tips hidden until you subscribe',
        $games,
        !$unlocked,
        '/plans?plan=' . $selectedPlanId,
        $unlocked ? 'Manage plans' : 'Unlock this jackpot',
        '/plans?plan=' . $selectedPlanId,
        'is-jackpot'
    );
    ?>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
