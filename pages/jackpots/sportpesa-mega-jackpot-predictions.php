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

<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
include_once BASE_PATH . "/components/shared/DateTimeToUsersTimezone.shared.php";
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

// Make cURL request
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Partner-Authorization: $token",
    "Origin: https://www.betsasured.com"
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
    
    $maxPercent = max($percentHome, $percentDraw, $percentAway);
    
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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<main class="container py-4">
    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Sportpesa Mega Jackpot Predictions</h2>
        <span class="today-date-tag">Week <?php echo date('W'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Looking for expert Sportpesa Mega Jackpot predictions this week? 
        Our comprehensive analysis covers all 17 games with detailed match breakdowns 
        and winning strategies.
    </p>

    <!-- Jackpot Stats Bar -->
    <?php if ($startDate && $endDate): ?>
    <div class="jackpot-stats-bar">
        <div class="stat-item">
            <span class="stat-value">17 Games</span>
            <span class="stat-label">This Week</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">KES 150M+</span>
            <span class="stat-label">Prize Pool</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= date('d M', strtotime($startDate)) ?></span>
            <span class="stat-label">Starts</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= date('d M', strtotime($endDate)) ?></span>
            <span class="stat-label">Ends</span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Column Headers (same as homepage) -->
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
        <?php if (count($predictions) === 0): ?>
            <div class="state-msg">
                No predictions available at the moment. Check back soon!
            </div>
        <?php else: ?>

        <?php foreach ($predictions as $index => $tip): 
            $prediction = get1X2Tip($tip);
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $scoreDisplay = ($homeScore === null || $awayScore === null) ? '—' : "{$homeScore} – {$awayScore}";
            
            $matchDate = isset($tip['date']) 
                ? (new DateTime($tip['date']))->modify('+3 hours') 
                : null;
            $formattedTime = $matchDate ? $matchDate->format('H:i') : '—';
            $formattedDate = $matchDate ? $matchDate->format('d/m') : '';
            
            $winningStatus = DetermineWinningOrLost($prediction, $homeScore, $awayScore);
            
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
            $leagueFull = $tip['league_name'] ?? 'Sportpesa Mega';
            $leagueCountry = $tip['league_country'] ?? '';
            
            // Prediction chip class
            $chipClass = 'chip-draw';
            $displayPrediction = $prediction;
            if ($prediction === "1") { $displayPrediction = "Home"; $chipClass = 'chip-home'; }
            if ($prediction === "2") { $displayPrediction = "Away"; $chipClass = 'chip-away'; }
            if ($prediction === "X") { $displayPrediction = "Draw"; $chipClass = 'chip-draw'; }
            
            // Odds (simplified for jackpot)
            $oddsDisplay = '—';
            if (!empty($tip['bets_home']) && $prediction === '1') $oddsDisplay = $tip['bets_home'];
            elseif (!empty($tip['bets_draw']) && $prediction === 'X') $oddsDisplay = $tip['bets_draw'];
            elseif (!empty($tip['bets_away']) && $prediction === '2') $oddsDisplay = $tip['bets_away'];
        ?>

        <div class="match-card">
            <!-- Time -->
            <div class="mc-time">
                <span><?php echo $formattedTime; ?></span>
                <?php if ($formattedDate): ?>
                <span style="font-size: 11px; color: #6c757d; display: block;"><?php echo $formattedDate; ?></span>
                <?php endif; ?>
            </div>

            <!-- Match -->
            <div class="mc-match">
                <span class="league-tag"><?php echo $leagueCountry ?: 'Mega'; ?> · Match <?php echo $index + 1; ?></span>
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

            <!-- Prediction -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo $displayPrediction; ?>
                </span>
                <?php if ($scoreDisplay !== '—'): ?>
                <span style="font-size: 10px; display: block; color: <?php echo ($winningStatus === 'Won') ? '#10b981' : '#dc3545'; ?>; margin-top: 4px;">
                    <?php echo $winningStatus; ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Score -->
            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <?php if ($scoreDisplay !== '—'): ?>
                <div class="score-status">FT</div>
                <?php else: ?>
                <div class="score-status" style="color: #f59e0b;">UPCOMING</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- Performance Summary (if there are results) -->
    <?php 
    if (count($predictions) > 0):
        $won = 0;
        $lost = 0;
        foreach ($predictions as $tip) {
            $pred = get1X2Tip($tip);
            $status = DetermineWinningOrLost($pred, $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
            if ($status === 'Won') $won++;
            elseif ($status === 'Lost') $lost++;
        }
        $total = $won + $lost;
        if ($total > 0):
    ?>
    <div class="perf-summary">
        <div class="perf-item">
            <span class="perf-label">Correct Tips</span>
            <span class="perf-value"><?php echo $won; ?>/<?php echo $total; ?></span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Success Rate</span>
            <span class="perf-value"><?php echo $total > 0 ? round(($won/$total)*100) : 0; ?>%</span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Jackpot Games</span>
            <span class="perf-value">17</span>
        </div>
    </div>
    <?php endif; endif; ?>

    <!-- Winning Tips Box -->
    <div class="tips-box">
        <h3>💡 How to Win Sportpesa Mega Jackpot</h3>
        <p>• Use multiple systems — create at least 3-5 different combinations</p>
        <p>• Identify 5-6 strong bankers with high confidence (80%+)</p>
        <p>• Include 2-3 draw predictions where odds are favorable</p>
        <p>• Mix home wins, away wins, and draws strategically</p>
    </div>

    <!-- SEO Content -->
    <section class="seo-section mt-4">
        <div class="blog-2 seo-content">
            <?php echo $htmlContent; ?>
        </div>
    </section>
</main>

<?php
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>