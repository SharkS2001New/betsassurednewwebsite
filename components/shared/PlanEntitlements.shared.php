<?php

/**
 * Pitch-compatible VIP entitlement helpers (plan ids 56–65).
 */

if (!function_exists('authParseActivePlanIds')) {
    /** @return list<int> */
    function authParseActivePlanIds(?array $user): array
    {
        if (!is_array($user)) {
            return [];
        }

        $ids = [];
        $raw = $user['active_plan_ids'] ?? null;
        if (is_array($raw)) {
            foreach ($raw as $id) {
                $ids[] = (int) $id;
            }
        } elseif (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                foreach ($decoded as $id) {
                    $ids[] = (int) $id;
                }
            }
        }

        if (!empty($user['active_plan_id'])) {
            $ids[] = (int) $user['active_plan_id'];
        }

        $ids = array_values(array_unique(array_filter($ids, static fn ($id) => $id > 0)));
        return $ids;
    }
}

if (!function_exists('authSubscriptionStillValid')) {
    function authSubscriptionStillValid(?array $user): bool
    {
        if (!is_array($user) || empty($user['subscription_end_date'])) {
            return false;
        }
        $end = strtotime((string) $user['subscription_end_date']);
        if ($end === false) {
            return false;
        }
        return $end >= strtotime('today');
    }
}

if (!function_exists('authUserHasPlan')) {
    function authUserHasPlan(?array $user, int $planId): bool
    {
        if (!authSubscriptionStillValid($user)) {
            return false;
        }

        $ids = authParseActivePlanIds($user);
        if (in_array($planId, $ids, true)) {
            return true;
        }

        $jackpotIds = [61, 62, 63, 64, 65];
        $weeklyMonthly = [58, 59];
        if (in_array($planId, $jackpotIds, true)) {
            foreach ($ids as $id) {
                if (in_array($id, $weeklyMonthly, true)) {
                    return true;
                }
            }
        }

        // Broad premium flag (after M-Pesa activation)
        $plan = strtolower((string) ($user['active_plan'] ?? 'free'));
        if ($plan === 'premium' && in_array($planId, $jackpotIds, true)) {
            return true;
        }
        if ($plan === 'premium' && in_array($planId, [56, 57, 58, 59], true)) {
            return true;
        }

        return false;
    }
}

if (!function_exists('authUserHasPremiumAccess')) {
    function authUserHasPremiumAccess(?array $user): bool
    {
        if (!is_array($user)) {
            return false;
        }
        if (!authSubscriptionStillValid($user)) {
            return false;
        }
        $plan = strtolower((string) ($user['active_plan'] ?? 'free'));
        if ($plan === 'premium') {
            return true;
        }
        return authParseActivePlanIds($user) !== [];
    }
}

if (!function_exists('authVipJackpotCatalog')) {
    /**
     * @return list<array{name: string, plan_id: int, amount: int, plan_type: string}>
     */
    function authVipJackpotCatalog(): array
    {
        return [
            ['name' => 'Sportpesa Mega Jackpot', 'plan_id' => 61, 'amount' => 110, 'plan_type' => 'jackpot'],
            ['name' => 'Sportpesa Midweek Jackpot', 'plan_id' => 62, 'amount' => 105, 'plan_type' => 'jackpot2'],
            ['name' => 'Betika Midweek Jackpot', 'plan_id' => 63, 'amount' => 105, 'plan_type' => 'jackpot3'],
            ['name' => 'Shabiki Midweek Jackpot', 'plan_id' => 65, 'amount' => 90, 'plan_type' => 'jackpot5'],
        ];
    }
}

if (!function_exists('authMultibetPlanCatalog')) {
    /**
     * @return list<array{label: string, blurb: string, plan_id: int, amount: int, plan_type: string}>
     */
    function authMultibetPlanCatalog(): array
    {
        return [
            ['label' => '1 Day · 3.5–5 Odds', 'blurb' => 'Daily VIP multibets from admin tickets', 'plan_id' => 56, 'amount' => 80, 'plan_type' => 'multibet'],
            ['label' => '1 Day · 10+ Odds', 'blurb' => 'Higher-odds day package', 'plan_id' => 57, 'amount' => 150, 'plan_type' => 'multibet2'],
            ['label' => '7 Days', 'blurb' => 'Full week of premium tips', 'plan_id' => 58, 'amount' => 500, 'plan_type' => 'multibet3'],
            ['label' => '1 Month', 'blurb' => 'Best value for regular punters', 'plan_id' => 59, 'amount' => 1500, 'plan_type' => 'multibet4'],
        ];
    }
}
