<?php
if (!function_exists('authIsLoggedIn')) {
    include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
    authBootstrapSession();
}
$navLoggedIn = authIsLoggedIn();
$navUserName = htmlspecialchars((string) ($_SESSION['user_name'] ?? 'User'), ENT_QUOTES, 'UTF-8');
$navUserEmail = htmlspecialchars((string) ($_SESSION['user_email'] ?? ''), ENT_QUOTES, 'UTF-8');
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
                    <button class="user-icon-btn" id="desktopUserIconBtn" type="button" aria-label="Account menu">
                        <i class="bi bi-person-circle"></i>
                    </button>
                    <div class="user-dropdown-menu" id="desktopUserDropdown">
                        <?php if ($navLoggedIn): ?>
                            <div class="user-info-menu">
                                <div class="user-avatar">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <div class="user-details">
                                    <div class="user-name"><?php echo $navUserName; ?></div>
                                    <div class="user-email"><?php echo $navUserEmail; ?></div>
                                </div>
                                <a href="/dashboard" class="menu-item">
                                    <i class="bi bi-speedometer2"></i>
                                    <span>Dashboard</span>
                                </a>
                                <a href="/profile" class="menu-item">
                                    <i class="bi bi-person"></i>
                                    <span>Edit Profile</span>
                                </a>
                                <a href="/logout" class="menu-item logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="login-form-container">
                                <div class="login-header">
                                    <h3>Welcome Back!</h3>
                                    <p>Sign in to your shared Pitch Predictions account</p>
                                </div>
                                <form action="/login" method="POST">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" required placeholder="Enter your email">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" required placeholder="Enter your password">
                                    </div>
                                    <button type="submit" class="signin-btn">
                                        <i class="bi bi-box-arrow-in-right"></i> Sign In
                                    </button>
                                </form>
                                <div class="login-footer">
                                    <a href="/register">+ Create new free account</a>
                                    <a href="/forgot-password">Forgot my password</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
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
            <li><a href="/" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="/todays-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'todays-predictions') !== false ? 'active' : ''; ?>">Today's Tips</a></li>
            <li><a href="/tomorrows-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'tomorrows-predictions') !== false ? 'active' : ''; ?>">Tomorrow's Tips</a></li>
            <li><a href="/yesterdays-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'yesterdays-predictions') !== false ? 'active' : ''; ?>">Yesterday's Tips</a></li>
            <li><a href="/jackpot-predictions" class="hot-badge <?php echo strpos($_SERVER['REQUEST_URI'], 'jackpot-predictions') !== false ? 'active' : ''; ?>">Jackpot Tips</a></li>
        </ul>

        <div class="mobile-user-icon">
            <div class="user-icon-wrapper">
                <button class="user-icon-btn" id="mobileUserIconBtn" type="button" aria-label="Account menu">
                    <i class="bi bi-person-circle"></i>
                </button>
                <div class="user-dropdown-menu" id="mobileUserDropdown">
                    <?php if ($navLoggedIn): ?>
                        <div class="user-info-menu">
                            <div class="user-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="user-details">
                                <div class="user-name"><?php echo $navUserName; ?></div>
                                <div class="user-email"><?php echo $navUserEmail; ?></div>
                            </div>
                            <a href="/dashboard" class="menu-item">
                                <i class="bi bi-speedometer2"></i>
                                <span>Dashboard</span>
                            </a>
                            <a href="/profile" class="menu-item">
                                <i class="bi bi-person"></i>
                                <span>Edit Profile</span>
                            </a>
                            <a href="/logout" class="menu-item logout">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="login-form-container">
                            <div class="login-header">
                                <h3>Welcome Back!</h3>
                                <p>Sign in to your account</p>
                            </div>
                            <form action="/login" method="POST">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" required placeholder="Enter your email">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" required placeholder="Enter your password">
                                </div>
                                <button type="submit" class="signin-btn">
                                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                                </button>
                            </form>
                            <div class="login-footer">
                                <a href="/register">+ Create new free account</a>
                                <a href="/forgot-password">Forgot my password</a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-nav">
        <div class="mobile-nav-items">
            <a href="/" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'index.php') !== false ? 'active' : ''; ?>">Home</a>
            <a href="/todays-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'todays-predictions') !== false ? 'active' : ''; ?>">Today's Tips</a>
            <a href="/tomorrows-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'tomorrows-predictions') !== false ? 'active' : ''; ?>">Tomorrow's Tips</a>
            <a href="/yesterdays-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'yesterdays-predictions') !== false ? 'active' : ''; ?>">Yesterday's Tips</a>
            <a href="/jackpot-predictions" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'jackpot-predictions') !== false ? 'active' : ''; ?>">Jackpot Tips 🔥</a>
        </div>
    </div>
</div>

<script>
const desktopBtn = document.getElementById('desktopUserIconBtn');
const desktopDropdown = document.getElementById('desktopUserDropdown');
if (desktopBtn) {
    desktopBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        desktopDropdown.classList.toggle('show');
    });
}

const mobileBtn = document.getElementById('mobileUserIconBtn');
const mobileDropdown = document.getElementById('mobileUserDropdown');
if (mobileBtn) {
    mobileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        mobileDropdown.classList.toggle('show');
    });
}

document.addEventListener('click', function(event) {
    if (desktopBtn && desktopDropdown) {
        if (!desktopBtn.contains(event.target) && !desktopDropdown.contains(event.target)) {
            desktopDropdown.classList.remove('show');
        }
    }
    if (mobileBtn && mobileDropdown) {
        if (!mobileBtn.contains(event.target) && !mobileDropdown.contains(event.target)) {
            mobileDropdown.classList.remove('show');
        }
    }
});

if (desktopDropdown) {
    desktopDropdown.addEventListener('click', function(e) { e.stopPropagation(); });
}
if (mobileDropdown) {
    mobileDropdown.addEventListener('click', function(e) { e.stopPropagation(); });
}
</script>
