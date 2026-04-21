-- =====================================================
-- Hệ thống Quản lý Dự Án & Chấm Công - Database Schema
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `ddonf_project` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ddonf_project`;

-- ---------------------------------------------------
-- Table: roles (vai trò)
-- ---------------------------------------------------
DROP TABLE IF EXISTS `user_roles`;
DROP TABLE IF EXISTS `hourly_rates`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `timesheets`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `project_members`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `system_settings`;

CREATE TABLE `roles` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `display_name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: users (người dùng)
-- ---------------------------------------------------
CREATE TABLE `users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) DEFAULT NULL,
    `department` VARCHAR(100) DEFAULT NULL,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `login_attempts` INT NOT NULL DEFAULT 0,
    `locked_until` DATETIME DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_email` (`email`),
    INDEX `idx_users_department` (`department`),
    INDEX `idx_users_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: user_roles (many-to-many)
-- ---------------------------------------------------
CREATE TABLE `user_roles` (
    `user_id` INT UNSIGNED NOT NULL,
    `role_id` INT UNSIGNED NOT NULL,
    `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `role_id`),
    CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: hourly_rates (đơn giá giờ công)
-- ---------------------------------------------------
CREATE TABLE `hourly_rates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `role_id` INT UNSIGNED DEFAULT NULL,
    `rate_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `effective_from` DATE NOT NULL,
    `effective_to` DATE DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_rates_user` (`user_id`),
    INDEX `idx_rates_role` (`role_id`),
    INDEX `idx_rates_effective` (`effective_from`, `effective_to`),
    CONSTRAINT `fk_rates_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_rates_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: projects (dự án)
-- ---------------------------------------------------
CREATE TABLE `projects` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(200) NOT NULL,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT DEFAULT NULL,
    `client_name` VARCHAR(200) DEFAULT NULL,
    `budget` DECIMAL(15,2) DEFAULT 0.00,
    `start_date` DATE DEFAULT NULL,
    `end_date` DATE DEFAULT NULL,
    `status` ENUM('planning','active','on_hold','completed','cancelled') NOT NULL DEFAULT 'planning',
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_projects_status` (`status`),
    INDEX `idx_projects_code` (`code`),
    INDEX `idx_projects_dates` (`start_date`, `end_date`),
    CONSTRAINT `fk_projects_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: project_members (thành viên dự án)
-- ---------------------------------------------------
CREATE TABLE `project_members` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `project_role` ENUM('manager','developer','qa','designer','other') NOT NULL DEFAULT 'developer',
    `joined_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_project_user` (`project_id`, `user_id`),
    INDEX `idx_pm_user` (`user_id`),
    CONSTRAINT `fk_pm_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pm_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: tasks (nhiệm vụ)
-- ---------------------------------------------------
CREATE TABLE `tasks` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `project_id` INT UNSIGNED NOT NULL,
    `title` VARCHAR(300) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `assigned_to` INT UNSIGNED DEFAULT NULL,
    `start_date` DATE DEFAULT NULL,
    `due_date` DATE DEFAULT NULL,
    `priority` ENUM('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
    `status` ENUM('todo','doing','review','done') NOT NULL DEFAULT 'todo',
    `estimated_hours` DECIMAL(8,2) DEFAULT 0.00,
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_by` INT UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_tasks_project_status` (`project_id`, `status`),
    INDEX `idx_tasks_assigned` (`assigned_to`),
    INDEX `idx_tasks_priority` (`priority`),
    INDEX `idx_tasks_dates` (`start_date`, `due_date`),
    CONSTRAINT `fk_tasks_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tasks_assigned` FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_tasks_creator` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: timesheets (chấm công)
-- ---------------------------------------------------
CREATE TABLE `timesheets` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `project_id` INT UNSIGNED NOT NULL,
    `task_id` INT UNSIGNED DEFAULT NULL,
    `work_date` DATE NOT NULL,
    `shift` ENUM('morning','afternoon','evening','flexible') NOT NULL DEFAULT 'flexible',
    `hours_worked` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `is_overtime` TINYINT(1) NOT NULL DEFAULT 0,
    `overtime_hours` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `description` TEXT DEFAULT NULL,
    `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `approved_by` INT UNSIGNED DEFAULT NULL,
    `approved_at` DATETIME DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_ts_user_date` (`user_id`, `work_date`),
    INDEX `idx_ts_project` (`project_id`),
    INDEX `idx_ts_task` (`task_id`),
    INDEX `idx_ts_status` (`status`),
    INDEX `idx_ts_date` (`work_date`),
    CONSTRAINT `fk_ts_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ts_project` FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_ts_task` FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_ts_approver` FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: audit_logs (nhật ký thao tác)
-- ---------------------------------------------------
CREATE TABLE `audit_logs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED DEFAULT NULL,
    `action` ENUM('create','update','delete','login','logout','approve','reject') NOT NULL,
    `entity_type` VARCHAR(50) NOT NULL,
    `entity_id` INT UNSIGNED DEFAULT NULL,
    `old_values` JSON DEFAULT NULL,
    `new_values` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` VARCHAR(500) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_entity` (`entity_type`, `entity_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_date` (`created_at`),
    CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------
-- Table: system_settings (cấu hình hệ thống)
-- ---------------------------------------------------
CREATE TABLE `system_settings` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `description` VARCHAR(255) DEFAULT NULL,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SEED DATA
-- =====================================================

-- Roles
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`) VALUES
(1, 'admin', 'Quản trị viên', 'Toàn quyền quản lý hệ thống'),
(2, 'pm', 'Quản lý dự án', 'Quản lý dự án được phân công'),
(3, 'hr', 'Nhân sự', 'Quản lý nhân sự, xem chấm công'),
(4, 'staff', 'Nhân viên', 'Xem và thao tác nhiệm vụ được giao');

-- Users (password: "password" for all)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `department`) VALUES
(1, 'Nguyễn Văn Admin', 'admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000001', 'Ban Giám đốc'),
(2, 'Trần Thị PM', 'pm@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000002', 'Phòng Kỹ thuật'),
(3, 'Lê Văn HR', 'hr@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000003', 'Phòng Nhân sự'),
(4, 'Phạm Minh Dev', 'staff@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000004', 'Phòng Kỹ thuật'),
(5, 'Hoàng Thị QA', 'qa@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000005', 'Phòng QA'),
(6, 'Vũ Đức Designer', 'designer@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000006', 'Phòng Thiết kế'),
(7, 'Đặng Công Dev2', 'dev2@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000007', 'Phòng Kỹ thuật'),
(8, 'Bùi Kim BA', 'ba@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901000008', 'Phòng BA');

-- User Roles
INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1), -- Admin
(2, 2), -- PM
(3, 3), -- HR
(4, 4), -- Staff (Dev)
(5, 4), -- Staff (QA)
(6, 4), -- Staff (Designer)
(7, 4), -- Staff (Dev2)
(8, 4); -- Staff (BA)

-- Hourly Rates
INSERT INTO `hourly_rates` (`user_id`, `role_id`, `rate_amount`, `effective_from`) VALUES
(NULL, 1, 500000, '2026-01-01'),  -- Admin: 500k/h
(NULL, 2, 400000, '2026-01-01'),  -- PM: 400k/h
(NULL, 3, 300000, '2026-01-01'),  -- HR: 300k/h
(NULL, 4, 250000, '2026-01-01'),  -- Staff: 250k/h
(4, NULL, 300000, '2026-01-01'),  -- Phạm Minh Dev: custom 300k/h
(5, NULL, 280000, '2026-01-01');  -- Hoàng Thị QA: custom 280k/h

-- Projects
INSERT INTO `projects` (`id`, `name`, `code`, `description`, `client_name`, `budget`, `start_date`, `end_date`, `status`, `created_by`) VALUES
(1, 'Website Thương mại điện tử ABC', 'PRJ-001', 'Xây dựng website TMĐT cho công ty ABC gồm frontend, backend, admin panel', 'Công ty ABC', 500000000, '2026-03-01', '2026-08-31', 'active', 1),
(2, 'App Mobile Quản lý Kho', 'PRJ-002', 'Phát triển ứng dụng mobile quản lý kho hàng cho chuỗi siêu thị', 'Siêu thị XYZ', 350000000, '2026-04-01', '2026-09-30', 'active', 2),
(3, 'Hệ thống CRM Nội bộ', 'PRJ-003', 'Xây dựng hệ thống CRM quản lý khách hàng nội bộ', NULL, 200000000, '2026-05-01', '2026-07-31', 'planning', 1),
(4, 'Landing Page Sự kiện', 'PRJ-004', 'Thiết kế landing page cho sự kiện launch sản phẩm mới', 'Công ty DEF', 50000000, '2026-02-01', '2026-03-15', 'completed', 2);

-- Project Members
INSERT INTO `project_members` (`project_id`, `user_id`, `project_role`) VALUES
(1, 2, 'manager'), (1, 4, 'developer'), (1, 5, 'qa'), (1, 6, 'designer'), (1, 7, 'developer'),
(2, 2, 'manager'), (2, 4, 'developer'), (2, 8, 'other'),
(3, 2, 'manager'), (3, 7, 'developer'), (3, 5, 'qa'),
(4, 2, 'manager'), (4, 6, 'designer');

-- Tasks for Project 1 (Website TMĐT)
INSERT INTO `tasks` (`id`, `project_id`, `title`, `description`, `assigned_to`, `start_date`, `due_date`, `priority`, `status`, `estimated_hours`, `sort_order`, `created_by`) VALUES
(1, 1, 'Thiết kế UI/UX Homepage', 'Thiết kế giao diện trang chủ responsive', 6, '2026-03-01', '2026-03-15', 'high', 'done', 40, 1, 2),
(2, 1, 'Phát triển Backend API Users', 'Xây dựng REST API quản lý users', 4, '2026-03-10', '2026-03-25', 'high', 'done', 32, 2, 2),
(3, 1, 'Phát triển Frontend - Trang chủ', 'Code HTML/CSS/JS cho trang chủ', 7, '2026-03-15', '2026-04-01', 'high', 'doing', 48, 3, 2),
(4, 1, 'API Sản phẩm & Giỏ hàng', 'Backend API cho module sản phẩm và giỏ hàng', 4, '2026-03-25', '2026-04-15', 'urgent', 'doing', 56, 4, 2),
(5, 1, 'Testing Module Users', 'Kiểm thử module quản lý users', 5, '2026-03-25', '2026-04-05', 'medium', 'review', 16, 5, 2),
(6, 1, 'Thiết kế trang Sản phẩm', 'UI/UX chi tiết sản phẩm, danh sách SP', 6, '2026-04-01', '2026-04-15', 'medium', 'todo', 32, 6, 2),
(7, 1, 'API Thanh toán', 'Tích hợp VNPay, Momo', 4, '2026-04-15', '2026-05-01', 'urgent', 'todo', 40, 7, 2),
(8, 1, 'Frontend - Giỏ hàng', 'Giao diện giỏ hàng và checkout', 7, '2026-04-01', '2026-04-20', 'high', 'todo', 36, 8, 2),
-- Tasks for Project 2
(9, 2, 'Wireframe App Mobile', 'Thiết kế wireframe các màn hình chính', 8, '2026-04-01', '2026-04-10', 'high', 'done', 24, 1, 2),
(10, 2, 'Setup Flutter Project', 'Khởi tạo dự án Flutter, cấu hình CI/CD', 4, '2026-04-05', '2026-04-12', 'high', 'doing', 16, 2, 2),
(11, 2, 'API Quản lý Kho', 'Backend API nhập/xuất kho', 4, '2026-04-12', '2026-05-01', 'high', 'todo', 48, 3, 2),
(12, 2, 'Màn hình Dashboard', 'Flutter UI cho dashboard tổng quan', 4, '2026-04-15', '2026-04-30', 'medium', 'todo', 32, 4, 2);

-- Timesheets (dữ liệu chấm công mẫu cho 3 tuần gần đây)
INSERT INTO `timesheets` (`user_id`, `project_id`, `task_id`, `work_date`, `shift`, `hours_worked`, `is_overtime`, `overtime_hours`, `description`, `status`, `approved_by`) VALUES
-- Tuần 07-11/04/2026
(4, 1, 4, '2026-04-07', 'flexible', 8.00, 0, 0.00, 'Code API products CRUD', 'approved', 2),
(4, 1, 4, '2026-04-08', 'flexible', 9.00, 1, 1.00, 'Code API cart + unit tests', 'approved', 2),
(4, 1, 4, '2026-04-09', 'flexible', 8.00, 0, 0.00, 'Fix bugs API products', 'approved', 2),
(4, 1, 4, '2026-04-10', 'flexible', 8.50, 1, 0.50, 'API cart validation', 'approved', 2),
(4, 1, 4, '2026-04-11', 'flexible', 8.00, 0, 0.00, 'Code review + merge', 'approved', 2),

(7, 1, 3, '2026-04-07', 'flexible', 8.00, 0, 0.00, 'Frontend homepage hero section', 'approved', 2),
(7, 1, 3, '2026-04-08', 'flexible', 8.00, 0, 0.00, 'Frontend homepage products grid', 'approved', 2),
(7, 1, 3, '2026-04-09', 'flexible', 7.50, 0, 0.00, 'Responsive adjustments', 'approved', 2),
(7, 1, 3, '2026-04-10', 'flexible', 8.00, 0, 0.00, 'Footer + navigation', 'approved', 2),
(7, 1, 3, '2026-04-11', 'flexible', 8.00, 0, 0.00, 'Homepage animations', 'approved', 2),

(5, 1, 5, '2026-04-07', 'morning', 4.00, 0, 0.00, 'Test cases cho user registration', 'approved', 2),
(5, 1, 5, '2026-04-07', 'afternoon', 4.00, 0, 0.00, 'Test cases cho user login', 'approved', 2),
(5, 1, 5, '2026-04-08', 'flexible', 8.00, 0, 0.00, 'Regression testing users', 'approved', 2),

(6, 1, 1, '2026-04-07', 'flexible', 6.00, 0, 0.00, 'Final review UI homepage', 'approved', 2),
(6, 1, 6, '2026-04-08', 'flexible', 8.00, 0, 0.00, 'Bắt đầu thiết kế trang sản phẩm', 'approved', 2),

-- Tuần 14-18/04/2026
(4, 1, 4, '2026-04-14', 'flexible', 8.00, 0, 0.00, 'API cart checkout flow', 'approved', 2),
(4, 1, 4, '2026-04-15', 'flexible', 10.00, 1, 2.00, 'Hoàn thành API cart + order', 'approved', 2),
(4, 2, 10, '2026-04-16', 'flexible', 8.00, 0, 0.00, 'Setup Flutter project structure', 'approved', 2),
(4, 2, 10, '2026-04-17', 'flexible', 8.00, 0, 0.00, 'Flutter state management setup', 'approved', 2),
(4, 2, 10, '2026-04-18', 'flexible', 8.00, 0, 0.00, 'Flutter CI/CD pipeline', 'approved', 2),

(7, 1, 3, '2026-04-14', 'flexible', 8.00, 0, 0.00, 'Frontend product listing page', 'approved', 2),
(7, 1, 3, '2026-04-15', 'flexible', 8.00, 0, 0.00, 'Frontend product detail page', 'approved', 2),
(7, 1, 3, '2026-04-16', 'flexible', 9.00, 1, 1.00, 'Frontend filters + search', 'approved', 2),
(7, 1, 8, '2026-04-17', 'flexible', 8.00, 0, 0.00, 'Bắt đầu code giỏ hàng UI', 'pending', NULL),
(7, 1, 8, '2026-04-18', 'flexible', 8.00, 0, 0.00, 'Cart UI + quantity update', 'pending', NULL),

(5, 1, 5, '2026-04-14', 'flexible', 8.00, 0, 0.00, 'Test API products', 'approved', 2),
(5, 1, 5, '2026-04-15', 'flexible', 8.00, 0, 0.00, 'Test API cart', 'approved', 2),

(6, 1, 6, '2026-04-14', 'flexible', 8.00, 0, 0.00, 'UI product detail page', 'approved', 2),
(6, 1, 6, '2026-04-15', 'flexible', 8.00, 0, 0.00, 'UI product listing responsive', 'approved', 2),

-- Tuần hiện tại 21/04/2026
(4, 2, 11, '2026-04-21', 'morning', 4.00, 0, 0.00, 'API nhập kho - models', 'pending', NULL),
(7, 1, 8, '2026-04-21', 'flexible', 4.00, 0, 0.00, 'Checkout page frontend', 'pending', NULL);

-- Audit Logs (mẫu)
INSERT INTO `audit_logs` (`user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `ip_address`, `created_at`) VALUES
(1, 'create', 'project', 1, NULL, '{"name":"Website Thương mại điện tử ABC","status":"planning"}', '127.0.0.1', '2026-03-01 08:00:00'),
(2, 'update', 'project', 1, '{"status":"planning"}', '{"status":"active"}', '127.0.0.1', '2026-03-01 09:00:00'),
(2, 'create', 'task', 1, NULL, '{"title":"Thiết kế UI/UX Homepage","assigned_to":6}', '127.0.0.1', '2026-03-01 09:30:00'),
(2, 'update', 'task', 1, '{"status":"doing"}', '{"status":"done"}', '127.0.0.1', '2026-03-20 17:00:00'),
(4, 'create', 'timesheet', 1, NULL, '{"hours_worked":8,"work_date":"2026-04-07"}', '127.0.0.1', '2026-04-07 17:30:00'),
(2, 'approve', 'timesheet', 1, '{"status":"pending"}', '{"status":"approved"}', '127.0.0.1', '2026-04-08 09:00:00');

-- System Settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('working_hours_per_day', '8', 'Số giờ làm hành chính mỗi ngày'),
('working_hours_per_week', '40', 'Số giờ làm tối đa mỗi tuần'),
('work_start_time', '08:00', 'Giờ bắt đầu làm việc'),
('work_end_time', '17:00', 'Giờ kết thúc làm việc'),
('ot_multiplier', '1.5', 'Hệ số lương OT'),
('max_weekly_hours', '40', 'Giới hạn giờ/tuần trước khi cảnh báo overbooking'),
('currency', 'VND', 'Đơn vị tiền tệ'),
('company_name', 'DDONF Corp', 'Tên công ty');

SET FOREIGN_KEY_CHECKS = 1;
