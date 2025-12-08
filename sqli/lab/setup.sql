-- 1. Create the Database
CREATE DATABASE IF NOT EXISTS oswe_lab;
USE oswe_lab;

-- 2. Create a Dedicated User (Best Practice Simulation)
-- We avoid using 'root' for the app connection to mimic real scenarios
CREATE USER IF NOT EXISTS 'oswe_user'@'localhost' IDENTIFIED BY 'oswe_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON oswe_lab.* TO 'oswe_user'@'localhost';
FLUSH PRIVILEGES;

-- 3. Create the Public Table (Legitimate Data)
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 4. Create the Hidden Table (The Target)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- 5. Seed Data
INSERT INTO `products` (`name`, `description`, `price`) VALUES
('OSWE Course Guide', 'Advanced Web Attacks and Exploitation study material.', 999.00),
('Debugging Tool', 'Standard issue rubber ducky.', 15.50),
('Mechanical Keyboard', 'Clicky switches for maximum hacking efficiency.', 120.00),
('Caffeine Supplement', 'Required for 48-hour exams.', 25.00);

-- Seed the Flag
INSERT INTO `users` (`username`, `password`, `is_admin`) VALUES
('admin', 'OSWE{Blind_SQLi_XAMPP_Mastery_2025}', 1),
('guest', 'guest', 0);
