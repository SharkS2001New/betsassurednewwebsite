<?php
$slug = trim((string) ($slug ?? ($_GET['slug'] ?? '')));
if ($slug === '') {
    http_response_code(404);
    include BASE_PATH . '/pages/404.php';
    return;
}

include_once BASE_PATH . '/components/shared/BlogApi.shared.php';

$blog = fetchBlogPostBySlug($slug);
if (!$blog || empty($blog['slug'])) {
    http_response_code(404);
    include BASE_PATH . '/pages/404.php';
    return;
}

$title = (string) ($blog['meta_title'] ?? $blog['title'] ?? 'Blog');
$description = (string) ($blog['meta_description'] ?? $blog['excerpt'] ?? 'BetAssured football betting blog article.');
$keywords = (string) ($blog['meta_keywords'] ?? 'betassured blog, football betting tips');
$canonical = 'https://www.betsassured.com/blog/' . rawurlencode((string) $blog['slug']);
$categoryLabel = ucfirst(strtolower((string) ($blog['category']['name'] ?? 'Articles')));
$published = $blog['published_at'] ?? ($blog['created_at'] ?? null);
$dateLabel = $published ? date('F j, Y', strtotime((string) $published)) : '';
$readTime = (int) ($blog['read_time'] ?? 5);
$contentHtml = (string) ($blog['content'] ?? '');
$excerpt = (string) ($blog['excerpt'] ?? '');

$safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$safeDesc = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
$safeKeywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');
$safeCanonical = htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8');

$metaTags = <<<HTML
<title>{$safeTitle} | BetAssured</title>
<meta name="title" content="{$safeTitle} | BetAssured">
<meta name="description" content="{$safeDesc}">
<meta name="keywords" content="{$safeKeywords}">
<meta property="og:type" content="article">
<meta property="og:title" content="{$safeTitle}">
<meta property="og:description" content="{$safeDesc}">
<meta property="og:url" content="{$safeCanonical}">
<meta name="twitter:title" content="{$safeTitle}">
<meta name="twitter:description" content="{$safeDesc}">
HTML;

include_once BASE_PATH . '/components/includes/header.inc.php';
include_once BASE_PATH . '/components/shared/preloader.shared.php';
include_once BASE_PATH . '/components/includes/navbar.inc.php';
?>
<link rel="stylesheet" href="/css/blog.css?v=6">

<main class="container blog-post-page">
    <article class="blog-article-card">
        <div class="blog-article-inner">
            <div class="blog-back-wrap">
                <a href="/blog" class="blog-back-link">
                    <span class="blog-back-icon" aria-hidden="true"><i class="bi bi-arrow-left"></i></span>
                    <span class="blog-back-text">Back to Blog</span>
                </a>
            </div>

            <p class="blog-category"><?php echo htmlspecialchars($categoryLabel, ENT_QUOTES, 'UTF-8'); ?></p>
            <h1 class="blog-article-title"><?php echo htmlspecialchars((string) ($blog['title'] ?? $title), ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="blog-meta">
                Admin
                <?php if ($dateLabel): ?> / <?php echo htmlspecialchars($dateLabel, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?>
                / <i class="bi bi-clock"></i> <?php echo $readTime; ?> min read
            </div>

            <?php if ($excerpt !== ''): ?>
                <p class="blog-lead"><?php echo htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>

            <div class="blog-article-body">
                <?php
                // Content is authored in admin (trusted CMS HTML).
                echo $contentHtml !== '' ? $contentHtml : '<p>This article has no content yet.</p>';
                ?>
            </div>
        </div>
    </article>
</main>

<?php include_once BASE_PATH . '/components/includes/footer.inc.php'; ?>
