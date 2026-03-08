<?php
function ComputeFixtureAverage($goals_for_home, $goals_against_home, $goals_for_away, $goals_against_away, $total_games_played_by_home, $total_games_played_by_away) {
    // Check for null values
    if ($goals_for_home === null || $goals_against_home === null ||
        $goals_for_away === null || $goals_against_away === null ||
        $total_games_played_by_home === null || $total_games_played_by_away === null) {
        return "-";
    }
    
    // Parse all values as float
    $goals_for_home = floatval($goals_for_home);
    $goals_against_home = floatval($goals_against_home);
    $goals_for_away = floatval($goals_for_away);
    $goals_against_away = floatval($goals_against_away);
    $total_games_played_by_home = floatval($total_games_played_by_home);
    $total_games_played_by_away = floatval($total_games_played_by_away);
    
    // Calculate total goals and total played
    $total_goals = $goals_for_home + $goals_against_home + $goals_for_away + $goals_against_away;
    $total_played = $total_games_played_by_home + $total_games_played_by_away;
    
    // Check for NaN or zero played
    if (is_nan($total_goals) || is_nan($total_played) || $total_played == 0) {
        return "-";
    }
    
    $average_goals = $total_goals / $total_played;
    
    return $average_goals > 0 ? number_format($average_goals, 2) : "-";
}
?>