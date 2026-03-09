<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Betsassured: Best Prediction Site - Free Football Tips</title>
<meta name="title" content="Accurate Football Predictions & Sure Tips">
<meta name="description" content="Get the edge with reliable predictions, daily free tips, and expert insights tailored for consistent football betting success.">
<meta name="keywords" content="free prediction site, daily predictions, successful soccer prediction  accurate football predictions, best football prediction site, accurate predictions, daily soccer tips, sure tips, accurate tip">

<!-- Open Graph -->
<meta property="og:title" content="Betsassured - Accurate Prediction Site">
<meta property="og:description" content="Betsassured provides free football predictions daily from experienced tipsters.">

<!-- Twitter -->
<meta property="twitter:title" content="Betsassured - Accurate Prediction Site ">
<meta property="twitter:description" content="Betsassured provides free football predictions daily from experienced tipsters.">
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/mwanasoka-tips.content.md');
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


<main class="container py-4">
    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <?php include_once BASE_PATH . "/components/shared/popular-tips.shared.php"; ?>

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