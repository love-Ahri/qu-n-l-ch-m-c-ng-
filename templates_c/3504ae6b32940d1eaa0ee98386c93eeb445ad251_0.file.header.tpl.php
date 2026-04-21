<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:49:03
  from 'D:\xampp\htdocs\ddonf\templates\layout\header.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e701bfb52987_40813217',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3504ae6b32940d1eaa0ee98386c93eeb445ad251' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\layout\\header.tpl',
      1 => 1776746387,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e701bfb52987_40813217 (Smarty_Internal_Template $_smarty_tpl) {
?><header class="app-header">
    <div class="header-left">
        <button class="btn-sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/">Trang chủ</a></li>
                <?php if ((isset($_smarty_tpl->tpl_vars['page_title']->value)) && $_smarty_tpl->tpl_vars['page_title']->value != 'Tổng quan') {?>
                <li class="breadcrumb-item active"><?php echo $_smarty_tpl->tpl_vars['page_title']->value;?>
</li>
                <?php }?>
            </ol>
        </nav>
    </div>
    <div class="header-right">
        <div class="dropdown user-dropdown">
            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    <?php echo mb_strtoupper((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'mb_substr' ][ 0 ], array( $_smarty_tpl->tpl_vars['current_user']->value['name'],0,1 )) ?? '', 'UTF-8');?>

                </div>
                <span class="d-none d-md-inline"><?php echo $_smarty_tpl->tpl_vars['current_user']->value['name'];?>
</span>
                <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <span class="dropdown-item-text" style="font-size:12px; color:var(--text-muted);">
                        <?php echo $_smarty_tpl->tpl_vars['current_user']->value['email'];?>

                    </span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users/profile"><i class="bi bi-person"></i> Hồ sơ cá nhân</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Auth/logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>
<?php }
}
