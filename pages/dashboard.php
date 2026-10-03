<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
include_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
include_once BASE_PATH . '/components/shared/DashboardGames.shared.php';
include_once BASE_PATH . '/components/shared/DashboardGamesTable.shared.php';

authBootstrapSession();
authRequireLogin('/login');

$user = authCurrentUser() ?? [];
$token = authCurrentToken() ?? '';
$firstName = trim((string) explode(' ', (string) ($user['full_name'] ?? 'punter'))[0]);
$fullName = (string) ($user['full_name'] ?? 'User');
$email = (string) ($user['email'] ?? '');
$plan = strtolower((string) ($user['active_plan'] ?? 'free'));
$endDate = $user['subscription_end_date'] ?? null;
$expired = false;
if ($plan === 'premium' && !empty($endDate)) {
    $expired = strtotime((string) $endDate) < strtotime('today');
}
$isPremium = $plan === 'premium' && !$expired;
$planLabel = $plan === 'free' ? 'Free' : ($expired ? 'Premium expired' : 'Premium');
$joined = !empty($user['created_at']) ? date('M j, Y', strtotime((string) $user['created_at'])) : '—';
$today = date('Y-m-d');
$todayLabel = date('l, M j');
$unlockHref = '/contact-us';

$freeBundle = dashboardFetchFreeGames($token !== '' ? $token : null, $today, 12);
$freeGames = $freeBundle['games'];
$freeSource = $freeBundle['source'];

$vipGames = $token !== '' ? dashboardFetchMultibetGames($token, $today, 'vip', 10) : [];
$vvipGames = $token !== '' ? dashboardFetchMultibetGames($token, $today, 'vvip', 10) : [];
$jackpotGames = dashboardFetchJackpotGames('Sportpesa Mega Jackpot', 8);

if (!$isPremium) {
    $vipGames = dashboardMaskLockedGames($vipGames);
    $vvipGames = dashboardMaskLockedGames($vvipGames);
    $jackpotGames = dashboardMaskLockedGames($jackpotGames);
}

$metaTags = <<<HTML
<title>Dashboard | BetAssured</title>
<meta name="title" content="Dashboard | BetAssured">
<meta name="description" content="Your BetAssured account dashboard with free, VIP, VVIP and jackpot tip games.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=5">

<main class="container dash-page">
    <section class="dash-hero-card">
        <div class="dash-hero-main">
            <p class="dash-kicker">BetAssured account</p>
            <h1 class="dash-title">Welcome back, <?php echo htmlspecialchars($firstName !== '' ? $firstName : 'punter', ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="dash-subtitle">
                <?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>
                · <?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <div class="dash-meta-row">
                <span class="dash-pill <?php echo $isPremium ? 'is-premium' : ''; ?>"><?php echo htmlspecialchars($planLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="dash-meta-soft">Joined <?php echo htmlspecialchars($joined, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if (!empty($endDate) && $plan !== 'free'): ?>
                    <span class="dash-meta-soft">Ends <?php echo htmlspecialchars(date('M j, Y', strtotime((string) $endDate)), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <div class="dash-hero-actions">
            <?php if (!$isPremium): ?>
                <a class="dash-btn" href="<?php echo htmlspecialchars($unlockHref, ENT_QUOTES, 'UTF-8'); ?>">Get Premium</a>
            <?php endif; ?>
            <a class="dash-btn <?php echo $isPremium ? '' : 'dash-btn-ghost'; ?>" href="/profile">Edit profile</a>
            <a class="dash-btn dash-btn-ghost" href="/logout">Logout</a>
        </div>
    </section>

    <nav class="dash-tip-nav" aria-label="Tip categories">
        <a class="dash-tip-nav-btn is-free" href="#free-tips">Free tips</a>
        <a class="dash-tip-nav-btn is-vip" href="/vip-tips">VIP tips</a>
        <a class="dash-tip-nav-btn is-vvip" href="/vvip-tips">VVIP tips</a>
        <a class="dash-tip-nav-btn is-jackpot" href="/jackpot-predictions">Jackpots</a>
    </nav>

    <?php
    dashboardRenderGamesPanel(
        'free-tips',
        'Free tips',
        ($freeSource !== '' ? $freeSource . ' · ' : '') . $todayLabel,
        $freeGames,
        false,
        '/todays-predictions',
        'View all free tips',
        $unlockHref,
        ''
    );

    dashboardRenderGamesPanel(
        'vip-tips',
        'VIP tips',
        $isPremium ? 'Unlocked Premium multibets · ' . $todayLabel : 'Premium required · tips hidden until you subscribe',
        $vipGames,
        !$isPremium,
        '/vip-tips',
        'Open VIP page',
        $unlockHref,
        'is-vip'
    );

    dashboardRenderGamesPanel(
        'vvip-tips',
        'VVIP tips',
        $isPremium ? 'Unlocked Premium multibets · ' . $todayLabel : 'Premium required · tips hidden until you subscribe',
        $vvipGames,
        !$isPremium,
        '/vvip-tips',
        'Open VVIP page',
        $unlockHref,
        'is-vvip'
    );

    dashboardRenderGamesPanel(
        'jackpot-tips',
        'Jackpot tips',
        $isPremium ? 'Sportpesa Mega Jackpot preview' : 'Premium required · Sportpesa Mega Jackpot preview locked',
        $jackpotGames,
        !$isPremium,
        '/jackpot-predictions',
        'All jackpots',
        $unlockHref,
        'is-jackpot'
    );
    ?>

    <section class="dash-panel dash-links-panel">
        <h2 class="dash-panel-title">Quick links</h2>
        <div class="dash-quick-links">
            <a href="/todays-predictions">Today's tips</a>
            <a href="/vip-tips">VIP tips</a>
            <a href="/vvip-tips">VVIP tips</a>
            <a href="/jackpot-predictions">Jackpot tips</a>
            <a href="/tomorrows-predictions">Tomorrow's tips</a>
            <a href="/blog">Blog</a>
            <a href="/profile">Edit profile</a>
        </div>
    </section>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
