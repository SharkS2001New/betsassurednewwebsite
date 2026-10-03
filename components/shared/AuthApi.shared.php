<?php

if (!function_exists('authSiteKey')) {
    function authSiteKey(): string
    {
        return 'bets';
    }
}

if (!function_exists('authApiBaseUrl')) {
    function authApiBaseUrl(): string
    {
        return 'https://api.pitchpredictions.com/api';
    }
}

if (!function_exists('authBootstrapSession')) {
    function authBootstrapSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}

if (!function_exists('authCurrentUser')) {
    function authCurrentUser(): ?array
    {
        authBootstrapSession();
        $user = $_SESSION['auth_user'] ?? null;
        return is_array($user) ? $user : null;
    }
}

if (!function_exists('authCurrentToken')) {
    function authCurrentToken(): ?string
    {
        authBootstrapSession();
        $token = trim((string) ($_SESSION['auth_token'] ?? ''));
        return $token !== '' ? $token : null;
    }
}

if (!function_exists('authIsLoggedIn')) {
    function authIsLoggedIn(): bool
    {
        return authCurrentToken() !== null && authCurrentUser() !== null;
    }
}

if (!function_exists('authStoreSession')) {
    function authStoreSession(string $token, array $user): void
    {
        authBootstrapSession();
        $_SESSION['auth_token'] = $token;
        $_SESSION['auth_user'] = $user;
        $_SESSION['user_id'] = $user['id'] ?? null;
        $_SESSION['user_name'] = $user['full_name'] ?? '';
        $_SESSION['user_email'] = $user['email'] ?? '';
    }
}

if (!function_exists('authClearSession')) {
    function authClearSession(): void
    {
        authBootstrapSession();
        unset(
            $_SESSION['auth_token'],
            $_SESSION['auth_user'],
            $_SESSION['user_id'],
            $_SESSION['user_name'],
            $_SESSION['user_email']
        );
    }
}

if (!function_exists('authRequireLogin')) {
    function authRequireLogin(string $redirectTo = '/login'): void
    {
        if (!authIsLoggedIn()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }
}

if (!function_exists('authRedirectIfLoggedIn')) {
    function authRedirectIfLoggedIn(string $redirectTo = '/dashboard'): void
    {
        if (authIsLoggedIn()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }
}

if (!function_exists('authApiHttpHeaders')) {
    /**
     * PHP server calls need Origin + partner token for EnsureApiAllowedOrigin.
     * User Sanctum token (when present) goes in Authorization for auth:sanctum routes.
     *
     * @return array<int, string>
     */
    function authApiHttpHeaders(?string $userToken = null): array
    {
        return [
            'Content-Type: application/json',
            'Accept: application/json',
            'Origin: ' . pitchApiOrigin(),
            'X-Site-Key: ' . authSiteKey(),
            'Partner-Authorization: ' . pitchPartnerAccessToken(),
            'Authorization: Bearer ' . ($userToken ?: pitchApiAccessToken()),
            'User-Agent: BetsassuredPHP/1.0',
        ];
    }
}

if (!function_exists('authApiRequest')) {
    /**
     * @param  array<string, mixed>|null  $body
     * @return array{ok: bool, status: int, data: array|null, raw: string}
     */
    function authApiRequest(string $method, string $path, ?array $body = null, ?string $userToken = null): array
    {
        $url = rtrim(authApiBaseUrl(), '/') . '/' . ltrim($path, '/');
        $headers = authApiHttpHeaders($userToken);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $raw = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno = curl_errno($ch);

        if ($errno || !is_string($raw)) {
            return ['ok' => false, 'status' => $status ?: 0, 'data' => null, 'raw' => ''];
        }

        $data = json_decode($raw, true);
        return [
            'ok' => $status >= 200 && $status < 300,
            'status' => $status,
            'data' => is_array($data) ? $data : null,
            'raw' => $raw,
        ];
    }
}

if (!function_exists('authApiErrorMessage')) {
    function authApiErrorMessage(?array $data, string $fallback = 'Something went wrong. Please try again.'): string
    {
        if (!is_array($data)) {
            return $fallback;
        }

        if (!empty($data['message']) && is_string($data['message'])) {
            return $data['message'];
        }

        if (!empty($data['error']) && is_string($data['error'])) {
            return $data['error'];
        }

        if (!empty($data['error']) && is_array($data['error'])) {
            $first = reset($data['error']);
            if (is_array($first)) {
                return (string) reset($first);
            }
            if (is_string($first)) {
                return $first;
            }
        }

        if (!empty($data['errors']) && is_array($data['errors'])) {
            $first = reset($data['errors']);
            if (is_array($first)) {
                return (string) reset($first);
            }
        }

        return $fallback;
    }
}

if (!function_exists('authCountries')) {
    /** @return list<string> */
    function authCountries(): array
    {
        return [
            'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda',
            'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas',
            'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize',
            'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana',
            'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cabo Verde',
            'Cambodia', 'Cameroon', 'Canada', 'Central African Republic', 'Chad',
            'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica',
            'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti',
            'Dominica', 'Dominican Republic', 'Ecuador', 'Egypt', 'El Salvador',
            'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia',
            'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany',
            'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau',
            'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia',
            'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan',
            'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan',
            'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein',
            'Lithuania', 'Luxembourg', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives',
            'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico',
            'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco',
            'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands',
            'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia',
            'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea',
            'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania',
            'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines',
            'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal',
            'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia',
            'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan',
            'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria',
            'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Timor-Leste', 'Togo',
            'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan',
            'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom',
            'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City',
            'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe',
        ];
    }
}
