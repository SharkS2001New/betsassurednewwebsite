<?php
// ── Data fetching & helpers (unchanged logic) ────────────────────────────────

function fetchPopularTipsSlider() {
    $startDate = date('Y-m-d');
    $endDate   = date('Y-m-d', strtotime('+2 days'));

    $apiUrl = "https://api.pitchpredictions.com/api/fetch_popular_tips_slider_fixtures?start_date={$startDate}&end_date={$endDate}";
    $token  = "R9TxV3PbOEu7qZnJKgydC5LmX2";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . $token]);
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

    curl_close($ch);
    return $matches;
}

function getPopularTipPrediction($match) {
    $h = intval(str_replace('%', '', $match['percent_pred_home'] ?? '0'));
    $d = intval(str_replace('%', '', $match['percent_pred_draw'] ?? '0'));
    $a = intval(str_replace('%', '', $match['percent_pred_away'] ?? '0'));

    if ($h > $d && $h > $a) return "1, {$h}% Win Probability";
    if ($d > $h && $d > $a) return "X, {$d}% Win Probability";
    return "2, {$a}% Win Probability";
}

function formatSliderDateTime($date) {
    return date('M d, H:i', strtotime($date));
}

$popularMatches = fetchPopularTipsSlider();
$popularError   = empty($popularMatches);

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
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: #dde3ef;
    color: #444;
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    transition: background 0.15s;
}
.pts-arrow:hover { background: #bcc5d8; }
.pts-arrow-left  { margin-right: 4px; }
.pts-arrow-right { margin-left:  4px; }

/* Scrollable strip */
.pts-strip {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    gap: 8px;
    padding: 4px 2px 6px;
    flex: 1;
    scrollbar-width: none;        /* Firefox */
    -ms-overflow-style: none;     /* IE */
}
.pts-strip::-webkit-scrollbar { display: none; }

/* ── Individual card ──────────────────────────────────────────────────────── */
.pts-card {
    flex: 0 0 auto;
    width: 210px;
    background: #fff;
    border: 1px solid #dde3ef;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    display: flex;
    flex-direction: column;
    font-family: inherit;
}

/* Date bar */
.pts-date {
    text-align: center;
    font-size: 0.78rem;
    font-weight: 600;
    color: #2c3e50;
    padding: 8px 6px 6px;
    border-bottom: 1px solid #edf0f7;
    background: #f7f9fc;
}

/* Teams row */
.pts-teams {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 8px 8px;
    gap: 4px;
}

.pts-team {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    min-width: 0;
    gap: 5px;
}

.pts-logo-wrap {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f2f8;
    flex-shrink: 0;
}

.pts-logo {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.pts-team-name {
    font-size: 0.72rem;
    font-weight: 700;
    color: #1a1a2e;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 80px;
    line-height: 1.2;
}

.pts-vs {
    font-size: 0.75rem;
    font-weight: 700;
    color: #1a2e8a;
    flex-shrink: 0;
    padding: 0 2px;
    padding-top: 0;   /* align with logos */
    align-self: center;
}

/* Tip bar */
.pts-tip {
    background: #334155;
    color: #fff;
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
        <button class="pts-arrow pts-arrow-left" onclick="ptsScroll(-240)" aria-label="Scroll left">&#8249;</button>

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

        <button class="pts-arrow pts-arrow-right" onclick="ptsScroll(240)" aria-label="Scroll right">&#8250;</button>
    </div>
</div>

<script>
(function () {
    const strip = document.getElementById('pts-slider');
    window.ptsScroll = function (amount) {
        strip.scrollBy({ left: amount, behavior: 'smooth' });
    };
})();
</script>