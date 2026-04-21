{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <div>
        <h1>{$project.name}</h1>
        <p class="text-secondary mb-0"><span class="badge badge-{$project.status}">{$project.status}</span> | {$project.code} | {$project.client_name|default:'Nội bộ'}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{$base_url}/Tasks/kanban/{$project.id}" class="btn btn-primary"><i class="bi bi-kanban"></i> Kanban</a>
        <a href="{$base_url}/Projects/members/{$project.id}" class="btn btn-ghost"><i class="bi bi-people"></i> Thành viên</a>
        <a href="{$base_url}/Projects/edit/{$project.id}" class="btn btn-ghost"><i class="bi bi-pencil"></i> Sửa</a>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card primary"><div class="d-flex align-items-center justify-content-between">
            <div><div class="stat-value">{$project.task_stats.total_tasks|default:0}</div><div class="stat-label">Tổng Tasks</div></div>
            <div class="stat-icon primary"><i class="bi bi-list-check"></i></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card success"><div class="d-flex align-items-center justify-content-between">
            <div><div class="stat-value">{$project.task_stats.done_tasks|default:0}</div><div class="stat-label">Tasks hoàn thành</div></div>
            <div class="stat-icon success"><i class="bi bi-check-circle"></i></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card warning"><div class="d-flex align-items-center justify-content-between">
            <div><div class="stat-value">{$project.hours_worked.total|default:0|number_format:1}h</div><div class="stat-label">Giờ làm (OT: {$project.hours_worked.total_ot|default:0|number_format:1}h)</div></div>
            <div class="stat-icon warning"><i class="bi bi-clock"></i></div>
        </div></div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card {if $budget_data.usage_percent > 90}accent{else}info{/if}"><div class="d-flex align-items-center justify-content-between">
            <div><div class="stat-value">{$budget_data.usage_percent}%</div><div class="stat-label">Ngân sách sử dụng</div></div>
            <div class="stat-icon {if $budget_data.usage_percent > 90}accent{else}info{/if}"><i class="bi bi-currency-dollar"></i></div>
        </div></div>
    </div>
</div>
{if $project.task_stats.total_tasks > 0}
<div class="row g-3 mb-4">
    <div class="col-lg-6"><div class="card"><div class="card-header">Tiến độ Tasks</div><div class="card-body">
        <div class="progress mb-2" style="height:12px;">
            <div class="progress-bar bg-success" style="width:{($project.task_stats.done_tasks/$project.task_stats.total_tasks*100)|number_format:0}%" title="Done">{$project.task_stats.done_tasks}</div>
            <div class="progress-bar" style="width:{($project.task_stats.doing_tasks/$project.task_stats.total_tasks*100)|number_format:0}%;background:#667eea;" title="Doing">{$project.task_stats.doing_tasks}</div>
            <div class="progress-bar" style="width:{($project.task_stats.review_tasks/$project.task_stats.total_tasks*100)|number_format:0}%;background:#f093fb;" title="Review">{$project.task_stats.review_tasks}</div>
        </div>
        <div class="d-flex gap-3 mt-2" style="font-size:12px;">
            <span><span style="color:#43e97b;">●</span> Done: {$project.task_stats.done_tasks}</span>
            <span><span style="color:#667eea;">●</span> Doing: {$project.task_stats.doing_tasks}</span>
            <span><span style="color:#f093fb;">●</span> Review: {$project.task_stats.review_tasks}</span>
            <span><span style="color:#8b8fa3;">●</span> Todo: {$project.task_stats.todo_tasks}</span>
        </div>
    </div></div></div>
    <div class="col-lg-6"><div class="card"><div class="card-header">Chi phí Nhân sự</div><div class="card-body">
        <div class="chart-container" style="height:200px;"><canvas id="costPie"></canvas></div>
    </div></div></div>
</div>
{/if}
<div class="card"><div class="card-header">Thành viên dự án</div><div class="card-body p-0">
    <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Tên</th><th>Vai trò DA</th><th>Phòng ban</th><th>Email</th></tr></thead><tbody>
        {foreach $project.members as $m}
        <tr><td><div class="d-flex align-items-center gap-2"><div class="user-avatar" style="width:28px;height:28px;font-size:11px;">{$m.name|mb_substr:0:1|upper}</div>{$m.name}</div></td>
        <td><span class="badge badge-{$m.project_role}">{$m.project_role}</span></td><td>{$m.department|default:'-'}</td><td>{$m.email}</td></tr>
        {/foreach}
    </tbody></table></div>
</div></div>
{/block}
{block name="scripts"}
<script>
const costByUser = {$cost_data.by_user|json_encode};
if (costByUser.length > 0) {
    new Chart(document.getElementById('costPie'), {
        type: 'doughnut',
        data: {
            labels: costByUser.map(u => u.user_name),
            datasets: [{
                data: costByUser.map(u => u.cost),
                backgroundColor: ['#667eea','#f093fb','#43e97b','#fee140','#a18cd1','#f5576c','#38f9d7','#fa709a'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { color: '#8b8fa3', font: { size: 11, family: 'Inter' } } },
                tooltip: { callbacks: { label: ctx => ctx.label + ': ' + new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VNĐ' } }
            }
        }
    });
}
</script>
{/block}
