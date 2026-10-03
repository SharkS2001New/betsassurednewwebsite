<?php

/**
 * Dashboard game rows from Pitch admin-backed tip APIs.
 * Prefer Sanctum auth tip endpoints; fall back to public free tips.
 */

if (!function_exists('dashboardFormatKickoff')) {
    function dashboardFormatKickoff($value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }
        $ts = strtotime((string) $value);
        if ($ts === false) {
            return (string) $value;
        }
        return date('D, H:i', $ts);
    }
}

if (!function_exists('dashboardPickLabel')) {
    function dashboardPickLabel(array $row): string
    {
        if (!empty($row['tip'])) {
            return strtoupper((string) $row['tip']);
        }
        if (!empty($row['double_chance'])) {
            return (string) $row['double_chance'];
        }
        if (!empty($row['both_team_to_score'])) {
            return 'BTTS ' . strtoupper((string) $row['both_team_to_score']);
        }

        $h = (int) str_replace('%', '', (string) ($row['percent_pred_home'] ?? '0'));
        $d = (int) str_replace('%', '', (string) ($row['percent_pred_draw'] ?? '0'));
        $a = (int) str_replace('%', '', (string) ($row['percent_pred_away'] ?? '0'));
        if ($h >= $d && $h >= $a) {
            return '1';
        }
        if ($d >= $h && $d >= $a) {
            return 'X';
        }
        return '2';
    }
}

if (!function_exists('dashboardNormalizeAuthRow')) {
    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    function dashboardNormalizeAuthRow(array $row): array
    {
        $kickoff = $row['date']
            ?? $row['fixture_date']
            ?? $row['fixture_date1']
            ?? $row['unformatedDate']
            ?? $row['unformatted_date']
            ?? '';

        $odd = $row['odd']
            ?? $row['bets_home']
            ?? $row['double_chance_home_draw']
            ?? $row['over_2_5']
            ?? null;

        return [
            'fixture_id' => $row['fixture_id'] ?? null,
            'home' => (string) ($row['home_team_name'] ?? 'Home'),
            'away' => (string) ($row['away_team_name'] ?? 'Away'),
            'league' => (string) ($row['league_name'] ?? $row['league_short_name'] ?? 'League'),
            'kickoff' => dashboardFormatKickoff($kickoff),
            'pick' => dashboardPickLabel($row),
            'odd' => $odd !== null && $odd !== '' ? number_format((float) $odd, 2) : '—',
            'score' => (
                isset($row['goals_home'], $row['goals_away'])
                && $row['goals_home'] !== null
                && $row['goals_away'] !== null
                && $row['goals_home'] !== ''
                && $row['goals_away'] !== ''
            ) ? ((string) $row['goals_home'] . ' - ' . (string) $row['goals_away']) : '—',
            'source' => 'auth',
        ];
    }
}

if (!function_exists('dashboardNormalizePublicTip')) {
    /**
     * @param  array<string, mixed>  $tip
     * @return array<string, mixed>
     */
    function dashboardNormalizePublicTip(array $tip): array
    {
        $h = (int) str_replace('%', '', (string) ($tip['percent_pred_home'] ?? '0'));
        $d = (int) str_replace('%', '', (string) ($tip['percent_pred_draw'] ?? '0'));
        $a = (int) str_replace('%', '', (string) ($tip['percent_pred_away'] ?? '0'));
        if ($h >= $d && $h >= $a) {
            $pick = '1';
            $odd = $tip['bets_home'] ?? $tip['odds_home'] ?? null;
        } elseif ($d >= $h && $d >= $a) {
            $pick = 'X';
            $odd = $tip['bets_draw'] ?? $tip['odds_draw'] ?? null;
        } else {
            $pick = '2';
            $odd = $tip['bets_away'] ?? $tip['odds_away'] ?? null;
        }

        return [
            'fixture_id' => $tip['fixture_id'] ?? null,
            'home' => (string) ($tip['home_team_name'] ?? 'Home'),
            'away' => (string) ($tip['away_team_name'] ?? 'Away'),
            'league' => (string) ($tip['league_name'] ?? 'League'),
            'kickoff' => dashboardFormatKickoff($tip['date'] ?? ($tip['unformatted_date'] ?? '')),
            'pick' => $pick,
            'odd' => $odd !== null && $odd !== '' && $odd !== '—' ? number_format((float) $odd, 2) : '—',
            'score' => (
                isset($tip['goals_home'], $tip['goals_away'])
                && $tip['goals_home'] !== null
                && $tip['goals_away'] !== null
                && $tip['goals_home'] !== ''
                && $tip['goals_away'] !== ''
            ) ? ((string) $tip['goals_home'] . ' - ' . (string) $tip['goals_away']) : '—',
            'source' => 'public',
        ];
    }
}

if (!function_exists('dashboardFetchAuthGames')) {
    /**
     * @return list<array<string, mixed>>
     */
    function dashboardFetchAuthGames(string $token, string $date, int $limit = 8): array
    {
        $endpoints = [
            "fetch_auth_double_chance_fixtures?fixture_date={$date}&start_index=0&end_index=" . max(0, $limit - 1),
            "fetch_auth_both_teams_to_score_fixtures?fixture_date={$date}&start_index=0&end_index=" . max(0, $limit - 1),
            "fetch_auth_over25_fixtures?fixture_date={$date}&start_index=0&end_index=" . max(0, $limit - 1),
        ];

        foreach ($endpoints as $path) {
            $result = authApiRequest('GET', $path, null, $token);
            $rows = $result['data']['data'] ?? null;
            if (!$result['ok'] || !is_array($rows) || $rows === []) {
                continue;
            }

            $normalized = [];
            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $normalized[] = dashboardNormalizeAuthRow($row);
                if (count($normalized) >= $limit) {
                    break;
                }
            }
            if ($normalized !== []) {
                return $normalized;
            }
        }

        return [];
    }
}

if (!function_exists('dashboardFetchVipGames')) {
    /**
     * @return list<array<string, mixed>>
     */
    function dashboardFetchVipGames(string $token, string $date, int $limit = 8): array
    {
        $path = "fetch_daily_multibet_games?fixture_date={$date}&category=vip&start_index=0&end_index=" . max(0, $limit - 1);
        $result = authApiRequest('GET', $path, null, $token);
        $rows = $result['data']['data'] ?? null;
        if (!$result['ok'] || !is_array($rows) || $rows === []) {
            return [];
        }

        $normalized = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $item = dashboardNormalizeAuthRow($row);
            $item['source'] = 'vip';
            $normalized[] = $item;
            if (count($normalized) >= $limit) {
                break;
            }
        }

        return $normalized;
    }
}

if (!function_exists('dashboardFetchPublicGames')) {
    /**
     * @return list<array<string, mixed>>
     */
    function dashboardFetchPublicGames(string $date, int $limit = 8): array
    {
        if (!function_exists('pitchApiHttpHeaders')) {
            return [];
        }

        $url = 'https://api.pitchpredictions.com/api/fetch_free_tips_by_date_fixtures?fixture_date=' . rawurlencode($date);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => pitchApiHttpHeaders(),
            CURLOPT_TIMEOUT => 12,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $raw = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code < 200 || $code >= 300 || !is_string($raw)) {
            return [];
        }

        $decoded = json_decode($raw, true);
        $rows = is_array($decoded['data'] ?? null) ? $decoded['data'] : [];
        if ($rows === []) {
            return [];
        }

        if (function_exists('normalizePitchPredictionsResponse')) {
            $rows = normalizePitchPredictionsResponse($rows);
        }

        $normalized = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $normalized[] = dashboardNormalizePublicTip($row);
            if (count($normalized) >= $limit) {
                break;
            }
        }

        return $normalized;
    }
}
