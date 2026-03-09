<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Weekly Jackpot Predictions for 30+ Betting Sites</title>
<meta name="title" content="All Jackpot Prediction – Daily Tips for 30+ Jackpots">
<meta name="description" content="Free weekly jackpot predictions for Sportpesa, Betika, Betway, Mozzart and 25+ betting sites. Expert analysis for Mega Jackpot, Midweek Jackpot and daily jackpots across africa.">
<meta name="keywords" content="jackpot predictions, free jackpot tips, sportpesa mega jackpot, betika jackpot, jackpot analysis, kenya jackpot predictions">

<!-- Open Graph -->
<meta property="og:title" content="Football Jackpot Predictions Today">
<meta property="og:description" content="Explore the latest football jackpot tips to improve your chances of winning big. Expertly picked for serious punters.">

<!-- Twitter -->
<meta property="twitter:title" content="Football Jackpot Predictions Today">
<meta property="twitter:description" content="Explore the latest football jackpot tips to improve your chances of winning big. Expertly picked for serious punters.">
HTML;

// Preloader & Header
include_once BASE_PATH . "/components/includes/header.inc.php";
?>

<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";

$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/jackpot-predictions.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// Group jackpots by region/category for better organization
$jackpotCategories = [
    "🇰🇪 Kenya" => [
        "Sportpesa Mega Jackpot" => "/sportpesa-mega-jackpot-predictions",
        "Sportpesa Midweek Jackpot" => "/sportpesa-midweek-jackpot-predictions",
        "Betika Midweek Jackpot" => "/betika-midweek-jackpot-predictions",
        "Betika Mega Jackpot" => "/betika-grand-jackpot-predictions",
        "Mozzart Super Daily Jackpot" => "/mozzart-super-daily-jackpot-predictions",
        "Mozzart Super Grand Jackpot" => "/mozzart-super-grand-jackpot-predictions",
        "Shabiki Midweek Jackpot" => "/shabiki-jackpot-predictions",
        "Odibet Laki Tatu Daily Jackpot" => "/odibet-laki-tatu-daily-jackpot-predictions",
        "MerryBet Jackpot" => "/merrybet-jackpot-predictions",
        "BetKing Jackpot" => "/betking-jackpot-predictions",
        "Betlion Daily JP Jackpot" => "/betlion-daily-jp-jackpot-predictions",
        "Betlion Goliath Jackpot" => "/betlion-goliath-jackpot-predictions",
        "Betway Jackpot Kenya" => "/betway-jackpot-predictions-kenya",
    ],
    "🇳🇬 Nigeria" => [
        "Bet9ja Supa9ja Jackpot" => "/bet9ja-supa9ja-jackpot-predictions",
        "1XBet Toto 15 Jackpot" => "/1xbet-toto-15-jackpot-predictions",
        "22 Bet Toto Jackpot" => "/22-bet-toto-jackpot-predictions",
        "Sportybet Jackpot" => "/sportybet-jackpot-predictions",
        "Betpawa Pick13 Nigeria" => "/betpawa-pick13-jackpot-predictions-nigeria",
    ],
    "🇹🇿 Tanzania" => [
        "Sportpesa Supa Jackpot 17 TZ" => "/sportpesa-supa-jackpot-17-predictions-tz",
        "Sportpesa Supa Jackpot 13 TZ" => "/sportpesa-supa-jackpot-13-predictions-tz",
        "Betika Kitonga Jackpot TZ" => "/betika-kitonga-jackpot-tz",
        "Betpawa Pick13 Tanzania" => "/betpawa-pick13-jackpot-predictions-tanzania",
        "Betway Jackpot Tanzania" => "/betway-jackpot-predictions-tanzania",
    ],
    "🇺🇬 Uganda" => [
        "Betpawa Pick13 Uganda" => "/betpawa-pick13-jackpot-predictions-uganda",
        "Betway Jackpot Uganda" => "/betway-jackpot-predictions-uganda",
    ],
    "🌍 Other Regions" => [
        "Betpawa Pick13 Zambia" => "/betpawa-pick13-jackpot-predictions-zambia",
        "Betpawa Pick13 Ghana" => "/betpawa-pick13-jackpot-predictions-ghana",
        "Betpawa Pick13 Cameroon" => "/betpawa-pick13-jackpot-predictions-cameroon",
        "Betpawa Pick13 DR Congo" => "/betpawa-pick13-jackpot-predictions-dr-congo",
    ]
];
?>

<style>
/* Modern Jackpot Page Styles */
:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --secondary: #7c3aed;
    --accent: #f59e0b;
    --success: #10b981;
    --dark: #0f172a;
    --light: #f8fafc;
    --gray: #64748b;
    --border: #e2e8f0;
}

.jackpot-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Hero Section */
.jackpot-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    padding: 60px 0;
    position: relative;
    overflow: hidden;
}

.jackpot-hero::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><path d="M20 20 L80 20 L80 80 L20 80 Z" fill="none" stroke="white" stroke-width="2"/><circle cx="50" cy="50" r="20" fill="none" stroke="white" stroke-width="2"/></svg>') repeat;
    background-size: 50px 50px;
    animation: float 20s linear infinite;
}

@keyframes float {
    from { transform: translateY(0) rotate(0deg); }
    to { transform: translateY(-100px) rotate(10deg); }
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.hero-title {
    font-size: 48px;
    font-weight: 800;
    margin-bottom: 20px;
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-subtitle {
    font-size: 18px;
    line-height: 1.6;
    color: #cbd5e1;
    margin-bottom: 30px;
}

.stats-banner {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 40px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #fbbf24;
    display: block;
}

.stat-label {
    font-size: 14px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Main Content */
.jackpot-main {
    background: #f8fafc;
    padding: 40px 0;
}

.container-custom {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Category Cards */
.category-section {
    margin-bottom: 40px;
}

.category-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}

.category-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.category-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--dark);
    margin: 0;
}

.jackpot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.jackpot-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.jackpot-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.jackpot-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
}

.jackpot-card:hover::before {
    opacity: 1;
}

.jackpot-logo {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #eef2ff, #ffffff);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
    border: 1px solid var(--border);
}

.jackpot-logo span {
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.jackpot-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 10px;
    line-height: 1.4;
}

.jackpot-badges {
    display: flex;
    gap: 8px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.jackpot-badge {
    background: #eef2ff;
    color: var(--primary);
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 20px;
}

.jackpot-prize {
    font-size: 14px;
    color: var(--gray);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.jackpot-prize strong {
    color: var(--accent);
    font-size: 16px;
}

.jackpot-link {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 15px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    margin-top: auto;
}

.jackpot-link:hover {
    transform: translateX(4px);
    color: white;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

/* Featured Jackpots */
.featured-section {
    margin-bottom: 40px;
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
}

.featured-card {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border-radius: 16px;
    padding: 30px;
    color: white;
    position: relative;
    overflow: hidden;
}

.featured-card::after {
    content: '💰';
    position: absolute;
    bottom: -20px;
    right: -20px;
    font-size: 120px;
    opacity: 0.1;
    transform: rotate(-15deg);
}

.featured-label {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 15px;
}

.featured-title {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 10px;
}

.featured-prize {
    font-size: 32px;
    font-weight: 800;
    color: #fbbf24;
    margin-bottom: 20px;
}

.featured-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.featured-stat {
    text-align: center;
}

.featured-stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #fbbf24;
    display: block;
}

.featured-stat-label {
    font-size: 12px;
    color: #94a3b8;
}

.featured-link {
    background: rgba(255,255,255,0.1);
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.2);
}

.featured-link:hover {
    background: rgba(255,255,255,0.2);
    color: white;
    gap: 15px;
}

/* Filter/Search Bar */
.filter-bar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

.search-box {
    flex: 1;
    min-width: 250px;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 12px 20px 12px 45px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
}

.filter-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 8px 16px;
    border: 1px solid var(--border);
    border-radius: 20px;
    background: white;
    color: var(--gray);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 32px;
    }
    
    .stats-banner {
        flex-direction: column;
        gap: 20px;
    }
    
    .jackpot-grid {
        grid-template-columns: 1fr;
    }
    
    .featured-grid {
        grid-template-columns: 1fr;
    }
    
    .category-title {
        font-size: 20px;
    }
}

/* Loading Animation */
@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

.loading-card {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    height: 200px;
    border-radius: 12px;
}

/* SEO Content Styling */
.seo-content {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-top: 40px;
    border: 1px solid var(--border);
}

.seo-content h2 {
    color: var(--dark);
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 20px;
}

.seo-content p {
    color: var(--gray);
    line-height: 1.7;
    margin-bottom: 15px;
}
</style>

<main class="jackpot-page">
    <!-- Hero Section -->
    <section class="jackpot-hero">
        <div class="hero-content">
            <h1 class="hero-title">Jackpot Predictions</h1>
            <p class="hero-subtitle">
                Your ultimate destination for free, expert jackpot predictions across 30+ betting sites in Africa. 
                Boost your chances of winning big with our carefully analyzed tips.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="jackpot-main">
        <div class="container-custom">           

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="jackpotSearch" placeholder="Search for jackpots..." onkeyup="filterJackpots()">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" onclick="filterByRegion('all')">All</button>
                    <button class="filter-btn" onclick="filterByRegion('kenya')">🇰🇪 Kenya</button>
                    <button class="filter-btn" onclick="filterByRegion('nigeria')">🇳🇬 Nigeria</button>
                    <button class="filter-btn" onclick="filterByRegion('tanzania')">🇹🇿 Tanzania</button>
                    <button class="filter-btn" onclick="filterByRegion('uganda')">🇺🇬 Uganda</button>
                </div>
            </div>

            <!-- Jackpot Categories -->
            <?php foreach ($jackpotCategories as $region => $jackpots): ?>
            <div class="category-section" data-region="<?php echo strtolower(str_replace('🇰🇪 ', '', $region)); ?>">
                <div class="category-header">
                    <div class="category-icon"><?php echo substr($region, 0, 2); ?></div>
                    <h2 class="category-title"><?php echo $region; ?></h2>
                </div>
                
                <div class="jackpot-grid">
                    <?php foreach ($jackpots as $title => $url): 
                        // Extract jackpot type for badge
                        $badge = '';
                        if (strpos($title, 'Mega') !== false) $badge = 'Mega';
                        elseif (strpos($title, 'Midweek') !== false) $badge = 'Midweek';
                        elseif (strpos($title, 'Daily') !== false) $badge = 'Daily';
                        elseif (strpos($title, 'Grand') !== false) $badge = 'Grand';
                        
                        // Generate random prize for demo (replace with actual data later)
                        $prize = 'KES ' . rand(5, 150) . 'M+';
                    ?>
                    <div class="jackpot-card jackpot-item">
                        <div class="jackpot-badges">
                            <?php if ($badge): ?>
                            <span class="jackpot-badge"><?php echo $badge; ?></span>
                            <?php endif; ?>
                            <span class="jackpot-badge"><?php echo explode(' ', $title)[0]; ?></span>
                        </div>
                        <h3 class="jackpot-title"><?php echo $title; ?></h3>
                        <div class="jackpot-prize">
                            <i class="fas fa-trophy" style="color: #f59e0b;"></i>
                            <span>Est. Prize: <strong><?php echo $prize; ?></strong></span>
                        </div>
                        <a href="<?php echo $url; ?>" class="jackpot-link">
                            <span>Get Predictions</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- SEO Content -->
            <section class="seo-content">
                <?php echo $htmlContent; ?>
            </section>
        </div>
    </section>
</main>

<script>
// Filter functionality
function filterJackpots() {
    const searchInput = document.getElementById('jackpotSearch').value.toLowerCase();
    const jackpotItems = document.querySelectorAll('.jackpot-item');
    
    jackpotItems.forEach(item => {
        const title = item.querySelector('.jackpot-title').textContent.toLowerCase();
        if (title.includes(searchInput)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

function filterByRegion(region) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    const sections = document.querySelectorAll('.category-section');
    
    if (region === 'all') {
        sections.forEach(section => section.style.display = 'block');
    } else {
        sections.forEach(section => {
            const sectionRegion = section.getAttribute('data-region');
            if (sectionRegion && sectionRegion.includes(region)) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }
}
</script>

<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>