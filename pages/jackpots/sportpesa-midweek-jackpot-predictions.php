<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Sportpesa Midweek Jackpot Predictions This Week - Free 13 Games Tips | AccurateStakes</title>
<meta name="title" content="SportPesa Midweek Jackpot Predictions">
<meta name="description" content="Free Sportpesa Midweek Jackpot predictions for this week's 13 games. Expert analysis, confidence ratings, winning strategies, and tips to hit bonus brackets. Updated every Wednesday.">
<meta name="keywords" content="sportpesa midweek jackpot predictions, sportpesa midweek jackpot, sportpesa midweek jackpot tips, sportpesa 13 games predictions, midweek jackpot predictions kenya">

<!-- Open Graph -->
<meta property="og:title" content="SportPesa Midweek Jackpot Predictions">
<meta property="og:description" content="Get accurate tips and predictions for the SportPesa Midweek Jackpot. All you need for a successful ticket.">

<!-- Twitter -->
<meta property="twitter:title" content="SportPesa Midweek Jackpot Predictions">
<meta property="twitter:description" content="Get accurate tips and predictions for the SportPesa Midweek Jackpot. All you need for a successful ticket.">
HTML;

// Preloader & Header
include_once BASE_PATH . "/components/includes/header.inc.php";
?>
 <!-- Schema: BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.accuratestakes.com/"},
        {"@type": "ListItem", "position": 2, "name": "Jackpot Predictions", "item": "https://www.accuratestakes.com/jackpot-predictions"},
        {"@type": "ListItem", "position": 3, "name": "Sportpesa Midweek Jackpot"}
      ]
    }
    </script>
    <!-- Schema: SportsEvent -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SportsEvent",
      "name": "Sportpesa Midweek Jackpot - Week of February 19, 2026",
      "description": "13-game midweek football jackpot competition",
      "startDate": "2026-02-19T18:00:00+03:00",
      "endDate": "2026-02-20T23:00:00+03:00",
      "eventStatus": "https://schema.org/EventScheduled",
      "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
      "location": {
        "@type": "Place",
        "name": "SportPesa Kenya",
        "address": {"@type": "PostalAddress", "addressCountry": "KE"}
      },
      "organizer": {
        "@type": "Organization",
        "name": "SportPesa",
        "url": "https://www.sportpesa.co.ke"
      },
      "offers": {
        "@type": "Offer",
        "price": "99",
        "priceCurrency": "KES",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
    <!-- Schema: FAQPage -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How many games are in Sportpesa Midweek Jackpot?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The Sportpesa Midweek Jackpot consists of 13 pre-selected football matches. You must correctly predict the outcome (1X2) of all 13 games to win the grand prize, though bonus prizes are awarded for 10, 11, and 12 correct predictions."
          }
        },
        {
          "@type": "Question",
          "name": "When does Sportpesa Midweek Jackpot run?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "The Sportpesa Midweek Jackpot typically runs from Wednesday evening through Thursday night, covering midweek matches from European leagues including Premier League, La Liga, Serie A, and others."
          }
        }
      ]
    }
    </script>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";

// Parse SEO content
$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH . '/components/seo-content/sportpesa-midweek-jackpot.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// Prepare API request
$jackpotName = "Sportpesa Midweek Jackpot";
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
            Free Sportpesa Midweek Jackpot Predictions - This Week's 13 Games
        </h1>
        <h2 id="jackpot-dates" class="text-center">
            <?php if ($startDate && $endDate): ?>
                (Starts At: <?= $startDate ?> - Ends At: <?= $endDate ?>)
            <?php endif; ?>
        </h2>

        <p>Looking for expert <strong>Sportpesa Midweek Jackpot predictions</strong> for this week's 13 games?
            AccurateStakes provides free, comprehensive analysis with confidence ratings,
            match breakdowns, and winning strategies to help you target the bonus brackets and grand prize.
        </p>
        <p>
        Unlike the 17-game Mega Jackpot, the Sportpesa Midweek Jackpot offers a more manageable 13-game format with midweek matches
            making it ideal for hitting bonus prizes consistently.
        </p>

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
