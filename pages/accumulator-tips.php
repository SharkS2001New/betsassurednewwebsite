<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Accumulator Tips Today - Free Football Accumulators | AccurateStakes</title>
<meta name="title" content="AccurateStakes Accumulator Tips – Win Big">
<meta name="description" content="Expert accumulator tips today with pre-built 3-fold, 4-fold & 5-fold accas. Free football accumulator predictions with total odds and success rates. Updated daily.">
<meta name="keywords" content="accumulator tips, accumulator tips today, football accumulators, acca tips, accumulator predictions, free accumulators, acca betting">

<!-- Open Graph -->
<meta property="og:title" content="Today's Football Predictions on Accuratesakes.com">
<meta property="og:description" content="AccurateStakes offers the best and most accurate free predictions. The right site is AccurateStakes.">

<!-- Twitter -->
<meta property="twitter:title" content="Today's Football Predictions on Accuratesakes.com">
<meta property="twitter:description" content="AccurateStakes offers the best and most accurate free predictions. The right site is AccurateStakes.">
HTML;

// Preloader & Header
include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<!-- Schema: WebPage -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebPage",
      "name": "Accumulator Tips Today - Football Accumulators",
      "description": "Expert accumulator tips with pre-built combinations and odds calculations",
      "publisher": {
        "@type": "Organization",
        "name": "AccurateStakes",
        "url": "https://www.accuratestakes.com"
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
          "name": "What is an accumulator bet?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "An accumulator (acca) is a single bet that combines 4 or more selections. All selections must win for the bet to pay out. Odds multiply together: 4 picks at 1.80 each = 10.50 total odds. Higher risk but higher potential returns than single bets."
          }
        },
        {
          "@type": "Question",
          "name": "How successful are accumulator tips?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Our 3-fold accumulators (using 75% confidence picks) achieve approximately 42% success rate. 4-fold accumulators achieve 31% success, 5-fold 24%. While lower than singles, the higher odds (6-15 range) make them profitable when disciplined bankroll management is used."
          }
        }
      ]
    }
    </script>
    <!-- Schema: BreadcrumbList -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.accuratestakes.com/"},
        {"@type": "ListItem", "position": 2, "name": "Accumulator Tips"}
      ]
    }
    </script>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";

// Include all helper functions
include_once BASE_PATH . "/components/shared/DateTimeToUsersTimezone.shared.php";
include_once BASE_PATH . "/components/shared/DoubleChanceWinningTeam.shared.php";
include_once BASE_PATH . "/components/shared/ComputeFixtureAverage.shared.php";
include_once BASE_PATH . "/components/shared/UnderOverWinningTeamAndOdd.shared.php";
include_once BASE_PATH . "/components/shared/WinningTeamPred1x2.shared.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";

$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/accumulator-tips.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// Configuration
$apiUrl = "https://api.pitchpredictions.com/api/fetch_accumulator_page_matches_fixtures";
$token = "R9TxV3PbOEu7qZnJKgydC5LmX2";
$currentDate = date('Y-m-d');
$popularLeagues = ["Premier League", "La Liga", "Serie A", "Bundesliga", "Ligue 1", "FA Cup"];
$maxMatches = 10;

// Fetch data from API
$tipsData = [];
$error = null;
$empty = false;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . "?fixture_date=" . $currentDate);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $token
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    $error = 'Curl error: ' . curl_error($ch);
} elseif ($httpCode !== 200) {
    $error = 'HTTP Error: ' . $httpCode;
} else {
    $data = json_decode($response, true);
    if (isset($data['data']) && is_array($data['data'])) {
        $tipsData = $data['data'];
        $empty = count($tipsData) === 0;
    } else {
        $error = 'Invalid data format received';
    }
}
curl_close($ch);

/**
 * Clean percentage value - matches JS cleanPercent()
 */
function cleanPercent($value) {
    if ($value === null || $value === '') return 0;
    return floatval(str_replace('%', '', trim($value))) ?: 0;
}

/**
 * Get prediction - matches JS getPrediction()
 */
function getPrediction($tip) {
    $homePercent = cleanPercent($tip['percent_pred_home'] ?? '0');
    $drawPercent = cleanPercent($tip['percent_pred_draw'] ?? '0');
    $awayPercent = cleanPercent($tip['percent_pred_away'] ?? '0');
    
    // Find the highest percentage
    $maxPercent = max($homePercent, $drawPercent, $awayPercent);
    
    if ($maxPercent === $homePercent) return "1";
    if ($maxPercent === $drawPercent) return "X";
    return "2";
}

/**
 * Get odd - matches JS getOdd()
 */
function getOdd($tip, $prediction) {
    // Try to parse the all_bets_odds JSON first
    try {
        if (!empty($tip['all_bets_odds'])) {
            $odds = json_decode($tip['all_bets_odds'], true);
            if (is_array($odds)) {
                foreach ($odds as $market) {
                    if (isset($market['name']) && $market['name'] === "Match Winner" && isset($market['values'])) {
                        $map = ["1" => "Home", "X" => "Draw", "2" => "Away"];
                        $label = $map[$prediction] ?? '';
                        foreach ($market['values'] as $bet) {
                            if (isset($bet['value']) && $bet['value'] === $label && isset($bet['odd'])) {
                                return floatval($bet['odd']) ?: 1.0;
                            }
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        // Fallback to direct odds
    }
    
    // Fallback to direct odds
    switch($prediction) {
        case "1":
            return floatval($tip['bets_home'] ?? 0) ?: 1.0;
        case "X":
            return floatval($tip['bets_draw'] ?? 0) ?: 1.0;
        case "2":
            return floatval($tip['bets_away'] ?? 0) ?: 1.0;
        default:
            return 1.0;
    }
}

/**
 * Get scores - matches JS getScores()
 */
function getScores($tip) {
    if (isset($tip['goals_home']) && $tip['goals_home'] !== null && 
        isset($tip['goals_away']) && $tip['goals_away'] !== null && 
        $tip['goals_home'] !== '' && $tip['goals_away'] !== '') {
        return htmlspecialchars($tip['goals_home'] . ' - ' . $tip['goals_away']);
    }
    return '-';
}

/**
 * Format time - matches JS formatTime()
 */
function formatTime($dateStr) {
    if (empty($dateStr)) return '-';
    
    try {
        // Date format is "01/11/2026 14:00" (DD/MM/YYYY HH:mm)
        $parts = explode(' ', $dateStr);
        if (count($parts) >= 2) {
            $timePart = $parts[1];
            $timeParts = explode(':', $timePart);
            if (count($timeParts) >= 2) {
                return $timeParts[0] . ':' . $timeParts[1];
            }
        }
        return date('H:i', strtotime($dateStr));
    } catch (Exception $e) {
        return $dateStr;
    }
}

// Process and filter tips
$filteredTips = [];
if (!empty($tipsData)) {
    foreach ($tipsData as $tip) {
        // Filter by popular leagues
        if (in_array($tip['league_name'] ?? '', $popularLeagues)) {
            $prediction = getPrediction($tip);
            $odd = getOdd($tip, $prediction);
            $confidence = max(
                cleanPercent($tip['percent_pred_home'] ?? '0'),
                cleanPercent($tip['percent_pred_draw'] ?? '0'),
                cleanPercent($tip['percent_pred_away'] ?? '0')
            );
            
            $filteredTips[] = [
                'tip' => $tip,
                'prediction' => $prediction,
                'odd' => $odd,
                'confidence' => $confidence
            ];
        }
    }
    
    // Sort by confidence (highest first)
    usort($filteredTips, function($a, $b) {
        return $b['confidence'] <=> $a['confidence'];
    });
    
    // Take top matches
    $filteredTips = array_slice($filteredTips, 0, $maxMatches);
}
?>
<main class="desktop-container" style="width: 100%; background-color: white; border: 1px solid #ddd">
    <section class="container py-3 py-md-4 d-flex mt-1 mb-2">
        <div class="row mb-2">
            <div class="col-12">
                <h1 style="font-size:28px;font-weight:bold">Accumulator Tips Today - Free Football Accumulators</h1>
            <p>
                Welcome to our <strong>accumulator tips</strong> page. We provide pre-built accumulators (accas) combining
                our highest-confidence predictions for bigger potential returns. Our accumulators range from conservative 3-folds 
                (42% success rate) to ambitious 5-folds (24% success rate), all calculated using our 70-75% accuracy predictions.
            </p>
            </div>     
        </div> 
    </section>
    
    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Popular Tips Today -->
    <?php include_once BASE_PATH . "/components/shared/popular-tips.shared.php"; ?>

    <div class="container-md-fluid mt-4">
        <!-- PHP-generated table content -->
        <div class="table-responsive">
            <?php if ($error): ?>
                <div class="alert alert-danger text-center">
                    Failed to load predictions. Please try again later.
                </div>
            <?php elseif ($empty || empty($filteredTips)): ?>
                <div class="text-center py-4 text-muted">
                    No predictions available for today.
                </div>
            <?php else: ?>
                <table id="tips-table" class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Time</th>
                            <th scope="col">League</th>
                            <th scope="col">Match</th>
                            <th scope="col">Tip</th>
                            <th scope="col" class="d-none d-md-table-cell text-left">Odds</th>
                            <th scope="col">Scores</th>
                        </tr>
                    </thead>
                    <tbody id="tips-container">
                        <?php foreach ($filteredTips as $item): 
                            $tip = $item['tip'];
                            $prediction = $item['prediction'];
                            $odd = $item['odd'];
                            
                            // Get winning/lost status using shared helper
                            $winningStatus = DetermineWinningOrLost($prediction, $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                            
                            // Format time using shared helper
                            $formattedTime = '-';
                            if (!empty($tip['date'])) {
                                $formattedTime = DateTimeToUsersTimezone($tip['date']);
                                // If the shared helper returns full datetime, extract just time
                                if (strlen($formattedTime) > 5 && strpos($formattedTime, ':') !== false) {
                                    $timeParts = explode(' ', $formattedTime);
                                    $formattedTime = end($timeParts);
                                }
                            }
                        ?>
                        <tr>
                            <td>
                                <?php echo $formattedTime; ?>
                            </td>
                            
                            <!-- Desktop League Name and Logo -->
                            <td class="d-none d-md-table-cell" style="width: 20%; font-weight: 500;">
                                <?php if (!empty($tip['downloaded_league_logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($tip['downloaded_league_logo']); ?>" 
                                        alt="<?php echo htmlspecialchars($tip['league_name'] ?? ''); ?>" 
                                        class="me-2" 
                                        style="width: 20px; height: 20px; vertical-align: middle;">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($tip['league_name'] ?? ''); ?>
                            </td>
                            
                            <!-- Mobile League Short Name -->
                            <td class="d-md-none" style="width: 10%; font-weight: 500;">
                                <?php echo htmlspecialchars($tip['league_short_name'] ?? ''); ?>
                            </td>
                            
                            <!-- Match -->
                            <td>
                                <?php echo htmlspecialchars($tip['home_team_name'] ?? ''); ?> 
                                <span class="text-danger">vs</span> 
                                <?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?>
                            </td>
                            
                            <!-- Tip -->
                            <td>
                                <b><?php echo $prediction; ?></b>
                                <?php echo $winningStatus; ?>
                            </td>
                            
                            <!-- Odds - Desktop only -->
                            <td class="d-none d-md-table-cell">
                                <?php echo number_format($odd, 2); ?>
                            </td>
                            
                            <!-- Scores -->
                            <td>
                                <?php echo getScores($tip); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php endif; ?>
        </div>
    </div>

    <!-- Seo Content -->
    <section class="container py-3 py-md-4 d-flex">
        <div class="row">
            <div class="blog-2 col-md-12 seo-content">
                <?php echo $htmlContent; ?>
            </div>
        </div>
    </section>
</main>

<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
