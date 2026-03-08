<?php
// Add this function with your other PHP functions
function fetchPopularTipsSlider() {
    $startDate = date('Y-m-d');
    $endDate = date('Y-m-d', strtotime('+2 days'));
    
    $apiUrl = "https://api.pitchpredictions.com/api/fetch_popular_tips_slider_fixtures?start_date={$startDate}&end_date={$endDate}";
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

function getPopularTipPrediction($match) {
    $percent_home = intval(str_replace('%', '', $match['percent_pred_home'] ?? '0'));
    $percent_draw = intval(str_replace('%', '', $match['percent_pred_draw'] ?? '0'));
    $percent_away = intval(str_replace('%', '', $match['percent_pred_away'] ?? '0'));
    
    if ($percent_home > $percent_draw && $percent_home > $percent_away) {
        return "1, {$percent_home}% Win Probability";
    }
    if ($percent_draw > $percent_home && $percent_draw > $percent_away) {
        return "X, {$percent_draw}% Win Probability";
    }
    return "2, {$percent_away}% Win Probability";
}

function formatSliderDateTime($date) {
    return date('M d, H:i', strtotime($date));
}

// Fetch popular tips data
$popularMatches = fetchPopularTipsSlider();
$popularError = empty($popularMatches) ? true : false;
?>

<!-- Popular Tips Slider Section -->
<section class="container d-flex" style="padding-top: 1.0rem">
  <div class="desktop-container-resize mb-0">
    <div class="col-sm-12 text-left bg-light">
      <h2 style="font-size: 18px">POPULAR TIPS FOR TODAY</h2>
    </div>
  </div>
</section>

<div class="match-slider-container">
  <button class="slider-btn left-btn" onclick="scrollSlider(-300)">&#8249;</button>
  <div class="match-slider" id="slider">
    <?php if ($popularError): ?>
      <div style="text-align:center; width: 100%; padding: 20px;">
        Failed to fetch matches
      </div>
    <?php elseif (empty($popularMatches)): ?>
      <div style="text-align:center; width: 100%; padding: 20px;">
        No popular tips available at the moment
      </div>
    <?php else: ?>
      <?php foreach ($popularMatches as $match): ?>
        <div class="match-card">
          <div class="date-bar">
            <span style="color: #212830">Date: <?php echo formatSliderDateTime($match['date'] ?? date('Y-m-d H:i:s')); ?></span>
          </div>
          <div class="match-header">
            <div class="team">
              <div class="team-logo-container">
                <?php if (!empty($match['home_team_logo'])): ?>
                  <img src="<?php echo htmlspecialchars($match['home_team_logo']); ?>" 
                       alt="<?php echo htmlspecialchars($match['home_team_name'] ?? 'Home'); ?>" 
                       class="team-logo" 
                       onerror="this.src='data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%3E%3Crect%20width%3D%2240%22%20height%3D%2240%22%20fill%3D%22%23f0f0f0%22%2F%3E%3Ctext%20x%3D%2220%22%20y%3D%2225%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20fill%3D%22%23999%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E';" />
                <?php else: ?>
                  <img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%3E%3Crect%20width%3D%2240%22%20height%3D%2240%22%20fill%3D%22%23f0f0f0%22%2F%3E%3Ctext%20x%3D%2220%22%20y%3D%2225%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20fill%3D%22%23999%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E" 
                       alt="Default logo" 
                       class="team-logo" />
                <?php endif; ?>
              </div>
              <p class="team-name"><?php echo htmlspecialchars($match['home_team_name'] ?? 'Home'); ?></p>
            </div>
            <div class="vs">vs</div>
            <div class="team">
              <div class="team-logo-container">
                <?php if (!empty($match['away_team_logo'])): ?>
                  <img src="<?php echo htmlspecialchars($match['away_team_logo']); ?>" 
                       alt="<?php echo htmlspecialchars($match['away_team_name'] ?? 'Away'); ?>" 
                       class="team-logo"
                       onerror="this.src='data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%3E%3Crect%20width%3D%2240%22%20height%3D%2240%22%20fill%3D%22%23f0f0f0%22%2F%3E%3Ctext%20x%3D%2220%22%20y%3D%2225%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20fill%3D%22%23999%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E';" />
                <?php else: ?>
                  <img src="data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2240%22%20height%3D%2240%22%20viewBox%3D%220%200%2040%2040%22%3E%3Crect%20width%3D%2240%22%20height%3D%2240%22%20fill%3D%22%23f0f0f0%22%2F%3E%3Ctext%20x%3D%2220%22%20y%3D%2225%22%20font-size%3D%2218%22%20text-anchor%3D%22middle%22%20fill%3D%22%23999%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E" 
                       alt="Default logo" 
                       class="team-logo" />
                <?php endif; ?>
              </div>
              <p class="team-name"><?php echo htmlspecialchars($match['away_team_name'] ?? 'Away'); ?></p>
            </div>
          </div>
          <div class="tip-bar">
            <div class="tip-value">
              Tip: <strong><?php echo htmlspecialchars(getPopularTipPrediction($match)); ?></strong>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <button class="slider-btn right-btn" onclick="scrollSlider(300)">&#8250;</button>
</div>

<script>
  // Keep only the scroll function, remove all fetch and render logic
  const slider = document.getElementById('slider');

  function scrollSlider(amount) {
    slider.scrollBy({ left: amount, behavior: 'smooth' });
  }
</script>