<?php
namespace App\Models;

use App\Core\Model;

class Timesheet extends Model
{
    protected $table = 'timesheets';

    /**
     * Get daily total hours for a user
     */
    public function getDailyHours($userId, $date)
    {
        $result = $this->queryOne(
            "SELECT COALESCE(SUM(hours_worked), 0) as total 
             FROM timesheets WHERE user_id = :uid AND work_date = :d",
            ['uid' => $userId, 'd' => $date]
        );
        return (float)$result['total'];
    }

    /**
     * Get weekly total hours for a user
     */
    public function getWeeklyHours($userId, $weekStart)
    {
        $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
        $result = $this->queryOne(
            "SELECT COALESCE(SUM(hours_worked), 0) as total,
                    COALESCE(SUM(overtime_hours), 0) as total_ot
             FROM timesheets 
             WHERE user_id = :uid AND work_date BETWEEN :ws AND :we",
            ['uid' => $userId, 'ws' => $weekStart, 'we' => $weekEnd]
        );
        return $result;
    }

    /**
     * Get timesheets for a user in a date range with project/task info
     */
    public function getByUserRange($userId, $startDate, $endDate, $status = null)
    {
        $where = "ts.user_id = :uid AND ts.work_date BETWEEN :sd AND :ed";
        $params = ['uid' => $userId, 'sd' => $startDate, 'ed' => $endDate];

        if ($status) {
            $where .= " AND ts.status = :status";
            $params['status'] = $status;
        }

        return $this->query(
            "SELECT ts.*, p.name as project_name, p.code as project_code,
                    t.title as task_title, u.name as approver_name
             FROM timesheets ts
             INNER JOIN projects p ON ts.project_id = p.id
             LEFT JOIN tasks t ON ts.task_id = t.id
             LEFT JOIN users u ON ts.approved_by = u.id
             WHERE {$where}
             ORDER BY ts.work_date DESC, ts.created_at DESC",
            $params
        );
    }

    /**
     * Get timesheets by project
     */
    public function getByProject($projectId, $startDate = null, $endDate = null)
    {
        $where = "ts.project_id = :pid";
        $params = ['pid' => $projectId];

        if ($startDate && $endDate) {
            $where .= " AND ts.work_date BETWEEN :sd AND :ed";
            $params['sd'] = $startDate;
            $params['ed'] = $endDate;
        }

        return $this->query(
            "SELECT ts.*, u.name as user_name, t.title as task_title
             FROM timesheets ts
             INNER JOIN users u ON ts.user_id = u.id
             LEFT JOIN tasks t ON ts.task_id = t.id
             WHERE {$where} AND ts.status = 'approved'
             ORDER BY ts.work_date DESC",
            $params
        );
    }

    /**
     * Get calendar data for a user (month view)
     */
    public function getCalendarData($userId, $year, $month)
    {
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->query(
            "SELECT ts.*, p.name as project_name, p.code as project_code, t.title as task_title
             FROM timesheets ts
             INNER JOIN projects p ON ts.project_id = p.id
             LEFT JOIN tasks t ON ts.task_id = t.id
             WHERE ts.user_id = :uid AND ts.work_date BETWEEN :sd AND :ed
             ORDER BY ts.work_date ASC, ts.shift ASC",
            ['uid' => $userId, 'sd' => $startDate, 'ed' => $endDate]
        );
    }

    /**
     * Get all timesheets for admin/PM approval view
     */
    public function getPendingForProject($projectId = null)
    {
        $where = "ts.status = 'pending'";
        $params = [];

        if ($projectId) {
            $where .= " AND ts.project_id = :pid";
            $params['pid'] = $projectId;
        }

        return $this->query(
            "SELECT ts.*, u.name as user_name, p.name as project_name, t.title as task_title
             FROM timesheets ts
             INNER JOIN users u ON ts.user_id = u.id
             INNER JOIN projects p ON ts.project_id = p.id
             LEFT JOIN tasks t ON ts.task_id = t.id
             WHERE {$where}
             ORDER BY ts.work_date DESC",
            $params
        );
    }

    /**
     * Calculate OT for a given day
     */
    public function calculateOT($userId, $date, $newHours, $maxDaily = 8)
    {
        $existing = $this->getDailyHours($userId, $date);
        $total = $existing + $newHours;
        $overtime = max(0, $total - $maxDaily);
        return [
            'total_daily' => $total,
            'is_overtime' => $overtime > 0,
            'overtime_hours' => $overtime,
            'normal_hours' => min($newHours, max(0, $maxDaily - $existing)),
        ];
    }

    /**
     * Get summary by user for a date range (for reports)
     */
    public function getSummaryByUser($startDate, $endDate, $projectId = null)
    {
        $where = "ts.work_date BETWEEN :sd AND :ed AND ts.status = 'approved'";
        $params = ['sd' => $startDate, 'ed' => $endDate];

        if ($projectId) {
            $where .= " AND ts.project_id = :pid";
            $params['pid'] = $projectId;
        }

        return $this->query(
            "SELECT ts.user_id, u.name as user_name, u.department,
                    SUM(ts.hours_worked) as total_hours,
                    SUM(ts.overtime_hours) as total_ot,
                    COUNT(DISTINCT ts.work_date) as working_days,
                    COUNT(DISTINCT ts.project_id) as project_count
             FROM timesheets ts
             INNER JOIN users u ON ts.user_id = u.id
             WHERE {$where}
             GROUP BY ts.user_id, u.name, u.department
             ORDER BY total_hours DESC",
            $params
        );
    }
}
