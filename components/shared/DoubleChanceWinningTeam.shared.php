<?php
function DoubleChanceWinningTeam($hometeamodd, $drawodd, $awayteamodd, $goals_home = null, $goals_away = null) {
    $winning_team = "";
    $has_won = "";
    
    $hometeamodd = percentToInt($hometeamodd);
    $drawodd = percentToInt($drawodd);
    $awayteamodd = percentToInt($awayteamodd);
    
    $isValidScore = ($goals_home !== null && $goals_away !== null);
    
    if (($hometeamodd > $drawodd && $hometeamodd > $awayteamodd) && $drawodd > $awayteamodd) {
        $winning_team = "1X";
        if ($isValidScore && ($goals_home >= $goals_away)) $has_won = "Won";
    } else if (($hometeamodd > $drawodd && $hometeamodd > $awayteamodd) && $drawodd == $awayteamodd) {
        $winning_team = "1X";
        if ($isValidScore && ($goals_home >= $goals_away)) $has_won = "Won";
    } else if (($awayteamodd > $drawodd && $awayteamodd > $hometeamodd) && $drawodd == $hometeamodd) {
        $winning_team = "X2";
        if ($isValidScore && ($goals_away >= $goals_home)) $has_won = "Won";
    } else if (($drawodd > $hometeamodd && $drawodd > $awayteamodd) && $awayteamodd > $hometeamodd) {
        $winning_team = "X2";
        if ($isValidScore && ($goals_away >= $goals_home)) $has_won = "Won";
    } else if (($drawodd > $hometeamodd && $drawodd > $awayteamodd) && $hometeamodd > $awayteamodd) {
        $winning_team = "1X";
        if ($isValidScore && ($goals_home >= $goals_away)) $has_won = "Won";
    } else if (($awayteamodd > $hometeamodd && $awayteamodd > $drawodd) && $hometeamodd > $drawodd) {
        $winning_team = "12";
        if ($isValidScore && ($goals_home != $goals_away)) $has_won = "Won";
    } else if (($awayteamodd > $hometeamodd && $awayteamodd > $drawodd) && $drawodd > $hometeamodd) {
        $winning_team = "X2";
        if ($isValidScore && ($goals_away >= $goals_home)) $has_won = "Won";
    } else if (($hometeamodd > $drawodd && $hometeamodd > $awayteamodd) && $awayteamodd > $drawodd) {
        $winning_team = "12";
        if ($isValidScore && ($goals_home != $goals_away)) $has_won = "Won";
    } else if ($hometeamodd == $drawodd && $drawodd == $awayteamodd) {
        $winning_team = "1X";
        if ($isValidScore && ($goals_home >= $goals_away)) $has_won = "Won";
    } else if ($hometeamodd == $drawodd && $hometeamodd > $awayteamodd) {
        $winning_team = "1X";
        if ($isValidScore && ($goals_home >= $goals_away)) $has_won = "Won";
    } else if ($hometeamodd == $awayteamodd && $hometeamodd > $drawodd) {
        $winning_team = "12";
        if ($isValidScore && ($goals_home != $goals_away)) $has_won = "Won";
    } else if ($awayteamodd == $drawodd && $awayteamodd > $hometeamodd) {
        $winning_team = "X2";
        if ($isValidScore && ($goals_away >= $goals_home)) $has_won = "Won";
    } else if ($hometeamodd == $awayteamodd && $drawodd > $hometeamodd) {
        $winning_team = "X2";
        if ($isValidScore && ($goals_away >= $goals_home)) $has_won = "Won";
    } else if ($hometeamodd == $awayteamodd && $drawodd > $awayteamodd) {
        $winning_team = "1X";
        if ($isValidScore && ($goals_home >= $goals_away)) $has_won = "Won";
    }
    
    return ['winning_team' => $winning_team, 'has_won' => $has_won];
}
?>