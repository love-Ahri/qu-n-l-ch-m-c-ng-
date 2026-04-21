<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:49:03
  from 'D:\xampp\htdocs\ddonf\templates\layout\sidebar.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e701bf993958_18850656',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c9224dd3c7f1f6dc39465d5e6e7f4e47659a320b' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\layout\\sidebar.tpl',
      1 => 1776745186,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e701bf993958_18850656 (Smarty_Internal_Template $_smarty_tpl) {
?><aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-kanban"></i>
        <span>DDONF</span>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'dashboard') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/">
                    <i class="bi bi-speedometer2"></i><span>Tổng quan</span>
                </a>
            </li>

            <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'admin',$_smarty_tpl->tpl_vars['user_roles']->value )) || call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'hr',$_smarty_tpl->tpl_vars['user_roles']->value ))) {?>
            <li class="nav-section">NHÂN SỰ</li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'users') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users">
                    <i class="bi bi-people"></i><span>Người dùng</span>
                </a>
            </li>
            <?php }?>

            <li class="nav-section">DỰ ÁN</li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'projects') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Projects">
                    <i class="bi bi-folder2-open"></i><span>Dự án</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'tasks') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Tasks">
                    <i class="bi bi-list-check"></i><span>Nhiệm vụ</span>
                </a>
            </li>

            <li class="nav-section">CHẤM CÔNG</li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'timesheets') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Timesheets">
                    <i class="bi bi-clock-history"></i><span>Chấm công</span>
                </a>
            </li>

            <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'admin',$_smarty_tpl->tpl_vars['user_roles']->value )) || call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'pm',$_smarty_tpl->tpl_vars['user_roles']->value )) || call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'hr',$_smarty_tpl->tpl_vars['user_roles']->value ))) {?>
            <li class="nav-section">PHÂN TÍCH</li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'resources') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Resources">
                    <i class="bi bi-calendar2-week"></i><span>Phân bổ nguồn lực</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'reports') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Reports">
                    <i class="bi bi-bar-chart-line"></i><span>Báo cáo</span>
                </a>
            </li>
            <?php }?>

            <li class="nav-section">CÔNG CỤ</li>
            <li class="nav-item">
                <a class="nav-link <?php if ($_smarty_tpl->tpl_vars['active_menu']->value == 'excel') {?>active<?php }?>" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Excel">
                    <i class="bi bi-file-earmark-spreadsheet"></i><span>Xuất/Nhập Excel</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<?php }
}
