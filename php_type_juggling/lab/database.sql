-- Create database
CREATE DATABASE IF NOT EXISTS type_juggling_lab;
USE type_juggling_lab;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO users (username, password, email, is_admin) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@lab.com', TRUE), -- md5('admin')
('alice', '5f4dcc3b5aa765d61d8327deb882cf99', 'alice@lab.com', FALSE), -- md5('password')
('bob', 'e10adc3949ba59abbe56e057f20f883e', 'bob@lab.com', FALSE), -- md5('123456')
('test', '098f6bcd4621d373cade4e832627b4f6', 'test@lab.com', FALSE); -- md5('test')

-- Create sessions table
CREATE TABLE user_sessions (
    session_id VARCHAR(64) PRIMARY KEY,
    user_id INT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create audit log
CREATE TABLE type_juggling_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(64),
    test_type VARCHAR(50),
    input_data TEXT,
    result TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create vulnerable table for demonstrations
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    quantity INT,
    is_active BOOLEAN DEFAULT TRUE,
    metadata TEXT
);

INSERT INTO products (name, price, quantity, metadata) VALUES
('Product A', 19.99, 100, '{"discount": "10%"}'),
('Product B', 29.99, 50, '{"discount": "5%"}'),
('Product C', 9.99, 200, '{"discount": "15%"}'),
('Free Product', 0.00, 1000, '{"discount": "100%"}');
