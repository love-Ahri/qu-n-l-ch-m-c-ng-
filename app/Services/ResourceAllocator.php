<?php
namespace App\Services;

class ResourceAllocator
{
    private $pdo;
    private $maxWeeklyHours = 40;
    private $maxDailyHours = 8;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get weekly allocation matrix: users × days
     */
    public function getWeeklyAllocation($weekStart)
    {
        $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));

        // Get all active users who have timesheets or tasks in this period
        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT u.id, u.name, u.department
             FROM users u
             WHERE u.is_active = 1
             AND (
                EXISTS (SELECT 1 FROM timesheets ts WHERE ts.user_id = u.id AND ts.work_date BETWEEN :ws AND :we)
                OR EXISTS (SELECT 1 FROM tasks t WHERE t.assigned_to = u.id AND t.status IN ('doing','review','todo')
                           AND t.start_date <= :we2 AND (t.due_date >= :ws2 OR t.due_date IS NULL))
             )
             ORDER BY u.name"
        );
        $stmt->execute(['ws' => $weekStart, 'we' => $weekEnd, 'ws2' => $weekStart, 'we2' => $weekEnd]);
        $users = $stmt->fetchAll();

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = date('Y-m-d', strtotime($weekStart . " +{$i} days"));
        }

        $matrix = [];
        foreach ($users as $user) {
            $row = [
                'user' => $user,
                'days' => [],
                'total_hours' => 0,
                'is_overbooked' => false,
            ];

            foreach ($days as $day) {
                // Get timesheets for this user/day
                $stmt = $this->pdo->prepare(
                    "SELECT ts.hours_worked, ts.is_overtime, ts.overtime_hours, ts.shift,
                            p.name as project_name, p.code as project_code,
                            t.title as task_title
                     FROM timesheets ts
                     INNER JOIN projects p ON ts.project_id = p.id
                     LEFT JOIN tasks t ON ts.task_id = t.id
                     WHERE ts.user_id = :uid AND ts.work_date = :d
                     ORDER BY ts.shift"
                );
                $stmt->execute(['uid' => $user['id'], 'd' => $day]);
                $entries = $stmt->fetchAll();

                $dayHours = array_sum(array_column($entries, 'hours_worked'));
                $dayStatus = 'normal';
                if ($dayHours > 10) $dayStatus = 'danger';
                elseif ($dayHours > $this->maxDailyHours) $dayStatus = 'warning';

                $row['days'][$day] = [
                    'hours' => $dayHours,
                    'entries' => $entries,
                    'status' => $dayStatus,
                ];
                $row['total_hours'] += $dayHours;
            }

            $row['is_overbooked'] = $row['total_hours'] > $this->maxWeeklyHours;
            $row['week_status'] = $row['total_hours'] > $this->maxWeeklyHours ? 'danger' 
                : ($row['total_hours'] > 35 ? 'warning' : 'normal');
            $matrix[] = $row;
        }

        return [
            'days' => $days,
            'matrix' => $matrix,
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
        ];
    }

    /**
     * Detect overbooking issues
     */
    public function detectOverbooking($weekStart)
    {
        $allocation = $this->getWeeklyAllocation($weekStart);
        $alerts = [];

        foreach ($allocation['matrix'] as $row) {
            if ($row['is_overbooked']) {
                $alerts[] = [
                    'type' => 'weekly_overbooked',
                    'user' => $row['user'],
                    'total_hours' => $row['total_hours'],
                    'max_hours' => $this->maxWeeklyHours,
                    'message' => "{$row['user']['name']} đã vượt {$this->maxWeeklyHours}h/tuần ({$row['total_hours']}h)",
                ];
            }

            foreach ($row['days'] as $day => $dayData) {
                if ($dayData['status'] === 'danger') {
                    $alerts[] = [
                        'type' => 'daily_overbooked',
                        'user' => $row['user'],
                        'date' => $day,
                        'hours' => $dayData['hours'],
                        'message' => "{$row['user']['name']} làm {$dayData['hours']}h ngày " . date('d/m', strtotime($day)),
                    ];
                }
            }
        }

        return $alerts;
    }
}
