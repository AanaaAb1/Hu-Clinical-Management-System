<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "System Requirements - HU CMS";
include '../includes/header.php';
?>

<style>
    .requirements-hero {
        background: linear-gradient(135deg, #1a5276, #2c3e50);
        color: white;
        padding: 5rem 0;
        text-align: center;
    }

    .requirements-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        color: white;
    }

    .requirements-hero .lead {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 2rem;
        opacity: 0.9;
    }

    .requirements-container {
        padding: 4rem 0;
    }

    .requirements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .requirement-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        border-top: 4px solid var(--accent-color);
    }

    .requirement-card:hover {
        transform: translateY(-5px);
    }

    .requirement-icon {
        font-size: 2.5rem;
        color: var(--accent-color);
        margin-bottom: 1.5rem;
    }

    .requirement-card h3 {
        color: var(--dark-color);
        margin-bottom: 1rem;
    }

    .requirement-card p {
        color: var(--gray-color);
        margin-bottom: 1.5rem;
        line-height: 1.7;
    }

    .requirement-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirement-list li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .requirement-list li:last-child {
        border-bottom: none;
    }

    .requirement-list .meets {
        color: var(--success-color);
    }

    .requirement-list .not-met {
        color: var(--danger-color);
    }

    .comparison-section {
        background: var(--light-color);
        padding: 3rem 0;
    }

    .table-container {
        overflow-x: auto;
        margin: 2rem 0;
    }

    .specs-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .specs-table th,
    .specs-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }

    .specs-table th {
        background: var(--primary-color);
        color: white;
        font-weight: 600;
    }

    .specs-table tr:hover {
        background: #f9f9f9;
    }

    .specs-table .check {
        color: var(--success-color);
        font-weight: bold;
    }

    .specs-table .cross {
        color: var(--danger-color);
        font-weight: bold;
    }

    .timeline {
        position: relative;
        max-width: 800px;
        margin: 3rem auto;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary-color);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 2rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--primary-color);
    }

    .timeline-date {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .timeline-content {
        background: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .cta-box {
        text-align: center;
        padding: 3rem;
        background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
        color: white;
        border-radius: var(--border-radius);
        margin-top: 3rem;
    }

    .cta-box h2 {
        color: white;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .requirements-hero h1 {
            font-size: 2.5rem;
        }

        .requirements-grid {
            grid-template-columns: 1fr;
        }

        .table-container {
            font-size: 0.9rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="requirements-hero">
    <div class="container">
        <h1>System Requirements & Specifications</h1>
        <p class="lead">
            Technical specifications, hardware requirements, and software prerequisites for
            implementing HU CMS in your healthcare facility.
        </p>
        <div class="hero-buttons">
            <a href="#technical" class="btn btn-light btn-lg">
                <i class="fas fa-server"></i> Technical Requirements
            </a>
            <a href="#functional" class="btn btn-outline-light btn-lg">
                <i class="fas fa-tasks"></i> Functional Requirements
            </a>
            <a href="#implementation" class="btn btn-outline-light btn-lg">
                <i class="fas fa-calendar-alt"></i> Implementation Plan
            </a>
        </div>
    </div>
</section>

<div class="container">
    <div class="requirements-container">
        <!-- Technical Requirements -->
        <section id="technical" class="mb-5">
            <h2 class="text-center mb-4" style="color: var(--primary-color);">
                <i class="fas fa-server"></i> Technical Requirements
            </h2>

            <div class="requirements-grid">
                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-desktop"></i>
                    </div>
                    <h3>Client Requirements</h3>
                    <p>Minimum specifications for end-user devices</p>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check-circle meets"></i> Modern web browser (Chrome 60+, Firefox 55+)</li>
                        <li><i class="fas fa-check-circle meets"></i> 4GB RAM minimum</li>
                        <li><i class="fas fa-check-circle meets"></i> 1280x720 screen resolution</li>
                        <li><i class="fas fa-check-circle meets"></i> JavaScript enabled</li>
                        <li><i class="fas fa-check-circle meets"></i> Internet connection (1 Mbps+)</li>
                    </ul>
                </div>

                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3>Server Requirements</h3>
                    <p>Server specifications for system hosting</p>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check-circle meets"></i> Apache 2.4+ or Nginx 1.18+</li>
                        <li><i class="fas fa-check-circle meets"></i> PHP 7.4+ with extensions</li>
                        <li><i class="fas fa-check-circle meets"></i> MySQL 5.7+ or MariaDB 10.3+</li>
                        <li><i class="fas fa-check-circle meets"></i> 8GB RAM minimum (16GB recommended)</li>
                        <li><i class="fas fa-check-circle meets"></i> 100GB storage with RAID</li>
                    </ul>
                </div>

                <div class="requirement-card">
                    <div class="requirement-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security Requirements</h3>
                    <p>Security specifications and compliance</p>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check-circle meets"></i> SSL/TLS encryption</li>
                        <li><i class="fas fa-check-circle meets"></i> Firewall configuration</li>
                        <li><i class="fas fa-check-circle meets"></i> Regular security updates</li>
                        <li><i class="fas fa-check-circle meets"></i> Data backup system</li>
                        <li><i class="fas fa-check-circle meets"></i> Access control policies</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Detailed Specifications -->
        <section class="comparison-section">
            <div class="container">
                <h2 class="text-center mb-4" style="color: var(--primary-color);">
                    <i class="fas fa-list-alt"></i> Detailed Specifications
                </h2>

                <div class="table-container">
                    <table class="specs-table">
                        <thead>
                            <tr>
                                <th>Component</th>
                                <th>Minimum</th>
                                <th>Recommended</th>
                                <th>Enterprise</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>CPU Cores</strong></td>
                                <td>2 cores</td>
                                <td>4 cores</td>
                                <td>8+ cores</td>
                            </tr>
                            <tr>
                                <td><strong>Memory</strong></td>
                                <td>8GB RAM</td>
                                <td>16GB RAM</td>
                                <td>32GB+ RAM</td>
                            </tr>
                            <tr>
                                <td><strong>Storage</strong></td>
                                <td>100GB HDD</td>
                                <td>500GB SSD</td>
                                <td>1TB SSD RAID</td>
                            </tr>
                            <tr>
                                <td><strong>Bandwidth</strong></td>
                                <td>10 Mbps</td>
                                <td>100 Mbps</td>
                                <td>1 Gbps</td>
                            </tr>
                            <tr>
                                <td><strong>Concurrent Users</strong></td>
                                <td>Up to 50</td>
                                <td>50-200</td>
                                <td>200+</td>
                            </tr>
                            <tr>
                                <td><strong>Data Retention</strong></td>
                                <td>3 years</td>
                                <td>7 years</td>
                                <td>10+ years</td>
                            </tr>
                            <tr>
                                <td><strong>Uptime SLA</strong></td>
                                <td>95%</td>
                                <td>99%</td>
                                <td>99.9%</td>
                            </tr>
                            <tr>
                                <td><strong>Support</strong></td>
                                <td>Business hours</td>
                                <td>24/5</td>
                                <td>24/7</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Functional Requirements -->
        <section id="functional" class="mt-5">
            <h2 class="text-center mb-4" style="color: var(--primary-color);">
                <i class="fas fa-tasks"></i> Functional Requirements
            </h2>

            <div class="requirements-grid">
                <div class="requirement-card">
                    <h3>Patient Management</h3>
                    <ul class="requirement-list">
                        <li>Patient registration with demographic data</li>
                        <li>Medical history recording</li>
                        <li>Insurance information management</li>
                        <li>Emergency contact details</li>
                        <li>Allergy and medication tracking</li>
                    </ul>
                </div>

                <div class="requirement-card">
                    <h3>Clinical Functions</h3>
                    <ul class="requirement-list">
                        <li>Electronic health records</li>
                        <li>Prescription management</li>
                        <li>Lab test ordering and results</li>
                        <li>Treatment planning</li>
                        <li>Progress notes documentation</li>
                    </ul>
                </div>

                <div class="requirement-card">
                    <h3>Administrative Functions</h3>
                    <ul class="requirement-list">
                        <li>Appointment scheduling</li>
                        <li>Billing and insurance claims</li>
                        <li>Inventory management</li>
                        <li>Reporting and analytics</li>
                        <li>User management</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Non-Functional Requirements -->
        <section class="mt-5">
            <h2 class="text-center mb-4" style="color: var(--primary-color);">
                <i class="fas fa-chart-line"></i> Non-Functional Requirements
            </h2>

            <div class="requirements-grid">
                <div class="requirement-card">
                    <h3>Performance</h3>
                    <ul class="requirement-list">
                        <li>Page load time: &lt; 3 seconds</li>
                        <li>Response time: &lt; 1 second for 95% requests</li>
                        <li>Support 100+ concurrent users</li>
                        <li>Database query optimization</li>
                        <li>Caching implementation</li>
                    </ul>
                </div>

                <div class="requirement-card">
                    <h3>Security</h3>
                    <ul class="requirement-list">
                        <li>Role-based access control</li>
                        <li>Data encryption at rest and in transit</li>
                        <li>Audit logging</li>
                        <li>Regular security updates</li>
                        <li>Compliance with healthcare regulations</li>
                    </ul>
                </div>

                <div class="requirement-card">
                    <h3>Usability</h3>
                    <ul class="requirement-list">
                        <li>Intuitive user interface</li>
                        <li>Responsive design for all devices</li>
                        <li>Multilingual support</li>
                        <li>Accessibility compliance</li>
                        <li>Comprehensive help system</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Implementation Timeline -->
        <section id="implementation" class="mt-5">
            <h2 class="text-center mb-4" style="color: var(--primary-color);">
                <i class="fas fa-calendar-alt"></i> Implementation Timeline
            </h2>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-date">Week 1-2</div>
                    <div class="timeline-content">
                        <h4>Planning & Assessment</h4>
                        <p>Requirements gathering, infrastructure assessment, and project planning</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-date">Week 3-4</div>
                    <div class="timeline-content">
                        <h4>Infrastructure Setup</h4>
                        <p>Server setup, software installation, and network configuration</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-date">Week 5-6</div>
                    <div class="timeline-content">
                        <h4>System Configuration</h4>
                        <p>Database setup, user roles configuration, and system customization</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-date">Week 7-8</div>
                    <div class="timeline-content">
                        <h4>Training & Testing</h4>
                        <p>User training, system testing, and quality assurance</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-date">Week 9-10</div>
                    <div class="timeline-content">
                        <h4>Go-Live & Support</h4>
                        <p>System deployment, data migration, and post-implementation support</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <div class="cta-box">
            <h2>Ready to Implement HU CMS?</h2>
            <p class="lead" style="opacity: 0.9; margin-bottom: 2rem;">
                Contact our team for a detailed requirements assessment and implementation plan.
            </p>
            <div class="hero-buttons">
                <a href="contact.php" class="btn btn-light btn-lg">
                    <i class="fas fa-phone-alt"></i> Contact Implementation Team
                </a>
                <a href="documentation.php#installation" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-download"></i> Download Implementation Guide
                </a>
            </div>
        </div>
    </div>
</div>

