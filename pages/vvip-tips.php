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
$isPremium = authUserHasPremiumAccess($user);
$today = date('Y-m-d');
$todayLabel = date('l, M j');
$unlockHref = '/plans';

$games = $token !== '' ? dashboardFetchMultibetGames($token, $today, 'vvip', 30) : [];
if (!$isPremium) {
    $games = dashboardMaskLockedGames($games);
}

$metaTags = <<<HTML
<title>VVIP Tips | BetAssured</title>
<meta name="title" content="VVIP Tips | BetAssured">
<meta name="description" content="BetAssured VVIP multibet tips from Pitch Predictions admin selections.">
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
        <a class="dash-tip-nav-btn is-vvip is-active" href="/vvip-tips">VVIP tips</a>
        <a class="dash-tip-nav-btn is-jackpot" href="/vip-jackpots">VIP Jackpots</a>
    </nav>

    <?php
    dashboardRenderGamesPanel(
        'vvip-tips',
        'VVIP tips',
        $isPremium ? 'Unlocked Premium multibets · ' . $todayLabel : 'Premium required · tips hidden until you subscribe',
        $games,
        !$isPremium,
        '/dashboard',
        'Back to dashboard',
        $unlockHref,
        'is-vvip'
    );
    ?>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
