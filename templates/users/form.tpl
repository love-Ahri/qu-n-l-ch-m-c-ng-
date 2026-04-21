{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <h1>{if $mode == 'create'}Thêm Người dùng{else}Sửa Người dùng{/if}</h1>
    <a href="{$base_url}/Users" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{$base_url}/Users/{if $mode == 'create'}store{else}update/{$user.id}{/if}">
                    <input type="hidden" name="csrf_token" value="{$csrf_token}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{$user.name|default:''}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{$user.email|default:''}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mật khẩu {if $mode == 'create'}<span class="text-danger">*</span>{/if}</label>
                            <input type="password" name="password" class="form-control" {if $mode == 'create'}required{/if} placeholder="{if $mode=='edit'}Để trống nếu không đổi{/if}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="{$user.phone|default:''}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phòng ban</label>
                            <input type="text" name="department" class="form-control" value="{$user.department|default:''}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Vai trò</label>
                            <div class="d-flex flex-wrap gap-3 mt-1">
                                {foreach $roles as $r}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{$r.id}" id="role_{$r.id}"
                                        {if $user && in_array($r.name, $user.role_names|default:[])}checked{/if}>
                                    <label class="form-check-label" for="role_{$r.id}">{$r.display_name}</label>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                        {if $mode == 'edit'}
                        <div class="col-md-6">
                            <label class="form-label">Đơn giá giờ công (VNĐ)</label>
                            <input type="number" name="rate_amount" class="form-control" placeholder="Để trống = theo vai trò" step="1000">
                            <small class="form-text">Giá hiện tại theo vai trò hoặc cá nhân</small>
                        </div>
                        {/if}
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> {if $mode == 'create'}Thêm{else}Cập nhật{/if}</button>
                        <a href="{$base_url}/Users" class="btn btn-ghost ms-2">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{/block}
