<?php
namespace App\Services;

use App\Models\User;
use App\Models\Timesheet;

class CostCalculator
{
    private $pdo;
    private $userModel;
    private $timesheetModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->timesheetModel = new Timesheet($pdo);
    }

    /**
     * Calculate cost for a single task
     */
    public function getTaskCost($taskId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT ts.user_id, SUM(ts.hours_worked) as hours, SUM(ts.overtime_hours) as ot_hours
             FROM timesheets ts
             WHERE ts.task_id = :tid AND ts.status = 'approved'
             GROUP BY ts.user_id"
        );
        $stmt->execute(['tid' => $taskId]);
        $rows = $stmt->fetchAll();

        $totalCost = 0;
        $details = [];
        foreach ($rows as $row) {
            $rate = $this->userModel->getHourlyRate($row['user_id']);
            $normalCost = ($row['hours'] - $row['ot_hours']) * $rate;
            $otCost = $row['ot_hours'] * $rate * 1.5;
            $cost = $normalCost + $otCost;
            $totalCost += $cost;
            $details[] = [
                'user_id' => $row['user_id'],
                'hours' => (float)$row['hours'],
                'ot_hours' => (float)$row['ot_hours'],
                'rate' => $rate,
                'cost' => $cost,
            ];
        }

        return ['total_cost' => $totalCost, 'details' => $details];
    }

    /**
     * Calculate total cost for a project
     */
    public function getProjectCost($projectId)
    {
        $stmt = $this->pdo->prepare(
            "SELECT ts.user_id, SUM(ts.hours_worked) as hours, SUM(ts.overtime_hours) as ot_hours
             FROM timesheets ts
             WHERE ts.project_id = :pid AND ts.status = 'approved'
             GROUP BY ts.user_id"
        );
        $stmt->execute(['pid' => $projectId]);
        $rows = $stmt->fetchAll();

        $totalCost = 0;
        $totalHours = 0;
        $totalOT = 0;
        $byUser = [];

        foreach ($rows as $row) {
            $rate = $this->userModel->getHourlyRate($row['user_id']);
            $normalHours = $row['hours'] - $row['ot_hours'];
            $normalCost = $normalHours * $rate;
            $otCost = $row['ot_hours'] * $rate * 1.5;
            $cost = $normalCost + $otCost;
            $totalCost += $cost;
            $totalHours += (float)$row['hours'];
            $totalOT += (float)$row['ot_hours'];

            // Get user info
            $user = $this->userModel->find($row['user_id']);
            $byUser[] = [
                'user_id' => $row['user_id'],
                'user_name' => $user['name'] ?? 'N/A',
                'hours' => (float)$row['hours'],
                'ot_hours' => (float)$row['ot_hours'],
                'rate' => $rate,
                'cost' => $cost,
            ];
        }

        return [
            'total_cost' => $totalCost,
            'total_hours' => $totalHours,
            'total_ot' => $totalOT,
            'by_user' => $byUser,
        ];
    }

    /**
     * Get budget usage percentage
     */
    public function getProjectBudgetUsage($projectId)
    {
        $stmt = $this->pdo->prepare("SELECT budget FROM projects WHERE id = :pid");
        $stmt->execute(['pid' => $projectId]);
        $project = $stmt->fetch();

        $cost = $this->getProjectCost($projectId);
        $budget = (float)($project['budget'] ?? 0);
        $usage = $budget > 0 ? ($cost['total_cost'] / $budget * 100) : 0;

        return [
            'budget' => $budget,
            'actual_cost' => $cost['total_cost'],
            'usage_percent' => round($usage, 1),
            'remaining' => $budget - $cost['total_cost'],
        ];
    }

    /**
     * Get cost breakdown for all active projects (for dashboard)
     */
    public function getAllProjectsCost()
    {
        $stmt = $this->pdo->query(
            "SELECT id, name, code, budget, status FROM projects WHERE status IN ('active', 'completed') ORDER BY name"
        );
        $projects = $stmt->fetchAll();

        $result = [];
        foreach ($projects as $p) {
            $cost = $this->getProjectCost($p['id']);
            $result[] = [
                'project_id' => $p['id'],
                'project_name' => $p['name'],
                'project_code' => $p['code'],
                'budget' => (float)$p['budget'],
                'actual_cost' => $cost['total_cost'],
                'total_hours' => $cost['total_hours'],
                'status' => $p['status'],
            ];
        }
        return $result;
    }
}
