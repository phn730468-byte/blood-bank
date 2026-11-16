CREATE DATABASE IF NOT EXISTS bloodbank;
USE bloodbank;
CREATE TABLE admin(id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) UNIQUE, password VARCHAR(255));
CREATE TABLE donors(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, age INT, gender VARCHAR(10), contact VARCHAR(15), address TEXT, blood_group VARCHAR(3) NOT NULL, last_donation DATE);
CREATE TABLE blood_units(id INT AUTO_INCREMENT PRIMARY KEY, donor_id INT NULL, blood_group VARCHAR(3) NOT NULL, quantity INT NOT NULL, collection_date DATE, expiry_date DATE, storage VARCHAR(100), FOREIGN KEY(donor_id) REFERENCES donors(id) ON DELETE SET NULL);
CREATE TABLE requests(id INT AUTO_INCREMENT PRIMARY KEY, requester VARCHAR(150) NOT NULL, contact VARCHAR(15), blood_group VARCHAR(3) NOT NULL, quantity INT NOT NULL, request_date DATE, status ENUM('pending','approved','rejected','issued') DEFAULT 'pending');
CREATE TABLE issues(id INT AUTO_INCREMENT PRIMARY KEY, request_id INT, blood_group VARCHAR(3), quantity INT, issue_date DATE, FOREIGN KEY(request_id) REFERENCES requests(id));
