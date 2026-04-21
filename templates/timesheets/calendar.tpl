{extends file="layout/main.tpl"}
{block name="content"}
<div class="page-header">
    <h1><i class="bi bi-calendar3 me-2"></i>Lịch Chấm công - {$view_user.name|default:'Tôi'}</h1>
    <div class="d-flex gap-2">
        <a href="{$base_url}/Timesheets/calendar?year={if $month == 1}{$year-1}{else}{$year}{/if}&month={if $month == 1}12{else}{$month-1}{/if}&user_id={$view_user_id}" class="btn btn-ghost btn-sm"><i class="bi bi-chevron-left"></i></a>
        <span class="btn btn-ghost btn-sm disabled">Tháng {$month}/{$year}</span>
        <a href="{$base_url}/Timesheets/calendar?year={if $month == 12}{$year+1}{else}{$year}{/if}&month={if $month == 12}1{else}{$month+1}{/if}&user_id={$view_user_id}" class="btn btn-ghost btn-sm"><i class="bi bi-chevron-right"></i></a>
    </div>
</div>
<div class="card">
    <div class="card-body p-2">
        <div class="calendar-grid">
            {foreach ['T2','T3','T4','T5','T6','T7','CN'] as $d}
            <div class="calendar-header-cell">{$d}</div>
            {/foreach}

            {* Empty cells before first day *}
            {for $i=1 to $start_dow-1}
            <div class="calendar-cell other-month"></div>
            {/for}

            {for $day=1 to $days_in_month}
            {assign var="dateStr" value="{$year}-{$month|str_pad:2:'0':$smarty.const.STR_PAD_LEFT}-{$day|str_pad:2:'0':$smarty.const.STR_PAD_LEFT}"}
            <div class="calendar-cell {if $dateStr == date('Y-m-d')}today{/if}">
                <div class="calendar-date">{$day}</div>
                {if isset($by_date.$dateStr)}
                    {foreach $by_date.$dateStr as $entry}
                    <div class="calendar-entry {if $entry.is_overtime}ot{/if}" data-bs-toggle="tooltip" title="{$entry.project_name}: {$entry.description|default:'...'}">
                        <strong>{$entry.hours_worked}h</strong> {$entry.project_code|default:''}
                    </div>
                    {/foreach}
                {/if}
            </div>
            {/for}
        </div>
    </div>
</div>
{/block}
