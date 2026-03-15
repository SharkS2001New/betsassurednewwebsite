<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>All Football Predictions Today | Free Daily Soccer Tips</title>
<meta name="title" content="All Football Predictions Today | Free Daily Soccer Tips">
<meta name="description" content="Get all football predictions today with free daily soccer tips, expert match analysis, 1X2 picks, over under predictions, and reliable betting insights across top leagues worldwide.">
<meta name="keywords" content="all football predictions, football predictions today, free football predictions, daily soccer tips, 1x2 football predictions, over under predictions, today football tips, expert football predictions">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="All Football Predictions Today | Free Daily Soccer Tips">
<meta property="og:description" content="Get all football predictions today with free daily soccer tips, expert match analysis, 1X2 picks, over under predictions, and reliable betting insights across top leagues worldwide.">
<meta property="og:url" content="https://www.betassured.com/all-predictions">
<meta property="og:site_name" content="BetAssured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="All Football Predictions Today | Free Daily Soccer Tips">
<meta name="twitter:description" content="Get all football predictions today with free daily soccer tips, expert match analysis, 1X2 picks, over under predictions, and reliable betting insights across top leagues worldwide.">
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/all-predictions.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_all_matches_fixtures_no_limit";
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
.section-title-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 30px 0 15px;
}
.section-title-bar h1 {
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
.match-card:last-child { border-bottom: none; }
.match-card:hover { background: #f8f9fa; }
.mc-time { font-weight: 500; color: #333; font-size: 14px; }
.mc-match { display: flex; flex-direction: column; gap: 4px; min-width: 0; }
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
.teams-inline { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
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
.home-crest { background: linear-gradient(135deg, #05384B, #0a4a60); }
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
.mc-odds { text-align: center; }
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
.mc-prob { display: flex; align-items: center; justify-content: space-between; gap: 5px; }
.prob-item { text-align: center; flex: 1; }
.prob-ring { position: relative; width: 40px; height: 40px; margin: 0 auto 4px; }
.prob-ring svg { width: 40px; height: 40px; transform: rotate(-90deg); }
.prob-ring circle { fill: none; stroke-width: 3; }
.prob-ring .track { stroke: #e9ecef; }
.prob-ring .fill-home { stroke: #05384B; stroke-linecap: round; }
.prob-ring .fill-draw { stroke: #6c757d; stroke-linecap: round; }
.prob-ring .fill-away { stroke: #dc3545; stroke-linecap: round; }
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
.prob-sep { width: 1px; height: 25px; background: #dee2e6; }
.mc-prediction { text-align: center; }
.pred-chip {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    min-width: 60px;
    text-align: center;
}
.chip-home  { background: #05384B; color: white; }
.chip-away  { background: #dc3545; color: white; }
.chip-draw  { background: #6c757d; color: white; }
.chip-over  { background: #10b981; color: white; }
.chip-under { background: #f59e0b; color: white; }
.chip-dc    { background: #ec4899; color: white; }
.mc-score { text-align: center; }
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
.state-msg {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
    border: 1px solid #dee2e6;
    border-radius: 8px;
}
@media (max-width: 992px) {
    .preds-table-header { display: none; }
    .match-card {
        grid-template-columns: 1fr;
        gap: 10px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 10px;
    }
}
</style>

<main class="container py-4">
    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <div class="section-title-bar">
        <h1>All Football Predictions Today</h1>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <p style="color: #4b5563; margin-bottom: 20px;">
        Explore our <strong>all football predictions</strong> for today, including <strong>1X2 tips</strong>, <strong>double chance picks</strong>, and <strong>over/under predictions</strong>. Every match comes with probability ratings, odds insight, and expert analysis to help you make better betting decisions.
    </p>

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
                $tip['teams_perfomance_home_for'] ?? null,
                $tip['teams_perfomance_home_aganist'] ?? $tip['teams_perfomance_home_against'] ?? null,
                $tip['teams_perfomance_away_for'] ?? null,
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
            if ($predictionValue === "1") { $displayPrediction = "Home"; $chipClass = 'chip-home'; }
            if ($predictionValue === "2") { $displayPrediction = "Away"; $chipClass = 'chip-away'; }
            if ($predictionValue === "X") { $displayPrediction = "Draw"; $chipClass = 'chip-draw'; }
            if (strpos($predictionValue, 'Over') === 0) { $chipClass = 'chip-over'; }
            if (strpos($predictionValue, 'Under') === 0) { $chipClass = 'chip-under'; }

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
            $scoreStatus = 'UPCOMING';
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $scoreStatus = 'FT';
            }

            /* ---- Probabilities ---- */
            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            $circ = 125.66;
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);

            /* ---- Team initials & league ---- */
            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));

            $leagueFull = $tip['league_name'] ?? '';
            $leagueCountry = $tip['league_country'] ?? '';
            if (strpos($leagueFull, ':') !== false) {
                [$leagueCountry, $leagueFull] = array_map('trim', explode(':', $leagueFull, 2));
            }

            /* ---- Time display ---- */
            $formattedTime = '—';
            $formattedDate = '';
            if (!empty($tip['date'])) {
                $formattedTime = DateTimeToUsersTimezone($tip['date']);
            }
        ?>

        <div class="match-card">

            <div class="mc-time">
                <span class="time-val"><?php echo htmlspecialchars($formattedTime); ?></span>
                <?php if ($formattedDate): ?>
                <span class="date-val"><?php echo htmlspecialchars($formattedDate); ?></span>
                <?php endif; ?>
            </div>

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

            <div class="mc-odds">
                <div class="odds-value"><?php echo htmlspecialchars($oddsDisplay); ?></div>
                <div class="odds-label">Odds</div>
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

            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
            </div>

            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <div class="score-status"><?php echo $scoreStatus; ?></div>
            </div>

        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <section class="seo-section">
        <div class="blog-2 seo-content">
            <?php echo $htmlContent; ?>
        </div>
    </section>

</main>

<?php
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
