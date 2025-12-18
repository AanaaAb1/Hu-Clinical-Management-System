-- Create database
CREATE DATABASE IF NOT EXISTS haramaya_cms;
USE haramaya_cms;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    user_role ENUM('admin', 'doctor', 'nurse', 'patient') DEFAULT 'patient',
    department VARCHAR(100),
    specialization VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255),
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status)
);

-- Insert users with exact credentials you provided
INSERT IGNORE INTO users (email, password, full_name, user_role, department, status) VALUES
('admin@hu.edu.et', 'admin123', 'System Administrator', 'admin', 'Administration', 'active'),
('doctor@hu.edu.et', 'doctor123', 'Dr. John Smith', 'doctor', 'Cardiology', 'active'),
('nurse@hu.edu.et', 'nurse123', 'Nurse Jane Doe', 'nurse', 'Emergency Room', 'active');

-- Update existing users to ensure correct credentials
UPDATE users SET password = 'admin123', status = 'active' WHERE email = 'admin@hu.edu.et';
UPDATE users SET password = 'doctor123', status = 'active' WHERE email = 'doctor@hu.edu.et';
UPDATE users SET password = 'nurse123', status = 'active' WHERE email = 'nurse@hu.edu.et';

-- Verify the inserted data
SELECT * FROM users;

-- Patients Table
CREATE TABLE IF NOT EXISTS patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    date_of_birth DATE NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    blood_type VARCHAR(5),
    allergies TEXT,
    medical_history TEXT,
    insurance_provider VARCHAR(100),
    insurance_number VARCHAR(50),
    status ENUM('active', 'inactive', 'deceased') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_patient_id (patient_id),
    INDEX idx_name (last_name, first_name),
    INDEX idx_phone (phone)
);

-- Appointments Table
CREATE TABLE IF NOT EXISTS appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id VARCHAR(20) UNIQUE NOT NULL,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration INT DEFAULT 30, -- in minutes
    reason VARCHAR(255),
    status ENUM('scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_date_status (appointment_date, status),
    INDEX idx_doctor_date (doctor_id, appointment_date)
);

-- Medical Records Table
CREATE TABLE IF NOT EXISTS medical_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    record_id VARCHAR(20) UNIQUE NOT NULL,
    patient_id INT NOT NULL,
    visit_date DATE NOT NULL,
    doctor_id INT NOT NULL,
    chief_complaint TEXT,
    diagnosis TEXT,
    treatment TEXT,
    prescription_id INT,
    lab_test_id INT,
    vital_signs JSON, -- Stores BP, temp, pulse, etc.
    notes TEXT,
    follow_up_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id),
    INDEX idx_patient_date (patient_id, visit_date),
    INDEX idx_doctor (doctor_id)
);

-- Prescriptions Table
CREATE TABLE IF NOT EXISTS prescriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prescription_id VARCHAR(20) UNIQUE NOT NULL,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    medication_name VARCHAR(100) NOT NULL,
    dosage VARCHAR(50),
    frequency VARCHAR(50),
    duration VARCHAR(50),
    instructions TEXT,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    prescribed_date DATE NOT NULL,
    dispensed_date DATE,
    dispensed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id),
    FOREIGN KEY (dispensed_by) REFERENCES users(id),
    INDEX idx_patient_status (patient_id, status),
    INDEX idx_doctor (doctor_id)
);

-- Laboratory Tests Table
CREATE TABLE IF NOT EXISTS lab_tests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    test_id VARCHAR(20) UNIQUE NOT NULL,
    patient_id INT NOT NULL,
    requested_by INT NOT NULL,
    test_type VARCHAR(100) NOT NULL,
    test_name VARCHAR(100) NOT NULL,
    specimen_type VARCHAR(50),
    collection_date DATE,
    result TEXT,
    result_date DATE,
    reviewed_by INT,
    status ENUM('requested', 'collected', 'processing', 'completed', 'cancelled') DEFAULT 'requested',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES users(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id),
    INDEX idx_patient_status (patient_id, status),
    INDEX idx_test_type (test_type)
);

-- Billing Table
CREATE TABLE IF NOT EXISTS billing (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id VARCHAR(20) UNIQUE NOT NULL,
    patient_id INT NOT NULL,
    appointment_id INT,
    billing_date DATE NOT NULL,
    due_date DATE,
    total_amount DECIMAL(10,2) NOT NULL,
    paid_amount DECIMAL(10,2) DEFAULT 0,
    balance DECIMAL(10,2) GENERATED ALWAYS AS (total_amount - paid_amount) STORED,
    payment_status ENUM('paid', 'partial', 'unpaid', 'cancelled') DEFAULT 'unpaid',
    payment_method VARCHAR(50),
    payment_date DATE,
    items JSON, -- Stores bill items as JSON array
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_patient_status (patient_id, payment_status),
    INDEX idx_invoice_date (billing_date)
);

-- Inventory Table (Pharmacy)
CREATE TABLE IF NOT EXISTS inventory (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_code VARCHAR(50) UNIQUE NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    unit VARCHAR(20),
    quantity INT NOT NULL DEFAULT 0,
    reorder_level INT DEFAULT 10,
    unit_price DECIMAL(10,2) NOT NULL,
    supplier VARCHAR(100),
    expiry_date DATE,
    location VARCHAR(50),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_quantity (quantity),
    INDEX idx_expiry (expiry_date)
);

-- Activity Logs Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    activity VARCHAR(255) NOT NULL,
    module VARCHAR(50),
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user_date (user_id, created_at),
    INDEX idx_module (module)
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(20) DEFAULT 'string',
    category VARCHAR(50),
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category)
);

-- Insert sample settings ONLY if not exists
INSERT IGNORE INTO settings (setting_key, setting_value, category, description) VALUES
('site_name', 'Haramaya University CMS', 'general', 'Website name'),
('site_email', 'info@hu-cms.edu.et', 'general', 'Default email address'),
('appointment_duration', '30', 'appointments', 'Default appointment duration in minutes'),
('currency', 'ETB', 'billing', 'Default currency');

-- Show all created tables
SHOW TABLES;
-- Insert users with correct credentials
INSERT INTO users (email, password, full_name, user_role, department, status, created_at) VALUES
('admin@hu.edu.et', 'admin123', 'System Administrator', 'admin', 'Administration', 'active', NOW()),
('doctor@hu.edu.et', 'doctor123', 'Dr. John Smith', 'doctor', 'Cardiology', 'active', NOW()),
('nurse@hu.edu.et', 'nurse123', 'Nurse Jane Doe', 'nurse', 'Emergency Room', 'active', NOW());

-- Verify the users were created
SELECT id, email, password, full_name, user_role, status FROM users WHERE email IN ('admin@hu.edu.et', 'doctor@hu.edu.et', 'nurse@hu.edu.et');

-- Verify users were inserted
SELECT id, email, password, full_name, user_role, status FROM users;