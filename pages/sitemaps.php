<?php
/**
 * Human-readable HTML sitemap — button-grid directory (crawler XML remains /sitemap.xml).
 */
include_once BASE_PATH . '/components/shared/jackpotRoutes.php';

if (!function_exists('baSitemapLabelFromSlug')) {
    function baSitemapLabelFromSlug(string $slug): string
    {
        $slug = trim($slug, '/');
        $label = str_replace(['-', '_'], ' ', $slug);
        $label = preg_replace('/\s+/', ' ', $label) ?? $label;
        return ucwords($label);
    }
}

if (!function_exists('baSitemapDedupe')) {
    /**
     * @param  list<array{label:string,href:string}>  $links
     * @return list<array{label:string,href:string}>
     */
    function baSitemapDedupe(array $links): array
    {
        $seen = [];
        $out = [];
        foreach ($links as $link) {
            $href = strtolower(rtrim((string) ($link['href'] ?? ''), '/'));
            if ($href === '' || isset($seen[$href])) {
                continue;
            }
            $seen[$href] = true;
            $out[] = $link;
        }
        return $out;
    }
}

$jackpotLinks = [
    ['label' => 'Jackpot Predictions Hub', 'href' => '/jackpot-predictions'],
];
foreach (getJackpotRoutes() as $route) {
    $jackpotLinks[] = [
        'label' => baSitemapLabelFromSlug((string) $route),
        'href' => '/' . ltrim((string) $route, '/'),
    ];
}
$jackpotLinks = baSitemapDedupe($jackpotLinks);

$sections = [
    [
        'id' => 'predictions',
        'title' => 'Predictions',
        'links' => [
            ['label' => 'Home', 'href' => '/'],
            ['label' => "Today's Predictions", 'href' => '/todays-predictions'],
            ['label' => "Tomorrow's Predictions", 'href' => '/tomorrows-predictions'],
            ['label' => "Yesterday's Predictions", 'href' => '/yesterdays-predictions'],
            ['label' => 'All Predictions', 'href' => '/all-predictions'],
            ['label' => 'Accumulator Tips', 'href' => '/free-football-betting-tips'],
            ['label' => 'Must Win Teams Today', 'href' => '/must-win-teams-today'],
            ['label' => 'Sure Win Prediction Today', 'href' => '/sure-win-prediction-today'],
            ['label' => 'Direct Win Predictions', 'href' => '/direct-win-predictions'],
        ],
    ],
    [
        'id' => 'markets',
        'title' => 'Markets',
        'links' => [
            ['label' => 'Both Teams To Score', 'href' => '/both-teams-to-score'],
            ['label' => 'Home Win Tips', 'href' => '/home-win-tips'],
            ['label' => 'Away Win Tips', 'href' => '/away-win-tips'],
            ['label' => 'Over/Under 1.5 Goals', 'href' => '/over-under-15-goals'],
            ['label' => 'Over/Under 2.5 Goals', 'href' => '/over-under-25-goals'],
            ['label' => 'Safe Draws', 'href' => '/safe-draws'],
            ['label' => 'Double Chance', 'href' => '/double-chance'],
        ],
    ],
    [
        'id' => 'tipsters',
        'title' => 'Tipster Pages',
        'links' => [
            ['label' => 'BetNumbers Tips', 'href' => '/betnumbers-tips'],
            ['label' => 'Cheerplex Tips', 'href' => '/cheerplex-tips'],
            ['label' => 'Mwanasoka Tips', 'href' => '/mwanasoka-tips'],
            ['label' => 'SokaFans Tips', 'href' => '/sokafans-tips'],
            ['label' => 'Sunpel Tips', 'href' => '/sunpel-tips'],
            ['label' => 'Vitibet Predictions', 'href' => '/vitibet-tips'],
            ['label' => 'Adibet Predictions', 'href' => '/adibet-tips'],
            ['label' => 'SoccerVista Predictions', 'href' => '/soccervista-tips'],
        ],
    ],
    [
        'id' => 'jackpots',
        'title' => 'Jackpots',
        'links' => $jackpotLinks,
    ],
    [
        'id' => 'site',
        'title' => 'Site',
        'links' => [
            ['label' => 'Blog', 'href' => '/blog'],
            ['label' => 'About Us', 'href' => '/about-us'],
            ['label' => 'Contact Us', 'href' => '/contact-us'],
            ['label' => 'Partners', 'href' => '/partners'],
            ['label' => 'Privacy Policy', 'href' => '/our-privacy-policy'],
            ['label' => 'Terms and Conditions', 'href' => '/our-terms-and-conditions'],
            ['label' => 'Login', 'href' => '/login'],
            ['label' => 'Create Account', 'href' => '/register'],
            ['label' => 'XML Sitemap', 'href' => '/sitemap.xml'],
        ],
    ],
];

$seenGlobal = [];
foreach ($sections as $si => $section) {
    $clean = [];
    foreach ($section['links'] as $link) {
        $href = strtolower(rtrim((string) ($link['href'] ?? ''), '/'));
        if ($href === '' || isset($seenGlobal[$href])) {
            continue;
        }
        $seenGlobal[$href] = true;
        $clean[] = $link;
    }
    $sections[$si]['links'] = $clean;
}

$linkCount = 0;
foreach ($sections as $section) {
    $linkCount += count($section['links']);
}

$metaTags = <<<HTML
<title>Sitemaps — All BetAssured Pages</title>
<meta name="title" content="Sitemaps — All BetAssured Pages">
<meta name="description" content="Browse every BetAssured page: daily predictions, markets, tipster boards, jackpot tips, blog and site links. XML sitemap for crawlers included.">
<meta name="robots" content="index, follow">
<meta name="keywords" content="betsassured sitemap, football predictions links, jackpot predictions index">
<link rel="canonical" href="https://www.betsassured.com/sitemaps">
<meta property="og:type" content="website">
<meta property="og:title" content="Sitemaps — All BetAssured Pages">
<meta property="og:description" content="Browse every BetAssured page: predictions, markets, jackpots, tipster boards and site links.">
<meta property="og:url" content="https://www.betsassured.com/sitemaps">
<meta property="og:site_name" content="BetAssured">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Sitemaps — All BetAssured Pages">
<meta name="twitter:description" content="Browse every BetAssured page: predictions, markets, jackpots, tipster boards and site links.">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<style>
.ba-sitemaps {
    padding: 28px 0 48px;
}
.ba-sitemaps-hero {
    margin-bottom: 1.25rem;
}
.ba-sitemaps-hero h1 {
    margin: 0 0 0.45rem;
    font-size: clamp(1.55rem, 2.5vw, 1.9rem);
    font-weight: 760;
    letter-spacing: -0.03em;
    color: var(--text-1);
}
.ba-sitemaps-hero p {
    margin: 0;
    max-width: 56ch;
    color: var(--text-2);
    line-height: 1.55;
    font-size: 0.98rem;
}
.ba-sitemaps-meta {
    margin: 0 0 1.4rem;
    font-size: 0.88rem;
    font-weight: 650;
    color: var(--text-3);
}
.ba-sitemaps-crumbs {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem 0.5rem;
    list-style: none;
    margin: 0 0 1rem;
    padding: 0;
    font-size: 0.88rem;
    color: var(--text-3);
}
.ba-sitemaps-crumbs a {
    color: var(--navy-light);
    text-decoration: none;
    font-weight: 650;
}
.ba-sitemaps-crumbs a:hover {
    text-decoration: underline;
}
.ba-sitemaps-panel {
    margin-bottom: 1.6rem;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 1.15rem 1.2rem 1.25rem;
}
.ba-sitemaps-panel h2 {
    margin: 0 0 0.85rem;
    font-size: 1.1rem;
    font-weight: 740;
    color: var(--text-1);
}
.ba-sitemaps-grid {
    list-style: none;
    margin: 0;
    padding: 0;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.65rem 0.85rem;
}
.ba-sitemaps-grid a {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 2.75rem;
    padding: 0.65rem 0.85rem;
    text-align: center;
    font-size: 0.9rem;
    font-weight: 650;
    line-height: 1.3;
    color: var(--text-1);
    text-decoration: none;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    transition: border-color 0.15s ease, color 0.15s ease, background 0.15s ease;
}
.ba-sitemaps-grid a:hover {
    color: var(--navy);
    background: #fff;
    border-color: rgba(30, 58, 95, 0.35);
}
@media (max-width: 640px) {
    .ba-sitemaps-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<main class="container ba-sitemaps">
    <nav aria-label="Breadcrumb">
        <ol class="ba-sitemaps-crumbs">
            <li><a href="/">Home</a></li>
            <li aria-hidden="true">/</li>
            <li><span aria-current="page">Sitemaps</span></li>
        </ol>
    </nav>

    <header class="ba-sitemaps-hero">
        <h1>BetAssured Links</h1>
        <p>Browse predictions, markets, tipster boards and jackpot pages. Crawlers can also use the XML sitemap under Site below.</p>
    </header>

    <p class="ba-sitemaps-meta"><?php echo (int) $linkCount; ?> links</p>

    <?php foreach ($sections as $section): ?>
        <section class="ba-sitemaps-panel" aria-labelledby="sitemaps-<?php echo htmlspecialchars($section['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <h2 id="sitemaps-<?php echo htmlspecialchars($section['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8'); ?>
            </h2>
            <ul class="ba-sitemaps-grid">
                <?php foreach ($section['links'] as $link): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endforeach; ?>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
