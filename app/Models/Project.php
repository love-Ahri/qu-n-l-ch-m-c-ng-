<?php
namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected $table = 'projects';

    /**
     * Find project with members and stats
     */
    public function findWithDetails($id)
    {
        $project = $this->find($id);
        if (!$project) return null;

        // Members
        $project['members'] = $this->query(
            "SELECT pm.*, u.name, u.email, u.avatar, u.department
             FROM project_members pm
             INNER JOIN users u ON pm.user_id = u.id
             WHERE pm.project_id = :pid ORDER BY pm.project_role, u.name",
            ['pid' => $id]
        );

        // Task stats
        $project['task_stats'] = $this->queryOne(
            "SELECT 
                COUNT(*) as total_tasks,
                SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done_tasks,
                SUM(CASE WHEN status = 'doing' THEN 1 ELSE 0 END) as doing_tasks,
                SUM(CASE WHEN status = 'todo' THEN 1 ELSE 0 END) as todo_tasks,
                SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) as review_tasks,
                SUM(estimated_hours) as total_estimated_hours
             FROM tasks WHERE project_id = :pid",
            ['pid' => $id]
        );

        // Total hours worked
        $project['hours_worked'] = $this->queryOne(
            "SELECT COALESCE(SUM(hours_worked), 0) as total,
                    COALESCE(SUM(overtime_hours), 0) as total_ot
             FROM timesheets WHERE project_id = :pid AND status = 'approved'",
            ['pid' => $id]
        );

        return $project;
    }

    /**
     * Search projects with filters
     */
    public function search($keyword = '', $status = '', $userId = null, $page = 1, $perPage = 15)
    {
        $where = '1=1';
        $params = [];

        if ($keyword) {
            $where .= " AND (p.name LIKE :kw OR p.code LIKE :kw2 OR p.client_name LIKE :kw3)";
            $params['kw'] = "%{$keyword}%";
            $params['kw2'] = "%{$keyword}%";
            $params['kw3'] = "%{$keyword}%";
        }
        if ($status) {
            $where .= " AND p.status = :status";
            $params['status'] = $status;
        }
        if ($userId) {
            $where .= " AND p.id IN (SELECT project_id FROM project_members WHERE user_id = :uid)";
            $params['uid'] = $userId;
        }

        $countSql = "SELECT COUNT(*) as total FROM projects p WHERE {$where}";
        $stmt = $this->pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];

        $totalPages = max(1, ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT p.*, 
                    (SELECT COUNT(*) FROM tasks WHERE project_id = p.id) as task_count,
                    (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND status = 'done') as done_count,
                    (SELECT COUNT(*) FROM project_members WHERE project_id = p.id) as member_count,
                    u.name as creator_name
                FROM projects p
                LEFT JOIN users u ON p.created_by = u.id
                WHERE {$where}
                ORDER BY p.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        return [
            'data' => $data, 'total' => $total, 'per_page' => $perPage,
            'current_page' => $page, 'total_pages' => $totalPages,
            'has_prev' => $page > 1, 'has_next' => $page < $totalPages,
        ];
    }

    /**
     * Get projects accessible by a user (for dropdowns)
     */
    public function getByUser($userId, $isAdmin = false)
    {
        if ($isAdmin) {
            return $this->query("SELECT id, name, code FROM projects WHERE status != 'cancelled' ORDER BY name");
        }
        return $this->query(
            "SELECT p.id, p.name, p.code FROM projects p
             INNER JOIN project_members pm ON p.id = pm.project_id
             WHERE pm.user_id = :uid AND p.status != 'cancelled'
             ORDER BY p.name",
            ['uid' => $userId]
        );
    }

    /**
     * Check if user is project manager
     */
    public function isManager($projectId, $userId)
    {
        $result = $this->queryOne(
            "SELECT COUNT(*) as cnt FROM project_members 
             WHERE project_id = :pid AND user_id = :uid AND project_role = 'manager'",
            ['pid' => $projectId, 'uid' => $userId]
        );
        return $result['cnt'] > 0;
    }

    /**
     * Check if user is member of project
     */
    public function isMember($projectId, $userId)
    {
        $result = $this->queryOne(
            "SELECT COUNT(*) as cnt FROM project_members WHERE project_id = :pid AND user_id = :uid",
            ['pid' => $projectId, 'uid' => $userId]
        );
        return $result['cnt'] > 0;
    }
}
