<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();
authRedirectIfLoggedIn('/dashboard');

$error = null;
$success = false;
$fullName = trim((string) ($_POST['full_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$phone = trim((string) ($_POST['phone_number'] ?? ''));
$country = trim((string) ($_POST['country'] ?? 'Kenya'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

    if ($fullName === '' || $email === '' || $phone === '' || $country === '') {
        $error = 'Please fill in all required fields.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $passwordConfirmation) {
        $error = 'Password and confirm password do not match.';
    } else {
        $result = authApiRequest('POST', 'register', [
            'full_name' => $fullName,
            'email' => $email,
            'phone_number' => $phone,
            'country' => $country,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
            'site' => authSiteKey(),
        ]);

        if ($result['ok']) {
            $success = true;
        } else {
            $error = authApiErrorMessage($result['data'], 'Registration failed. Please try again.');
        }
    }
}

$metaTags = <<<HTML
<title>Create Account | BetAssured</title>
<meta name="title" content="Create Account | BetAssured">
<meta name="description" content="Create a free BetAssured account to access your dashboard and betting tips.">
<meta name="robots" content="noindex, follow">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
$countries = authCountries();
?>
<link rel="stylesheet" href="/css/auth.css?v=9">

<main class="auth-page">
    <div class="auth-shell auth-shell-wide">
        <section class="auth-card" aria-labelledby="auth-title">
            <div class="auth-brand">
                <img src="/betsassured.png" alt="BetAssured">
                <p class="auth-brand-tag">Smart football predictions</p>
            </div>

            <?php if ($success): ?>
                <h1 id="auth-title" class="auth-title">You're in</h1>
                <p class="auth-lead">Your BetAssured account is ready. Sign in to open your dashboard.</p>
                <div class="auth-alert auth-alert-success" role="status">
                    Registration successful. You can now log in with your email and password.
                </div>
                <div class="auth-actions">
                    <a class="auth-btn" href="/login">Go to login</a>
                    <a class="auth-btn auth-btn-secondary" href="/todays-predictions">Browse free tips</a>
                </div>
            <?php else: ?>
                <h1 id="auth-title" class="auth-title">Create your account</h1>
                <p class="auth-lead">Join free — then unlock VIP tips and jackpots when you’re ready.</p>

                <?php if ($error): ?>
                    <div class="auth-alert auth-alert-error" role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form method="POST" action="/register" class="auth-form">
                    <div class="auth-form-grid">
                        <div class="form-group">
                            <label for="full_name">Full name</label>
                            <input id="full_name" type="text" name="full_name" required autocomplete="name" value="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Your full name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" required autocomplete="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="you@example.com">
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone number</label>
                            <input id="phone_number" type="tel" name="phone_number" required autocomplete="tel" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" placeholder="2547…">
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select id="country" name="country" required>
                                <?php foreach ($countries as $item): ?>
                                    <option value="<?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $country === $item ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($item, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required minlength="6" autocomplete="new-password" placeholder="At least 6 characters">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required minlength="6" autocomplete="new-password" placeholder="Repeat password">
                        </div>
                    </div>
                    <button type="submit" class="auth-btn">Create free account</button>
                </form>

                <p class="auth-switch">
                    Already have an account?
                    <a href="/login">Sign in</a>
                </p>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
