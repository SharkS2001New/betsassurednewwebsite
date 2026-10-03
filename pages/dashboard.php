<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();
authRequireLogin('/login');

$user = authCurrentUser() ?? [];
$firstName = trim((string) explode(' ', (string) ($user['full_name'] ?? 'punter'))[0]);
$plan = strtolower((string) ($user['active_plan'] ?? 'free'));
$endDate = $user['subscription_end_date'] ?? null;
$expired = false;
if ($plan === 'premium' && !empty($endDate)) {
    $expired = strtotime((string) $endDate) < strtotime('today');
}
$planLabel = $plan === 'free' ? 'Free Plan' : ($expired ? 'Expired Premium' : 'Premium');
$joined = !empty($user['created_at']) ? date('F j, Y', strtotime((string) $user['created_at'])) : '—';

$metaTags = <<<HTML
<title>Dashboard | BetAssured</title>
<meta name="title" content="Dashboard | BetAssured">
<meta name="description" content="Your BetAssured account dashboard.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=1">

<main class="container auth-page">
    <h1 class="page-hero-title">Welcome back, <?php echo htmlspecialchars($firstName !== '' ? $firstName : 'punter', ENT_QUOTES, 'UTF-8'); ?></h1>

    <div class="dash-grid">
        <section class="dash-card">
            <p class="dash-kicker">BetAssured · Account</p>
            <h2 class="dash-name"><?php echo htmlspecialchars((string) ($user['full_name'] ?? 'User'), ENT_QUOTES, 'UTF-8'); ?></h2>
            <p class="dash-meta">
                <?php echo htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?><br>
                Joined <?php echo htmlspecialchars($joined, ENT_QUOTES, 'UTF-8'); ?><br>
                Plan: <strong><?php echo htmlspecialchars($planLabel, ENT_QUOTES, 'UTF-8'); ?></strong>
                <?php if (!empty($endDate) && $plan !== 'free'): ?>
                    <br>Ends: <?php echo htmlspecialchars(date('M j, Y', strtotime((string) $endDate)), ENT_QUOTES, 'UTF-8'); ?>
                <?php endif; ?>
            </p>
            <div class="dash-actions">
                <a href="/profile">Edit profile</a>
                <a class="secondary" href="/logout">Logout</a>
            </div>
        </section>

        <section class="dash-card">
            <p class="dash-kicker">Quick links</p>
            <div class="dash-actions" style="margin-top:8px;">
                <a href="/todays-predictions">Today's tips</a>
                <a class="secondary" href="/jackpot-predictions">Jackpot tips</a>
                <a class="secondary" href="/blog">Blog</a>
                <a class="secondary" href="/free-football-betting-tips">Accumulator tips</a>
            </div>
            <p class="dash-meta" style="margin-top:16px;">
                Welcome to your BetAssured dashboard. Use the same email and password whenever you sign in.
            </p>
        </section>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
