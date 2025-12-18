<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "About HU CMS";
include '../includes/header.php';
?>

<style>
    /* Background with medical pattern */
    .about-background {
        background: 
            linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.98)),
            url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
        min-height: 100vh;
        position: relative;
    }
    
    /* Modern card design */
    .about-card {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #eaeaea;
        margin-bottom: 2rem;
        transition: transform 0.3s ease;
    }
    
    .about-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    }
    
    .about-card h2 {
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    
    /* Mission section with icon */
    .mission-icon {
        font-size: 5rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        opacity: 0.8;
    }
    
    /* Stats grid */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }
    
    .stat-box {
        text-align: center;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa, #ffffff);
        border-radius: 10px;
        border: 1px solid #eaeaea;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-color);
        display: block;
    }
    
    .stat-label {
        color: var(--gray-color);
        font-size: 0.9rem;
    }
    
    /* Values grid */
    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }
    
    .value-item {
        text-align: center;
        padding: 1.5rem;
    }
    
    .value-icon {
        width: 70px;
        height: 70px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
    }
    
    /* Quick navigation */
    .quick-nav {
        background: var(--primary-color);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin: 2rem 0;
    }
    
    .quick-nav h3 {
        color: white;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .nav-links {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .nav-links a {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        padding: 0.8rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .nav-links a:hover {
        background: white;
        color: var(--primary-color);
        transform: translateY(-2px);
    }
</style>

<div class="about-background">
    <div class="container" style="padding: 4rem 0;">
        
        <!-- Main About Section -->
        <div class="about-card text-center">
            <h1 style="color: var(--primary-color); margin-bottom: 1rem;">
                About Haramaya University Clinical Management System
            </h1>
            <p class="lead" style="color: var(--gray-color); max-width: 800px; margin: 0 auto 2rem;">
                Transforming healthcare delivery through digital innovation and efficient clinical management
            </p>
            
            <div class="mission-icon">
                <i class="fas fa-hospital"></i>
            </div>
        </div>
        
        <!-- Mission Section -->
        <div class="about-card">
            <h2><i class="fas fa-bullseye"></i> Our Mission & Vision</h2>
            <div style="line-height: 1.8; font-size: 1.1rem;">
                <p>The Haramaya University Clinical Management System (HU-CMS) is a comprehensive digital platform designed to revolutionize healthcare delivery across our medical facilities. Our mission is to:</p>
                
                <ul style="padding-left: 1.5rem; margin: 1.5rem 0;">
                    <li style="margin-bottom: 0.8rem;">Enhance patient care through technology-driven solutions</li>
                    <li style="margin-bottom: 0.8rem;">Improve clinical efficiency and reduce administrative burdens</li>
                    <li style="margin-bottom: 0.8rem;">Support medical education and research initiatives</li>
                    <li>Provide secure, accessible, and integrated healthcare services</li>
                </ul>
                
                <p>By transitioning from manual paper-based systems to a fully digital platform, we aim to eliminate errors, reduce waiting times, and empower healthcare professionals with modern tools for exceptional patient care.</p>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="about-card">
            <h2><i class="fas fa-chart-line"></i> Our Impact</h2>
            <div class="stats-container">
                <div class="stat-box">
                    <span class="stat-number" data-target="5000">0</span>
                    <span class="stat-label">Patients Served Annually</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number" data-target="150">0</span>
                    <span class="stat-label">Medical Professionals</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number" data-target="99">0</span>
                    <span class="stat-label">Patient Satisfaction Rate</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number" data-target="24">0</span>
                    <span class="stat-label">Integrated Departments</span>
                </div>
            </div>
        </div>
        
        <!-- Core Values -->
        <div class="about-card">
            <h2><i class="fas fa-heart"></i> Our Core Values</h2>
            <div class="values-grid">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Patient-Centered Care</h4>
                    <p style="color: var(--gray-color);">We prioritize patient well-being and comfort in all our services.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h4>Clinical Excellence</h4>
                    <p style="color: var(--gray-color);">We maintain the highest standards in medical practice and innovation.</p>
                </div>
                
                <div class="value-item">
                    <div class="value-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Education & Research</h4>
                    <p style="color: var(--gray-color);">We foster learning and advance healthcare through research.</p>
                </div>
            </div>
        </div>
        
        <!-- System Features -->
        <div class="about-card">
            <h2><i class="fas fa-cogs"></i> Key System Features</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                <div>
                    <h4 style="color: var(--primary-color);">
                        <i class="fas fa-user-circle"></i> Patient Management
                    </h4>
                    <ul style="color: var(--gray-color);">
                        <li>Digital registration and records</li>
                        <li>Appointment scheduling</li>
                        <li>Medical history tracking</li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="color: var(--primary-color);">
                        <i class="fas fa-stethoscope"></i> Clinical Tools
                    </h4>
                    <ul style="color: var(--gray-color);">
                        <li>Electronic Health Records</li>
                        <li>Lab results integration</li>
                        <li>Prescription management</li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="color: var(--primary-color);">
                        <i class="fas fa-chart-pie"></i> Analytics & Reporting
                    </h4>
                    <ul style="color: var(--gray-color);">
                        <li>Real-time dashboards</li>
                        <li>Performance metrics</li>
                        <li>Research data analysis</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Quick Navigation -->
        <div class="quick-nav">
            <h3>Explore More</h3>
           
        </div>
        
        <!-- Call to Action -->
        <div class="about-card text-center">
            <h2>Get Started Today</h2>
            <p style="color: var(--gray-color); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Join us in revolutionizing healthcare delivery at Haramaya University
            </p>
            <div>
                <a href="contact.php" class="btn btn-primary btn-lg" style="margin: 0.5rem;">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
                <a href="login.php" class="btn btn-outline btn-lg" style="margin: 0.5rem;">
                    <i class="fas fa-sign-in-alt"></i> Login to System
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Animated counters
    function animateCounters() {
        const counters = document.querySelectorAll('.stat-number');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            let current = 0;
            const increment = target / 50;
            
            const updateCounter = () => {
                if (current < target) {
                    current += increment;
                    counter.textContent = Math.ceil(current);
                    setTimeout(updateCounter, 30);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Start counter animation
        setTimeout(animateCounters, 500);
        
        // Add hover effect to cards
        document.querySelectorAll('.about-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 15px 50px rgba(0, 0, 0, 0.1)';
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 10px 40px rgba(0, 0, 0, 0.08)';
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>

