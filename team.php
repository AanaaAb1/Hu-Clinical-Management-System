<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$page_title = "Development Team - HU CMS";
include '../includes/header.php';

// Team members data
$team_members = [
    [
        'name' => 'Aanaa Abdella Mume',
        'id' => '0399/16',
        'role' => 'Project Manager & System Analyst',
        'department' => 'Information Systems',
        'email' => 'aanaa.abdella@hu.edu.et',
        'skills' => ['Project Management', 'Requirements Analysis', 'System Design'],
        'avatar_color' => '#1a5276'
    ],
    [
        'name' => 'Abdisa Yusuf',
        'id' => '0431/16',
        'role' => 'Lead Developer',
        'department' => 'Software Engineering',
        'email' => 'abdisa.yusuf@hu.edu.et',
        'skills' => ['PHP Development', 'Database Design', 'API Development'],
        'avatar_color' => '#2e86c1'
    ],
    [
        'name' => 'Abduzahid Abdi',
        'id' => '0517/16',
        'role' => 'Full Stack Developer',
        'department' => 'Computer Science',
        'email' => 'abduzahid.abdi@hu.edu.et',
        'skills' => ['Frontend Development', 'UI/UX Design', 'Testing'],
        'avatar_color' => '#3498db'
    ],
    [
        'name' => 'Emado Awal',
        'id' => '1132/16',
        'role' => 'Database Administrator & Security Specialist',
        'department' => 'Information Technology',
        'email' => 'emado.awal@hu.edu.et',
        'skills' => ['Database Management', 'Security', 'System Administration'],
        'avatar_color' => '#2c3e50'
    ]
];

// Project advisors
$advisors = [
    [
        'name' => 'Dr. Samuel Teshome',
        'role' => 'Project Supervisor',
        'department' => 'College of Computing and Informatics',
        'qualification' => 'PhD in Information Systems'
    ],
    [
        'name' => 'Prof. Helen Mengistu',
        'role' => 'Healthcare Consultant',
        'department' => 'College of Health Sciences',
        'qualification' => 'MD, MPH'
    ],
    [
        'name' => 'Eng. Michael Asrat',
        'role' => 'Technical Advisor',
        'department' => 'IT Department',
        'qualification' => 'MSc in Software Engineering'
    ]
];
?>

<style>
    .team-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
        color: white;
        padding: 5rem 0;
        text-align: center;
    }

    .team-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        color: white;
    }

    .team-hero .lead {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 2rem;
        opacity: 0.9;
    }

    .team-container {
        padding: 4rem 0;
    }

    .team-section {
        margin-bottom: 4rem;
    }

    .section-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-header h2 {
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .section-header p {
        color: var(--gray-color);
        max-width: 700px;
        margin: 0 auto;
        font-size: 1.1rem;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .team-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        text-align: center;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .team-name {
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        font-size: 1.5rem;
    }

    .team-id {
        color: var(--gray-color);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .team-role {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .team-department {
        color: var(--gray-color);
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .team-skills {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .skill-tag {
        background: var(--light-color);
        color: var(--dark-color);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .team-contact {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f0f0;
    }

    .contact-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .contact-link:hover {
        color: var(--secondary-color);
    }

    .advisor-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--box-shadow);
        text-align: center;
        border-top: 4px solid var(--success-color);
    }

    .advisor-name {
        color: var(--dark-color);
        font-size: 1.3rem;
        margin-bottom: 0.5rem;
    }

    .advisor-role {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .advisor-department {
        color: var(--gray-color);
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .advisor-qualification {
        color: var(--success-color);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .university-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e8f4f8 100%);
        padding: 3rem;
        border-radius: var(--border-radius);
        margin-top: 3rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .info-item {
        text-align: center;
        padding: 1.5rem;
    }

    .info-item i {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .info-item h4 {
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .info-item p {
        color: var(--gray-color);
        margin-bottom: 0;
    }

    .project-details {
        margin-top: 4rem;
        padding: 3rem;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .timeline {
        position: relative;
        max-width: 800px;
        margin: 2rem auto;
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
        background: var(--light-color);
        padding: 1.5rem;
        border-radius: var(--border-radius);
    }

    @media (max-width: 768px) {
        .team-hero h1 {
            font-size: 2.5rem;
        }

        .team-grid {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Hero Section -->
<section class="team-hero">
    <div class="container">
        <h1>Meet Our Development Team</h1>
        <p class="lead">
            The talented students and advisors behind the Haramaya University
            Clinical Management System project.
        </p>
        <div class="hero-buttons">
            <a href="#development-team" class="btn btn-light btn-lg">
                <i class="fas fa-users"></i> Development Team
            </a>
            <a href="#project-details" class="btn btn-outline-light btn-lg">
                <i class="fas fa-info-circle"></i> Project Details
            </a>
            <a href="#university" class="btn btn-outline-light btn-lg">
                <i class="fas fa-university"></i> University Info
            </a>
        </div>
    </div>
</section>

<div class="container">
    <div class="team-container">
        <!-- Development Team -->
        <section id="development-team" class="team-section">
            <div class="section-header">
                <h2><i class="fas fa-users"></i> Development Team</h2>
                <p>
                    Bachelor of Science students from the Department of Information System,
                    College of Computing and Informatics, Haramaya University
                </p>
            </div>

            <div class="team-grid">
                <?php foreach ($team_members as $member): ?>
                    <div class="team-card">
                        <div class="team-avatar" style="background: <?php echo $member['avatar_color']; ?>;">
                            <?php
                            $names = explode(' ', $member['name']);
                            $initials = strtoupper(substr($names[0], 0, 1) . substr(end($names), 0, 1));
                            echo $initials;
                            ?>
                        </div>

                        <h3 class="team-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <div class="team-id">ID: <?php echo htmlspecialchars($member['id']); ?></div>
                        <div class="team-role"><?php echo htmlspecialchars($member['role']); ?></div>
                        <div class="team-department"><?php echo htmlspecialchars($member['department']); ?></div>

                        <div class="team-skills">
                            <?php foreach ($member['skills'] as $skill): ?>
                                <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>

                        <div class="team-contact">
                            <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="contact-link">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Project Advisors -->
        <section class="team-section">
            <div class="section-header">
                <h2><i class="fas fa-user-graduate"></i> Project Advisors</h2>
                <p>Expert guidance and supervision from faculty members</p>
            </div>

            <div class="team-grid">
                <?php foreach ($advisors as $advisor): ?>
                    <div class="advisor-card">
                        <h3 class="advisor-name"><?php echo htmlspecialchars($advisor['name']); ?></h3>
                        <div class="advisor-role"><?php echo htmlspecialchars($advisor['role']); ?></div>
                        <div class="advisor-department"><?php echo htmlspecialchars($advisor['department']); ?></div>
                        <div class="advisor-qualification">
                            <i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($advisor['qualification']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Project Details -->
        <section id="project-details" class="project-details">
            <div class="section-header">
                <h2><i class="fas fa-info-circle"></i> Project Details</h2>
                <p>Information about the Clinical Management System project</p>
            </div>

            <div class="row"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-book"></i> Course Information
                    </h4>
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <strong>Course:</strong> Requirement Engineering
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <strong>Course Code:</strong> IS 4032
                        </li>
                        <li style="padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <strong>Academic Year:</strong> 2023/2024
                        </li>
                        <li style="padding: 8px 0;">
                            <strong>Semester:</strong> Second Semester
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem;">
                        <i class="fas fa-calendar-alt"></i> Project Timeline
                    </h4>
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">January 2024</div>
                            <div class="timeline-content">
                                <strong>Project Initiation</strong><br>
                                Requirements gathering and planning
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-date">February - March 2024</div>
                            <div class="timeline-content">
                                <strong>Development Phase</strong><br>
                                System design and implementation
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-date">April 2024</div>
                            <div class="timeline-content">
                                <strong>Testing & Documentation</strong><br>
                                Quality assurance and documentation
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-date">May 2024</div>
                            <div class="timeline-content">
                                <strong>Deployment & Presentation</strong><br>
                                System deployment and project defense
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="documentation.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-book"></i> View Project Documentation
                </a>
            </div>
        </section>

        <!-- University Information -->
        <section id="university" class="university-info">
            <div class="section-header">
                <h2><i class="fas fa-university"></i> Haramaya University</h2>
                <p>Building the Basis for Development</p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>College of Computing and Informatics</h4>
                    <p>Leading institution for ICT education and research in Ethiopia</p>
                </div>

                <div class="info-item">
                    <i class="fas fa-laptop-code"></i>
                    <h4>Department of Information System</h4>
                    <p>Specializing in information systems design and implementation</p>
                </div>

                <div class="info-item">
                    <i class="fas fa-heartbeat"></i>
                    <h4>College of Health Sciences</h4>
                    <p>Partnering for healthcare innovation and digital transformation</p>
                </div>
            </div>

            <div style="text-align: center; margin-top: 2rem;">
                <a href="https://www.haramaya.edu.et" target="_blank" class="btn btn-outline btn-lg">
                    <i class="fas fa-external-link-alt"></i> Visit University Website
                </a>
            </div>
        </section>

        <!-- Contact Team -->
        <div class="cta-box"
            style="background: linear-gradient(135deg, var(--primary-color), var(--dark-color)); color: white; padding: 3rem; border-radius: var(--border-radius); margin-top: 3rem; text-align: center;">
            <h2 style="color: white;">Contact the Development Team</h2>
            <p class="lead" style="opacity: 0.9; margin-bottom: 2rem;">
                Have questions or feedback about the Clinical Management System?
            </p>
            <div class="hero-buttons">
                <a href="contact.php" class="btn btn-light btn-lg">
                    <i class="fas fa-envelope"></i> Send Message
                </a>
                <a href="features.php" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-star"></i> Explore Features
                </a>
            </div>
        </div>
    </div>
</div>

