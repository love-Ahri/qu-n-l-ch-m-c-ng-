<?php
namespace App\Core;

class Auth
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Attempt login with email and password
     */
    public function attempt($email, $password)
    {
        // Check brute force lock
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user) {
            return ['success' => false, 'message' => 'Email hoặc mật khẩu không đúng.'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'Tài khoản đã bị khóa.'];
        }

        // Check lock
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $remain = ceil((strtotime($user['locked_until']) - time()) / 60);
            return ['success' => false, 'message' => "Tài khoản bị khóa tạm thời. Thử lại sau {$remain} phút."];
        }

        if (!password_verify($password, $user['password'])) {
            // Increment failed attempts
            $attempts = $user['login_attempts'] + 1;
            $lockUntil = null;
            if ($attempts >= 5) {
                $lockUntil = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                $attempts = 0;
            }
            $stmt = $this->pdo->prepare(
                "UPDATE users SET login_attempts = :attempts, locked_until = :locked WHERE id = :id"
            );
            $stmt->execute([
                'attempts' => $attempts,
                'locked'   => $lockUntil,
                'id'       => $user['id'],
            ]);
            $remaining = 5 - $attempts;
            if ($lockUntil) {
                return ['success' => false, 'message' => 'Đăng nhập sai 5 lần. Tài khoản bị khóa 15 phút.'];
            }
            return ['success' => false, 'message' => "Email hoặc mật khẩu không đúng. Còn {$remaining} lần thử."];
        }

        // Reset failed attempts
        $stmt = $this->pdo->prepare(
            "UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = :id"
        );
        $stmt->execute(['id' => $user['id']]);

        // Load user roles
        $roles = $this->getUserRoles($user['id']);
        $primaryRole = $roles[0]['name'] ?? 'staff';

        // Set session
        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_name']   = $user['name'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_role']   = $primaryRole;
        $_SESSION['user_roles']  = array_column($roles, 'name');
        $_SESSION['user_avatar'] = $user['avatar'];
        $_SESSION['user_dept']   = $user['department'];

        return ['success' => true, 'message' => 'Đăng nhập thành công.'];
    }

    /**
     * Get all roles of a user
     */
    public function getUserRoles($userId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.* FROM roles r 
             INNER JOIN user_roles ur ON r.id = ur.role_id 
             WHERE ur.user_id = :uid 
             ORDER BY r.id ASC"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll();
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function user()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        return [
            'id'         => $_SESSION['user_id'],
            'name'       => $_SESSION['user_name'],
            'email'      => $_SESSION['user_email'],
            'role'       => $_SESSION['user_role'],
            'roles'      => $_SESSION['user_roles'] ?? [],
            'avatar'     => $_SESSION['user_avatar'] ?? null,
            'department' => $_SESSION['user_dept'] ?? null,
        ];
    }

    public function hasRole($role)
    {
        $roles = $_SESSION['user_roles'] ?? [];
        return in_array($role, $roles);
    }

    public function hasAnyRole($roles)
    {
        $userRoles = $_SESSION['user_roles'] ?? [];
        return !empty(array_intersect($roles, $userRoles));
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function userId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
    }
}
