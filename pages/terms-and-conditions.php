<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Terms and Conditions - BetAssured</title>
<meta name="title" content="Terms and Conditions - BetAssured">
<meta name="description" content="Read the terms and conditions for using BetAssured's football prediction services. Understand our guidelines and policies.">
<meta name="keywords" content="terms and conditions, terms of use, betting terms, football predictions terms">

<!-- Open Graph -->
<meta property="og:title" content="Terms and Conditions - BetAssured">
<meta property="og:description" content="Read the terms and conditions for using BetAssured's football prediction services.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.betassured.com/terms">

<!-- Twitter -->
<meta property="twitter:title" content="Terms and Conditions - BetAssured">
<meta property="twitter:description" content="Read the terms and conditions for using BetAssured's football prediction services.">
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
.terms-page {
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
    margin-bottom: 0.5rem;
    letter-spacing: -0.01em;
}

.page-header .last-updated {
    font-size: 0.85rem;
    color: var(--gray-600);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-header .last-updated i {
    color: var(--primary);
    font-size: 0.8rem;
}

/* Content Card */
.terms-content {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    box-shadow: var(--shadow-sm);
}

/* Typography */
.terms-content h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 2rem 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.terms-content h2:first-of-type {
    margin-top: 0;
}

.terms-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 1.5rem 0 1rem;
}

.terms-content p {
    font-size: 0.95rem;
    color: var(--gray-700);
    margin-bottom: 1rem;
    line-height: 1.7;
}

.terms-content ul, 
.terms-content ol {
    margin: 1rem 0 1.5rem;
    padding-left: 1.5rem;
}

.terms-content li {
    font-size: 0.95rem;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.terms-content li:last-child {
    margin-bottom: 0;
}

.terms-content strong {
    color: var(--gray-800);
    font-weight: 600;
}

.terms-content a {
    color: var(--primary);
    text-decoration: none;
    border-bottom: 1px dotted var(--gray-300);
}

.terms-content a:hover {
    color: var(--gray-800);
    border-bottom-color: var(--primary);
}

/* Info Box */
.info-box {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    margin: 1.5rem 0;
}

.info-box p {
    margin-bottom: 0.5rem;
}

.info-box p:last-child {
    margin-bottom: 0;
}

.info-box i {
    color: var(--primary);
    margin-right: 0.5rem;
}

/* Divider */
.divider {
    height: 1px;
    background: var(--gray-200);
    margin: 2rem 0;
}

/* Footer */
.page-footer {
    margin-top: 2rem;
    padding-top: 1rem;
    text-align: left;
}

.footer-text {
    font-size: 0.85rem;
    color: var(--gray-600);
}

.footer-text a {
    color: var(--primary);
    text-decoration: none;
}

.footer-text a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .terms-content {
        padding: 1.5rem;
    }
    
    .terms-content h2 {
        font-size: 1.15rem;
    }
    
    .terms-content h3 {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .terms-page {
        padding: 1rem 0;
    }
    
    .terms-content {
        padding: 1.25rem;
    }
    
    .terms-content ul, 
    .terms-content ol {
        padding-left: 1.25rem;
    }
}
</style>

<main class="terms-page">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>Terms and Conditions</h1>
            <div class="last-updated">
                <i class="fas fa-calendar-alt"></i>
                <span>Last Updated: March 2026</span>
            </div>
        </div>

        <!-- Terms Content -->
        <div class="terms-content">
            <p>Welcome to BetAssured. By accessing or using our website, you agree to be bound by these terms and conditions.</p>

            <!-- 1. Acceptance -->
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing this website, you accept these terms and conditions in full. If you disagree with any part, please do not use our website. BetAssured operates under the laws of Kenya.</p>

            <!-- 2. Definitions -->
            <h2>2. Definitions</h2>
            <p>"Client", "You", "Your" refers to you, the person accessing this website. "The Company", "We", "Our", "Us" refers to BetAssured. "Party", "Parties" refers to both you and us.</p>

            <!-- 3. Cookies -->
            <h2>3. Cookies</h2>
            <p>We use cookies to enhance your experience. By using BetAssured, you consent to our use of cookies in accordance with our Privacy Policy. Cookies help us understand how you use our site and improve your experience.</p>

            <!-- 4. Intellectual Property -->
            <h2>4. Intellectual Property Rights</h2>
            <p>Unless otherwise stated, BetAssured and/or its licensors own the intellectual property rights for all material on this website. All rights are reserved.</p>
            
            <p><strong>You may not:</strong></p>
            <ul>
                <li>Sell, rent, or sub-license material from BetAssured</li>
                <li>Reproduce, duplicate, or copy material from BetAssured for commercial purposes</li>
                <li>Redistribute content from BetAssured without prior written consent</li>
            </ul>

            <!-- 5. User Content -->
            <h2>5. User Comments and Content</h2>
            <p>Parts of this website may allow users to post comments. BetAssured does not filter, edit, or review comments before they appear. Comments reflect the views of the person posting them.</p>
            
            <p><strong>You warrant that:</strong></p>
            <ul>
                <li>You are entitled to post the comments and have all necessary permissions</li>
                <li>Your comments do not infringe any intellectual property rights</li>
                <li>Your comments do not contain defamatory, offensive, or unlawful material</li>
                <li>Your comments will not be used to solicit or promote business</li>
            </ul>
            
            <p>BetAssured reserves the right to monitor and remove any comments at our discretion.</p>

            <!-- 6. Hyperlinking -->
            <h2>6. Hyperlinking to Our Content</h2>
            <p>The following organizations may link to our website without prior approval:</p>
            <ul>
                <li>Search engines</li>
                <li>News organizations</li>
                <li>Online directory distributors</li>
                <li>Accredited businesses (excluding non-profits and charity groups)</li>
            </ul>
            
            <p>Links must not be deceptive or falsely imply sponsorship. We may consider other link requests from educational institutions, trade associations, and professional firms.</p>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <p>To request a link, please email us with your details and linking intentions. We typically respond within 2–3 weeks.</p>
            </div>

            <!-- 7. iFrames -->
            <h2>7. iFrames</h2>
            <p>You may not create frames around our webpages that alter the visual presentation without our prior approval.</p>

            <!-- 8. Content Liability -->
            <h2>8. Content Liability</h2>
            <p>We are not responsible for content appearing on external websites that link to us. You agree to defend us against any claims arising from your website. No link should appear that may be interpreted as libelous, obscene, or criminal.</p>

            <!-- 9. Reservation of Rights -->
            <h2>9. Reservation of Rights</h2>
            <p>We reserve the right to request removal of any links at any time. You agree to remove links upon request. We may amend these terms at any time, and by continuing to link to us, you agree to be bound by the updated terms.</p>

            <!-- 10. Removal of Links -->
            <h2>10. Removal of Links</h2>
            <p>If you find any link on our website objectionable, please <a href="/contact-us">contact us</a>. We will consider removal requests but are not obligated to respond directly or take action.</p>

            <!-- 11. Disclaimer -->
            <h2>11. Disclaimer</h2>
            <p>To the fullest extent permitted by law, we exclude all representations and warranties relating to our website. Nothing in this disclaimer will:</p>
            <ul>
                <li>Limit or exclude liability for death or personal injury</li>
                <li>Limit or exclude liability for fraud or fraudulent misrepresentation</li>
                <li>Limit any liabilities in ways not permitted under applicable law</li>
                <li>Exclude any liabilities that cannot be excluded under applicable law</li>
            </ul>
            
            <p>The information on this website is provided free of charge, and we are not liable for any loss or damage arising from its use.</p>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Contact -->
            <h2>Questions?</h2>
            <p>If you have any questions about these terms, please <a href="/contact-us">contact us</a>.</p>
        </div>

        <!-- Footer -->
        <div class="page-footer">
            <p class="footer-text">
                <i class="fas fa-copyright"></i> BetAssured. All rights reserved.
            </p>
        </div>
    </div>
</main>

<?php
// Footer
include_once BASE_PATH . "/components/includes/footer.inc.php";
?>