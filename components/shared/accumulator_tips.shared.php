<?php
// Add these functions with your other PHP functions

function fetchAccumulatorTips() {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime('+1 day'));
    
    $apiUrl = "https://api.pitchpredictions.com/api/fetch_accumulator_shared_tips_fixtures?start_date={$startDate}&end_date={$endDate}";
    $token = "R9TxV3PbOEu7qZnJKgydC5LmX2";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $token
    ]);
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

    if ($predHome >= 60 && $homeOdd) {
        return ["market" => "Home Win", "odd" => $homeOdd];
    }
    if ($predAway >= 60 && $awayOdd) {
        return ["market" => "Away Win", "odd" => $awayOdd];
    }
    if ($predDraw >= 60 && $drawOdd) {
        return ["market" => "Draw", "odd" => $drawOdd];
    }

    return null;
}

function formatAccumulatorDate() {
    return date('l, F j, Y');
}

function renderAccumulatorTable($matches, $startIndex, $count) {
    $tableRows = [];
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
            : 'data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%3E%3Crect%20width%3D%2220%22%20height%3D%2220%22%20fill%3D%22%23f0f0f0%22%2F%3E%3Ctext%20x%3D%2210%22%20y%3D%2214%22%20font-size%3D%2212%22%20text-anchor%3D%22middle%22%20fill%3D%22%23999%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E';
        
        $homeTeam = htmlspecialchars($match['home_team_name'] ?? 'Home');
        $awayTeam = htmlspecialchars($match['away_team_name'] ?? 'Away');
        $marketName = htmlspecialchars($market['market']);
        $oddValue = number_format($market['odd'], 2);
        
        $tableRows[] = "
            <tr>
                <td>
                    <img src=\"{$leagueLogo}\" width=\"20\" class=\"me-2\" alt=\"League logo\" />
                    {$homeTeam} <span style=\"color:#121f70\">vs</span> {$awayTeam}
                </td>
                <td class=\"text-end fw-semibold\" style=\"color:#121f70\">{$marketName}</td>
                <td class=\"text-end\" style=\"color:#121f70\">{$oddValue}</td>
            </tr>
        ";
    }
    
    if ($validMatches > 0 && $totalOdds != 1) {
        $totalOddsFormatted = number_format($totalOdds, 2);
        $tableRows[] = "
            <tr class=\"border-top\">
                <td colspan=\"2\" class=\"text-start fw-bold\" style=\"color:#121f70\">Total Odds @:</td>
                <td class=\"text-end fw-bold\" style=\"color:#121f70\">{$totalOddsFormatted}</td>
            </tr>
        ";
    }
    
    return implode('', $tableRows);
}

// Fetch accumulator tips data
$accumulatorMatches = fetchAccumulatorTips();
$accumulatorError = empty($accumulatorMatches) ? true : false;

// Remove duplicates and limit to 9 matches
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

<!-- Accumulator Tips Section -->
<div class="row custom-card desktop-mb-5 mt-3">
    <div id="accumulator-tips" class="text-dark mb-1">
        <?php if ($accumulatorError || !$hasEnoughMatches): ?>
            <div class="text-center text-danger py-3">
                <?php echo $accumulatorError ? 'Failed to fetch accumulator tips' : 'Not enough matches available for accumulator tips'; ?>
            </div>
        <?php else: ?>
            <h2 class="text-left" style="font-weight:bold;font-size:large" id="accumulator-date">
                Free Accumulator Tips: <?php echo formatAccumulatorDate(); ?>
            </h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="pb-1 rounded">
                        <table class="table text-dark" id="accumulator-table-1" style="border: 1px solid #ddd; border-radius: 5px;">
                            <tbody>
                                <?php echo renderAccumulatorTable($uniqueAccumulatorMatches, 0, 5); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="pb-1 rounded">
                        <table class="table text-dark" id="accumulator-table-2" style="border: 1px solid #ddd; border-radius: 5px;">
                            <tbody>
                                <?php echo renderAccumulatorTable($uniqueAccumulatorMatches, 5, 4); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

