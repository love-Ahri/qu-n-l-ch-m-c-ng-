{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1><i class="bi bi-person-lines-fill me-2"></i>Báo cáo Cá nhân</h1></div>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" href="{$base_url}/Reports/individual">Cá nhân</a></li>
    <li class="nav-item"><a class="nav-link" href="{$base_url}/Reports/team">Nhóm</a></li>
    <li class="nav-item"><a class="nav-link" href="{$base_url}/Reports/cost">Chi phí</a></li>
</ul>
<form method="GET" class="filter-bar mb-4">
    <select name="user_id" class="form-select">
        {foreach $all_users as $u}<option value="{$u.id}" {if $u.id == $filters.user_id}selected{/if}>{$u.name}</option>{/foreach}
    </select>
    <input type="date" name="start_date" class="form-control" value="{$filters.start_date}">
    <input type="date" name="end_date" class="form-control" value="{$filters.end_date}">
    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Xem</button>
</form>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card primary"><div class="stat-value">{$report.summary.total_hours|number_format:1}h</div><div class="stat-label">Tổng giờ làm</div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card warning"><div class="stat-value">{$report.summary.total_ot|number_format:1}h</div><div class="stat-label">Giờ OT</div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card success"><div class="stat-value">{$report.summary.working_days}</div><div class="stat-label">Ngày làm việc</div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card info"><div class="stat-value">{$report.tasks.done_tasks|default:0}/{$report.tasks.total_tasks|default:0}</div><div class="stat-label">Task hoàn thành</div></div></div>
</div>
<div class="row g-4">
    <div class="col-lg-8"><div class="card"><div class="card-header">Giờ làm theo tuần</div><div class="card-body"><div class="chart-container"><canvas id="weeklyChart"></canvas></div></div></div></div>
    <div class="col-lg-4"><div class="card"><div class="card-header">Theo dự án</div><div class="card-body p-0">
        {foreach $report.by_project as $bp}
        <div class="d-flex justify-content-between px-3 py-2" style="border-bottom:1px solid var(--border-color);font-size:13px;">
            <span>{$bp.project_name}</span><strong>{$bp.hours|number_format:1}h</strong>
        </div>
        {foreachelse}
        <div class="text-center text-muted py-3">Không có dữ liệu</div>
        {/foreach}
    </div></div></div>
</div>
{/block}
{block name="scripts"}
<script>
const weeklyData = {$report.weekly|json_encode};
if (weeklyData.length > 0 && document.getElementById('weeklyChart')) {
    new Chart(document.getElementById('weeklyChart'), {
        type: 'line',
        data: {
            labels: weeklyData.map(w => w.week_start ? w.week_start.substring(5) : ''),
            datasets: [{
                label: 'Giờ làm',
                data: weeklyData.map(w => parseFloat(w.hours)),
                borderColor: '#667eea', backgroundColor: 'rgba(102,126,234,0.1)',
                fill: true, tension: 0.4, borderWidth: 2
            },{
                label: 'OT',
                data: weeklyData.map(w => parseFloat(w.ot)),
                borderColor: '#fa709a', backgroundColor: 'rgba(250,112,154,0.1)',
                fill: true, tension: 0.4, borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#8b8fa3' } } },
            scales: {
                x: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } },
                y: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } }
            }
        }
    });
}
</script>
{/block}
