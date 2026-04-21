<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:53:29
  from 'D:\xampp\htdocs\ddonf\templates\resources\allocation.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e702c90f08d9_90787455',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd57536c11b67f7cea5e4df034db78a6b92f3ff6e' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\resources\\allocation.tpl',
      1 => 1776746387,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e702c90f08d9_90787455 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_204562099269e702c90b7f45_79674325', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_204562099269e702c90b7f45_79674325 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_204562099269e702c90b7f45_79674325',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),1=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.count.php','function'=>'smarty_modifier_count',),2=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.number_format.php','function'=>'smarty_modifier_number_format',),));
?>

<div class="page-header">
    <h1><i class="bi bi-calendar2-week me-2"></i>Phân bổ Nguồn lực</h1>
    <div class="d-flex gap-2">
        <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Resources/allocation?week=<?php echo $_smarty_tpl->tpl_vars['prev_week']->value;?>
" class="btn btn-ghost"><i class="bi bi-chevron-left"></i> Tuần trước</a>
        <span class="btn btn-ghost disabled"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['week_start']->value,"%d/%m");?>
 - <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['data']->value['week_end'],"%d/%m/%Y");?>
</span>
        <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Resources/allocation?week=<?php echo $_smarty_tpl->tpl_vars['next_week']->value;?>
" class="btn btn-ghost">Tuần sau <i class="bi bi-chevron-right"></i></a>
    </div>
</div>

<?php if (smarty_modifier_count($_smarty_tpl->tpl_vars['alerts']->value) > 0) {
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['alerts']->value, 'alert');
$_smarty_tpl->tpl_vars['alert']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['alert']->value) {
$_smarty_tpl->tpl_vars['alert']->do_else = false;
?>
<div class="overbooking-alert">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><?php echo $_smarty_tpl->tpl_vars['alert']->value['message'];?>
</span>
</div>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}?>

<div class="card">
    <div class="card-body p-0" style="overflow-x:auto;">
        <table class="allocation-table">
            <thead>
                <tr>
                    <th style="min-width:160px; text-align:left; position:sticky; left:0; background:var(--bg-card); z-index:11;">Nhân viên</th>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['days'], 'day');
$_smarty_tpl->tpl_vars['day']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->do_else = false;
?>
                    <th class="allocation-cell">
                        <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%a");?>
<br>
                        <small><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['day']->value,"%d/%m");?>
</small>
                    </th>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <th style="min-width:80px;">Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['matrix'], 'row');
$_smarty_tpl->tpl_vars['row']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->do_else = false;
?>
                <tr>
                    <td style="text-align:left; position:sticky; left:0; background:var(--bg-card); z-index:10;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:28px;height:28px;font-size:11px;"><?php echo mb_strtoupper((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'mb_substr' ][ 0 ], array( $_smarty_tpl->tpl_vars['row']->value['user']['name'],0,1 )) ?? '', 'UTF-8');?>
</div>
                            <div><strong style="font-size:13px;"><?php echo $_smarty_tpl->tpl_vars['row']->value['user']['name'];?>
</strong><br><small class="text-muted"><?php echo (($tmp = $_smarty_tpl->tpl_vars['row']->value['user']['department'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</small></div>
                        </div>
                    </td>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['data']->value['days'], 'day');
$_smarty_tpl->tpl_vars['day']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->do_else = false;
?>
                    <td class="allocation-cell <?php echo $_smarty_tpl->tpl_vars['row']->value['days'][$_smarty_tpl->tpl_vars['day']->value]['status'];?>
">
                        <?php if ($_smarty_tpl->tpl_vars['row']->value['days'][$_smarty_tpl->tpl_vars['day']->value]['hours'] > 0) {?>
                        <span class="allocation-hours <?php echo $_smarty_tpl->tpl_vars['row']->value['days'][$_smarty_tpl->tpl_vars['day']->value]['status'];?>
"><?php echo smarty_modifier_number_format($_smarty_tpl->tpl_vars['row']->value['days'][$_smarty_tpl->tpl_vars['day']->value]['hours'],1);?>
</span>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['row']->value['days'][$_smarty_tpl->tpl_vars['day']->value]['entries'], 'e');
$_smarty_tpl->tpl_vars['e']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['e']->value) {
$_smarty_tpl->tpl_vars['e']->do_else = false;
?>
                        <div class="allocation-task" title="<?php echo $_smarty_tpl->tpl_vars['e']->value['project_name'];?>
: <?php echo (($tmp = $_smarty_tpl->tpl_vars['e']->value['task_title'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
"><?php echo (($tmp = $_smarty_tpl->tpl_vars['e']->value['project_code'] ?? null)===null||$tmp==='' ? '' ?? null : $tmp);?>
</div>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        <?php } else { ?>
                        <span class="text-muted">-</span>
                        <?php }?>
                    </td>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    <td>
                        <span class="allocation-hours <?php echo $_smarty_tpl->tpl_vars['row']->value['week_status'];?>
" style="font-size:18px;"><?php echo smarty_modifier_number_format($_smarty_tpl->tpl_vars['row']->value['total_hours'],1);?>
</span>
                        <?php if ($_smarty_tpl->tpl_vars['row']->value['is_overbooked']) {?><br><span class="badge badge-urgent" style="font-size:9px;">QUÁ TẢI</span><?php }?>
                    </td>
                </tr>
                <?php
}
if ($_smarty_tpl->tpl_vars['row']->do_else) {
?>
                <tr><td colspan="<?php echo smarty_modifier_count($_smarty_tpl->tpl_vars['data']->value['days'])+2;?>
" class="text-center text-muted py-4">Không có dữ liệu tuần này</td></tr>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
}
/* {/block "content"} */
}
