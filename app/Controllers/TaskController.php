<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TaskController extends Controller
{
    private $taskModel;

    private function init()
    {
        $this->taskModel = new Task($this->pdo);
    }

    public function index()
    {
        $this->init();
        $projectId = (int)($_GET['project_id'] ?? 0);

        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        if (!$projectId && !empty($projects)) {
            $projectId = $projects[0]['id'];
        }

        $tasks = $projectId ? $this->taskModel->getByProject($projectId) : [];

        $this->render('tasks/index.tpl', [
            'page_title' => 'Nhiệm vụ',
            'tasks'      => $tasks,
            'projects'   => $projects,
            'project_id' => $projectId,
            'flash'      => $this->getFlash(),
        ]);
    }

    public function kanban($projectId = null)
    {
        $this->init();
        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        $projectId = $projectId ?: (int)($_GET['project_id'] ?? ($projects[0]['id'] ?? 0));

        $kanbanData = $projectId ? $this->taskModel->getKanbanData($projectId) : [];
        $project = $projectId ? $projectModel->find($projectId) : null;

        // Members for assignment
        $members = $projectId ? $this->pdo->prepare(
            "SELECT u.id, u.name FROM project_members pm 
             INNER JOIN users u ON pm.user_id = u.id 
             WHERE pm.project_id = :pid ORDER BY u.name"
        ) : null;
        if ($members) {
            $members->execute(['pid' => $projectId]);
            $memberList = $members->fetchAll();
        } else {
            $memberList = [];
        }

        $this->render('tasks/kanban.tpl', [
            'page_title'  => 'Kanban Board',
            'kanban'      => $kanbanData,
            'projects'    => $projects,
            'project'     => $project,
            'project_id'  => $projectId,
            'members'     => $memberList,
            'flash'       => $this->getFlash(),
        ]);
    }

    public function create($projectId = null)
    {
        $this->init();
        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        $members = [];
        if ($projectId) {
            $stmt = $this->pdo->prepare(
                "SELECT u.id, u.name FROM project_members pm 
                 INNER JOIN users u ON pm.user_id = u.id WHERE pm.project_id = :pid"
            );
            $stmt->execute(['pid' => $projectId]);
            $members = $stmt->fetchAll();
        }

        $this->render('tasks/form.tpl', [
            'page_title'  => 'Tạo Nhiệm vụ',
            'task'        => null,
            'projects'    => $projects,
            'project_id'  => $projectId,
            'members'     => $members,
            'mode'        => 'create',
        ]);
    }

    public function store()
    {
        $this->init();
        $this->validateCsrf();

        $data = [
            'project_id'      => (int)($_POST['project_id'] ?? 0),
            'title'           => $this->sanitize($_POST['title'] ?? ''),
            'description'     => $this->sanitize($_POST['description'] ?? ''),
            'assigned_to'     => ($_POST['assigned_to'] ?? '') ?: null,
            'start_date'      => $_POST['start_date'] ?? null,
            'due_date'        => $_POST['due_date'] ?? null,
            'priority'        => $_POST['priority'] ?? 'medium',
            'status'          => $_POST['status'] ?? 'todo',
            'estimated_hours' => (float)($_POST['estimated_hours'] ?? 0),
            'created_by'      => $this->auth->userId(),
        ];

        $id = $this->taskModel->create($data);
        $this->logAction('create', 'task', $id, null, $data);
        $this->setFlash('success', "Đã tạo nhiệm vụ '{$data['title']}'.");
        $this->redirect("Tasks/kanban/{$data['project_id']}");
    }

    public function edit($id)
    {
        $this->init();
        $task = $this->taskModel->findWithDetails($id);
        if (!$task) {
            $this->redirect('Tasks');
            return;
        }

        $projectModel = new Project($this->pdo);
        $isAdmin = $this->auth->isAdmin();
        $projects = $projectModel->getByUser($this->auth->userId(), $isAdmin);

        $stmt = $this->pdo->prepare(
            "SELECT u.id, u.name FROM project_members pm 
             INNER JOIN users u ON pm.user_id = u.id WHERE pm.project_id = :pid"
        );
        $stmt->execute(['pid' => $task['project_id']]);
        $members = $stmt->fetchAll();

        $this->render('tasks/form.tpl', [
            'page_title' => 'Sửa Nhiệm vụ',
            'task'       => $task,
            'projects'   => $projects,
            'project_id' => $task['project_id'],
            'members'    => $members,
            'mode'       => 'edit',
        ]);
    }

    public function update($id)
    {
        $this->init();
        $this->validateCsrf();
        $old = $this->taskModel->find($id);

        $data = [
            'title'           => $this->sanitize($_POST['title'] ?? ''),
            'description'     => $this->sanitize($_POST['description'] ?? ''),
            'assigned_to'     => ($_POST['assigned_to'] ?? '') ?: null,
            'start_date'      => $_POST['start_date'] ?? null,
            'due_date'        => $_POST['due_date'] ?? null,
            'priority'        => $_POST['priority'] ?? 'medium',
            'status'          => $_POST['status'] ?? 'todo',
            'estimated_hours' => (float)($_POST['estimated_hours'] ?? 0),
        ];

        $this->taskModel->update($id, $data);
        $this->logAction('update', 'task', $id, $old, $data);
        $this->setFlash('success', 'Đã cập nhật nhiệm vụ.');
        $this->redirect("Tasks/kanban/{$old['project_id']}");
    }

    /**
     * AJAX: Update task status (Kanban drag & drop)
     */
    public function updateStatus()
    {
        $this->init();
        $taskId = (int)($_POST['task_id'] ?? 0);
        $status = $this->sanitize($_POST['status'] ?? '');

        if (!in_array($status, ['todo', 'doing', 'review', 'done'])) {
            $this->json(['success' => false, 'message' => 'Trạng thái không hợp lệ.'], 400);
        }

        $old = $this->taskModel->find($taskId);
        if (!$old) {
            $this->json(['success' => false, 'message' => 'Không tìm thấy task.'], 404);
        }

        $this->taskModel->updateStatus($taskId, $status);
        $this->logAction('update', 'task', $taskId, ['status' => $old['status']], ['status' => $status]);
        $this->json(['success' => true]);
    }

    /**
     * AJAX: Get tasks by project (for timesheet form)
     */
    public function getByProject($projectId)
    {
        $this->init();
        $tasks = $this->taskModel->getSimpleByProject($projectId);
        $this->json($tasks);
    }

    public function delete($id)
    {
        $this->init();
        $task = $this->taskModel->find($id);
        if ($task) {
            $projectId = $task['project_id'];
            $this->taskModel->delete($id);
            $this->logAction('delete', 'task', $id, $task);
            $this->setFlash('success', "Đã xóa nhiệm vụ '{$task['title']}'.");
            $this->redirect("Tasks/kanban/{$projectId}");
        } else {
            $this->redirect('Tasks');
        }
    }
}
