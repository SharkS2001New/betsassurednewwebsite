<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>BTTS Predictions Today - Both Teams to Score Tips | Betnumbers</title>
<meta name="title" content="BTTS Predictions Today - Both Teams to Score Tips | Betnumbers">
<meta name="description" content="Get free BTTS predictions today on Betnumbers. Discover both teams to score tips with confidence ratings, odds analysis, and expert football insights updated daily.">
<meta name="keywords" content="btts predictions, both teams to score tips, btts tips today, soccer btts predictions, football betting tips btts, betnumbers predictions">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="BTTS Predictions Today - Both Teams to Score Tips | Betnumbers">
<meta property="og:description" content="Free BTTS predictions with confidence ratings and betting insights. Updated daily on Betnumbers.">
<meta property="og:url" content="https://www.betnumbers.com/btts-predictions">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="BTTS Predictions Today - Both Teams to Score Tips | Betnumbers">
<meta name="twitter:description" content="Get today's best BTTS predictions with expert analysis and confidence ratings on Betnumbers.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/both-teams-to-score.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_btts_matches_fixtures";
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
 * Calculate BTTS confidence based on multiple factors including odds
 * Confidence is always between 50-95%
 */
function calculateBttsConfidence($tip, $prediction, $odd) {
    // Get percentages
    $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
    $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
    $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');
    
    // Get team performance data
    $homeFor = floatval($tip['teams_perfomance_home_for'] ?? 0);
    $homeAgainst = floatval($tip['teams_perfomance_home_aganist'] ?? $tip['teams_perfomance_home_against'] ?? 0);
    $awayFor = floatval($tip['teams_perfomance_away_for'] ?? 0);
    $awayAgainst = floatval($tip['teams_perfomance_away_aganist'] ?? $tip['teams_perfomance_away_against'] ?? 0);
    $homeGames = floatval($tip['teams_games_played_home'] ?? 1);
    $awayGames = floatval($tip['teams_games_played_away'] ?? 1);
    
    if ($homeGames <= 0) $homeGames = 1;
    if ($awayGames <= 0) $awayGames = 1;
    
    $homeAvgScored = $homeFor / $homeGames;
    $homeAvgConceded = $homeAgainst / $homeGames;
    $awayAvgScored = $awayFor / $awayGames;
    $awayAvgConceded = $awayAgainst / $awayGames;
    
    // Calculate average goals per game
    $avgTotalGoals = ($homeFor + $homeAgainst + $awayFor + $awayAgainst) / ($homeGames + $awayGames);
    
    // Start with base confidence (always at least 50)
    $confidence = 60;
    
    if ($prediction === 'Yes') {
        // Factor 1: Based on 1X2 percentages
        // Higher draw percentage suggests BTTS
        $drawFactor = $drawPercent * 0.3;
        
        // More balanced percentages suggest both teams can score
        $balanceFactor = 100 - (abs($homePercent - $awayPercent) * 0.4);
        
        // Factor 2: Based on average goals
        $goalsFactor = min(25, $avgTotalGoals * 8);
        
        // Factor 3: Based on team scoring/conceding
        $scoringFactor = 0;
        if ($homeAvgScored > 1.2) $scoringFactor += 5;
        if ($homeAvgConceded > 1) $scoringFactor += 5;
        if ($awayAvgScored > 1.2) $scoringFactor += 5;
        if ($awayAvgConceded > 1) $scoringFactor += 5;
        
        // Factor 4: Based on odds (lower odds = higher confidence)
        $oddsFactor = 0;
        if (is_numeric($odd) && $odd > 0) {
            // Odds between 1.30-2.00 give confidence boost
            if ($odd <= 1.50) {
                $oddsFactor = 15;
            } elseif ($odd <= 1.70) {
                $oddsFactor = 10;
            } elseif ($odd <= 2.00) {
                $oddsFactor = 5;
            }
        }
        
        $confidence = $drawFactor + $balanceFactor/2 + $goalsFactor + $scoringFactor + $oddsFactor;
        
    } else {
        // For BTTS No
        // Factor 1: Dominant team (high home or away percentage)
        $maxPercent = max($homePercent, $awayPercent);
        $dominanceFactor = $maxPercent * 0.4;
        
        // Factor 2: Low average goals
        $goalsFactor = max(0, 20 - ($avgTotalGoals * 5));
        
        // Factor 3: Defensive strength
        $defenseFactor = 0;
        if ($homeAvgConceded < 0.8) $defenseFactor += 8;
        if ($awayAvgConceded < 0.8) $defenseFactor += 8;
        
        // Factor 4: Odds factor (lower odds for BTTS No = higher confidence)
        $oddsFactor = 0;
        if (is_numeric($odd) && $odd > 0) {
            if ($odd <= 1.60) {
                $oddsFactor = 15;
            } elseif ($odd <= 1.80) {
                $oddsFactor = 10;
            } elseif ($odd <= 2.10) {
                $oddsFactor = 5;
            }
        }
        
        $confidence = $dominanceFactor + $goalsFactor + $defenseFactor + $oddsFactor + 10;
    }
    
    // Ensure confidence is between 50-95
    return min(95, max(50, round($confidence)));
}

/**
 * Find BTTS odd from all_bets_odds
 */
function findBttsOdd($allBets) {
    if (empty($allBets)) return null;
    
    foreach ($allBets as $market) {
        if ($market['name'] === "Both Teams Score" && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === "Yes") {
                    return floatval($bet['odd']);
                }
            }
        }
    }
    return null;
}

/**
 * Find BTTS No odd from all_bets_odds
 */
function findBttsNoOdd($allBets) {
    if (empty($allBets)) return null;
    
    foreach ($allBets as $market) {
        if ($market['name'] === "Both Teams Score" && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === "No") {
                    return floatval($bet['odd']);
                }
            }
        }
    }
    return null;
}

/**
 * Get chip class based on BTTS prediction
 */
function getBttsChipClass($prediction) {
    if ($prediction === "Yes") {
        return 'chip-btts';
    } else {
        return 'chip-btts-no';
    }
}

/**
 * Get display text based on BTTS prediction
 */
function getBttsDisplayText($prediction) {
    if ($prediction === "Yes") {
        return 'BTTS Yes';
    } else {
        return 'BTTS No';
    }
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
/* Match the exact styles from the site */
.section-title-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 30px 0 15px;
}

.section-title-bar h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

.today-date-tag {
    background: #f0f0f0;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    color: #333;
}

/* Stats bar for BTTS */
.btts-stats-bar {
    display: flex;
    gap: 20px;
    margin: 15px 0 20px;
    padding: 15px 20px;
    background: linear-gradient(135deg, #05384B 0%, #0a4a60 100%);
    border-radius: 10px;
    color: white;
}

.stat-item {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 22px;
    font-weight: 700;
    line-height: 1.2;
}

.stat-label {
    font-size: 12px;
    opacity: 0.9;
}

/* Table headers */
.preds-table-header {
    display: grid;
    grid-template-columns: 8% 30% 10% 15% 12% 18%;
    gap: 8px;
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    color: #495057;
    border: 1px solid #dee2e6;
    border-bottom: none;
    font-size: 14px;
}

/* Match cards */
.preds-wrapper {
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 8px 8px;
    overflow: hidden;
    margin-bottom: 30px;
}

.match-card {
    display: grid;
    grid-template-columns: 8% 30% 10% 15% 12% 18%;
    gap: 8px;
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    background: white;
    align-items: center;
    font-family: 'DM Sans', sans-serif;
}

.match-card:last-child {
    border-bottom: none;
}

.match-card:hover {
    background: #f8f9fa;
}

/* Time column */
.mc-time {
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.mc-time .date-small {
    font-size: 11px;
    color: #6c757d;
    display: block;
}

/* Match column */
.mc-match {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.league-tag {
    font-size: 11px;
    font-weight: 500;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.teams-inline {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.team-crest {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #05384B, #0a4a60);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 12px;
    color: white;
    text-transform: uppercase;
    flex-shrink: 0;
}

.home-crest {
    background: linear-gradient(135deg, #05384B, #0a4a60);
}

.team-name-text {
    font-weight: 500;
    font-size: 14px;
    color: #212529;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.vs-badge {
    color: #dc3545;
    font-weight: 600;
    font-size: 12px;
    margin: 0 2px;
    flex-shrink: 0;
}

/* Odds column */
.mc-odds {
    text-align: center;
}

.odds-value {
    font-weight: 700;
    font-size: 16px;
    color: #f59e0b;
    background: rgba(251,191,36,.08);
    border: 1px solid rgba(251,191,36,.2);
    border-radius: 6px;
    padding: 4px 8px;
    display: inline-block;
    line-height: 1;
}

/* Probability rings */
.mc-prob {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.prob-item {
    text-align: center;
    flex: 1;
}

.prob-ring {
    position: relative;
    width: 45px;
    height: 45px;
    margin: 0 auto 4px;
}

.prob-ring svg {
    width: 45px;
    height: 45px;
    transform: rotate(-90deg);
}

.prob-ring circle {
    fill: none;
    stroke-width: 3;
    cx: 22.5;
    cy: 22.5;
    r: 19;
}

.prob-ring .track {
    stroke: #e9ecef;
}

/* BTTS Yes color - Purple */
.prob-ring .fill-btts-yes {
    stroke: #8b5cf6;
    stroke-linecap: round;
}

/* BTTS No color - Orange */
.prob-ring .fill-btts-no {
    stroke: #f59e0b;
    stroke-linecap: round;
}

.prob-ring-value {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 11px;
    font-weight: 700;
    font-family: 'DM Mono', monospace;
}

/* Prediction chip */
.mc-prediction {
    text-align: center;
}

.pred-chip {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    min-width: 75px;
    text-align: center;
}

.chip-btts {
    background: #8b5cf6;
    color: white;
}

.chip-btts-no {
    background: #f59e0b;
    color: white;
}

/* Score column */
.mc-score {
    text-align: center;
}

.score-display {
    font-weight: 700;
    font-size: 16px;
    color: #212529;
    font-family: 'DM Mono', monospace;
}

.score-status {
    font-size: 10px;
    color: #10b981;
    text-transform: uppercase;
    font-weight: 600;
}

.score-status.upcoming {
    color: #f59e0b;
}

/* Result badge */
.result-badge-small {
    font-size: 10px;
    font-weight: 600;
    display: block;
    margin-top: 2px;
}

.result-won {
    color: #10b981;
}

.result-lost {
    color: #dc3545;
}

/* State messages */
.state-msg {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

/* Performance summary */
.perf-summary {
    display: flex;
    gap: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 20px 0 30px;
    border: 1px solid #dee2e6;
}

.perf-item {
    display: flex;
    flex-direction: column;
}

.perf-label {
    font-size: 13px;
    color: #6c757d;
}

.perf-value {
    font-size: 24px;
    font-weight: 700;
    color: #05384B;
}

/* Confidence indicator */
.confidence-high {
    color: #10b981;
    font-weight: 600;
}

.confidence-medium {
    color: #f59e0b;
    font-weight: 600;
}

.confidence-low {
    color: #6c757d;
    font-weight: 600;
}

/* Tips box */
.tips-box {
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    padding: 20px;
    margin: 30px 0;
}

.tips-box h3 {
    font-size: 18px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 10px;
}

.tips-box p {
    color: #92400e;
    margin-bottom: 5px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tips-box p:before {
    content: "•";
    font-weight: 700;
    font-size: 18px;
}

/* Responsive */
@media (max-width: 992px) {
    .preds-table-header {
        display: none;
    }
    
    .match-card {
        grid-template-columns: 1fr;
        gap: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .mc-time {
        font-weight: 600;
    }
}
</style>

<main class="container py-1">
    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Both Teams to Score (BTTS) Predictions</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>BTTS predictions</strong> show whether both teams are expected to score (<span style="color: #8b5cf6; font-weight: 600;">BTTS Yes</span>) or not (<span style="color: #f59e0b; font-weight: 600;">BTTS No</span>) in each match. Confidence percentages (50-95%) are calculated using team performance, 1X2 probabilities, and current odds.
    </p>

    <!-- BTTS Stats Bar -->
    <?php 
    $yesCount = 0;
    $noCount = 0;
    foreach ($tipsData as $tip) {
        if (isset($tip['both_team_to_score']) && $tip['both_team_to_score'] === 'Yes') {
            $yesCount++;
        } elseif (isset($tip['both_team_to_score']) && $tip['both_team_to_score'] === 'No') {
            $noCount++;
        }
    }
    ?>
    <div class="btts-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo count($tipsData); ?></span>
            <span class="stat-label">Total Matches</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #8b5cf6;"><?php echo $yesCount; ?></span>
            <span class="stat-label">BTTS Yes</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #f59e0b;"><?php echo $noCount; ?></span>
            <span class="stat-label">BTTS No</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">50-95%</span>
            <span class="stat-label">Confidence Range</span>
        </div>
    </div>

    <!-- Column Headers -->
    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Confidence</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Score</span>
    </div>

    <!-- Predictions Wrapper -->
    <div class="preds-wrapper">
        <?php if ($error): ?>
            <div class="state-msg">
                Our experts are working on the predictions — please check back in a few minutes!
            </div>
        <?php elseif ($empty || empty($tipsData)): ?>
            <div class="state-msg">
                No BTTS predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php 
        foreach ($tipsData as $tip):
            
            // Get BTTS prediction from API
            $bttsPrediction = $tip['both_team_to_score'] ?? 'Yes';
            
            // Get appropriate odds
            $bttsOdd = '—';
            if (!empty($tip['all_bets_odds'])) {
                try {
                    $oddsData = json_decode($tip['all_bets_odds'], true);
                    if (is_array($oddsData)) {
                        if ($bttsPrediction === 'Yes') {
                            $bttsOdd = findBttsOdd($oddsData) ?: '—';
                        } else {
                            $bttsOdd = findBttsNoOdd($oddsData) ?: '—';
                        }
                    }
                } catch (Exception $e) {
                    // Keep default
                }
            }
            
            // Calculate BTTS confidence based on multiple factors including odds
            $bttsConfidence = calculateBttsConfidence($tip, $bttsPrediction, $bttsOdd);
            
            // Get match scores
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $scoreDisplay = '—';
            $matchStatus = 'UPCOMING';
            $statusClass = 'upcoming';
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $matchStatus = 'FT';
                $statusClass = '';
            }
            
            // Determine if BTTS happened (for finished matches)
            $bttsResult = '';
            $bttsResultClass = '';
            if ($scoreDisplay !== '—') {
                if ($homeScore > 0 && $awayScore > 0) {
                    $bttsResult = '✅ BTTS';
                    $bttsResultClass = 'result-won';
                } else {
                    $bttsResult = '❌ No BTTS';
                    $bttsResultClass = 'result-lost';
                }
            }
            
            // Format time
            $formattedTime = '—';
            if (!empty($tip['date'])) {
                $dateTime = DateTimeToUsersTimezone($tip['date']);
                // Extract just time part if full datetime
                if (strpos($dateTime, ' ') !== false) {
                    $dateTime = explode(' ', $dateTime)[1];
                }
                $formattedTime = $dateTime;
            }
            
            // Team initials
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));
            
            // League info
            $leagueFull = $tip['league_name'] ?? '';
            
            // SVG ring calculation
            $circ = 119.38; // 2πr with r=19
            $dashValue = round(($bttsConfidence / 100) * $circ, 2);
            
            // Determine fill class based on prediction
            $fillClass = ($bttsPrediction === 'Yes') ? 'fill-btts-yes' : 'fill-btts-no';
            
            // Chip class and display text
            $chipClass = getBttsChipClass($bttsPrediction);
            $displayText = getBttsDisplayText($bttsPrediction);
            
            // Confidence class for text color
            $confidenceClass = 'confidence-medium';
            if ($bttsConfidence >= 80) {
                $confidenceClass = 'confidence-high';
            } elseif ($bttsConfidence <= 60) {
                $confidenceClass = 'confidence-low';
            }
        ?>

        <div class="match-card">
            <!-- Time -->
            <div class="mc-time">
                <span><?php echo htmlspecialchars($formattedTime); ?></span>
            </div>

            <!-- Match -->
            <div class="mc-match">
                <span class="league-tag"><?php echo htmlspecialchars($leagueFull); ?></span>
                <div class="teams-inline">
                    <div class="team-crest home-crest"><?php echo $homeInitial; ?></div>
                    <span class="team-name-text"><?php echo htmlspecialchars($tip['home_team_name'] ?? ''); ?></span>
                    <span class="vs-badge">VS</span>
                    <div class="team-crest"><?php echo $awayInitial; ?></div>
                    <span class="team-name-text"><?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?></span>
                </div>
            </div>

            <!-- BTTS Odds -->
            <div class="mc-odds">
                <div class="odds-value"><?php echo is_numeric($bttsOdd) ? number_format($bttsOdd, 2) : $bttsOdd; ?></div>
            </div>

            <!-- Confidence Ring - Now shows unique confidence per match (50-95%) -->
            <div class="mc-prob">
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 45 45">
                            <circle class="track" cx="22.5" cy="22.5" r="19"/>
                            <circle class="<?php echo $fillClass; ?>" cx="22.5" cy="22.5" r="19"
                                stroke-dasharray="<?php echo $dashValue; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value <?php echo $confidenceClass; ?>"><?php echo $bttsConfidence; ?>%</div>
                    </div>
                </div>
            </div>

            <!-- Prediction -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo $displayText; ?>
                </span>
            </div>

            <!-- Score with BTTS result indicator -->
            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <?php if ($scoreDisplay !== '—'): ?>
                    <div class="score-status"><?php echo $matchStatus; ?></div>
                    <div class="<?php echo $bttsResultClass; ?>" style="font-size: 10px; margin-top: 2px;"><?php echo $bttsResult; ?></div>
                <?php else: ?>
                    <div class="score-status upcoming"><?php echo $matchStatus; ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- BTTS Performance Summary -->
    <?php 
    if (!empty($tipsData)):
        $bttsHits = 0;
        $bttsMisses = 0;
        $bttsCorrect = 0;
        $bttsTotal = 0;
        
        foreach ($tipsData as $tip) {
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $prediction = $tip['both_team_to_score'] ?? 'Yes';
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $bttsTotal++;
                $actualBtts = ($homeScore > 0 && $awayScore > 0) ? 'Yes' : 'No';
                if ($actualBtts === $prediction) {
                    $bttsCorrect++;
                }
                
                if ($actualBtts === 'Yes') {
                    $bttsHits++;
                } else {
                    $bttsMisses++;
                }
            }
        }
        
        if ($bttsTotal > 0):
    ?>
    <div class="perf-summary">
        <div class="perf-item">
            <span class="perf-label">BTTS Hits</span>
            <span class="perf-value"><?php echo $bttsHits; ?>/<?php echo $bttsTotal; ?></span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Correct Predictions</span>
            <span class="perf-value"><?php echo $bttsCorrect; ?>/<?php echo $bttsTotal; ?></span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Success Rate</span>
            <span class="perf-value"><?php echo round(($bttsCorrect/$bttsTotal)*100); ?>%</span>
        </div>
    </div>
    <?php endif; endif; ?>
    
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