<?php
$metaTags = <<<HTML
<!-- Primary Meta Tags -->
<title>Privacy Policy - BetAssured</title>
<meta name="title" content="Privacy Policy - BetAssured">
<meta name="description" content="Read BetAssured's privacy policy to understand how we collect, use, and protect your personal information when using our football prediction services.">
<meta name="keywords" content="privacy policy, data protection, privacy, personal information, betting privacy">

<!-- Open Graph -->
<meta property="og:title" content="Privacy Policy - BetAssured">
<meta property="og:description" content="Read BetAssured's privacy policy to understand how we collect, use, and protect your personal information.">
<meta property="og:type" content="website">
<meta property="og:url" content="https://www.betassured.com/privacy">

<!-- Twitter -->
<meta property="twitter:title" content="Privacy Policy - BetAssured">
<meta property="twitter:description" content="Read BetAssured's privacy policy to understand how we collect, use, and protect your personal information.">
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
.privacy-page {
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
.privacy-content {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-lg);
    padding: 2.5rem;
    box-shadow: var(--shadow-sm);
}

/* Typography */
.privacy-content h2 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 2rem 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--gray-200);
}

.privacy-content h2:first-of-type {
    margin-top: 0;
}

.privacy-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 1.5rem 0 0.75rem;
}

.privacy-content h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin: 1.25rem 0 0.5rem;
}

.privacy-content p {
    font-size: 0.95rem;
    color: var(--gray-700);
    margin-bottom: 1rem;
    line-height: 1.7;
}

.privacy-content ul, 
.privacy-content ol {
    margin: 1rem 0 1.5rem;
    padding-left: 1.5rem;
}

.privacy-content li {
    font-size: 0.95rem;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.privacy-content li:last-child {
    margin-bottom: 0;
}

.privacy-content strong {
    color: var(--gray-800);
    font-weight: 600;
}

.privacy-content a {
    color: var(--primary);
    text-decoration: none;
    border-bottom: 1px dotted var(--gray-300);
}

.privacy-content a:hover {
    color: var(--gray-800);
    border-bottom-color: var(--primary);
}

/* Cookie Box */
.cookie-box {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 1.25rem;
    margin: 1.5rem 0;
}

.cookie-box p {
    margin-bottom: 0.75rem;
}

.cookie-box p:last-child {
    margin-bottom: 0;
}

.cookie-box i {
    color: var(--primary);
    margin-right: 0.5rem;
}

/* Info Box */
.info-box {
    background: var(--primary-light);
    border-left: 3px solid var(--primary);
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

/* Cookie Types */
.cookie-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
}

.cookie-type {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-md);
    padding: 1rem;
}

.cookie-type h5 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 0.5rem;
}

.cookie-type p {
    font-size: 0.85rem;
    margin-bottom: 0;
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
    
    .privacy-content {
        padding: 1.5rem;
    }
    
    .privacy-content h2 {
        font-size: 1.15rem;
    }
    
    .privacy-content h3 {
        font-size: 1rem;
    }
    
    .cookie-types {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .privacy-page {
        padding: 1rem 0;
    }
    
    .privacy-content {
        padding: 1.25rem;
    }
    
    .privacy-content ul, 
    .privacy-content ol {
        padding-left: 1.25rem;
    }
}
</style>

<main class="privacy-page">
    <div class="container">
        <!-- Header -->
        <div class="page-header">
            <h1>Privacy Policy</h1>
            <div class="last-updated">
                <i class="fas fa-calendar-alt"></i>
                <span>Last Updated: March 2026</span>
            </div>
        </div>

        <!-- Privacy Content -->
        <div class="privacy-content">
            <p>This Privacy Policy explains how BetAssured collects, uses, and protects your personal information when you use our website and services. By using BetAssured, you agree to the practices described in this policy.</p>

            <!-- 1. Introduction -->
            <h2>1. Introduction</h2>
            <p>BetAssured ("we", "us", or "our") is committed to protecting your privacy. This policy applies to all information collected through our website and related services. We comply with applicable data protection laws in Kenya.</p>

            <!-- 2. Information We Collect -->
            <h2>2. Information We Collect</h2>
            
            <h3>2.1 Personal Information</h3>
            <p>We may collect the following types of personal information:</p>
            <ul>
                <li>Email address (if you contact us or subscribe)</li>
                <li>Name (if you provide it)</li>
                <li>Phone number (if you provide it)</li>
                <li>Any information you voluntarily provide in communications</li>
            </ul>

            <h3>2.2 Usage Information</h3>
            <p>We automatically collect certain information when you visit our website, including:</p>
            <ul>
                <li>IP address and device information</li>
                <li>Browser type and version</li>
                <li>Pages visited and time spent</li>
                <li>Referring website addresses</li>
                <li>Date and time of visits</li>
            </ul>

            <!-- 3. Cookies -->
            <h2>3. Cookies and Tracking Technologies</h2>
            <p>We use cookies and similar technologies to improve your experience and understand how you use our site.</p>

            <div class="cookie-box">
                <i class="fas fa-cookie-bite"></i>
                <p>By continuing to use our website, you consent to our use of cookies as described in this policy.</p>
            </div>

            <h4>Types of Cookies We Use:</h4>
            <div class="cookie-types">
                <div class="cookie-type">
                    <h5>Necessary Cookies</h5>
                    <p>Essential for website functionality and security. These cannot be disabled.</p>
                </div>
                <div class="cookie-type">
                    <h5>Preference Cookies</h5>
                    <p>Remember your settings and preferences for a better experience.</p>
                </div>
                <div class="cookie-type">
                    <h5>Analytics Cookies</h5>
                    <p>Help us understand how visitors interact with our site to improve it.</p>
                </div>
            </div>

            <p>You can control cookies through your browser settings, but disabling them may affect site functionality.</p>

            <!-- 4. How We Use Your Information -->
            <h2>4. How We Use Your Information</h2>
            <p>We use your information for the following purposes:</p>
            <ul>
                <li>To provide and maintain our service</li>
                <li>To respond to your inquiries and requests</li>
                <li>To improve and personalize your experience</li>
                <li>To analyze usage patterns and optimize our website</li>
                <li>To comply with legal obligations</li>
                <li>To protect our rights and prevent misuse</li>
            </ul>

            <!-- 5. Information Sharing -->
            <h2>5. Information Sharing</h2>
            <p>We do not sell your personal information. We may share information in limited circumstances:</p>
            <ul>
                <li>With service providers who assist in operating our website (under confidentiality agreements)</li>
                <li>If required by law or to protect legal rights</li>
                <li>In connection with a business transfer (e.g., merger or acquisition)</li>
                <li>With your consent</li>
            </ul>

            <!-- 6. Data Security -->
            <h2>6. Data Security</h2>
            <p>We implement reasonable security measures to protect your information. However, no method of transmission or storage is 100% secure. We cannot guarantee absolute security.</p>

            <div class="info-box">
                <i class="fas fa-shield-alt"></i>
                <p>We regularly review our security practices and update them as needed to protect your data.</p>
            </div>

            <!-- 7. Your Rights -->
            <h2>7. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access the personal information we hold about you</li>
                <li>Request correction of inaccurate information</li>
                <li>Request deletion of your information</li>
                <li>Object to certain processing activities</li>
                <li>Withdraw consent at any time</li>
            </ul>
            <p>To exercise these rights, please <a href="/contact-us">contact us</a>.</p>

            <!-- 8. Children's Privacy -->
            <h2>8. Children's Privacy</h2>
            <p>Our service is not directed to individuals under 18. We do not knowingly collect information from children. If you believe a child has provided us with information, please contact us.</p>

            <!-- 9. Third-Party Links -->
            <h2>9. Third-Party Links</h2>
            <p>Our website may contain links to external sites. We are not responsible for their privacy practices and encourage you to review their policies.</p>

            <!-- 10. International Data Transfers -->
            <h2>10. International Data Transfers</h2>
            <p>Your information may be transferred to and processed in countries outside your own. By using our service, you consent to such transfers.</p>

            <!-- 11. Changes to This Policy -->
            <h2>11. Changes to This Policy</h2>
            <p>We may update this policy from time to time. Changes will be posted here with an updated "Last Updated" date. We encourage you to review this page periodically.</p>

            <!-- 12. Contact Us -->
            <h2>12. Contact Us</h2>
            <p>If you have questions about this Privacy Policy, please contact us:</p>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:privacy@betassured.com">privacy@betassured.com</a></li>
                <li><strong>Website:</strong> <a href="/contact-us">Contact Page</a></li>
            </ul>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Consent Summary -->
            <p><strong>By using BetAssured, you acknowledge that you have read and understood this Privacy Policy.</strong></p>
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