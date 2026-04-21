<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:53:18
  from 'D:\xampp\htdocs\ddonf\templates\timesheets\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e702be6f4d74_58723347',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '971ab8af36f010061d81cbea5485b70a992080eb' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\timesheets\\index.tpl',
      1 => 1776745759,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e702be6f4d74_58723347 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_45377642069e702be6b9d19_19773030', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_45377642069e702be6b9d19_19773030 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_45377642069e702be6b9d19_19773030',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.number_format.php','function'=>'smarty_modifier_number_format',),1=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.count.php','function'=>'smarty_modifier_count',),2=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),3=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.truncate.php','function'=>'smarty_modifier_truncate',),));
?>

<div class="page-header">
    <div>
        <h1><i class="bi bi-clock-history me-2"></i>Chấm công</h1>
        <p class="text-secondary mb-0">Tuần này: <strong><?php echo smarty_modifier_number_format((($tmp = $_smarty_tpl->tpl_vars['weekly_summary']->value['total'] ?? null)===null||$tmp==='' ? 0 ?? null : $tmp),1);?>
h</strong> (OT: <?php echo smarty_modifier_number_format((($tmp = $_smarty_tpl->tpl_vars['weekly_summary']->value['total_ot'] ?? null)===null||$tmp==='' ? 0 ?? null : $tmp),1);?>
h)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets/calendar" class="btn btn-ghost"><i class="bi bi-calendar3"></i> Lịch</a>
        <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Chấm công</a>
    </div>
</div>

<?php if (($_smarty_tpl->tpl_vars['is_admin']->value || $_smarty_tpl->tpl_vars['is_pm']->value) && smarty_modifier_count($_smarty_tpl->tpl_vars['pending']->value) > 0) {?>
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-hourglass-split me-2"></i>Chờ duyệt (<?php echo smarty_modifier_count($_smarty_tpl->tpl_vars['pending']->value);?>
)</div>
    <div class="card-body p-0"><div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Ngày</th><th>Nhân viên</th><th>Dự án</th><th>Task</th><th>Giờ</th><th>OT</th><th>Thao tác</th></tr></thead>
        <tbody>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['pending']->value, 'p');
$_smarty_tpl->tpl_vars['p']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->do_else = false;
?>
            <tr>
                <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['p']->value['work_date'],"%d/%m/%Y");?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['user_name'];?>
</td><td><?php echo $_smarty_tpl->tpl_vars['p']->value['project_name'];?>
</td><td><?php echo (($tmp = $_smarty_tpl->tpl_vars['p']->value['task_title'] ?? null)===null||$tmp==='' ? '-' ?? null : $tmp);?>
</td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['p']->value['hours_worked'];?>
h</strong></td>
                <td><?php if ($_smarty_tpl->tpl_vars['p']->value['overtime_hours'] > 0) {?><span class="text-warning"><?php echo $_smarty_tpl->tpl_vars['p']->value['overtime_hours'];?>
h</span><?php } else { ?>-<?php }?></td>
                <td class="d-flex gap-1">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets/approve/<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-sm btn-success"><i class="bi bi-check"></i></a>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets/reject/<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></a>
                </td>
            </tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tbody>
    </table></div></div>
</div>
<?php }?>

<div class="card">
    <div class="card-header">Chấm công của tôi (tháng này)</div>
    <div class="card-body p-0"><div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Ngày</th><th>Dự án</th><th>Task</th><th>Ca</th><th>Giờ</th><th>OT</th><th>Mô tả</th><th>TT</th><th></th></tr></thead>
        <tbody>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['my_timesheets']->value, 'ts');
$_smarty_tpl->tpl_vars['ts']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ts']->value) {
$_smarty_tpl->tpl_vars['ts']->do_else = false;
?>
            <tr>
                <td><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['ts']->value['work_date'],"%d/%m/%Y");?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['ts']->value['project_name'];?>
</td><td><?php echo (($tmp = $_smarty_tpl->tpl_vars['ts']->value['task_title'] ?? null)===null||$tmp==='' ? '-' ?? null : $tmp);?>
</td>
                <td><?php if ($_smarty_tpl->tpl_vars['ts']->value['shift'] == 'morning') {?>Sáng<?php } elseif ($_smarty_tpl->tpl_vars['ts']->value['shift'] == 'afternoon') {?>Chiều<?php } elseif ($_smarty_tpl->tpl_vars['ts']->value['shift'] == 'evening') {?>Tối<?php } else { ?>Linh hoạt<?php }?></td>
                <td><strong><?php echo $_smarty_tpl->tpl_vars['ts']->value['hours_worked'];?>
h</strong></td>
                <td><?php if ($_smarty_tpl->tpl_vars['ts']->value['overtime_hours'] > 0) {?><span class="text-warning"><?php echo $_smarty_tpl->tpl_vars['ts']->value['overtime_hours'];?>
h</span><?php } else { ?>-<?php }?></td>
                <td><small><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['ts']->value['description'],40);?>
</small></td>
                <td><span class="badge badge-status badge-<?php echo $_smarty_tpl->tpl_vars['ts']->value['status'];?>
"><?php if ($_smarty_tpl->tpl_vars['ts']->value['status'] == 'pending') {?>Chờ<?php } elseif ($_smarty_tpl->tpl_vars['ts']->value['status'] == 'approved') {?>OK<?php } else { ?>Từ chối<?php }?></span></td>
                <td><?php if ($_smarty_tpl->tpl_vars['ts']->value['status'] == 'pending') {?><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets/delete/<?php echo $_smarty_tpl->tpl_vars['ts']->value['id'];?>
" class="btn btn-xs btn-ghost text-danger" data-confirm="Xóa?"><i class="bi bi-trash"></i></a><?php }?></td>
            </tr>
            <?php
}
if ($_smarty_tpl->tpl_vars['ts']->do_else) {
?>
            <tr><td colspan="9" class="text-center text-muted py-3">Chưa có dữ liệu tháng này</td></tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </tbody>
    </table></div></div>
</div>
<?php
}
}
/* {/block "content"} */
}
