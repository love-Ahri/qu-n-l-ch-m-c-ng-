{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <h1><i class="bi bi-folder2-open me-2"></i>Quản lý Dự án</h1>
    {if in_array('admin', $user_roles) || in_array('pm', $user_roles)}
    <a href="{$base_url}/Projects/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tạo Dự án</a>
    {/if}
</div>
<div class="row g-3">
    {foreach $projects as $p}
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <span class="badge badge-{$p.status}">{if $p.status=='active'}Active{elseif $p.status=='planning'}Lập kế hoạch{elseif $p.status=='completed'}Hoàn thành{elseif $p.status=='on_hold'}Tạm dừng{else}Hủy{/if}</span>
                    <small class="text-muted">{$p.code}</small>
                </div>
                <h6 class="mb-1"><a href="{$base_url}/Projects/detail/{$p.id}">{$p.name}</a></h6>
                <p class="text-secondary fs-14 mb-3">{$p.client_name|default:'Nội bộ'}</p>
                <div class="d-flex gap-3 mb-3" style="font-size:13px;">
                    <span><i class="bi bi-people text-primary me-1"></i>{$p.member_count} TV</span>
                    <span><i class="bi bi-list-check text-warning me-1"></i>{$p.done_count}/{$p.task_count} Task</span>
                </div>
                {if $p.task_count > 0}
                <div class="progress mb-2" style="height:6px;">
                    <div class="progress-bar bg-success" style="width:{if $p.task_count > 0}{($p.done_count/$p.task_count*100)|number_format:0}{else}0{/if}%"></div>
                </div>
                <small class="text-muted">{if $p.task_count > 0}{($p.done_count/$p.task_count*100)|number_format:0}%{else}0%{/if} hoàn thành</small>
                {/if}
            </div>
            <div class="card-body pt-0 d-flex gap-1">
                <a href="{$base_url}/Projects/detail/{$p.id}" class="btn btn-sm btn-ghost"><i class="bi bi-eye"></i> Xem</a>
                <a href="{$base_url}/Tasks/kanban/{$p.id}" class="btn btn-sm btn-ghost"><i class="bi bi-kanban"></i> Kanban</a>
                {if in_array('admin', $user_roles) || in_array('pm', $user_roles)}
                <a href="{$base_url}/Projects/edit/{$p.id}" class="btn btn-sm btn-ghost"><i class="bi bi-pencil"></i></a>
                {/if}
            </div>
        </div>
    </div>
    {foreachelse}
    <div class="col-12"><div class="empty-state"><i class="bi bi-folder-x"></i><p>Chưa có dự án nào</p></div></div>
    {/foreach}
</div>
{/block}
