<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>About BetAssured - Football Predictions You Can Trust</title>
<meta name="title" content="About BetAssured - Football Predictions You Can Trust">
<meta name="description" content="Learn about BetAssured, your trusted source for football predictions. We provide data-driven insights to help you make smarter betting decisions.">
<meta name="keywords" content="about us, football predictions, betassured, betting tips">

<!-- Open Graph -->
<meta property="og:title" content="About BetAssured - Football Predictions You Can Trust">
<meta property="og:description" content="Learn about BetAssured, your trusted source for football predictions.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.betassured.com/about-us">

<!-- Twitter -->
<meta property="twitter:title" content="About BetAssured - Football Predictions You Can Trust">
<meta property="twitter:description" content="Learn about BetAssured, your trusted source for football predictions.">
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
.about-page {
    padding: 2rem 0;
}

/* Simple Header */
.about-header {
    margin-bottom: 3rem;
}

.about-header h1 {
    font-size: 2rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
}

.about-header h1 span {
    color: var(--primary);
    font-weight: 500;
}

.about-header p {
    font-size: 1rem;
    color: var(--gray-600);
    max-width: 600px;
}

/* Content Sections */
.content-section {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-sm);
}

.content-section:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--gray-300);
    transition: all 0.2s ease;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--primary);
    font-size: 1.1rem;
    opacity: 0.8;
}

.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-200);
    margin-left: 1rem;
}

.section-text {
    color: var(--gray-600);
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.section-text:last-child {
    margin-bottom: 0;
}

.section-text strong {
    color: var(--primary);
    font-weight: 600;
}

/* Stats Row */
.stats-row {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    margin: 2rem 0;
    padding: 0.5rem 0;
}

.stat-item {
    flex: 1;
    min-width: 120px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

/* Simple Grid */
.simple-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin: 1.5rem 0;
}

.grid-item {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 1.25rem;
}

.grid-item h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}

.grid-item p {
    font-size: 0.9rem;
    color: var(--gray-600);
    line-height: 1.5;
    margin: 0;
}

/* Services List */
.services-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.service-tag {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.service-tag i {
    color: var(--primary);
    font-size: 0.9rem;
    width: 16px;
}

.service-tag span {
    font-size: 0.9rem;
    color: var(--gray-700);
}

.service-tag:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    transition: all 0.2s ease;
}

/* Quote */
.quote {
    background: var(--primary-light);
    border-left: 3px solid var(--primary);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    margin: 2rem 0;
}

.quote p {
    font-size: 0.95rem;
    color: var(--gray-700);
    font-style: italic;
    margin-bottom: 0.5rem;
}

.quote-author {
    font-size: 0.85rem;
    color: var(--primary);
    font-weight: 500;
}

/* CTA */
.cta-box {
    text-align: center;
    margin-top: 2rem;
    padding: 2rem;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
}

.cta-text {
    font-size: 1rem;
    color: var(--gray-600);
    margin-bottom: 1.5rem;
}

.cta-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.5rem;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.cta-link:hover {
    background: var(--gray-800);
    transform: translateY(-1px);
}

.thank-you {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: var(--gray-600);
}

.thank-you i {
    color: #e74c3c;
    font-size: 0.8rem;
    margin: 0 0.2rem;
}

.thank-you strong {
    color: var(--primary);
    font-weight: 600;
}

/* Divider */
.divider {
    height: 1px;
    background: var(--gray-200);
    margin: 2rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .about-header h1 {
        font-size: 1.75rem;
    }
    
    .stats-row {
        gap: 1rem;
    }
    
    .stat-number {
        font-size: 1.25rem;
    }
    
    .content-section {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .about-header h1 {
        font-size: 1.5rem;
    }
    
    .stats-row {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .simple-grid {
        grid-template-columns: 1fr;
    }
    
    .services-list {
        grid-template-columns: 1fr;
    }
}
</style>

<main class="about-page">
    <div class="container">
        <!-- Simple Header -->
        <div class="about-header">
            <h1>About <span>BetAssured</span></h1>
            <p>Football predictions, backed by data and delivered with clarity.</p>
        </div>

        <!-- Quick Stats -->
        <div class="stats-row">
            <div class="stat-item">
                <div class="stat-number">2019</div>
                <div class="stat-label">Founded</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">15k+</div>
                <div class="stat-label">Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">60+</div>
                <div class="stat-label">Leagues</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">82%</div>
                <div class="stat-label">Accuracy</div>
            </div>
        </div>

        <!-- Our Story -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-book-open"></i> Our Story
            </div>
            <p class="section-text">
                BetAssured started in 2019 with a straightforward idea: football betting should be based on analysis, not guesswork. What began as a small group of analysts sharing insights has grown into a platform trusted by thousands of users across Africa.
            </p>
            <p class="section-text">
                We focus on providing clear, actionable predictions without the hype. No guaranteed wins, no empty promises — just honest analysis you can rely on.
            </p>
        </div>

        <!-- What We Do -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-chart-line"></i> What We Do
            </div>
            <p class="section-text">
                We analyze football matches using a combination of statistical data and contextual understanding. Each prediction considers team form, head-to-head records, player availability, and other relevant factors.
            </p>
            
            <div class="simple-grid">
                <div class="grid-item">
                    <h4>1X2 Predictions</h4>
                    <p>Home, draw, and away win probabilities with confidence ratings</p>
                </div>
                <div class="grid-item">
                    <h4>Double Chance</h4>
                    <p>Cover two outcomes with 1X, X2, and 12 predictions</p>
                </div>
                <div class="grid-item">
                    <h4>Over/Under Goals</h4>
                    <p>Total goals predictions for 1.5, 2.5, and 3.5 markets</p>
                </div>
                <div class="grid-item">
                    <h4>BTTS Tips</h4>
                    <p>Both Teams to Score predictions with probability analysis</p>
                </div>
            </div>
        </div>

        <!-- Our Approach -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-database"></i> Our Approach
            </div>
            <p class="section-text">
                We look at over 50 data points for each match — from recent form and scoring patterns to defensive records and situational factors. This helps us identify patterns and opportunities that might otherwise go unnoticed.
            </p>
            <p class="section-text">
                <strong>Transparency matters to us.</strong> We share our reasoning and track our results openly, so you can see exactly what goes into each prediction.
            </p>
        </div>

        <!-- What We Value -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-heart"></i> What We Value
            </div>
            
            <div style="margin-bottom: 1rem;">
                <p class="section-text" style="margin-bottom: 0.25rem;"><strong>Data, not guesswork</strong></p>
                <p class="section-text" style="margin-bottom: 1rem;">Every prediction starts with numbers, trends, and analysis.</p>
                
                <p class="section-text" style="margin-bottom: 0.25rem;"><strong>Honest communication</strong></p>
                <p class="section-text" style="margin-bottom: 1rem;">We don't promise wins — we provide information to help you decide.</p>
                
                <p class="section-text" style="margin-bottom: 0.25rem;"><strong>Continuous improvement</strong></p>
                <p class="section-text">We regularly review our methods and adjust based on what works.</p>
            </div>
        </div>

        <!-- Quote -->
        <div class="quote">
            <p>"We're not here to tell you what to bet on. We're here to give you the information you need to make your own informed choices."</p>
            <div class="quote-author">— The BetAssured Team</div>
        </div>

        <!-- Services Overview -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-list"></i> Predictions We Offer
            </div>
            
            <div class="services-list">
                <div class="service-tag">
                    <i class="fas fa-futbol"></i>
                    <span>1X2 Tips</span>
                </div>
                <div class="service-tag">
                    <i class="fas fa-handshake"></i>
                    <span>Double Chance</span>
                </div>
                <div class="service-tag">
                    <i class="fas fa-arrow-up"></i>
                    <span>Over/Under Goals</span>
                </div>
                <div class="service-tag">
                    <i class="fas fa-futbol"></i>
                    <span>BTTS</span>
                </div>
                <div class="service-tag">
                    <i class="fas fa-layer-group"></i>
                    <span>Accumulators</span>
                </div>
                <div class="service-tag">
                    <i class="fas fa-trophy"></i>
                    <span>Jackpot Analysis</span>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- CTA -->
        <div class="cta-box">
            <div class="cta-text">
                Have questions or feedback? We'd like to hear from you.
            </div>
            <a href="/contact-us" class="cta-link">
                <i class="fas fa-envelope"></i> Contact Us
            </a>
            <div class="thank-you">
                <i class="fas fa-heart"></i> Thanks for choosing BetAssured <i class="fas fa-heart"></i>
            </div>
        </div>
    </div>
</main>

<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>