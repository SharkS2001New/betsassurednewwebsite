<?php

if (!function_exists('blogSiteKey')) {
    function blogSiteKey(): string
    {
        return 'bets';
    }
}

if (!function_exists('blogApiBaseUrl')) {
    function blogApiBaseUrl(): string
    {
        return 'https://api.pitchpredictions.com/api';
    }
}

if (!function_exists('blogApiHttpHeaders')) {
    /**
     * @return array<int, string>
     */
    function blogApiHttpHeaders(): array
    {
        $headers = pitchApiHttpHeaders();
        $headers[] = 'X-Site-Key: ' . blogSiteKey();
        $headers[] = 'Accept: application/json';

        return $headers;
    }
}

if (!function_exists('blogCacheClearKey')) {
    function blogCacheClearKey(): ?string
    {
        foreach (['BLOG_CACHE_CLEAR_KEY', 'CACHE_CLEAR_KEY'] as $envKey) {
            $secret = trim((string) (getenv($envKey) ?: ($_ENV[$envKey] ?? '')));
            if (strlen($secret) === 24) {
                return $secret;
            }
        }

        return null;
    }
}

if (!function_exists('isBlogCacheClearAuthorized')) {
    function isBlogCacheClearAuthorized(): bool
    {
        $secret = blogCacheClearKey();
        if ($secret === null) {
            return false;
        }

        $auth = trim((string) ($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? ''));
        if (preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
            $auth = trim($m[1]);
        }

        $provided = trim((string) (
            $auth
            ?: ($_SERVER['HTTP_X_FOOTER_CLEAR_KEY'] ?? '')
            ?: ($_SERVER['HTTP_X_BLOG_CACHE_CLEAR_KEY'] ?? '')
            ?: ($_GET['key'] ?? '')
        ));

        return $provided !== '' && hash_equals($secret, $provided);
    }
}

if (!function_exists('blogCacheDir')) {
    function blogCacheDir(): string
    {
        $dir = BASE_PATH . '/storage/blog-cache';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        return $dir;
    }
}

if (!function_exists('blogCachePath')) {
    function blogCachePath(string $key): string
    {
        return blogCacheDir() . '/' . preg_replace('/[^a-zA-Z0-9._-]+/', '_', $key) . '.json';
    }
}

if (!function_exists('readBlogCache')) {
    function readBlogCache(string $key, int $ttlSeconds = 900): ?array
    {
        $path = blogCachePath($key);
        if (!is_readable($path)) {
            return null;
        }

        $mtime = @filemtime($path);
        if ($mtime === false || (time() - $mtime) > $ttlSeconds) {
            return null;
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        return is_array($decoded) ? $decoded : null;
    }
}

if (!function_exists('writeBlogCache')) {
    function writeBlogCache(string $key, array $payload): void
    {
        @file_put_contents(blogCachePath($key), json_encode($payload));
    }
}

if (!function_exists('clearBlogListCaches')) {
    function clearBlogListCaches(): array
    {
        $dir = blogCacheDir();
        $removed = 0;
        foreach (glob($dir . '/list_*.json') ?: [] as $file) {
            if (@unlink($file)) {
                $removed++;
            }
        }
        foreach (glob($dir . '/featured_*.json') ?: [] as $file) {
            if (@unlink($file)) {
                $removed++;
            }
        }

        return ['cleared' => $removed];
    }
}

if (!function_exists('clearBlogPostCache')) {
    function clearBlogPostCache(string $slug): array
    {
        $slug = trim($slug);
        $removed = 0;
        $path = blogCachePath('post_' . $slug);
        if (is_file($path) && @unlink($path)) {
            $removed++;
        }

        // Also drop list caches so new/updated titles show quickly.
        $list = clearBlogListCaches();
        $removed += (int) ($list['cleared'] ?? 0);

        return ['cleared' => $removed, 'slug' => $slug];
    }
}

if (!function_exists('pitchBlogRequest')) {
    function pitchBlogRequest(string $path, array $query = []): ?array
    {
        $url = rtrim(blogApiBaseUrl(), '/') . '/' . ltrim($path, '/');
        if ($query !== []) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, blogApiHttpHeaders());
        curl_setopt($ch, CURLOPT_TIMEOUT, 12);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno = curl_errno($ch);

        if ($errno || $httpCode < 200 || $httpCode >= 300 || !is_string($response)) {
            return null;
        }

        $decoded = json_decode($response, true);
        return is_array($decoded) ? $decoded : null;
    }
}

if (!function_exists('fetchBlogListPage')) {
    function fetchBlogListPage(int $page = 1, string $category = 'ALL', int $perPage = 6): ?array
    {
        $page = max(1, $page);
        $category = $category !== '' ? $category : 'ALL';
        $cacheKey = "list_{$category}_p{$page}_pp{$perPage}";

        $cached = readBlogCache($cacheKey, 900);
        if ($cached !== null) {
            return $cached;
        }

        $payload = pitchBlogRequest('blog', [
            'site' => blogSiteKey(),
            'page' => $page,
            'category' => $category,
            'per_page' => $perPage,
        ]);

        if ($payload !== null) {
            writeBlogCache($cacheKey, $payload);
        }

        return $payload;
    }
}

if (!function_exists('fetchBlogPostBySlug')) {
    function fetchBlogPostBySlug(string $slug): ?array
    {
        $slug = trim($slug);
        if ($slug === '') {
            return null;
        }

        $cacheKey = 'post_' . $slug;
        $cached = readBlogCache($cacheKey, 21600);
        if ($cached !== null) {
            return $cached;
        }

        $payload = pitchBlogRequest('blog/' . rawurlencode($slug), [
            'site' => blogSiteKey(),
        ]);

        if ($payload !== null && !empty($payload['slug'])) {
            writeBlogCache($cacheKey, $payload);
        }

        return $payload;
    }
}
