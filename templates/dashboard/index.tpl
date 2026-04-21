{extends file="layout/main.tpl"}

{block name="content"}
<div class="page-header">
    <div>
        <h1>Tổng quan</h1>
        <p class="text-secondary mb-0">Xin chào, <strong>{$current_user.name}</strong>! Hôm nay là {$smarty.now|date_format:"%d/%m/%Y"}</p>
    </div>
</div>

{* === STAT CARDS === *}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{$project_count}</div>
                    <div class="stat-label">Dự án Active</div>
                </div>
                <div class="stat-icon primary"><i class="bi bi-folder2-open"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{$task_count}</div>
                    <div class="stat-label">Task đang thực hiện</div>
                </div>
                <div class="stat-icon warning"><i class="bi bi-list-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{$user_count}</div>
                    <div class="stat-label">Nhân sự hoạt động</div>
                </div>
                <div class="stat-icon success"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card accent">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value">{$pending_ts}</div>
                    <div class="stat-label">Chấm công chờ duyệt</div>
                </div>
                <div class="stat-icon accent"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {* === COST CHART === *}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart me-2"></i>Chi phí Dự án (Thực tế vs Ngân sách)</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="costChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {* === RECENT ACTIVITY === *}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-activity me-2"></i>Hoạt động gần đây
            </div>
            <div class="card-body p-0">
                <div style="max-height:340px; overflow-y:auto;">
                    {foreach $audit_logs as $log}
                    <div class="d-flex align-items-start gap-3 px-3 py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="user-avatar" style="width:28px;height:28px;font-size:11px;flex-shrink:0;">
                            {$log.user_name|mb_substr:0:1|upper}
                        </div>
                        <div style="min-width:0;">
                            <div style="font-size:13px;">
                                <strong>{$log.user_name|default:'Hệ thống'}</strong>
                                {if $log.action == 'create'}tạo mới{elseif $log.action == 'update'}cập nhật{elseif $log.action == 'delete'}xóa{elseif $log.action == 'approve'}duyệt{elseif $log.action == 'login'}đăng nhập{else}{$log.action}{/if}
                                <span class="text-secondary">{$log.entity_type}</span>
                            </div>
                            <small class="text-muted">{$log.created_at|date_format:"%d/%m %H:%M"}</small>
                        </div>
                    </div>
                    {foreachelse}
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>Chưa có hoạt động</p></div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>

{* === MY TASKS (for staff) === *}
{if $my_tasks|@count > 0}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-check2-square me-2"></i>Nhiệm vụ của tôi</span>
                <a href="{$base_url}/Tasks" class="btn btn-sm btn-ghost">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nhiệm vụ</th>
                                <th>Dự án</th>
                                <th>Ưu tiên</th>
                                <th>Trạng thái</th>
                                <th>Hạn</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach $my_tasks as $task}
                            <tr>
                                <td><strong>{$task.title}</strong></td>
                                <td><span class="text-secondary">{$task.project_code}</span> {$task.project_name}</td>
                                <td><span class="badge badge-{$task.priority}">{$task.priority|upper}</span></td>
                                <td><span class="badge badge-status badge-{$task.status}">{if $task.status=='todo'}To-Do{elseif $task.status=='doing'}Đang làm{elseif $task.status=='review'}Review{else}Done{/if}</span></td>
                                <td>{if $task.due_date}{$task.due_date|date_format:"%d/%m/%Y"}{else}-{/if}</td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{/if}

{* === RECENT TIMESHEETS === *}
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock me-2"></i>Chấm công gần đây</span>
                <a href="{$base_url}/Timesheets" class="btn btn-sm btn-ghost">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Ngày</th><th>Nhân viên</th><th>Dự án</th><th>Giờ</th><th>OT</th><th>Trạng thái</th></tr>
                        </thead>
                        <tbody>
                            {foreach $recent_timesheets as $ts}
                            <tr>
                                <td>{$ts.work_date|date_format:"%d/%m/%Y"}</td>
                                <td>{$ts.user_name}</td>
                                <td>{$ts.project_name}</td>
                                <td><strong>{$ts.hours_worked}h</strong></td>
                                <td>{if $ts.overtime_hours > 0}<span class="text-warning">{$ts.overtime_hours}h</span>{else}-{/if}</td>
                                <td><span class="badge badge-status badge-{$ts.status}">{if $ts.status=='pending'}Chờ duyệt{elseif $ts.status=='approved'}Đã duyệt{else}Từ chối{/if}</span></td>
                            </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}

{block name="scripts"}
<script>
const costData = {$project_costs|json_encode};
if (costData.length > 0 && document.getElementById('costChart')) {
    new Chart(document.getElementById('costChart'), {
        type: 'bar',
        data: {
            labels: costData.map(p => p.project_code),
            datasets: [
                {
                    label: 'Ngân sách',
                    data: costData.map(p => p.budget),
                    backgroundColor: 'rgba(102, 126, 234, 0.3)',
                    borderColor: '#667eea',
                    borderWidth: 2,
                    borderRadius: 6,
                },
                {
                    label: 'Chi phí thực tế',
                    data: costData.map(p => p.actual_cost),
                    backgroundColor: 'rgba(240, 147, 251, 0.3)',
                    borderColor: '#f093fb',
                    borderWidth: 2,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#8b8fa3', font: { family: 'Inter' } } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VNĐ';
                        }
                    }
                }
            },
            scales: {
                x: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } },
                y: {
                    ticks: {
                        color: '#8b8fa3',
                        callback: v => (v / 1000000) + 'M'
                    },
                    grid: { color: 'rgba(255,255,255,0.04)' }
                }
            }
        }
    });
}
</script>
{/block}
