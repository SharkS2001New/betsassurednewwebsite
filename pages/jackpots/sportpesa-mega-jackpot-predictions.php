<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Sportpesa Mega Jackpot Predictions This Week | 17 Games Kenya</title>
<meta name="title" content="Sportpesa Mega Jackpot Predictions This Week | 17 Games Kenya">
<meta name="description" content="Free Sportpesa Mega Jackpot predictions for all 17 games this week. Expert analysis with probability ratings to help you hit the top prize and bonus brackets. Updated weekly.">
<meta name="keywords" content="sportpesa mega jackpot predictions, sportpesa mega jackpot tips this week, sportpesa 17 games predictions, how to win sportpesa mega jackpot, sportpesa jackpot analysis kenya, sportpesa mega jackpot this week">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Sportpesa Mega Jackpot Predictions This Week | 17 Games Kenya">
<meta property="og:description" content="Free Sportpesa Mega Jackpot predictions for all 17 games this week. Expert analysis with probability ratings to help you hit the top prize and bonus brackets. Updated weekly.">
<meta property="og:url" content="https://www.betsassured.com/sportpesa-mega-jackpot-predictions">
<meta property="og:site_name" content="Betsassured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Sportpesa Mega Jackpot Predictions This Week | 17 Games Kenya">
<meta name="twitter:description" content="Free Sportpesa Mega Jackpot predictions for all 17 games this week. Expert analysis with probability ratings to help you hit the top prize and bonus brackets. Updated weekly.">
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
      "name": "Sportpesa Mega Jackpot Predictions This Week | 17 Games Kenya",
      "url": "https://www.betsassured.com/sportpesa-mega-jackpot-predictions",
      "description": "Free Sportpesa Mega Jackpot predictions for all 17 games this week. Expert analysis with probability ratings to help you hit the top prize and bonus brackets. Updated weekly.",
      "inLanguage": "en",
      "isPartOf": {
        "@type": "WebSite",
        "name": "Betsassured",
        "url": "https://www.betsassured.com"
      }
    },
    {
      "@type": "CollectionPage",
      "name": "Sportpesa Mega Jackpot Predictions",
      "url": "https://www.betsassured.com/sportpesa-mega-jackpot-predictions",
      "description": "A weekly collection of Sportpesa Mega Jackpot predictions covering all 17 games with probability ratings and expert analysis."
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
          "name": "Sportpesa Mega Jackpot Predictions",
          "item": "https://www.betsassured.com/sportpesa-mega-jackpot-predictions"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How many games are included in the Sportpesa Mega Jackpot predictions?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "All 17 games for the current week's Sportpesa Mega Jackpot are covered with predictions and probability ratings."
          }
        },
        {
          "@type": "Question",
          "name": "Are the Sportpesa Mega Jackpot tips free?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, all Sportpesa Mega Jackpot predictions on Betsassured are free and updated weekly."
          }
        }
      ]
    }
  ]
}
</script>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
include_once BASE_PATH . "/components/shared/DateTimeToUsersTimezone.shared.php";
include_once BASE_PATH . "/components/shared/DetermineWinningOrLost.shared.php";

$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH . '/components/seo-content/sportpesa-mega-jackpot.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// API request
$jackpotName = "Sportpesa Mega Jackpot";
$encodedName = urlencode($jackpotName);
$apiUrl      = "https://api.alljackpotpredictions.com/api/fetch_jackpot_fixtures_by_name?jackpot_name=$encodedName";
$token       = "q2LsJ9FmT6XvRaCbHuYdK8ZwN4";

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
$startDt     = null;
$endDt       = null;

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

<main class="container">
    <h1 class="page-hero-title">Sportpesa Mega Jackpot Predictions This Week | Kenya</h1>

    <?php include_once BASE_PATH . "/components/includes/scrollable-nav.inc.php"; ?>

    <!-- Page Header -->
    <div class="section-title-bar">
        <h2>Sportpesa Mega Jackpot Predictions</h2>
        <span class="today-date-tag">Week <?php echo date('W'); ?></span>
    </div>

    <!-- Description -->
    <p style="color: #4b5563; margin-bottom: 20px;">
        Free <strong>Sportpesa Mega Jackpot predictions</strong> for all <?php echo $gameCount > 0 ? $gameCount : '17'; ?> games this week.
        Each fixture includes a probability breakdown and recommended selection to help you build the strongest possible jackpot slip and maximise your bracket coverage.
    </p>

    <!-- Stats Bar -->
    <?php if ($startDt && $endDt): ?>
    <div class="jackpot-stats-bar">
        <div class="stat-item">
            <span class="stat-value"><?php echo $gameCount; ?> Games</span>
            <span class="stat-label">This Week</span>
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
        <span style="text-align:center">Prediction</span>
        <span style="text-align:center">Probability</span>
        <span style="text-align:center">Odds</span>
        <span style="text-align:center">Score</span>
    </div>

    <!-- Predictions Wrapper -->
    <div class="preds-wrapper">
        <?php if (empty($predictions)): ?>
            <div class="state-msg">
                Sportpesa Mega Jackpot predictions are being prepared — check back soon!
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
            $matchStatus   = $tip['status_short'] ?? null;

            // Won/lost
            $winningStatus = ($scoreDisplay !== '—')
                ? DetermineWinningOrLost($prediction, $homeScore, $awayScore)
                : '';

            // Time — keep as DateTime, never strtotime a pre-formatted string
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
            
            /* ---- League: split country / name ---- */
            $leagueFull = $tip['league_name'] ?? '';
            $leagueCountry = $tip['league_country'] ?? '';
            /* Try to detect "Country: League" format */
            if (strpos($leagueFull, ':') !== false) {
                [$leagueCountry, $leagueFull] = array_map('trim', explode(':', $leagueFull, 2));
            }
            
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
