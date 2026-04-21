{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <div>
        <h1><i class="bi bi-people me-2"></i>Quản lý Người dùng</h1>
    </div>
    {if in_array('admin', $user_roles)}
    <a href="{$base_url}/Users/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm người dùng</a>
    {/if}
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{$base_url}/Users" class="filter-bar">
            <input type="text" name="q" class="form-control" placeholder="Tìm tên, email, SĐT..." value="{$filters.q}">
            <select name="department" class="form-select">
                <option value="">-- Phòng ban --</option>
                {foreach $departments as $d}
                <option value="{$d.department}" {if $filters.department == $d.department}selected{/if}>{$d.department}</option>
                {/foreach}
            </select>
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="1" {if $filters.status === '1'}selected{/if}>Hoạt động</option>
                <option value="0" {if $filters.status === '0'}selected{/if}>Khóa</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Tìm</button>
            <a href="{$base_url}/Users" class="btn btn-ghost btn-sm">Xóa lọc</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th><th>Họ tên</th><th>Email</th><th>Điện thoại</th>
                        <th>Phòng ban</th><th>Vai trò</th><th>Trạng thái</th><th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach $users as $u}
                    <tr>
                        <td>{$u.id}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">
                                    {$u.name|mb_substr:0:1|upper}
                                </div>
                                <strong>{$u.name}</strong>
                            </div>
                        </td>
                        <td>{$u.email}</td>
                        <td>{$u.phone|default:'-'}</td>
                        <td>{$u.department|default:'-'}</td>
                        <td>
                            {if $u.role_list}
                                {foreach $u.role_list as $rn}
                                <span class="badge badge-{$rn}">{$rn}</span>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if $u.is_active}
                            <span class="badge badge-status badge-active">Hoạt động</span>
                            {else}
                            <span class="badge badge-status badge-inactive">Khóa</span>
                            {/if}
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{$base_url}/Users/edit/{$u.id}" class="btn btn-sm btn-ghost" title="Sửa"><i class="bi bi-pencil"></i></a>
                                {if in_array('admin', $user_roles)}
                                <a href="{$base_url}/Users/toggleActive/{$u.id}" class="btn btn-sm btn-ghost" title="{if $u.is_active}Khóa{else}Mở khóa{/if}">
                                    <i class="bi bi-{if $u.is_active}lock{else}unlock{/if}"></i>
                                </a>
                                <a href="{$base_url}/Users/delete/{$u.id}" class="btn btn-sm btn-ghost text-danger" data-confirm="Xóa người dùng '{$u.name}'?" title="Xóa"><i class="bi bi-trash"></i></a>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {foreachelse}
                    <tr><td colspan="8" class="empty-state"><i class="bi bi-inbox"></i><p>Không có dữ liệu</p></td></tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {if $pagination.total_pages > 1}
    <div class="card-body d-flex justify-content-between align-items-center">
        <small class="text-muted">Hiển thị {$pagination.current_page}/{$pagination.total_pages} trang ({$pagination.total} kết quả)</small>
        <nav>
            <ul class="pagination mb-0">
                <li class="page-item {if !$pagination.has_prev}disabled{/if}"><a class="page-link" href="{$base_url}/Users?page={$pagination.current_page - 1}&q={$filters.q}&department={$filters.department}&status={$filters.status}">‹</a></li>
                {for $p=1 to $pagination.total_pages}
                <li class="page-item {if $p == $pagination.current_page}active{/if}"><a class="page-link" href="{$base_url}/Users?page={$p}&q={$filters.q}&department={$filters.department}&status={$filters.status}">{$p}</a></li>
                {/for}
                <li class="page-item {if !$pagination.has_next}disabled{/if}"><a class="page-link" href="{$base_url}/Users?page={$pagination.current_page + 1}&q={$filters.q}&department={$filters.department}&status={$filters.status}">›</a></li>
            </ul>
        </nav>
    </div>
    {/if}
</div>
{/block}
