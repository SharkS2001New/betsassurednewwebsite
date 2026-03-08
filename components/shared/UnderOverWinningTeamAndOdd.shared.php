<?php
function UnderOverWinningTeamAndOdd($averageGoals, $path) {
    $winning_team = "";
    
    if ($averageGoals === "-") {
        $winning_team = "-";
    } else {
        // Remove leading slash if present
        $cleanPath = (strpos($path, "/") === 0) ? substr($path, 1) : $path;
        $averageGoals = floatval($averageGoals);
        
        // Determine prediction based on the provided path
        if ($cleanPath === "predictions/1-5-goals") {
            $winning_team = $averageGoals < 1.5 ? "Under" : "Over";
        } else if ($cleanPath === "predictions/2-5-goals") {
            $winning_team = $averageGoals < 2.5 ? "Under" : "Over";
        } else if ($cleanPath === "predictions/3-5-goals") {
            $winning_team = $averageGoals < 3.5 ? "Under" : "Over";
        } else {
            $winning_team = "-"; // fallback for other paths
        }
    }
    
    return $winning_team;
}
?>