<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
include_once BASE_PATH . '/components/shared/PitchPredictionsApi.shared.php';
include_once BASE_PATH . '/components/shared/DashboardGames.shared.php';

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

$games = $token !== '' ? dashboardFetchAuthGames($token, $today, 8) : [];
$gamesSource = $games !== [] ? 'Member tips' : '';
if ($games === []) {
    $games = dashboardFetchPublicGames($today, 8);
    $gamesSource = $games !== [] ? 'Today\'s free tips' : '';
}

$vipGames = [];
if ($isPremium && $token !== '') {
    $vipGames = dashboardFetchVipGames($token, $today, 8);
}

$metaTags = <<<HTML
<title>Dashboard | BetAssured</title>
<meta name="title" content="Dashboard | BetAssured">
<meta name="description" content="Your BetAssured account dashboard with today's tip games.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=4">

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
            <a class="dash-btn" href="/profile">Edit profile</a>
            <a class="dash-btn dash-btn-ghost" href="/logout">Logout</a>
        </div>
    </section>

    <section class="dash-panel">
        <div class="dash-panel-head">
            <div>
                <h2 class="dash-panel-title">Today's tip games</h2>
                <p class="dash-panel-sub"><?php echo htmlspecialchars($todayLabel, ENT_QUOTES, 'UTF-8'); ?><?php if ($gamesSource !== ''): ?> · <?php echo htmlspecialchars($gamesSource, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?></p>
            </div>
            <a class="dash-link" href="/todays-predictions">View all tips</a>
        </div>

        <?php if ($games === []): ?>
            <div class="dash-empty">
                <p>No tip games available for today yet. Check back shortly or browse the public tips pages.</p>
                <div class="dash-hero-actions" style="margin-top:14px;">
                    <a class="dash-btn" href="/todays-predictions">Today's tips</a>
                    <a class="dash-btn dash-btn-ghost" href="/jackpot-predictions">Jackpot tips</a>
                </div>
            </div>
        <?php else: ?>
            <div class="dash-games-table" role="table" aria-label="Today tip games">
                <div class="dash-games-row dash-games-head" role="row">
                    <span>Kick-off</span>
                    <span>Match</span>
                    <span>League</span>
                    <span>Tip</span>
                    <span>Odds</span>
                    <span>Score</span>
                </div>
                <?php foreach ($games as $game): ?>
                    <div class="dash-games-row" role="row">
                        <span class="dash-kick"><?php echo htmlspecialchars((string) $game['kickoff'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="dash-match">
                            <strong><?php echo htmlspecialchars((string) $game['home'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            <span class="dash-vs">vs</span>
                            <strong><?php echo htmlspecialchars((string) $game['away'], ENT_QUOTES, 'UTF-8'); ?></strong>
                        </span>
                        <span class="dash-league"><?php echo htmlspecialchars((string) $game['league'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span><span class="dash-tip"><?php echo htmlspecialchars((string) $game['pick'], ENT_QUOTES, 'UTF-8'); ?></span></span>
                        <span class="dash-odd"><?php echo htmlspecialchars((string) $game['odd'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="dash-score"><?php echo htmlspecialchars((string) $game['score'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($isPremium): ?>
        <section class="dash-panel">
            <div class="dash-panel-head">
                <div>
                    <h2 class="dash-panel-title">VIP multibet games</h2>
                    <p class="dash-panel-sub">From Pitch Predictions admin selections</p>
                </div>
            </div>
            <?php if ($vipGames === []): ?>
                <div class="dash-empty">
                    <p>No VIP multibet games published for today yet.</p>
                </div>
            <?php else: ?>
                <div class="dash-games-table" role="table" aria-label="VIP multibet games">
                    <div class="dash-games-row dash-games-head" role="row">
                        <span>Kick-off</span>
                        <span>Match</span>
                        <span>League</span>
                        <span>Tip</span>
                        <span>Odds</span>
                        <span>Score</span>
                    </div>
                    <?php foreach ($vipGames as $game): ?>
                        <div class="dash-games-row" role="row">
                            <span class="dash-kick"><?php echo htmlspecialchars((string) $game['kickoff'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="dash-match">
                                <strong><?php echo htmlspecialchars((string) $game['home'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                <span class="dash-vs">vs</span>
                                <strong><?php echo htmlspecialchars((string) $game['away'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </span>
                            <span class="dash-league"><?php echo htmlspecialchars((string) $game['league'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span><span class="dash-tip is-vip"><?php echo htmlspecialchars((string) $game['pick'], ENT_QUOTES, 'UTF-8'); ?></span></span>
                            <span class="dash-odd"><?php echo htmlspecialchars((string) $game['odd'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="dash-score"><?php echo htmlspecialchars((string) $game['score'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <section class="dash-panel dash-links-panel">
        <h2 class="dash-panel-title">Quick links</h2>
        <div class="dash-quick-links">
            <a href="/todays-predictions">Today's tips</a>
            <a href="/tomorrows-predictions">Tomorrow's tips</a>
            <a href="/jackpot-predictions">Jackpot tips</a>
            <a href="/free-football-betting-tips">Accumulator</a>
            <a href="/blog">Blog</a>
            <a href="/profile">Edit profile</a>
        </div>
    </section>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
