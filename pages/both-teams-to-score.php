<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>BTTS Predictions Today | Both Teams to Score Tips & Analysis</title>
<meta name="title" content="BTTS Predictions Today | Both Teams to Score Tips & Analysis">
<meta name="description" content="Free BTTS predictions today with confidence ratings, odds, and expert analysis. Both teams to score tips updated daily across Premier League, La Liga, Bundesliga and more.">
<meta name="keywords" content="btts predictions today, both teams to score tips, btts tips today, btts football predictions, both teams to score today, free btts tips, btts yes no predictions">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="BTTS Predictions Today | Both Teams to Score Tips & Analysis">
<meta property="og:description" content="Free BTTS predictions today with confidence ratings, odds, and expert analysis. Both teams to score tips updated daily across Premier League, La Liga, Bundesliga and more.">
<meta property="og:url" content="https://www.betnumbers.com/btts-predictions">
<meta property="og:site_name" content="Betnumbers">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="BTTS Predictions Today | Both Teams to Score Tips & Analysis">
<meta name="twitter:description" content="Free BTTS predictions today with confidence ratings, odds, and expert analysis. Both teams to score tips updated daily across Premier League, La Liga, Bundesliga and more.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [

    {
      "@type": "WebSite",
      "name": "Betnumbers",
      "url": "https://www.betnumbers.com"
    },

    {
      "@type": "WebPage",
      "name": "BTTS Predictions Today | Both Teams to Score Tips & Analysis",
      "url": "https://www.betnumbers.com/btts-predictions",
      "description": "Free BTTS predictions today with confidence ratings, odds, and expert analysis. Both teams to score tips updated daily across Premier League, La Liga, Bundesliga and more.",
      "inLanguage": "en",
      "isPartOf": {
        "@type": "WebSite",
        "name": "Betnumbers",
        "url": "https://www.betnumbers.com"
      }
    },

    {
      "@type": "CollectionPage",
      "name": "BTTS Predictions Today",
      "url": "https://www.betnumbers.com/btts-predictions",
      "description": "Daily both teams to score predictions with probabilities, odds and expert insights across top football leagues."
    },

    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://www.betnumbers.com"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "BTTS Predictions",
          "item": "https://www.betnumbers.com/btts-predictions"
        }
      ]
    },

    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What does BTTS mean in football betting?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "BTTS stands for Both Teams To Score. It means both the home and away teams must score at least one goal during the match."
          }
        },
        {
          "@type": "Question",
          "name": "How accurate are BTTS predictions?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "BTTS predictions are based on statistical analysis such as team scoring trends, defensive records, and head-to-head data. While accuracy is high, no prediction is guaranteed."
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/both-teams-to-score.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_btts_matches_fixtures";
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
 * Calculate BTTS confidence based on multiple factors including odds
 * Confidence is always between 50-95%
 */
function calculateBttsConfidence($tip, $prediction, $odd) {
    $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
    $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
    $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

    $homeFor = floatval($tip['teams_perfomance_home_for'] ?? 0);
    $homeAgainst = floatval($tip['teams_perfomance_home_aganist'] ?? $tip['teams_perfomance_home_against'] ?? 0);
    $awayFor = floatval($tip['teams_perfomance_away_for'] ?? 0);
    $awayAgainst = floatval($tip['teams_perfomance_away_aganist'] ?? $tip['teams_perfomance_away_against'] ?? 0);
    $homeGames = floatval($tip['teams_games_played_home'] ?? 1);
    $awayGames = floatval($tip['teams_games_played_away'] ?? 1);

    if ($homeGames <= 0) $homeGames = 1;
    if ($awayGames <= 0) $awayGames = 1;

    $homeAvgScored    = $homeFor / $homeGames;
    $homeAvgConceded  = $homeAgainst / $homeGames;
    $awayAvgScored    = $awayFor / $awayGames;
    $awayAvgConceded  = $awayAgainst / $awayGames;

    $avgTotalGoals = ($homeFor + $homeAgainst + $awayFor + $awayAgainst) / ($homeGames + $awayGames);

    $confidence = 60;

    if ($prediction === 'Yes') {
        $drawFactor    = $drawPercent * 0.3;
        $balanceFactor = 100 - (abs($homePercent - $awayPercent) * 0.4);
        $goalsFactor   = min(25, $avgTotalGoals * 8);
        $scoringFactor = 0;
        if ($homeAvgScored   > 1.2) $scoringFactor += 5;
        if ($homeAvgConceded > 1)   $scoringFactor += 5;
        if ($awayAvgScored   > 1.2) $scoringFactor += 5;
        if ($awayAvgConceded > 1)   $scoringFactor += 5;
        $oddsFactor = 0;
        if (is_numeric($odd) && $odd > 0) {
            if ($odd <= 1.50)      $oddsFactor = 15;
            elseif ($odd <= 1.70)  $oddsFactor = 10;
            elseif ($odd <= 2.00)  $oddsFactor = 5;
        }
        $confidence = $drawFactor + $balanceFactor / 2 + $goalsFactor + $scoringFactor + $oddsFactor;
    } else {
        $maxPercent      = max($homePercent, $awayPercent);
        $dominanceFactor = $maxPercent * 0.4;
        $goalsFactor     = max(0, 20 - ($avgTotalGoals * 5));
        $defenseFactor   = 0;
        if ($homeAvgConceded < 0.8) $defenseFactor += 8;
        if ($awayAvgConceded < 0.8) $defenseFactor += 8;
        $oddsFactor = 0;
        if (is_numeric($odd) && $odd > 0) {
            if ($odd <= 1.60)      $oddsFactor = 15;
            elseif ($odd <= 1.80)  $oddsFactor = 10;
            elseif ($odd <= 2.10)  $oddsFactor = 5;
        }
        $confidence = $dominanceFactor + $goalsFactor + $defenseFactor + $oddsFactor + 10;
    }

    return min(95, max(50, round($confidence)));
}

function findBttsOdd($allBets) {
    if (empty($allBets)) return null;
    foreach ($allBets as $market) {
        if ($market['name'] === "Both Teams Score" && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === "Yes") return floatval($bet['odd']);
            }
        }
    }
    return null;
}

function findBttsNoOdd($allBets) {
    if (empty($allBets)) return null;
    foreach ($allBets as $market) {
        if ($market['name'] === "Both Teams Score" && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === "No") return floatval($bet['odd']);
            }
        }
    }
    return null;
}

function getBttsChipClass($prediction) {
    return $prediction === "Yes" ? 'chip-btts' : 'chip-btts-no';
}

function getBttsDisplayText($prediction) {
    return $prediction === "Yes" ? 'BTTS Yes' : 'BTTS No';
}

/**
 * Safe function to get winning status without errors
 */
function getSafeWinningStatus($prediction, $homeScore, $awayScore, $matchStatus) {
    // Only calculate if match is finished
    if (($matchStatus === 'FT' || $matchStatus === 'AET' || $matchStatus === 'PEN') && 
        $homeScore !== null && $awayScore !== null && 
        $homeScore !== '' && $awayScore !== '') {
        
        // For BTTS, winning means both teams scored
        $actualBtts = ($homeScore > 0 && $awayScore > 0) ? 'Yes' : 'No';
        
        if ($actualBtts === $prediction) {
            return 'Won';
        } else {
            return 'Lost';
        }
    }
    return '';
}
?>

<main class="container">
    <h1 class="page-hero-title">BTTS Predictions Today | Both Teams to Score Tips</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Both Teams to Score (BTTS) Predictions</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>BTTS predictions</strong> show whether both teams are expected to score (<span style="color: #8b5cf6; font-weight: 600;">BTTS Yes</span>) or not (<span style="color: #f59e0b; font-weight: 600;">BTTS No</span>) in each match. Confidence percentages (50–95%) are calculated using team performance, 1X2 probabilities, and current odds.
    </p>

    <!-- BTTS Stats Bar -->
    <?php
    $yesCount = 0;
    $noCount  = 0;
    foreach ($tipsData as $tip) {
        if (isset($tip['both_team_to_score'])) {
            if ($tip['both_team_to_score'] === 'Yes') $yesCount++;
            elseif ($tip['both_team_to_score'] === 'No') $noCount++;
        }
    }
    ?>
    <div class="btts-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo count($tipsData); ?></span>
            <span class="stat-label">Total Matches</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #8b5cf6;"><?php echo $yesCount; ?></span>
            <span class="stat-label">BTTS Yes</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #f59e0b;"><?php echo $noCount; ?></span>
            <span class="stat-label">BTTS No</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">50–95%</span>
            <span class="stat-label">Confidence Range</span>
        </div>
    </div>

    <!-- Column Headers -->
    <div class="preds-table-header">
        <span>Time</span>
        <span>Match</span>
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Confidence</span>
        <span style="text-align:center">Odds</span>
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
                No BTTS predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php foreach ($tipsData as $tip):

            $bttsPrediction = $tip['both_team_to_score'] ?? 'Yes';

            $bttsOdd = '—';
            if (!empty($tip['all_bets_odds'])) {
                try {
                    $oddsData = json_decode($tip['all_bets_odds'], true);
                    if (is_array($oddsData)) {
                        $bttsOdd = ($bttsPrediction === 'Yes')
                            ? (findBttsOdd($oddsData) ?: '—')
                            : (findBttsNoOdd($oddsData) ?: '—');
                    }
                } catch (Exception $e) { /* keep default */ }
            }

            $bttsConfidence = calculateBttsConfidence($tip, $bttsPrediction, $bttsOdd);

            /* ---- Score, match status & result badge ---- */
            $homeScore     = $tip['goals_home'] ?? null;
            $awayScore     = $tip['goals_away'] ?? null;
            $scoreDisplay  = '—';
            $matchStatus   = $tip['status_short'] ?? '';
            
            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay = htmlspecialchars($homeScore . ' – ' . $awayScore);
            }

            // Use safe function to get winning status
            $winningStatus = getSafeWinningStatus($bttsPrediction, $homeScore, $awayScore, $matchStatus);

            $bttsResult      = '';
            $bttsResultClass = '';
            if ($scoreDisplay !== '—') {
                if ($homeScore > 0 && $awayScore > 0) {
                    $bttsResult      = '✅ BTTS';
                    $bttsResultClass = 'result-won';
                } else {
                    $bttsResult      = '❌ No BTTS';
                    $bttsResultClass = 'result-lost';
                }
            }

            /* ---- Time display ---- */
            $formattedTime = '—';
            $formattedDate = '';
            if (!empty($tip['date'])) {
                $formattedTime = DateTimeToUsersTimezone($tip['date']);
            }

            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));
            $leagueFull  = $tip['league_name'] ?? '';
            $leagueCountry = $tip['league_country'] ?? '';

            $circ      = 119.38;
            $dashValue = round(($bttsConfidence / 100) * $circ, 2);
            $fillClass = ($bttsPrediction === 'Yes') ? 'fill-btts-yes' : 'fill-btts-no';
            $chipClass   = getBttsChipClass($bttsPrediction);
            $displayText = getBttsDisplayText($bttsPrediction);

            $confidenceClass = 'confidence-medium';
            if ($bttsConfidence >= 80)     $confidenceClass = 'confidence-high';
            elseif ($bttsConfidence <= 60) $confidenceClass = 'confidence-low';

            $hasScore = ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '');
            $statusShort = htmlspecialchars($tip['status_short'] ?? '');   // e.g. "FT", "HT", "1H", "NS"
        ?>

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
                            <!-- Show score on mobile -->
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
                        <div class="team-crest away-crest"><?php echo $awayInitial; ?></div>
                        <span class="team-name-text away-name"><?php echo htmlspecialchars($tip['away_team_name'] ?? ''); ?></span>
                    </div>
                </div>
            </div>

            <div class="mc-prediction">
                <span class="pred-chip <?php echo $chipClass; ?>">
                    <?php echo $displayText; ?>
                </span>
            </div>

            <div class="mc-prob">
                <div class="prob-item">
                    <div class="prob-ring">
                        <svg viewBox="0 0 40 40">
                            <circle class="track" cx="20" cy="20" r="17"/>
                            <circle class="fill-home <?php echo $fillClass; ?>" cx="20" cy="20" r="17"
                                stroke-dasharray="<?php echo $dashValue; ?> <?php echo $circ; ?>"/>
                        </svg>
                        <div class="prob-ring-value <?php echo $confidenceClass; ?>"><?php echo $bttsConfidence; ?>%</div>
                    </div>
                </div>
            </div>         

            <div class="mc-odds">
                <div class="odds-value"><?php echo is_numeric($bttsOdd) ? number_format($bttsOdd, 2) : $bttsOdd; ?></div>
            </div>

            <div class="mc-score">
                <div class="score-display"><?php echo $scoreDisplay; ?></div>
                <?php if ($scoreDisplay !== '—'): ?>
                    <div class="score-status"><?php echo $matchStatus; ?></div>
                    <div class="<?php echo $bttsResultClass; ?>" style="font-size:10px;margin-top:2px;"><?php echo $bttsResult; ?></div>
                <?php else: ?>
                    <div class="score-status upcoming"><?php echo $matchStatus ?: 'Upcoming'; ?></div>
                <?php endif; ?>
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