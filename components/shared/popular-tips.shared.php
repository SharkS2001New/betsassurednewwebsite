<?php
// ── Data fetching & helpers ──────────────────────────────────────────────────

if (!function_exists('fetchPopularTipsSlider')) {
    function fetchPopularTipsSlider() {
        $startDate = date('Y-m-d');
        $endDate   = date('Y-m-d', strtotime('+2 days'));

        $apiUrl = "https://api.pitchpredictions.com/api/fetch_popular_tips_slider_fixtures?start_date={$startDate}&end_date={$endDate}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, pitchApiHttpHeaders());
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $matches  = [];

        if (!curl_errno($ch) && $httpCode === 200) {
            $data = json_decode($response, true);
            if (isset($data['data']) && is_array($data['data'])) {
                $matches = $data['data'];
            }
        }

        return $matches;
    }
}

if (!function_exists('normalizePopularTipMatch')) {
    /**
     * Support both flat rows and FixtureResource nested payloads.
     */
    function normalizePopularTipMatch(array $match): array
    {
        $homeTeam = is_array($match['home_team'] ?? null) ? $match['home_team'] : [];
        $awayTeam = is_array($match['away_team'] ?? null) ? $match['away_team'] : [];
        $league = is_array($match['league'] ?? null) ? $match['league'] : [];
        $game = is_array($match['match'] ?? null) ? $match['match'] : [];
        $predictions = is_array($match['predictions']['1x2'] ?? null) ? $match['predictions']['1x2'] : [];

        $homePercent = $match['percent_pred_home']
            ?? $predictions['home']
            ?? 0;
        $drawPercent = $match['percent_pred_draw']
            ?? $predictions['draw']
            ?? 0;
        $awayPercent = $match['percent_pred_away']
            ?? $predictions['away']
            ?? 0;

        if (function_exists('formatPitchPredictionPercent')) {
            $homePercent = formatPitchPredictionPercent($homePercent);
            $drawPercent = formatPitchPredictionPercent($drawPercent);
            $awayPercent = formatPitchPredictionPercent($awayPercent);
        } else {
            $homePercent = is_numeric($homePercent) ? ((float) $homePercent <= 1 ? round($homePercent * 100) . '%' : round($homePercent) . '%') : (string) $homePercent;
            $drawPercent = is_numeric($drawPercent) ? ((float) $drawPercent <= 1 ? round($drawPercent * 100) . '%' : round($drawPercent) . '%') : (string) $drawPercent;
            $awayPercent = is_numeric($awayPercent) ? ((float) $awayPercent <= 1 ? round($awayPercent * 100) . '%' : round($awayPercent) . '%') : (string) $awayPercent;
        }

        $date = $match['date']
            ?? ($game['datetime'] ?? null)
            ?? ($game['unformatted_date'] ?? null)
            ?? date('Y-m-d H:i:s');

        return [
            'home_team_name' => $match['home_team_name'] ?? ($homeTeam['name'] ?? 'Home'),
            'away_team_name' => $match['away_team_name'] ?? ($awayTeam['name'] ?? 'Away'),
            'home_team_logo' => $match['home_team_logo'] ?? ($homeTeam['logo'] ?? ''),
            'away_team_logo' => $match['away_team_logo'] ?? ($awayTeam['logo'] ?? ''),
            'league_name' => $match['league_name'] ?? ($league['name'] ?? ''),
            'date' => $date,
            'percent_pred_home' => $homePercent,
            'percent_pred_draw' => $drawPercent,
            'percent_pred_away' => $awayPercent,
        ];
    }
}

if (!function_exists('getPopularTipPrediction')) {
    function getPopularTipPrediction($match) {
        $h = intval(str_replace('%', '', $match['percent_pred_home'] ?? '0'));
        $d = intval(str_replace('%', '', $match['percent_pred_draw'] ?? '0'));
        $a = intval(str_replace('%', '', $match['percent_pred_away'] ?? '0'));

        if ($h > $d && $h > $a) return "1, {$h}% Win Probability";
        if ($d > $h && $d > $a) return "X, {$d}% Win Probability";
        return "2, {$a}% Win Probability";
    }
}

if (!function_exists('formatSliderDateTime')) {
    function formatSliderDateTime($date) {
        $ts = strtotime((string) $date);
        if ($ts === false) {
            return date('M d, H:i');
        }
        return date('M d, H:i', $ts);
    }
}

if (!function_exists('shuffleTipsWithSeed')) {
    /**
     * Deterministic Fisher–Yates shuffle so order varies by day/section
     * without changing on every page refresh.
     */
    function shuffleTipsWithSeed(array $items, string $seed): array
    {
        $items = array_values($items);
        $n = count($items);
        if ($n < 2) {
            return $items;
        }

        $hash = hash('sha256', $seed);
        for ($i = $n - 1; $i > 0; $i--) {
            $hash = hash('sha256', $hash . $i);
            $j = (int) (hexdec(substr($hash, 0, 8)) % ($i + 1));
            $tmp = $items[$i];
            $items[$i] = $items[$j];
            $items[$j] = $tmp;
        }

        return $items;
    }
}

if (!function_exists('tipMatchKey')) {
    function tipMatchKey(array $match): string
    {
        $home = strtolower(trim((string) ($match['home_team_name'] ?? '')));
        $away = strtolower(trim((string) ($match['away_team_name'] ?? '')));

        return $home . '|' . $away;
    }
}

$popularMatchesRaw = fetchPopularTipsSlider();
$popularMatches = array_map('normalizePopularTipMatch', $popularMatchesRaw);
$popularError   = empty($popularMatchesRaw);

if (!$popularError && $popularMatches !== []) {
    $popularMatches = shuffleTipsWithSeed($popularMatches, 'popular-tips-' . date('Y-m-d'));
    // Keep the carousel readable; pull a mixed subset when the API returns many.
    if (count($popularMatches) > 16) {
        $popularMatches = array_slice($popularMatches, 0, 16);
    }

    $sliderKeys = [];
    foreach ($popularMatches as $match) {
        $key = tipMatchKey($match);
        if ($key !== '|') {
            $sliderKeys[$key] = true;
        }
    }
    $GLOBALS['betsassured_slider_match_keys'] = $sliderKeys;
}

// Default logo SVG (inline data URI)
$defaultLogo = "data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%3E%3Crect%20width%3D%2240%22%20height%3D%2240%22%20fill%3D%22%23e8eaf0%22%2F%3E%3Ctext%20x%3D%2220%22%20y%3D%2226%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20fill%3D%22%23aaa%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E";
?>

<style>
/* ── Popular Tips Slider ─────────────────────────────────────────────────── */
.pts-section {
    padding: 10px 0 4px;
}

.pts-heading {
    font-size: 1rem;
    font-weight: 800;
    color: #1a1a2e;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 10px;
    padding: 0 4px;
}

/* Wrapper holds arrows + scrollable strip */
.pts-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

/* Arrow buttons */
.pts-arrow {
    flex-shrink: 0;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid #dde3ef;
    background: #fff;
    color: #1a1a2e;
    font-size: 1.4rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    transition: background 0.15s, box-shadow 0.15s;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
}

.pts-arrow:hover {
    background: #f0f4ff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}

.pts-arrow-left  { margin-right: 6px; }
.pts-arrow-right { margin-left: 6px; }

/* Scrollable strip */
.pts-strip {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 4px 2px 10px;
    flex: 1;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.pts-strip::-webkit-scrollbar { display: none; }

/* Individual card */
.pts-card {
    flex: 0 0 210px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.15s, transform 0.15s;
}

.pts-card:hover {
    box-shadow: 0 4px 14px rgba(0,0,0,0.10);
    transform: translateY(-2px);
}

.pts-date {
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    padding: 8px 10px 0;
    text-align: center;
}

.pts-teams {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 12px 8px;
    gap: 4px;
}

.pts-team {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    flex: 1;
    min-width: 0;
}

.pts-logo-wrap {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.pts-logo {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.pts-team-name {
    font-size: 0.72rem;
    font-weight: 600;
    color: #0f172a;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 80px;
}

.pts-vs {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    flex-shrink: 0;
    padding: 0 2px;
}

.pts-tip {
    background: #0d1b2a;
    color: #c8f135;
    font-size: 0.76rem;
    font-weight: 700;
    text-align: center;
    padding: 7px 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    border-top: none;
    margin-top: auto;
}

/* Error / empty state */
.pts-empty {
    width: 100%;
    text-align: center;
    padding: 20px;
    color: #888;
    font-size: 0.9rem;
}
</style>

<!-- ── Popular Tips Slider ─────────────────────────────────────────────── -->
<div class="pts-section">
    <div class="pts-heading">Popular Tips For Today</div>

    <div class="pts-wrapper">
        <button class="pts-arrow pts-arrow-left" onclick="ptsScroll(-240)" aria-label="Scroll left" type="button">&#8249;</button>

        <div class="pts-strip" id="pts-slider">
            <?php if ($popularError): ?>
                <div class="pts-empty">Failed to fetch matches.</div>
            <?php elseif (empty($popularMatches)): ?>
                <div class="pts-empty">No popular tips available at the moment.</div>
            <?php else: ?>
                <?php foreach ($popularMatches as $match): ?>
                    <?php
                        $homeLogo  = !empty($match['home_team_logo']) ? htmlspecialchars($match['home_team_logo']) : $defaultLogo;
                        $awayLogo  = !empty($match['away_team_logo']) ? htmlspecialchars($match['away_team_logo']) : $defaultLogo;
                        $homeName  = htmlspecialchars($match['home_team_name'] ?? 'Home');
                        $awayName  = htmlspecialchars($match['away_team_name'] ?? 'Away');
                        $dateStr   = formatSliderDateTime($match['date'] ?? date('Y-m-d H:i:s'));
                        $tip       = htmlspecialchars(getPopularTipPrediction($match));
                    ?>
                    <div class="pts-card">
                        <div class="pts-date">Date: <?php echo $dateStr; ?></div>

                        <div class="pts-teams">
                            <div class="pts-team">
                                <div class="pts-logo-wrap">
                                    <img src="<?php echo $homeLogo; ?>"
                                         alt="<?php echo $homeName; ?>"
                                         class="pts-logo"
                                         onerror="this.src='<?php echo $defaultLogo; ?>'" />
                                </div>
                                <span class="pts-team-name"><?php echo $homeName; ?></span>
                            </div>

                            <span class="pts-vs">vs</span>

                            <div class="pts-team">
                                <div class="pts-logo-wrap">
                                    <img src="<?php echo $awayLogo; ?>"
                                         alt="<?php echo $awayName; ?>"
                                         class="pts-logo"
                                         onerror="this.src='<?php echo $defaultLogo; ?>'" />
                                </div>
                                <span class="pts-team-name"><?php echo $awayName; ?></span>
                            </div>
                        </div>

                        <div class="pts-tip">Tip: <strong><?php echo $tip; ?></strong></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <button class="pts-arrow pts-arrow-right" onclick="ptsScroll(240)" aria-label="Scroll right" type="button">&#8250;</button>
    </div>
</div>

<script>
(function () {
    const strip = document.getElementById('pts-slider');
    window.ptsScroll = function (amount) {
        if (strip) {
            strip.scrollBy({ left: amount, behavior: 'smooth' });
        }
    };
})();
</script>
