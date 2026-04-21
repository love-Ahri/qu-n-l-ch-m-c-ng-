<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\ReportGenerator;
use App\Services\CostCalculator;
use App\Models\Project;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $this->redirect('Reports/individual');
    }

    public function individual()
    {
        $userId = (int)($_GET['user_id'] ?? $this->auth->userId());
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        if (!$this->auth->isAdmin() && !$this->auth->hasRole('pm') && !$this->auth->hasRole('hr')) {
            $userId = $this->auth->userId();
        }

        $generator = new ReportGenerator($this->pdo);
        $report = $generator->getIndividualReport($userId, $startDate, $endDate);

        $userModel = new User($this->pdo);
        $viewUser = $userModel->find($userId);
        $allUsers = $userModel->getActiveUsers();

        $this->render('reports/individual.tpl', [
            'page_title'  => 'Báo cáo Cá nhân',
            'report'      => $report,
            'view_user'   => $viewUser,
            'all_users'   => $allUsers,
            'filters'     => ['user_id' => $userId, 'start_date' => $startDate, 'end_date' => $endDate],
        ]);
    }

    public function team()
    {
        $projectId = (int)($_GET['project_id'] ?? 0);
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        if (!$projectId && !empty($projects)) {
            $projectId = $projects[0]['id'];
        }

        $report = null;
        if ($projectId) {
            $generator = new ReportGenerator($this->pdo);
            $report = $generator->getTeamReport($projectId, $startDate, $endDate);
        }

        $this->render('reports/team.tpl', [
            'page_title'  => 'Báo cáo Nhóm',
            'report'      => $report,
            'projects'    => $projects,
            'filters'     => ['project_id' => $projectId, 'start_date' => $startDate, 'end_date' => $endDate],
        ]);
    }

    public function cost()
    {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $projectId = (int)($_GET['project_id'] ?? 0);

        $generator = new ReportGenerator($this->pdo);
        $report = $generator->getCostReport($startDate, $endDate, $projectId ?: null);

        // Calculate costs
        $costCalc = new CostCalculator($this->pdo);
        $projectCosts = $costCalc->getAllProjectsCost();

        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        $this->render('reports/cost.tpl', [
            'page_title'    => 'Báo cáo Chi phí',
            'report'        => $report,
            'project_costs' => $projectCosts,
            'projects'      => $projects,
            'filters'       => ['project_id' => $projectId, 'start_date' => $startDate, 'end_date' => $endDate],
        ]);
    }
}
