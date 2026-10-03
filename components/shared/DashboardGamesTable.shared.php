<?php

/**
 * Render a dashboard tips table, optionally with a payment lock overlay.
 *
 * @param  list<array<string, mixed>>  $games
 */
if (!function_exists('dashboardRenderGamesPanel')) {
    function dashboardRenderGamesPanel(
        string $id,
        string $title,
        string $subtitle,
        array $games,
        bool $locked,
        string $viewAllHref = '',
        string $viewAllLabel = 'View all',
        string $unlockHref = '/contact-us',
        string $tipClass = ''
    ): void {
        ?>
        <section class="dash-panel<?php echo $locked ? ' is-locked' : ''; ?>" id="<?php echo htmlspecialchars($id, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="dash-panel-head">
                <div>
                    <h2 class="dash-panel-title"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p class="dash-panel-sub"><?php echo htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php if ($viewAllHref !== ''): ?>
                    <a class="dash-link" href="<?php echo htmlspecialchars($viewAllHref, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($viewAllLabel, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($games === []): ?>
                <div class="dash-empty">
                    <p>No games published for this section yet. Check back shortly.</p>
                    <?php if ($locked): ?>
                        <div class="dash-hero-actions" style="margin-top:14px;">
                            <a class="dash-btn" href="<?php echo htmlspecialchars($unlockHref, ENT_QUOTES, 'UTF-8'); ?>">Unlock Premium</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="dash-lock-wrap">
                    <div class="dash-games-table<?php echo $locked ? ' is-blurred' : ''; ?>" role="table" aria-label="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="dash-games-row dash-games-head" role="row">
                            <span>Kick-off</span>
                            <span>Match</span>
                            <span>League</span>
                            <span>Tip</span>
                            <span>Odds</span>
                            <span>Score</span>
                        </div>
                        <?php foreach ($games as $game): ?>
                            <div class="dash-games-row" role="row">
                                <span class="dash-kick"><?php echo htmlspecialchars((string) ($game['kickoff'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="dash-match">
                                    <strong><?php echo htmlspecialchars((string) ($game['home'] ?? 'Home'), ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <span class="dash-vs">vs</span>
                                    <strong><?php echo htmlspecialchars((string) ($game['away'] ?? 'Away'), ENT_QUOTES, 'UTF-8'); ?></strong>
                                </span>
                                <span class="dash-league"><?php echo htmlspecialchars((string) ($game['league'] ?? 'League'), ENT_QUOTES, 'UTF-8'); ?></span>
                                <span>
                                    <span class="dash-tip <?php echo htmlspecialchars($tipClass, ENT_QUOTES, 'UTF-8'); ?>">
                                        <?php echo htmlspecialchars((string) ($game['pick'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </span>
                                <span class="dash-odd"><?php echo htmlspecialchars((string) ($game['odd'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?></span>
                                <span class="dash-score"><?php echo htmlspecialchars((string) ($game['score'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($locked): ?>
                        <div class="dash-lock-overlay">
                            <div class="dash-lock-card">
                                <p class="dash-lock-kicker">Premium tips</p>
                                <h3 class="dash-lock-title">Unlock to see these tips</h3>
                                <p class="dash-lock-copy">Matches are visible. Tips and odds unlock after you subscribe to Premium.</p>
                                <div class="dash-hero-actions">
                                    <a class="dash-btn" href="<?php echo htmlspecialchars($unlockHref, ENT_QUOTES, 'UTF-8'); ?>">Get Premium</a>
                                    <?php if ($viewAllHref !== ''): ?>
                                        <a class="dash-btn dash-btn-ghost" href="<?php echo htmlspecialchars($viewAllHref, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($viewAllLabel, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
