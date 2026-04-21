<?php
namespace App\Core;

class Router
{
    private $pdo;
    private $config;

    public function __construct($pdo, $config)
    {
        $this->pdo = $pdo;
        $this->config = $config;
    }

    public function dispatch()
    {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $parts = $url ? explode('/', $url) : [];

        // Default controller and action
        $controllerName = !empty($parts[0]) ? ucfirst($parts[0]) : 'Dashboard';
        $action = !empty($parts[1]) ? $parts[1] : 'index';
        $params = array_slice($parts, 2);

        // Map controller names
        $controllerMap = [
            'Dashboard'  => 'DashboardController',
            'Auth'       => 'AuthController',
            'Users'      => 'UserController',
            'Projects'   => 'ProjectController',
            'Tasks'      => 'TaskController',
            'Timesheets' => 'TimesheetController',
            'Resources'  => 'ResourceController',
            'Reports'    => 'ReportController',
            'Excel'      => 'ExcelController',
        ];

        $controllerClass = $controllerMap[$controllerName] ?? null;

        if (!$controllerClass) {
            $this->error404();
            return;
        }

        $fullClass = "App\\Controllers\\{$controllerClass}";

        if (!class_exists($fullClass)) {
            $this->error404();
            return;
        }

        $controller = new $fullClass($this->pdo, $this->config);

        // Check authentication (except for Auth controller)
        if ($controllerName !== 'Auth') {
            $auth = new Auth($this->pdo);
            if (!$auth->isLoggedIn()) {
                header('Location: ' . $this->config['base_url'] . '/Auth/login');
                exit;
            }
            $controller->setAuth($auth);

            // Check middleware / permissions
            $middleware = new Middleware($auth);
            if (!$middleware->checkAccess($controllerName, $action)) {
                http_response_code(403);
                $controller->renderError('403', 'Bạn không có quyền truy cập chức năng này.');
                return;
            }
        }

        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        } else {
            $this->error404();
        }
    }

    private function error404()
    {
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="vi"><head><meta charset="UTF-8"><title>404</title>';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '</head><body class="bg-dark text-white d-flex align-items-center justify-content-center min-vh-100">';
        echo '<div class="text-center"><h1 class="display-1 fw-bold">404</h1>';
        echo '<p class="fs-4">Không tìm thấy trang yêu cầu</p>';
        echo '<a href="' . $this->config['base_url'] . '" class="btn btn-primary">Quay lại Trang chủ</a>';
        echo '</div></body></html>';
    }
}
