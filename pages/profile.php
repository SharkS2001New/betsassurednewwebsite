<?php
include_once BASE_PATH . '/components/shared/AuthApi.shared.php';
authBootstrapSession();
authRequireLogin('/login');

$user = authCurrentUser() ?? [];
$error = null;
$success = null;

$fullName = trim((string) ($_POST['full_name'] ?? $user['full_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? $user['email'] ?? ''));
$phone = trim((string) ($_POST['phone_number'] ?? $user['phone_number'] ?? ''));
$country = trim((string) ($_POST['country'] ?? $user['country'] ?? 'Kenya'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($fullName === '' || $email === '' || $phone === '' || $country === '') {
        $error = 'Please fill in all fields.';
    } else {
        $token = authCurrentToken();
        $result = authApiRequest('POST', 'edit', [
            'full_name' => $fullName,
            'email' => $email,
            'phone_number' => $phone,
            'country' => $country,
        ], $token);

        if ($result['ok'] && !empty($result['data']['user'])) {
            authStoreSession((string) $token, (array) $result['data']['user']);
            $user = authCurrentUser() ?? [];
            $success = authApiErrorMessage($result['data'], 'Profile updated successfully.');
        } else {
            $error = authApiErrorMessage($result['data'], 'Could not update profile.');
        }
    }
}

$metaTags = <<<HTML
<title>Edit Profile | BetAssured</title>
<meta name="title" content="Edit Profile | BetAssured">
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
        <h1 class="auth-title">Edit profile</h1>
        <p class="auth-lead">Update your BetAssured account details.</p>
        <?php if ($success): ?>
            <div class="auth-alert auth-alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="POST" action="/profile" class="auth-form">
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
                <input id="phone_number" type="text" name="phone_number" required value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>">
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
            <button type="submit" class="auth-btn">Save changes</button>
        </form>

        <div class="auth-links">
            <a href="/dashboard">Back to dashboard</a>
            <a href="/logout">Logout</a>
        </div>
    </div>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
