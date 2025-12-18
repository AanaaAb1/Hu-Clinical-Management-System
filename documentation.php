<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Documentation - HU CMS";
$additional_scripts = ['js/documentation.js'];
include '../includes/header.php';
?>

<!-- Documentation CSS -->
<style>
    .doc-hero {
        background: linear-gradient(135deg, #2c3e50, var(--dark-color));
        color: white;
        padding: 5rem 0;
        text-align: center;
    }

    .doc-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        color: white;
    }

    .doc-hero .lead {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 2rem;
        opacity: 0.9;
    }

    .documentation-container {
        padding: 4rem 0;
        display: flex;
        gap: 3rem;
    }

    .doc-sidebar {
        flex: 0 0 300px;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2rem;
        position: sticky;
        top: 100px;
        height: fit-content;
        max-height: calc(100vh - 150px);
        overflow-y: auto;
    }

    .doc-sidebar h3 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-color);
    }

    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin-bottom: 0.5rem;
    }

    .sidebar-nav a {
        display: block;
        padding: 10px 15px;
        color: var(--dark-color);
        text-decoration: none;
        border-radius: var(--border-radius);
        transition: var(--transition);
        border-left: 3px solid transparent;
    }

    .sidebar-nav a:hover,
    .sidebar-nav a.active {
        background: var(--light-color);
        color: var(--primary-color);
        border-left-color: var(--primary-color);
    }

    .doc-content {
        flex: 1;
    }

    .doc-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 2.5rem;
        margin-bottom: 2rem;
        scroll-margin-top: 100px;
    }

    .doc-section h2 {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--light-color);
    }

    .doc-section h3 {
        color: var(--dark-color);
        margin: 2rem 0 1rem;
    }

    .doc-section p {
        line-height: 1.8;
        margin-bottom: 1.5rem;
        color: var(--gray-color);
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }

    .feature-list li {
        padding: 10px 0 10px 30px;
        position: relative;
        border-bottom: 1px solid #f0f0f0;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-list li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--success-color);
        font-weight: bold;
    }

    .code-block {
        background: #2c3e50;
        color: #ecf0f1;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        font-family: 'Courier New', monospace;
        margin: 1.5rem 0;
        overflow-x: auto;
    }

    .code-block .comment {
        color: #7f8c8d;
    }

    .code-block .keyword {
        color: #3498db;
    }

    .code-block .string {
        color: #2ecc71;
    }

    .code-block .function {
        color: #f39c12;
    }

    .step-guide {
        background: var(--light-color);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin: 2rem 0;
    }

    .step-guide h4 {
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .step-guide ol {
        margin: 1rem 0;
        padding-left: 1.5rem;
    }

    .step-guide li {
        margin-bottom: 0.8rem;
        line-height: 1.6;
    }

    .tab-container {
        margin: 2rem 0;
    }

    .tab-buttons {
        display: flex;
        border-bottom: 1px solid #ddd;
        margin-bottom: 0;
    }

    .tab-btn {
        padding: 12px 24px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        color: var(--gray-color);
        cursor: pointer;
        transition: var(--transition);
    }

    .tab-btn:hover {
        color: var(--primary-color);
    }

    .tab-btn.active {
        color: var(--primary-color);
        border-bottom-color: var(--primary-color);
    }

    .tab-content {
        padding: 2rem;
        background: white;
        border-radius: 0 var(--border-radius) var(--border-radius) var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .table-responsive {
        overflow-x: auto;
        margin: 1.5rem 0;
    }

    .doc-table {
        width: 100%;
        border-collapse: collapse;
    }

    .doc-table th,
    .doc-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .doc-table th {
        background: var(--light-color);
        font-weight: 600;
        color: var(--dark-color);
    }

    .doc-table tr:hover {
        background: #f9f9f9;
    }

    .download-section {
        text-align: center;
        padding: 3rem;
        background: linear-gradient(135deg, var(--light-color), #fff);
        border-radius: var(--border-radius);
        margin-top: 3rem;
    }

    .download-links {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    @media (max-width: 992px) {
        .documentation-container {
            flex-direction: column;
        }

        .doc-sidebar {
            position: static;
            max-height: none;
        }
    }

    @media (max-width: 768px) {
        .doc-hero h1 {
            font-size: 2.5rem;
        }

        .doc-section {
            padding: 1.5rem;
        }

        .download-links {
            flex-direction: column;
        }
    }
</style>

<!-- Hero Section -->
<section class="doc-hero">
    <div class="container">
        <h1>HU CMS Documentation</h1>
        <p class="lead">
            Comprehensive guides, tutorials, and references for using the Haramaya University
            Clinical Management System effectively.
        </p>
        <div class="hero-buttons">
            <a href="#getting-started" class="btn btn-light btn-lg">
                <i class="fas fa-play-circle"></i> Getting Started
            </a>
            <a href="#user-guides" class="btn btn-outline-light btn-lg">
                <i class="fas fa-book"></i> User Guides
            </a>
            <a href="#api-docs" class="btn btn-outline-light btn-lg">
                <i class="fas fa-code"></i> API Documentation
            </a>
        </div>
    </div>
</section>

<div class="container">
    <div class="documentation-container">
        <!-- Sidebar Navigation -->
        <aside class="doc-sidebar">
            <h3><i class="fas fa-book"></i> Documentation</h3>
            <ul class="sidebar-nav">
                <li><a href="#getting-started" class="active"><i class="fas fa-play-circle"></i> Getting Started</a>
                </li>
                <li><a href="#system-overview"><i class="fas fa-info-circle"></i> System Overview</a></li>
                <li><a href="#user-guides"><i class="fas fa-users"></i> User Guides</a></li>
                <li><a href="#installation"><i class="fas fa-download"></i> Installation Guide</a></li>
                <li><a href="#configuration"><i class="fas fa-cog"></i> Configuration</a></li>
                <li><a href="#api-docs"><i class="fas fa-code"></i> API Documentation</a></li>
                <li><a href="#troubleshooting"><i class="fas fa-wrench"></i> Troubleshooting</a></li>
                <li><a href="#faq"><i class="fas fa-question-circle"></i> FAQ</a></li>
                <li><a href="#release-notes"><i class="fas fa-sticky-note"></i> Release Notes</a></li>
            </ul>

            <h3 style="margin-top: 2rem;"><i class="fas fa-download"></i> Quick Downloads</h3>
            <ul class="sidebar-nav">
                <li><a href="downloads/user-manual.pdf" target="_blank"><i class="fas fa-file-pdf"></i> User Manual
                        (PDF)</a></li>
                <li><a href="downloads/admin-guide.pdf" target="_blank"><i class="fas fa-file-pdf"></i> Admin Guide</a>
                </li>
                <li><a href="downloads/api-reference.pdf" target="_blank"><i class="fas fa-file-pdf"></i> API
                        Reference</a></li>
                <li><a href="downloads/installation-guide.pdf" target="_blank"><i class="fas fa-file-pdf"></i>
                        Installation Guide</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="doc-content">
            <!-- Getting Started -->
            <section id="getting-started" class="doc-section">
                <h2><i class="fas fa-play-circle"></i> Getting Started</h2>
                <p>
                    Welcome to the Haramaya University Clinical Management System (HU CMS) documentation.
                    This guide will help you get started with using the system effectively.
                </p>

                <div class="step-guide">
                    <h4>Quick Start Guide</h4>
                    <ol>
                        <li><strong>Register for an account</strong> using your university email address</li>
                        <li><strong>Wait for approval</strong> from system administrator</li>
                        <li><strong>Login to the system</strong> using your credentials</li>
                        <li><strong>Complete your profile</strong> with your professional information</li>
                        <li><strong>Explore the dashboard</strong> and familiarize yourself with the interface</li>
                        <li><strong>Watch tutorial videos</strong> available in the help section</li>
                    </ol>
                </div>

                <h3>System Requirements</h3>
                <div class="table-responsive">
                    <table class="doc-table">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Minimum</th>
                                <th>Recommended</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Web Browser</td>
                                <td>Chrome 60+, Firefox 55+, Safari 11+</td>
                                <td>Latest version</td>
                            </tr>
                            <tr>
                                <td>Internet Connection</td>
                                <td>1 Mbps</td>
                                <td>10 Mbps or higher</td>
                            </tr>
                            <tr>
                                <td>Screen Resolution</td>
                                <td>1024x768</td>
                                <td>1920x1080 or higher</td>
                            </tr>
                            <tr>
                                <td>JavaScript</td>
                                <td>Enabled</td>
                                <td>Enabled</td>
                            </tr>
                            <tr>
                                <td>Cookies</td>
                                <td>Enabled</td>
                                <td>Enabled</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- System Overview -->
            <section id="system-overview" class="doc-section">
                <h2><i class="fas fa-info-circle"></i> System Overview</h2>
                <p>
                    HU CMS is a comprehensive clinical management system designed specifically for
                    Haramaya University's healthcare facilities. It provides digital solutions for
                    patient management, clinical workflows, pharmacy operations, and administrative tasks.
                </p>

                <h3>Key Components</h3>
                <div class="features-grid"
                    style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin: 2rem 0;">
                    <div style="background: var(--light-color); padding: 1.5rem; border-radius: var(--border-radius);">
                        <h4 style="color: var(--primary-color); margin-bottom: 1rem;">
                            <i class="fas fa-user-injured"></i> Patient Management
                        </h4>
                        <p>Complete patient lifecycle management from registration to discharge.</p>
                    </div>

                    <div style="background: var(--light-color); padding: 1.5rem; border-radius: var(--border-radius);">
                        <h4 style="color: var(--primary-color); margin-bottom: 1rem;">
                            <i class="fas fa-stethoscope"></i> Clinical Module
                        </h4>
                        <p>Electronic health records, prescriptions, and treatment planning.</p>
                    </div>

                    <div style="background: var(--light-color); padding: 1.5rem; border-radius: var(--border-radius);">
                        <h4 style="color: var(--primary-color); margin-bottom: 1rem;">
                            <i class="fas fa-pills"></i> Pharmacy Management
                        </h4>
                        <p>Inventory control, prescription dispensing, and drug safety.</p>
                    </div>
                </div>
            </section>

            <!-- User Guides -->
            <section id="user-guides" class="doc-section">
                <h2><i class="fas fa-users"></i> User Guides</h2>
                <p>Select your role to view relevant documentation and tutorials.</p>

                <div class="tab-container">
                    <div class="tab-buttons">
                        <button class="tab-btn active" data-tab="doctors">Doctors</button>
                        <button class="tab-btn" data-tab="nurses">Nurses</button>
                        <button class="tab-btn" data-tab="pharmacists">Pharmacists</button>
                        <button class="tab-btn" data-tab="admins">Administrators</button>
                        <button class="tab-btn" data-tab="reception">Reception</button>
                    </div>

                    <div class="tab-content">
                        <div id="doctors" class="tab-pane active">
                            <h3>Doctor's Guide</h3>
                            <p>Learn how to use HU CMS for clinical practice:</p>
                            <ul class="feature-list">
                                <li>Accessing patient medical records</li>
                                <li>Writing electronic prescriptions</li>
                                <li>Ordering laboratory tests</li>
                                <li>Documenting diagnoses and treatments</li>
                                <li>Viewing patient history</li>
                                <li>Managing appointments</li>
                            </ul>

                            <h4>Tutorial Videos</h4>
                            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: var(--border-radius);">
                                <p><i class="fas fa-video"></i> <a href="#">How to Write a Prescription</a> (5:23)</p>
                                <p><i class="fas fa-video"></i> <a href="#">Patient Record Management</a> (7:45)</p>
                                <p><i class="fas fa-video"></i> <a href="#">Lab Test Ordering</a> (4:12)</p>
                            </div>
                        </div>

                        <div id="nurses" class="tab-pane">
                            <h3>Nurse's Guide</h3>
                            <p>Instructions for nursing staff:</p>
                            <ul class="feature-list">
                                <li>Recording vital signs</li>
                                <li>Medication administration</li>
                                <li>Patient monitoring</li>
                                <li>Appointment scheduling</li>
                                <li>Treatment assistance</li>
                                <li>Patient education</li>
                            </ul>
                        </div>

                        <div id="pharmacists" class="tab-pane">
                            <h3>Pharmacist's Guide</h3>
                            <p>Pharmacy module usage guide:</p>
                            <ul class="feature-list">
                                <li>Prescription verification</li>
                                <li>Inventory management</li>
                                <li>Drug dispensing</li>
                                <li>Patient counseling</li>
                                <li>Stock control</li>
                                <li>Expiry management</li>
                            </ul>
                        </div>

                        <div id="admins" class="tab-pane">
                            <h3>Administrator's Guide</h3>
                            <p>System administration and management:</p>
                            <ul class="feature-list">
                                <li>User account management</li>
                                <li>System configuration</li>
                                <li>Report generation</li>
                                <li>Data backup and recovery</li>
                                <li>Security settings</li>
                                <li>System monitoring</li>
                            </ul>
                        </div>

                        <div id="reception" class="tab-pane">
                            <h3>Receptionist's Guide</h3>
                            <p>Front desk operations:</p>
                            <ul class="feature-list">
                                <li>Patient registration</li>
                                <li>Appointment scheduling</li>
                                <li>Check-in/check-out</li>
                                <li>Billing and payments</li>
                                <li>Patient communication</li>
                                <li>Record management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Installation Guide -->
            <section id="installation" class="doc-section">
                <h2><i class="fas fa-download"></i> Installation Guide</h2>
                <p>Follow these steps to install HU CMS on your server:</p>

                <div class="code-block">
                    <span class="comment"># Step 1: System Requirements Check</span><br>
                    <span class="keyword">Web Server:</span> Apache 2.4+ or Nginx 1.18+<br>
                    <span class="keyword">PHP:</span> 7.4 or higher<br>
                    <span class="keyword">Database:</span> MySQL 5.7+ or MariaDB 10.3+<br>
                    <span class="keyword">Extensions:</span> PDO, MySQLi, OpenSSL, cURL, GD<br><br>

                    <span class="comment"># Step 2: Download and Extract</span><br>
                    wget https://hu-cms.edu.et/download/latest.zip<br>
                    unzip latest.zip -d /var/www/html/hu-cms<br><br>

                    <span class="comment"># Step 3: Set Permissions</span><br>
                    chown -R www-data:www-data /var/www/html/hu-cms<br>
                    chmod -R 755 /var/www/html/hu-cms<br>
                </div>

                <div class="step-guide">
                    <h4>Database Setup</h4>
                    <ol>
                        <li>Create a new MySQL database: <code>CREATE DATABASE hu_cms;</code></li>
                        <li>Create a database user with privileges</li>
                        <li>Import the database schema from <code>database/schema.sql</code></li>
                        <li>Update database configuration in <code>includes/config.php</code></li>
                    </ol>
                </div>
            </section>

            <!-- API Documentation -->
            <section id="api-docs" class="doc-section">
                <h2><i class="fas fa-code"></i> API Documentation</h2>
                <p>HU CMS provides RESTful API endpoints for integration with other systems.</p>

                <h3>Authentication</h3>
                <div class="code-block">
                    <span class="comment">// API Authentication Endpoint</span><br>
                    <span class="keyword">POST</span> /api/auth/login<br>
                    <span class="keyword">Content-Type:</span> application/json<br><br>

                    <span class="comment">// Request Body</span><br>
                    {<br>
                    &nbsp;&nbsp;<span class="string">"email"</span>: <span class="string">"user@hu.edu.et"</span>,<br>
                    &nbsp;&nbsp;<span class="string">"password"</span>: <span class="string">"password123"</span><br>
                    }<br><br>

                    <span class="comment">// Response</span><br>
                    {<br>
                    &nbsp;&nbsp;<span class="string">"success"</span>: <span class="keyword">true</span>,<br>
                    &nbsp;&nbsp;<span class="string">"token"</span>: <span
                        class="string">"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."</span>,<br>
                    &nbsp;&nbsp;<span class="string">"user"</span>: { ... }<br>
                    }
                </div>

                <h3>Available Endpoints</h3>
                <div class="table-responsive">
                    <table class="doc-table">
                        <thead>
                            <tr>
                                <th>Endpoint</th>
                                <th>Method</th>
                                <th>Description</th>
                                <th>Authentication</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code>/api/patients</code></td>
                                <td>GET</td>
                                <td>List patients</td>
                                <td>Required</td>
                            </tr>
                            <tr>
                                <td><code>/api/patients/{id}</code></td>
                                <td>GET</td>
                                <td>Get patient details</td>
                                <td>Required</td>
                            </tr>
                            <tr>
                                <td><code>/api/appointments</code></td>
                                <td>POST</td>
                                <td>Create appointment</td>
                                <td>Required</td>
                            </tr>
                            <tr>
                                <td><code>/api/prescriptions</code></td>
                                <td>GET</td>
                                <td>List prescriptions</td>
                                <td>Required</td>
                            </tr>
                            <tr>
                                <td><code>/api/reports/generate</code></td>
                                <td>POST</td>
                                <td>Generate reports</td>
                                <td>Admin only</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p>For complete API documentation, visit <a href="../api/">API Reference</a>.</p>
            </section>

            <!-- Download Section -->
            <section class="download-section">
                <h2><i class="fas fa-download"></i> Download Resources</h2>
                <p>Access all documentation and resources in downloadable formats.</p>

                <div class="download-links">
                    <a href="downloads/user-manual.pdf" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-pdf"></i> Complete User Manual
                    </a>
                    <a href="downloads/quick-start.pdf" class="btn btn-outline" target="_blank">
                        <i class="fas fa-file-alt"></i> Quick Start Guide
                    </a>
                    <a href="downloads/api-specification.pdf" class="btn btn-outline" target="_blank">
                        <i class="fas fa-code"></i> API Specification
                    </a>
                    <a href="downloads/installation-guide.pdf" class="btn btn-outline" target="_blank">
                        <i class="fas fa-download"></i> Installation Guide
                    </a>
                </div>

                <p class="mt-3" style="color: var(--gray-color);">
                    <i class="fas fa-info-circle"></i> Need help? Contact support at
                    <a href="mailto:support@hu-cms.edu.et">support@hu-cms.edu.et</a>
                </p>
            </section>
        </main>
    </div>
</div>

<script src="js/documentation.js"></script>
