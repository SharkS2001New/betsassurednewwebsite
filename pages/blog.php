<?php
$page = max(1, (int) ($_GET['page'] ?? 1));
$category = trim((string) ($_GET['category'] ?? 'ALL'));
if ($category === '') {
    $category = 'ALL';
}

include_once BASE_PATH . '/components/shared/BlogApi.shared.php';

$payload = fetchBlogListPage($page, $category, 6);
$blogs = is_array($payload['data'] ?? null) ? $payload['data'] : [];
$currentPage = (int) ($payload['current_page'] ?? $page);
$lastPage = max(1, (int) ($payload['last_page'] ?? 1));
$total = (int) ($payload['total'] ?? count($blogs));
$error = $payload === null;

$metaTags = <<<HTML
<title>Football Betting Blog | Tips, Guides & Match Analysis | BetAssured</title>
<meta name="title" content="Football Betting Blog | Tips, Guides & Match Analysis | BetAssured">
<meta name="description" content="Read BetAssured football betting blog posts: match analysis, betting guides, prediction insights, and expert tips for smarter football betting.">
<meta name="keywords" content="betassured blog, football betting blog, betting tips articles, soccer prediction guides, match analysis">
<meta property="og:title" content="Football Betting Blog | BetAssured">
<meta property="og:description" content="Expert football betting articles, prediction insights, and match analysis from BetAssured.">
<meta property="og:url" content="https://www.betsassured.com/blog">
<meta name="twitter:title" content="Football Betting Blog | BetAssured">
<meta name="twitter:description" content="Expert football betting articles, prediction insights, and match analysis from BetAssured.">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/blog.css?v=1">

<main class="container blogs-page">
    <h1 class="page-hero-title">BetAssured Blog</h1>

    <?php include_once BASE_PATH . '/components/includes/scrollable-nav.inc.php'; ?>

    <div class="section-title-bar">
        <h2>Latest Articles</h2>
        <span class="today-date-tag"><?php echo (int) $total; ?> posts</span>
    </div>

    <?php if ($error): ?>
        <div class="blog-empty-state">
            <p>Unable to load blog posts right now. Please try again shortly.</p>
        </div>
    <?php elseif (empty($blogs)): ?>
        <div class="blog-empty-state">
            <p>No blog posts published yet. Check back soon.</p>
        </div>
    <?php else: ?>
        <div class="blog-list-grid">
            <?php foreach ($blogs as $blog):
                $slug = htmlspecialchars((string) ($blog['slug'] ?? ''), ENT_QUOTES, 'UTF-8');
                $title = htmlspecialchars((string) ($blog['title'] ?? 'Untitled'), ENT_QUOTES, 'UTF-8');
                $excerpt = htmlspecialchars((string) ($blog['excerpt'] ?? ''), ENT_QUOTES, 'UTF-8');
                $categoryName = (string) ($blog['category']['name'] ?? 'Articles');
                $categoryLabel = htmlspecialchars(ucfirst(strtolower($categoryName)), ENT_QUOTES, 'UTF-8');
                $published = $blog['published_at'] ?? ($blog['created_at'] ?? null);
                $dateLabel = $published ? date('M j, Y', strtotime((string) $published)) : '';
                $readTime = (int) ($blog['read_time'] ?? 5);
            ?>
            <article class="blog-card">
                <div class="blog-content">
                    <small class="blog-category"><?php echo $categoryLabel; ?></small>
                    <a href="/blog/<?php echo $slug; ?>" class="blog-title"><?php echo $title; ?></a>
                    <div class="blog-meta">
                        Admin<?php if ($dateLabel): ?> / <?php echo htmlspecialchars($dateLabel, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?>
                    </div>
                    <?php if ($excerpt !== ''): ?>
                        <p class="blog-excerpt"><?php echo $excerpt; ?></p>
                    <?php endif; ?>
                </div>
                <div class="blog-footer">
                    <a href="/blog/<?php echo $slug; ?>" class="read-more-btn">Read More <i class="bi bi-arrow-right"></i></a>
                    <div class="blog-social">
                        <span><i class="bi bi-clock"></i> <?php echo $readTime; ?> Minutes</span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if ($lastPage > 1): ?>
            <div class="pagination-container">
                <?php if ($currentPage > 1): ?>
                    <a class="page-btn" href="/blog?page=<?php echo $currentPage - 1; ?>&category=<?php echo urlencode($category); ?>">← Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $lastPage; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <span class="page-btn active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a class="page-btn" href="/blog?page=<?php echo $i; ?>&category=<?php echo urlencode($category); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $lastPage): ?>
                    <a class="page-btn" href="/blog?page=<?php echo $currentPage + 1; ?>&category=<?php echo urlencode($category); ?>">Next →</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
