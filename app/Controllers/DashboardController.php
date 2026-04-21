<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\CostCalculator;

class DashboardController extends Controller
{
    public function index()
    {
        $user = $this->auth->user();
        $isAdmin = $this->auth->isAdmin();
        $userId = $user['id'];

        // Stats
        $projectCount = $this->pdo->query("SELECT COUNT(*) as cnt FROM projects WHERE status = 'active'")->fetch()['cnt'];
        $taskCount = $this->pdo->query("SELECT COUNT(*) as cnt FROM tasks WHERE status IN ('doing','review')")->fetch()['cnt'];
        $userCount = $this->pdo->query("SELECT COUNT(*) as cnt FROM users WHERE is_active = 1")->fetch()['cnt'];

        $pendingTs = $this->pdo->query("SELECT COUNT(*) as cnt FROM timesheets WHERE status = 'pending'")->fetch()['cnt'];

        // My tasks (for staff)
        $myTasks = [];
        if (!$isAdmin) {
            $stmt = $this->pdo->prepare(
                "SELECT t.*, p.name as project_name, p.code as project_code
                 FROM tasks t INNER JOIN projects p ON t.project_id = p.id
                 WHERE t.assigned_to = :uid AND t.status IN ('todo','doing','review')
                 ORDER BY t.priority DESC, t.due_date ASC LIMIT 10"
            );
            $stmt->execute(['uid' => $userId]);
            $myTasks = $stmt->fetchAll();
        }

        // Recent timesheets
        $stmt = $this->pdo->prepare(
            "SELECT ts.*, u.name as user_name, p.name as project_name
             FROM timesheets ts
             INNER JOIN users u ON ts.user_id = u.id
             INNER JOIN projects p ON ts.project_id = p.id
             ORDER BY ts.created_at DESC LIMIT 10"
        );
        $stmt->execute();
        $recentTimesheets = $stmt->fetchAll();

        // Project cost data for chart
        $costCalc = new CostCalculator($this->pdo);
        $projectCosts = $costCalc->getAllProjectsCost();

        // Recent audit logs
        $stmt = $this->pdo->prepare(
            "SELECT al.*, u.name as user_name FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             ORDER BY al.created_at DESC LIMIT 8"
        );
        $stmt->execute();
        $auditLogs = $stmt->fetchAll();

        $flash = $this->getFlash();
        $this->render('dashboard/index.tpl', [
            'page_title'       => 'Tổng quan',
            'project_count'    => $projectCount,
            'task_count'       => $taskCount,
            'user_count'       => $userCount,
            'pending_ts'       => $pendingTs,
            'my_tasks'         => $myTasks,
            'recent_timesheets'=> $recentTimesheets,
            'project_costs'    => $projectCosts,
            'audit_logs'       => $auditLogs,
            'flash'            => $flash,
        ]);
    }
}
