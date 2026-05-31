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
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [

    {
      "@type": "WebSite",
      "name": "BetAssured",
      "url": "https://www.betassured.com/",
      "description": "All football predictions today, free daily soccer tips, and expert betting insights across top leagues worldwide.",
      "inLanguage": "en",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.betassured.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },

    {
      "@type": "Organization",
      "name": "BetAssured",
      "url": "https://www.betassured.com/",
      "logo": {
        "@type": "ImageObject",
        "url": "https://www.betassured.com/betsassured.png"
      }
    },

    {
      "@type": "WebPage",
      "name": "All Football Predictions Today",
      "url": "https://www.betassured.com/all-predictions",
      "description": "Explore all football predictions today including 1X2 tips, over/under predictions, and expert match analysis with probability insights.",
      "isPartOf": {
        "@type": "WebSite",
        "name": "BetAssured",
        "url": "https://www.betassured.com/"
      },
      "inLanguage": "en"
    },

    {
      "@type": "FAQPage",
      "mainEntity": [

        {
          "@type": "Question",
          "name": "What are football predictions?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Football predictions are expert analyses of matches based on statistics, team form, and historical data to estimate possible outcomes."
          }
        },

        {
          "@type": "Question",
          "name": "What are high confidence football predictions?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "High confidence predictions are tips supported by strong statistical indicators such as team form, head-to-head performance, and probability analysis suggesting a higher likelihood of success."
          }
        },

        {
          "@type": "Question",
          "name": "Are football predictions guaranteed?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "No. Football predictions are based on probability and analysis, but outcomes are never guaranteed. Betting always involves risk."
          }
        },

        {
          "@type": "Question",
          "name": "How often are predictions updated?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Predictions are updated daily to reflect the latest fixtures, team news, and statistical insights from leagues worldwide."
          }
        }

      ]
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
$markdownContent = file_get_contents(BASE_PATH . '/components/seo-content/all-predictions.content.md');
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

?>

<main class="container">
    <h1 class="page-hero-title">All Football Predictions Today</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <div class="section-title-bar">
        <h2>Today's Football Predictions</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <p style="color: #4b5563; margin-bottom: 20px;">
        Explore our <strong>all football predictions</strong> for today, including <strong>1X2 tips</strong>, <strong>double chance picks</strong>, and <strong>over/under predictions</strong>. Every match comes with probability ratings, odds insight, and expert analysis to help you make better betting decisions.
    </p>

    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Probability</span>
        <span style="text-align:center">Odds</span>
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
                    if (
                        ($winningtip['winning_team'] === "1" && $homeP < 49) ||
                        ($winningtip['winning_team'] === "X" && $drawP < 49) ||
                        ($winningtip['winning_team'] === "2" && $awayP < 49)
                    ) {
                        $predictionValue = $doubleChancewinningTip['winning_team'];
                    } else {
                        $predictionValue = $winningtip['winning_team'];
                    }
                }
            } else {
                $homeP = percentToInt($tip['percent_pred_home'] ?? '0');
                $drawP = percentToInt($tip['percent_pred_draw'] ?? '0');
                $awayP = percentToInt($tip['percent_pred_away'] ?? '0');
                if (
                    ($winningtip['winning_team'] === "1" && $homeP < 49) ||
                    ($winningtip['winning_team'] === "X" && $drawP < 49) ||
                    ($winningtip['winning_team'] === "2" && $awayP < 49)
                ) {
                    $predictionValue = $doubleChancewinningTip['winning_team'];
                } else {
                    $predictionValue = $winningtip['winning_team'];
                }
            }

            $displayPrediction = $predictionValue;
            $chipClass = 'chip-dc';
            if ($predictionValue === "1") { $displayPrediction = "Home"; $chipClass = 'chip-home'; }
            if ($predictionValue === "2") { $displayPrediction = "Away"; $chipClass = 'chip-away'; }
            if ($predictionValue === "X") { $displayPrediction = "Draw"; $chipClass = 'chip-draw'; }
            if (strpos($predictionValue, 'Over') === 0) { $chipClass = 'chip-over'; }
            if (strpos($predictionValue, 'Under') === 0) { $chipClass = 'chip-under'; }

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
                } catch (Exception $e) {
                    /* keep default */
                }
            }

            /* ---- Score ---- */
            /* ---- Score, match status & result badge ---- */
            $homeScore     = $tip['goals_home'] ?? null;
            $awayScore     = $tip['goals_away'] ?? null;
            $scoreDisplay  = '—';
            $matchStatus   = $tip['status_short'] ?? null;
            $winningStatus = '';

            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay  = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $winningStatus = DetermineWinningOrLost($predictionValue, $homeScore, $awayScore);
            }
            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            $circ = 125.66;
            $dashHome = round(($homePercent / 100) * $circ, 2);
            $dashDraw = round(($drawPercent / 100) * $circ, 2);
            $dashAway = round(($awayPercent / 100) * $circ, 2);

            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));

            $leagueFull = $tip['league_name'] ?? '';
            $leagueCountry = $tip['league_country'] ?? '';
            if (strpos($leagueFull, ':') !== false) {
                [$leagueCountry, $leagueFull] = array_map('trim', explode(':', $leagueFull, 2));
            }

            $formattedTime = '—';
            $formattedDate = '';
            if (!empty($tip['date'])) {
                $dateTime = DateTimeToUsersTimezone($tip['date']);
                if (strpos($dateTime, ' ') !== false) {
                    $parts = explode(' ', $dateTime, 2);
                    $formattedDate = $parts[0];
                    $formattedTime = $parts[1];
                } else {
                    $formattedTime = $dateTime;
                }
            }
            
            $hasScore = ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '');
            $statusShort = htmlspecialchars($tip['status_short'] ?? '');   // e.g. "FT", "HT", "1H", "NS"
        ?>

       <!-- SINGLE MATCH CARD FOR BOTH DESKTOP AND MOBILE -->
        <div class="match-card">
            <!-- Time Column (hidden on mobile via CSS) -->
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
            
            <!-- Prediction Column -->
            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
                </span>
            </div>

            <!-- Probability Rings Column -->
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

            <!-- Odds Column -->
            <div class="mc-odds">
                <div class="odds-value"><?php echo htmlspecialchars($oddsDisplay); ?></div>
                <div class="odds-label">Odds</div>
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

    <section class="seo-section">
        <div class="blog-2 seo-content">
            <?php echo $htmlContent; ?>
        </div>
    </section>
</main>

<?php
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
