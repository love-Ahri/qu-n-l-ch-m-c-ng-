<?php
namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExcelService
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Export users to Excel
     */
    public function exportUsers()
    {
        $stmt = $this->pdo->query(
            "SELECT u.id, u.name, u.email, u.phone, u.department, 
                    GROUP_CONCAT(r.display_name SEPARATOR ', ') as roles,
                    CASE WHEN u.is_active = 1 THEN 'Hoạt động' ELSE 'Khóa' END as status,
                    u.created_at
             FROM users u
             LEFT JOIN user_roles ur ON u.id = ur.user_id
             LEFT JOIN roles r ON ur.role_id = r.id
             GROUP BY u.id ORDER BY u.id"
        );
        $users = $stmt->fetchAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách nhân sự');

        // Header
        $headers = ['ID', 'Họ tên', 'Email', 'Điện thoại', 'Phòng ban', 'Vai trò', 'Trạng thái', 'Ngày tạo'];
        foreach ($headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
        }
        $this->styleHeader($sheet, 'A1:H1');

        // Data
        foreach ($users as $i => $u) {
            $row = $i + 2;
            $sheet->setCellValue("A{$row}", $u['id']);
            $sheet->setCellValue("B{$row}", $u['name']);
            $sheet->setCellValue("C{$row}", $u['email']);
            $sheet->setCellValue("D{$row}", $u['phone']);
            $sheet->setCellValue("E{$row}", $u['department']);
            $sheet->setCellValue("F{$row}", $u['roles']);
            $sheet->setCellValue("G{$row}", $u['status']);
            $sheet->setCellValue("H{$row}", $u['created_at']);
        }

        // Auto width
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->output($spreadsheet, 'Nhan_su_' . date('Y-m-d'));
    }

    /**
     * Export timesheets to Excel
     */
    public function exportTimesheets($startDate, $endDate, $userId = null, $projectId = null)
    {
        $where = "ts.work_date BETWEEN :sd AND :ed";
        $params = ['sd' => $startDate, 'ed' => $endDate];

        if ($userId) {
            $where .= " AND ts.user_id = :uid";
            $params['uid'] = $userId;
        }
        if ($projectId) {
            $where .= " AND ts.project_id = :pid";
            $params['pid'] = $projectId;
        }

        $stmt = $this->pdo->prepare(
            "SELECT ts.work_date, u.name as user_name, p.name as project_name, p.code as project_code,
                    t.title as task_title, ts.shift, ts.hours_worked, ts.overtime_hours,
                    ts.description, ts.status
             FROM timesheets ts
             INNER JOIN users u ON ts.user_id = u.id
             INNER JOIN projects p ON ts.project_id = p.id
             LEFT JOIN tasks t ON ts.task_id = t.id
             WHERE {$where}
             ORDER BY ts.work_date DESC, u.name"
        );
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Chấm công');

        $shiftMap = ['morning' => 'Sáng', 'afternoon' => 'Chiều', 'evening' => 'Tối', 'flexible' => 'Linh hoạt'];
        $statusMap = ['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'rejected' => 'Từ chối'];

        $headers = ['Ngày', 'Nhân viên', 'Dự án', 'Mã DA', 'Nhiệm vụ', 'Ca', 'Giờ làm', 'Giờ OT', 'Mô tả', 'Trạng thái'];
        foreach ($headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
        }
        $this->styleHeader($sheet, 'A1:J1');

        foreach ($data as $i => $row) {
            $r = $i + 2;
            $sheet->setCellValue("A{$r}", $row['work_date']);
            $sheet->setCellValue("B{$r}", $row['user_name']);
            $sheet->setCellValue("C{$r}", $row['project_name']);
            $sheet->setCellValue("D{$r}", $row['project_code']);
            $sheet->setCellValue("E{$r}", $row['task_title']);
            $sheet->setCellValue("F{$r}", $shiftMap[$row['shift']] ?? $row['shift']);
            $sheet->setCellValue("G{$r}", $row['hours_worked']);
            $sheet->setCellValue("H{$r}", $row['overtime_hours']);
            $sheet->setCellValue("I{$r}", $row['description']);
            $sheet->setCellValue("J{$r}", $statusMap[$row['status']] ?? $row['status']);
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $this->output($spreadsheet, 'Cham_cong_' . $startDate . '_' . $endDate);
    }

    /**
     * Import users from Excel
     */
    public function importUsers($filePath)
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        if (count($rows) < 2) {
            return ['success' => false, 'message' => 'File không có dữ liệu.'];
        }

        // Expected columns: Họ tên, Email, Điện thoại, Phòng ban, Vai trò (admin/pm/hr/staff)
        $imported = 0;
        $errors = [];
        $defaultPassword = password_hash('password', PASSWORD_DEFAULT);

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            $lineNum = $i + 1;

            $name = trim($row[0] ?? '');
            $email = trim($row[1] ?? '');
            $phone = trim($row[2] ?? '');
            $department = trim($row[3] ?? '');
            $roleName = trim($row[4] ?? 'staff');

            if (empty($name) || empty($email)) {
                $errors[] = "Dòng {$lineNum}: Thiếu họ tên hoặc email.";
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Dòng {$lineNum}: Email '{$email}' không hợp lệ.";
                continue;
            }

            // Check duplicate
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                $errors[] = "Dòng {$lineNum}: Email '{$email}' đã tồn tại.";
                continue;
            }

            // Insert user
            $stmt = $this->pdo->prepare(
                "INSERT INTO users (name, email, password, phone, department) VALUES (:name, :email, :pwd, :phone, :dept)"
            );
            $stmt->execute([
                'name' => $name, 'email' => $email, 'pwd' => $defaultPassword,
                'phone' => $phone, 'dept' => $department,
            ]);
            $userId = $this->pdo->lastInsertId();

            // Assign role
            $role = $this->pdo->prepare("SELECT id FROM roles WHERE name = :name");
            $role->execute(['name' => $roleName]);
            $roleRow = $role->fetch();
            if ($roleRow) {
                $this->pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:uid, :rid)")
                    ->execute(['uid' => $userId, 'rid' => $roleRow['id']]);
            }

            $imported++;
        }

        return [
            'success' => true,
            'imported' => $imported,
            'errors' => $errors,
            'total_rows' => count($rows) - 1,
        ];
    }

    private function styleHeader($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '667eea']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
    }

    private function output($spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}.xlsx\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
