<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();
authRedirectIfLoggedIn('/dashboard');

$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$email = trim((string) ($_GET['email'] ?? $_POST['email'] ?? ''));
$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

    if ($token === '' || $email === '') {
        $error = 'Reset link is invalid or incomplete.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $passwordConfirmation) {
        $error = 'Password and confirm password do not match.';
    } else {
        $result = authApiRequest('POST', 'reset_password', [
            'token' => $token,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ]);

        if ($result['ok']) {
            $success = authApiErrorMessage($result['data'], 'Password reset successful. You can now log in.');
        } else {
            $error = authApiErrorMessage($result['data'], 'Failed to reset password. Please request a new link.');
        }
    }
}

$metaTags = <<<HTML
<title>Reset Password | BetAssured</title>
<meta name="title" content="Reset Password | BetAssured">
<meta name="description" content="Set a new password for your BetAssured account.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/auth.css?v=4">

<main class="container auth-page">
    <div class="auth-card">
        <h1 class="auth-title">Reset password</h1>
        <?php if ($success): ?>
            <div class="auth-alert auth-alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="auth-actions">
                <a class="auth-btn" href="/login">Go to Login</a>
            </div>
        <?php else: ?>
            <p class="auth-lead">Choose a new password for <?php echo htmlspecialchars($email !== '' ? $email : 'your account', ENT_QUOTES, 'UTF-8'); ?>.</p>

            <?php if ($error): ?>
                <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if ($token === '' || $email === ''): ?>
                <div class="auth-alert auth-alert-error">This reset link is missing a token or email. Please request a new one.</div>
                <div class="auth-actions">
                    <a class="auth-btn" href="/forgot-password">Request new reset link</a>
                </div>
            <?php else: ?>
                <form method="POST" action="/reset-password" class="auth-form">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-group">
                        <label for="password">New password</label>
                        <input id="password" type="password" name="password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm new password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required minlength="6">
                    </div>
                    <button type="submit" class="auth-btn">Update password</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
