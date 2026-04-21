{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <div>
        <h1><i class="bi bi-clock-history me-2"></i>Chấm công</h1>
        <p class="text-secondary mb-0">Tuần này: <strong>{$weekly_summary.total|default:0|number_format:1}h</strong> (OT: {$weekly_summary.total_ot|default:0|number_format:1}h)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{$base_url}/Timesheets/calendar" class="btn btn-ghost"><i class="bi bi-calendar3"></i> Lịch</a>
        <a href="{$base_url}/Timesheets/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Chấm công</a>
    </div>
</div>

{if ($is_admin || $is_pm) && $pending|@count > 0}
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-hourglass-split me-2"></i>Chờ duyệt ({$pending|@count})</div>
    <div class="card-body p-0"><div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Ngày</th><th>Nhân viên</th><th>Dự án</th><th>Task</th><th>Giờ</th><th>OT</th><th>Thao tác</th></tr></thead>
        <tbody>
            {foreach $pending as $p}
            <tr>
                <td>{$p.work_date|date_format:"%d/%m/%Y"}</td>
                <td>{$p.user_name}</td><td>{$p.project_name}</td><td>{$p.task_title|default:'-'}</td>
                <td><strong>{$p.hours_worked}h</strong></td>
                <td>{if $p.overtime_hours > 0}<span class="text-warning">{$p.overtime_hours}h</span>{else}-{/if}</td>
                <td class="d-flex gap-1">
                    <a href="{$base_url}/Timesheets/approve/{$p.id}" class="btn btn-sm btn-success"><i class="bi bi-check"></i></a>
                    <a href="{$base_url}/Timesheets/reject/{$p.id}" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table></div></div>
</div>
{/if}

<div class="card">
    <div class="card-header">Chấm công của tôi (tháng này)</div>
    <div class="card-body p-0"><div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Ngày</th><th>Dự án</th><th>Task</th><th>Ca</th><th>Giờ</th><th>OT</th><th>Mô tả</th><th>TT</th><th></th></tr></thead>
        <tbody>
            {foreach $my_timesheets as $ts}
            <tr>
                <td>{$ts.work_date|date_format:"%d/%m/%Y"}</td>
                <td>{$ts.project_name}</td><td>{$ts.task_title|default:'-'}</td>
                <td>{if $ts.shift=='morning'}Sáng{elseif $ts.shift=='afternoon'}Chiều{elseif $ts.shift=='evening'}Tối{else}Linh hoạt{/if}</td>
                <td><strong>{$ts.hours_worked}h</strong></td>
                <td>{if $ts.overtime_hours > 0}<span class="text-warning">{$ts.overtime_hours}h</span>{else}-{/if}</td>
                <td><small>{$ts.description|truncate:40}</small></td>
                <td><span class="badge badge-status badge-{$ts.status}">{if $ts.status=='pending'}Chờ{elseif $ts.status=='approved'}OK{else}Từ chối{/if}</span></td>
                <td>{if $ts.status == 'pending'}<a href="{$base_url}/Timesheets/delete/{$ts.id}" class="btn btn-xs btn-ghost text-danger" data-confirm="Xóa?"><i class="bi bi-trash"></i></a>{/if}</td>
            </tr>
            {foreachelse}
            <tr><td colspan="9" class="text-center text-muted py-3">Chưa có dữ liệu tháng này</td></tr>
            {/foreach}
        </tbody>
    </table></div></div>
</div>
{/block}
