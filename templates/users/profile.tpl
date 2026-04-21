{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header"><h1>Hồ sơ cá nhân</h1></div>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center">
            <div class="card-body py-4">
                <div class="user-avatar mx-auto mb-3" style="width:80px;height:80px;font-size:32px;">{$profile.name|mb_substr:0:1|upper}</div>
                <h5 class="mb-1">{$profile.name}</h5>
                <p class="text-secondary mb-2">{$profile.email}</p>
                <p class="text-muted mb-3">{$profile.department|default:'Chưa có phòng ban'}</p>
                {foreach $profile.roles as $r}
                <span class="badge badge-{$r.name}">{$r.display_name}</span>
                {/foreach}
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Chấm công gần đây</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Ngày</th><th>Dự án</th><th>Giờ</th><th>OT</th><th>Trạng thái</th></tr></thead>
                        <tbody>
                            {foreach $timesheets as $ts}
                            <tr>
                                <td>{$ts.work_date|date_format:"%d/%m/%Y"}</td>
                                <td>{$ts.project_name}</td>
                                <td>{$ts.hours_worked}h</td>
                                <td>{if $ts.overtime_hours > 0}<span class="text-warning">{$ts.overtime_hours}h</span>{else}-{/if}</td>
                                <td><span class="badge badge-status badge-{$ts.status}">{if $ts.status=='approved'}Đã duyệt{elseif $ts.status=='pending'}Chờ duyệt{else}Từ chối{/if}</span></td>
                            </tr>
                            {foreachelse}
                            <tr><td colspan="5" class="text-center text-muted py-3">Chưa có dữ liệu</td></tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
