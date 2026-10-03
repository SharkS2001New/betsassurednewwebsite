<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();
authRedirectIfLoggedIn('/dashboard');

$error = null;
$email = trim((string) ($_POST['email'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    if ($email === '' || $password === '') {
        $error = 'Please enter your email and password.';
    } else {
        $result = authApiRequest('POST', 'login', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($result['ok'] && !empty($result['data']['token']) && !empty($result['data']['user'])) {
            authStoreSession((string) $result['data']['token'], (array) $result['data']['user']);
            header('Location: /dashboard');
            exit;
        }

        $error = authApiErrorMessage($result['data'], 'Login failed. Please try again.');
    }
}

$metaTags = <<<HTML
<title>Login | BetAssured</title>
<meta name="title" content="Login | BetAssured">
<meta name="description" content="Sign in to your BetAssured account to access your dashboard and saved tips.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=4">

<main class="container auth-page">
    <div class="auth-card">
        <h1 class="auth-title">Login</h1>
        <p class="auth-lead">Sign in to your BetAssured account to access your dashboard.</p>

        <?php if ($error): ?>
            <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="POST" action="/login" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Your password">
            </div>
            <button type="submit" class="auth-btn">Sign In</button>
        </form>

        <div class="auth-links">
            <a href="/register">Create free account</a>
            <a href="/forgot-password">Forgot password?</a>
        </div>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
