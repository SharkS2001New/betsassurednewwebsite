<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Contact Us - BetAssured | Get in Touch</title>
<meta name="title" content="Contact Us - BetAssured">
<meta name="description" content="Get in touch with BetAssured for assistance, inquiries, or feedback. We're here to help with your football predictions needs.">
<meta name="keywords" content="contact us, customer support, football predictions help, betassured contact">

<!-- Open Graph -->
<meta property="og:title" content="Contact Us - BetAssured">
<meta property="og:description" content="Get in touch with BetAssured for assistance, inquiries, or feedback.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.betassured.com/contact">

<!-- Twitter -->
<meta property="twitter:title" content="Contact Us - BetAssured">
<meta property="twitter:description" content="Get in touch with BetAssured for assistance, inquiries, or feedback.">
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
.contact-page {
    padding: 2rem 0;
}

/* Simple Header */
.contact-header {
    margin-bottom: 2.5rem;
}

.contact-header h1 {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.25rem;
    letter-spacing: -0.01em;
}

.contact-header p {
    font-size: 0.95rem;
    color: var(--gray-600);
}

/* Contact Grid */
.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.contact-card {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    transition: all 0.2s ease;
}

.contact-card:hover {
    border-color: var(--gray-300);
    box-shadow: var(--shadow-sm);
}

.card-row {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.card-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-light);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.1rem;
    flex-shrink: 0;
}

.card-content {
    flex: 1;
    min-width: 0;
}

.card-label {
    font-size: 0.8rem;
    color: var(--gray-600);
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.02em;
}

.card-value {
    font-size: 1rem;
    font-weight: 500;
    color: var(--gray-800);
    word-break: break-word;
    margin-bottom: 0.5rem;
}

.card-value a {
    color: var(--gray-800);
    text-decoration: none;
}

.card-value a:hover {
    color: var(--primary);
}

.card-action {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
    color: var(--gray-600);
    text-decoration: none;
    padding: 0.25rem 0;
}

.card-action i {
    font-size: 0.75rem;
    color: var(--primary);
}

.card-action:hover {
    color: var(--primary);
}

/* WhatsApp card special */
.whatsapp-card {
    background: #f0f9f0;
    border-color: #d0e6d0;
}

.whatsapp-card .card-icon {
    background: #25D366;
    color: white;
}

.whatsapp-card .card-value a {
    color: #1e7e4a;
}

.whatsapp-card .card-action:hover {
    color: #1e7e4a;
}

/* Message Section */
.message-section {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 2rem;
    margin-bottom: 2rem;
}

.message-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.message-title i {
    color: var(--primary);
    font-size: 1rem;
}

.message-title h2 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
}

.message-text {
    font-size: 0.95rem;
    color: var(--gray-600);
    line-height: 1.6;
    margin-bottom: 1rem;
    max-width: 600px;
}

.response-time {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    background: var(--gray-100);
    border: 1px solid var(--gray-200);
    border-radius: 30px;
    font-size: 0.85rem;
    color: var(--gray-700);
}

.response-time i {
    color: var(--primary);
    font-size: 0.8rem;
}

/* Footer Section */
.footer-section {
    text-align: center;
    padding: 1.5rem;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
}

.footer-section p {
    font-size: 0.9rem;
    color: var(--gray-600);
    margin-bottom: 0.75rem;
}

.home-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    background: var(--gray-100);
    border: 1px solid var(--gray-200);
    border-radius: 30px;
    color: var(--gray-700);
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.home-link:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

.home-link i {
    font-size: 0.8rem;
}

/* Divider */
.divider {
    height: 1px;
    background: var(--gray-200);
    margin: 2rem 0;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-header h1 {
        font-size: 1.5rem;
    }
    
    .contact-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .contact-page {
        padding: 1rem 0;
    }
    
    .message-section {
        padding: 1.5rem;
    }
}
</style>

<main class="contact-page">
    <div class="container">
        <!-- Simple Header -->
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>We're here to help. Reach out anytime.</p>
        </div>

        <!-- Contact Cards - Clean grid -->
        <div class="contact-grid">
            <!-- Email -->
            <div class="contact-card">
                <div class="card-row">
                    <div class="card-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Email</div>
                        <div class="card-value">
                            <a href="mailto:support@pitchpredictions.com">support@pitchpredictions.com</a>
                        </div>
                        <a href="mailto:support@pitchpredictions.com" class="card-action">
                            <span>Send message</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Phone -->
            <div class="contact-card">
                <div class="card-row">
                    <div class="card-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="card-content">
                        <div class="card-label">Phone</div>
                        <div class="card-value">
                            <a href="tel:0799566287">0799566287</a>
                        </div>
                        <a href="tel:0799566287" class="card-action">
                            <span>Call now</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- WhatsApp -->
            <div class="contact-card whatsapp-card">
                <div class="card-row">
                    <div class="card-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="card-content">
                        <div class="card-label">WhatsApp</div>
                        <div class="card-value">
                            <a href="https://wa.me/254111509962" target="_blank">+254 111 509 962</a>
                        </div>
                        <a href="https://wa.me/254111509962" target="_blank" class="card-action">
                            <span>Chat with us</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Section -->
        <div class="message-section">
            <div class="message-title">
                <i class="fas fa-comment"></i>
                <h2>We value your feedback</h2>
            </div>
            <p class="message-text">
                Your questions and suggestions help us improve. Feel free to reach out with any questions, concerns, or ideas.
            </p>
            <div class="response-time">
                <i class="fas fa-clock"></i>
                <span>Typically responds within 24 hours</span>
            </div>
        </div>

        <!-- Simple Footer -->
        <div class="footer-section">
            <p>Thanks for choosing BetAssured</p>
            <a href="/" class="home-link">
                <i class="fas fa-arrow-left"></i>
                <span>Back to home</span>
            </a>
        </div>
    </div>
</main>

<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>