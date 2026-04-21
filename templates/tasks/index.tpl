{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1>Nhiệm vụ</h1>
    <div class="d-flex gap-2">
        <select class="form-select" style="max-width:250px;" onchange="location='{$base_url}/Tasks?project_id='+this.value">
            {foreach $projects as $p}<option value="{$p.id}" {if $p.id == $project_id}selected{/if}>{$p.code} - {$p.name}</option>{/foreach}
        </select>
        <a href="{$base_url}/Tasks/kanban/{$project_id}" class="btn btn-primary"><i class="bi bi-kanban"></i> Kanban</a>
    </div>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table mb-0">
    <thead><tr><th>Tên</th><th>Người thực hiện</th><th>Ưu tiên</th><th>Trạng thái</th><th>Giờ (TT/DK)</th><th>Hạn</th><th></th></tr></thead>
    <tbody>
        {foreach $tasks as $t}
        <tr>
            <td><strong>{$t.title}</strong></td>
            <td>{$t.assignee_name|default:'<span class="text-muted">—</span>'}</td>
            <td><span class="badge badge-{$t.priority}">{$t.priority|upper}</span></td>
            <td><span class="badge badge-status badge-{$t.status}">{$t.status}</span></td>
            <td>{$t.actual_hours|number_format:1}h / {$t.estimated_hours}h</td>
            <td>{if $t.due_date}{$t.due_date|date_format:"%d/%m/%Y"}{else}-{/if}</td>
            <td><a href="{$base_url}/Tasks/edit/{$t.id}" class="btn btn-sm btn-ghost"><i class="bi bi-pencil"></i></a></td>
        </tr>
        {foreachelse}
        <tr><td colspan="7" class="empty-state"><i class="bi bi-inbox"></i><p>Chưa có task</p></td></tr>
        {/foreach}
    </tbody>
</table></div></div></div>
{/block}
