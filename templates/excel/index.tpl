{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1><i class="bi bi-file-earmark-spreadsheet me-2"></i>Xuất/Nhập Excel</h1></div>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-download me-2"></i>Xuất dữ liệu</div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{$base_url}/Excel/exportUsers" class="btn btn-outline-primary">
                        <i class="bi bi-people"></i> Xuất Danh sách Nhân sự
                    </a>
                    <div class="card" style="background:var(--bg-input);border-color:var(--border-color);">
                        <div class="card-body">
                            <h6 class="mb-3">Xuất Chấm công</h6>
                            <form method="GET" action="{$base_url}/Excel/exportTimesheets">
                                <div class="row g-2">
                                    <div class="col-6"><label class="form-label">Từ ngày</label><input type="date" name="start_date" class="form-control" value="{$smarty.now|date_format:'%Y-%m-01'}"></div>
                                    <div class="col-6"><label class="form-label">Đến ngày</label><input type="date" name="end_date" class="form-control" value="{$smarty.now|date_format:'%Y-%m-%d'}"></div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 w-100"><i class="bi bi-download"></i> Xuất Excel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-upload me-2"></i>Nhập dữ liệu</div>
            <div class="card-body">
                <form method="POST" action="{$base_url}/Excel/importUsers" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="{$csrf_token}">
                    <h6 class="mb-3">Nhập Danh sách Nhân sự</h6>
                    <div class="alert alert-info" style="font-size:13px;">
                        <strong>Format:</strong> File .xlsx với các cột: Họ tên, Email, Điện thoại, Phòng ban, Vai trò (admin/pm/hr/staff)<br>
                        Mật khẩu mặc định: <code>password</code>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-upload"></i> Nhập Excel</button>
                </form>
            </div>
        </div>
    </div>
</div>
{/block}
