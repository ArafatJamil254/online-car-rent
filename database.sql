-- ============================================================
--  Online Car Rent — Full Database
--  Database : car_rent_project
--  Compatible with: MySQL 5.7+ / MariaDB 10.4+
--  Matches: Project 05 spec (Tasks 1-4)
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Create & select database
-- ------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `car_rent_project`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `car_rent_project`;

-- ------------------------------------------------------------
-- Drop tables in safe order (children first)
-- ------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `blogs`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `cars`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- TABLE: users
-- Tasks 1, 2, 3, 4  (auth, profile, members list, blog author)
-- ============================================================
CREATE TABLE `users` (
  `id`               INT(11)      NOT NULL AUTO_INCREMENT,
  `name`             VARCHAR(100) NOT NULL,
  `email`            VARCHAR(120) NOT NULL,
  `password_hash`    VARCHAR(255) NOT NULL,
  `role`             ENUM('admin','member') NOT NULL DEFAULT 'member',
  `profile_picture`  VARCHAR(255)           DEFAULT NULL,
  `address`          VARCHAR(255)           DEFAULT NULL,
  `phone`            VARCHAR(20)            DEFAULT NULL,
  `created_at`       TIMESTAMP    NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: cars
-- Task 2 (admin CRUD), Task 1 (home/browse), Task 3 (order)
-- ============================================================
CREATE TABLE `cars` (
  `id`                  INT(11)        NOT NULL AUTO_INCREMENT,
  `name`                VARCHAR(100)   NOT NULL,
  `model`               VARCHAR(100)   NOT NULL,
  `type`                VARCHAR(50)    NOT NULL
                          COMMENT 'Private car | Microbus | Pick-up | SUV | Luxury car',
  `price_per_day`       DECIMAL(10,2)  NOT NULL,
  `availability_status` ENUM('available','unavailable') NOT NULL DEFAULT 'available',
  `image_path`          VARCHAR(255)   DEFAULT NULL,
  `description`         TEXT           DEFAULT NULL,
  `created_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: orders
-- Task 3 (place order, cancel, confirm), Task 2 (view history)
-- ============================================================
CREATE TABLE `orders` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `user_id`        INT(11)       NOT NULL,
  `car_id`         INT(11)       NOT NULL,
  `start_date`     DATE          NOT NULL,
  `end_date`       DATE          NOT NULL,
  `total_cost`     DECIMAL(10,2) NOT NULL,
  `status`         ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` VARCHAR(50)   DEFAULT NULL
                     COMMENT 'Filled when payment is confirmed',
  `order_date`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_car_id`  (`car_id`),
  KEY `idx_status`  (`status`),
  -- ON DELETE CASCADE: deleting a member removes their orders
  -- ON DELETE RESTRICT: cannot delete a car that has any order (block)
  CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_orders_car`  FOREIGN KEY (`car_id`)  REFERENCES `cars`  (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: payments
-- Task 3 (store payment after finalize)
-- ============================================================
CREATE TABLE `payments` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `order_id`       INT(11)       NOT NULL,
  `amount`         DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50)   NOT NULL
                     COMMENT 'credit_card | bkash | nagad | bank_transfer | cash_on_delivery',
  `transaction_id` VARCHAR(100)  DEFAULT NULL,
  `payment_date`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  -- ON DELETE CASCADE: if an order is deleted, its payment record goes too
  CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: blogs
-- Task 4 (post, view, delete own, admin delete any)
-- ============================================================
CREATE TABLE `blogs` (
  `id`         INT(11)      NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11)      NOT NULL,
  `title`      VARCHAR(150) NOT NULL,
  `content`    TEXT         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  -- ON DELETE CASCADE: deleting a member removes their blog posts too
  CONSTRAINT `fk_blogs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Default admin account  (password: admin1234)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`, `address`, `phone`) VALUES
('Admin User', 'admin@carrent.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'admin', 'Dhaka, Bangladesh', '01700000000');

-- Sample member account  (password: member1234)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`, `address`, `phone`) VALUES
('Test Member', 'member@carrent.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'member', 'Chittagong, Bangladesh', '01800000000');

-- Sample cars
INSERT INTO `cars` (`name`, `model`, `type`, `price_per_day`, `availability_status`, `description`) VALUES
('Toyota Corolla',  'X 2023',       'Private car', 3500.00, 'available',   'Comfortable sedan for city travel'),
('Hyundai Starex',  'Grand 2022',   'Microbus',    6000.00, 'available',   'Spacious microbus for group travel'),
('Toyota Hilux',    'Revo 2022',    'Pick-up',     5000.00, 'available',   'Heavy duty pick-up truck'),
('Honda Civic',     'RS 2023',      'Private car', 4000.00, 'unavailable', 'Sporty sedan currently in maintenance'),
('Toyota Land Cruiser', 'V8 2022',  'SUV',         9000.00, 'available',   'Premium SUV for off-road trips'),
('Mercedes-Benz',   'E-Class 2023', 'Luxury car',  12000.00,'available',   'Executive luxury sedan');

CREATE TABLE IF NOT EXISTS remember_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token_hash VARCHAR(255) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
