<?php
if (!function_exists('authIsLoggedIn')) {
    include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
    authBootstrapSession();
}
$navLoggedIn = authIsLoggedIn();

// Active nav must use the request path. PHP_SELF is always public/index.php under the router,
// which incorrectly kept "Home" selected on every page.
$navPath = parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
$navPath = is_string($navPath) && $navPath !== '' ? $navPath : '/';
$navPath = rtrim($navPath, '/') ?: '/';

$navIsHome = $navPath === '/' || $navPath === '/index.php';
$navIsToday = $navPath === '/todays-predictions';
$navIsTomorrow = $navPath === '/tomorrows-predictions';
$navIsYesterday = $navPath === '/yesterdays-predictions';
$navIsJackpot = $navPath === '/jackpot-predictions'
    || str_contains($navPath, '-jackpot-predictions');
?>
<!-- Top Bar (Desktop only) -->
<div class="top-bar">
    <div class="top-bar-content">
        <div class="top-bar-left">
            <i class="fas fa-bolt"></i>
            <span class="top-bar-text">BetAssured • Smart Football Predictions • Daily Winning Tips</span>
        </div>
        <div class="top-bar-right">
            <div class="top-bar-social">
                <a href="https://www.facebook.com/profile.php?id=100094600476269"
                   target="_blank" rel="noopener noreferrer"
                   class="social-icon" aria-label="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://twitter.com/FWT1x2"
                   target="_blank" rel="noopener noreferrer"
                   class="social-icon" aria-label="Twitter">
                    <i class="bi bi-twitter"></i>
                </a>
                <a href="https://instagram.com/freewinningtips1x2?utm_source=qr&igshid=MzNlNGNkZWQ4Mg%3D%3D"
                   target="_blank" rel="noopener noreferrer"
                   class="social-icon" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
            </div>

            <div class="desktop-user-icon">
                <div class="user-icon-wrapper">
                    <?php if ($navLoggedIn): ?>
                        <a href="/dashboard" class="nav-login-link">Dashboard</a>
                    <?php else: ?>
                        <a href="/login" class="nav-login-link">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation Bar -->
<div class="main-navbar">
    <div class="navbar-container">
        <div class="navbar-logo">
            <a href="/">
                <img src="/betsassured.png" alt="BetAssured Logo" title="BetAssured">
            </a>
        </div>

        <ul class="navbar-menu">
            <li><a href="/" class="<?php echo $navIsHome ? 'active' : ''; ?>">Home</a></li>
            <li><a href="/todays-predictions" class="<?php echo $navIsToday ? 'active' : ''; ?>">Today's Tips</a></li>
            <li><a href="/tomorrows-predictions" class="<?php echo $navIsTomorrow ? 'active' : ''; ?>">Tomorrow's Tips</a></li>
            <li><a href="/yesterdays-predictions" class="<?php echo $navIsYesterday ? 'active' : ''; ?>">Yesterday's Tips</a></li>
            <li><a href="/jackpot-predictions" class="hot-badge <?php echo $navIsJackpot ? 'active' : ''; ?>">Jackpot Tips</a></li>
        </ul>

        <div class="mobile-user-icon">
            <div class="user-icon-wrapper">
                <?php if ($navLoggedIn): ?>
                    <a href="/dashboard" class="nav-login-link">Dashboard</a>
                <?php else: ?>
                    <a href="/login" class="nav-login-link">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="mobile-nav">
        <div class="mobile-nav-items">
            <a href="/" class="<?php echo $navIsHome ? 'active' : ''; ?>">Home</a>
            <a href="/todays-predictions" class="<?php echo $navIsToday ? 'active' : ''; ?>">Today's Tips</a>
            <a href="/tomorrows-predictions" class="<?php echo $navIsTomorrow ? 'active' : ''; ?>">Tomorrow's Tips</a>
            <a href="/yesterdays-predictions" class="<?php echo $navIsYesterday ? 'active' : ''; ?>">Yesterday's Tips</a>
            <a href="/jackpot-predictions" class="<?php echo $navIsJackpot ? 'active' : ''; ?>">Jackpot Tips 🔥</a>
        </div>
    </div>
</div>
