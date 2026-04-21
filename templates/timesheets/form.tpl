{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1>Ghi nhận Chấm công</h1><a href="{$base_url}/Timesheets" class="btn btn-ghost"><i class="bi bi-arrow-left"></i></a></div>
<div class="row"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form method="POST" action="{$base_url}/Timesheets/store">
        <input type="hidden" name="csrf_token" value="{$csrf_token}">
        <div class="row g-3">
            <div class="col-12"><label class="form-label">Dự án *</label>
                <select name="project_id" class="form-select" id="projectSelect" required>
                    <option value="">-- Chọn dự án --</option>
                    {foreach $projects as $p}<option value="{$p.id}">{$p.code} - {$p.name}</option>{/foreach}
                </select>
            </div>
            <div class="col-12"><label class="form-label">Nhiệm vụ</label>
                <select name="task_id" class="form-select" id="taskSelect"><option value="">-- Chọn task --</option></select>
            </div>
            <div class="col-md-6"><label class="form-label">Ngày làm *</label><input type="date" name="work_date" class="form-control" value="{$smarty.now|date_format:'%Y-%m-%d'}" required></div>
            <div class="col-md-6"><label class="form-label">Ca làm</label>
                <select name="shift" class="form-select">
                    <option value="flexible">Linh hoạt</option>
                    <option value="morning">Sáng (8:00-12:00)</option>
                    <option value="afternoon">Chiều (13:00-17:00)</option>
                    <option value="evening">Tối (18:00-22:00)</option>
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Số giờ làm *</label><input type="number" name="hours_worked" class="form-control" min="0.5" max="24" step="0.5" value="8" required></div>
            <div class="col-12"><label class="form-label">Mô tả công việc</label><textarea name="description" class="form-control" rows="2" placeholder="Bạn đã làm gì hôm nay?"></textarea></div>
        </div>
        <div class="mt-4"><button type="submit" class="btn btn-primary w-100"><i class="bi bi-check-lg"></i> Ghi nhận</button></div>
    </form>
</div></div></div></div>
{/block}
{block name="scripts"}
<script>
document.getElementById('projectSelect').addEventListener('change', function() {
    loadTasksByProject(this.value, document.getElementById('taskSelect'));
});
</script>
{/block}
