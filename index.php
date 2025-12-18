<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = "Welcome to Haramaya University CMS";
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title">Transforming Healthcare Delivery</h1>
                <p class="hero-subtitle">
                    Haramaya University Clinical Management System (CMS) is a comprehensive digital solution
                    designed to streamline healthcare operations, enhance patient care, and improve clinical
                    efficiency across all medical facilities.
                </p>
                <div class="hero-buttons">
                    <?php if (Helper::isLoggedIn()): ?>
                        <a href="dashboard.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                        </a>
                        <a href="pages/features.php" class="btn btn-outline btn-lg">
                            <i class="fas fa-star"></i> Explore Features
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Login to System
                        </a>
                        <a href="register.php" class="btn btn-outline btn-lg">
                            <i class="fas fa-user-plus"></i> Request Access
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">5000+</span>
                        <span class="stat-label">Patients Served</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99.9%</span>
                        <span class="stat-label">Uptime</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">ISO 27001</span>
                        <span class="stat-label">Certified</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="dashboard-preview">
                    <div class="preview-header">
                        <div class="preview-controls">
                            <span class="control red"></span>
                            <span class="control yellow"></span>
                            <span class="control green"></span>
                        </div>
                        <span>CMS Dashboard</span>
                    </div>
                    <div class="preview-content">
                        <div class="preview-stats">
                            <div class="preview-stat">
                                <span class="preview-stat-number">1,248</span>
                                <span class="preview-stat-label">Today's Patients</span>
                            </div>
                            <div class="preview-stat">
                                <span class="preview-stat-number">87%</span>
                                <span class="preview-stat-label">Bed Occupancy</span>
                            </div>
                        </div>
                        <div class="preview-chart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Features Overview -->
<section class="features-overview">
    <div class="container">
        <div class="section-header">
            <h2>Comprehensive Healthcare Management</h2>
            <p>Everything you need to run an efficient medical facility</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-injured"></i>
                </div>
                <h3>Patient Management</h3>
                <p>Complete digital patient registration, medical history, and profile management with real-time updates.</p>
                <ul class="feature-list">
                    <li>Patient Registration</li>
                    <li>Medical History</li>
                    <li>Insurance Management</li>
                    <li>Emergency Contacts</li>
                </ul>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Smart Scheduling</h3>
                <p>Intelligent appointment booking system with automated reminders and conflict resolution.</p>
                <ul class="feature-list">
                    <li>Online Booking</li>
                    <li>Doctor Availability</li>
                    <li>Automated Reminders</li>
                    <li>Waitlist Management</li>
                </ul>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-medical"></i>
                </div>
                <h3>Medical Records</h3>
                <p>Comprehensive EHR system compliant with Ethiopian healthcare standards and international protocols.</p>
                <ul class="feature-list">
                    <li>Digital Charts</li>
                    <li>Diagnosis Tracking</li>
                    <li>Treatment Plans</li>
                    <li>Progress Notes</li>
                </ul>
            </div>
        </div>
        
        <div class="features-grid mt-4">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <h3>Pharmacy Management</h3>
                <p>Integrated pharmacy module with drug interaction checks, inventory control, and prescription tracking.</p>
                <ul class="feature-list">
                    <li>Electronic Prescriptions</li>
                    <li>Drug Interaction Alerts</li>
                    <li>Inventory Management</li>
                    <li>Dispensing Tracking</li>
                </ul>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Laboratory Integration</h3>
                <p>Seamless integration with laboratory systems for test ordering, result tracking, and reporting.</p>
                <ul class="feature-list">
                    <li>Test Order Management</li>
                    <li>Result Integration</li>
                    <li>Automated Notifications</li>
                    <li>Quality Control</li>
                </ul>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3>Billing & Insurance</h3>
                <p>Automated billing system with insurance claim processing and financial reporting.</p>
                <ul class="feature-list">
                    <li>Automated Invoicing</li>
                    <li>Insurance Claims</li>
                    <li>Payment Tracking</li>
                    <li>Financial Reports</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- CTA Section -->
<section class="hero" style="background: linear-gradient(135deg, var(--primary-color), var(--dark-color)); padding: 80px 0;">
    <div class="container">
        <div class="section-header text-center" style="color: white;">
            <h2>Ready to Transform Healthcare?</h2>
            <p style="color: rgba(255, 255, 255, 0.8);">Join the digital revolution in healthcare management</p>
        </div>
        <div class="text-center mt-3">
            <?php if (Helper::isLoggedIn()): ?>
                <a href="dashboard.php" class="btn btn-secondary btn-lg">
                    <i class="fas fa-rocket"></i> Launch Dashboard
                </a>
            <?php else: ?>
                <a href="register.php" class="btn btn-light btn-lg">
                    <i class="fas fa-user-plus"></i> Get Started Today
                </a>
                <a href="pages/contact.php" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                    <i class="fas fa-phone-alt"></i> Contact Sales
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>