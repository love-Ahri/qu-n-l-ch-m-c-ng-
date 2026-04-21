{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1>{if $mode == 'create'}Tạo Nhiệm vụ{else}Sửa Nhiệm vụ{/if}</h1><a href="{$base_url}/Tasks" class="btn btn-ghost"><i class="bi bi-arrow-left"></i></a></div>
<div class="row"><div class="col-lg-8"><div class="card"><div class="card-body">
    <form method="POST" action="{$base_url}/Tasks/{if $mode == 'create'}store{else}update/{$task.id}{/if}">
        <input type="hidden" name="csrf_token" value="{$csrf_token}">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Tên nhiệm vụ *</label><input type="text" name="title" class="form-control" value="{$task.title|default:''}" required></div>
            <div class="col-md-4"><label class="form-label">Dự án</label>
                <select name="project_id" class="form-select" id="projectSelect" required>
                    {foreach $projects as $p}<option value="{$p.id}" {if $p.id == ($task.project_id|default:$project_id)}selected{/if}>{$p.name}</option>{/foreach}
                </select>
            </div>
            <div class="col-12"><label class="form-label">Mô tả</label><textarea name="description" class="form-control" rows="3">{$task.description|default:''}</textarea></div>
            <div class="col-md-4"><label class="form-label">Giao cho</label>
                <select name="assigned_to" class="form-select" id="assigneeSelect">
                    <option value="">-- Chưa giao --</option>
                    {foreach $members as $m}<option value="{$m.id}" {if ($task.assigned_to|default:'') == $m.id}selected{/if}>{$m.name}</option>{/foreach}
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Ưu tiên</label>
                <select name="priority" class="form-select">
                    {foreach ['low'=>'Thấp','medium'=>'Trung bình','high'=>'Cao','urgent'=>'Khẩn cấp'] as $k=>$v}
                    <option value="{$k}" {if ($task.priority|default:'medium') == $k}selected{/if}>{$v}</option>{/foreach}
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    {foreach ['todo'=>'To-Do','doing'=>'Đang làm','review'=>'Review','done'=>'Hoàn thành'] as $k=>$v}
                    <option value="{$k}" {if ($task.status|default:'todo') == $k}selected{/if}>{$v}</option>{/foreach}
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Ngày bắt đầu</label><input type="date" name="start_date" class="form-control" value="{$task.start_date|default:''}"></div>
            <div class="col-md-4"><label class="form-label">Hạn</label><input type="date" name="due_date" class="form-control" value="{$task.due_date|default:''}"></div>
            <div class="col-md-4"><label class="form-label">Giờ ước tính</label><input type="number" name="estimated_hours" class="form-control" value="{$task.estimated_hours|default:0}" step="0.5"></div>
        </div>
        <div class="mt-4"><button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {if $mode == 'create'}Tạo{else}Cập nhật{/if}</button></div>
    </form>
</div></div></div></div>
{/block}
