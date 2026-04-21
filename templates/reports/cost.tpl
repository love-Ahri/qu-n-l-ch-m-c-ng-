{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1><i class="bi bi-currency-dollar me-2"></i>Báo cáo Chi phí</h1></div>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link" href="{$base_url}/Reports/individual">Cá nhân</a></li>
    <li class="nav-item"><a class="nav-link" href="{$base_url}/Reports/team">Nhóm</a></li>
    <li class="nav-item"><a class="nav-link active" href="{$base_url}/Reports/cost">Chi phí</a></li>
</ul>
<form method="GET" class="filter-bar mb-4">
    <select name="project_id" class="form-select"><option value="">-- Tất cả dự án --</option>
        {foreach $projects as $p}<option value="{$p.id}" {if $p.id == $filters.project_id}selected{/if}>{$p.name}</option>{/foreach}
    </select>
    <input type="date" name="start_date" class="form-control" value="{$filters.start_date}">
    <input type="date" name="end_date" class="form-control" value="{$filters.end_date}">
    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
</form>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card"><div class="card-header">Chi phí theo Dự án</div><div class="card-body"><div class="chart-container"><canvas id="costBarChart"></canvas></div></div></div>
    </div>
    <div class="col-lg-5">
        <div class="card"><div class="card-header">Phân bổ chi phí</div><div class="card-body"><div class="chart-container"><canvas id="costPieChart"></canvas></div></div></div>
    </div>
</div>
<div class="card mt-4"><div class="card-header">Chi tiết theo Dự án</div><div class="card-body p-0">
    <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Dự án</th><th>Giờ làm</th><th>OT</th><th>Ngân sách</th><th>Chi phí TT</th><th>% Sử dụng</th></tr></thead><tbody>
        {foreach $report.by_project as $p}
        <tr><td><strong>{$p.name}</strong> <small class="text-muted">{$p.code}</small></td>
            <td>{$p.total_hours|number_format:1}h</td>
            <td>{$p.total_ot|number_format:1}h</td>
            <td>{$p.budget|number_format:0:',':'.'} VNĐ</td>
            <td>-</td>
            <td>{if $p.budget > 0}<div class="progress" style="height:6px;width:80px;display:inline-block;"><div class="progress-bar" style="width:50%"></div></div>{else}-{/if}</td>
        </tr>
        {foreachelse}
        <tr><td colspan="6" class="text-center text-muted py-3">Không có dữ liệu</td></tr>
        {/foreach}
    </tbody></table></div>
</div></div>
<div class="card mt-4"><div class="card-header">Chi phí theo Phòng ban</div><div class="card-body p-0">
    <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Phòng ban</th><th>Giờ làm</th><th>Giờ OT</th></tr></thead><tbody>
        {foreach $report.by_department as $d}
        <tr><td>{$d.department|default:'Chưa xác định'}</td><td>{$d.total_hours|number_format:1}h</td><td>{$d.total_ot|number_format:1}h</td></tr>
        {/foreach}
    </tbody></table></div>
</div></div>
{/block}
{block name="scripts"}
<script>
const costData = {$project_costs|json_encode};
if (costData.length > 0) {
    new Chart(document.getElementById('costBarChart'), {
        type: 'bar',
        data: {
            labels: costData.map(p => p.project_code),
            datasets: [
                { label: 'Ngân sách', data: costData.map(p => p.budget), backgroundColor: 'rgba(102,126,234,0.3)', borderColor: '#667eea', borderWidth: 2, borderRadius: 6 },
                { label: 'Chi phí TT', data: costData.map(p => p.actual_cost), backgroundColor: 'rgba(240,147,251,0.3)', borderColor: '#f093fb', borderWidth: 2, borderRadius: 6 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { color: '#8b8fa3' } } }, scales: { x: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } }, y: { ticks: { color: '#8b8fa3', callback: v => (v/1000000)+'M' }, grid: { color: 'rgba(255,255,255,0.04)' } } } }
    });
    new Chart(document.getElementById('costPieChart'), {
        type: 'doughnut',
        data: {
            labels: costData.map(p => p.project_name),
            datasets: [{ data: costData.map(p => p.total_hours), backgroundColor: ['#667eea','#f093fb','#43e97b','#fee140','#a18cd1','#f5576c','#38f9d7'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: '#8b8fa3', font: { size: 11 } } } } }
    });
}
</script>
{/block}
