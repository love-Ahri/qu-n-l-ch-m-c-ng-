<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:49:03
  from 'D:\xampp\htdocs\ddonf\templates\dashboard\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e701bf3f04d0_78067646',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '36c799731ae79838351497bb82430e6599c3cf3e' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\dashboard\\index.tpl',
      1 => 1776746387,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e701bf3f04d0_78067646 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_145126199869e701bf3c3067_02539245', "content");
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_50365635169e701bf3ee3a4_71653257', "scripts");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_145126199869e701bf3c3067_02539245 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_145126199869e701bf3c3067_02539245',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.count.php','function'=>'smarty_modifier_count',),));
?>

<div class="page-header">
    <div>
        <h1>Tổng quan</h1>
        <p class="text-secondary mb-0">Xin chào, <strong><?php echo $_smarty_tpl->tpl_vars['current_user']->value['name'];?>
</strong>! Hôm nay là <?php echo smarty_modifier_date_format(time(),"%d/%m/%Y");?>
</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value"><?php echo $_smarty_tpl->tpl_vars['project_count']->value;?>
</div>
                    <div class="stat-label">Dự án Active</div>
                </div>
                <div class="stat-icon primary"><i class="bi bi-folder2-open"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value"><?php echo $_smarty_tpl->tpl_vars['task_count']->value;?>
</div>
                    <div class="stat-label">Task đang thực hiện</div>
                </div>
                <div class="stat-icon warning"><i class="bi bi-list-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value"><?php echo $_smarty_tpl->tpl_vars['user_count']->value;?>
</div>
                    <div class="stat-label">Nhân sự hoạt động</div>
                </div>
                <div class="stat-icon success"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card accent">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-value"><?php echo $_smarty_tpl->tpl_vars['pending_ts']->value;?>
</div>
                    <div class="stat-label">Chấm công chờ duyệt</div>
                </div>
                <div class="stat-icon accent"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
        <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bar-chart me-2"></i>Chi phí Dự án (Thực tế vs Ngân sách)</span>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="costChart"></canvas>
                </div>
            </div>
        </div>
    </div>

        <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-activity me-2"></i>Hoạt động gần đây
            </div>
            <div class="card-body p-0">
                <div style="max-height:340px; overflow-y:auto;">
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['audit_logs']->value, 'log');
$_smarty_tpl->tpl_vars['log']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['log']->value) {
$_smarty_tpl->tpl_vars['log']->do_else = false;
?>
                    <div class="d-flex align-items-start gap-3 px-3 py-2" style="border-bottom:1px solid var(--border-color);">
                        <div class="user-avatar" style="width:28px;height:28px;font-size:11px;flex-shrink:0;">
                            <?php echo mb_strtoupper((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'mb_substr' ][ 0 ], array( $_smarty_tpl->tpl_vars['log']->value['user_name'],0,1 )) ?? '', 'UTF-8');?>

                        </div>
                        <div style="min-width:0;">
                            <div style="font-size:13px;">
                                <strong><?php echo (($tmp = $_smarty_tpl->tpl_vars['log']->value['user_name'] ?? null)===null||$tmp==='' ? 'Hệ thống' ?? null : $tmp);?>
</strong>
                                <?php if ($_smarty_tpl->tpl_vars['log']->value['action'] == 'create') {?>tạo mới<?php } elseif ($_smarty_tpl->tpl_vars['log']->value['action'] == 'update') {?>cập nhật<?php } elseif ($_smarty_tpl->tpl_vars['log']->value['action'] == 'delete') {?>xóa<?php } elseif ($_smarty_tpl->tpl_vars['log']->value['action'] == 'approve') {?>duyệt<?php } elseif ($_smarty_tpl->tpl_vars['log']->value['action'] == 'login') {?>đăng nhập<?php } else {
echo $_smarty_tpl->tpl_vars['log']->value['action'];
}?>
                                <span class="text-secondary"><?php echo $_smarty_tpl->tpl_vars['log']->value['entity_type'];?>
</span>
                            </div>
                            <small class="text-muted"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['log']->value['created_at'],"%d/%m %H:%M");?>
</small>
                        </div>
                    </div>
                    <?php
}
if ($_smarty_tpl->tpl_vars['log']->do_else) {
?>
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>Chưa có hoạt động</p></div>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (smarty_modifier_count($_smarty_tpl->tpl_vars['my_tasks']->value) > 0) {?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-check2-square me-2"></i>Nhiệm vụ của tôi</span>
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks" class="btn btn-sm btn-ghost">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nhiệm vụ</th>
                                <th>Dự án</th>
                                <th>Ưu tiên</th>
                                <th>Trạng thái</th>
                                <th>Hạn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['my_tasks']->value, 'task');
$_smarty_tpl->tpl_vars['task']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['task']->value) {
$_smarty_tpl->tpl_vars['task']->do_else = false;
?>
                            <tr>
                                <td><strong><?php echo $_smarty_tpl->tpl_vars['task']->value['title'];?>
</strong></td>
                                <td><span class="text-secondary"><?php echo $_smarty_tpl->tpl_vars['task']->value['project_code'];?>
</span> <?php echo $_smarty_tpl->tpl_vars['task']->value['project_name'];?>
</td>
                                <td><span class="badge badge-<?php echo $_smarty_tpl->tpl_vars['task']->value['priority'];?>
"><?php echo mb_strtoupper((string) $_smarty_tpl->tpl_vars['task']->value['priority'] ?? '', 'UTF-8');?>
</span></td>
                                <td><span class="badge badge-status badge-<?php echo $_smarty_tpl->tpl_vars['task']->value['status'];?>
"><?php if ($_smarty_tpl->tpl_vars['task']->value['status'] == 'todo') {?>To-Do<?php } elseif ($_smarty_tpl->tpl_vars['task']->value['status'] == 'doing') {?>Đang làm<?php } elseif ($_smarty_tpl->tpl_vars['task']->value['status'] == 'review') {?>Review<?php } else { ?>Done<?php }?></span></td>
                                <td><?php if ($_smarty_tpl->tpl_vars['task']->value['due_date']) {
echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['task']->value['due_date'],"%d/%m/%Y");
} else { ?>-<?php }?></td>
                            </tr>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }?>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock me-2"></i>Chấm công gần đây</span>
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets" class="btn btn-sm btn-ghost">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr><th>Ngày</th><th>Nhân viên</th><th>Dự án</th><th>Giờ</th><th>OT</th><th>Trạng thái</th></tr>
                        </thead>
                        <tbody>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['recent_timesheets']->value, 'ts');
$_smarty_tpl->tpl_vars['ts']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ts']->value) {
$_smarty_tpl->tpl_vars['ts']->do_else = false;
?>
                            <tr>
                                <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ts']->value['work_date'],"%d/%m/%Y");?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['ts']->value['user_name'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['ts']->value['project_name'];?>
</td>
                                <td><strong><?php echo $_smarty_tpl->tpl_vars['ts']->value['hours_worked'];?>
h</strong></td>
                                <td><?php if ($_smarty_tpl->tpl_vars['ts']->value['overtime_hours'] > 0) {?><span class="text-warning"><?php echo $_smarty_tpl->tpl_vars['ts']->value['overtime_hours'];?>
h</span><?php } else { ?>-<?php }?></td>
                                <td><span class="badge badge-status badge-<?php echo $_smarty_tpl->tpl_vars['ts']->value['status'];?>
"><?php if ($_smarty_tpl->tpl_vars['ts']->value['status'] == 'pending') {?>Chờ duyệt<?php } elseif ($_smarty_tpl->tpl_vars['ts']->value['status'] == 'approved') {?>Đã duyệt<?php } else { ?>Từ chối<?php }?></span></td>
                            </tr>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
}
/* {/block "content"} */
/* {block "scripts"} */
class Block_50365635169e701bf3ee3a4_71653257 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'scripts' => 
  array (
    0 => 'Block_50365635169e701bf3ee3a4_71653257',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
const costData = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'json_encode' ][ 0 ], array( $_smarty_tpl->tpl_vars['project_costs']->value ));?>
;
if (costData.length > 0 && document.getElementById('costChart')) {
    new Chart(document.getElementById('costChart'), {
        type: 'bar',
        data: {
            labels: costData.map(p => p.project_code),
            datasets: [
                {
                    label: 'Ngân sách',
                    data: costData.map(p => p.budget),
                    backgroundColor: 'rgba(102, 126, 234, 0.3)',
                    borderColor: '#667eea',
                    borderWidth: 2,
                    borderRadius: 6,
                },
                {
                    label: 'Chi phí thực tế',
                    data: costData.map(p => p.actual_cost),
                    backgroundColor: 'rgba(240, 147, 251, 0.3)',
                    borderColor: '#f093fb',
                    borderWidth: 2,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#8b8fa3', font: { family: 'Inter' } } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VNĐ';
                        }
                    }
                }
            },
            scales: {
                x: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } },
                y: {
                    ticks: {
                        color: '#8b8fa3',
                        callback: v => (v / 1000000) + 'M'
                    },
                    grid: { color: 'rgba(255,255,255,0.04)' }
                }
            }
        }
    });
}
<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scripts"} */
}
