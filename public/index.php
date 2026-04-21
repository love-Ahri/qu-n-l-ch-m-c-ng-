<?php
/**
 * Front Controller - Entry point for all requests
 * Hệ thống Quản lý Dự Án & Chấm Công
 */
session_start();

// Error reporting for development
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Autoload
require_once BASE_PATH . '/vendor/autoload.php';

// Load configuration
$appConfig = require BASE_PATH . '/config/app.php';
$dbConfig  = require BASE_PATH . '/config/database.php';

// Set timezone
date_default_timezone_set($appConfig['timezone']);

// Initialize database connection
try {
    $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $dbConfig['options']);
} catch (PDOException $e) {
    die('Lỗi kết nối database: ' . $e->getMessage());
}

// Store in global registry
$GLOBALS['pdo'] = $pdo;
$GLOBALS['config'] = $appConfig;

// Initialize Router and dispatch
use App\Core\Router;

$router = new Router($pdo, $appConfig);
$router->dispatch();
