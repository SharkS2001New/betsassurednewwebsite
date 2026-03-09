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
    border-radius: 10px;
    padding: 22px 24px 18px;
    margin-top: 16px;
    margin-bottom: 24px;
}

.accu-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1a2e8a;
    margin-bottom: 16px;
    letter-spacing: 0.01em;
}

/* Tables */
.accu-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #dde3ef;
    border-radius: 6px;
    overflow: hidden;
    font-size: 0.875rem;
}

.accu-row {
    border-bottom: 1px solid #edf0f7;
    transition: background 0.15s;
}
.accu-row:last-child { border-bottom: none; }
.accu-row:hover { background: #f5f7fb; }

/* Match cell */
.accu-match-cell {
    padding: 9px 12px;
    display: flex;
    align-items: center;
    gap: 9px;
    color: #2c3e50;
    font-weight: 400;
    white-space: nowrap;
}

.accu-logo {
    border-radius: 3px;
    object-fit: contain;
    flex-shrink: 0;
}

.accu-teams {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: nowrap;
}

.accu-home, .accu-away {
    color: #1a1a2e;
    font-weight: 500;
}

.accu-vs {
    color: #8892b0;
    font-size: 0.78rem;
    font-weight: 400;
    margin: 0 2px;
}

/* Market cell */
.accu-market-cell {
    padding: 9px 10px;
    text-align: right;
    color: #1a2e8a;
    font-weight: 700;
    white-space: nowrap;
    font-size: 0.82rem;
}

/* Odd cell */
.accu-odd-cell {
    padding: 9px 14px 9px 6px;
    text-align: right;
    color: #2c3e50;
    font-weight: 500;
    min-width: 42px;
    font-variant-numeric: tabular-nums;
}

/* Total row */
.accu-total-row {
    background: #f7f9fc;
    border-top: 2px solid #dde3ef !important;
}

.accu-total-label {
    padding: 10px 12px;
    font-weight: 700;
    color: #1a2e8a;
    font-size: 0.875rem;
}

.accu-total-value {
    padding: 10px 14px 10px 6px;
    text-align: right;
    font-weight: 700;
    color: #1a2e8a;
    font-size: 0.9rem;
    font-variant-numeric: tabular-nums;
}

/* Error state */
.accu-error {
    text-align: center;
    color: #dc3545;
    padding: 18px 0;
    font-size: 0.9rem;
}

/* Responsive column layout */
.accu-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

@media (max-width: 768px) {
    .accu-cols {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    .accu-section {
        padding: 16px 14px;
    }
}
</style>

<!-- Accumulator Tips Section -->
<div class="accu-section" id="accumulator-tips">

    <?php if ($accumulatorError || !$hasEnoughMatches): ?>
        <div class="accu-error">
            <?php echo $accumulatorError
                ? 'Failed to fetch accumulator tips.'
                : 'Not enough matches available for accumulator tips.'; ?>
        </div>
    <?php else: ?>

        <div class="accu-title">
            Free Accumulator Tips: <?php echo formatAccumulatorDate(); ?>
        </div>

        <div class="accu-cols">
            <!-- Left table: matches 0–4 -->
            <table class="accu-table" id="accumulator-table-1">
                <tbody>
                    <?php echo renderAccumulatorRows($uniqueAccumulatorMatches, 0, 5); ?>
                </tbody>
            </table>

            <!-- Right table: matches 5–8 -->
            <table class="accu-table" id="accumulator-table-2">
                <tbody>
                    <?php echo renderAccumulatorRows($uniqueAccumulatorMatches, 5, 4); ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>