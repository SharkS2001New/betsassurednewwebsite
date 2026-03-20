<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Over Under 2.5 Goals Predictions Today | Total Goals Tips</title>
<meta name="title" content="Over Under 2.5 Goals Predictions Today | Total Goals Tips">
<meta name="description" content="Free Over/Under 2.5 goals predictions today. Expert total goals tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.">
<meta name="keywords" content="over 2.5 goals predictions today, under 2.5 goals tips, over under 2.5 football predictions, total goals tips today, 2.5 goals betting tips, over 2.5 tips, under 2.5 predictions, goals betting today">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Over Under 2.5 Goals Predictions Today | Total Goals Tips">
<meta property="og:description" content="Free Over/Under 2.5 goals predictions today. Expert total goals tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.">
<meta property="og:url" content="https://www.betsassured.com/over-under-2-5-goals">
<meta property="og:site_name" content="Betsassured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Over Under 2.5 Goals Predictions Today | Total Goals Tips">
<meta name="twitter:description" content="Free Over/Under 2.5 goals predictions today. Expert total goals tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "What are high confidence football predictions?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "High confidence predictions are football tips with statistical probability ratings of 70% or higher, based on comprehensive data analysis including team form, head-to-head records, and tactical matchups."
      }
    },
    {
      "@type": "Question",
      "name": "How accurate are high confidence predictions?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Our high confidence predictions (90%+ probability) achieve approximately 78% accuracy based on verified historical results. However, no prediction is guaranteed and all betting carries risk."
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/over-under-25-goals.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_under_over25_free_winning_tips";
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
 * Get Over/Under 2.5 prediction based on average goals
 */
function getOverUnderPrediction($tip) {
    $avgGoals = floatval($tip['average_goals'] ?? 0);
    if ($avgGoals > 2.4) {
        return ['prediction' => 'Over 2.5', 'display' => 'Over 2.5', 'chipClass' => 'chip-over', 'type' => 'over'];
    } else {
        return ['prediction' => 'Under 2.5', 'display' => 'Under 2.5', 'chipClass' => 'chip-under', 'type' => 'under'];
    }
}

/**
 * Calculate confidence for Over/Under 2.5 based on average goals
 */
function calculateOverUnderConfidence($tip, $prediction) {
    $avgGoals = floatval($tip['average_goals'] ?? 0);
    $confidence = 65;

    if ($prediction === 'Over 2.5') {
        if ($avgGoals > 3.0)      $confidence = 90;
        elseif ($avgGoals > 2.7)  $confidence = 85;
        elseif ($avgGoals > 2.5)  $confidence = 80;
        elseif ($avgGoals > 2.3)  $confidence = 72;
        $confidence += min(10, ($avgGoals - 2.3) * 12);
    } else {
        if ($avgGoals < 1.2)      $confidence = 90;
        elseif ($avgGoals < 1.4)  $confidence = 85;
        elseif ($avgGoals < 1.6)  $confidence = 78;
        elseif ($avgGoals < 1.7)  $confidence = 72;
        $confidence += min(10, (1.7 - $avgGoals) * 15);
    }

    return min(95, max(50, round($confidence)));
}

/**
 * Find Over/Under 2.5 odds from goals_over_under JSON
 */
function findOverUnder25Odd($goalsOverUnder, $value) {
    if (empty($goalsOverUnder)) return null;
    try {
        $oddsData = json_decode($goalsOverUnder, true);
        if (is_array($oddsData)) {
            foreach ($oddsData as $bet) {
                if (isset($bet['value']) && $bet['value'] === $value) {
                    return floatval($bet['odd']);
                }
            }
        }
    } catch (Exception $e) {
        return null;
    }
    return null;
}

// Filter tips to only include those with clear Over/Under 2.5 predictions
$filteredTips = [];
foreach ($tipsData as $tip) {
    $predData = getOverUnderPrediction($tip);
    if ($predData !== null) {
        $filteredTips[] = ['tip' => $tip, 'predData' => $predData];
    }
}
?>

<main class="container">
    <h1 class="page-hero-title">Over Under 2.5 Goals Predictions Today | Total Goals Tips</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Today's Over/Under 2.5 Goals Predictions</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>Over/Under 2.5 goals predictions</strong> show whether the total goals in a match will be <span style="color: #10b981; font-weight: 600;">Over 2.5</span> (3 or more goals) or <span style="color: #f59e0b; font-weight: 600;">Under 2.5</span> (0, 1, or 2 goals). Each tip includes a confidence rating based on average goals per game data to guide your betting decisions.
    </p>

    <!-- Stats Bar -->
    <?php
    $overCount  = 0;
    $underCount = 0;
    foreach ($filteredTips as $item) {
        if ($item['predData']['type'] === 'over') $overCount++;
        else $underCount++;
    }
    ?>
    <div class="ou-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo count($filteredTips); ?></span>
            <span class="stat-label">Total Picks</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #6ee7b7;"><?php echo $overCount; ?></span>
            <span class="stat-label">Over 2.5 Tips</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #fcd34d;"><?php echo $underCount; ?></span>
            <span class="stat-label">Under 2.5 Tips</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">⚽⚽⚽</span>
            <span class="stat-label">3+ Goals Market</span>
        </div>
    </div>

    <!-- Column Headers -->
    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Confidence</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Score</span>
    </div>

    <!-- Predictions Wrapper -->
    <div class="preds-wrapper">
        <?php if ($error): ?>
            <div class="state-msg">
                Our analysts are working on today's Over/Under 2.5 predictions — please check back in a few minutes!
            </div>
        <?php elseif ($empty || empty($filteredTips)): ?>
            <div class="state-msg">
                No Over/Under 2.5 goals predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php foreach ($filteredTips as $item):
            $tip               = $item['tip'];
            $predData          = $item['predData'];
            $prediction        = $predData['prediction'];
            $displayPrediction = $predData['display'];
            $chipClass         = $predData['chipClass'];
            $type              = $predData['type'];

            $confidence = calculateOverUnderConfidence($tip, $prediction);

            // Odds
            $oddsDisplay = '—';
            if (!empty($tip['goals_over_under'])) {
                $odd = findOverUnder25Odd($tip['goals_over_under'], $prediction);
                if ($odd) $oddsDisplay = number_format($odd, 2);
            }

            // Scores
            $homeScore     = $tip['goals_home'] ?? null;
            $awayScore     = $tip['goals_away'] ?? null;
            $scoreDisplay  = '—';
            $matchStatus   = 'UPCOMING';
            $winningStatus = '';

            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay  = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $matchStatus   = $tip['status_short'] ?? null;
                $totalGoals    = $homeScore + $awayScore;
                $actualResult  = ($totalGoals >= 3) ? 'Over 2.5' : 'Under 2.5';
                $winningStatus = ($actualResult === $prediction) ? 'Won' : 'Lost';
            }

            $avgGoals = floatval($tip['average_goals'] ?? 0);

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

            $circ      = 119.38;
            $dashValue = round(($confidence / 100) * $circ, 2);
            $fillClass = ($type === 'over') ? 'fill-over' : 'fill-under';
            $circ      = 119.38;
            $dashValue = round(($confidence / 100) * $circ, 2);
            $fillClass = ($type === 'over') ? 'fill-over' : 'fill-under';
        ?>

       <div class="match-card">
            <div class="mc-time">
                <span><?php echo htmlspecialchars($formattedTime); ?></span>
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

            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
            </div>

            <div class="mc-prob">
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 40 40">
                            <circle class="track" cx="20" cy="20" r="17"/>
                            <circle class="fill-home <?php echo $fillClass; ?>" cx="20" cy="20" r="17"
                                stroke-dasharray="<?php echo $dashValue; ?> <?php echo $circ; ?>"/>
                        </svg>
                         <div class="prob-ring-value <?php echo $confidenceClass; ?>"><?php echo $confidence; ?>%</div>
                    </div>
                </div>
            </div>

            <div class="mc-odds">
                <div class="odds-value"><?php echo $oddsDisplay; ?></div>
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
