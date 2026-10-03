<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
include_once BASE_PATH . '/components/shared/PlanEntitlements.shared.php';

authBootstrapSession();
authRequireLogin('/login');

$user = authCurrentUser() ?? [];
$multibets = authMultibetPlanCatalog();
$jackpots = authVipJackpotCatalog();
$preselect = (int) ($_GET['plan'] ?? 0);

$metaTags = <<<HTML
<title>Premium Plans | BetAssured</title>
<meta name="title" content="Premium Plans | BetAssured">
<meta name="description" content="Subscribe to BetAssured VIP tips and jackpot tickets via M-Pesa.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=6">

<main class="container dash-page">
    <section class="dash-hero-card">
        <div class="dash-hero-main">
            <p class="dash-kicker">BetAssured Premium</p>
            <h1 class="dash-title">Choose your plan</h1>
            <p class="dash-subtitle">Same payment flow as Pitch Predictions — M-Pesa STK for Kenya. Your BetAssured account stays separate from Pitch and FreeTips.</p>
        </div>
        <div class="dash-hero-actions">
            <a class="dash-btn dash-btn-ghost" href="/dashboard">Back to dashboard</a>
        </div>
    </section>

    <section class="dash-panel">
        <div class="dash-panel-head">
            <div>
                <h2 class="dash-panel-title">VIP multibets</h2>
                <p class="dash-panel-sub">Unlock VIP and VVIP tip tickets</p>
            </div>
        </div>
        <div class="ba-plans-grid">
            <?php foreach ($multibets as $plan): ?>
                <?php $owned = authUserHasPlan($user, (int) $plan['plan_id']); ?>
                <article class="ba-plan-card<?php echo $preselect === (int) $plan['plan_id'] ? ' is-selected' : ''; ?><?php echo $owned ? ' is-owned' : ''; ?>">
                    <span class="ba-plan-badge">Multibet</span>
                    <h3><?php echo htmlspecialchars($plan['label'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p class="ba-plan-copy"><?php echo htmlspecialchars($plan['blurb'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="ba-plan-price">KES <?php echo number_format((int) $plan['amount']); ?></p>
                    <div class="dash-hero-actions">
                        <?php if ($owned): ?>
                            <a class="dash-btn dash-btn-ghost" href="/vip-tips">View tips</a>
                        <?php else: ?>
                            <a class="dash-btn" href="/pay/mpesa?plans=<?php echo (int) $plan['plan_id']; ?>">Pay with M-Pesa</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="dash-panel">
        <div class="dash-panel-head">
            <div>
                <h2 class="dash-panel-title">VIP jackpots</h2>
                <p class="dash-panel-sub">Premium admin jackpot tickets</p>
            </div>
        </div>
        <div class="ba-plans-grid">
            <?php foreach ($jackpots as $plan): ?>
                <?php $owned = authUserHasPlan($user, (int) $plan['plan_id']); ?>
                <article class="ba-plan-card<?php echo $preselect === (int) $plan['plan_id'] ? ' is-selected' : ''; ?><?php echo $owned ? ' is-owned' : ''; ?>">
                    <span class="ba-plan-badge is-jackpot">Jackpot</span>
                    <h3><?php echo htmlspecialchars($plan['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p class="ba-plan-copy">Kenya VIP jackpot ticket access</p>
                    <p class="ba-plan-price">KES <?php echo number_format((int) $plan['amount']); ?></p>
                    <div class="dash-hero-actions">
                        <?php if ($owned): ?>
                            <a class="dash-btn dash-btn-ghost" href="/vip-jackpots?name=<?php echo rawurlencode($plan['name']); ?>">View jackpot</a>
                        <?php else: ?>
                            <a class="dash-btn" href="/pay/mpesa?plans=<?php echo (int) $plan['plan_id']; ?>">Pay with M-Pesa</a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
