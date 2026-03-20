<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Jackpot Predictions Today | Free Tips for 30+ Betting Sites</title>
<meta name="title" content="Jackpot Predictions Today | Free Tips for 30+ Betting Sites">
<meta name="description" content="Free jackpot predictions for Sportpesa Mega Jackpot, Betika, Betway, Mozzart, Bet9ja and 25+ betting sites across Kenya, Nigeria, Tanzania, Uganda and more.">
<meta name="keywords" content="jackpot predictions today, free jackpot tips, sportpesa mega jackpot predictions, betika jackpot predictions, mozzart jackpot tips, bet9ja jackpot predictions, kenya jackpot predictions, africa jackpot tips, football jackpot analysis">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Jackpot Predictions Today | Free Tips for 30+ Betting Sites">
<meta property="og:description" content="Free jackpot predictions for Sportpesa Mega Jackpot, Betika, Betway, Mozzart, Bet9ja and 25+ betting sites across Kenya, Nigeria, Tanzania, Uganda and more.">
<meta property="og:url" content="https://www.betsassured.com/jackpot-predictions">
<meta property="og:site_name" content="Betsassured">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Jackpot Predictions Today | Free Tips for 30+ Betting Sites">
<meta name="twitter:description" content="Free jackpot predictions for Sportpesa Mega Jackpot, Betika, Betway, Mozzart, Bet9ja and 25+ betting sites across Kenya, Nigeria, Tanzania, Uganda and more.">
HTML;

include_once BASE_PATH . "/components/includes/header.inc.php";
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "name": "Betsassured",
      "url": "https://www.betsassured.com"
    },
    {
      "@type": "WebPage",
      "name": "Jackpot Predictions Today | Free Tips for 30+ Betting Sites",
      "url": "https://www.betsassured.com/jackpot-predictions",
      "description": "Free jackpot predictions for Sportpesa Mega Jackpot, Betika, Betway, Mozzart, Bet9ja and 25+ betting sites across Kenya, Nigeria, Tanzania, Uganda and more.",
      "inLanguage": "en",
      "isPartOf": {
        "@type": "WebSite",
        "name": "Betsassured",
        "url": "https://www.betsassured.com"
      }
    },
    {
      "@type": "CollectionPage",
      "name": "Jackpot Predictions",
      "url": "https://www.betsassured.com/jackpot-predictions",
      "description": "Daily jackpot predictions and tips for Sportpesa, Betika, Betway, Mozzart, Bet9ja, and other top betting sites in Africa."
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://www.betsassured.com"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Jackpot Predictions",
          "item": "https://www.betsassured.com/jackpot-predictions"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "What are jackpot predictions?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Jackpot predictions provide expert tips for winning multi-match jackpots across top betting sites in Africa, including Sportpesa, Betika, Betway, Mozzart, and Bet9ja."
          }
        },
        {
          "@type": "Question",
          "name": "Are Betsassured jackpot tips free?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, all jackpot predictions and tips on Betsassured are free and updated daily for multiple betting sites."
          }
        }
      ]
    }
  ]
}
</script>
<?php
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";

$Parsedown = new Parsedown();
$markdownContent = file_get_contents(BASE_PATH.'/components/seo-content/jackpot-predictions.content.md');
$htmlContent = $Parsedown->text($markdownContent);

// Jackpot hub data — region key is plain lowercase for JS filter matching
$jackpotCategories = [
    ["region" => "kenya",    "label" => "🇰🇪 Kenya", "jackpots" => [
        ["title" => "Sportpesa Mega Jackpot",         "url" => "/sportpesa-mega-jackpot-predictions",          "type" => "Mega"],
        ["title" => "Sportpesa Midweek Jackpot",      "url" => "/sportpesa-midweek-jackpot-predictions",       "type" => "Midweek"],
        ["title" => "Betika Midweek Jackpot",         "url" => "/betika-midweek-jackpot-predictions",          "type" => "Midweek"],
        ["title" => "Betika Mega Jackpot",            "url" => "/betika-grand-jackpot-predictions",            "type" => "Mega"],
        ["title" => "Mozzart Super Daily Jackpot",    "url" => "/mozzart-daily-jackpot-predictions",            "type" => "Daily"],
        ["title" => "Mozzart Super Grand Jackpot",    "url" => "/mozzart-bet-grand-jackpot-predictions",     "type" => "Grand"],
        ["title" => "Shabiki Midweek Jackpot",        "url" => "/shabiki-jackpot-predictions",                 "type" => "Midweek"],
        ["title" => "Odibet Laki Tatu Daily Jackpot", "url" => "/odibet-laki-tatu-daily-jackpot-predictions",  "type" => "Daily"],
        ["title" => "MerryBet Jackpot",               "url" => "/merrybet-jackpot-predictions",                "type" => ""],
        ["title" => "BetKing Jackpot",                "url" => "/betking-jackpot-predictions",                 "type" => ""],
        ["title" => "Betlion Daily JP Jackpot",       "url" => "/betlion-daily-jp-jackpot-predictions",        "type" => "Daily"],
        ["title" => "Betlion Goliath Jackpot",        "url" => "/betlion-goliath-jackpot-predictions",         "type" => ""],
        ["title" => "Betway Jackpot Kenya",           "url" => "/betway-jackpot-predictions-kenya",            "type" => ""],
    ]],
    ["region" => "nigeria",  "label" => "🇳🇬 Nigeria", "jackpots" => [
        ["title" => "Bet9ja Supa9ja Jackpot",         "url" => "/bet9ja-supa9ja-jackpot-predictions",          "type" => ""],
        ["title" => "1XBet Toto 15 Jackpot",          "url" => "/1xbet-toto-15-jackpot-predictions",           "type" => ""],
        ["title" => "22 Bet Toto Jackpot",            "url" => "/22-bet-toto-jackpot-predictions",             "type" => ""],
        ["title" => "Sportybet Jackpot",              "url" => "/sportybet-jackpot-predictions",               "type" => ""],
        ["title" => "Betpawa Pick13 Nigeria",         "url" => "/betpawa-pick13-jackpot-predictions-nigeria",  "type" => ""],
    ]],
    ["region" => "tanzania", "label" => "🇹🇿 Tanzania", "jackpots" => [
        ["title" => "Sportpesa Supa Jackpot 17 TZ",   "url" => "/sportpesa-supa-jackpot-17-predictions-tz",    "type" => ""],
        ["title" => "Sportpesa Supa Jackpot 13 TZ",   "url" => "/sportpesa-supa-jackpot-13-predictions-tz",    "type" => ""],
        ["title" => "Betika Kitonga Jackpot TZ",      "url" => "/betika-kitonga-tanzania-predictions",                   "type" => ""],
        ["title" => "Betpawa Pick13 Tanzania",        "url" => "/betpawa-pick13-jackpot-predictions-tanzania", "type" => ""],
        ["title" => "Betway Jackpot Tanzania",        "url" => "/betway-jackpot-predictions-tanzania",         "type" => ""],
    ]],
    ["region" => "uganda",   "label" => "🇺🇬 Uganda", "jackpots" => [
        ["title" => "Betpawa Pick13 Uganda",          "url" => "/betpawa-pick13-jackpot-predictions-uganda",   "type" => ""],
        ["title" => "Betway Jackpot Uganda",          "url" => "/betway-jackpot-predictions-uganda",           "type" => ""],
    ]],
    ["region" => "other",    "label" => "🌍 Other Regions", "jackpots" => [
        ["title" => "Betpawa Pick13 Zambia",          "url" => "/betpawa-pick13-jackpot-predictions-zambia",   "type" => ""],
        ["title" => "Betpawa Pick13 Ghana",           "url" => "/betpawa-pick13-jackpot-predictions-ghana",    "type" => ""],
        ["title" => "Betpawa Pick13 Cameroon",        "url" => "/betpawa-pick13-jackpot-predictions-cameroon", "type" => ""],
        ["title" => "Betpawa Pick13 DR Congo",        "url" => "/betpawa-pick13-jackpot-predictions-dr-congo", "type" => ""],
    ]],
];

$totalJackpots  = array_sum(array_map(fn($c) => count($c['jackpots']), $jackpotCategories));
$totalCountries = count($jackpotCategories);
?>
<style>
:root {
    --primary: #2563eb;
    --secondary: #7c3aed;
    --accent: #f59e0b;
    --dark: #0f172a;
    --gray: #64748b;
    --border: #e2e8f0;
}
.jackpot-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    padding: 20px 20px;
    position: relative;
    overflow: hidden;
}
.jackpot-hero::before {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><path d="M20 20 L80 20 L80 80 L20 80 Z" fill="none" stroke="white" stroke-width="2"/><circle cx="50" cy="50" r="20" fill="none" stroke="white" stroke-width="2"/></svg>') repeat;
    background-size: 50px 50px;
    animation: float 20s linear infinite;
}
@keyframes float {
    from { transform: translateY(0) rotate(0deg); }
    to   { transform: translateY(-100px) rotate(10deg); }
}
.hero-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}
.hero-h1 {
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
    margin-top: 20px;
    flex-wrap: wrap;
}
.stat-item { text-align: center; }
.stat-number { font-size: 32px; font-weight: 700; color: #fbbf24; display: block; }
.stat-label  { font-size: 14px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
.jackpot-main { background: #f8fafc; padding: 40px 0; }
.container-custom { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
.category-section { margin-bottom: 40px; }
.category-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
.category-icon {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.category-title { font-size: 24px; font-weight: 700; color: var(--dark); margin: 0; }
.jackpot-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}
.jackpot-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
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
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    opacity: 0;
    transition: opacity 0.3s ease;
}
.jackpot-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0,0,0,.1); }
.jackpot-card:hover::before { opacity: 1; }
.jackpot-badges { display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap; }
.jackpot-badge { background: #eef2ff; color: var(--primary); font-size: 12px; font-weight: 600; padding: 4px 8px; border-radius: 20px; }
.jackpot-title { font-size: 17px; font-weight: 600; color: var(--dark); margin-bottom: 15px; line-height: 1.4; }
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
.jackpot-link:hover { transform: translateX(4px); color: white; box-shadow: 0 4px 12px rgba(37,99,235,.3); }
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
    box-shadow: 0 4px 6px -1px rgba(0,0,0,.1);
}
.search-box { flex: 1; min-width: 250px; position: relative; }
.search-box input {
    width: 100%;
    padding: 12px 20px 12px 45px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}
.search-box input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--gray); }
.filter-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
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
.filter-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
.seo-content {
    background: white;
    border-radius: 12px;
    padding: 30px;
    margin-top: 40px;
    border: 1px solid var(--border);
}
.seo-content h2 { color: var(--dark); font-size: 24px; font-weight: 700; margin-bottom: 15px; }
.seo-content h3 { color: var(--dark); font-size: 20px; font-weight: 600; margin: 20px 0 10px; }
.seo-content p  { color: var(--gray); line-height: 1.7; margin-bottom: 15px; }
@media (max-width: 768px) {
    .hero-h1 { font-size: 30px; }
    .stats-banner { gap: 20px; }
    .jackpot-grid { grid-template-columns: 1fr; }
    .category-title { font-size: 20px; }
}
</style>

<main>
    <!-- Hero — H1 is here, styled to match the original visual -->
    <section class="jackpot-hero">
        <div class="hero-content">
            <h1 class="hero-h1">Jackpot Predictions Today</h1>
            <p class="hero-subtitle">
                Free expert jackpot predictions for <?php echo $totalJackpots; ?>+ jackpots across <?php echo $totalCountries; ?> countries.
                Covering Sportpesa, Betika, Betway, Mozzart, Bet9ja and more — updated daily.
            </p>
            <div class="stats-banner">
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalJackpots; ?>+</span>
                    <span class="stat-label">Jackpots Covered</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalCountries; ?></span>
                    <span class="stat-label">Countries</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">Free</span>
                    <span class="stat-label">No Signup Needed</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="jackpot-main">
        <div class="container-custom">

            <!-- Filter Bar -->
            <div class="filter-bar">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="jackpotSearch" placeholder="Search jackpots..." onkeyup="filterJackpots()">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" onclick="filterByRegion('all', this)">All</button>
                    <button class="filter-btn" onclick="filterByRegion('kenya', this)">🇰🇪 Kenya</button>
                    <button class="filter-btn" onclick="filterByRegion('nigeria', this)">🇳🇬 Nigeria</button>
                    <button class="filter-btn" onclick="filterByRegion('tanzania', this)">🇹🇿 Tanzania</button>
                    <button class="filter-btn" onclick="filterByRegion('uganda', this)">🇺🇬 Uganda</button>
                    <button class="filter-btn" onclick="filterByRegion('other', this)">🌍 Other</button>
                </div>
            </div>

            <!-- Jackpot Category Sections -->
            <?php foreach ($jackpotCategories as $category): ?>
            <div class="category-section" data-region="<?php echo htmlspecialchars($category['region']); ?>">
                <div class="category-header">
                    <div class="category-icon"><?php echo mb_substr($category['label'], 0, 2); ?></div>
                    <h2 class="category-title"><?php echo htmlspecialchars($category['label']); ?></h2>
                </div>

                <div class="jackpot-grid">
                    <?php foreach ($category['jackpots'] as $jackpot): ?>
                    <div class="jackpot-card jackpot-item">
                        <?php if (!empty($jackpot['type'])): ?>
                        <div class="jackpot-badges">
                            <span class="jackpot-badge"><?php echo htmlspecialchars($jackpot['type']); ?></span>
                        </div>
                        <?php endif; ?>
                        <h3 class="jackpot-title"><?php echo htmlspecialchars($jackpot['title']); ?></h3>
                        <a href="<?php echo htmlspecialchars($jackpot['url']); ?>" class="jackpot-link">
                            <span>Get Predictions</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- SEO Article -->
            <section class="seo-content">
                <?php echo $htmlContent; ?>
            </section>

        </div>
    </section>
</main>

<script>
function filterJackpots() {
    const q = document.getElementById('jackpotSearch').value.toLowerCase().trim();
    document.querySelectorAll('.jackpot-item').forEach(item => {
        const title = item.querySelector('.jackpot-title').textContent.toLowerCase();
        item.style.display = title.includes(q) ? 'flex' : 'none';
    });
    // Hide sections that have no visible cards
    document.querySelectorAll('.category-section').forEach(section => {
        const hasVisible = [...section.querySelectorAll('.jackpot-item')].some(i => i.style.display !== 'none');
        section.style.display = hasVisible ? 'block' : 'none';
    });
}

function filterByRegion(region, btn) {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    // Reset search input and restore all card visibility
    document.getElementById('jackpotSearch').value = '';
    document.querySelectorAll('.jackpot-item').forEach(i => i.style.display = 'flex');
    document.querySelectorAll('.category-section').forEach(section => {
        section.style.display = (region === 'all' || section.getAttribute('data-region') === region) ? 'block' : 'none';
    });
}
</script>

<?php
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>
