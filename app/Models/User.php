<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';

    /**
     * Find user with their roles
     */
    public function findWithRoles($id)
    {
        $user = $this->find($id);
        if (!$user) return null;

        $stmt = $this->pdo->prepare(
            "SELECT r.* FROM roles r 
             INNER JOIN user_roles ur ON r.id = ur.role_id 
             WHERE ur.user_id = :uid ORDER BY r.id"
        );
        $stmt->execute(['uid' => $id]);
        $user['roles'] = $stmt->fetchAll();
        $user['role_names'] = array_column($user['roles'], 'name');
        return $user;
    }

    /**
     * Search users with filters and pagination
     */
    public function search($keyword = '', $department = '', $status = '', $page = 1, $perPage = 15)
    {
        $where = '1=1';
        $params = [];

        if ($keyword) {
            $where .= " AND (u.name LIKE :kw OR u.email LIKE :kw2 OR u.phone LIKE :kw3)";
            $params['kw'] = "%{$keyword}%";
            $params['kw2'] = "%{$keyword}%";
            $params['kw3'] = "%{$keyword}%";
        }
        if ($department) {
            $where .= " AND u.department = :dept";
            $params['dept'] = $department;
        }
        if ($status !== '') {
            $where .= " AND u.is_active = :status";
            $params['status'] = (int)$status;
        }

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM users u WHERE {$where}";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];

        $totalPages = max(1, ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        // Fetch with roles
        $sql = "SELECT u.*, GROUP_CONCAT(r.display_name SEPARATOR ', ') as role_display,
                       GROUP_CONCAT(r.name SEPARATOR ',') as role_names
                FROM users u
                LEFT JOIN user_roles ur ON u.id = ur.user_id
                LEFT JOIN roles r ON ur.role_id = r.id
                WHERE {$where}
                GROUP BY u.id
                ORDER BY u.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        // Post-process: split role_names into array
        foreach ($data as &$row) {
            $row['role_list'] = $row['role_names'] ? array_map('trim', explode(',', $row['role_names'])) : [];
        }

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
        ];
    }

    /**
     * Get all departments (distinct)
     */
    public function getDepartments()
    {
        return $this->query(
            "SELECT DISTINCT department FROM users WHERE department IS NOT NULL AND department != '' ORDER BY department"
        );
    }

    /**
     * Assign roles to user
     */
    public function assignRoles($userId, $roleIds)
    {
        $this->execute("DELETE FROM user_roles WHERE user_id = :uid", ['uid' => $userId]);
        foreach ($roleIds as $roleId) {
            $this->execute(
                "INSERT INTO user_roles (user_id, role_id) VALUES (:uid, :rid)",
                ['uid' => $userId, 'rid' => $roleId]
            );
        }
    }

    /**
     * Get effective hourly rate for a user at a given date
     */
    public function getHourlyRate($userId, $date = null)
    {
        $date = $date ?: date('Y-m-d');

        // Check user-specific rate first
        $rate = $this->queryOne(
            "SELECT rate_amount FROM hourly_rates 
             WHERE user_id = :uid AND effective_from <= :d 
             AND (effective_to IS NULL OR effective_to >= :d2)
             ORDER BY effective_from DESC LIMIT 1",
            ['uid' => $userId, 'd' => $date, 'd2' => $date]
        );
        if ($rate) return (float)$rate['rate_amount'];

        // Fall back to role-based rate
        $rate = $this->queryOne(
            "SELECT hr.rate_amount FROM hourly_rates hr
             INNER JOIN user_roles ur ON hr.role_id = ur.role_id
             WHERE ur.user_id = :uid AND hr.user_id IS NULL
             AND hr.effective_from <= :d AND (hr.effective_to IS NULL OR hr.effective_to >= :d2)
             ORDER BY hr.effective_from DESC LIMIT 1",
            ['uid' => $userId, 'd' => $date, 'd2' => $date]
        );
        if ($rate) return (float)$rate['rate_amount'];

        return 250000; // default 250k/h
    }

    /**
     * Check if email exists (for validation)
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as cnt FROM users WHERE email = :email";
        $params = ['email' => $email];
        if ($excludeId) {
            $sql .= " AND id != :eid";
            $params['eid'] = $excludeId;
        }
        $result = $this->queryOne($sql, $params);
        return $result['cnt'] > 0;
    }

    /**
     * Get all active users (for dropdowns)
     */
    public function getActiveUsers()
    {
        return $this->query("SELECT id, name, email, department FROM users WHERE is_active = 1 ORDER BY name");
    }
}
