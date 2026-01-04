-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS unipulse_db;
USE unipulse_db;

-- 1. users Table
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    student_id VARCHAR(50),
    blood_group VARCHAR(5),
    dob DATE,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. donor_details Table
CREATE TABLE IF NOT EXISTS donor_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    department VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. blood_requests Table
CREATE TABLE IF NOT EXISTS blood_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    requester_name VARCHAR(100),
    requester_phone VARCHAR(20),
    urgency ENUM('routine', 'urgent', 'critical') DEFAULT 'routine',
    blood_type VARCHAR(5) NOT NULL,
    units INT DEFAULT 1,
    hospital VARCHAR(255),
    notes TEXT,
    status VARCHAR(20) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- 4. blood_inventory Table
CREATE TABLE IF NOT EXISTS blood_inventory (
    blood_type VARCHAR(5) PRIMARY KEY,
    status VARCHAR(20) NOT NULL,
    units_available INT DEFAULT 0
);

-- Initial Data for inventory
INSERT INTO blood_inventory (blood_type, status, units_available) VALUES 
('A+', 'Good', 85),
('A-', 'Low', 20),
('B+', 'Good', 70),
('B-', 'Critical', 10),
('AB+', 'Moderate', 45),
('AB-', 'Low', 15),
('O+', 'Good', 90),
('O-', 'Moderate', 40)
ON DUPLICATE KEY UPDATE 
status = VALUES(status), 
units_available = VALUES(units_available);
