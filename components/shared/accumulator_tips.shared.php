<?php

// Add these functions with your other PHP functions

function fetchAccumulatorTips() {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime('+1 day'));

    $apiUrl = "https://api.pitchpredictions.com/api/fetch_accumulator_tips_fixtures?start_date={$startDate}&end_date={$endDate}";
    $token = "R9TxV3PbOEu7qZnJKgydC5LmX2";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $token]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $matches = [];

    if (!curl_errno($ch) && $httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['data']) && is_array($data['data'])) {
            $matches = $data['data'];
        }
    }

    curl_close($ch);
    return $matches;
}

function findOddAccumulator($allBets, $marketName, $value) {
    if (empty($allBets)) return null;
    foreach ($allBets as $market) {
        if ($market['name'] === $marketName && isset($market['values'])) {
            foreach ($market['values'] as $bet) {
                if ($bet['value'] === $value) {
                    return floatval($bet['odd']);
                }
            }
        }
    }
    return null;
}

function getAccumulatorBettingMarket($match) {
    if (!$match) return null;

    $avgGoals = floatval($match['average_goals'] ?? 0);
    $predHome = intval(str_replace('%', '', $match['percent_pred_home'] ?? '0'));
    $predDraw = intval(str_replace('%', '', $match['percent_pred_draw'] ?? '0'));
    $predAway = intval(str_replace('%', '', $match['percent_pred_away'] ?? '0'));

    $allBets = [];
    if (!empty($match['all_bets_odds'])) {
        try {
            $allBets = json_decode($match['all_bets_odds'], true);
        } catch (Exception $e) {
            $allBets = [];
        }
    }

    $homeOdd = findOddAccumulator($allBets, "Match Winner", "Home");
    if ($predHome >= 60 && $homeOdd >= 1.13 && $homeOdd <= 1.8) {
        return ["market" => "Home Win", "odd" => $homeOdd];
    }

    $awayOdd = findOddAccumulator($allBets, "Match Winner", "Away");
    if ($predAway >= 60 && $awayOdd >= 1.13 && $awayOdd <= 1.8) {
        return ["market" => "Away Win", "odd" => $awayOdd];
    }

    $drawOdd = findOddAccumulator($allBets, "Match Winner", "Draw");
    if ($predDraw >= 60 && $drawOdd >= 1.13 && $drawOdd <= 1.8) {
        return ["market" => "Draw", "odd" => $drawOdd];
    }

    $bttsYesOdd = findOddAccumulator($allBets, "Both Teams Score", "Yes");
    if ($bttsYesOdd && $bttsYesOdd >= 1.13 && $bttsYesOdd <= 1.8) {
        return ["market" => "BTTS (Yes)", "odd" => $bttsYesOdd];
    }

    $overOdd = findOddAccumulator($allBets, "Goals Over/Under", "Over 2.5");
    $underOdd = findOddAccumulator($allBets, "Goals Over/Under", "Under 2.5");

    if ($avgGoals >= 2.8 && $overOdd && $overOdd >= 1.13 && $overOdd <= 1.8) {
        return ["market" => "Over 2.5", "odd" => $overOdd];
    }
    if ($avgGoals <= 1.7 && $underOdd && $underOdd >= 1.13 && $underOdd <= 1.8) {
        return ["market" => "Under 2.5", "odd" => $underOdd];
    }

    if ($predHome > $predAway && $predHome > $predDraw) {
        $odd = findOddAccumulator($allBets, "Double Chance", "Home/Draw");
        if ($odd && $odd >= 1.13 && $odd <= 1.8) {
            return ["market" => "DC (1X)", "odd" => $odd];
        }
    }

    if ($predAway > $predHome && $predAway > $predDraw) {
        $odd = findOddAccumulator($allBets, "Double Chance", "Draw/Away");
        if ($odd && $odd >= 1.13 && $odd <= 1.8) {
            return ["market" => "DC (X2)", "odd" => $odd];
        }
    }

    $dc12Odd = findOddAccumulator($allBets, "Double Chance", "Home/Away");
    if ($dc12Odd && $dc12Odd >= 1.13 && $dc12Odd <= 1.8) {
        return ["market" => "DC (12)", "odd" => $dc12Odd];
    }

    if ($predHome >= 60 && $homeOdd) return ["market" => "Home Win", "odd" => $homeOdd];
    if ($predAway >= 60 && $awayOdd) return ["market" => "Away Win", "odd" => $awayOdd];
    if ($predDraw >= 60 && $drawOdd) return ["market" => "Draw", "odd" => $drawOdd];

    return null;
}

function formatAccumulatorDate() {
    return date('l, F j, Y');
}

function renderAccumulatorRows($matches, $startIndex, $count) {
    $rows = [];
    $totalOdds = 1;
    $validMatches = 0;

    $slice = array_slice($matches, $startIndex, $count);

    foreach ($slice as $match) {
        $market = getAccumulatorBettingMarket($match);
        if (!$market) continue;

        $validMatches++;
        $totalOdds *= $market['odd'];

        $leagueLogo = !empty($match['downloaded_league_logo'])
            ? htmlspecialchars($match['downloaded_league_logo'])
            : 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Crect%20width%3D%2224%22%20height%3D%2224%22%20rx%3D%224%22%20fill%3D%22%23e8eaf0%22%2F%3E%3Ctext%20x%3D%2212%22%20y%3D%2216%22%20font-size%3D%2212%22%20text-anchor%3D%22middle%22%20fill%3D%22%23aaa%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E';

        $homeTeam = htmlspecialchars($match['home_team_name'] ?? 'Home');
        $awayTeam = htmlspecialchars($match['away_team_name'] ?? 'Away');
        $marketName = htmlspecialchars($market['market']);
        $oddValue = number_format($market['odd'], 2);

        $rows[] = "
            <tr class=\"accu-row\">
                <td class=\"accu-match-cell\">
                    <img src=\"{$leagueLogo}\" width=\"24\" height=\"24\" class=\"accu-logo\" alt=\"\" />
                    <span class=\"accu-teams\">
                        <span class=\"accu-home\">{$homeTeam}</span>
                        <span class=\"accu-vs\">vs</span>
                        <span class=\"accu-away\">{$awayTeam}</span>
                    </span>
                </td>
                <td class=\"accu-market-cell\">{$marketName}</td>
                <td class=\"accu-odd-cell\">{$oddValue}</td>
            </tr>
        ";
    }

    if ($validMatches > 0) {
        $totalOddsFormatted = number_format($totalOdds, 2);
        $rows[] = "
            <tr class=\"accu-total-row\">
                <td colspan=\"2\" class=\"accu-total-label\">Total Odds @:</td>
                <td class=\"accu-total-value\">{$totalOddsFormatted}</td>
            </tr>
        ";
    }

    return implode('', $rows);
}

// Fetch & deduplicate
$accumulatorMatches = fetchAccumulatorTips();
$accumulatorError = empty($accumulatorMatches);

$uniqueAccumulatorMatches = [];
$seenKeys = [];

if (!empty($accumulatorMatches)) {
    foreach ($accumulatorMatches as $match) {
        $key = ($match['home_team_name'] ?? '') . '-' . ($match['away_team_name'] ?? '');
        if (!isset($seenKeys[$key])) {
            $seenKeys[$key] = true;
            $uniqueAccumulatorMatches[] = $match;
        }
        if (count($uniqueAccumulatorMatches) >= 9) break;
    }
}

$hasEnoughMatches = count($uniqueAccumulatorMatches) >= 8;
?>

<style>
/* ── Accumulator Tips Section ── */
.accu-section {
    background: #fff;
    border: 1px solid #dde3ef;
    border-radius: 12px;
    padding: 12px;
    margin-top: 20px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.accu-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--navy-light);
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--lime);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.accu-title .accu-date {
    color: var(--text-2);
    font-size: 0.9rem;
    font-weight: 400;
    background: var(--surface-2);
    padding: 4px 12px;
    border-radius: 20px;
}

/* Two column layout */
.accu-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Match container */
.accu-container {
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid var(--border);
    overflow: hidden;
}

/* Header row */
.accu-header {
    display: grid;
    grid-template-columns: 1fr 100px 70px;
    background: var(--navy);
    color: rgba(255,255,255,0.7);
    padding: 10px 16px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.accu-header span:last-child {
    text-align: right;
}

/* Match row */
.accu-row {
    display: grid;
    grid-template-columns: 1fr 100px 70px;
    padding: 8px 10px;
    border-bottom: 1px solid var(--border);
    align-items: center;
    transition: background 0.2s;
}

.accu-row:last-child {
    border-bottom: none;
}

.accu-row:hover {
    background: #fff;
}

/* Match cell */
.accu-match {
    display: flex;
    align-items: center;
    gap: 12px;
}

.accu-logo {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    object-fit: contain;
    background: #fff;
    border: 1px solid var(--border);
    flex-shrink: 0;
}

.accu-teams {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.accu-home, .accu-away {
    font-weight: 600;
    color: var(--text-1);
    font-size: 12px;
    white-space: nowrap;
}

.accu-vs {
    color: var(--text-3);
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    background: var(--surface-2);
    padding: 2px 6px;
    border-radius: 4px;
}

/* Market cell */
.accu-market {
    font-weight: 600;
    color: var(--navy-light);
    font-size: 12px;
    white-space: nowrap;
    padding-right: 10px;
}

/* Odd cell */
.accu-odd {
    font-family: var(--font-mono);
    font-weight: 700;
    /* color: var(--amber); */
    font-size: 12px;
    text-align: right;
    background: rgba(251,191,36,0.1);
    padding: 4px 8px;
    border-radius: 6px;
    border: 1px solid rgba(251,191,36,0.2);
    display: inline-block;
    justify-self: end;
}

/* Total row */
.accu-total {
    display: grid;
    grid-template-columns: 1fr 100px 70px;
    padding: 14px 16px;
    background: linear-gradient(135deg, var(--navy-light), var(--navy));
    color: white;
    font-weight: 700;
    border-top: 2px solid var(--lime);
}

.accu-total-label {
    grid-column: 2;
    text-align: right;
    font-size: 12px;
    opacity: 0.9;
}

.accu-total-value {
    text-align: right;
    font-family: var(--font-mono);
    font-size: 12px;
}

/* Error state */
.accu-error {
    text-align: center;
    padding: 40px 20px;
    background: #fff1f0;
    border: 1px solid #ffccc7;
    border-radius: 10px;
    color: #f5222d;
}

.accu-error p {
    margin-top: 8px;
    color: var(--text-2);
}

/* Responsive */
@media (max-width: 900px) {
    .accu-section {
        padding: 8px;
    }
    
    .accu-cols {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .accu-title {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 600px) {
    .accu-row {
        grid-template-columns: 1fr 80px 60px;
        padding: 10px 12px;
    }
    
    .accu-header {
        grid-template-columns: 1fr 80px 60px;
        font-size: 0.7rem;
    }
    
    .accu-match {
        gap: 8px;
    }
    
    .accu-logo {
        width: 24px;
        height: 24px;
    }
    
    .accu-home, .accu-away {
        font-size: 12px;
        max-width: 100px;
        /* overflow: hidden; */
        text-overflow: ellipsis;
    }
    
    .accu-market {
        font-size: 12px;
        padding-right: 5px;
    }
    
    .accu-odd {
        font-size: 12px;
        padding: 3px 6px;
    }
    
    .accu-total {
        grid-template-columns: 1fr 80px 60px;
        padding: 12px;
    }
    
    .accu-total-label {
        font-size: 12px;
    }
    
    .accu-total-value {
        font-size: 12px;
    }
}
</style>

<!-- Accumulator Tips Section -->
<div class="accu-section" id="accumulator-tips">

    <?php if ($accumulatorError || !$hasEnoughMatches): ?>
        <div class="accu-error">
            <strong>⚠️ Accumulator Tips Unavailable</strong>
            <p><?php echo $accumulatorError 
                ? 'Unable to fetch accumulator tips at the moment.' 
                : 'Not enough qualifying matches for today\'s accumulator.'; ?></p>
        </div>
    <?php else: ?>

        <div class="accu-title">
            <span>🎯 Free Accumulator Tips</span>
            <span class="accu-date"><?php echo formatAccumulatorDate(); ?></span>
        </div>

        <div class="accu-cols">
            <!-- Left column: matches 0–4 -->
            <div class="accu-container">
                <div class="accu-header">
                    <span>Match</span>
                    <span>Tip</span>
                    <span>Odds</span>
                </div>
                
                <?php 
                $totalOdds1 = 1;
                $validCount1 = 0;
                $slice1 = array_slice($uniqueAccumulatorMatches, 0, 5);
                
                foreach ($slice1 as $match):
                    $market = getAccumulatorBettingMarket($match);
                    if (!$market) continue;
                    
                    $validCount1++;
                    $totalOdds1 *= $market['odd'];
                    
                    $leagueLogo = !empty($match['downloaded_league_logo'])
                        ? htmlspecialchars($match['downloaded_league_logo'])
                        : 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Crect%20width%3D%2224%22%20height%3D%2224%22%20rx%3D%224%22%20fill%3D%22%23e8eaf0%22%2F%3E%3Ctext%20x%3D%2212%22%20y%3D%2216%22%20font-size%3D%2212%22%20text-anchor%3D%22middle%22%20fill%3D%22%23aaa%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E';
                    
                    $homeTeam = htmlspecialchars($match['home_team_name'] ?? 'Home');
                    $awayTeam = htmlspecialchars($match['away_team_name'] ?? 'Away');
                    $marketName = htmlspecialchars($market['market']);
                    $oddValue = number_format($market['odd'], 2);
                ?>
                
                <div class="accu-row">
                    <div class="accu-match">
                        <img src="<?php echo $leagueLogo; ?>" class="accu-logo" alt="">
                        <div class="accu-teams">
                            <span class="accu-home"><?php echo $homeTeam; ?></span>
                            <span class="accu-vs">vs</span>
                            <span class="accu-away"><?php echo $awayTeam; ?></span>
                        </div>
                    </div>
                    <div class="accu-market"><?php echo $marketName; ?></div>
                    <div class="accu-odd"><?php echo $oddValue; ?></div>
                </div>
                
                <?php endforeach; ?>
                
                <?php if ($validCount1 > 0): ?>
                <div class="accu-total">
                    <div></div>
                    <div class="accu-total-label">Total Odds:</div>
                    <div class="accu-total-value"><?php echo number_format($totalOdds1, 2); ?></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right column: matches 5–8 -->
            <div class="accu-container">
                <div class="accu-header">
                    <span>Match</span>
                    <span>Tip</span>
                    <span>Odds</span>
                </div>
                
                <?php 
                $totalOdds2 = 1;
                $validCount2 = 0;
                $slice2 = array_slice($uniqueAccumulatorMatches, 5, 4);
                
                foreach ($slice2 as $match):
                    $market = getAccumulatorBettingMarket($match);
                    if (!$market) continue;
                    
                    $validCount2++;
                    $totalOdds2 *= $market['odd'];
                    
                    $leagueLogo = !empty($match['downloaded_league_logo'])
                        ? htmlspecialchars($match['downloaded_league_logo'])
                        : 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%3E%3Crect%20width%3D%2224%22%20height%3D%2224%22%20rx%3D%224%22%20fill%3D%22%23e8eaf0%22%2F%3E%3Ctext%20x%3D%2212%22%20y%3D%2216%22%20font-size%3D%2212%22%20text-anchor%3D%22middle%22%20fill%3D%22%23aaa%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E';
                    
                    $homeTeam = htmlspecialchars($match['home_team_name'] ?? 'Home');
                    $awayTeam = htmlspecialchars($match['away_team_name'] ?? 'Away');
                    $marketName = htmlspecialchars($market['market']);
                    $oddValue = number_format($market['odd'], 2);
                ?>
                
                <div class="accu-row">
                    <div class="accu-match">
                        <img src="<?php echo $leagueLogo; ?>" class="accu-logo" alt="">
                        <div class="accu-teams">
                            <span class="accu-home"><?php echo $homeTeam; ?></span>
                            <span class="accu-vs">vs</span>
                            <span class="accu-away"><?php echo $awayTeam; ?></span>
                        </div>
                    </div>
                    <div class="accu-market"><?php echo $marketName; ?></div>
                    <div class="accu-odd"><?php echo $oddValue; ?></div>
                </div>
                
                <?php endforeach; ?>
                
                <?php if ($validCount2 > 0): ?>
                <div class="accu-total">
                    <div></div>
                    <div class="accu-total-label">Total Odds:</div>
                    <div class="accu-total-value"><?php echo number_format($totalOdds2, 2); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>