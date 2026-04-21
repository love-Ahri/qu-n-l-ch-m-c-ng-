<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;

class AuthController extends Controller
{
    public function login()
    {
        // If already logged in, redirect to dashboard
        $auth = new Auth($this->pdo);
        if ($auth->isLoggedIn()) {
            $this->redirect('/');
            return;
        }

        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        $this->smarty->assign('page_title', 'Đăng nhập');
        $this->smarty->assign('error', $error);
        $this->smarty->display('auth/login.tpl');
    }

    public function doLogin()
    {
        $this->validateCsrf();

        $email = $this->sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Vui lòng nhập email và mật khẩu.';
            $this->redirect('Auth/login');
            return;
        }

        $auth = new Auth($this->pdo);
        $result = $auth->attempt($email, $password);

        if ($result['success']) {
            // Log login action
            $stmt = $this->pdo->prepare(
                "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip_address, user_agent, created_at) 
                 VALUES (:uid, 'login', 'user', :uid2, :ip, :ua, NOW())"
            );
            $stmt->execute([
                'uid' => $_SESSION['user_id'],
                'uid2' => $_SESSION['user_id'],
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'ua' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            ]);

            $this->redirect('/');
        } else {
            $_SESSION['login_error'] = $result['message'];
            $this->redirect('Auth/login');
        }
    }

    public function logout()
    {
        $auth = new Auth($this->pdo);

        if ($auth->isLoggedIn()) {
            // Log logout action
            $stmt = $this->pdo->prepare(
                "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip_address, created_at)
                 VALUES (:uid, 'logout', 'user', :uid2, :ip, NOW())"
            );
            $stmt->execute([
                'uid' => $auth->userId(),
                'uid2' => $auth->userId(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            ]);
        }

        $auth->logout();
        $this->redirect('Auth/login');
    }
}
