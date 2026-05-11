CREATE DATABASE IF NOT EXISTS trx_clawd_fortress;
USE trx_clawd_fortress;
DROP TABLE IF EXISTS client_portal_accounts;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL
);
CREATE TABLE clients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  contact_number VARCHAR(20) NOT NULL,
  company_name VARCHAR(100) NOT NULL,
  address TEXT NOT NULL,
  status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL
);
CREATE TABLE client_portal_accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  client_id INT NOT NULL UNIQUE,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_client_portal_client FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);
INSERT INTO users (full_name, username, password) VALUES
('System Administrator', 'admin', '$2y$10$QyN7kLzVYJ8pH4j3W9kBfO5sS7Y8L6nL4sM1WvTn5yT0xWJ1Xxg2K');
INSERT INTO clients (full_name, email, contact_number, company_name, address, status, created_at) VALUES
('Rafael Dela Cruz', 'rafael@northpeak.ph', '+63 917 100 1001', 'North Peak Systems', 'Makati City, Metro Manila', 'Active', DATE_SUB(NOW(), INTERVAL 62 DAY)),
('Ariana Velasco', 'ariana@blueforge.ph', '+63 917 100 1002', 'BlueForge Logistics', 'Pasig City, Metro Manila', 'Active', DATE_SUB(NOW(), INTERVAL 49 DAY)),
('Noel Serrano', 'noel@zenbyte.ph', '+63 917 100 1003', 'ZenByte Digital', 'Cebu City, Cebu', 'Inactive', DATE_SUB(NOW(), INTERVAL 37 DAY)),
('Eliza Montemayor', 'eliza@aurelia.ph', '+63 917 100 1004', 'Aurelia HealthTech', 'Taguig City, Metro Manila', 'Active', DATE_SUB(NOW(), INTERVAL 28 DAY)),
('Miguel Reyes', 'miguel@blueforge.ph', '+63 917 100 1005', 'BlueForge Logistics', 'Davao City, Davao del Sur', 'Active', DATE_SUB(NOW(), INTERVAL 16 DAY)),
('Trisha Santos', 'trisha@neonworks.ph', '+63 917 100 1006', 'NeonWorks Studio', 'Bacoor, Cavite', 'Inactive', DATE_SUB(NOW(), INTERVAL 9 DAY)),
('Patricia Luna', 'patricia@atlasworks.ph', '+63 917 100 1007', 'Atlas Works', 'Kawit, Cavite', 'Active', DATE_SUB(NOW(), INTERVAL 7 DAY)),
('Jerome Navarro', 'jerome@skyport.ph', '+63 917 100 1008', 'SkyPort Holdings', 'Quezon City, Metro Manila', 'Active', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('Sonia Villar', 'sonia@solis.ph', '+63 917 100 1009', 'Solis Energy', 'Iloilo City, Iloilo', 'Inactive', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('Dane Flores', 'dane@primecrest.ph', '+63 917 100 1010', 'PrimeCrest Ventures', 'Baguio City, Benguet', 'Active', DATE_SUB(NOW(), INTERVAL 1 DAY));
INSERT INTO client_portal_accounts (client_id, username, password) VALUES
(1, 'rafael.client', '$2y$10$QyN7kLzVYJ8pH4j3W9kBfO5sS7Y8L6nL4sM1WvTn5yT0xWJ1Xxg2K'),
(7, 'patricia.client', '$2y$10$QyN7kLzVYJ8pH4j3W9kBfO5sS7Y8L6nL4sM1WvTn5yT0xWJ1Xxg2K');
-- Admin login: admin / admin123
-- Client portal logins: rafael.client / admin123, patricia.client / admin123
