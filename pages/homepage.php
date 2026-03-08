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

// Preloader & Header
include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "@id": "https://www.accuratestakes.com/",
      "name": "AccurateStakes",
      "url": "https://www.accuratestakes.com/",
      "logo": "https://www.accuratestakes.com/accuratestakes.png",
      "description": "AccurateStakes provides free daily football predictions, expert tips, accumulator guides, jackpots, and betting insights based on team form, statistics, and performance analysis.",
      "sameAs": [
        "https://t.me/accuratestakes",
        "https://wa.me/2348100245895"
      ]
    },
    {
      "@type": "WebSite",
      "@id": "https://www.accuratestakes.com/",
      "url": "https://www.accuratestakes.com/",
      "name": "AccurateStakes",
      "publisher": {
        "@id": "https://www.accuratestakes.com/"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://www.accuratestakes.com/?s={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "WebPage",
      "@id": "https://www.accuratestakes.com/",
      "url": "https://www.accuratestakes.com/",
      "name": "AccurateStakes - Free Football Predictions & Daily Tips",
      "isPartOf": {
        "@id": "https://www.accuratestakes.com/"
      },
      "about": {
        "@id": "https://www.accuratestakes.com/about-us"
      },
      "description": "AccurateStakes offers free daily football predictions, expert match analysis, accumulator tips, jackpots, and betting insights to help users make smarter betting decisions.",
      "inLanguage": "en"
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/homepage.content.md');
$htmlContent = $Parsedown->text($markdownContent);

/**
 * Convert percentage string to integer
 */
function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

/**
 * Get Prediction - Kept for backward compatibility
 */
function getPrediction($tip) {
    $percentHome = percentToInt($tip['percent_pred_home'] ?? '0');
    $percentDraw = percentToInt($tip['percent_pred_draw'] ?? '0');
    $percentAway = percentToInt($tip['percent_pred_away'] ?? '0');
    
    if ($percentHome >= $percentDraw && $percentHome >= $percentAway) {
        return ['prediction' => '1', 'odds' => $tip['bets_home'] ?? null];
    } elseif ($percentDraw >= $percentHome && $percentDraw >= $percentAway) {
        return ['prediction' => 'X', 'odds' => $tip['bets_draw'] ?? null];
    } else {
        return ['prediction' => '2', 'odds' => $tip['bets_away'] ?? null];
    }
}

// Fetch data from API using PHP
$apiUrl = "https://api.pitchpredictions.com/api/fetch_homepage_preds_match_tips";
$token = "R9TxV3PbOEu7qZnJKgydC5LmX2";
$currentDate = date('Y-m-d');

$tipsData = [];
$error = null;
$empty = false;

// Initialize cURL session
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl . "?fixture_date=" . $currentDate);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $token
]);
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
<main class="desktop-container" style="width: 100%; background-color: white; border: 1px solid #ddd">
    <section class="container py-3 py-md-4 d-flex mt-1 mb-1">
        <div class="row">
            <div class="col-12">
                <h1 style="font-size:28px;font-weight:bold">Get the Best Free Football Tips at Accurate Stakes</h1>
                <p>
                Are you looking for a <strong>Successful Soccer Prediction website</strong> that delivers accurate tips without
                    trying to empty your wallet. Well, your search is now over! Here you have it!
                    A completely free football prediction website that is accurate and reliable and also 
                    provides value without any cost or waste. This website is your new best friend if you're 
                    serious about improving your betting success without spending any money. 
                    Try it now to begin making more money with less risk. </p>
            </div>     
        </div>  
    </section>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Popular Tips Today -->
<?php include_once BASE_PATH . "/components/shared/popular-tips.shared.php"; ?>

    <section class="container mt-3 mb-2">
        <div class="row">
            <div class="col-md-10 col-lg-8 mx-auto">
                <a href="https://t.me/accuratestakesTM" target="_blank" rel="noopener noreferrer" class="text-decoration-none d-block">
                    <div class="p-2 px-3 d-flex flex-column flex-md-row align-items-center justify-content-between text-white rounded shadow-sm" style="background-color: #2481cc;">
                        
                        <div class="text-center text-md-start mb-2 mb-md-0">
                            <span class="fw-bold d-block text-white" style="font-size: 1rem;">Join Our Official Telegram Channel!</span>
                            <small class="text-white">Get instant access to exclusive free VIP tips and daily updates.</small>
                        </div>
                        
                        <span class="btn btn-light btn-sm fw-bold px-3 rounded-pill text-nowrap" style="color: #2481cc; text-transform: uppercase; font-size: 13px;">
                            Join Now
                        </span> 
                    </div>
                </a>
            </div>
        </div>
    </section>
    <?php include_once BASE_PATH . "/components/shared/accumulator_tips.shared.php"; ?>
    <!-- Main predictions table - EXACT MATCH to JavaScript -->
    <div class="container-md-fluid mt-4">
        <div class="table-responsive">
            <?php if ($error): ?>
                <div class="alert alert-danger text-center">
                    Our Experts are working on the Predictions Please Check Back in Few Mins!!!
                </div>
            <?php elseif ($empty): ?>
                <div class="text-center py-4 text-muted">
                    Working On the Predictions Check Back Later!!
                </div>
            <?php else: ?>
                <table id="tips-table" class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 10%">Time</th>
                            <th scope="col" style="width: 20%">League</th>
                            <th scope="col" style="width: 40%">Match</th>
                            <th scope="col" class="d-none d-md-table-cell text-left">Tip</th>
                            <th scope="col" class="d-md-none text-center">Tip</th>
                            <th scope="col" class="d-none d-md-table-cell text-left">Odds</th>
                            <th scope="col">Scores</th>
                        </tr>
                    </thead>
                    <tbody id="tips-container">
                        <?php foreach ($tipsData as $tip): 
                            // Calculate fixture average using EXACT JS method
                            $fixturesAverage = ComputeFixtureAverage(
                                $tip['teams_perfomance_home_for'] ?? null,
                                $tip['teams_perfomance_home_aganist'] ?? $tip['teams_perfomance_home_against'] ?? null,
                                $tip['teams_perfomance_away_for'] ?? null,
                                $tip['teams_perfomance_away_aganist'] ?? $tip['teams_perfomance_away_against'] ?? null,
                                $tip['teams_games_played_home'] ?? null,
                                $tip['teams_games_played_away'] ?? null
                            );

                            // Get predictions using EXACT JS methods
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

                            // Determine prediction to display
                            $predictionDisplay = '';
                            $predictionValue = '';
                            
                            // Check if fixturesAverage is valid and outside 2.0-3.0 range
                            if ($fixturesAverage !== "-" && floatval($fixturesAverage) > 0) {
                                $avgFloat = floatval($fixturesAverage);
                                if ($avgFloat < 2.0 || $avgFloat > 3.0) {
                                    if ($avgFloat > 2.5) {
                                        $predictionValue = "Over2.5";
                                    } else {
                                        $predictionValue = "Under2.5";
                                    }
                                    $predictionDisplay = $predictionValue . "&nbsp;&nbsp;" . DetermineWinningOrLost($predictionValue, $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                                } else {
                                    // Regular 1X2 or Double Chance logic
                                    $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
                                    $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
                                    $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');
                                    
                                    // Check if winning tip percentage is below 49% for double chance
                                    if (($winningtip['winning_team'] === "1" && $homePercent < 49) ||
                                        ($winningtip['winning_team'] === "X" && $drawPercent < 49) ||
                                        ($winningtip['winning_team'] === "2" && $awayPercent < 49)) {
                                        $predictionValue = $doubleChancewinningTip['winning_team'];
                                        $predictionDisplay = $doubleChancewinningTip['winning_team'] . "&nbsp;&nbsp;" . DetermineWinningOrLost($doubleChancewinningTip['winning_team'], $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                                    } else {
                                        $predictionValue = $winningtip['winning_team'];
                                        $predictionDisplay = $winningtip['winning_team'] . "&nbsp;&nbsp;" . DetermineWinningOrLost($winningtip['winning_team'], $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                                    }
                                }
                            } else {
                                // If fixture average is invalid, default to 1X2
                                $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
                                $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
                                $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');
                                
                                if (($winningtip['winning_team'] === "1" && $homePercent < 49) ||
                                    ($winningtip['winning_team'] === "X" && $drawPercent < 49) ||
                                    ($winningtip['winning_team'] === "2" && $awayPercent < 49)) {
                                    $predictionValue = $doubleChancewinningTip['winning_team'];
                                    $predictionDisplay = $doubleChancewinningTip['winning_team'] . "&nbsp;&nbsp;" . DetermineWinningOrLost($doubleChancewinningTip['winning_team'], $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                                } else {
                                    $predictionValue = $winningtip['winning_team'];
                                    $predictionDisplay = $winningtip['winning_team'] . "&nbsp;&nbsp;" . DetermineWinningOrLost($winningtip['winning_team'], $tip['goals_home'] ?? null, $tip['goals_away'] ?? null);
                                }
                            }

                            // Get odds
                            $oddsDisplay = '-';
                            if (!empty($tip['all_bets_odds'])) {
                                try {
                                    $oddsData = json_decode($tip['all_bets_odds'], true);
                                    
                                    if (is_array($oddsData)) {
                                        // Handle Over/Under markets
                                        if (strpos($predictionValue, "Over") === 0 || strpos($predictionValue, "Under") === 0) {
                                            foreach ($oddsData as $market) {
                                                if (isset($market['name']) && $market['name'] === "Goals Over/Under" && isset($market['values']) && is_array($market['values'])) {
                                                    $formattedValue = str_replace("Over", "Over ", str_replace("Under", "Under ", $predictionValue));
                                                    foreach ($market['values'] as $bet) {
                                                        if (isset($bet['value']) && $bet['value'] === $formattedValue) {
                                                            $oddsDisplay = $bet['odd'] ?? '-';
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        // Handle 1X2 (Match Winner)
                                        elseif (in_array($predictionValue, ['1', 'X', '2'])) {
                                            foreach ($oddsData as $market) {
                                                if (isset($market['name']) && $market['name'] === "Match Winner" && isset($market['values']) && is_array($market['values'])) {
                                                    $map = ["1" => "Home", "X" => "Draw", "2" => "Away"];
                                                    $label = $map[$predictionValue] ?? '';
                                                    foreach ($market['values'] as $bet) {
                                                        if (isset($bet['value']) && $bet['value'] === $label) {
                                                            $oddsDisplay = $bet['odd'] ?? '-';
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        // Handle Double Chance
                                        elseif (in_array($predictionValue, ['1X', 'X2', '12'])) {
                                            foreach ($oddsData as $market) {
                                                if (isset($market['name']) && $market['name'] === "Double Chance" && isset($market['values']) && is_array($market['values'])) {
                                                    $map = ["1X" => "Home/Draw", "12" => "Home/Away", "X2" => "Draw/Away"];
                                                    $label = $map[$predictionValue] ?? '';
                                                    foreach ($market['values'] as $bet) {
                                                        if (isset($bet['value']) && $bet['value'] === $label) {
                                                            $oddsDisplay = $bet['odd'] ?? '-';
                                                            break 2;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } catch (Exception $e) {
                                    $oddsDisplay = '-';
                                }
                            }

                            // Format time using EXACT JS method
                            $formattedTime = '-';
                            if (!empty($tip['date'])) {
                                $formattedTime = DateTimeToUsersTimezone($tip['date']);
                            }

                            // Get scores for display
                            $homeScore = $tip['goals_home'] ?? null;
                            $awayScore = $tip['goals_away'] ?? null;
                            $scoreDisplay = '-';
                            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                                $scoreDisplay = htmlspecialchars($homeScore . ' - ' . $awayScore);
                            }
                        ?>
                        <tr class="align-middle">
                            <td style="width: 10%;">
                                <?php echo $formattedTime; ?>
                            </td>
                            
                            <!-- Desktop League Name and Logo -->
                            <td class="d-none d-md-table-cell" style="width: 20%; font-weight: 500;">
                                <?php if (!empty($tip['downloaded_league_logo'])): ?>
                                    <img src="<?php echo htmlspecialchars($tip['downloaded_league_logo']); ?>" 
                                        alt="<?php echo htmlspecialchars($tip['league_name'] ?? ''); ?>" 
                                        class="me-2" 
                                        style="width: 20px; height: 20px; vertical-align: middle;">
                                <?php endif; ?>
                                <?php echo htmlspecialchars($tip['league_name'] ?? ''); ?>
                            </td>
                            
                            <!-- Mobile League Short Name -->
                            <td class="d-md-none" style="width: 10%; font-weight: 500;">
                                <?php echo htmlspecialchars($tip['league_short_name'] ?? ''); ?>
                            </td>
                            
                            <!-- Matchup -->
                            <td style="width: 40%;">
                                <?php echo htmlspecialchars($tip['home_team_name'] ?? ''); ?> 
                                <span class="text-danger">vs</span> 
                                <?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?>
                            </td>
                            
                            <!-- Desktop Prediction & Result -->
                            <td class="d-none d-md-table-cell">
                                <div style="display: inline-block; margin-right: 4px; font-weight: bold; font-size: 15px;">
                                    <?php echo $predictionDisplay; ?>
                                </div>
                            </td>
                            
                            <!-- Mobile Prediction & Result -->
                            <td class="d-md-none text-center" style="width: 10%;font-size:14px">
                                <span style="color: #212529; font-weight: bold;"><?php echo $predictionValue; ?></span><br><br>
                                <?php echo DetermineWinningOrLost($predictionValue, $tip['goals_home'] ?? null, $tip['goals_away'] ?? null); ?>
                            </td>
                            
                            <!-- Odds - Desktop only -->
                            <td class="d-none d-md-table-cell">
                                <?php echo $oddsDisplay; ?>
                            </td>

                            <!-- Score -->
                            <td class="fw-bold">
                                <?php echo $scoreDisplay; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <!-- Seo Content -->
    <section class="container py-3 py-md-4 d-flex">
        <div class="row">
            <div class="blog-2 col-md-12 seo-content">
                <?php echo $htmlContent; ?>
            </div>
        </div>
    </section>
</main>
<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
