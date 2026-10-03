<?php

if (!function_exists('footerSponsorsJsonPath')) {
    function footerSponsorsJsonPath(): string
    {
        return BASE_PATH . '/public/site-content/footer-sponsors.json';
    }
}

if (!function_exists('parseSponsorDateOnly')) {
    function parseSponsorDateOnly($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return $raw;
        }
        $ts = strtotime($raw);
        return $ts ? gmdate('Y-m-d', $ts) : null;
    }
}

if (!function_exists('todayNairobiDateString')) {
    function todayNairobiDateString(): string
    {
        return (new DateTimeImmutable('now', new DateTimeZone('Africa/Nairobi')))->format('Y-m-d');
    }
}

if (!function_exists('normalizeSponsorRelTags')) {
    function normalizeSponsorRelTags($raw): array
    {
        $allowed = ['sponsored', 'nofollow', 'noopener', 'noreferrer'];
        $tags = [];
        if (is_string($raw)) {
            $tags = preg_split('/[\s,]+/', $raw) ?: [];
        } elseif (is_array($raw)) {
            $tags = $raw;
        }

        $out = [];
        foreach ($tags as $tag) {
            $t = strtolower(trim((string) $tag));
            if ($t !== '' && in_array($t, $allowed, true) && !in_array($t, $out, true)) {
                $out[] = $t;
            }
        }
        if (!in_array('noopener', $out, true)) {
            $out[] = 'noopener';
        }
        if (!in_array('noreferrer', $out, true)) {
            $out[] = 'noreferrer';
        }

        return $out;
    }
}

if (!function_exists('normalizeGraceDays')) {
    function normalizeGraceDays($raw): int
    {
        if ($raw === null || $raw === '') {
            return 4;
        }
        $days = (int) $raw;
        if ($days < 0) {
            return 4;
        }
        return min($days, 365);
    }
}

if (!function_exists('slugifySponsorId')) {
    function slugifySponsorId(string $label, string $url): string
    {
        $base = strtolower($label !== '' ? $label : $url);
        $base = preg_replace('#https?://#', '', $base) ?? $base;
        $base = preg_replace('/[^a-z0-9]+/', '-', $base) ?? $base;
        $base = trim($base, '-');
        $base = substr($base, 0, 48);

        return $base !== '' ? $base : 'link';
    }
}

if (!function_exists('normalizeSponsorLink')) {
    function normalizeSponsorLink($raw, int $index = 0): ?array
    {
        if (!is_array($raw)) {
            return null;
        }

        $label = trim((string) ($raw['label'] ?? ''));
        $url = trim((string) ($raw['url'] ?? ''));
        if ($label === '' || $url === '') {
            return null;
        }

        $id = trim((string) ($raw['id'] ?? ''));
        if ($id === '') {
            $id = slugifySponsorId($label, $url) . '-' . ($index + 1);
        }

        return [
            'id' => $id,
            'label' => $label,
            'url' => $url,
            'starts_at' => parseSponsorDateOnly($raw['starts_at'] ?? null),
            'expires_at' => parseSponsorDateOnly($raw['expires_at'] ?? null),
            'notes' => trim((string) ($raw['notes'] ?? '')),
            'active' => array_key_exists('active', $raw) ? ($raw['active'] !== false) : true,
            'rel' => normalizeSponsorRelTags($raw['rel'] ?? ($raw['rel_tags'] ?? null)),
            'grace_days' => normalizeGraceDays($raw['grace_days'] ?? null),
        ];
    }
}

if (!function_exists('normalizeSponsorDocument')) {
    function normalizeSponsorDocument($raw): array
    {
        $links = [];
        $incoming = is_array($raw['links'] ?? null) ? $raw['links'] : [];
        foreach ($incoming as $i => $link) {
            $normalized = normalizeSponsorLink($link, (int) $i);
            if ($normalized !== null) {
                $links[] = $normalized;
            }
        }

        return [
            'updated_at' => trim((string) ($raw['updated_at'] ?? '')) ?: gmdate('c'),
            'links' => $links,
        ];
    }
}

if (!function_exists('isSponsorVisible')) {
    function isSponsorVisible(array $link): bool
    {
        if (($link['active'] ?? true) === false) {
            return false;
        }

        $today = todayNairobiDateString();
        $startsAt = $link['starts_at'] ?? null;
        $expiresAt = $link['expires_at'] ?? null;

        if (is_string($startsAt) && $startsAt !== '' && strcmp($today, $startsAt) < 0) {
            return false;
        }

        if (is_string($expiresAt) && $expiresAt !== '' && strcmp($today, $expiresAt) > 0) {
            $graceDays = normalizeGraceDays($link['grace_days'] ?? 4);
            $graceEnd = (new DateTimeImmutable($expiresAt, new DateTimeZone('UTC')))
                ->modify('+' . $graceDays . ' days')
                ->format('Y-m-d');
            if (strcmp($today, $graceEnd) > 0) {
                return false;
            }
        }

        return trim((string) ($link['label'] ?? '')) !== ''
            && trim((string) ($link['url'] ?? '')) !== '';
    }
}

if (!function_exists('readSponsorDocument')) {
    function readSponsorDocument(): array
    {
        $path = footerSponsorsJsonPath();
        if (!is_readable($path)) {
            return ['updated_at' => gmdate('c'), 'links' => []];
        }

        $decoded = json_decode((string) file_get_contents($path), true);
        return normalizeSponsorDocument(is_array($decoded) ? $decoded : []);
    }
}

if (!function_exists('writeSponsorDocument')) {
    function writeSponsorDocument(array $incoming): array
    {
        $document = normalizeSponsorDocument($incoming);
        $document['updated_at'] = gmdate('c');

        $path = footerSponsorsJsonPath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $json = json_encode($document, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false || @file_put_contents($path, $json . "\n") === false) {
            throw new RuntimeException('Failed to write footer-sponsors.json');
        }

        return $document;
    }
}

if (!function_exists('publicVisibleSponsorLinks')) {
    function publicVisibleSponsorLinks(?array $document = null): array
    {
        $document = $document ?? readSponsorDocument();
        $out = [];
        foreach ($document['links'] as $link) {
            if (!isSponsorVisible($link)) {
                continue;
            }
            $out[] = [
                'id' => $link['id'],
                'label' => $link['label'],
                'url' => $link['url'],
                'rel' => $link['rel'],
            ];
        }

        return $out;
    }
}
