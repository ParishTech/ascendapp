-- Ascend Altar Server Management System
-- MariaDB Schema

CREATE DATABASE IF NOT EXISTS ascend;
USE ascend;

-- Users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('server', 'mc', 'trainer', 'admin') NOT NULL DEFAULT 'server',
  parish_id INT,
  phone VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX (email),
  INDEX (role),
  INDEX (parish_id)
);

-- Parishes table
CREATE TABLE parishes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  address VARCHAR(255),
  city VARCHAR(100),
  state VARCHAR(50),
  zip_code VARCHAR(10),
  phone VARCHAR(20),
  email VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Masses table
CREATE TABLE masses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  parish_id INT NOT NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  type ENUM('Sunday', 'Weekday', 'Holy Day', 'Funeral', 'Wedding') DEFAULT 'Sunday',
  mc_id INT,
  status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (parish_id) REFERENCES parishes(id),
  FOREIGN KEY (mc_id) REFERENCES users(id),
  INDEX (date),
  INDEX (parish_id)
);

-- Clock in/out records
CREATE TABLE clock_records (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  mass_id INT NOT NULL,
  clock_in DATETIME NOT NULL,
  clock_out DATETIME,
  duration_minutes INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (mass_id) REFERENCES masses(id),
  INDEX (user_id),
  INDEX (mass_id),
  INDEX (clock_in)
);

-- Performance notes
CREATE TABLE performance_notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  mass_id INT NOT NULL,
  server_id INT NOT NULL,
  mc_id INT NOT NULL,
  timeliness INT CHECK (timeliness >= 1 AND timeliness <= 5),
  demeanor INT CHECK (demeanor >= 1 AND demeanor <= 5),
  accuracy INT CHECK (accuracy >= 1 AND accuracy <= 5),
  notes TEXT,
  has_referral BOOLEAN DEFAULT FALSE,
  referral_reason VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (mass_id) REFERENCES masses(id),
  FOREIGN KEY (server_id) REFERENCES users(id),
  FOREIGN KEY (mc_id) REFERENCES users(id),
  INDEX (server_id),
  INDEX (mass_id),
  INDEX (created_at)
);

-- Training sessions
CREATE TABLE training_sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  trainer_id INT,
  topic VARCHAR(255),
  date_completed DATE,
  status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (trainer_id) REFERENCES users(id),
  INDEX (user_id),
  INDEX (status)
);

-- Training referrals
CREATE TABLE training_referrals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  referred_by INT NOT NULL,
  reason VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  resolved_at TIMESTAMP NULL,
  resolved BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (referred_by) REFERENCES users(id),
  INDEX (user_id),
  INDEX (resolved)
);

-- Prayer content
CREATE TABLE prayers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('vesting', 'rosary', 'daily', 'scripture', 'reflection') NOT NULL,
  title VARCHAR(255),
  content LONGTEXT NOT NULL,
  source VARCHAR(255),
  date_posted DATE,
  is_ai_generated BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (type),
  INDEX (date_posted)
);

-- Daily AI prayers cache
CREATE TABLE daily_prayers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE UNIQUE NOT NULL,
  prayer_content LONGTEXT NOT NULL,
  liturgical_info VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Scripture readings
CREATE TABLE scripture_readings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE UNIQUE NOT NULL,
  book VARCHAR(50),
  chapter INT,
  verse_start INT,
  verse_end INT,
  text LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Session tokens for API authentication
CREATE TABLE sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(255) UNIQUE NOT NULL,
  expires_at TIMESTAMP NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX (token),
  INDEX (user_id)
);
