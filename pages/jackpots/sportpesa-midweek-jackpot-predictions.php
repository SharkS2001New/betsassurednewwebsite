<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Sportpesa Midweek Jackpot Predictions This Week - Free 13 Games Tips | Betsassured</title>
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

include_once BASE_PATH . "/components/includes/header.inc.php";
?>

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

// Make cURL request
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Partner-Authorization: $token",
    "Origin: https://www.betsassured.com"
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

<main class="container">
    <h1 class="page-hero-title">Sportpesa Midweek Jackpot Predictions</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <div class="section-title-bar">
        <h2>This Week's Midweek Jackpot Tips</h2>
        <span class="today-date-tag">Week <?php echo date('W'); ?></span>
    </div>

    <p style="color: #4b5563; margin-bottom: 20px;">
        Looking for expert <strong>Sportpesa Midweek Jackpot predictions</strong> this week?
        Our comprehensive analysis covers all 13 games with detailed match breakdowns,
        confidence ratings, and jackpot strategy insights.
    </p>

    <?php if ($startDate && $endDate): ?>
    <div class="jackpot-stats-bar">
        <div class="stat-item">
            <span class="stat-value">13 Games</span>
            <span class="stat-label">This Week</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">KES 24M+</span>
            <span class="stat-label">Prize Pool</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= date('d M', strtotime(str_replace('/', '-', explode(' ', $startDate)[0]))) ?></span>
            <span class="stat-label">Starts</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= date('d M', strtotime(str_replace('/', '-', explode(' ', $endDate)[0]))) ?></span>
            <span class="stat-label">Ends</span>
        </div>
    </div>
    <?php endif; ?>

    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Probability</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Score</span>
    </div>

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

            $scoreDisplay = '—';
            $scoreStatus = 'UPCOMING';
            $scoreStatusClass = 'upcoming';

            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $scoreStatus = 'FT';
                $scoreStatusClass = '';
            }

            $matchDate = isset($tip['date'])
                ? (new DateTime($tip['date']))->modify('+3 hours')
                : null;
            $formattedTime = $matchDate ? $matchDate->format('H:i') : '—';
            $formattedDate = $matchDate ? $matchDate->format('d/m') : '';

            $winningStatus = DetermineWinningOrLost($prediction, $homeScore, $awayScore);

            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            $circ = 106.76;
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);

            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));

            $leagueFull = $tip['league_name'] ?? 'Sportpesa Midweek Jackpot';
            $leagueCountry = $tip['league_country'] ?? '';

            $chipClass = 'chip-draw';
            $displayPrediction = $prediction;
            if ($prediction === "1") { $displayPrediction = "Home"; $chipClass = 'chip-home'; }
            if ($prediction === "2") { $displayPrediction = "Away"; $chipClass = 'chip-away'; }
            if ($prediction === "X") { $displayPrediction = "Draw"; $chipClass = 'chip-draw'; }

            $oddsDisplay = '—';
            if (!empty($tip['bets_home']) && $prediction === '1') {
                $oddsDisplay = $tip['bets_home'];
            } elseif (!empty($tip['bets_draw']) && $prediction === 'X') {
                $oddsDisplay = $tip['bets_draw'];
            } elseif (!empty($tip['bets_away']) && $prediction === '2') {
                $oddsDisplay = $tip['bets_away'];
            }

            $hasScore = ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '');
            $statusShort = htmlspecialchars($tip['status_short'] ?? '');   // e.g. "FT", "HT", "1H", "NS"
        ?>

        <div class="match-card">
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

            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
                <?php if ($scoreDisplay !== '—' && $winningStatus): ?>
                <span class="result-badge-small <?php echo ($winningStatus === 'Won') ? 'result-won' : 'result-lost'; ?>">
                    <?php echo htmlspecialchars($winningStatus); ?>
                </span>
                <?php endif; ?>
            </div>

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

            <div class="mc-odds">
                <div class="odds-value"><?php echo htmlspecialchars($oddsDisplay); ?></div>
                <div class="odds-label">Odds</div>
            </div>

            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <div class="score-status <?php echo $scoreStatusClass; ?>"><?php echo $scoreStatus; ?></div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

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
            <span class="perf-value"><?php echo round(($won / $total) * 100); ?>%</span>
        </div>
        <div class="perf-item">
            <span class="perf-label">Jackpot Games</span>
            <span class="perf-value">13</span>
        </div>
    </div>
    <?php endif; endif; ?>

    <section class="seo-section mt-4">
        <div class="blog-2 seo-content">
            <?php echo $htmlContent; ?>
        </div>
    </section>
</main>

<?php
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
