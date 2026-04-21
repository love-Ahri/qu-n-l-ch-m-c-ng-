{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <h1><i class="bi bi-calendar2-week me-2"></i>Phân bổ Nguồn lực</h1>
    <div class="d-flex gap-2">
        <a href="{$base_url}/Resources/allocation?week={$prev_week}" class="btn btn-ghost"><i class="bi bi-chevron-left"></i> Tuần trước</a>
        <span class="btn btn-ghost disabled">{$week_start|date_format:"%d/%m"} - {$data.week_end|date_format:"%d/%m/%Y"}</span>
        <a href="{$base_url}/Resources/allocation?week={$next_week}" class="btn btn-ghost">Tuần sau <i class="bi bi-chevron-right"></i></a>
    </div>
</div>

{if $alerts|@count > 0}
{foreach $alerts as $alert}
<div class="overbooking-alert">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span>{$alert.message}</span>
</div>
{/foreach}
{/if}

<div class="card">
    <div class="card-body p-0" style="overflow-x:auto;">
        <table class="allocation-table">
            <thead>
                <tr>
                    <th style="min-width:160px; text-align:left; position:sticky; left:0; background:var(--bg-card); z-index:11;">Nhân viên</th>
                    {foreach $data.days as $day}
                    <th class="allocation-cell">
                        {$day|date_format:"%a"}<br>
                        <small>{$day|date_format:"%d/%m"}</small>
                    </th>
                    {/foreach}
                    <th style="min-width:80px;">Tổng</th>
                </tr>
            </thead>
            <tbody>
                {foreach $data.matrix as $row}
                <tr>
                    <td style="text-align:left; position:sticky; left:0; background:var(--bg-card); z-index:10;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:28px;height:28px;font-size:11px;">{$row.user.name|mb_substr:0:1|upper}</div>
                            <div><strong style="font-size:13px;">{$row.user.name}</strong><br><small class="text-muted">{$row.user.department|default:''}</small></div>
                        </div>
                    </td>
                    {foreach $data.days as $day}
                    <td class="allocation-cell {$row.days.$day.status}">
                        {if $row.days.$day.hours > 0}
                        <span class="allocation-hours {$row.days.$day.status}">{$row.days.$day.hours|number_format:1}</span>
                        {foreach $row.days.$day.entries as $e}
                        <div class="allocation-task" title="{$e.project_name}: {$e.task_title|default:''}">{$e.project_code|default:''}</div>
                        {/foreach}
                        {else}
                        <span class="text-muted">-</span>
                        {/if}
                    </td>
                    {/foreach}
                    <td>
                        <span class="allocation-hours {$row.week_status}" style="font-size:18px;">{$row.total_hours|number_format:1}</span>
                        {if $row.is_overbooked}<br><span class="badge badge-urgent" style="font-size:9px;">QUÁ TẢI</span>{/if}
                    </td>
                </tr>
                {foreachelse}
                <tr><td colspan="{$data.days|@count + 2}" class="text-center text-muted py-4">Không có dữ liệu tuần này</td></tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{/block}
