{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <h1>{if $mode == 'create'}Tạo Dự án mới{else}Sửa Dự án{/if}</h1>
    <a href="{$base_url}/Projects" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>
<div class="row"><div class="col-lg-8">
<div class="card"><div class="card-body">
    <form method="POST" action="{$base_url}/Projects/{if $mode == 'create'}store{else}update/{$project.id}{/if}">
        <input type="hidden" name="csrf_token" value="{$csrf_token}">
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Tên dự án *</label><input type="text" name="name" class="form-control" value="{$project.name|default:''}" required></div>
            <div class="col-md-4"><label class="form-label">Mã dự án *</label><input type="text" name="code" class="form-control" value="{$project.code|default:''}" required placeholder="VD: PRJ-005"></div>
            <div class="col-12"><label class="form-label">Mô tả</label><textarea name="description" class="form-control" rows="3">{$project.description|default:''}</textarea></div>
            <div class="col-md-6"><label class="form-label">Khách hàng</label><input type="text" name="client_name" class="form-control" value="{$project.client_name|default:''}"></div>
            <div class="col-md-6"><label class="form-label">Ngân sách (VNĐ)</label><input type="number" name="budget" class="form-control" value="{$project.budget|default:0}" step="1000000"></div>
            <div class="col-md-4"><label class="form-label">Ngày bắt đầu</label><input type="date" name="start_date" class="form-control" value="{$project.start_date|default:''}"></div>
            <div class="col-md-4"><label class="form-label">Ngày kết thúc</label><input type="date" name="end_date" class="form-control" value="{$project.end_date|default:''}"></div>
            <div class="col-md-4"><label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="planning" {if ($project.status|default:'planning') == 'planning'}selected{/if}>Lập kế hoạch</option>
                    <option value="active" {if ($project.status|default:'') == 'active'}selected{/if}>Active</option>
                    <option value="on_hold" {if ($project.status|default:'') == 'on_hold'}selected{/if}>Tạm dừng</option>
                    <option value="completed" {if ($project.status|default:'') == 'completed'}selected{/if}>Hoàn thành</option>
                    <option value="cancelled" {if ($project.status|default:'') == 'cancelled'}selected{/if}>Hủy</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {if $mode == 'create'}Tạo dự án{else}Cập nhật{/if}</button>
        </div>
    </form>
</div></div>
</div></div>
{/block}
