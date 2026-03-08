<?php
// Preloader & Header
include_once BASE_PATH . "/components/shared/getJackpotFilterName.php";

$jackpot_name = returnJackpotNameSavedInDB($_SERVER['REQUEST_URI']);

$metaTags = <<<HTML
<title>{$jackpot_name} Predictions & Free Tips</title>
<meta name="description" content="Get the latest {$jackpot_name} predictions and free tips. Smart analysis to help you make better betting choices on AccurateStakes.">
<meta name="keywords" content="{$jackpot_name}, jackpot predictions, free jackpot tips, betting tips, football jackpot">

<meta property="og:title" content="{$jackpot_name} Predictions & Free Tips">
<meta property="og:description" content="Get the latest {$jackpot_name} predictions and free tips. Smart analysis to help you make better betting choices on AccurateStakes.">

<meta property="twitter:title" content="{$jackpot_name} Predictions & Free Tips">
<meta property="twitter:description" content="Get the latest {$jackpot_name} predictions and free tips. Smart analysis to help you make better betting choices on AccurateStakes.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
include_once BASE_PATH . "/components/shared/getJackpotFilterName.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";

// Prepare API request
$encodedName = urlencode($jackpot_name);
$apiUrl = "https://api.alljackpotpredictions.com/api/fetch_jackpot_fixtures_by_name?jackpot_name=$encodedName";
$token = "q2LsJ9FmT6XvRaCbHuYdK8ZwN4";

// Make cURL request with Authorization and Origin headers
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Partner-Authorization: $token",
    "Origin: https://www.accuratestakes.com"
]);

$response = curl_exec($ch);
curl_close($ch);

// Initialize variables
$predictions = [];
$startDate = $endDate = null;

/**
 * Convert percentage string to integer
 */
function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

/**
 * Determine 1X2 tip based on percentages
 */
function get1X2Tip($tip) {
    $percentHome = percentToInt($tip['percent_pred_home'] ?? '0');
    $percentDraw = percentToInt($tip['percent_pred_draw'] ?? '0');
    $percentAway = percentToInt($tip['percent_pred_away'] ?? '0');
    
    // Find the highest percentage
    $maxPercent = max($percentHome, $percentDraw, $percentAway);
    
    // Return 1, X, or 2 based on highest percentage
    if ($maxPercent === $percentHome) {
        return '1';
    } elseif ($maxPercent === $percentDraw) {
        return 'X';
    } else {
        return '2';
    }
}

// Handle response
if ($response) {
    $result = json_decode($response, true);

    if (json_last_error() === JSON_ERROR_NONE && isset($result['data']) && is_array($result['data'])) {
        $predictions = $result['data'];

        if (!empty($predictions)) {
            $dates = array_filter(array_column($predictions, 'date'));

            if (!empty($dates)) {
                sort($dates);

                $start = new DateTime(reset($dates));
                $end = new DateTime(end($dates));

                $start->modify('+3 hours');
                $end->modify('+3 hours');

                $startDate = $start->format('d/m/Y H:i');
                $endDate = $end->format('d/m/Y H:i');
            } else {
                $startDate = $endDate = '-';
            }
        }
    }
}
?>
<main class="desktop-container" style="width: 100%; background-color: white; border: 1px solid #ddd">
    <div class="container-md-fluid mt-4">
        <h1 id="jackpot-name" class="responsive-title text-center">
            Free <?= htmlspecialchars($jackpot_name) ?> Predictions
        </h1>
        <h2 id="jackpot-dates" class="text-center">
            <?php if ($startDate && $endDate): ?>
                (Starts At: <?= $startDate ?> - Ends At: <?= $endDate ?>)
            <?php endif; ?>
        </h2>

        <div class="table-responsive">
            <?php if (count($predictions) > 0): ?>
                <table id="tips-table" class="table">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Match</th>
                            <th class="d-none d-md-table-cell">Tip</th>
                            <th class="d-md-none text-center">Tip</th>
                            <th>Scores</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($predictions as $index => $tip): ?>
                            <?php
                                // Get 1X2 tip based on percentages
                                $prediction = get1X2Tip($tip);
                                
                                $score = ($tip['goals_home'] === null || $tip['goals_away'] === null)
                                    ? '-'
                                    : "{$tip['goals_home']} - {$tip['goals_away']}";
                                $matchDate = isset($tip['date']) 
                                    ? (new DateTime($tip['date']))->modify('+3 hours')->format('d/m/Y H:i') 
                                    : '-';
                                
                                // Get win/loss status
                                $winningStatus = DetermineWinningOrLost($prediction, $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                            ?>
                            <tr class="align-middle">
                                <td><?= $index + 1 ?>.</td>
                                <td><?= $matchDate ?></td>
                                <td>
                                    <?= htmlspecialchars($tip['home_team_name'] ?? '') ?> 
                                    <span class="text-danger">vs</span> 
                                    <?= htmlspecialchars($tip['away_team_name'] ?? '') ?>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <strong><?= $prediction ?></strong>
                                    <?= $winningStatus ?>
                                </td>
                                <td class="d-md-none text-center">
                                    <strong><?= $prediction ?></strong>
                                    <br><br>
                                    <?= $winningStatus ?>
                                </td>
                                <td class="fw-bold"><?= $score ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center py-4 text-muted">
                    No predictions available for <?= htmlspecialchars($jackpot_name) ?>.
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>
<?php include_once BASE_PATH . "/components/includes/footer.inc.php"; ?>