<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Contact Us - HU CMS";
$success = '';
$error = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = Helper::sanitize($_POST['name'] ?? '');
    $email = Helper::sanitize($_POST['email'] ?? '');
    $subject = Helper::sanitize($_POST['subject'] ?? '');
    $message = Helper::sanitize($_POST['message'] ?? '');
    $department = Helper::sanitize($_POST['department'] ?? 'general');

    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!Helper::isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real application, you would send an email or save to database
        // For now, we'll just show a success message
        $success = 'Thank you for your message! We will get back to you within 24 hours.';

        // Clear form data
        $_POST = [];
    }
}

include '../includes/header.php';
?>

<style>
    .contact-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
        color: white;
        padding: 5rem 0;
        text-align: center;
    }

    .contact-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        color: white;
    }

    .contact-hero .lead {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 2rem;
        opacity: 0.9;
    }

    .contact-container {
        padding: 4rem 0;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
    }

    .contact-info {
        padding: 2rem;
    }

    .contact-form-container {
        background: white;
        border-radius: var(--border-radius);
        padding: 2.5rem;
        box-shadow: var(--box-shadow);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--dark-color);
    }

    .form-label .required {
        color: var(--danger-color);
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 82, 118, 0.1);
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .contact-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .contact-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        text-align: center;
        transition: var(--transition);
    }

    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .contact-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .contact-card h3 {
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
    }

    .contact-card p {
        color: var(--gray-color);
        margin-bottom: 0.5rem;
    }

    .contact-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    .contact-link:hover {
        color: var(--secondary-color);
    }

    .department-hours {
        margin-top: 3rem;
        padding: 2rem;
        background: var(--light-color);
        border-radius: var(--border-radius);
    }

    .hours-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .hours-table th,
    .hours-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .hours-table th {
        font-weight: 600;
        color: var(--dark-color);
    }

    .success-message {
        background: #e8f5e9;
        color: #2e7d32;
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        border-left: 4px solid #4caf50;
    }

    .error-message {
        background: #ffebee;
        color: #c62828;
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        border-left: 4px solid #f44336;
    }

    .map-container {
        margin-top: 3rem;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .map-placeholder {
        height: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    @media (max-width: 992px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .contact-hero h1 {
            font-size: 2.5rem;
        }

        .contact-form-container {
            padding: 1.5rem;
        }

        .contact-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <h1>Contact Us</h1>
        <p class="lead">
            Get in touch with the HU CMS team for support, inquiries, or feedback about
            the Clinical Management System.
        </p>
        <div class="hero-buttons">
            <a href="#contact-form" class="btn btn-light btn-lg">
                <i class="fas fa-envelope"></i> Send Message
            </a>
            <a href="#support" class="btn btn-outline-light btn-lg">
                <i class="fas fa-headset"></i> Support Channels
            </a>
            <a href="#location" class="btn btn-outline-light btn-lg">
                <i class="fas fa-map-marker-alt"></i> Our Location
            </a>
        </div>
    </div>
</section>

<div class="container">
    <div class="contact-container">
        <?php if ($success): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Contact Information -->
            <div class="contact-info">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-info-circle"></i> Contact Information
                </h2>
                <p style="line-height: 1.7; color: var(--gray-color); margin-bottom: 2rem;">
                    Whether you need technical support, have questions about implementation,
                    or want to provide feedback, our team is here to help.
                </p>

                <div class="contact-cards">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3>Phone Support</h3>
                        <p>+251 (0) 255 530 000</p>
                        <p>Mon - Fri: 8:00 AM - 6:00 PM</p>
                        <a href="tel:+251255530000" class="contact-link">
                            <i class="fas fa-phone"></i> Call Now
                        </a>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email Support</h3>
                        <p>General Inquiries</p>
                        <p>info@hu-cms.edu.et</p>
                        <a href="mailto:info@hu-cms.edu.et" class="contact-link">
                            <i class="fas fa-envelope"></i> Send Email
                        </a>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>Technical Support</h3>
                        <p>System Issues & Bugs</p>
                        <p>support@hu-cms.edu.et</p>
                        <a href="mailto:support@hu-cms.edu.et" class="contact-link">
                            <i class="fas fa-life-ring"></i> Get Help
                        </a>
                    </div>
                </div>

                <!-- Department Hours -->
                <div id="support" class="department-hours">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-clock"></i> Support Hours by Department
                    </h3>
                    <table class="hours-table">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Hours</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Technical Support</td>
                                <td>24/7</td>
                                <td>support@hu-cms.edu.et</td>
                            </tr>
                            <tr>
                                <td>Administration</td>
                                <td>Mon-Fri: 8AM-5PM</td>
                                <td>admin@hu-cms.edu.et</td>
                            </tr>
                            <tr>
                                <td>Implementation Team</td>
                                <td>Mon-Fri: 9AM-4PM</td>
                                <td>implementation@hu-cms.edu.et</td>
                            </tr>
                            <tr>
                                <td>Training Department</td>
                                <td>Mon-Thu: 10AM-3PM</td>
                                <td>training@hu-cms.edu.et</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Contact Form -->
            <div id="contact-form" class="contact-form-container">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">
                    <i class="fas fa-paper-plane"></i> Send Us a Message
                </h2>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name" class="form-control" required
                            placeholder="Enter your full name"
                            value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-control" required
                            placeholder="Enter your email address"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="subject" class="form-label">
                            Subject <span class="required">*</span>
                        </label>
                        <input type="text" id="subject" name="subject" class="form-control" required
                            placeholder="Enter message subject"
                            value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="department" class="form-label">
                            Department
                        </label>
                        <select id="department" name="department" class="form-control">
                            <option value="general" <?php echo ($_POST['department'] ?? 'general') === 'general' ? 'selected' : ''; ?>>General Inquiry</option>
                            <option value="technical" <?php echo ($_POST['department'] ?? '') === 'technical' ? 'selected' : ''; ?>>Technical Support</option>
                            <option value="implementation" <?php echo ($_POST['department'] ?? '') === 'implementation' ? 'selected' : ''; ?>>Implementation</option>
                            <option value="training" <?php echo ($_POST['department'] ?? '') === 'training' ? 'selected' : ''; ?>>Training</option>
                            <option value="feedback" <?php echo ($_POST['department'] ?? '') === 'feedback' ? 'selected' : ''; ?>>Feedback & Suggestions</option>
                            <option value="partnership" <?php echo ($_POST['department'] ?? '') === 'partnership' ? 'selected' : ''; ?>>Partnership</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">
                            Message <span class="required">*</span>
                        </label>
                        <textarea id="message" name="message" class="form-control" required
                            placeholder="Type your message here..."><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>

                <p style="text-align: center; margin-top: 1.5rem; color: var(--gray-color); font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i> We typically respond within 24 hours on business days.
                </p>
            </div>
        </div>

        <!-- Location Map -->
        <div id="location" class="map-container">
            <div class="map-placeholder">
                <div style="text-align: center;">
                    <i class="fas fa-map-marker-alt" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h3 style="margin-bottom: 0.5rem;">Haramaya University</h3>
                    <p>College of Computing and Informatics</p>
                    <p>Dire Dawa, Ethiopia</p>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div
            style="margin-top: 4rem; padding: 3rem; background: var(--light-color); border-radius: var(--border-radius);">
            <h2 style="text-align: center; color: var(--primary-color); margin-bottom: 2rem;">
                <i class="fas fa-question-circle"></i> Frequently Asked Questions
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div>
                    <h4 style="color: var(--dark-color); margin-bottom: 0.5rem;">
                        <i class="fas fa-question"></i> How do I request access to HU CMS?
                    </h4>
                    <p style="color: var(--gray-color);">
                        Visit the <a href="../register.php">registration page</a> and fill out the access request form.
                        Your request will be reviewed by the administration team.
                    </p>
                </div>

                <div>
                    <h4 style="color: var(--dark-color); margin-bottom: 0.5rem;">
                        <i class="fas fa-question"></i> What if I forget my password?
                    </h4>
                    <p style="color: var(--gray-color);">
                        Use the <a href="../forgot-password.php">password reset</a> feature on the login page.
                        You'll receive an email with reset instructions.
                    </p>
                </div>

                <div>
                    <h4 style="color: var(--dark-color); margin-bottom: 0.5rem;">
                        <i class="fas fa-question"></i> Is training available for new users?
                    </h4>
                    <p style="color: var(--gray-color);">
                        Yes! We provide comprehensive training sessions. Contact
                        <a href="mailto:training@hu-cms.edu.et">training@hu-cms.edu.et</a> to schedule training.
                    </p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="documentation.php#faq" class="btn btn-outline">
                    <i class="fas fa-book"></i> View More FAQs
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="margin-top: 3rem; text-align: center;">
            <h3 style="color: var(--primary-color); margin-bottom: 1.5rem;">Quick Links</h3>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="../index.php" class="btn btn-outline">
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="features.php" class="btn btn-outline">
                    <i class="fas fa-star"></i> Features
                </a>
                <a href="documentation.php" class="btn btn-outline">
                    <i class="fas fa-book"></i> Documentation
                </a>
                <a href="requirements.php" class="btn btn-outline">
                    <i class="fas fa-server"></i> Requirements
                </a>
                <a href="team.php" class="btn btn-outline">
                    <i class="fas fa-users"></i> Our Team
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Contact form validation
    document.addEventListener('DOMContentLoaded', function () {
        const contactForm = document.querySelector('form');

        if (contactForm) {
            contactForm.addEventListener('submit', function (e) {
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const subject = document.getElementById('subject').value.trim();
                const message = document.getElementById('message').value.trim();

                // Basic validation
                if (!name || !email || !subject || !message) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    alert('Please enter a valid email address.');
                    return false;
                }

                // If all validations pass, show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                submitBtn.disabled = true;

                // Re-enable after 5 seconds (in case of error)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    window.scrollTo({
                        top: targetElement.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>

