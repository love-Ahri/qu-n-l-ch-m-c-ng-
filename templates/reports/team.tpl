{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1><i class="bi bi-people me-2"></i>Báo cáo Nhóm</h1></div>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link" href="{$base_url}/Reports/individual">Cá nhân</a></li>
    <li class="nav-item"><a class="nav-link active" href="{$base_url}/Reports/team">Nhóm</a></li>
    <li class="nav-item"><a class="nav-link" href="{$base_url}/Reports/cost">Chi phí</a></li>
</ul>
<form method="GET" class="filter-bar mb-4">
    <select name="project_id" class="form-select">
        {foreach $projects as $p}<option value="{$p.id}" {if $p.id == $filters.project_id}selected{/if}>{$p.name}</option>{/foreach}
    </select>
    <input type="date" name="start_date" class="form-control" value="{$filters.start_date}">
    <input type="date" name="end_date" class="form-control" value="{$filters.end_date}">
    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Xem</button>
</form>
{if $report}
<div class="row g-3 mb-4">
    <div class="col-sm-4"><div class="stat-card primary"><div class="stat-value">{$report.task_stats.total|default:0}</div><div class="stat-label">Tổng Tasks</div></div></div>
    <div class="col-sm-4"><div class="stat-card success"><div class="stat-value">{$report.task_stats.done|default:0}</div><div class="stat-label">Hoàn thành</div></div></div>
    <div class="col-sm-4"><div class="stat-card accent"><div class="stat-value">{$report.task_stats.overdue|default:0}</div><div class="stat-label">Trễ hạn</div></div></div>
</div>
<div class="card"><div class="card-header">Hiệu suất thành viên</div><div class="card-body p-0">
    <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Tên</th><th>Vai trò DA</th><th>Phòng ban</th><th>Giờ làm</th><th>OT</th><th>Task (Done/Total)</th><th>Hiệu suất</th></tr></thead><tbody>
        {foreach $report.members as $m}
        <tr><td><strong>{$m.name}</strong></td><td><span class="badge badge-{$m.project_role}">{$m.project_role}</span></td>
            <td>{$m.department|default:'-'}</td><td>{$m.total_hours|number_format:1}h</td>
            <td>{if $m.total_ot > 0}<span class="text-warning">{$m.total_ot|number_format:1}h</span>{else}-{/if}</td>
            <td>{$m.done_tasks}/{$m.total_tasks}</td>
            <td>{if $m.total_tasks > 0}
                <div class="progress" style="height:6px;width:80px;display:inline-block;vertical-align:middle;"><div class="progress-bar bg-success" style="width:{($m.done_tasks/$m.total_tasks*100)|number_format:0}%"></div></div>
                <small>{($m.done_tasks/$m.total_tasks*100)|number_format:0}%</small>
            {else}-{/if}</td>
        </tr>
        {/foreach}
    </tbody></table></div>
</div></div>
{/if}
{/block}
