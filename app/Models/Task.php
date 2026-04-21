<?php
namespace App\Models;

use App\Core\Model;

class Task extends Model
{
    protected $table = 'tasks';

    /**
     * Get tasks by project with assignee info, optionally filtered by status
     */
    public function getByProject($projectId, $status = null, $assignedTo = null)
    {
        $where = "t.project_id = :pid";
        $params = ['pid' => $projectId];

        if ($status) {
            $where .= " AND t.status = :status";
            $params['status'] = $status;
        }
        if ($assignedTo) {
            $where .= " AND t.assigned_to = :uid";
            $params['uid'] = $assignedTo;
        }

        return $this->query(
            "SELECT t.*, u.name as assignee_name, u.avatar as assignee_avatar,
                    cr.name as creator_name,
                    COALESCE((SELECT SUM(hours_worked) FROM timesheets WHERE task_id = t.id AND status = 'approved'), 0) as actual_hours
             FROM tasks t
             LEFT JOIN users u ON t.assigned_to = u.id
             LEFT JOIN users cr ON t.created_by = cr.id
             WHERE {$where}
             ORDER BY t.sort_order ASC, t.priority DESC, t.id DESC",
            $params
        );
    }

    /**
     * Get tasks grouped by status for Kanban board
     */
    public function getKanbanData($projectId, $assignedTo = null)
    {
        $statuses = ['todo', 'doing', 'review', 'done'];
        $result = [];
        foreach ($statuses as $s) {
            $result[$s] = $this->getByProject($projectId, $s, $assignedTo);
        }
        return $result;
    }

    /**
     * Get tasks assigned to a user across all projects
     */
    public function getByUser($userId, $status = null)
    {
        $where = "t.assigned_to = :uid";
        $params = ['uid' => $userId];

        if ($status) {
            $where .= " AND t.status = :status";
            $params['status'] = $status;
        }

        return $this->query(
            "SELECT t.*, p.name as project_name, p.code as project_code
             FROM tasks t
             INNER JOIN projects p ON t.project_id = p.id
             WHERE {$where}
             ORDER BY t.priority DESC, t.due_date ASC",
            $params
        );
    }

    /**
     * Get tasks for a project (simple list for dropdowns)
     */
    public function getSimpleByProject($projectId)
    {
        return $this->query(
            "SELECT id, title, status FROM tasks WHERE project_id = :pid AND status != 'done' ORDER BY title",
            ['pid' => $projectId]
        );
    }

    /**
     * Update task status
     */
    public function updateStatus($taskId, $newStatus)
    {
        return $this->update($taskId, ['status' => $newStatus]);
    }

    /**
     * Find task with full details
     */
    public function findWithDetails($id)
    {
        return $this->queryOne(
            "SELECT t.*, u.name as assignee_name, p.name as project_name, p.code as project_code,
                    cr.name as creator_name,
                    COALESCE((SELECT SUM(hours_worked) FROM timesheets WHERE task_id = t.id AND status = 'approved'), 0) as actual_hours
             FROM tasks t
             LEFT JOIN users u ON t.assigned_to = u.id
             LEFT JOIN projects p ON t.project_id = p.id
             LEFT JOIN users cr ON t.created_by = cr.id
             WHERE t.id = :id",
            ['id' => $id]
        );
    }
}
