<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Partners - BetAssured | Our Trusted Partners & Affiliates</title>
<meta name="title" content="Partners - BetAssured">
<meta name="description" content="Discover our trusted partners and affiliates in the football prediction and betting tips space. Explore who we work with.">
<meta name="keywords" content="partners, affiliates, football predictions, betting tips, trusted partners">

<!-- Open Graph -->
<meta property="og:title" content="Partners - BetAssured">
<meta property="og:description" content="Discover our trusted partners and affiliates in the football prediction space.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.betassured.com/partners">

<!-- Twitter -->
<meta property="twitter:title" content="Partners - BetAssured">
<meta property="twitter:description" content="Discover our trusted partners and affiliates in the football prediction space.">
HTML;

// Preloader & Header
include_once BASE_PATH . "/components/includes/header.inc.php";
include_once BASE_PATH . "/components/shared/preloader.shared.php";
include_once BASE_PATH . "/components/includes/navbar.inc.php";
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>

.partners-page {
    padding: 2rem 0;
}

/* Header */
.page-header {
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
    letter-spacing: -0.01em;
}

.page-header p {
    font-size: 0.95rem;
    color: var(--gray-600);
}

/* Partners Grid */
.partners-section {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.partners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 0.75rem;
    margin: 1.5rem 0;
}

.partner-item {
    list-style: none;
    margin: 0;
    padding: 0;
}

.partner-link {
    display: block;
    padding: 0.6rem 1rem;
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    color: var(--gray-700);
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.partner-link:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    color: var(--primary);
}

/* Info Box */
.info-box {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin: 2rem 0;
}

.info-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-title i {
    color: var(--primary);
    font-size: 0.9rem;
}

.info-content {
    font-size: 0.95rem;
    color: var(--gray-600);
    line-height: 1.6;
}

.info-content p {
    margin-bottom: 0.75rem;
}

.info-content ul {
    margin: 0.75rem 0;
    padding-left: 1.25rem;
}

.info-content li {
    margin-bottom: 0.5rem;
}

.info-content strong {
    color: var(--gray-800);
    font-weight: 600;
}

.info-content a {
    color: var(--primary);
    text-decoration: none;
}

.info-content a:hover {
    text-decoration: underline;
}

/* Contact Link */
.contact-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
    margin-top: 0.5rem;
}

.contact-link:hover {
    color: var(--gray-800);
}

.contact-link i {
    font-size: 0.8rem;
}

/* Footer */
.page-footer {
    text-align: left;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-200);
}

.footer-text {
    font-size: 0.9rem;
    color: var(--gray-600);
}

.footer-text a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

.footer-text a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .partners-section {
        padding: 1.5rem;
    }
    
    .partners-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    }
}

@media (max-width: 480px) {
    .partners-page {
        padding: 1rem 0;
    }
    
    .partners-section {
        padding: 1rem;
    }
    
    .partners-grid {
        grid-template-columns: 1fr;
    }
    
    .partner-link {
        white-space: normal;
    }
}
</style>

<main class="partners-page">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>Our Partners</h1>
            <p>We value partnerships with platforms that enhance user experience and provide quality insights.</p>
        </div>

        <!-- Partners List -->
        <div class="partners-section">
            <div class="partners-grid">
                <div class="partner-item"><a href="https://www.freewinningtips.com/" target="_blank" class="partner-link">Free Winning Tips</a></div>
                <div class="partner-item"><a href="https://www.amazingstakes.com/" target="_blank" class="partner-link">Amazing Stakes Predictions</a></div>
                <div class="partner-item"><a href="https://www.sokapredictions.com" target="_blank" class="partner-link">Soka Predictions</a></div>
                <div class="partner-item"><a href="https://www.pitchpredictions.com/" target="_blank" class="partner-link">Pitch Predictions</a></div>
                <div class="partner-item"><a href="https://wintipster.com/" target="_blank" class="partner-link">WinTipster</a></div>
                <div class="partner-item"><a href="https://www.betarazi.com" target="_blank" class="partner-link">Betarazi - Sure tips</a></div>
                <div class="partner-item"><a href="https://surepredictz.com" target="_blank" class="partner-link">Sure Predictz</a></div>
                <div class="partner-item"><a href="https://www.betgaranteed.com" target="_blank" class="partner-link">Bet Garanteed</a></div>
                <div class="partner-item"><a href="https://bankeroftheday.com" target="_blank" class="partner-link">Banker of the Day</a></div>
                <div class="partner-item"><a href="https://betandsured.com" target="_blank" class="partner-link">BetandSured</a></div>
                <div class="partner-item"><a href="https://climopredict.com" target="_blank" class="partner-link">Climopredict</a></div>
                <div class="partner-item"><a href="https://www.ggprediction.com" target="_blank" class="partner-link">GG Prediction</a></div>
                <div class="partner-item"><a href="https://www.supatips.com" target="_blank" class="partner-link">Supa Tips</a></div>
                <div class="partner-item"><a href="https://www.everydaywinningtips.com.ng" target="_blank" class="partner-link">Everyday Winning Tips</a></div>
                <div class="partner-item"><a href="https://www.betwinningtips.com" target="_blank" class="partner-link">Bet Winning Tips</a></div>
                <div class="partner-item"><a href="https://surestraightwinfortoday.com.ng" target="_blank" class="partner-link">Sure Straight Win Today</a></div>
                <div class="partner-item"><a href="https://100percentsurewins.com" target="_blank" class="partner-link">100 Percent Sure Wins</a></div>
                <div class="partner-item"><a href="https://bettingvoice.com" target="_blank" class="partner-link">Betting Voice</a></div>
                <div class="partner-item"><a href="https://bet360prediction.com" target="_blank" class="partner-link">Bet360 Prediction</a></div>
                <div class="partner-item"><a href="https://betpredictiontoday.com" target="_blank" class="partner-link">Bet Prediction Today</a></div>
                <div class="partner-item"><a href="https://www.bettingtips.co.ke" target="_blank" class="partner-link">Betting Tips Kenya</a></div>
                <div class="partner-item"><a href="http://kcpredict.com" target="_blank" class="partner-link">KC Predict</a></div>
                <div class="partner-item"><a href="https://acepredict.com" target="_blank" class="partner-link">Ace Predict</a></div>
                <div class="partner-item"><a href="https://www.feedinco.com" target="_blank" class="partner-link">Feedinco</a></div>
                <div class="partner-item"><a href="https://www.geekinco.com" target="_blank" class="partner-link">Geekinco</a></div>
                <div class="partner-item"><a href="https://todayspredict.com" target="_blank" class="partner-link">Today's Predict</a></div>
                <div class="partner-item"><a href="https://tips100.com" target="_blank" class="partner-link">Tips 100</a></div>
                <div class="partner-item"><a href="https://socapredict.com" target="_blank" class="partner-link">Soca Predict</a></div>
                <div class="partner-item"><a href="https://www.mwanasoka.co.ke" target="_blank" class="partner-link">Mwanasoka</a></div>
                <div class="partner-item"><a href="https://www.statarea.co.ke" target="_blank" class="partner-link">Statarea Kenya</a></div>
                <div class="partner-item"><a href="https://www.alljackpotpredictions.com/" target="_blank" class="partner-link">Accumulator Tips Today</a></div>
                <div class="partner-item"><a href="https://www.baopredictions.com/" target="_blank" class="partner-link">Must Win Tips Today</a></div>
            </div>

            <p style="font-size: 0.95rem; color: var(--gray-600); margin-top: 1.5rem;">
                We are always open to collaborating with like-minded platforms. 
                <a href="/contact-us" style="color: var(--primary); text-decoration: none;">Contact us</a> if you're interested in a partnership!
            </p>
        </div>

        <!-- Partner Information -->
        <div class="info-box">
            <div class="info-title">
                <i class="fas fa-info-circle"></i>
                <span>Become a Partner</span>
            </div>
            <div class="info-content">
                <p>If you would like to be featured as a partner, we recommend adding us to your site first using the information below, then send us an email.</p>
                
                <ul>
                    <li><strong>Name:</strong> BetAssured</li>
                    <li><strong>Description:</strong> Accurate Football Predictions & Betting Tips</li>
                    <li><strong>URL:</strong> <a href="https://www.betassured.com/" target="_blank">https://www.betassured.com/</a></li>
                </ul>
                
                <p>After adding us, send an email to: <a href="mailto:info@betassured.com">info@betassured.com</a></p>
                
                <a href="mailto:info@betassured.com" class="contact-link">
                    <span>Send us an email</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="page-footer">
            <p class="footer-text">
                Thank you for your interest in <a href="https://www.betassured.com/">BetAssured Platform</a>.
            </p>
        </div>
    </div>
</main>

<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>