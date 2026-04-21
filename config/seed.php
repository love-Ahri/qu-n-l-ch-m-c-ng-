<?php
/**
 * Seed script - Run via browser or CLI to populate database with UTF-8 data
 * Usage: php seed.php OR visit /ddonf/config/seed.php
 */
header('Content-Type: text/html; charset=utf-8');

$dbConfig = require __DIR__ . '/database.php';
$dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
$pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
$pdo->exec("SET NAMES utf8mb4");

echo "<pre>\n";

// Clear all data first
echo "Clearing existing data...\n";
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$tables = ['audit_logs', 'timesheets', 'tasks', 'project_members', 'hourly_rates', 'user_roles', 'projects', 'users', 'roles'];
foreach ($tables as $t) {
    $pdo->exec("TRUNCATE TABLE {$t}");
}
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

// Roles
echo "Seeding roles...\n";
$pdo->exec("INSERT INTO roles (id, name, display_name, description) VALUES
    (1, 'admin', 'Quản trị viên', 'Toàn quyền truy cập hệ thống'),
    (2, 'pm', 'Quản lý Dự án', 'Quản lý dự án và nhân sự trong dự án'),
    (3, 'hr', 'Nhân sự', 'Quản lý chấm công, đơn giá, và báo cáo'),
    (4, 'staff', 'Nhân viên', 'Truy cập nhiệm vụ được giao và chấm công cá nhân')
");

// Users
echo "Seeding users...\n";
$password = password_hash('password', PASSWORD_DEFAULT);
$users = [
    [1, 'Nguyễn Văn Admin', 'admin@demo.com', '0901234567', 'Ban Giám đốc'],
    [2, 'Trần Thị PM', 'pm@demo.com', '0912345678', 'Phòng Phát triển'],
    [3, 'Lê Hoàng HR', 'hr@demo.com', '0923456789', 'Phòng Nhân sự'],
    [4, 'Phạm Minh Staff', 'staff@demo.com', '0934567890', 'Phòng Phát triển'],
    [5, 'Nguyễn Thị Lan', 'lan@demo.com', '0945678901', 'Phòng Phát triển'],
    [6, 'Hoàng Văn Dũng', 'dung@demo.com', '0956789012', 'Phòng Thiết kế'],
    [7, 'Vũ Thị Hương', 'huong@demo.com', '0967890123', 'Phòng QA'],
    [8, 'Đặng Quốc Bảo', 'bao@demo.com', '0978901234', 'Phòng Phát triển'],
];
$stmt = $pdo->prepare("INSERT INTO users (id, name, email, password, phone, department) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($users as $u) {
    $stmt->execute([$u[0], $u[1], $u[2], $password, $u[3], $u[4]]);
}

// User roles
echo "Seeding user_roles...\n";
$pdo->exec("INSERT INTO user_roles (user_id, role_id) VALUES
    (1, 1), (1, 2),
    (2, 2),
    (3, 3),
    (4, 4), (5, 4), (6, 4), (7, 4), (8, 4)
");

// Hourly rates
echo "Seeding hourly_rates...\n";
$pdo->exec("INSERT INTO hourly_rates (role_id, user_id, rate_amount, effective_from) VALUES
    (1, NULL, 500000, '2024-01-01'),
    (2, NULL, 400000, '2024-01-01'),
    (3, NULL, 350000, '2024-01-01'),
    (4, NULL, 250000, '2024-01-01'),
    (NULL, 5, 280000, '2024-06-01')
");

// Projects
echo "Seeding projects...\n";
$pdo->exec("INSERT INTO projects (id, name, code, description, client_name, budget, start_date, end_date, status, created_by) VALUES
    (1, 'Website Thương mại điện tử', 'PRJ-001', 'Xây dựng website TMĐT cho khách hàng ABC Corp với đầy đủ chức năng giỏ hàng, thanh toán online, và quản lý kho hàng.', 'ABC Corp', 500000000, '2024-01-15', '2024-06-30', 'active', 1),
    (2, 'App Mobile Quản lý Kho', 'PRJ-002', 'Phát triển ứng dụng mobile (iOS/Android) quản lý kho hàng real-time bằng React Native.', 'XYZ Ltd', 300000000, '2024-03-01', '2024-09-30', 'active', 2),
    (3, 'Hệ thống CRM Nội bộ', 'PRJ-003', 'Xây dựng hệ thống CRM nội bộ để quản lý khách hàng, pipeline bán hàng và hỗ trợ sau bán hàng.', 'Nội bộ', 200000000, '2024-02-01', '2024-08-31', 'active', 1),
    (4, 'Landing Page Sự kiện', 'PRJ-004', 'Thiết kế và phát triển landing page cho sự kiện Tech Summit 2024.', 'Tech Summit', 50000000, '2024-04-01', '2024-04-30', 'completed', 2),
    (5, 'Nâng cấp Hạ tầng Server', 'PRJ-005', 'Nâng cấp hệ thống server, migration sang cloud và tối ưu hiệu năng.', 'Nội bộ', 150000000, '2024-05-01', NULL, 'planning', 1)
");

// Project members
echo "Seeding project_members...\n";
$pdo->exec("INSERT INTO project_members (project_id, user_id, project_role) VALUES
    (1, 1, 'manager'), (1, 2, 'manager'), (1, 4, 'developer'), (1, 5, 'developer'), (1, 6, 'designer'), (1, 7, 'qa'),
    (2, 2, 'manager'), (2, 4, 'developer'), (2, 8, 'developer'), (2, 7, 'qa'),
    (3, 1, 'manager'), (3, 5, 'developer'), (3, 6, 'designer'), (3, 8, 'developer'),
    (4, 2, 'manager'), (4, 6, 'designer'), (4, 5, 'developer')
");

// Tasks
echo "Seeding tasks...\n";
$pdo->exec("INSERT INTO tasks (id, project_id, title, description, assigned_to, start_date, due_date, priority, status, estimated_hours, created_by) VALUES
    (1, 1, 'Thiết kế wireframe trang chủ', 'Thiết kế layout, wireframe chi tiết cho trang chủ TMĐT', 6, '2024-01-15', '2024-01-25', 'high', 'done', 16, 2),
    (2, 1, 'Phát triển API danh mục sản phẩm', 'Xây dựng RESTful API cho quản lý danh mục sản phẩm', 4, '2024-01-20', '2024-02-10', 'high', 'done', 40, 2),
    (3, 1, 'Tích hợp cổng thanh toán VNPay', 'Tích hợp cổng thanh toán VNPay cho checkout', 5, '2024-02-15', '2024-03-15', 'urgent', 'doing', 32, 2),
    (4, 1, 'Kiểm thử UAT Module Giỏ hàng', 'Testing chức năng giỏ hàng end-to-end', 7, '2024-03-01', '2024-03-20', 'medium', 'review', 24, 2),
    (5, 1, 'Responsive design cho mobile', 'Tối ưu giao diện hiển thị trên thiết bị di động', 6, '2024-03-10', '2024-04-10', 'medium', 'todo', 20, 2),
    (6, 1, 'Tối ưu SEO on-page', 'Tối ưu meta tags, structured data, sitemap', 5, '2024-04-01', NULL, 'low', 'todo', 12, 2),
    (7, 2, 'Setup React Native project', 'Khởi tạo project React Native, cấu hình CI/CD', 4, '2024-03-01', '2024-03-10', 'high', 'done', 8, 2),
    (8, 2, 'Module Quét Barcode', 'Phát triển module quét mã vạch sản phẩm bằng camera', 8, '2024-03-15', '2024-04-15', 'high', 'doing', 40, 2),
    (9, 2, 'API Đồng bộ Kho real-time', 'Xây dựng WebSocket API đồng bộ dữ liệu kho real-time', 4, '2024-04-01', '2024-05-01', 'urgent', 'doing', 48, 2),
    (10, 2, 'Testing trên thiết bị thật', 'QA testing trên các dòng điện thoại phổ biến', 7, '2024-05-01', '2024-05-20', 'medium', 'todo', 16, 2),
    (11, 3, 'Thiết kế Database Schema', 'Thiết kế cấu trúc database cho module CRM', 8, '2024-02-01', '2024-02-15', 'high', 'done', 12, 1),
    (12, 3, 'Module Quản lý Khách hàng', 'CRUD khách hàng với phân loại, tags, và lịch sử tương tác', 5, '2024-02-15', '2024-03-31', 'high', 'doing', 60, 1),
    (13, 3, 'Dashboard Báo cáo KPI', 'Phát triển dashboard với các biểu đồ KPI bán hàng', 8, '2024-04-01', '2024-05-15', 'medium', 'todo', 32, 1),
    (14, 4, 'Thiết kế giao diện Landing Page', 'Thiết kế UI/UX cho landing page sự kiện', 6, '2024-04-01', '2024-04-10', 'high', 'done', 16, 2),
    (15, 4, 'Phát triển và Deploy', 'Code HTML/CSS/JS và deploy lên production', 5, '2024-04-10', '2024-04-20', 'high', 'done', 12, 2)
");

// Timesheets
echo "Seeding timesheets...\n";
$timesheetData = [
    // User 4 - Phạm Minh Staff
    [4, 1, 2, '2024-04-14', 'morning', 4, 0, 0, 'Phát triển API sản phẩm', 'approved', 1],
    [4, 1, 2, '2024-04-14', 'afternoon', 4, 0, 0, 'Code review và fix bug', 'approved', 1],
    [4, 2, 9, '2024-04-15', 'morning', 4, 0, 0, 'Phát triển WebSocket cho kho', 'approved', 2],
    [4, 2, 9, '2024-04-15', 'afternoon', 4, 0, 0, 'Testing và debug WebSocket', 'approved', 2],
    [4, 2, 9, '2024-04-15', 'evening', 3, 1, 3, 'Fix lỗi đồng bộ dữ liệu', 'approved', 2],
    [4, 1, 3, '2024-04-16', 'morning', 4, 0, 0, 'Tích hợp VNPay sandbox', 'approved', 2],
    [4, 1, 3, '2024-04-16', 'afternoon', 4, 0, 0, 'Xử lý callback thanh toán', 'approved', 2],
    [4, 2, 9, '2024-04-17', 'flexible', 8, 0, 0, 'API real-time inventory', 'pending', null],
    [4, 1, 3, '2024-04-18', 'flexible', 8, 0, 0, 'Hoàn thiện tích hợp VNPay', 'pending', null],
    // User 5 - Nguyễn Thị Lan
    [5, 1, 3, '2024-04-14', 'flexible', 8, 0, 0, 'Tích hợp cổng thanh toán', 'approved', 2],
    [5, 3, 12, '2024-04-15', 'flexible', 8, 0, 0, 'Module quản lý khách hàng', 'approved', 1],
    [5, 1, 3, '2024-04-16', 'morning', 4, 0, 0, 'Xử lý refund flow', 'approved', 2],
    [5, 3, 12, '2024-04-16', 'afternoon', 4, 0, 0, 'API CRUD khách hàng', 'approved', 1],
    [5, 1, 3, '2024-04-17', 'flexible', 8, 0, 0, 'Testing payment flow end-to-end', 'pending', null],
    // User 6 - Hoàng Văn Dũng
    [6, 1, 5, '2024-04-14', 'flexible', 6, 0, 0, 'Responsive design trang chủ', 'approved', 2],
    [6, 1, 5, '2024-04-15', 'flexible', 8, 0, 0, 'Responsive design trang sản phẩm', 'approved', 2],
    [6, 3, NULL, '2024-04-16', 'flexible', 8, 0, 0, 'UI design dashboard CRM', 'approved', 1],
    [6, 1, 5, '2024-04-17', 'flexible', 4, 0, 0, 'Fix UI bugs mobile', 'pending', null],
    // User 7 - Vũ Thị Hương
    [7, 1, 4, '2024-04-15', 'flexible', 8, 0, 0, 'UAT testing giỏ hàng', 'approved', 2],
    [7, 2, 10, '2024-04-16', 'flexible', 8, 0, 0, 'Test app trên Samsung Galaxy', 'approved', 2],
    [7, 1, 4, '2024-04-17', 'flexible', 8, 0, 0, 'Log bug và verify fix', 'pending', null],
    // User 8 - Đặng Quốc Bảo
    [8, 2, 8, '2024-04-14', 'flexible', 8, 0, 0, 'Phát triển module barcode scanner', 'approved', 2],
    [8, 2, 8, '2024-04-15', 'flexible', 8, 0, 0, 'Optimize camera performance', 'approved', 2],
    [8, 2, 8, '2024-04-15', 'evening', 2, 1, 2, 'Fix crash trên Android', 'approved', 2],
    [8, 3, 11, '2024-04-16', 'flexible', 8, 0, 0, 'Review và cập nhật schema', 'approved', 1],
    [8, 3, 13, '2024-04-17', 'flexible', 8, 0, 0, 'Dashboard KPI charts', 'pending', null],
];

$stmt = $pdo->prepare("INSERT INTO timesheets (user_id, project_id, task_id, work_date, shift, hours_worked, is_overtime, overtime_hours, description, status, approved_by) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
foreach ($timesheetData as $ts) {
    $stmt->execute($ts);
}

echo "\n✅ Seed hoàn tất! Database đã được nạp dữ liệu mẫu.\n";
echo "Tài khoản đăng nhập:\n";
echo "  admin@demo.com / password (Admin)\n";
echo "  pm@demo.com / password (PM)\n";
echo "  hr@demo.com / password (HR)\n";
echo "  staff@demo.com / password (Staff)\n";
echo "</pre>";
