<?php
namespace App\Core;

use Smarty;

class Controller
{
    protected $pdo;
    protected $config;
    protected $smarty;
    protected $auth;

    public function __construct($pdo, $config)
    {
        $this->pdo = $pdo;
        $this->config = $config;
        $this->initSmarty();
    }

    private function initSmarty()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(BASE_PATH . '/templates');
        $this->smarty->setCompileDir(BASE_PATH . '/templates_c');
        $this->smarty->setCacheDir(BASE_PATH . '/cache');

        // Create dirs if not exist
        foreach ([BASE_PATH . '/templates_c', BASE_PATH . '/cache'] as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        // Allow PHP functions in {if} expressions
        $this->smarty->registerPlugin('function', 'php_in_array', function($params) {
            return in_array($params['needle'], $params['haystack'] ?? []) ? 'true' : 'false';
        });

        // Register PHP functions as Smarty modifiers
        $this->smarty->registerPlugin('modifier', 'json_encode', function($data) {
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
        });
        $this->smarty->registerPlugin('modifier', 'explode', function($string, $delimiter = ',') {
            return explode($delimiter, $string);
        });
        $this->smarty->registerPlugin('modifier', 'in_array', function($needle, $haystack) {
            return is_array($haystack) && in_array($needle, $haystack);
        });
        $this->smarty->registerPlugin('modifier', 'min', function($a, $b) {
            return min($a, $b);
        });
        $this->smarty->registerPlugin('modifier', 'str_pad', function($input, $length, $pad = ' ', $type = STR_PAD_RIGHT) {
            return str_pad($input, $length, $pad, $type);
        });
        $this->smarty->registerPlugin('modifier', 'mb_substr', function($string, $start, $length = null) {
            return mb_substr($string, $start, $length, 'UTF-8');
        });

        // Determine active menu from URL
        $url = $_GET['url'] ?? '';
        $urlParts = explode('/', trim($url, '/'));
        $menuMap = [
            'Users' => 'users', 'Projects' => 'projects', 'Tasks' => 'tasks',
            'Timesheets' => 'timesheets', 'Resources' => 'resources',
            'Reports' => 'reports', 'Excel' => 'excel',
        ];
        $activeMenu = $menuMap[$urlParts[0] ?? ''] ?? 'dashboard';

        // Assign global variables
        $this->smarty->assign('base_url', $this->config['base_url']);
        $this->smarty->assign('app_name', $this->config['name']);
        $this->smarty->assign('csrf_token', $this->generateCsrfToken());
        $this->smarty->assign('active_menu', $activeMenu);
    }

    public function setAuth(Auth $auth)
    {
        $this->auth = $auth;
        $this->smarty->assign('current_user', $auth->user());
        $this->smarty->assign('user_role', $auth->user()['role'] ?? '');
        $this->smarty->assign('user_roles', $auth->user()['roles'] ?? []);
    }

    protected function render($template, $data = [])
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        $this->smarty->display($template);
    }

    protected function redirect($path)
    {
        header('Location: ' . $this->config['base_url'] . '/' . ltrim($path, '/'));
        exit;
    }

    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrf()
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token không hợp lệ');
        }
    }

    protected function sanitize($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public function renderError($code, $message)
    {
        $this->render('layout/error.tpl', [
            'error_code'    => $code,
            'error_message' => $message,
        ]);
    }

    /**
     * Log an action to audit_logs table
     */
    protected function logAction($action, $entityType, $entityId = null, $oldValues = null, $newValues = null)
    {
        $userId = $this->auth ? $this->auth->userId() : null;
        $stmt = $this->pdo->prepare(
            "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent)
             VALUES (:uid, :action, :etype, :eid, :old_val, :new_val, :ip, :ua)"
        );
        $stmt->execute([
            'uid'     => $userId,
            'action'  => $action,
            'etype'   => $entityType,
            'eid'     => $entityId,
            'old_val' => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
            'new_val' => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
            'ip'      => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'ua'      => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        ]);
    }

    protected function uploadFile($file, $directory, $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'])
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        $uploadDir = $this->config['upload_path'] . '/' . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $ext;
        $destination = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $directory . '/' . $filename;
        }

        return null;
    }

    /**
     * Get flash message and clear it
     */
    protected function getFlash()
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * Set flash message
     */
    protected function setFlash($type, $message)
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}
