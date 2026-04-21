<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Timesheet;
use App\Models\Project;
use App\Models\Task;

class TimesheetController extends Controller
{
    private $tsModel;

    private function init()
    {
        $this->tsModel = new Timesheet($this->pdo);
    }

    public function index()
    {
        $this->init();
        $userId = $this->auth->userId();
        $isAdmin = $this->auth->isAdmin();
        $isPM = $this->auth->hasRole('pm');
        $isHR = $this->auth->hasRole('hr');

        // Pending timesheets for approval (admin/PM)
        $pending = [];
        if ($isAdmin || $isPM || $isHR) {
            $pending = $this->tsModel->getPendingForProject();
        }

        // My recent timesheets
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $myTimesheets = $this->tsModel->getByUserRange($userId, $startDate, $endDate);

        // Weekly summary
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $weeklySummary = $this->tsModel->getWeeklyHours($userId, $weekStart);

        $this->render('timesheets/index.tpl', [
            'page_title'     => 'Chấm công',
            'pending'        => $pending,
            'my_timesheets'  => $myTimesheets,
            'weekly_summary' => $weeklySummary,
            'is_admin'       => $isAdmin,
            'is_pm'          => $isPM,
            'flash'          => $this->getFlash(),
        ]);
    }

    public function create()
    {
        $this->init();
        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        $this->render('timesheets/form.tpl', [
            'page_title' => 'Ghi nhận Chấm công',
            'timesheet'  => null,
            'projects'   => $projects,
            'mode'       => 'create',
        ]);
    }

    public function store()
    {
        $this->init();
        $this->validateCsrf();

        $userId = $this->auth->userId();
        $projectId = (int)($_POST['project_id'] ?? 0);
        $taskId = ($_POST['task_id'] ?? '') ?: null;
        $workDate = $_POST['work_date'] ?? date('Y-m-d');
        $shift = $_POST['shift'] ?? 'flexible';
        $hoursWorked = (float)($_POST['hours_worked'] ?? 0);
        $description = $this->sanitize($_POST['description'] ?? '');

        if ($hoursWorked <= 0 || $hoursWorked > 24) {
            $this->setFlash('danger', 'Số giờ làm không hợp lệ.');
            $this->redirect('Timesheets/create');
            return;
        }

        // Calculate OT
        $otCalc = $this->tsModel->calculateOT($userId, $workDate, $hoursWorked);

        $data = [
            'user_id'        => $userId,
            'project_id'     => $projectId,
            'task_id'        => $taskId,
            'work_date'      => $workDate,
            'shift'          => $shift,
            'hours_worked'   => $hoursWorked,
            'is_overtime'    => $otCalc['is_overtime'] ? 1 : 0,
            'overtime_hours' => $otCalc['overtime_hours'],
            'description'    => $description,
            'status'         => 'pending',
        ];

        $id = $this->tsModel->create($data);
        $this->logAction('create', 'timesheet', $id, null, $data);

        $msg = "Đã ghi nhận {$hoursWorked}h ngày {$workDate}.";
        if ($otCalc['is_overtime']) {
            $msg .= " (OT: {$otCalc['overtime_hours']}h)";
        }
        $this->setFlash('success', $msg);
        $this->redirect('Timesheets');
    }

    public function approve($id)
    {
        $this->init();
        $ts = $this->tsModel->find($id);
        if ($ts && $ts['status'] === 'pending') {
            $this->tsModel->update($id, [
                'status'      => 'approved',
                'approved_by' => $this->auth->userId(),
                'approved_at' => date('Y-m-d H:i:s'),
            ]);
            $this->logAction('approve', 'timesheet', $id, ['status' => 'pending'], ['status' => 'approved']);
            $this->setFlash('success', 'Đã duyệt chấm công.');
        }
        $this->redirect('Timesheets');
    }

    public function reject($id)
    {
        $this->init();
        $ts = $this->tsModel->find($id);
        if ($ts && $ts['status'] === 'pending') {
            $this->tsModel->update($id, [
                'status'      => 'rejected',
                'approved_by' => $this->auth->userId(),
                'approved_at' => date('Y-m-d H:i:s'),
            ]);
            $this->logAction('reject', 'timesheet', $id, ['status' => 'pending'], ['status' => 'rejected']);
            $this->setFlash('warning', 'Đã từ chối chấm công.');
        }
        $this->redirect('Timesheets');
    }

    public function calendar()
    {
        $this->init();
        $userId = (int)($_GET['user_id'] ?? $this->auth->userId());
        $year = (int)($_GET['year'] ?? date('Y'));
        $month = (int)($_GET['month'] ?? date('m'));

        // Security: non-admin can only see their own
        if (!$this->auth->isAdmin() && !$this->auth->hasRole('pm') && !$this->auth->hasRole('hr')) {
            $userId = $this->auth->userId();
        }

        $entries = $this->tsModel->getCalendarData($userId, $year, str_pad($month, 2, '0', STR_PAD_LEFT));

        // Build calendar grid
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = date('t', $firstDay);
        $startDow = (date('N', $firstDay)); // 1=Mon

        // Group entries by date
        $byDate = [];
        foreach ($entries as $e) {
            $byDate[$e['work_date']][] = $e;
        }

        $userModel = new \App\Models\User($this->pdo);
        $viewUser = $userModel->find($userId);

        $this->render('timesheets/calendar.tpl', [
            'page_title'    => 'Lịch Chấm công',
            'year'          => $year,
            'month'         => $month,
            'days_in_month' => $daysInMonth,
            'start_dow'     => $startDow,
            'by_date'       => $byDate,
            'view_user'     => $viewUser,
            'view_user_id'  => $userId,
        ]);
    }

    public function delete($id)
    {
        $this->init();
        $ts = $this->tsModel->find($id);
        if ($ts && ($ts['user_id'] == $this->auth->userId() || $this->auth->isAdmin())) {
            if ($ts['status'] === 'pending') {
                $this->tsModel->delete($id);
                $this->logAction('delete', 'timesheet', $id, $ts);
                $this->setFlash('success', 'Đã xóa bản ghi chấm công.');
            } else {
                $this->setFlash('warning', 'Chỉ có thể xóa chấm công ở trạng thái chờ duyệt.');
            }
        }
        $this->redirect('Timesheets');
    }
}
