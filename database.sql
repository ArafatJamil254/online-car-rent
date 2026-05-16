-- ============================================================
--  SQL Setup for Web Technologies Project - Online Car Rent
--  Task 4: Blog Feature
-- ============================================================
--  Run this entire file in phpMyAdmin or MySQL command line.
--  It creates the database and all required tables.
-- ============================================================


-- Step 1: Create the database (skip if it already exists)
CREATE DATABASE IF NOT EXISTS webtech;
USE webtech;


-- ============================================================
-- SHARED TABLES (used by all 4 tasks)
-- These tables must NOT be dropped or altered per project rules
-- ============================================================

-- Users table (created by Task 1, used by all)
CREATE TABLE IF NOT EXISTS users (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)  NOT NULL,
    email           VARCHAR(150)  NOT NULL UNIQUE,
    password_hash   VARCHAR(255)  NOT NULL,
    role            ENUM('admin', 'member') NOT NULL DEFAULT 'member',
    profile_picture VARCHAR(255)  DEFAULT NULL,
    address         TEXT          DEFAULT NULL,
    phone           VARCHAR(20)   DEFAULT NULL,
    created_at      DATETIME      DEFAULT CURRENT_TIMESTAMP
);


-- Cars table (created by Task 2)
CREATE TABLE IF NOT EXISTS cars (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    name                VARCHAR(100)  NOT NULL,
    model               VARCHAR(100)  NOT NULL,
    type                VARCHAR(50)   NOT NULL,   -- e.g. Private car, Microbus, Pick-up
    price_per_day       DECIMAL(10,2) NOT NULL,
    availability_status TINYINT(1)    NOT NULL DEFAULT 1,  -- 1=available, 0=unavailable
    image_path          VARCHAR(255)  DEFAULT NULL,
    description         TEXT          DEFAULT NULL,
    created_at          DATETIME      DEFAULT CURRENT_TIMESTAMP
);


-- Orders table (created by Task 3)
CREATE TABLE IF NOT EXISTS orders (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    user_id         INT           NOT NULL,
    car_id          INT           NOT NULL,
    start_date      DATE          NOT NULL,
    end_date        DATE          NOT NULL,
    total_cost      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status          ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
    payment_method  VARCHAR(50)   DEFAULT NULL,
    order_date      DATETIME      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id)  REFERENCES cars(id)  ON DELETE CASCADE
);


-- Payments table (created by Task 3)
CREATE TABLE IF NOT EXISTS payments (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    order_id        INT           NOT NULL,
    amount          DECIMAL(10,2) NOT NULL,
    payment_method  VARCHAR(50)   NOT NULL,   -- credit_card, bkash, nagad, bank_transfer
    transaction_id  VARCHAR(100)  DEFAULT NULL,
    payment_date    DATETIME      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);


-- ============================================================
-- TASK 4 TABLE: blogs
-- ============================================================

CREATE TABLE IF NOT EXISTS blogs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT           NOT NULL,
    title       VARCHAR(255)  NOT NULL,
    content     TEXT          NOT NULL,
    created_at  DATETIME      DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- ============================================================
-- SAMPLE DATA (optional - for testing)
-- ============================================================

-- Insert a test admin user (password: password)
-- The hash below is for "password" - in real code use password_hash()
INSERT IGNORE INTO users (name, email, password_hash, role, phone, address)
VALUES (
    'Admin User',
    'admin@carrent.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    '01711000000',
    'Dhaka, Bangladesh'
);

-- Insert a test member user (password: password)
INSERT IGNORE INTO users (name, email, password_hash, role, phone, address)
VALUES (
    'John Member',
    'member@carrent.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'member',
    '01722000000',
    'Chittagong, Bangladesh'
);

-- Insert some sample blog posts
-- (These assume user IDs 1 and 2 exist from inserts above)
INSERT IGNORE INTO blogs (user_id, title, content, created_at) VALUES
(2, 'Great Experience with the Microbus!', 'I rented a microbus for our family trip to Coxs Bazar. The car was very clean and the process was super easy. Highly recommend this service to everyone!', '2025-05-01 10:30:00'),
(2, 'Smooth Booking Process', 'I booked a private car for my business meeting. The online booking was fast and the car arrived on time. Will definitely use this service again.', '2025-05-05 14:00:00'),
(1, 'Admin Note: New Cars Added', 'We have added 5 new cars to our fleet this month including two luxury sedans. Check out the Cars section for details.', '2025-05-10 09:00:00');
