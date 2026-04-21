<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\HourlyRate;

class UserController extends Controller
{
    private $userModel;
    private $roleModel;

    private function init()
    {
        $this->userModel = new User($this->pdo);
        $this->roleModel = new Role($this->pdo);
    }

    public function index()
    {
        $this->init();
        $keyword = $this->sanitize($_GET['q'] ?? '');
        $department = $this->sanitize($_GET['department'] ?? '');
        $status = $_GET['status'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));

        $result = $this->userModel->search($keyword, $department, $status, $page, $this->config['pagination']['per_page']);
        $departments = $this->userModel->getDepartments();

        $this->render('users/index.tpl', [
            'page_title'  => 'Quản lý Người dùng',
            'users'       => $result['data'],
            'pagination'  => $result,
            'departments' => $departments,
            'filters'     => ['q' => $keyword, 'department' => $department, 'status' => $status],
            'flash'       => $this->getFlash(),
        ]);
    }

    public function create()
    {
        $this->init();
        $roles = $this->roleModel->getAllRoles();
        $this->render('users/form.tpl', [
            'page_title' => 'Thêm Người dùng',
            'user'       => null,
            'roles'      => $roles,
            'mode'       => 'create',
            'flash'      => $this->getFlash(),
        ]);
    }

    public function store()
    {
        $this->init();
        $this->validateCsrf();

        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $department = $this->sanitize($_POST['department'] ?? '');
        $password = $_POST['password'] ?? '';
        $roleIds = $_POST['roles'] ?? [];

        // Validate
        if (empty($name) || empty($email) || empty($password)) {
            $this->setFlash('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            $this->redirect('Users/create');
            return;
        }

        if ($this->userModel->emailExists($email)) {
            $this->setFlash('danger', 'Email đã tồn tại trong hệ thống.');
            $this->redirect('Users/create');
            return;
        }

        $userId = $this->userModel->create([
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'phone'      => $phone,
            'department' => $department,
        ]);

        if (!empty($roleIds)) {
            $this->userModel->assignRoles($userId, $roleIds);
        }

        $this->logAction('create', 'user', $userId, null, ['name' => $name, 'email' => $email]);
        $this->setFlash('success', "Đã thêm người dùng '{$name}' thành công.");
        $this->redirect('Users');
    }

    public function edit($id)
    {
        $this->init();
        $user = $this->userModel->findWithRoles($id);
        if (!$user) {
            $this->setFlash('danger', 'Không tìm thấy người dùng.');
            $this->redirect('Users');
            return;
        }

        $roles = $this->roleModel->getAllRoles();
        $rates = (new HourlyRate($this->pdo))->getByUser($id);

        $this->render('users/form.tpl', [
            'page_title' => 'Sửa Người dùng',
            'user'       => $user,
            'roles'      => $roles,
            'rates'      => $rates,
            'mode'       => 'edit',
            'flash'      => $this->getFlash(),
        ]);
    }

    public function update($id)
    {
        $this->init();
        $this->validateCsrf();

        $user = $this->userModel->find($id);
        if (!$user) {
            $this->redirect('Users');
            return;
        }

        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $department = $this->sanitize($_POST['department'] ?? '');
        $roleIds = $_POST['roles'] ?? [];

        if ($this->userModel->emailExists($email, $id)) {
            $this->setFlash('danger', 'Email đã tồn tại.');
            $this->redirect("Users/edit/{$id}");
            return;
        }

        $data = [
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'department' => $department,
        ];

        // Password change (optional)
        $newPassword = $_POST['password'] ?? '';
        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $oldData = ['name' => $user['name'], 'email' => $user['email']];
        $this->userModel->update($id, $data);
        $this->userModel->assignRoles($id, $roleIds);

        // Save hourly rate if provided
        $rateAmount = $_POST['rate_amount'] ?? '';
        if ($rateAmount !== '') {
            $rateModel = new HourlyRate($this->pdo);
            // Deactivate old personal rates
            $this->pdo->prepare(
                "UPDATE hourly_rates SET effective_to = CURDATE() WHERE user_id = :uid AND effective_to IS NULL"
            )->execute(['uid' => $id]);
            // Insert new rate
            $rateModel->create([
                'user_id'        => $id,
                'role_id'        => null,
                'rate_amount'    => (float)$rateAmount,
                'effective_from' => date('Y-m-d'),
            ]);
        }

        $this->logAction('update', 'user', $id, $oldData, $data);
        $this->setFlash('success', "Đã cập nhật người dùng '{$name}'.");
        $this->redirect('Users');
    }

    public function toggleActive($id)
    {
        $this->init();
        $user = $this->userModel->find($id);
        if ($user) {
            $newStatus = $user['is_active'] ? 0 : 1;
            $this->userModel->update($id, ['is_active' => $newStatus]);
            $action = $newStatus ? 'Mở khóa' : 'Khóa';
            $this->logAction('update', 'user', $id, ['is_active' => $user['is_active']], ['is_active' => $newStatus]);
            $this->setFlash('success', "{$action} người dùng '{$user['name']}' thành công.");
        }
        $this->redirect('Users');
    }

    public function delete($id)
    {
        $this->init();
        $user = $this->userModel->find($id);
        if ($user) {
            $this->userModel->delete($id);
            $this->logAction('delete', 'user', $id, ['name' => $user['name'], 'email' => $user['email']]);
            $this->setFlash('success', "Đã xóa người dùng '{$user['name']}'.");
        }
        $this->redirect('Users');
    }

    public function profile()
    {
        $this->init();
        $user = $this->userModel->findWithRoles($this->auth->userId());

        // My recent timesheets
        $stmt = $this->pdo->prepare(
            "SELECT ts.*, p.name as project_name FROM timesheets ts
             INNER JOIN projects p ON ts.project_id = p.id
             WHERE ts.user_id = :uid ORDER BY ts.work_date DESC LIMIT 10"
        );
        $stmt->execute(['uid' => $user['id']]);
        $timesheets = $stmt->fetchAll();

        $this->render('users/profile.tpl', [
            'page_title'  => 'Hồ sơ cá nhân',
            'profile'     => $user,
            'timesheets'  => $timesheets,
            'flash'       => $this->getFlash(),
        ]);
    }
}
