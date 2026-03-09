<style>
/* Modern Navigation Styles */
:root {
    --primary: #05384B;
    --primary-dark: #032a38;
    --accent: #f59e0b;
    --accent-hover: #d97706;
    --text-light: #f8fafc;
    --text-muted: #cbd5e1;
}

/* Top Bar */
.top-bar {
    background: linear-gradient(135deg, #05384B 0%, #0a4a60 100%);
    color: white;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    font-size: 13px;
}

.top-bar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand-tagline {
    display: flex;
    align-items: center;
    gap: 8px;
}

.brand-tagline i {
    color: var(--accent);
    font-size: 14px;
}

.brand-tagline span {
    font-weight: 500;
    letter-spacing: 0.3px;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-links a {
    color: var(--text-muted);
    transition: all 0.3s ease;
    font-size: 14px;
}

.social-links a:hover {
    color: var(--accent);
    transform: translateY(-2px);
}

/* Main Header */
.main-header {
    background: linear-gradient(135deg, #05384B 0%, #0a4a60 100%);
    color: white;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
}

/* Logo Section */
.logo-wrapper {
    flex: 0 0 250px;
}

.logo-link {
    display: block;
    transition: all 0.3s ease;
}

.logo-link:hover {
    transform: scale(1.02);
}

.logo-image {
    height: auto;
    width: 100%;
    max-width: 220px;
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
}

/* Desktop Navigation */
.desktop-nav {
    flex: 1;
    display: flex;
    justify-content: flex-end;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.nav-item {
    position: relative;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    color: var(--text-light);
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-link i {
    font-size: 16px;
    transition: transform 0.3s ease;
}

.nav-link::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 3px;
    background: var(--accent);
    transition: width 0.3s ease;
    border-radius: 3px 3px 0 0;
}

.nav-link:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
}

.nav-link:hover i {
    transform: rotate(5deg) scale(1.1);
    color: var(--accent);
}

.nav-link:hover::before {
    width: 70%;
}

/* Active State */
.nav-link.active {
    background: rgba(245, 158, 11, 0.15);
    color: var(--accent);
}

.nav-link.active i {
    color: var(--accent);
}

.nav-link.active::before {
    width: 70%;
    background: var(--accent);
}

/* Jackpot Badge */
.jackpot-badge {
    background: var(--accent);
    color: var(--primary);
    font-size: 11px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 12px;
    margin-left: 4px;
    text-transform: uppercase;
}

/* Mobile Menu Button */
.mobile-menu-btn {
    display: none;
    background: transparent;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 8px;
    padding: 10px 15px;
    color: white;
    font-size: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-menu-btn:hover {
    background: rgba(255,255,255,0.1);
    border-color: var(--accent);
}

/* Mobile Navigation */
.mobile-nav {
    display: none;
    background: linear-gradient(135deg, #05384B 0%, #0a4a60 100%);
    padding: 10px 0 15px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.mobile-nav-container {
    position: relative;
    padding: 0 10px;
}

.mobile-scroll-nav {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--accent) rgba(255,255,255,0.1);
    padding: 5px 0;
    -webkit-overflow-scrolling: touch;
}

.mobile-scroll-nav::-webkit-scrollbar {
    height: 4px;
}

.mobile-scroll-nav::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.1);
    border-radius: 4px;
}

.mobile-scroll-nav::-webkit-scrollbar-thumb {
    background: var(--accent);
    border-radius: 4px;
}

.mobile-nav-link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 25px;
    color: var(--text-light);
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.mobile-nav-link i {
    font-size: 14px;
    color: var(--accent);
}

.mobile-nav-link:hover,
.mobile-nav-link:active {
    background: var(--accent);
    color: var(--primary);
    border-color: var(--accent);
    transform: translateY(-2px);
}

.mobile-nav-link:hover i {
    color: var(--primary);
}

.mobile-nav-link.active {
    background: var(--accent);
    color: var(--primary);
    border-color: var(--accent);
}

.mobile-nav-link.active i {
    color: var(--primary);
}

/* Scroll Hint */
.scroll-hint {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 40px;
    background: linear-gradient(to right, transparent, #05384B);
    pointer-events: none;
    display: none;
}

/* Responsive Breakpoints */
@media (max-width: 992px) {
    .desktop-nav {
        display: none;
    }
    
    .mobile-menu-btn {
        display: block;
    }
    
    .logo-wrapper {
        flex: 0 0 180px;
    }
    
    .logo-image {
        max-width: 160px;
    }
    
    .mobile-nav {
        display: block;
    }
    
    .scroll-hint {
        display: block;
    }
}

@media (max-width: 768px) {
    .top-bar {
        display: none;
    }
    
    .header-container {
        padding: 8px 0;
    }
    
    .logo-wrapper {
        flex: 0 0 150px;
    }
    
    .logo-image {
        max-width: 140px;
    }
}

@media (max-width: 480px) {
    .mobile-nav-link {
        padding: 8px 14px;
        font-size: 13px;
    }
    
    .mobile-nav-link i {
        font-size: 12px;
    }
}

/* Animation for mobile menu */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.mobile-nav {
    animation: slideDown 0.3s ease forwards;
}
</style>

<!-- Top Bar -->
<div class="top-bar">
    <div class="container">
        <div class="top-bar-content">
            <div class="brand-tagline">
                <i class="fas fa-bolt"></i>
                <span>BetAssured • Smart Football Predictions • Daily Winning Tips</span>
            </div>
            <div class="social-links">
                <a href="https://t.me/yourchannel" target="_blank" rel="noopener noreferrer" title="Telegram">
                    <i class="fab fa-telegram-plane"></i>
                </a>
                <a href="https://twitter.com/yourhandle" target="_blank" rel="noopener noreferrer" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://wa.me/yournumber" target="_blank" rel="noopener noreferrer" title="WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="https://facebook.com/yourpage" target="_blank" rel="noopener noreferrer" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<div class="main-header">
    <div class="container">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo-wrapper">
                <a href="/" class="logo-link">
                    <img src="/betsassured.png" 
                         class="logo-image"
                         alt="BetAssured Football Predictions"
                         title="BetAssured Football Predictions">
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="/" class="nav-link">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/todays-predictions" class="nav-link">
                            <i class="fas fa-calendar-day"></i>
                            <span>Today's Tips</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/tomorrows-predictions" class="nav-link">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Tomorrow's Tips</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/yesterdays-predictions" class="nav-link">
                            <i class="fas fa-calendar-check"></i>
                            <span>Yesterday's Tips</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/jackpot-predictions" class="nav-link">
                            <i class="fas fa-trophy"></i>
                            <span>Jackpot Tips</span>
                            <span class="jackpot-badge">HOT</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Mobile Menu Button (hidden on desktop) -->
            <button class="mobile-menu-btn" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</div>

<!-- Mobile Navigation -->
<div class="mobile-nav" id="mobileNav">
    <div class="container">
        <div class="mobile-nav-container">
            <div class="mobile-scroll-nav">
                <a href="/" class="mobile-nav-link">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="/todays-predictions" class="mobile-nav-link">
                    <i class="fas fa-calendar-day"></i>
                    <span>Today</span>
                </a>
                <a href="/tomorrows-predictions" class="mobile-nav-link">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Tomorrow</span>
                </a>
                <a href="/yesterdays-predictions" class="mobile-nav-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Yesterday</span>
                </a>
                <a href="/jackpot-predictions" class="mobile-nav-link">
                    <i class="fas fa-trophy"></i>
                    <span>Jackpots</span>
                </a>
            </div>
            <div class="scroll-hint"></div>
        </div>
    </div>
</div>

<script>
// Optional: Add active class based on current URL
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    
    // Desktop nav active state
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
    
    // Mobile nav active state
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
    
    // Mobile menu toggle (if needed for collapsible)
    const toggleBtn = document.getElementById('mobileMenuToggle');
    const mobileNav = document.getElementById('mobileNav');
    
    if (toggleBtn && mobileNav) {
        toggleBtn.addEventListener('click', function() {
            mobileNav.classList.toggle('show');
        });
    }
});

// Scroll hint fades out when scrolled to end
const scrollNav = document.querySelector('.mobile-scroll-nav');
const scrollHint = document.querySelector('.scroll-hint');

if (scrollNav && scrollHint) {
    scrollNav.addEventListener('scroll', function() {
        const maxScroll = this.scrollWidth - this.clientWidth;
        if (this.scrollLeft >= maxScroll - 10) {
            scrollHint.style.opacity = '0';
        } else {
            scrollHint.style.opacity = '1';
        }
    });
}
</script>

<!-- Font Awesome for icons (add to head if not already present) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">