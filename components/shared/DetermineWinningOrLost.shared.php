<?php
function DetermineWinningOrLost($tip, $goals_home, $goals_away) {
    $has_won = '';

    $goalsAvailable = !is_null($goals_home) && !is_null($goals_away);

    if ($goalsAvailable) {
        $home = (int) $goals_home;
        $away = (int) $goals_away;
        $totalGoals = $home + $away;
        $normalizedTip = strtoupper(trim($tip));

        // 1X2
        if ($normalizedTip === '1' && $home > $away) {
            $has_won = wonSpan();
        } elseif ($normalizedTip === 'X' && $home === $away) {
            $has_won = wonSpan();
        } elseif ($normalizedTip === '2' && $home < $away) {
            $has_won = wonSpan();

        // Double Chance
        } elseif (in_array($normalizedTip, ['1X', 'DC1X', 'DCX1']) && ($home >= $away)) {
            $has_won = wonSpan();
        } elseif (in_array($normalizedTip, ['X2', 'DCX2', 'DC2X']) && ($away >= $home)) {
            $has_won = wonSpan();
        } elseif (in_array($normalizedTip, ['12', 'DC12', 'DC21']) && ($home !== $away)) {
            $has_won = wonSpan();

        // GG / NG
        } elseif (in_array($normalizedTip, ['GG', 'YES']) && $home > 0 && $away > 0) {
            $has_won = wonSpan();
        } elseif (in_array($normalizedTip, ['NOGG', 'NO']) && ($home === 0 || $away === 0)) {
            $has_won = wonSpan();

        // Over
        } elseif ($normalizedTip === 'OVER1.5' && $totalGoals >= 2) {
            $has_won = wonSpan();
        } elseif ($normalizedTip === 'OVER2.5' && $totalGoals >= 3) {
            $has_won = wonSpan();
        } elseif ($normalizedTip === 'OVER3.5' && $totalGoals >= 4) {
            $has_won = wonSpan();

        // Under
        } elseif ($normalizedTip === 'UNDER1.5' && $totalGoals <= 1) {
            $has_won = wonSpan();
        } elseif ($normalizedTip === 'UNDER2.5' && $totalGoals <= 2) {
            $has_won = wonSpan();
        } elseif ($normalizedTip === 'UNDER3.5' && $totalGoals <= 3) {
            $has_won = wonSpan();
        }
    }

    return $has_won;
}

function wonSpan() {
    return '<span style="font-weight:bold;border-radius:10px;padding:2px;background-color:green;border:1px solid green;color:white;font-size:11px;">Won</span>';
}

?>