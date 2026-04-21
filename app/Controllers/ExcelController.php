<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Services\ExcelService;

class ExcelController extends Controller
{
    public function index()
    {
        $this->render('excel/index.tpl', [
            'page_title' => 'Xuất/Nhập Excel',
            'flash'      => $this->getFlash(),
        ]);
    }

    public function exportUsers()
    {
        $service = new ExcelService($this->pdo);
        $service->exportUsers();
    }

    public function exportTimesheets()
    {
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $userId = ($_GET['user_id'] ?? '') ?: null;
        $projectId = ($_GET['project_id'] ?? '') ?: null;

        $service = new ExcelService($this->pdo);
        $service->exportTimesheets($startDate, $endDate, $userId, $projectId);
    }

    public function importUsers()
    {
        $this->validateCsrf();

        if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('danger', 'Vui lòng chọn file Excel.');
            $this->redirect('Excel');
            return;
        }

        $file = $_FILES['excel_file'];
        $allowed = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
        ];

        if (!in_array($file['type'], $allowed)) {
            $this->setFlash('danger', 'Chỉ chấp nhận file .xlsx hoặc .xls.');
            $this->redirect('Excel');
            return;
        }

        $uploadDir = $this->config['excel_path'] ?? (BASE_PATH . '/uploads/excel');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filePath = $uploadDir . '/' . uniqid('import_') . '.xlsx';
        move_uploaded_file($file['tmp_name'], $filePath);

        $service = new ExcelService($this->pdo);
        $result = $service->importUsers($filePath);

        // Clean up
        @unlink($filePath);

        if ($result['success']) {
            $msg = "Import thành công: {$result['imported']}/{$result['total_rows']} nhân sự.";
            if (!empty($result['errors'])) {
                $msg .= '<br><br><strong>Lỗi:</strong><br>' . implode('<br>', $result['errors']);
            }
            $this->setFlash($result['errors'] ? 'warning' : 'success', $msg);
        } else {
            $this->setFlash('danger', $result['message']);
        }

        $this->logAction('create', 'import', null, null, ['type' => 'users', 'result' => $result]);
        $this->redirect('Excel');
    }
}
