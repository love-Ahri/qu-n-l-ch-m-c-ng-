<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Project;
use App\Models\User;
use App\Services\CostCalculator;

class ProjectController extends Controller
{
    private $projectModel;

    private function init()
    {
        $this->projectModel = new Project($this->pdo);
    }

    public function index()
    {
        $this->init();
        $keyword = $this->sanitize($_GET['q'] ?? '');
        $status = $this->sanitize($_GET['status'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));

        $userId = $this->auth->isAdmin() ? null : $this->auth->userId();
        $result = $this->projectModel->search($keyword, $status, $userId, $page);

        $this->render('projects/index.tpl', [
            'page_title' => 'Quản lý Dự án',
            'projects'   => $result['data'],
            'pagination' => $result,
            'filters'    => ['q' => $keyword, 'status' => $status],
            'flash'      => $this->getFlash(),
        ]);
    }

    public function create()
    {
        $this->render('projects/form.tpl', [
            'page_title' => 'Tạo Dự án mới',
            'project'    => null,
            'mode'       => 'create',
        ]);
    }

    public function store()
    {
        $this->init();
        $this->validateCsrf();

        $data = [
            'name'        => $this->sanitize($_POST['name'] ?? ''),
            'code'        => $this->sanitize($_POST['code'] ?? ''),
            'description' => $this->sanitize($_POST['description'] ?? ''),
            'client_name' => $this->sanitize($_POST['client_name'] ?? ''),
            'budget'      => (float)($_POST['budget'] ?? 0),
            'start_date'  => $_POST['start_date'] ?? null,
            'end_date'    => $_POST['end_date'] ?? null,
            'status'      => $_POST['status'] ?? 'planning',
            'created_by'  => $this->auth->userId(),
        ];

        $id = $this->projectModel->create($data);

        // Add creator as manager
        $this->pdo->prepare(
            "INSERT INTO project_members (project_id, user_id, project_role) VALUES (:pid, :uid, 'manager')"
        )->execute(['pid' => $id, 'uid' => $this->auth->userId()]);

        $this->logAction('create', 'project', $id, null, $data);
        $this->setFlash('success', "Đã tạo dự án '{$data['name']}'.");
        $this->redirect('Projects');
    }

    public function detail($id)
    {
        $this->init();
        $project = $this->projectModel->findWithDetails($id);
        if (!$project) {
            $this->setFlash('danger', 'Không tìm thấy dự án.');
            $this->redirect('Projects');
            return;
        }

        $costCalc = new CostCalculator($this->pdo);
        $costData = $costCalc->getProjectCost($id);
        $budgetData = $costCalc->getProjectBudgetUsage($id);

        $this->render('projects/detail.tpl', [
            'page_title'  => $project['name'],
            'project'     => $project,
            'cost_data'   => $costData,
            'budget_data' => $budgetData,
            'flash'       => $this->getFlash(),
        ]);
    }

    public function edit($id)
    {
        $this->init();
        $project = $this->projectModel->find($id);
        if (!$project) {
            $this->redirect('Projects');
            return;
        }

        $this->render('projects/form.tpl', [
            'page_title' => 'Sửa Dự án',
            'project'    => $project,
            'mode'       => 'edit',
        ]);
    }

    public function update($id)
    {
        $this->init();
        $this->validateCsrf();
        $old = $this->projectModel->find($id);

        $data = [
            'name'        => $this->sanitize($_POST['name'] ?? ''),
            'code'        => $this->sanitize($_POST['code'] ?? ''),
            'description' => $this->sanitize($_POST['description'] ?? ''),
            'client_name' => $this->sanitize($_POST['client_name'] ?? ''),
            'budget'      => (float)($_POST['budget'] ?? 0),
            'start_date'  => $_POST['start_date'] ?? null,
            'end_date'    => $_POST['end_date'] ?? null,
            'status'      => $_POST['status'] ?? 'planning',
        ];

        $this->projectModel->update($id, $data);
        $this->logAction('update', 'project', $id, $old, $data);
        $this->setFlash('success', "Đã cập nhật dự án '{$data['name']}'.");
        $this->redirect("Projects/detail/{$id}");
    }

    public function members($id)
    {
        $this->init();
        $project = $this->projectModel->findWithDetails($id);
        if (!$project) {
            $this->redirect('Projects');
            return;
        }

        $userModel = new User($this->pdo);
        $allUsers = $userModel->getActiveUsers();

        $this->render('projects/members.tpl', [
            'page_title' => 'Thành viên - ' . $project['name'],
            'project'    => $project,
            'all_users'  => $allUsers,
            'flash'      => $this->getFlash(),
        ]);
    }

    public function addMember($projectId)
    {
        $this->validateCsrf();
        $userId = (int)($_POST['user_id'] ?? 0);
        $role = $this->sanitize($_POST['project_role'] ?? 'developer');

        if ($userId) {
            $this->pdo->prepare(
                "INSERT IGNORE INTO project_members (project_id, user_id, project_role) VALUES (:pid, :uid, :role)"
            )->execute(['pid' => $projectId, 'uid' => $userId, 'role' => $role]);

            $this->logAction('create', 'project_member', $projectId, null, ['user_id' => $userId, 'role' => $role]);
            $this->setFlash('success', 'Đã thêm thành viên.');
        }
        $this->redirect("Projects/members/{$projectId}");
    }

    public function removeMember($projectId, $userId)
    {
        $this->pdo->prepare(
            "DELETE FROM project_members WHERE project_id = :pid AND user_id = :uid"
        )->execute(['pid' => $projectId, 'uid' => $userId]);

        $this->logAction('delete', 'project_member', $projectId, ['user_id' => $userId]);
        $this->setFlash('success', 'Đã xóa thành viên khỏi dự án.');
        $this->redirect("Projects/members/{$projectId}");
    }

    public function delete($id)
    {
        $this->init();
        $project = $this->projectModel->find($id);
        if ($project) {
            $this->projectModel->delete($id);
            $this->logAction('delete', 'project', $id, $project);
            $this->setFlash('success', "Đã xóa dự án '{$project['name']}'.");
        }
        $this->redirect('Projects');
    }
}
