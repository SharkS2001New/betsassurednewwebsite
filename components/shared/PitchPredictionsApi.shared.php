<?php

if (!function_exists('formatPitchPredictionPercent')) {
    function formatPitchPredictionPercent($value): string {
        if ($value === null || $value === '') {
            return '0%';
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return '0%';
            }
            if (str_ends_with($value, '%')) {
                return $value;
            }
            if (is_numeric($value)) {
                $value = $value + 0;
            }
        }

        if (is_int($value) || is_float($value)) {
            if ($value > 0 && $value <= 1) {
                return intval(round($value * 100, 0)) . '%';
            }
            return intval(round($value, 0)) . '%';
        }

        return '0%';
    }
}

if (!function_exists('normalizePitchPredictionTip')) {
    function normalizePitchPredictionTip(array $tip): array {
        $homeTeam = $tip['home_team'] ?? [];
        $awayTeam = $tip['away_team'] ?? [];
        $league = $tip['league'] ?? [];
        $match = $tip['match'] ?? [];
        $score = $tip['score'] ?? [];
        $predictions = $tip['predictions']['1x2'] ?? [];
        $odds = $tip['odds'] ?? [];

        $homePercent = formatPitchPredictionPercent($predictions['home'] ?? 0);
        $drawPercent = formatPitchPredictionPercent($predictions['draw'] ?? 0);
        $awayPercent = formatPitchPredictionPercent($predictions['away'] ?? 0);

        $oddsHome = isset($odds['home']) ? (string) $odds['home'] : null;
        $oddsDraw = isset($odds['draw']) ? (string) $odds['draw'] : null;
        $oddsAway = isset($odds['away']) ? (string) $odds['away'] : null;

        $doubleChance = $odds['double_chance'] ?? [];
        $bttsOdds = $odds['btts'] ?? [];
        $overUnderOdds = $odds['over_under'] ?? [];
        $htFtOdds = $odds['ht_ft'] ?? [];

        $doubleChanceValues = [
            ['value' => 'Home/Draw', 'odd' => isset($doubleChance['home_draw']) ? (string) $doubleChance['home_draw'] : '—'],
            ['value' => 'Home/Away', 'odd' => isset($doubleChance['home_away']) ? (string) $doubleChance['home_away'] : '—'],
            ['value' => 'Draw/Away', 'odd' => isset($doubleChance['draw_away']) ? (string) $doubleChance['draw_away'] : '—'],
        ];

        $bttsValues = [
            ['value' => 'Yes', 'odd' => isset($bttsOdds['yes']) ? (string) $bttsOdds['yes'] : '—'],
            ['value' => 'No', 'odd' => isset($bttsOdds['no']) ? (string) $bttsOdds['no'] : '—'],
        ];

        $overUnderValues = [];
        foreach ($overUnderOdds as $key => $value) {
            if (!is_scalar($value)) {
                continue;
            }
            $label = str_replace(['over_', 'under_'], ['Over ', 'Under '], $key);
            $label = str_replace('_', '.', $label);
            $overUnderValues[] = ['value' => $label, 'odd' => (string) $value];
        }

        $htFtValues = [
            ['value' => 'HT Home', 'odd' => isset($htFtOdds['ht_home']) ? (string) $htFtOdds['ht_home'] : '—'],
            ['value' => 'HT Draw', 'odd' => isset($htFtOdds['ht_draw']) ? (string) $htFtOdds['ht_draw'] : '—'],
            ['value' => 'HT Away', 'odd' => isset($htFtOdds['ht_away']) ? (string) $htFtOdds['ht_away'] : '—'],
        ];

        $allBetsOdds = [
            [
                'name' => 'Match Winner',
                'values' => [
                    ['value' => 'Home', 'odd' => $oddsHome ?? '—'],
                    ['value' => 'Draw', 'odd' => $oddsDraw ?? '—'],
                    ['value' => 'Away', 'odd' => $oddsAway ?? '—'],
                ],
            ],
            [
                'name' => 'Double Chance',
                'values' => $doubleChanceValues,
            ],
            [
                'name' => 'Both Teams Score',
                'values' => $bttsValues,
            ],
        ];

        if (!empty($overUnderValues)) {
            $allBetsOdds[] = [
                'name' => 'Over/Under',
                'values' => $overUnderValues,
            ];
        }

        if (!empty($htFtValues)) {
            $allBetsOdds[] = [
                'name' => 'HT/FT',
                'values' => $htFtValues,
            ];
        }

        $goalsOverUnder = null;
        if (!empty($overUnderValues)) {
            $goalsOverUnder = json_encode($overUnderValues);
        }

        $bothTeamToScorePrediction = $tip['predictions']['both_teams_to_score']['prediction'] ?? null;
        if (is_string($bothTeamToScorePrediction)) {
            $bothTeamToScorePrediction = trim($bothTeamToScorePrediction);
        }

        $averageGoals = $tip['predictions']['avg_goals'] ?? $tip['predictions']['average_goals'] ?? null;

        return [
            'fixture_id' => $tip['fixture_id'] ?? null,
            'league_id' => $tip['league_id'] ?? null,
            'home_team_name' => $homeTeam['name'] ?? '',
            'away_team_name' => $awayTeam['name'] ?? '',
            'home_team_logo' => $homeTeam['logo'] ?? '',
            'away_team_logo' => $awayTeam['logo'] ?? '',
            'league_name' => $league['name'] ?? '',
            'country_name' => $league['country'] ?? $league['country_name'] ?? '',
            'status_short' => $match['status'] ?? '',
            'status_long' => $match['status_long'] ?? '',
            'date' => $match['datetime'] ?? ($match['unformatted_date'] ?? ''),
            'goals_home' => $score['home'] ?? null,
            'goals_away' => $score['away'] ?? null,
            'percent_pred_home' => $homePercent,
            'percent_pred_draw' => $drawPercent,
            'percent_pred_away' => $awayPercent,
            'average_goals' => $averageGoals,
            'both_team_to_score' => $bothTeamToScorePrediction,
            'goals_over_under' => $goalsOverUnder,
            'odds_home' => $oddsHome,
            'odds_draw' => $oddsDraw,
            'odds_away' => $oddsAway,
            'all_bets_odds' => json_encode($allBetsOdds),
            'league_short_name' => $league['short_name'] ?? '',
            'country_logo' => $league['country_logo'] ?? '',
            'match_recommendation' => $tip['predictions']['recommendation'] ?? '',
        ];
    }
}

if (!function_exists('normalizePitchPredictionsResponse')) {
    function normalizePitchPredictionsResponse(array $items): array {
        return array_map('normalizePitchPredictionTip', $items);
    }
}

if (!function_exists('pitchApiAccessToken')) {
    /**
     * ACCESS_TOKEN for Pitch Predictions general API (not JACKPOT_API_KEY).
     */
    function pitchApiAccessToken(): string
    {
        $token = getenv('ACCESS_TOKEN') ?: ($_ENV['ACCESS_TOKEN'] ?? '');
        $token = trim((string) $token);
        if ($token !== '') {
            return $token;
        }

        // Fallback matches pitchpredictionsbackend ACCESS_TOKEN until env is set in deploy.
        return 'UJlhuDILIR1Lc2IEwZDIKOln9d';
    }
}

if (!function_exists('pitchApiOrigin')) {
    function pitchApiOrigin(): string
    {
        $origin = getenv('APP_URL') ?: ($_ENV['APP_URL'] ?? 'https://www.betsassured.com');
        $origin = rtrim(trim((string) $origin), '/');
        return $origin !== '' ? $origin : 'https://www.betsassured.com';
    }
}

if (!function_exists('pitchApiHttpHeaders')) {
    /**
     * Headers required by backend EnsureApiAllowedOrigin for PHP/server clients.
     *
     * @return array<int, string>
     */
    function pitchApiHttpHeaders(): array
    {
        return [
            'Origin: ' . pitchApiOrigin(),
            'Authorization: Bearer ' . pitchApiAccessToken(),
        ];
    }
}
