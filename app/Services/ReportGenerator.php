<?php
namespace App\Services;

class ReportGenerator
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Individual report
     */
    public function getIndividualReport($userId, $startDate, $endDate)
    {
        // Summary
        $summary = $this->queryOne(
            "SELECT COALESCE(SUM(hours_worked), 0) as total_hours,
                    COALESCE(SUM(overtime_hours), 0) as total_ot,
                    COUNT(DISTINCT work_date) as working_days,
                    COUNT(DISTINCT project_id) as project_count
             FROM timesheets
             WHERE user_id = :uid AND work_date BETWEEN :sd AND :ed AND status = 'approved'",
            ['uid' => $userId, 'sd' => $startDate, 'ed' => $endDate]
        );

        // Tasks completed
        $tasks = $this->queryOne(
            "SELECT COUNT(*) as total_tasks,
                    SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done_tasks
             FROM tasks WHERE assigned_to = :uid
             AND updated_at BETWEEN :sd AND :ed",
            ['uid' => $userId, 'sd' => $startDate, 'ed' => "{$endDate} 23:59:59"]
        );

        // Weekly breakdown
        $weekly = $this->query(
            "SELECT YEARWEEK(work_date, 1) as yw,
                    MIN(work_date) as week_start,
                    SUM(hours_worked) as hours,
                    SUM(overtime_hours) as ot
             FROM timesheets
             WHERE user_id = :uid AND work_date BETWEEN :sd AND :ed AND status = 'approved'
             GROUP BY YEARWEEK(work_date, 1)
             ORDER BY yw",
            ['uid' => $userId, 'sd' => $startDate, 'ed' => $endDate]
        );

        // By project
        $byProject = $this->query(
            "SELECT p.name as project_name, p.code as project_code,
                    SUM(ts.hours_worked) as hours, SUM(ts.overtime_hours) as ot
             FROM timesheets ts
             INNER JOIN projects p ON ts.project_id = p.id
             WHERE ts.user_id = :uid AND ts.work_date BETWEEN :sd AND :ed AND ts.status = 'approved'
             GROUP BY p.id, p.name, p.code
             ORDER BY hours DESC",
            ['uid' => $userId, 'sd' => $startDate, 'ed' => $endDate]
        );

        return [
            'summary' => $summary,
            'tasks' => $tasks,
            'weekly' => $weekly,
            'by_project' => $byProject,
        ];
    }

    /**
     * Team report (by project)
     */
    public function getTeamReport($projectId, $startDate, $endDate)
    {
        // Project info
        $project = $this->queryOne("SELECT * FROM projects WHERE id = :pid", ['pid' => $projectId]);

        // Member hours
        $members = $this->query(
            "SELECT u.id, u.name, u.department, pm.project_role,
                    COALESCE(SUM(ts.hours_worked), 0) as total_hours,
                    COALESCE(SUM(ts.overtime_hours), 0) as total_ot,
                    (SELECT COUNT(*) FROM tasks WHERE assigned_to = u.id AND project_id = :pid1 AND status = 'done') as done_tasks,
                    (SELECT COUNT(*) FROM tasks WHERE assigned_to = u.id AND project_id = :pid2) as total_tasks
             FROM project_members pm
             INNER JOIN users u ON pm.user_id = u.id
             LEFT JOIN timesheets ts ON ts.user_id = u.id AND ts.project_id = pm.project_id
                 AND ts.work_date BETWEEN :sd AND :ed AND ts.status = 'approved'
             WHERE pm.project_id = :pid3
             GROUP BY u.id, u.name, u.department, pm.project_role
             ORDER BY total_hours DESC",
            ['pid1' => $projectId, 'pid2' => $projectId, 'pid3' => $projectId, 'sd' => $startDate, 'ed' => $endDate]
        );

        // Task status summary
        $taskStats = $this->queryOne(
            "SELECT COUNT(*) as total,
                    SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done,
                    SUM(CASE WHEN status = 'doing' THEN 1 ELSE 0 END) as doing,
                    SUM(CASE WHEN status = 'todo' THEN 1 ELSE 0 END) as todo,
                    SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) as review,
                    SUM(CASE WHEN due_date < CURDATE() AND status != 'done' THEN 1 ELSE 0 END) as overdue
             FROM tasks WHERE project_id = :pid",
            ['pid' => $projectId]
        );

        return [
            'project' => $project,
            'members' => $members,
            'task_stats' => $taskStats,
        ];
    }

    /**
     * Cost report
     */
    public function getCostReport($startDate, $endDate, $projectId = null)
    {
        $where = "ts.work_date BETWEEN :sd AND :ed AND ts.status = 'approved'";
        $params = ['sd' => $startDate, 'ed' => $endDate];

        if ($projectId) {
            $where .= " AND ts.project_id = :pid";
            $params['pid'] = $projectId;
        }

        // By project
        $byProject = $this->query(
            "SELECT p.id, p.name, p.code, p.budget,
                    SUM(ts.hours_worked) as total_hours,
                    SUM(ts.overtime_hours) as total_ot
             FROM timesheets ts
             INNER JOIN projects p ON ts.project_id = p.id
             WHERE {$where}
             GROUP BY p.id, p.name, p.code, p.budget
             ORDER BY total_hours DESC",
            $params
        );

        // By department
        $byDepartment = $this->query(
            "SELECT u.department, SUM(ts.hours_worked) as total_hours,
                    SUM(ts.overtime_hours) as total_ot
             FROM timesheets ts
             INNER JOIN users u ON ts.user_id = u.id
             WHERE {$where}
             GROUP BY u.department
             ORDER BY total_hours DESC",
            $params
        );

        return [
            'by_project' => $byProject,
            'by_department' => $byDepartment,
        ];
    }

    private function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function queryOne($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}
