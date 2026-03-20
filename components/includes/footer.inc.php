<!-- Footer -->
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">

      <!-- About -->
      <div class="footer-about">
        <h3 class="footer-title">What is BetAssured?</h3>
        <p class="footer-text">
          BetAssured is your trusted source for reliable football predictions and expert betting insights. 
          Our platform delivers carefully analyzed match tips, daily predictions, and strategic betting guides 
          covering major leagues and competitions around the world. Whether you are searching for today's 
          winning tips, high-probability predictions, or jackpot selections, BetAssured helps you make 
          smarter betting decisions with data-driven analysis and consistent updates.
        </p>      
      </div>

      <!-- Quick Links -->
      <div class="footer-links">
        <h3 class="footer-title">Quick Links</h3>
        <ul class="footer-menu">
          <li><a href="/todays-predictions">Today's Predictions</a></li>
          <li><a href="/tomorrows-predictions">Tomorrow's Predictions</a></li>
          <li><a href="/jackpot-predictions">Jackpot Predictions</a></li>
          <li><a href="/blog">Blog</a></li>
        </ul>
      </div>

      <!-- Useful Links -->
      <div class="footer-links">
        <h3 class="footer-title">Useful Links</h3>
        <ul class="footer-menu">
          <li><a href="/terms-and-conditions">Terms and Conditions</a></li>
          <li><a href="/privacy-policy">Privacy Policy</a></li>
          <li><a href="/about-us">About Us</a></li>
          <li><a href="/contact-us">Contact Us</a></li>
          <li><a href="/partners">Partners</a></li>
        </ul>
      </div>

      <!-- Social -->
      <div class="footer-social">
        <h3 class="footer-title">Join Us On</h3>
        
          <span class="font-weight-bold footerLinks mb-5">Connect With Us</span>
          <br><br>
          <!-- Facebook -->
          <a class="btn btn-outline-light btn-floating m-1" role="button" aria-label="Facebook"
              href="https://www.facebook.com/profile.php?id=100094600476269" target="_blank" rel="noopener noreferrer">
              <i class="bi bi-facebook"></i>
          </a>

          <!-- Twitter -->
          <a class="btn btn-outline-light btn-floating m-1" role="button" aria-label="Twitter"
              href="https://twitter.com/FWT1x2" target="_blank" rel="noopener noreferrer">
              <i class="bi bi-twitter"></i>
          </a>

          <!-- Instagram -->
          <a class="btn btn-outline-light btn-floating m-1" role="button" aria-label="Instagram"
              href="https://instagram.com/freewinningtips1x2?utm_source=qr&igshid=MzNlNGNkZWQ4Mg%3D%3D" target="_blank" rel="noopener noreferrer">
              <i class="bi bi-instagram"></i>
          </a>

            <!-- Instagram -->
          <a class="btn btn-outline-light btn-floating m-1" role="button" aria-label="Instagram"
              href="https://t.me/betsassuredkenya" target="_blank" rel="noopener noreferrer">
              <i class="bi bi-telegram"></i>
          </a>
        </div>
    </div>

    <div class="footer-divider"></div>

    <p class="footer-disclaimer">
      <strong>Disclaimer:</strong> 18+ Only. The predictions and tips on BetAssured.com are for informational and entertainment purposes only.
      Gambling involves risk and you should only bet with money you can afford to lose. We do not guarantee winnings. Please gamble responsibly.
    </p>

    <div class="footer-divider"></div>

    <!-- Copyright -->
    <div class="footer-bottom">
      <p class="copyright">
        Copyright © <span id="year"><?php echo date('Y'); ?></span> BetAssured.com. All rights reserved.
      </p>
      
      <button type="button" class="back-to-top" id="btn-back-to-top" aria-label="Back To Top">
        <i class="bi bi-arrow-up"></i>
      </button>
    </div>
  </div>
</footer>

<style>
.site-footer {
  background-color: #05384B;
  color: white;
  padding: 3rem 0 1.5rem;
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  border-top: 1px solid var(--footer-border);
  margin-top: 3rem;
}

.footer-grid {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1.2fr;
  gap: 2.5rem;
  /* margin-bottom: 2.5rem; */
}

/* Titles */
.footer-title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--footer-heading);
  margin: 0 0 1.25rem 0;
  letter-spacing: -0.01em;
  text-transform: uppercase;
  opacity: 0.8;
}

/* About section */
.footer-text {
  font-size: 0.9rem;
  line-height: 1.6;
  color: var(--footer-text);
  margin-bottom: 1.25rem;
}

.footer-disclaimer {
  font-size: 0.8rem;
  line-height: 1.5;
  color: var(--footer-light);
  padding: 1rem;
  background: rgba(0,0,0,0.02);
  border-radius: 6px;
  border-left: 2px solid #ddd;
}

.footer-disclaimer strong {
  color: var(--footer-text);
  font-weight: 600;
}

/* Links */
.footer-menu {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-menu li {
  margin-bottom: 0.6rem;
}

.footer-menu a {
  color: var(--footer-link);
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s;
  display: inline-block;
}

.footer-menu a:hover {
  color: var(--footer-link-hover);
  transform: translateX(2px);
}

/* Social */
.social-links {
  display: flex;
  gap: 0.75rem;
  margin-bottom: 1rem;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  background: white;
  border: 1px solid var(--footer-border);
  border-radius: 50%;
  color: var(--footer-text);
  font-size: 1.1rem;
  transition: all 0.2s;
  text-decoration: none;
}

.social-link:hover {
  background: var(--footer-link-hover);
  border-color: var(--footer-link-hover);
  color: white;
}

/* Divider */
.footer-divider {
  height: 1px;
  background: var(--footer-border);
  margin: 1.5rem 0 1rem;
}

/* Bottom */
.footer-bottom {
  display: flex;
  justify-content: center;
  align-items: center!important;
  flex-direction: column;
  gap: 0.6rem;
  text-align: center;
}

.copyright {
  font-size: 0.85rem;
  color: var(--footer-light);
  margin: 0;
  text-align: center;
}

.back-to-top {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border: 1px solid var(--footer-border);
  border-radius: 50%;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  padding: 0;
}


/* Responsive */
@media (max-width: 992px) {
  .footer-grid {
    grid-template-columns: 1.5fr 1fr 1fr;
  }
  
  .footer-social {
    grid-column: span 3;
  }
}

@media (max-width: 768px) {
  .footer-grid {
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }
  
  .footer-about {
    grid-column: span 2;
  }
  
  .footer-social {
    grid-column: span 2;
  }
  
  .footer-title {
    margin-bottom: 0.75rem;
  }
}

@media (max-width: 480px) {
  .footer-grid {
    grid-template-columns: 1fr;
  }
  
  .footer-about,
  .footer-social {
    grid-column: span 1;
  }
  
  .footer-bottom {
    flex-direction: column-reverse;
    text-align: center;
  }
  
  .site-footer {
    padding: 2rem 0 1rem;
  }
}
</style>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/script.js"></script>
</body>
</html>