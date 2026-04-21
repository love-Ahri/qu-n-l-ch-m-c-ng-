{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <h1>Thành viên - {$project.name}</h1>
    <a href="{$base_url}/Projects/detail/{$project.id}" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Thêm thành viên</div>
            <div class="card-body">
                <form method="POST" action="{$base_url}/Projects/addMember/{$project.id}">
                    <input type="hidden" name="csrf_token" value="{$csrf_token}">
                    <div class="mb-3">
                        <select name="user_id" class="form-select" required>
                            <option value="">-- Chọn nhân viên --</option>
                            {foreach $all_users as $u}<option value="{$u.id}">{$u.name} ({$u.email})</option>{/foreach}
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="project_role" class="form-select">
                            <option value="developer">Developer</option>
                            <option value="manager">Manager</option>
                            <option value="qa">QA</option>
                            <option value="designer">Designer</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-plus"></i> Thêm</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Danh sách thành viên ({$project.members|@count})</div>
            <div class="card-body p-0">
                <div class="table-responsive"><table class="table mb-0"><thead><tr><th>Tên</th><th>Vai trò</th><th>Ngày tham gia</th><th></th></tr></thead><tbody>
                    {foreach $project.members as $m}
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><div class="user-avatar" style="width:28px;height:28px;font-size:11px;">{$m.name|mb_substr:0:1|upper}</div><div><strong>{$m.name}</strong><br><small class="text-muted">{$m.email}</small></div></div></td>
                        <td><span class="badge badge-{$m.project_role}">{$m.project_role}</span></td>
                        <td>{$m.joined_at|date_format:"%d/%m/%Y"}</td>
                        <td><a href="{$base_url}/Projects/removeMember/{$project.id}/{$m.user_id}" class="btn btn-sm btn-ghost text-danger" data-confirm="Xóa thành viên này?"><i class="bi bi-x-lg"></i></a></td>
                    </tr>
                    {/foreach}
                </tbody></table></div>
            </div>
        </div>
    </div>
</div>
{/block}
