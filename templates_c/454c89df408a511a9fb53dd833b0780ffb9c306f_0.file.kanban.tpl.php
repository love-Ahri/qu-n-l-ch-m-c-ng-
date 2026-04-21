<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:49:23
  from 'D:\xampp\htdocs\ddonf\templates\tasks\kanban.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e701d32e0d64_94395425',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '454c89df408a511a9fb53dd833b0780ffb9c306f' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\tasks\\kanban.tpl',
      1 => 1776746904,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e701d32e0d64_94395425 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_136536963569e701d32a57c3_42777943', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_136536963569e701d32a57c3_42777943 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_136536963569e701d32a57c3_42777943',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.count.php','function'=>'smarty_modifier_count',),1=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),2=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.number_format.php','function'=>'smarty_modifier_number_format',),));
?>

<div class="page-header">
    <div class="d-flex align-items-center gap-3">
        <h1><i class="bi bi-kanban me-2"></i>Kanban Board</h1>
        <select class="form-select" style="max-width:250px;" onchange="location='<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks/kanban/'+this.value">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['projects']->value, 'p');
$_smarty_tpl->tpl_vars['p']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
$_smarty_tpl->tpl_vars['p']->do_else = false;
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['p']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['p']->value['id'] == $_smarty_tpl->tpl_vars['project_id']->value) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['p']->value['code'];?>
 - <?php echo $_smarty_tpl->tpl_vars['p']->value['name'];?>
</option>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['project']->value) {?>
    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks/create/<?php echo $_smarty_tpl->tpl_vars['project_id']->value;?>
" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm Task</a>
    <?php }?>
</div>

<?php if ($_smarty_tpl->tpl_vars['project']->value) {?>
<div class="kanban-board">
    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, array('todo'=>'To-Do','doing'=>'Đang làm','review'=>'Review','done'=>'Hoàn thành'), 'label', false, 'status');
$_smarty_tpl->tpl_vars['label']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['status']->value => $_smarty_tpl->tpl_vars['label']->value) {
$_smarty_tpl->tpl_vars['label']->do_else = false;
?>
    <div class="kanban-column <?php echo $_smarty_tpl->tpl_vars['status']->value;?>
">
        <div class="kanban-column-header">
            <span><?php echo $_smarty_tpl->tpl_vars['label']->value;?>
</span>
            <span class="count"><?php echo (($tmp = smarty_modifier_count($_smarty_tpl->tpl_vars['kanban']->value[$_smarty_tpl->tpl_vars['status']->value]) ?? null)===null||$tmp==='' ? 0 ?? null : $tmp);?>
</span>
        </div>
        <div class="kanban-cards" data-status="<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
">
            <?php if ((isset($_smarty_tpl->tpl_vars['kanban']->value[$_smarty_tpl->tpl_vars['status']->value]))) {?>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['kanban']->value[$_smarty_tpl->tpl_vars['status']->value], 'task');
$_smarty_tpl->tpl_vars['task']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['task']->value) {
$_smarty_tpl->tpl_vars['task']->do_else = false;
?>
            <div class="kanban-card" data-task-id="<?php echo $_smarty_tpl->tpl_vars['task']->value['id'];?>
" draggable="true">
                <div class="d-flex align-items-start justify-content-between mb-1">
                    <span class="badge badge-<?php echo $_smarty_tpl->tpl_vars['task']->value['priority'];?>
" style="font-size:10px;"><?php echo mb_strtoupper((string) $_smarty_tpl->tpl_vars['task']->value['priority'] ?? '', 'UTF-8');?>
</span>
                    <div class="dropdown">
                        <button class="btn btn-xs btn-ghost" data-bs-toggle="dropdown" style="padding:0 4px;"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks/edit/<?php echo $_smarty_tpl->tpl_vars['task']->value['id'];?>
"><i class="bi bi-pencil"></i> Sửa</a></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks/delete/<?php echo $_smarty_tpl->tpl_vars['task']->value['id'];?>
" data-confirm="Xóa task này?"><i class="bi bi-trash"></i> Xóa</a></li>
                        </ul>
                    </div>
                </div>
                <div class="kanban-card-title"><?php echo $_smarty_tpl->tpl_vars['task']->value['title'];?>
</div>
                <div class="kanban-card-meta">
                    <div class="kanban-card-assignee">
                        <?php if ($_smarty_tpl->tpl_vars['task']->value['assignee_name']) {?>
                        <span class="avatar-xs"><?php echo mb_strtoupper((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'mb_substr' ][ 0 ], array( $_smarty_tpl->tpl_vars['task']->value['assignee_name'],0,1 )) ?? '', 'UTF-8');?>
</span>
                        <span><?php echo $_smarty_tpl->tpl_vars['task']->value['assignee_name'];?>
</span>
                        <?php } else { ?>
                        <span class="text-muted">Chưa giao</span>
                        <?php }?>
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['task']->value['due_date']) {?>
                    <span <?php if ($_smarty_tpl->tpl_vars['task']->value['due_date'] < smarty_modifier_date_format(time(),'%Y-%m-%d') && $_smarty_tpl->tpl_vars['task']->value['status'] != 'done') {?>class="text-danger"<?php }?>>
                        <i class="bi bi-calendar-event"></i> <?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['task']->value['due_date'],"%d/%m");?>

                    </span>
                    <?php }?>
                </div>
                <?php if ($_smarty_tpl->tpl_vars['task']->value['estimated_hours'] > 0) {?>
                <div class="mt-2">
                    <div class="progress" style="height:4px;">
                        <div class="progress-bar" style="width:<?php if ($_smarty_tpl->tpl_vars['task']->value['estimated_hours'] > 0) {
echo smarty_modifier_number_format(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'min' ][ 0 ], array( ($_smarty_tpl->tpl_vars['task']->value['actual_hours']/$_smarty_tpl->tpl_vars['task']->value['estimated_hours']*100),100 )),0);
} else { ?>0<?php }?>%"></div>
                    </div>
                    <small class="text-muted" style="font-size:10px;"><?php echo smarty_modifier_number_format($_smarty_tpl->tpl_vars['task']->value['actual_hours'],1);?>
h / <?php echo $_smarty_tpl->tpl_vars['task']->value['estimated_hours'];?>
h</small>
                </div>
                <?php }?>
            </div>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            <?php }?>
        </div>
    </div>
    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
</div>
<?php } else { ?>
<div class="empty-state"><i class="bi bi-kanban"></i><p>Chọn một dự án để xem Kanban board</p></div>
<?php }
}
}
/* {/block "content"} */
}
