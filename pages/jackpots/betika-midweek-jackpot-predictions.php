<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Betika Midweek Jackpot Predictions This Week | Kenya Free Tips</title>
<meta name="title" content="Betika Midweek Jackpot Predictions This Week | Kenya Free Tips">
<meta name="description" content="Free Betika Midweek Jackpot predictions for all games this week. Expert analysis with probability ratings to help you win more brackets. Updated every week.">
<meta name="keywords" content="betika midweek jackpot predictions, betika midweek jackpot tips, betika jackpot predictions this week, betika midweek jackpot analysis, betika jackpot predictions kenya, betika midweek tips today">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Betika Midweek Jackpot Predictions This Week | Kenya Free Tips">
<meta property="og:description" content="Free Betika Midweek Jackpot predictions for all games this week. Expert analysis with probability ratings to help you win more brackets. Updated every week.">
<meta property="og:url" content="https://www.betsassured.com/betika-midweek-jackpot-predictions">
<meta property="og:site_name" content="Betsassured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Betika Midweek Jackpot Predictions This Week | Kenya Free Tips">
<meta name="twitter:description" content="Free Betika Midweek Jackpot predictions for all games this week. Expert analysis with probability ratings to help you win more brackets. Updated every week.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
include_once BASE_PATH . "/components/shared/DateTimeToUsersTimezone.shared.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";

$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH . '/components/seo-content/betika-midweek-jackpot.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// API request
$jackpotName  = "Betika Midweek Jackpot";
$encodedName  = urlencode($jackpotName);
$apiUrl       = "https://api.alljackpotpredictions.com/api/fetch_jackpot_fixtures_by_name?jackpot_name=$encodedName";
$token        = "q2LsJ9FmT6XvRaCbHuYdK8ZwN4";

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Partner-Authorization: $token",
    "Origin: https://www.betsassured.com"
]);
$response = curl_exec($ch);
curl_close($ch);

$predictions = [];
// DateTime objects for stats bar (kept as DateTime, not pre-formatted strings)
$startDt = null;
$endDt   = null;

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

function get1X2Tip($tip) {
    $h = percentToInt($tip['percent_pred_home'] ?? '0');
    $d = percentToInt($tip['percent_pred_draw'] ?? '0');
    $a = percentToInt($tip['percent_pred_away'] ?? '0');
    $max = max($h, $d, $a);
    if ($max === $h) return '1';
    if ($max === $d) return 'X';
    return '2';
}

if ($response) {
    $result = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($result['data']) && is_array($result['data'])) {
        $predictions = $result['data'];

        if (!empty($predictions)) {
            $rawDates = array_filter(array_column($predictions, 'date'));
            if (!empty($rawDates)) {
                sort($rawDates);
                $startDt = (new DateTime(reset($rawDates)))->modify('+3 hours');
                $endDt   = (new DateTime(end($rawDates)))->modify('+3 hours');
            }
        }
    }
}

$gameCount = count($predictions);
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
.jackpot-stats-bar {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin: 15px 0 20px;
    padding: 15px 20px;
    background: linear-gradient(135deg, #05384B 0%, #0a4a60 100%);
    border-radius: 10px;
    color: white;
}
.jackpot-stats-bar .stat-item { display: flex; flex-direction: column; }
.jackpot-stats-bar .stat-value { font-size: 20px; font-weight: 700; line-height: 1.2; }
.jackpot-stats-bar .stat-label { font-size: 12px; opacity: 0.85; }
.perf-summary {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    margin: 20px 0 30px;
    border: 1px solid #dee2e6;
}
.perf-item { display: flex; flex-direction: column; }
.perf-label { font-size: 13px; color: #6c757d; }
.perf-value { font-size: 24px; font-weight: 700; color: #05384B; }
.result-won  { color: #10b981; }
.result-lost { color: #dc3545; }
.result-badge-small { font-size: 10px; font-weight: 600; display: block; margin-top: 3px; }
</style>

<main class="container py-4">
    <h1 class="page-hero-title">Betika Midweek Jackpot Predictions This Week | Kenya</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Betika Midweek Jackpot Predictions</h2>
        <span class="today-date-tag">Week <?php echo date('W'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Expert <strong>Betika Midweek Jackpot predictions</strong> for all <?php echo $gameCount > 0 ? $gameCount : ''; ?> games this week.
        Each match includes a probability breakdown and recommended selection to help you build the best possible jackpot slip.
    </p>

    <!-- Stats Bar -->
    <?php if ($startDt && $endDt): ?>
    <div class="jackpot-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo $gameCount; ?></span>
            <span class="stat-label">Jackpot Games</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $startDt->format('d M'); ?></span>
            <span class="stat-label">First Kick-off</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?php echo $endDt->format('d M'); ?></span>
            <span class="stat-label">Last Game</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">Free</span>
            <span class="stat-label">No Sign-up</span>
        </div>
    </div>
    <?php endif; ?>

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
        <?php if (empty($predictions)): ?>
            <div class="state-msg">
                Betika Midweek Jackpot predictions are being prepared — check back soon!
            </div>
        <?php else: ?>

        <?php foreach ($predictions as $index => $tip):
            $prediction = get1X2Tip($tip);

            // Score & match status
            $homeScore    = $tip['goals_home'] ?? null;
            $awayScore    = $tip['goals_away'] ?? null;
            $scoreDisplay = ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '')
                ? htmlspecialchars($homeScore . ' – ' . $awayScore)
                : '—';
            $matchStatus  = ($scoreDisplay !== '—') ? 'FT' : 'UPCOMING';

            // Won/lost
            $winningStatus = ($scoreDisplay !== '—')
                ? DetermineWinningOrLost($prediction, $homeScore, $awayScore)
                : '';

            // Time — use DateTime directly, not a pre-formatted string
            $matchDt       = !empty($tip['date']) ? (new DateTime($tip['date']))->modify('+3 hours') : null;
            $formattedTime = $matchDt ? $matchDt->format('H:i') : '—';
            $formattedDate = $matchDt ? $matchDt->format('d/m') : '';

            // Probabilities
            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            $circ     = 106.81;
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);

            // Team initials
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));

            // League
            $leagueCountry = $tip['league_country'] ?? '';

            // Chip
            $chipClass         = 'chip-draw';
            $displayPrediction = $prediction;
            if ($prediction === "1") { $displayPrediction = "Home Win"; $chipClass = 'chip-home'; }
            if ($prediction === "2") { $displayPrediction = "Away Win"; $chipClass = 'chip-away'; }
            if ($prediction === "X") { $displayPrediction = "Draw";     $chipClass = 'chip-draw'; }

            // Odds
            $oddsDisplay = '—';
            if (!empty($tip['bets_home'])  && $prediction === '1') $oddsDisplay = $tip['bets_home'];
            elseif (!empty($tip['bets_draw'])  && $prediction === 'X') $oddsDisplay = $tip['bets_draw'];
            elseif (!empty($tip['bets_away'])  && $prediction === '2') $oddsDisplay = $tip['bets_away'];
        ?>

        <div class="match-card">
            <!-- Time -->
            <div class="mc-time">
                <span class="time-val"><?php echo htmlspecialchars($formattedTime); ?></span>
                <?php if ($formattedDate): ?>
                <span style="font-size:11px;color:#6c757d;display:block;"><?php echo htmlspecialchars($formattedDate); ?></span>
                <?php endif; ?>
            </div>

            <!-- Match -->
            <div class="mc-match">
                <span class="league-tag">
                    <?php echo htmlspecialchars($leagueCountry ?: 'Betika'); ?> · Game <?php echo $index + 1; ?>
                </span>
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

            <!-- Prediction + won/lost -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
                <?php if ($winningStatus !== ''): ?>
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
    if (!empty($predictions)):
        $won  = 0;
        $total = 0;
        foreach ($predictions as $tip) {
            $hs = $tip['goals_home'] ?? null;
            $as = $tip['goals_away'] ?? null;
            if ($hs !== null && $as !== null && $hs !== '' && $as !== '') {
                $total++;
                if (DetermineWinningOrLost(get1X2Tip($tip), $hs, $as) === 'Won') $won++;
            }
        }
        if ($total > 0):
    ?>
    <div class="perf-summary">
        <div class="perf-item">
            <span class="perf-label">Correct Selections</span>
            <span class="perf-value"><?php echo $won; ?>/<?php echo $total; ?></span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Accuracy</span>
            <span class="perf-value"><?php echo round(($won / $total) * 100); ?>%</span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Total Games</span>
            <span class="perf-value"><?php echo $gameCount; ?></span>
        </div>
    </div>
    <?php endif; endif; ?>

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
