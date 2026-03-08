<?php
function WinningTeamPred1x2($hometeamodd, $drawodd, $awayteamodd, $goals_home = null, $goals_away = null) {
    $winning_team = "";
    $has_won = "";
    
    $hometeamodd = percentToInt($hometeamodd);
    $drawodd = percentToInt($drawodd);
    $awayteamodd = percentToInt($awayteamodd);
    
    $goalsAvailable = ($goals_home !== null && $goals_away !== null);
    
    if ($hometeamodd > $drawodd && $hometeamodd > $awayteamodd) {
        $winning_team = "1";
        if ($goalsAvailable && $goals_home > $goals_away) {
            $has_won = "Won";
        }
    } else if ($drawodd > $hometeamodd && $drawodd > $awayteamodd) {
        $winning_team = "X";
        if ($goalsAvailable && $goals_home == $goals_away) {
            $has_won = "Won";
        }
    } else if ($awayteamodd > $drawodd && $awayteamodd > $hometeamodd) {
        $winning_team = "2";
        if ($goalsAvailable && $goals_home < $goals_away) {
            $has_won = "Won";
        }
    } else if ($drawodd == $hometeamodd && $awayteamodd < $hometeamodd) {
        $winning_team = "1";
        if ($goalsAvailable && $goals_home == $goals_away) {
            $has_won = "Won";
        }
    } else if ($drawodd == $hometeamodd && $awayteamodd > $drawodd) {
        $winning_team = "2";
        if ($goalsAvailable && $goals_home < $goals_away) {
            $has_won = "Won";
        }
    } else if ($drawodd == $awayteamodd && $hometeamodd < $awayteamodd) {
        $winning_team = "X";
    } else if ($hometeamodd == $awayteamodd && $hometeamodd > $drawodd) {
        $winning_team = "1";
        if ($goalsAvailable && $goals_home > $goals_away) {
            $has_won = "Won";
        }
    } else if ($hometeamodd == $drawodd && $drawodd == $awayteamodd) {
        $winning_team = "X";
        if ($goalsAvailable && $goals_home == $goals_away) {
            $has_won = "Won";
        }
    }
    
    return ['winning_team' => $winning_team, 'has_won' => $has_won];
}
?>