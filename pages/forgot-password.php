<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();
authRedirectIfLoggedIn('/dashboard');

$error = null;
$success = null;
$email = trim((string) ($_POST['email'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $result = authApiRequest('POST', 'forgot_password', [
            'email' => $email,
            'site' => authSiteKey(),
        ]);
        if ($result['ok']) {
            $success = authApiErrorMessage($result['data'], 'Password reset link sent to your email.');
            $email = '';
        } else {
            $error = authApiErrorMessage($result['data'], 'Failed to send reset link. Please try again.');
        }
    }
}

$metaTags = <<<HTML
<title>Forgot Password | BetAssured</title>
<meta name="title" content="Forgot Password | BetAssured">
<meta name="description" content="Reset your BetAssured account password.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=9">

<main class="auth-page">
    <div class="auth-shell">
        <section class="auth-card" aria-labelledby="auth-title">
            <div class="auth-brand">
                <img src="/betsassured.png" alt="BetAssured">
                <p class="auth-brand-tag">Smart football predictions</p>
            </div>

            <h1 id="auth-title" class="auth-title">Reset your password</h1>
            <p class="auth-lead">Enter the email on your BetAssured account and we’ll send a reset link.</p>

            <?php if ($success): ?>
                <div class="auth-alert auth-alert-success" role="status"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="auth-alert auth-alert-error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST" action="/forgot-password" class="auth-form">
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" type="email" name="email" required autocomplete="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="you@example.com">
                </div>
                <button type="submit" class="auth-btn">Send reset link</button>
            </form>

            <p class="auth-switch">
                Remembered it?
                <a href="/login">Back to sign in</a>
            </p>
        </section>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
