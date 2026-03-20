<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Draw Predictions Today | Safe Draw Tips & 1X2 Football Picks</title>
<meta name="title" content="Draw Predictions Today | Safe Draw Tips & 1X2 Football Picks">
<meta name="description" content="Get free draw predictions today. Safe draw tips with probability ratings and odds analysis updated daily across top leagues worldwide. Find the best draw bets for football betting.">
<meta name="keywords" content="draw predictions today, safe draw tips, football draw predictions, 1x2 draw tips, draw betting tips today, safe draws football, draw no bet tips, today draw predictions">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Draw Predictions Today | Safe Draw Tips & 1X2 Football Picks">
<meta property="og:description" content="Get free draw predictions today. Safe draw tips with probability ratings and odds analysis updated daily across top leagues worldwide.">
<meta property="og:url" content="https://www.betsassured.com/draw-predictions">
<meta property="og:site_name" content="Betsassured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Draw Predictions Today | Safe Draw Tips & 1X2 Football Picks">
<meta name="twitter:description" content="Get free draw predictions today. Safe draw tips with probability ratings and odds analysis updated daily across top leagues worldwide.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "name": "Draw Predictions Today | Safe Draw Tips & 1X2 Football Picks",
  "url": "https://www.betsassured.com/draw-predictions",
  "description": "Get free draw predictions today. Safe draw tips with probability ratings and odds analysis updated daily across top leagues worldwide. Find the best draw bets for football betting."
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What are draw predictions in football?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Draw predictions indicate football matches that are likely to end in a tie, based on statistical analysis, team form, and historical match data."
      }
    },
    {
      "@type": "Question",
      "name": "How accurate are draw predictions?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Our draw predictions use data-driven analysis and expert insight. While they aim for high accuracy, no prediction is guaranteed."
      }
    },
    {
      "@type": "Question",
      "name": "Are draw predictions free to use?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, all draw predictions and safe draw tips on Betsassured are completely free for users."
      }
    },
    {
      "@type": "Question",
      "name": "Can I use these predictions for accumulators?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Yes, draw predictions can be used for both single bets and accumulators depending on your betting strategy."
      }
    }
  ]
}
</script>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/shared/DateTimeToUsersTimezone.shared.php";
include_once BASE_PATH . "/components/shared/DoubleChanceWinningTeam.shared.php";
include_once BASE_PATH . "/components/shared/ComputeFixtureAverage.shared.php";
include_once BASE_PATH . "/components/shared/UnderOverWinningTeamAndOdd.shared.php";
include_once BASE_PATH . "/components/shared/WinningTeamPred1x2.shared.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";

$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/draw-win-tips.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_draws_matches_fixtures";
$token = "R9TxV3PbOEu7qZnJKgydC5LmX2";
$currentDate = date('Y-m-d');

$tipsData = [];
$error = null;
$empty = false;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . "?fixture_date=" . $currentDate);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $token]);
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
 * Get 1X2 prediction with confidence
 */
function getPredictionWithConfidence($tip) {
    $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
    $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
    $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

    $maxPercent = max($homePercent, $drawPercent, $awayPercent);

    if ($maxPercent === $homePercent) {
        return ['prediction' => '1', 'display' => 'Home Win', 'chipClass' => 'chip-home', 'confidence' => $homePercent, 'type' => 'home'];
    } elseif ($maxPercent === $drawPercent) {
        return ['prediction' => 'X', 'display' => 'Draw', 'chipClass' => 'chip-draw', 'confidence' => $drawPercent, 'type' => 'draw'];
    } else {
        return ['prediction' => '2', 'display' => 'Away Win', 'chipClass' => 'chip-away', 'confidence' => $awayPercent, 'type' => 'away'];
    }
}

/**
 * Find odd from market
 */
function findOddFromMarket($allBets, $marketName, $value) {
    if (empty($allBets)) return null;
    foreach ($allBets as $market) {
        if ($market['name'] === $marketName && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === $value) return floatval($bet['odd']);
            }
        }
    }
    return null;
}
?>

<main class="container">
    <h1 class="page-hero-title">Draw Predictions Today | Safe Draw Tips & Football Picks</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Today's Draw Predictions & Safe Draw Tips</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>draw predictions</strong> identify today's most likely <span style="color: #6c757d; font-weight: 600;">Draw (X)</span> outcomes across top leagues. Each safe draw tip shows home, draw, and away probability so you can see exactly how balanced the fixture is before placing.
    </p>

    <!-- Stats Bar -->
    <?php
    $homeCount = 0;
    $drawCount = 0;
    $awayCount = 0;

    foreach ($tipsData as $tip) {
        $predData = getPredictionWithConfidence($tip);
        if ($predData['prediction'] === '1')     $homeCount++;
        elseif ($predData['prediction'] === 'X') $drawCount++;
        elseif ($predData['prediction'] === '2') $awayCount++;
    }
    ?>
    <div class="pred-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo count($tipsData); ?></span>
            <span class="stat-label">Total Matches</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #e2e8f0;"><?php echo $drawCount; ?></span>
            <span class="stat-label">Draw Tips</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #a5f3d0;"><?php echo $homeCount; ?></span>
            <span class="stat-label">Home Wins</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #fca5a5;"><?php echo $awayCount; ?></span>
            <span class="stat-label">Away Wins</span>
        </div>
    </div>

    <!-- Column Headers -->
    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Probability</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Score</span>
    </div>

    <!-- Predictions Wrapper -->
    <div class="preds-wrapper">
        <?php if ($error): ?>
            <div class="state-msg">
                Our analysts are working on today's draw predictions — please check back in a few minutes!
            </div>
        <?php elseif ($empty || empty($tipsData)): ?>
            <div class="state-msg">
                No draw predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php foreach ($tipsData as $tip):

            $predData          = getPredictionWithConfidence($tip);
            $prediction        = $predData['prediction'];
            $displayPrediction = $predData['display'];
            $chipClass         = $predData['chipClass'];
            $confidence        = $predData['confidence'];

            // Odds
            $oddsDisplay = '—';
            if (!empty($tip['all_bets_odds'])) {
                try {
                    $oddsData = json_decode($tip['all_bets_odds'], true);
                    if (is_array($oddsData)) {
                        foreach ($oddsData as $market) {
                            if (($market['name'] ?? '') === "Match Winner" && !empty($market['values'])) {
                                $map   = ["1" => "Home", "X" => "Draw", "2" => "Away"];
                                $label = $map[$prediction] ?? '';
                                foreach ($market['values'] as $bet) {
                                    if (($bet['value'] ?? '') === $label) {
                                        $oddsDisplay = $bet['odd'] ?? '—';
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                } catch (Exception $e) { /* keep default */ }
            }

            // Scores
            $homeScore     = $tip['goals_home'] ?? null;
            $awayScore     = $tip['goals_away'] ?? null;
            $scoreDisplay  = '—';
            $matchStatus   = 'UPCOMING';
            $statusClass   = 'upcoming';
            $winningStatus = '';

            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay  = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $matchStatus   = $tip['status_short'] ?? null;
                $statusClass   = '';
                $winningStatus = DetermineWinningOrLost($prediction, $homeScore, $awayScore);
            }

            // Probabilities
            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            $circ     = 106.76;
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);

            // Team initials
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));

            // League
            $leagueFull    = $tip['league_name'] ?? '';
            $leagueCountry = $tip['country_name'] ?? '';

           /* ---- Time display ---- */
            $formattedTime = '—';
            $formattedDate = '';
            if (!empty($tip['date'])) {
                $formattedTime = DateTimeToUsersTimezone($tip['date']);
            }
            
            $hasScore = ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '');
            $statusShort = htmlspecialchars($tip['status_short'] ?? '');   // e.g. "FT", "HT", "1H", "NS"
        ?>

       <div class="match-card">
            <!-- Time Column (hidden on mobile via CSS) -->
            <div class="mc-time">
                <span class="time-val"><?php echo htmlspecialchars($formattedTime); ?></span>
                <?php if ($formattedDate): ?>
                <span class="date-val"><?php echo htmlspecialchars($formattedDate); ?></span>
                <?php endif; ?>
            </div>

            <!-- Match Column - Desktop shows VS, Mobile shows score -->
            <div class="mc-match">
                <span class="league-tag">
                    <?php echo htmlspecialchars($leagueCountry ? $leagueCountry . ' · ' . $leagueFull : $leagueFull); ?>
                </span>
                <div class="teams-inline">
                    <!-- Home team section - fixed position on left -->
                    <div class="team-home">
                        <div class="team-crest home-crest"><?php echo $homeInitial; ?></div>
                        <span class="team-name-text home-name"><?php echo htmlspecialchars($tip['home_team_name'] ?? ''); ?></span>
                    </div>
                    
                    <!-- VS badge - centered -->
                    <div class="vs-container">
                        <?php if ($hasScore && $statusShort !== '' && $statusShort !== 'NS'): ?>
                            <!-- Show score on mobile (VS hidden on mobile via CSS) -->    
                            <div class="score-stack">
                                <?php if ($winningStatus !== ''): ?>
                                    <span class="result-badge-small mb-2 <?php echo ($winningStatus === 'Won') ? 'result-won' : 'result-lost'; ?>">
                                        <?php echo $winningStatus; ?>
                                    </span>
                                <?php endif; ?>

                                <span class="vs-badge vs-badge--score">
                                    <?php echo htmlspecialchars($homeScore . ' - ' . $awayScore); ?>
                                </span>
                            </div>
                            <!-- VS badge (hidden on mobile via CSS) -->
                            <span class="vs-badge vs-badge--desktop" style="text-align:center;">VS</span>
                        <?php else: ?>
                            <!-- No score yet, show VS and time -->
                            <span class="vs-badge vs-badge--desktop" style="text-align:center;">VS</span>
                            <span class="vs-badge vs-badge--score"><?php echo htmlspecialchars($formattedTime); ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Away team section - fixed position on right -->
                     <div class="team-home">
                        <div class="team-crest home-crest"><?php echo $awayInitial; ?></div>
                        <span class="team-name-text home-name"><?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Prediction Column -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
            </div>

            <!-- Probability Rings Column -->
            <div class="mc-prob">
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 40 40">
                            <circle class="track" cx="20" cy="20" r="17"/>
                            <circle class="fill-home" cx="20" cy="20" r="17"
                                stroke-dasharray="<?php echo $dashHome; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $homePercent; ?></div>
                    </div>
                    <span class="prob-label">Home</span>
                </div>
                <div class="prob-sep"></div>
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 40 40">
                            <circle class="track" cx="20" cy="20" r="17"/>
                            <circle class="fill-draw" cx="20" cy="20" r="17"
                                stroke-dasharray="<?php echo $dashDraw; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $drawPercent; ?></div>
                    </div>
                    <span class="prob-label">Draw</span>
                </div>
                <div class="prob-sep"></div>
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 40 40">
                            <circle class="track" cx="20" cy="20" r="17"/>
                            <circle class="fill-away" cx="20" cy="20" r="17"
                                stroke-dasharray="<?php echo $dashAway; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $awayPercent; ?></div>
                    </div>
                    <span class="prob-label">Away</span>
                </div>
            </div>

            <!-- Odds Column -->
            <div class="mc-odds">
                <div class="odds-value"><?php echo htmlspecialchars($oddsDisplay); ?></div>
                <div class="odds-label">Odds</div>
            </div>

             <!-- Col 6: Score -->
            <div class="mc-score">
                <div class="score-status"><?php echo $matchStatus; ?></div>
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                 <?php if ($winningStatus !== ''): ?>
                <span class="result-badge-small <?php echo ($winningStatus === 'Won') ? 'result-won' : 'result-lost'; ?>">
                    <?php echo $winningStatus; ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>    

    <!-- SEO Content -->
    <section class="seo-section">
        <div class="blog-2 seo-content">
            <?php echo $htmlContent; ?>
        </div>
    </section>
</main>

<?php
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
