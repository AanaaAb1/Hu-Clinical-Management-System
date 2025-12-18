</main>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <i class="fas fa-hospital-alt"></i>
                    <div class="logo-text">
                        <span class="logo-title">HU CMS</span>
                        <span class="logo-subtitle">Clinical Management System</span>
                    </div>
                </div>
                <p class="footer-description">
                    Transforming healthcare delivery at Haramaya University through digital innovation and efficient
                    clinical management.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/about.php"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/feature.php"><i class="fas fa-chevron-right"></i> Features</a></li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/documentation.php"><i class="fas fa-chevron-right"></i> Documentation</a></li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>System Features</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/feature.php#patient"><i class="fas fa-chevron-right"></i> Patient Management</a>
                    </li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/feature.php#appointment"><i class="fas fa-chevron-right"></i> Appointment
                            Scheduling</a></li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/feature.php#medical"><i class="fas fa-chevron-right"></i> Medical Records</a>
                    </li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/feature.php#pharmacy"><i class="fas fa-chevron-right"></i> Pharmacy
                            Management</a></li>
                    <li><a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/feature.php#billing"><i class="fas fa-chevron-right"></i> Billing System</a>
                    </li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contact Info</h4>
                <ul class="contact-info">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Haramaya University<br>College of Health Sciences<br>Dire Dawa, Ethiopia</span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <span>+251 (0) 255 530 000</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>info@hu-cms.edu.et</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Mon - Fri: 8:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Haramaya University Clinical Management System. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/about.php">About</a>
                <a href="<?php echo defined('BASE_PATH') ? BASE_PATH : ''; ?>/pages/contact.php">Contact</a>
            </div>
        </div>
        </div>
        </footer>
        
        <script src="js/main.js"></script>
        <?php if (isset($additional_scripts)): ?>
            <?php foreach ($additional_scripts as $script): ?>
                <script src="<?php echo $script; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
        </body>
        
        </html>