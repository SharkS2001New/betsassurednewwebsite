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
<link rel="stylesheet" href="/css/auth.css?v=2">

<main class="container auth-page">
    <div class="auth-card">
        <h1 class="auth-title">Create free account</h1>
        <?php if ($success): ?>
            <div class="auth-alert auth-alert-success">
                Registration successful. You can now log in with your email and password.
            </div>
            <div class="auth-actions">
                <a class="auth-btn" href="/login">Go to Login</a>
                <a class="auth-btn auth-btn-secondary" href="/todays-predictions">Browse free tips</a>
            </div>
        <?php else: ?>
            <p class="auth-lead">
                Create a free BetAssured account to access your dashboard and tips.
            </p>

            <?php if ($error): ?>
                <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST" action="/register" class="auth-form">
                <div class="form-group">
                    <label for="full_name">Full name</label>
                    <input id="full_name" type="text" name="full_name" required value="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" required value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone number</label>
                    <input id="phone_number" type="text" name="phone_number" required value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" placeholder="2547...">
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
                    <input id="password" type="password" name="password" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="6">
                </div>
                <button type="submit" class="auth-btn">Create account</button>
            </form>

            <div class="auth-links">
                <a href="/login">Already have an account? Login</a>
                <a href="/todays-predictions">Skip to free tips</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
