<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Home Win Predictions Today - 1X2 Football Tips</title>
<meta name="title" content="Home Win Predictions - 1X2 Football Tips">
<meta name="description" content="Free home win predictions today. 1X2 football tips with confidence ratings and odds. Best home win tips for football betting.">
<meta name="keywords" content="home win predictions, 1x2 tips, football predictions, home win tips, 1x2 betting, soccer home wins">

<!-- Open Graph -->
<meta property="og:title" content="Home Win Predictions Today - 1X2 Football Tips">
<meta property="og:description" content="Free home win predictions with confidence ratings and odds. Updated daily.">

<!-- Twitter -->
<meta property="twitter:title" content="Home Win Predictions Today - 1X2 Football Tips">
<meta property="twitter:description" content="Free home win predictions with confidence ratings and odds. Updated daily.">
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/home-win-tips.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_home_matches_fixtures";
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
        return [
            'prediction' => '1',
            'display' => 'Home',
            'chipClass' => 'chip-home',
            'confidence' => $homePercent,
            'type' => 'home'
        ];
    } elseif ($maxPercent === $drawPercent) {
        return [
            'prediction' => 'X',
            'display' => 'Draw',
            'chipClass' => 'chip-draw',
            'confidence' => $drawPercent,
            'type' => 'draw'
        ];
    } else {
        return [
            'prediction' => '2',
            'display' => 'Away',
            'chipClass' => 'chip-away',
            'confidence' => $awayPercent,
            'type' => 'away'
        ];
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
                if ($bet['value'] === $value) {
                    return floatval($bet['odd']);
                }
            }
        }
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

/* Stats bar for 1X2 */
.pred-stats-bar {
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
    grid-template-columns: 10% 30% 10% 22% 12% 10%;
    gap: 10px;
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
    grid-template-columns: 10% 30% 10% 22% 12% 10%;
    gap: 10px;
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

.odds-label {
    font-size: 10px;
    color: #6c757d;
    text-transform: uppercase;
    margin-top: 2px;
}

/* Probability rings */
.mc-prob {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 5px;
}

.prob-item {
    text-align: center;
    flex: 1;
}

.prob-ring {
    position: relative;
    width: 40px;
    height: 40px;
    margin: 0 auto 4px;
}

.prob-ring svg {
    width: 40px;
    height: 40px;
    transform: rotate(-90deg);
}

.prob-ring circle {
    fill: none;
    stroke-width: 3;
    cx: 20;
    cy: 20;
    r: 17;
}

.prob-ring .track {
    stroke: #e9ecef;
}

.prob-ring .fill-home {
    stroke: #05384B;
    stroke-linecap: round;
}

.prob-ring .fill-draw {
    stroke: #6c757d;
    stroke-linecap: round;
}

.prob-ring .fill-away {
    stroke: #dc3545;
    stroke-linecap: round;
}

.prob-ring-value {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 10px;
    font-weight: 600;
    font-family: 'DM Mono', monospace;
}

.prob-label {
    font-size: 9px;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 500;
}

.prob-sep {
    width: 1px;
    height: 25px;
    background: #dee2e6;
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
    min-width: 60px;
    text-align: center;
}

.chip-home {
    background: #05384B;
    color: white;
}

.chip-away {
    background: #dc3545;
    color: white;
}

.chip-draw {
    background: #6c757d;
    color: white;
}

.chip-over {
    background: #10b981;
    color: white;
}

.chip-under {
    background: #f59e0b;
    color: white;
}

.chip-dc {
    background: #ec4899;
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

/* Confidence colors */
.confidence-high {
    font-weight: 700;
    color: #10b981;
}

.confidence-medium {
    font-weight: 600;
    color: #f59e0b;
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
        <h2>1X2 Predictions - Home, Draw & Away Tips</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>1X2 predictions</strong> show the most likely outcome for each match - <span style="color: #05384B; font-weight: 600;">Home Win (1)</span>, <span style="color: #6c757d; font-weight: 600;">Draw (X)</span>, or <span style="color: #dc3545; font-weight: 600;">Away Win (2)</span>. Confidence percentages are based on our prediction algorithm.
    </p>

    <!-- Stats Bar -->
    <?php 
    $homeCount = 0;
    $drawCount = 0;
    $awayCount = 0;
    
    foreach ($tipsData as $tip) {
        $predData = getPredictionWithConfidence($tip);
        if ($predData['prediction'] === '1') $homeCount++;
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
            <span class="stat-value" style="color: #05384B;"><?php echo $homeCount; ?></span>
            <span class="stat-label">Home Wins</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #6c757d;"><?php echo $drawCount; ?></span>
            <span class="stat-label">Draws</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #dc3545;"><?php echo $awayCount; ?></span>
            <span class="stat-label">Away Wins</span>
        </div>
    </div>

    <!-- Column Headers -->
    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Probability</span>
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
                No predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php foreach ($tipsData as $tip):
            
            // Get prediction data
            $predData = getPredictionWithConfidence($tip);
            $prediction = $predData['prediction'];
            $displayPrediction = $predData['display'];
            $chipClass = $predData['chipClass'];
            $confidence = $predData['confidence'];
            
            // Get odds
            $oddsDisplay = '—';
            if (!empty($tip['all_bets_odds'])) {
                try {
                    $oddsData = json_decode($tip['all_bets_odds'], true);
                    if (is_array($oddsData)) {
                        foreach ($oddsData as $market) {
                            if (($market['name'] ?? '') === "Match Winner" && !empty($market['values'])) {
                                $map = ["1" => "Home", "X" => "Draw", "2" => "Away"];
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
            
            // Get scores
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $scoreDisplay = '—';
            $matchStatus = 'UPCOMING';
            $statusClass = 'upcoming';
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $matchStatus = 'FT';
                $statusClass = '';
                
                // Determine win/loss status
                $winningStatus = DetermineWinningOrLost($prediction, $homeScore, $awayScore);
            }
            
            // Get percentages
            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');
            
            // SVG ring calculations
            $circ = 106.76; // 2πr with r=17
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);
            
            // Team initials
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));
            
            // League info
            $leagueFull = $tip['league_name'] ?? '';
            $leagueCountry = $tip['country_name'] ?? '';
            
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
        ?>

        <div class="match-card">
            <!-- Time -->
            <div class="mc-time">
                <span><?php echo htmlspecialchars($formattedTime); ?></span>
            </div>

            <!-- Match -->
            <div class="mc-match">
                <span class="league-tag"><?php echo htmlspecialchars($leagueCountry ?: $leagueFull); ?></span>
                <div class="teams-inline">
                    <div class="team-crest home-crest"><?php echo $homeInitial; ?></div>
                    <span class="team-name-text"><?php echo htmlspecialchars($tip['home_team_name'] ?? ''); ?></span>
                    <span class="vs-badge">VS</span>
                    <div class="team-crest"><?php echo $awayInitial; ?></div>
                    <span class="team-name-text"><?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?></span>
                </div>
            </div>

            <!-- Odds -->
            <div class="mc-odds">
                <div class="odds-value"><?php echo htmlspecialchars($oddsDisplay); ?></div>
                <div class="odds-label">Odds</div>
            </div>

            <!-- Probability Rings -->
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
                    <span class="prob-label">H</span>
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
                    <span class="prob-label">D</span>
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
                    <span class="prob-label">A</span>
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
            $predData = getPredictionWithConfidence($tip);
            $prediction = $predData['prediction'];
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $totalFinished++;
                $status = DetermineWinningOrLost($prediction, $homeScore, $awayScore);
                if ($status === 'Won') $won++;
                elseif ($status === 'Lost') $lost++;
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

    <!-- 1X2 Tips Box -->
    <div class="tips-box">
        <h3>⚽ Understanding 1X2 Betting</h3>
        <p><span style="color: #05384B; font-weight: 600;">Home Win (1)</span> - The home team wins the match</p>
        <p><span style="color: #6c757d; font-weight: 600;">Draw (X)</span> - The match ends in a draw</p>
        <p><span style="color: #dc3545; font-weight: 600;">Away Win (2)</span> - The away team wins the match</p>
        <p>Confidence percentages (50-95%) indicate the strength of each prediction based on our analysis</p>
        <p>Higher confidence (80%+) suggests very strong probability for the predicted outcome</p>
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