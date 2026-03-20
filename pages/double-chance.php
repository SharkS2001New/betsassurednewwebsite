<?php
$metaTags= <<<HTML
<!-- Primary Meta Tags -->
<title>Double Chance Predictions Today | 1X, X2 & 12 Football Tips</title>
<meta name="title" content="Double Chance Predictions Today | 1X, X2 & 12 Football Tips">
<meta name="description" content="Free double chance predictions today. Expert 1X, X2 and 12 tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.">
<meta name="keywords" content="double chance predictions today, 1x tips today, x2 football tips, 12 double chance betting, double chance football predictions, 1x x2 12 tips, double chance tips today, safe football betting tips">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Double Chance Predictions Today | 1X, X2 & 12 Football Tips">
<meta property="og:description" content="Free double chance predictions today. Expert 1X, X2 and 12 tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.">
<meta property="og:url" content="https://www.betsassured.com/double-chance-predictions">
<meta property="og:site_name" content="Betsassured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Double Chance Predictions Today | 1X, X2 & 12 Football Tips">
<meta name="twitter:description" content="Free double chance predictions today. Expert 1X, X2 and 12 tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "name": "Betsassured",
      "url": "https://www.betsassured.com"
    },
    {
      "@type": "WebPage",
      "name": "Double Chance Predictions Today | 1X, X2 & 12 Football Tips",
      "url": "https://www.betsassured.com/double-chance-predictions",
      "description": "Free double chance predictions today. Expert 1X, X2 and 12 tips with confidence ratings and odds analysis updated daily across top football leagues worldwide.",
      "inLanguage": "en",
      "isPartOf": {
        "@type": "WebSite",
        "name": "Betsassured",
        "url": "https://www.betsassured.com"
      }
    },
    {
      "@type": "CollectionPage",
      "name": "Double Chance Football Predictions",
      "url": "https://www.betsassured.com/double-chance-predictions",
      "description": "Daily 1X, X2, and 12 double chance football predictions with expert tips and confidence ratings."
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://www.betsassured.com"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Double Chance Predictions",
          "item": "https://www.betsassured.com/double-chance-predictions"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What are double chance predictions?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Double chance predictions give you two possible outcomes of a football match (1X, X2, or 12) to increase your chances of winning based on expert analysis and match statistics."
          }
        },
        {
          "@type": "Question",
          "name": "Are Betsassured double chance tips free?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, all double chance predictions and tips on Betsassured are free and updated daily."
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
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/double-chance-tips.content.md');
$htmlContent = $Parsedown->text($markdownContent);

function percentToInt($percent) {
    return intval(str_replace('%', '', $percent ?? '0'));
}

// API fetch
$apiUrl = "https://api.pitchpredictions.com/api/fetch_double_chance_free_winning_tips";
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
 * Get Double Chance prediction based on percentages
 */
function getDoubleChancePrediction($tip) {
    $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
    $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
    $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

    $homeDraw = $homePercent + $drawPercent;
    $homeAway = $homePercent + $awayPercent;
    $drawAway = $drawPercent + $awayPercent;

    $maxCombined = max($homeDraw, $homeAway, $drawAway);

    if ($maxCombined === $homeDraw) {
        return ['prediction' => '1X', 'display' => '1X', 'chipClass' => 'chip-dc', 'confidence' => $homeDraw, 'type' => 'home_draw', 'description' => 'Home Win or Draw'];
    } elseif ($maxCombined === $drawAway) {
        return ['prediction' => 'X2', 'display' => 'X2', 'chipClass' => 'chip-dc', 'confidence' => $drawAway, 'type' => 'draw_away', 'description' => 'Draw or Away Win'];
    } else {
        return ['prediction' => '12', 'display' => '12', 'chipClass' => 'chip-dc', 'confidence' => $homeAway, 'type' => 'home_away', 'description' => 'Home Win or Away Win (No Draw)'];
    }
}

/**
 * Calculate confidence for display (ensure between 50-95)
 */
function calculateDisplayConfidence($confidence) {
    return min(95, max(50, $confidence));
}

/**
 * Find Double Chance odds from all_bets_odds
 */
function findDoubleChanceOdd($allBets, $prediction) {
    if (empty($allBets)) return null;

    $map = ['1X' => 'Home/Draw', '12' => 'Home/Away', 'X2' => 'Draw/Away'];
    $value = $map[$prediction] ?? '';
    if (empty($value)) return null;

    foreach ($allBets as $market) {
        if ($market['name'] === "Double Chance" && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === $value) return floatval($bet['odd']);
            }
        }
    }
    return null;
}
?>

<main class="container">
    <h1 class="page-hero-title">Double Chance Predictions Today | 1X, X2 & 12 Football Tips</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Today's Double Chance Predictions — 1X, X2 & 12</h2>
        <span class="today-date-tag"><?php echo date('D, d M Y'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Our <strong>double chance predictions</strong> cover two of the three possible match outcomes, giving you a wider safety net on every tip. Options include <span style="color: #ec4899; font-weight: 600;">1X (Home Win or Draw)</span>, <span style="color: #ec4899; font-weight: 600;">X2 (Draw or Away Win)</span>, and <span style="color: #ec4899; font-weight: 600;">12 (Home Win or Away Win — no draw)</span>. Confidence ratings are based on combined outcome probabilities.
    </p>

    <!-- Stats Bar -->
    <?php
    $oneXCount   = 0;
    $xTwoCount   = 0;
    $twelveCount = 0;

    foreach ($tipsData as $tip) {
        $predData = getDoubleChancePrediction($tip);
        if ($predData['prediction'] === '1X')      $oneXCount++;
        elseif ($predData['prediction'] === 'X2')  $xTwoCount++;
        elseif ($predData['prediction'] === '12')  $twelveCount++;
    }
    ?>
    <div class="dc-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo count($tipsData); ?></span>
            <span class="stat-label">Total Picks</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #f9a8d4;"><?php echo $oneXCount; ?></span>
            <span class="stat-label">1X Tips</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #f9a8d4;"><?php echo $xTwoCount; ?></span>
            <span class="stat-label">X2 Tips</span>
        </div>
        <div class="stat-item">
            <span class="stat-value" style="color: #f9a8d4;"><?php echo $twelveCount; ?></span>
            <span class="stat-label">12 Tips</span>
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
                Our analysts are working on today's double chance predictions — please check back in a few minutes!
            </div>
        <?php elseif ($empty || empty($tipsData)): ?>
            <div class="state-msg">
                No double chance predictions available for today. Check back later!
            </div>
        <?php else: ?>

        <?php foreach ($tipsData as $tip):

            $predData          = getDoubleChancePrediction($tip);
            $prediction        = $predData['prediction'];
            $displayPrediction = $predData['display'];
            $chipClass         = $predData['chipClass'];
            $description       = $predData['description'];

            $confidence = calculateDisplayConfidence($predData['confidence']);

            // Odds
            $oddsDisplay = '—';
            if (!empty($tip['all_bets_odds'])) {
                try {
                    $oddsData = json_decode($tip['all_bets_odds'], true);
                    if (is_array($oddsData)) {
                        $odd = findDoubleChanceOdd($oddsData, $prediction);
                        if ($odd) $oddsDisplay = number_format($odd, 2);
                    }
                } catch (Exception $e) { /* keep default */ }
            }

            // Scores
            $homeScore     = $tip['goals_home'] ?? null;
            $awayScore     = $tip['goals_away'] ?? null;
            $scoreDisplay  = '—';
            $matchStatus   = 'UPCOMING';
            $winningStatus = '';

            if ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '') {
                $scoreDisplay  = htmlspecialchars($homeScore . ' – ' . $awayScore);
                $matchStatus   = $tip['status_short'] ?? null;
                $winningStatus = DetermineWinningOrLost($prediction, $homeScore, $awayScore);
            }

            $homePercent = percentToInt($tip['percent_pred_home'] ?? '0');
            $drawPercent = percentToInt($tip['percent_pred_draw'] ?? '0');
            $awayPercent = percentToInt($tip['percent_pred_away'] ?? '0');

            $homeInitial = strtoupper(substr(trim($tip['home_team_name'] ?? 'H'), 0, 2));
            $awayInitial = strtoupper(substr(trim($tip['away_team_name'] ?? 'A'), 0, 2));
            
            // League
            $leagueFull    = $tip['league_name'] ?? '';
            $leagueCountry = $tip['country_name'] ?? '';

            /* ---- Time display ---- */
            $formattedTime = '—';
            $formattedDate = '';
            if (!empty($tip['date'])) {
                $formattedTime = DateTimeToUsersTimezone($tip['date']);
            }
            
            $hasScore = ($homeScore !== null && $awayScore !== null && $homeScore !== '' && $awayScore !== '');
            $statusShort = htmlspecialchars($tip['status_short'] ?? '');   // e.g. "FT", "HT", "1H", "NS"

            $circ      = 119.38;
            $dashValue = round(($confidence / 100) * $circ, 2);
        ?>

        <div class="match-card">
            <div class="mc-time">
                <span><?php echo htmlspecialchars($formattedTime); ?></span>
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
                <span class="pred-chip <?php echo $chipClass; ?>" title="<?php echo htmlspecialchars($description); ?>">
                    <?php echo htmlspecialchars($displayPrediction); ?>
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
                        <div class="prob-ring-value <?php echo $confidenceClass; ?>"><?php echo $confidence; ?>%</div>
                    </div>
                </div>
            </div>

            <div class="mc-odds">
                <div class="odds-value"><?php echo $oddsDisplay; ?></div>
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
