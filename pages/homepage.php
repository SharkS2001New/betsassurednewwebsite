<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Accurate Stakes: Best Prediction Site - Free Football Tips</title>
<meta name="title" content="Accurate Football Predictions & Sure Tips">
<meta name="description" content="Get the edge with reliable predictions, daily free tips, and expert insights tailored for consistent football betting success.">
<meta name="keywords" content="free prediction site, daily predictions, successful soccer prediction  accurate football predictions, best football prediction site, accurate predictions, daily soccer tips, sure tips, accurate tip">

<!-- Open Graph -->
<meta property="og:title" content="AccurateStakes - Accurate Prediction Site">
<meta property="og:description" content="AccurateStakes provides free football predictions daily from experienced tipsters.">

<!-- Twitter -->
<meta property="twitter:title" content="AccurateStakes - Accurate Prediction Site ">
<meta property="twitter:description" content="AccurateStakes provides free football predictions daily from experienced tipsters.">
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/homepage.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_homepage_preds_match_tips";
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
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
/* ============================================================
   DESIGN SYSTEM — AccurateStakes Premium Predictions
   Aesthetic: Refined editorial sports data dashboard
   Palette: Near-white base, deep navy accents, electric lime pop
   ============================================================ */
:root {
    --navy:      #0d1b2a;
    --navy-mid:  #162535;
    --navy-soft: #1e3248;
    --lime:      #c8f135;
    --lime-dim:  #a8cc20;
    --sky:       #38bdf8;
    --coral:     #fb7185;
    --amber:     #fbbf24;
    --emerald:   #34d399;
    --surface:   #f5f7fa;
    --surface-2: #edf0f5;
    --border:    #e2e8f0;
    --text-1:    #0d1b2a;
    --text-2:    #475569;
    --text-3:    #94a3b8;
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 3px rgba(13,27,42,.06), 0 1px 2px rgba(13,27,42,.04);
    --shadow-md: 0 4px 16px rgba(13,27,42,.10);
    --shadow-lg: 0 12px 40px rgba(13,27,42,.14);
    --font-sans: 'DM Sans', -apple-system, sans-serif;
    --font-mono: 'DM Mono', monospace;
}

/* ---------- Page ---------- */
body {
    font-family: var(--font-sans);
    background: var(--surface);
    color: var(--text-1);
}

/* ---------- Hero Header ---------- */
.pred-hero {
    background: var(--navy);
    padding: 52px 0 40px;
    position: relative;
    overflow: hidden;
}

.pred-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 60% at 80% 50%, rgba(200,241,53,.07) 0%, transparent 70%),
        radial-gradient(ellipse 40% 80% at 10% 80%, rgba(56,189,248,.06) 0%, transparent 70%);
    pointer-events: none;
}

/* Subtle pitch lines decoration */
.pred-hero::after {
    content: '';
    position: absolute;
    right: 0; top: 0; bottom: 0;
    width: 340px;
    background-image:
        repeating-linear-gradient(90deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 60px),
        repeating-linear-gradient(0deg, rgba(255,255,255,.025) 0px, rgba(255,255,255,.025) 1px, transparent 1px, transparent 60px);
    pointer-events: none;
}

.pred-hero .container {
    position: relative;
    z-index: 1;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(200,241,53,.12);
    border: 1px solid rgba(200,241,53,.25);
    border-radius: 100px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--lime);
    margin-bottom: 18px;
}

.hero-eyebrow span.dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: var(--lime);
    animation: pulse-dot 1.8s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: .4; transform: scale(.6); }
}

.pred-hero h1 {
    font-size: clamp(24px, 4vw, 38px);
    font-weight: 700;
    color: #fff;
    line-height: 1.22;
    margin: 0 0 14px;
    max-width: 620px;
}

.pred-hero h1 em {
    font-style: normal;
    color: var(--lime);
}

.pred-hero p {
    color: rgba(255,255,255,.58);
    font-size: 15px;
    line-height: 1.7;
    max-width: 580px;
    margin: 0 0 30px;
}

/* Stats row */
.hero-stats {
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
}

.hero-stat {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.hero-stat .value {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    font-variant-numeric: tabular-nums;
}

.hero-stat .value sup {
    font-size: 13px;
    color: var(--lime);
}

.hero-stat .label {
    font-size: 11px;
    color: rgba(255,255,255,.45);
    letter-spacing: .06em;
    text-transform: uppercase;
}

/* Date badge in hero corner */
.hero-date-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: var(--radius-md);
    padding: 14px 20px;
    min-width: 90px;
}

.hero-date-badge .day-num {
    font-size: 36px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.hero-date-badge .month {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--lime);
    margin-top: 3px;
}

.hero-date-badge .year {
    font-size: 11px;
    color: rgba(255,255,255,.35);
    margin-top: 1px;
}

/* ==========================================================
   TABLE HEADER
   Columns: Time | Match | Odds | Probability | Prediction | Score
   ========================================================== */
.preds-table-header {
    display: grid;
    grid-template-columns: 80px 1fr 80px 220px 120px 90px;
    align-items: center;
    padding: 10px 24px;
    background: var(--navy);
    border-radius: var(--radius-md) var(--radius-md) 0 0;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.35);
    gap: 16px;
}

/* ==========================================================
   MATCH CARDS WRAPPER
   ========================================================== */
.preds-wrapper {
    background: #fff;
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.match-card {
    display: grid;
    grid-template-columns: 80px 1fr 80px 220px 120px 90px;
    align-items: center;
    gap: 16px;
    padding: 14px 24px;
    border-bottom: 1px solid var(--border);
    transition: background .18s;
    position: relative;
}

.match-card:last-child { border-bottom: none; }
.match-card:hover      { background: var(--surface); }

.match-card::before {
    content: '';
    position: absolute;
    left: 0; top: 8px; bottom: 8px;
    width: 3px;
    background: var(--lime);
    border-radius: 0 3px 3px 0;
    opacity: 0;
    transition: opacity .18s;
}
.match-card:hover::before { opacity: 1; }

/* --- Col 1: Date / Time --- */
.mc-time {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2px;
}

.mc-time .time-val {
    font-family: var(--font-mono);
    font-size: 15px;
    font-weight: 600;
    color: var(--text-1);
    line-height: 1;
}

.mc-time .date-val {
    font-size: 10px;
    color: var(--text-3);
    font-weight: 500;
}

/* --- Col 2: Match (league label above, Home vs Away inline) --- */
.mc-match {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}

.league-tag {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--text-3);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.teams-inline {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;
    min-width: 0;
}

.team-crest {
    width: 28px;
    height: 28px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 11px;
    color: #fff;
    flex-shrink: 0;
    letter-spacing: .02em;
}

.team-crest.home-crest { background: linear-gradient(135deg, var(--navy-soft), var(--navy-mid)); }
.team-crest.away-crest { background: linear-gradient(135deg, #374151, #1f2937); }

.team-name-text {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-1);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.vs-badge {
    font-size: 10px;
    font-weight: 700;
    color: var(--text-3);
    background: var(--surface-2);
    border-radius: 4px;
    padding: 2px 6px;
    flex-shrink: 0;
    letter-spacing: .04em;
}

/* --- Col 3: Odds --- */
.mc-odds {
    text-align: center;
}

.odds-value {
    font-family: var(--font-mono);
    font-size: 16px;
    font-weight: 500;
    color: var(--amber);
    background: rgba(251,191,36,.08);
    border: 1px solid rgba(251,191,36,.2);
    border-radius: var(--radius-sm);
    padding: 5px 10px;
    display: inline-block;
    line-height: 1;
}

.odds-label {
    font-size: 9px;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--text-3);
    margin-top: 4px;
}

/* --- Col 4: Probability rings --- */
.mc-prob {
    display: flex;
    align-items: center;
    gap: 6px;
}

.prob-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}

.prob-ring {
    position: relative;
    width: 50px;
    height: 50px;
}

.prob-ring svg {
    width: 50px;
    height: 50px;
    transform: rotate(-90deg);
}

.prob-ring .track      { fill: none; stroke: var(--border); stroke-width: 4; }
.prob-ring .fill-home  { fill: none; stroke: var(--sky);    stroke-width: 4; stroke-linecap: round; }
.prob-ring .fill-draw  { fill: none; stroke: var(--amber);  stroke-width: 4; stroke-linecap: round; }
.prob-ring .fill-away  { fill: none; stroke: var(--coral);  stroke-width: 4; stroke-linecap: round; }

.prob-ring-value {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: var(--text-1);
    font-family: var(--font-mono);
}

.prob-label {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: .04em;
    color: var(--text-3);
    text-transform: uppercase;
}

.prob-sep {
    width: 1px;
    height: 38px;
    background: var(--border);
    flex-shrink: 0;
}

/* --- Col 5: Prediction --- */
.mc-prediction {
    display: flex;
    justify-content: center;
}

.pred-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 100px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .02em;
    white-space: nowrap;
}

.pred-chip.chip-home  { background: rgba(56,189,248,.12);  color: var(--sky);      border: 1px solid rgba(56,189,248,.25); }
.pred-chip.chip-away  { background: rgba(251,113,133,.12); color: var(--coral);    border: 1px solid rgba(251,113,133,.25); }
.pred-chip.chip-draw  { background: rgba(251,191,36,.10);  color: var(--amber);    border: 1px solid rgba(251,191,36,.25); }
.pred-chip.chip-over  { background: rgba(52,211,153,.12);  color: var(--emerald);  border: 1px solid rgba(52,211,153,.25); }
.pred-chip.chip-under { background: rgba(148,163,184,.10); color: var(--text-2);   border: 1px solid var(--border); }
.pred-chip.chip-dc    { background: rgba(200,241,53,.10);  color: var(--lime-dim); border: 1px solid rgba(200,241,53,.25); }

.pred-chip::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
    opacity: .7;
}

/* --- Col 6: Score --- */
.mc-score {
    text-align: center;
}

.score-display {
    font-family: var(--font-mono);
    font-size: 17px;
    font-weight: 600;
    color: var(--text-1);
    letter-spacing: .04em;
    line-height: 1;
}

.score-status {
    margin-top: 4px;
    font-size: 9px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--emerald);
}

/* ---------- Empty / Error states ---------- */
.state-msg {
    padding: 48px 24px;
    text-align: center;
    color: var(--text-3);
    font-size: 15px;
}

/* ---------- Section Title ---------- */
.section-title-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 36px 0 16px;
}

.section-title-bar h2 {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-1);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-title-bar h2::before {
    content: '';
    display: inline-block;
    width: 4px; height: 22px;
    background: var(--lime);
    border-radius: 2px;
}

.today-date-tag {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-2);
    background: var(--surface-2);
    border: 1px solid var(--border);
    border-radius: 100px;
    padding: 4px 12px;
    font-family: var(--font-mono);
}

/* ---------- SEO content ---------- */
.seo-section {
    margin-top: 56px;
    padding-top: 36px;
    border-top: 1px solid var(--border);
}

/* ---------- Responsive ---------- */
@media (max-width: 1100px) {
    .preds-table-header,
    .match-card {
        grid-template-columns: 70px 1fr 72px 190px 110px 80px;
    }
}

@media (max-width: 900px) {
    .preds-table-header { display: none; }
    .match-card {
        grid-template-columns: auto 1fr auto;
        grid-template-rows: auto auto auto;
        gap: 10px 16px;
        padding: 16px;
    }
    /* Row 1: time | match header (league+teams) | score */
    .mc-time       { grid-column: 1; grid-row: 1; align-self: center; }
    .mc-match      { grid-column: 2; grid-row: 1 / 3; }
    .mc-score      { grid-column: 3; grid-row: 1; text-align: right; }
    /* Row 2: odds | — | prediction */
    .mc-odds       { grid-column: 1; grid-row: 2; text-align: left; }
    .mc-prediction { grid-column: 3; grid-row: 2; justify-content: flex-end; }
    /* Row 3: probability spans full width */
    .mc-prob       { grid-column: 1 / 4; grid-row: 3; justify-content: space-between; }
}

@media (max-width: 600px) {
    .pred-hero { padding: 36px 0 30px; }
    .hero-stats { gap: 20px; }
    .hero-date-badge { display: none; }
}
</style>

    <?php include_once BASE_PATH . "/components/shared/accumulator_tips.shared.php"; ?>

<main class="container py-4">

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <div class="section-title-bar">
        <h2>Today's Predictions</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Column headers -->
    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Probability</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Score</span>
    </div>

    <div class="preds-wrapper">
        <?php if ($error): ?>
            <div class="state-msg">
                Our experts are working on the predictions — please check back in a few minutes!
            </div>
        <?php elseif ($empty): ?>
            <div class="state-msg">
                Working on today's predictions. Check back later!
            </div>
        <?php else: ?>

        <?php foreach ($tipsData as $tip):

            /* ---- Prediction logic (unchanged from original) ---- */
            $fixturesAverage = ComputeFixtureAverage(
                $tip['teams_perfomance_home_for']    ?? null,
                $tip['teams_perfomance_home_aganist'] ?? $tip['teams_perfomance_home_against'] ?? null,
                $tip['teams_perfomance_away_for']    ?? null,
                $tip['teams_perfomance_away_aganist'] ?? $tip['teams_perfomance_away_against'] ?? null,
                $tip['teams_games_played_home'] ?? null,
                $tip['teams_games_played_away'] ?? null
            );

            $winningtip = WinningTeamPred1x2(
                $tip['percent_pred_home'] ?? '0%',
                $tip['percent_pred_draw'] ?? '0%',
                $tip['percent_pred_away'] ?? '0%',
                $tip['goals_home'] ?? null,
                $tip['goals_away'] ?? null
            );

            $doubleChancewinningTip = DoubleChanceWinningTeam(
                $tip['percent_pred_home'] ?? '0%',
                $tip['percent_pred_draw'] ?? '0%',
                $tip['percent_pred_away'] ?? '0%',
                $tip['goals_home'] ?? null,
                $tip['goals_away'] ?? null
            );

            $predictionValue = '';

            if ($fixturesAverage !== "-" && floatval($fixturesAverage) > 0) {
                $avgFloat = floatval($fixturesAverage);
                if ($avgFloat < 2.0 || $avgFloat > 3.0) {
                    $predictionValue = ($avgFloat > 2.5) ? "Over 2.5" : "Under 2.5";
                } else {
                    $homeP = percentToInt($tip['percent_pred_home'] ?? '0');
                    $drawP = percentToInt($tip['percent_pred_draw'] ?? '0');
                    $awayP = percentToInt($tip['percent_pred_away'] ?? '0');
                    if (($winningtip['winning_team'] === "1" && $homeP < 49) ||
                        ($winningtip['winning_team'] === "X" && $drawP < 49) ||
                        ($winningtip['winning_team'] === "2" && $awayP < 49)) {
                        $predictionValue = $doubleChancewinningTip['winning_team'];
                    } else {
                        $predictionValue = $winningtip['winning_team'];
                    }
                }
            } else {
                $homeP = percentToInt($tip['percent_pred_home'] ?? '0');
                $drawP = percentToInt($tip['percent_pred_draw'] ?? '0');
                $awayP = percentToInt($tip['percent_pred_away'] ?? '0');
                if (($winningtip['winning_team'] === "1" && $homeP < 49) ||
                    ($winningtip['winning_team'] === "X" && $drawP < 49) ||
                    ($winningtip['winning_team'] === "2" && $awayP < 49)) {
                    $predictionValue = $doubleChancewinningTip['winning_team'];
                } else {
                    $predictionValue = $winningtip['winning_team'];
                }
            }

            /* ---- Friendly label & chip class ---- */
            $displayPrediction = $predictionValue;
            $chipClass = 'chip-dc';
            if ($predictionValue === "1")         { $displayPrediction = "Home";     $chipClass = 'chip-home'; }
            if ($predictionValue === "2")         { $displayPrediction = "Away";     $chipClass = 'chip-away'; }
            if ($predictionValue === "X")         { $displayPrediction = "Draw";     $chipClass = 'chip-draw'; }
            if (strpos($predictionValue,'Over')  === 0) { $chipClass = 'chip-over'; }
            if (strpos($predictionValue,'Under') === 0) { $chipClass = 'chip-under'; }

            /* ---- Odds ---- */
            $oddsDisplay = '—';
            if (!empty($tip['all_bets_odds'])) {
                try {
                    $oddsData = json_decode($tip['all_bets_odds'], true);
                    if (is_array($oddsData)) {
                        if (strpos($predictionValue, "Over") === 0 || strpos($predictionValue, "Under") === 0) {
                            foreach ($oddsData as $market) {
                                if (($market['name'] ?? '') === "Goals Over/Under" && !empty($market['values'])) {
                                    foreach ($market['values'] as $bet) {
                                        if (($bet['value'] ?? '') === $predictionValue) {
                                            $oddsDisplay = $bet['odd'] ?? '—';
                                            break 2;
                                        }
                                    }
                                }
                            }
                        } elseif (in_array($predictionValue, ['1', 'X', '2'])) {
                            foreach ($oddsData as $market) {
                                if (($market['name'] ?? '') === "Match Winner" && !empty($market['values'])) {
                                    $map = ["1" => "Home", "X" => "Draw", "2" => "Away"];
                                    $label = $map[$predictionValue] ?? '';
                                    foreach ($market['values'] as $bet) {
                                        if (($bet['value'] ?? '') === $label) {
                                            $oddsDisplay = $bet['odd'] ?? '—';
                                            break 2;
                                        }
                                    }
                                }
                            }
                        } elseif (in_array($predictionValue, ['1X', 'X2', '12'])) {
                            foreach ($oddsData as $market) {
                                if (($market['name'] ?? '') === "Double Chance" && !empty($market['values'])) {
                                    $map = ["1X" => "Home/Draw", "12" => "Home/Away", "X2" => "Draw/Away"];
                                    $label = $map[$predictionValue] ?? '';
                                    foreach ($market['values'] as $bet) {
                                        if (($bet['value'] ?? '') === $label) {
                                            $oddsDisplay = $bet['odd'] ?? '—';
                                            break 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (Exception $e) { /* keep default */ }
            }

            /* ---- Score ---- */
            $homeScore = $tip['goals_home'] ?? null;
            $awayScore = $tip['goals_away'] ?? null;
            $scoreDisplay = '—';
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
            }

            /* ---- Probabilities ---- */
            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            /* SVG ring circumference = 2π×r, r=20, circ≈125.66 */
            $circ = 125.66;
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);

            /* ---- Team initials & league ---- */
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));

            /* ---- League: split country / name ---- */
            $leagueFull = $tip['league_name'] ?? '';
            $leagueCountry = $tip['league_country'] ?? '';
            /* Try to detect "Country: League" format */
            if (strpos($leagueFull, ':') !== false) {
                [$leagueCountry, $leagueFull] = array_map('trim', explode(':', $leagueFull, 2));
            }
        ?>

        <?php
            /* ---- Time display ---- */
            $formattedTime = '—';
            $formattedDate = '';
            if (!empty($tip['date'])) {
                $formattedTime = DateTimeToUsersTimezone($tip['date']);
            }
        ?>

        <div class="match-card">

            <!-- Col 1: Time -->
            <div class="mc-time">
                <span class="time-val"><?php echo htmlspecialchars($formattedTime); ?></span>
                <?php if ($formattedDate): ?>
                <span class="date-val"><?php echo htmlspecialchars($formattedDate); ?></span>
                <?php endif; ?>
            </div>

            <!-- Col 2: Match — league small above, teams inline -->
            <div class="mc-match">
                <span class="league-tag">
                    <?php echo htmlspecialchars($leagueCountry ? $leagueCountry . ' · ' . $leagueFull : $leagueFull); ?>
                </span>
                <div class="teams-inline">
                    <div class="team-crest home-crest"><?php echo $homeInitial; ?></div>
                    <span class="team-name-text"><?php echo htmlspecialchars($tip['home_team_name'] ?? ''); ?></span>
                    <span class="vs-badge" style="text-align:left;">VS</span>
                    <div class="team-crest"><?php echo $awayInitial; ?></div>
                    <span class="team-name-text"><?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?></span>
                </div>
            </div>

            <!-- Col 3: Odds -->
            <div class="mc-odds">
                <div class="odds-value"><?php echo htmlspecialchars($oddsDisplay); ?></div>
                <div class="odds-label">Odds</div>
            </div>

            <!-- Col 4: Probability rings -->
            <div class="mc-prob">
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 52 52">
                            <circle class="track" cx="26" cy="26" r="20"/>
                            <circle class="fill-home" cx="26" cy="26" r="20"
                                stroke-dasharray="<?php echo $dashHome; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $homePercent; ?></div>
                    </div>
                    <span class="prob-label">Home</span>
                </div>
                <div class="prob-sep"></div>
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 52 52">
                            <circle class="track" cx="26" cy="26" r="20"/>
                            <circle class="fill-draw" cx="26" cy="26" r="20"
                                stroke-dasharray="<?php echo $dashDraw; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $drawPercent; ?></div>
                    </div>
                    <span class="prob-label">Draw</span>
                </div>
                <div class="prob-sep"></div>
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 52 52">
                            <circle class="track" cx="26" cy="26" r="20"/>
                            <circle class="fill-away" cx="26" cy="26" r="20"
                                stroke-dasharray="<?php echo $dashAway; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value"><?php echo $awayPercent; ?></div>
                    </div>
                    <span class="prob-label">Away</span>
                </div>
            </div>

            <!-- Col 5: Prediction chip -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
            </div>

            <!-- Col 6: Score -->
            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <div class="score-status">FT</div>
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