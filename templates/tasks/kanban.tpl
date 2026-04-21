{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1><i class="bi bi-kanban me-2"></i>Kanban Board</h1>
        <select class="form-select" style="max-width:250px;" onchange="location='{$base_url}/Tasks/kanban/'+this.value">
            {foreach $projects as $p}
            <option value="{$p.id}" {if $p.id == $project_id}selected{/if}>{$p.code} - {$p.name}</option>
            {/foreach}
        </select>
    </div>
    {if $project}
    <a href="{$base_url}/Tasks/create/{$project_id}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm Task</a>
    {/if}
</div>

{if $project}
<div class="kanban-board">
    {foreach ['todo' => 'To-Do', 'doing' => 'Đang làm', 'review' => 'Review', 'done' => 'Hoàn thành'] as $status => $label}
    <div class="kanban-column {$status}">
        <div class="kanban-column-header">
            <span>{$label}</span>
            <span class="count">{$kanban.$status|@count|default:0}</span>
        </div>
        <div class="kanban-cards" data-status="{$status}">
            {if isset($kanban.$status)}
            {foreach $kanban.$status as $task}
            <div class="kanban-card" data-task-id="{$task.id}" draggable="true">
                <div class="d-flex align-items-start justify-content-between mb-1">
                    <span class="badge badge-{$task.priority}" style="font-size:10px;">{$task.priority|upper}</span>
                    <div class="dropdown">
                        <button class="btn btn-xs btn-ghost" data-bs-toggle="dropdown" style="padding:0 4px;"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{$base_url}/Tasks/edit/{$task.id}"><i class="bi bi-pencil"></i> Sửa</a></li>
                            <li><a class="dropdown-item text-danger" href="{$base_url}/Tasks/delete/{$task.id}" data-confirm="Xóa task này?"><i class="bi bi-trash"></i> Xóa</a></li>
                        </ul>
                    </div>
                </div>
                <div class="kanban-card-title">{$task.title}</div>
                <div class="kanban-card-meta">
                    <div class="kanban-card-assignee">
                        {if $task.assignee_name}
                        <span class="avatar-xs">{$task.assignee_name|mb_substr:0:1|upper}</span>
                        <span>{$task.assignee_name}</span>
                        {else}
                        <span class="text-muted">Chưa giao</span>
                        {/if}
                    </div>
                    {if $task.due_date}
                    <span {if $task.due_date < $smarty.now|date_format:'%Y-%m-%d' && $task.status != 'done'}class="text-danger"{/if}>
                        <i class="bi bi-calendar-event"></i> {$task.due_date|date_format:"%d/%m"}
                    </span>
                    {/if}
                </div>
                {if $task.estimated_hours > 0}
                <div class="mt-2">
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar" style="width:{if $task.estimated_hours > 0}{min(($task.actual_hours/$task.estimated_hours*100), 100)|number_format:0}{else}0{/if}%"></div>
                    </div>
                    <small class="text-muted" style="font-size:10px;">{$task.actual_hours|number_format:1}h / {$task.estimated_hours}h</small>
                </div>
                {/if}
            </div>
            {/foreach}
            {/if}
        </div>
    </div>
    {/foreach}
</div>
{else}
<div class="empty-state"><i class="bi bi-kanban"></i><p>Chọn một dự án để xem Kanban board</p></div>
{/if}
{/block}
