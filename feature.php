<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Features - HU CMS";
include '../includes/header.php';
?>

<!-- Features Page CSS -->
<style>
.features-hero {
    background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
    color: white;
    padding: 6rem 0;
    text-align: center;
}

.features-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    color: white;
}

.features-hero .lead {
    font-size: 1.3rem;
    max-width: 800px;
    margin: 0 auto 2rem;
    opacity: 0.9;
}

.feature-category {
    padding: 4rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.feature-category:nth-child(even) {
    background: var(--light-color);
}

.category-header {
    text-align: center;
    margin-bottom: 3rem;
}

.category-header h2 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.category-header h2 i {
    font-size: 2.5rem;
}

.category-header p {
    color: var(--gray-color);
    max-width: 700px;
    margin: 0 auto;
    font-size: 1.1rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.feature-item {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--box-shadow);
    transition: var(--transition);
    border-top: 4px solid var(--accent-color);
}

.feature-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--accent-color);
    margin-bottom: 1.5rem;
    display: inline-block;
    padding: 15px;
    background: rgba(52, 152, 219, 0.1);
    border-radius: 12px;
}

.feature-item h3 {
    color: var(--dark-color);
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.feature-description {
    color: var(--gray-color);
    margin-bottom: 1.5rem;
    line-height: 1.7;
}

.feature-benefits {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem 0;
}

.feature-benefits li {
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.feature-benefits li:last-child {
    border-bottom: none;
}

.feature-benefits li i {
    color: var(--success-color);
    font-size: 0.9rem;
}

.feature-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 3rem;
    padding: 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.stat-card {
    text-align: center;
    padding: 1.5rem;
}

.stat-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.stat-label {
    color: var(--gray-color);
    font-size: 1rem;
}

.comparison-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2rem;
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
}

.comparison-table th,
.comparison-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
}

.comparison-table th {
    background: var(--primary-color);
    color: white;
    font-weight: 600;
}

.comparison-table tr:last-child td {
    border-bottom: none;
}

.comparison-table .yes {
    color: var(--success-color);
    font-weight: 600;
}

.comparison-table .no {
    color: var(--danger-color);
    font-weight: 600;
}

.comparison-table .partial {
    color: var(--warning-color);
    font-weight: 600;
}

.cta-section {
    text-align: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e8f4f8 100%);
}

.cta-section h2 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.cta-section p {
    color: var(--gray-color);
    max-width: 600px;
    margin: 0 auto 2rem;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .features-hero h1 {
        font-size: 2.5rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .feature-stats {
        grid-template-columns: 1fr;
    }
    
    .comparison-table {
        display: block;
        overflow-x: auto;
    }
}
</style>

<!-- Hero Section -->
<section class="features-hero">
    <div class="container">
        <h1>Powerful Features for Modern Healthcare</h1>
        <p class="lead">
            Discover how the Haramaya University Clinical Management System transforms healthcare delivery 
            with comprehensive digital solutions designed for efficiency, security, and patient-centered care.
        </p>
        <div class="hero-buttons">
            <a href="#patient-management" class="btn btn-light btn-lg">
                <i class="fas fa-user-injured"></i> Patient Features
            </a>
            <a href="#clinical-tools" class="btn btn-outline-light btn-lg">
                <i class="fas fa-stethoscope"></i> Clinical Tools
            </a>
            <a href="#admin-features" class="btn btn-outline-light btn-lg">
                <i class="fas fa-cog"></i> Administration
            </a>
        </div>
    </div>
</section>

<!-- Patient Management Features -->
<section id="patient-management" class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-user-injured"></i>
                Patient Management
            </h2>
            <p>Comprehensive tools for managing patient information, appointments, and medical history</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Patient Registration</h3>
                <p class="feature-description">
                    Digital patient registration system with complete demographic, contact, and insurance information.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Quick registration with auto-generated ID</li>
                    <li><i class="fas fa-check"></i> Photo capture and storage</li>
                    <li><i class="fas fa-check"></i> Insurance information management</li>
                    <li><i class="fas fa-check"></i> Emergency contact details</li>
                    <li><i class="fas fa-check"></i> Complete medical history intake</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Patient Search & Records</h3>
                <p class="feature-description">
                    Advanced search capabilities with instant access to complete patient records.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Multi-criteria search (name, ID, phone, etc.)</li>
                    <li><i class="fas fa-check"></i> Quick patient lookup</li>
                    <li><i class="fas fa-check"></i> Complete medical history view</li>
                    <li><i class="fas fa-check"></i> Previous visit summaries</li>
                    <li><i class="fas fa-check"></i> Allergy and medication alerts</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Appointment Scheduling</h3>
                <p class="feature-description">
                    Intelligent scheduling system with automated reminders and conflict prevention.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Online appointment booking</li>
                    <li><i class="fas fa-check"></i> Doctor availability calendar</li>
                    <li><i class="fas fa-check"></i> Automated SMS/email reminders</li>
                    <li><i class="fas fa-check"></i> Waitlist management</li>
                    <li><i class="fas fa-check"></i> No-show tracking</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Clinical Tools -->
<section id="clinical-tools" class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-stethoscope"></i>
                Clinical Management Tools
            </h2>
            <p>Advanced tools for healthcare professionals to deliver quality patient care</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-file-medical"></i>
                </div>
                <h3>Electronic Health Records</h3>
                <p class="feature-description">
                    Complete EHR system compliant with Ethiopian healthcare standards and international protocols.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Digital medical charts</li>
                    <li><i class="fas fa-check"></i> Diagnosis and treatment tracking</li>
                    <li><i class="fas fa-check"></i> Progress notes and SOAP format</li>
                    <li><i class="fas fa-check"></i> Vital signs documentation</li>
                    <li><i class="fas fa-check"></i> Treatment plan management</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-prescription"></i>
                </div>
                <h3>Prescription Management</h3>
                <p class="feature-description">
                    Electronic prescription system with drug interaction checks and inventory integration.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Electronic prescription writing</li>
                    <li><i class="fas fa-check"></i> Drug interaction alerts</li>
                    <li><i class="fas fa-check"></i> Allergy checking</li>
                    <li><i class="fas fa-check"></i> Pharmacy integration</li>
                    <li><i class="fas fa-check"></i> Refill management</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-flask"></i>
                </div>
                <h3>Laboratory Integration</h3>
                <p class="feature-description">
                    Seamless integration with laboratory systems for test ordering and result management.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Electronic test ordering</li>
                    <li><i class="fas fa-check"></i> Result tracking and alerts</li>
                    <li><i class="fas fa-check"></i> Lab report generation</li>
                    <li><i class="fas fa-check"></i> Quality control tracking</li>
                    <li><i class="fas fa-check"></i> Equipment management</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Pharmacy Management -->
<section class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-pills"></i>
                Pharmacy Management
            </h2>
            <p>Complete pharmacy management system for efficient medication dispensing and inventory control</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-prescription-bottle-alt"></i>
                </div>
                <h3>Medication Dispensing</h3>
                <p class="feature-description">
                    Streamlined medication dispensing process with tracking and verification.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Prescription verification</li>
                    <li><i class="fas fa-check"></i> Dosage calculation</li>
                    <li><i class="fas fa-check"></i> Patient counseling records</li>
                    <li><i class="fas fa-check"></i> Dispensing history</li>
                    <li><i class="fas fa-check"></i> Batch number tracking</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <h3>Inventory Control</h3>
                <p class="feature-description">
                    Real-time inventory tracking with automated reordering and expiry management.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Real-time stock levels</li>
                    <li><i class="fas fa-check"></i> Automated reorder alerts</li>
                    <li><i class="fas fa-check"></i> Expiry date tracking</li>
                    <li><i class="fas fa-check"></i> Supplier management</li>
                    <li><i class="fas fa-check"></i> Stock movement tracking</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Drug Safety</h3>
                <p class="feature-description">
                    Comprehensive drug safety features to ensure patient medication safety.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Drug interaction checking</li>
                    <li><i class="fas fa-check"></i> Allergy alerts</li>
                    <li><i class="fas fa-check"></i> Contraindication warnings</li>
                    <li><i class="fas fa-check"></i> Dose adjustment suggestions</li>
                    <li><i class="fas fa-check"></i> Adverse reaction reporting</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Billing & Financial Management -->
<section class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-file-invoice-dollar"></i>
                Billing & Financial Management
            </h2>
            <p>Complete financial management system with billing, insurance, and reporting</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h3>Automated Billing</h3>
                <p class="feature-description">
                    Automated billing system with insurance integration and multiple payment options.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Automated invoice generation</li>
                    <li><i class="fas fa-check"></i> Insurance claim processing</li>
                    <li><i class="fas fa-check"></i> Multiple payment methods</li>
                    <li><i class="fas fa-check"></i> Partial payment support</li>
                    <li><i class="fas fa-check"></i> Receipt generation</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Insurance Management</h3>
                <p class="feature-description">
                    Comprehensive insurance management system for Ethiopian healthcare insurance schemes.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Insurance verification</li>
                    <li><i class="fas fa-check"></i> Claim submission</li>
                    <li><i class="fas fa-check"></i> Policy tracking</li>
                    <li><i class="fas fa-check"></i> Coverage checking</li>
                    <li><i class="fas fa-check"></i> Reimbursement tracking</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Financial Reporting</h3>
                <p class="feature-description">
                    Comprehensive financial reports for revenue tracking and management.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Revenue reports</li>
                    <li><i class="fas fa-check"></i> Accounts receivable</li>
                    <li><i class="fas fa-check"></i> Expense tracking</li>
                    <li><i class="fas fa-check"></i> Financial statements</li>
                    <li><i class="fas fa-check"></i> Audit trails</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Administrative Features -->
<section id="admin-features" class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-cogs"></i>
                Administrative Features
            </h2>
            <p>Powerful administrative tools for system management and reporting</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>User Management</h3>
                <p class="feature-description">
                    Complete user management system with role-based access control.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Role-based permissions</li>
                    <li><i class="fas fa-check"></i> User account management</li>
                    <li><i class="fas fa-check"></i> Activity logging</li>
                    <li><i class="fas fa-check"></i> Session management</li>
                    <li><i class="fas fa-check"></i> Audit trails</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Analytics & Reporting</h3>
                <p class="feature-description">
                    Advanced analytics and reporting tools for data-driven decision making.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Clinical analytics</li>
                    <li><i class="fas fa-check"></i> Operational reports</li>
                    <li><i class="fas fa-check"></i> Performance metrics</li>
                    <li><i class="fas fa-check"></i> Custom report builder</li>
                    <li><i class="fas fa-check"></i> Data export capabilities</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-database"></i>
                </div>
                <h3>System Administration</h3>
                <p class="feature-description">
                    Complete system administration tools for configuration and maintenance.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> System configuration</li>
                    <li><i class="fas fa-check"></i> Backup and recovery</li>
                    <li><i class="fas fa-check"></i> Data management</li>
                    <li><i class="fas fa-check"></i> System monitoring</li>
                    <li><i class="fas fa-check"></i> Update management</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Security Features -->
<section class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-shield-alt"></i>
                Security & Compliance
            </h2>
            <p>Enterprise-grade security features to protect patient data and ensure compliance</p>
        </div>
        
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>Data Security</h3>
                <p class="feature-description">
                    Multi-layered security system to protect sensitive patient information.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> 256-bit encryption</li>
                    <li><i class="fas fa-check"></i> Secure data transmission</li>
                    <li><i class="fas fa-check"></i> Data backup and recovery</li>
                    <li><i class="fas fa-check"></i> Intrusion detection</li>
                    <li><i class="fas fa-check"></i> Regular security audits</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Access Control</h3>
                <p class="feature-description">
                    Granular access control system to ensure data privacy and security.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> Role-based access control</li>
                    <li><i class="fas fa-check"></i> Two-factor authentication</li>
                    <li><i class="fas fa-check"></i> Session timeout</li>
                    <li><i class="fas fa-check"></i> IP restriction</li>
                    <li><i class="fas fa-check"></i> Access logging</li>
                </ul>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3>Compliance & Standards</h3>
                <p class="feature-description">
                    Built to comply with Ethiopian healthcare regulations and international standards.
                </p>
                <ul class="feature-benefits">
                    <li><i class="fas fa-check"></i> HIPAA compliance ready</li>
                    <li><i class="fas fa-check"></i> Ethiopian MoH standards</li>
                    <li><i class="fas fa-check"></i> ISO 27001 framework</li>
                    <li><i class="fas fa-check"></i> Audit trail compliance</li>
                    <li><i class="fas fa-check"></i> Data retention policies</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- System Statistics -->
<section class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-chart-pie"></i>
                System Capabilities
            </h2>
            <p>High-performance system designed for reliability and scalability</p>
        </div>
        
        <div class="feature-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">Unlimited</div>
                <div class="stat-label">Concurrent Users</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="stat-number">99.9%</div>
                <div class="stat-label">Uptime Guarantee</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="stat-number">&lt;1s</div>
                <div class="stat-label">Response Time</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="stat-number">100%</div>
                <div class="stat-label">Mobile Responsive</div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Comparison Table -->
<section class="feature-category">
    <div class="container">
        <div class="category-header">
            <h2>
                <i class="fas fa-balance-scale"></i>
                Feature Comparison
            </h2>
            <p>See how HU CMS compares to traditional paper-based systems</p>
        </div>
        
        <table class="comparison-table">
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Paper-Based System</th>
                    <th>HU CMS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Patient Registration</strong></td>
                    <td class="no">Manual forms, prone to errors</td>
                    <td class="yes">Digital, automated, error-free</td>
                </tr>
                <tr>
                    <td><strong>Record Access</strong></td>
                    <td class="no">Physical search, slow retrieval</td>
                    <td class="yes">Instant search, anywhere access</td>
                </tr>
                <tr>
                    <td><strong>Appointment Scheduling</strong></td>
                    <td class="partial">Manual calendar, conflicts common</td>
                    <td class="yes">Automated, conflict-free scheduling</td>
                </tr>
                <tr>
                    <td><strong>Prescription Writing</strong></td>
                    <td class="no">Illegible handwriting, errors</td>
                    <td class="yes">Digital, legible, error-checking</td>
                </tr>
                <tr>
                    <td><strong>Billing & Insurance</strong></td>
                    <td class="partial">Manual calculations, delays</td>
                    <td class="yes">Automated, real-time processing</td>
                </tr>
                <tr>
                    <td><strong>Reporting</strong></td>
                    <td class="no">Manual compilation, days to prepare</td>
                    <td class="yes">Automatic generation, real-time</td>
                </tr>
                <tr>
                    <td><strong>Data Security</strong></td>
                    <td class="no">Physical files, risk of loss/theft</td>
                    <td class="yes">Encrypted, backed up, secure</td>
                </tr>
                <tr>
                    <td><strong>Cost Efficiency</strong></td>
                    <td class="partial">High paper/printing costs</td>
                    <td class="yes">Paperless, reduces costs by 60%</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Transform Your Healthcare Facility?</h2>
        <p>
            Join the digital healthcare revolution with Haramaya University Clinical Management System. 
            Experience improved efficiency, better patient care, and comprehensive clinical management.
        </p>
        <div class="hero-buttons">
            <a href="../register.php" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket"></i> Get Started Today
            </a>
            <a href="../pages/contact.php" class="btn btn-outline btn-lg">
                <i class="fas fa-comments"></i> Schedule a Demo
            </a>
            <a href="../pages/documentation.php" class="btn btn-outline btn-lg">
                <i class="fas fa-book"></i> Read Documentation
            </a>
        </div>
    </div>
</section>

<!-- Interactive Feature Explorer -->
<div class="container" style="padding: 3rem 0; display: none;" id="featureExplorer">
    <div class="card">
        <h3 style="text-align: center; margin-bottom: 2rem; color: var(--primary-color);">
            <i class="fas fa-search"></i> Find the Right Features for You
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <button class="role-filter btn btn-outline" data-role="doctor">
                <i class="fas fa-user-md"></i> Doctor
            </button>
            <button class="role-filter btn btn-outline" data-role="nurse">
                <i class="fas fa-user-nurse"></i> Nurse
            </button>
            <button class="role-filter btn btn-outline" data-role="pharmacist">
                <i class="fas fa-prescription-bottle-alt"></i> Pharmacist
            </button>
            <button class="role-filter btn btn-outline" data-role="admin">
                <i class="fas fa-user-cog"></i> Administrator
            </button>
            <button class="role-filter btn btn-outline" data-role="reception">
                <i class="fas fa-headset"></i> Receptionist
            </button>
        </div>
        <div id="roleFeatures" style="margin-top: 2rem; padding: 1.5rem; background: var(--light-color); border-radius: var(--border-radius);">
            <p style="text-align: center; color: var(--gray-color);">Select a role to see relevant features</p>
        </div>
    </div>
</div>

<script>
// Role-based feature filter
document.addEventListener('DOMContentLoaded', function() {
    const roleFilters = document.querySelectorAll('.role-filter');
    const roleFeatures = document.getElementById('roleFeatures');
    
    const featureMap = {
        'doctor': [
            'Electronic Health Records',
            'Prescription Management',
            'Patient History View',
            'Lab Test Ordering',
            'Treatment Planning',
            'Progress Notes'
        ],
        'nurse': [
            'Patient Vitals Recording',
            'Medication Administration',
            'Appointment Scheduling',
            'Patient Monitoring',
            'Treatment Assistance',
            'Patient Education'
        ],
        'pharmacist': [
            'Prescription Verification',
            'Inventory Management',
            'Drug Interaction Checking',
            'Dispensing Tracking',
            'Patient Counseling',
            'Stock Management'
        ],
        'admin': [
            'User Management',
            'System Configuration',
            'Reporting & Analytics',
            'Billing Management',
            'Security Settings',
            'Data Management'
        ],
        'reception': [
            'Patient Registration',
            'Appointment Scheduling',
            'Check-in/Check-out',
            'Billing & Payments',
            'Patient Communication',
            'Record Management'
        ]
    };
    
    roleFilters.forEach(button => {
        button.addEventListener('click', function() {
            const role = this.dataset.role;
            
            // Update active state
            roleFilters.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show features for selected role
            const features = featureMap[role];
            if (features && roleFeatures) {
                let html = `<h4 style="margin-bottom: 1rem; color: var(--primary-color);">Features for ${this.textContent.trim()}:</h4>`;
                html += '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">';
                
                features.forEach(feature => {
                    html += `
                        <div style="background: white; padding: 1rem; border-radius: var(--border-radius);">
                            <i class="fas fa-check-circle" style="color: var(--success-color); margin-right: 10px;"></i>
                            ${feature}
                        </div>
                    `;
                });
                
                html += '</div>';
                roleFeatures.innerHTML = html;
            }
        });
    });
    
    // Show feature explorer after page load
    setTimeout(() => {
        const explorer = document.getElementById('featureExplorer');
        if (explorer) {
            explorer.style.display = 'block';
        }
    }, 2000);
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>

