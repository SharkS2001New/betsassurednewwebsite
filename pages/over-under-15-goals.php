<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Over/Under 1.5 Goals Predictions Today - Total Goals Tips | Betsassured</title>
<meta name="title" content="Over/Under 1.5 Goals Predictions - Total Goals Tips">
<meta name="description" content="Free Over/Under 1.5 goals predictions today. Total goals tips with confidence ratings and odds. Best over/under tips for football betting.">
<meta name="keywords" content="over 1.5 goals, under 1.5 goals, total goals predictions, over under tips, football goals predictions">

<!-- Open Graph -->
<meta property="og:title" content="Over/Under 1.5 Goals Predictions Today">
<meta property="og:description" content="Free Over/Under 1.5 goals predictions with confidence ratings and odds. Updated daily.">

<!-- Twitter -->
<meta property="twitter:title" content="Over/Under 1.5 Goals Predictions Today">
<meta property="twitter:description" content="Free Over/Under 1.5 goals predictions with confidence ratings and odds. Updated daily.">
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/over-under-15-goals.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_under_over15_free_winning_tips";
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
 * Get Over/Under 1.5 prediction based on average goals
 */
function getOverUnderPrediction($tip) {
    $avgGoals = floatval($tip['average_goals'] ?? 0);
    
    // Determine prediction based on average goals
    if ($avgGoals > 1.6) {
        return [
            'prediction' => 'Over 1.5',
            'display' => 'Over 1.5',
            'chipClass' => 'chip-over',
            'type' => 'over'
        ];
    } else {
        return [
            'prediction' => 'Under 1.5',
            'display' => 'Under 1.5',
            'chipClass' => 'chip-under',
            'type' => 'under'
        ];
    }
}

/**
 * Calculate confidence for Over/Under 1.5 based on average goals
 */
function calculateOverUnderConfidence($tip, $prediction) {
    $avgGoals = floatval($tip['average_goals'] ?? 0);
    
    // Base confidence
    $confidence = 65;
    
    if ($prediction === 'Over 1.5') {
        // Higher average goals = higher confidence for Over 1.5
        if ($avgGoals > 2.0) {
            $confidence = 85;
        } elseif ($avgGoals > 1.8) {
            $confidence = 78;
        } elseif ($avgGoals > 1.6) {
            $confidence = 72;
        }
        
        // Boost based on how far above threshold
        $confidence += min(10, ($avgGoals - 1.5) * 15);
        
    } else { // Under 1.5
        // Lower average goals = higher confidence for Under 1.5
        if ($avgGoals < 1.2) {
            $confidence = 85;
        } elseif ($avgGoals < 1.4) {
            $confidence = 78;
        } elseif ($avgGoals < 1.6) {
            $confidence = 72;
        }
        
        // Boost based on how far below threshold
        $confidence += min(10, (1.5 - $avgGoals) * 15);
    }
    
    // Ensure confidence is between 50-95
    return min(95, max(50, round($confidence)));
}

/**
 * Find Over/Under 1.5 odds from goals_over_under JSON
 */
function findOverUnderOdd($goalsOverUnder, $value) {
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

/* Stats bar for Over/Under */
.ou-stats-bar {
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

/* Confidence ring */
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

/* Over 1.5 color - Green */
.prob-ring .fill-over {
    stroke: #10b981;
    stroke-linecap: round;
}

/* Under 1.5 color - Orange */
.prob-ring .fill-under {
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

.chip-over {
    background: #10b981;
    color: white;
}

.chip-under {
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
        <h2>Over/Under 1.5 Goals Predictions</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>Over/Under 1.5 goals predictions</strong> show whether the total goals in a match will be <span style="color: #10b981; font-weight: 600;">Over 1.5</span> (2 or more goals) or <span style="color: #f59e0b; font-weight: 600;">Under 1.5</span> (0 or 1 goal). Predictions are based on average goals per game statistics.
    </p>

    <!-- Stats Bar -->
    <?php 
    $overCount = 0;
    $underCount = 0;
    
    foreach ($tipsData as $tip) {
        $avgGoals = floatval($tip['average_goals'] ?? 0);
        if ($avgGoals > 1.6) {
            $overCount++;
        } else {
            $underCount++;
        }
    }
    ?>
    <div class="ou-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo count($tipsData); ?></span>
            <span class="stat-label">Total Matches</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #10b981;"><?php echo $overCount; ?></span>
            <span class="stat-label">Over 1.5</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #f59e0b;"><?php echo $underCount; ?></span>
            <span class="stat-label">Under 1.5</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">⚽</span>
            <span class="stat-label">Total Goals</span>
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
                No Over/Under 1.5 predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php 
        $index = 1;
        foreach ($tipsData as $tip):
            
            // Get Over/Under prediction based on average goals
            $predData = getOverUnderPrediction($tip);
            $prediction = $predData['prediction'];
            $displayPrediction = $predData['display'];
            $chipClass = $predData['chipClass'];
            $type = $predData['type'];
            
            // Calculate confidence based on average goals
            $confidence = calculateOverUnderConfidence($tip, $prediction);
            
            // Get odds from goals_over_under field
            $oddsDisplay = '—';
            if (!empty($tip['goals_over_under'])) {
                $oddsDisplay = findOverUnderOdd($tip['goals_over_under'], $prediction);
                if ($oddsDisplay) {
                    $oddsDisplay = number_format($oddsDisplay, 2);
                }
            }
            
            // Get scores
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $scoreDisplay = '—';
            $matchStatus = 'UPCOMING';
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $matchStatus = 'FT';
                
                // Determine if Over/Under 1.5 was correct
                $totalGoals = $homeScore + $awayScore;
                $actualResult = ($totalGoals >= 2) ? 'Over 1.5' : 'Under 1.5';
                $winningStatus = ($actualResult === $prediction) ? 'Won' : 'Lost';
            }
            
            // Get average goals for display
            $avgGoals = floatval($tip['average_goals'] ?? 0);
            
            // Team initials
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));
            
            // League info
            $leagueFull = $tip['league_name'] ?? '';
            
            // Format time
            $formattedTime = '—';
            if (!empty($tip['date'])) {
                $dateParts = explode(' ', $tip['date']);
                if (count($dateParts) >= 2) {
                    $formattedTime = $dateParts[1];
                }
            }
            
            // SVG ring calculation
            $circ = 119.38;
            $dashValue = round(($confidence / 100) * $circ, 2);
            $fillClass = ($type === 'over') ? 'fill-over' : 'fill-under';
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
                <div style="font-size: 10px; color: #6c757d; margin-top: 2px;">Avg: <?php echo number_format($avgGoals, 2); ?> goals/match</div>
            </div>

            <!-- Odds -->
            <div class="mc-odds">
                <div class="odds-value"><?php echo $oddsDisplay ?: '—'; ?></div>
            </div>

            <!-- Confidence Ring -->
            <div class="mc-prob">
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 45 45">
                            <circle class="track" cx="22.5" cy="22.5" r="19"/>
                            <circle class="<?php echo $fillClass; ?>" cx="22.5" cy="22.5" r="19"
                                stroke-dasharray="<?php echo $dashValue; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $confidence; ?>%</div>
                    </div>
                </div>
            </div>

            <!-- Prediction -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
                <?php if ($scoreDisplay !== '—'): ?>
                <span class="result-badge-small <?php echo ($winningStatus === 'Won') ? 'result-won' : 'result-lost'; ?>">
                    <?php echo $winningStatus; ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Score -->
            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <?php if ($scoreDisplay !== '—'): ?>
                <div class="score-status"><?php echo $matchStatus; ?></div>
                <?php else: ?>
                <div class="score-status upcoming"><?php echo $matchStatus; ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- Performance Summary -->
    <?php 
    if (!empty($tipsData)):
        $won = 0;
        $lost = 0;
        $totalFinished = 0;
        
        foreach ($tipsData as $tip) {
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $avgGoals = floatval($tip['average_goals'] ?? 0);
            $prediction = ($avgGoals > 1.6) ? 'Over 1.5' : 'Under 1.5';
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $totalFinished++;
                $totalGoals = $homeScore + $awayScore;
                $actualResult = ($totalGoals >= 2) ? 'Over 1.5' : 'Under 1.5';
                if ($actualResult === $prediction) {
                    $won++;
                } else {
                    $lost++;
                }
            }
        }
        
        if ($totalFinished > 0):
    ?>
    <div class="perf-summary">
        <div class="perf-item">
            <span class="perf-label">Correct Tips</span>
            <span class="perf-value"><?php echo $won; ?>/<?php echo $totalFinished; ?></span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Success Rate</span>
            <span class="perf-value"><?php echo round(($won/$totalFinished)*100); ?>%</span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Total Matches</span>
            <span class="perf-value"><?php echo count($tipsData); ?></span>
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