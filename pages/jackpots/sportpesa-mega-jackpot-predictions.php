<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Free Sportpesa Mega Jackpot Predictions This Week - 17 Games | Kenya</title>
<meta name="title" content="SportPesa Mega Jackpot Predictions">
<meta name="description" content="Free Sportpesa Mega Jackpot predictions for this week's 17 games. Expert analysis, winning strategies, and tips to help you hit the bonus brackets. Updated weekly.">
<meta name="keywords" content="sportpesa mega jackpot predictions, sportpesa jackpot tips, sportpesa mega jackpot this week, how to win sportpesa jackpot, sportpesa 17 games predictions">

<!-- Open Graph -->
<meta property="og:title" content="SportPesa Mega Jackpot Predictions">
<meta property="og:description" content="Find trusted SportPesa Mega Jackpot predictions and tips to boost your chances in the big prize pool.">

<!-- Twitter -->
<meta property="twitter:title" content="SportPesa Mega Jackpot Predictions">
<meta property="twitter:description" content="Find trusted SportPesa Mega Jackpot predictions and tips to boost your chances in the big prize pool.">
HTML;

// Preloader & Header
include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SportsEvent",
  "name": "Sportpesa Mega Jackpot - February 14-15, 2026",
  "description": "Weekly Sportpesa Mega Jackpot with 17 football matches. Win up to KES 250 million by correctly predicting all game outcomes.",
  "startDate": "2026-02-14T18:15:00+03:00",
  "endDate": "2026-02-15T23:00:00+03:00",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
  "location": {
    "@type": "VirtualLocation",
    "url": "https://www.sportpesa.co.ke"
  },
  "image": "https://www.accuratestakes.com/images/sportpesa-mega-jackpot.jpg",
  "organizer": {
    "@type": "Organization",
    "name": "SportPesa",
    "url": "https://www.sportpesa.co.ke"
  },
  "offers": {
    "@type": "Offer",
    "url": "https://www.sportpesa.co.ke/jackpots",
    "price": "99",
    "priceCurrency": "KES",
    "availability": "https://schema.org/InStock",
    "validFrom": "2026-02-13T00:00:00+03:00"
  }
}
</script>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";

// Parse SEO content
$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH . '/components/seo-content/sportpesa-mega-jackpot.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// Prepare API request
$jackpotName = "Sportpesa Mega Jackpot";
$encodedName = urlencode($jackpotName);
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
           Free Sportpesa Mega Jackpot Predictions - This Week's 17 Games</h1>
        <p><p>Looking for expert Sportpesa Mega Jackpot predictions this week? 
        Our comprehensive Sportpesa jackpot analysis covers all 17 games with 
        detailed match breakdowns, confidence ratings, and winning strategies. Learn 
        <strong>how to win Sportpesa Mega Jackpot</strong> with our proven, data-driven approach.</p></p>
        
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
                                    <br>
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
                    No predictions available for today.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- SEO Content -->
    <section class="container py-3 py-md-4 d-flex">
        <div class="row">
            <div class="blog-2 col-md-12 seo-content">
                <?= $htmlContent ?>
            </div>
        </div>
    </section>
</main>
<?php include_once BASE_PATH . "/components/includes/footer.inc.php"; ?>
