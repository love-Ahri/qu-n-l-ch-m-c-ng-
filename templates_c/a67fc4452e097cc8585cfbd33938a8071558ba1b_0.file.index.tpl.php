<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:52:57
  from 'D:\xampp\htdocs\ddonf\templates\projects\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e702a9b2bd97_50020842',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a67fc4452e097cc8585cfbd33938a8071558ba1b' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\projects\\index.tpl',
      1 => 1776745647,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e702a9b2bd97_50020842 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_89393677469e702a9af16b3_14490504', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_89393677469e702a9af16b3_14490504 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_89393677469e702a9af16b3_14490504',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.number_format.php','function'=>'smarty_modifier_number_format',),));
?>

<div class="page-header">
    <h1><i class="bi bi-folder2-open me-2"></i>Quản lý Dự án</h1>
    <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'admin',$_smarty_tpl->tpl_vars['user_roles']->value )) || call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'pm',$_smarty_tpl->tpl_vars['user_roles']->value ))) {?>
    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Projects/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tạo Dự án</a>
    <?php }?>
</div>
<div class="row g-3">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['projects']->value, 'p');
$_smarty_tpl->tpl_vars['p']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->do_else = false;
?>
    <div class="col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <span class="badge badge-<?php echo $_smarty_tpl->tpl_vars['p']->value['status'];?>
"><?php if ($_smarty_tpl->tpl_vars['p']->value['status'] == 'active') {?>Active<?php } elseif ($_smarty_tpl->tpl_vars['p']->value['status'] == 'planning') {?>Lập kế hoạch<?php } elseif ($_smarty_tpl->tpl_vars['p']->value['status'] == 'completed') {?>Hoàn thành<?php } elseif ($_smarty_tpl->tpl_vars['p']->value['status'] == 'on_hold') {?>Tạm dừng<?php } else { ?>Hủy<?php }?></span>
                    <small class="text-muted"><?php echo $_smarty_tpl->tpl_vars['p']->value['code'];?>
</small>
                </div>
                <h6 class="mb-1"><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Projects/detail/<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['p']->value['name'];?>
</a></h6>
                <p class="text-secondary fs-14 mb-3"><?php echo (($tmp = $_smarty_tpl->tpl_vars['p']->value['client_name'] ?? null)===null||$tmp==='' ? 'Nội bộ' ?? null : $tmp);?>
</p>
                <div class="d-flex gap-3 mb-3" style="font-size:13px;">
                    <span><i class="bi bi-people text-primary me-1"></i><?php echo $_smarty_tpl->tpl_vars['p']->value['member_count'];?>
 TV</span>
                    <span><i class="bi bi-list-check text-warning me-1"></i><?php echo $_smarty_tpl->tpl_vars['p']->value['done_count'];?>
/<?php echo $_smarty_tpl->tpl_vars['p']->value['task_count'];?>
 Task</span>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['p']->value['task_count'] > 0) {?>
                <div class="progress mb-2" style="height:6px;">
                    <div class="progress-bar bg-success" style="width:<?php if ($_smarty_tpl->tpl_vars['p']->value['task_count'] > 0) {
echo smarty_modifier_number_format(($_smarty_tpl->tpl_vars['p']->value['done_count']/$_smarty_tpl->tpl_vars['p']->value['task_count']*100),0);
} else { ?>0<?php }?>%"></div>
                </div>
                <small class="text-muted"><?php if ($_smarty_tpl->tpl_vars['p']->value['task_count'] > 0) {
echo smarty_modifier_number_format(($_smarty_tpl->tpl_vars['p']->value['done_count']/$_smarty_tpl->tpl_vars['p']->value['task_count']*100),0);?>
%<?php } else { ?>0%<?php }?> hoàn thành</small>
                <?php }?>
            </div>
            <div class="card-body pt-0 d-flex gap-1">
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Projects/detail/<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-sm btn-ghost"><i class="bi bi-eye"></i> Xem</a>
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks/kanban/<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-sm btn-ghost"><i class="bi bi-kanban"></i> Kanban</a>
                <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'admin',$_smarty_tpl->tpl_vars['user_roles']->value )) || call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'pm',$_smarty_tpl->tpl_vars['user_roles']->value ))) {?>
                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Projects/edit/<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" class="btn btn-sm btn-ghost"><i class="bi bi-pencil"></i></a>
                <?php }?>
            </div>
        </div>
    </div>
    <?php
}
if ($_smarty_tpl->tpl_vars['p']->do_else) {
?>
    <div class="col-12"><div class="empty-state"><i class="bi bi-folder-x"></i><p>Chưa có dự án nào</p></div></div>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php
}
}
/* {/block "content"} */
}
