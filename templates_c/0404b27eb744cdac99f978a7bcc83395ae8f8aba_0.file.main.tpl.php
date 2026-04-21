<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:49:03
  from 'D:\xampp\htdocs\ddonf\templates\layout\main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e701bf81ef91_43458481',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0404b27eb744cdac99f978a7bcc83395ae8f8aba' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\layout\\main.tpl',
      1 => 1776745178,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:layout/sidebar.tpl' => 1,
    'file:layout/header.tpl' => 1,
  ),
),false)) {
function content_69e701bf81ef91_43458481 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hệ thống Quản lý Dự Án & Chấm Công - DDONF">
    <meta name="base-url" content="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
">
    <meta name="csrf-token" content="<?php echo $_smarty_tpl->tpl_vars['csrf_token']->value;?>
">
    <title><?php echo (($tmp = $_smarty_tpl->tpl_vars['page_title']->value ?? null)===null||$tmp==='' ? 'Tổng quan' ?? null : $tmp);?>
 | <?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        <?php $_smarty_tpl->_subTemplateRender("file:layout/sidebar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <div class="app-content">
            <?php $_smarty_tpl->_subTemplateRender("file:layout/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
            <main class="main-content">
                <div class="container-fluid px-4 py-4">
                                        <?php if ((isset($_smarty_tpl->tpl_vars['flash']->value)) && $_smarty_tpl->tpl_vars['flash']->value) {?>
                    <div class="alert alert-<?php echo $_smarty_tpl->tpl_vars['flash']->value['type'];?>
 alert-dismissible fade show" role="alert">
                        <?php echo $_smarty_tpl->tpl_vars['flash']->value['message'];?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php }?>
                    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_105065694669e701bf81d447_00658947', "content");
?>

                </div>
            </main>
        </div>
    </div>

    <?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/assets/js/app.js"><?php echo '</script'; ?>
>
    <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_159338658369e701bf81e421_38276630', "scripts");
?>

</body>
</html>
<?php }
/* {block "content"} */
class Block_105065694669e701bf81d447_00658947 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_105065694669e701bf81d447_00658947',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "content"} */
/* {block "scripts"} */
class Block_159338658369e701bf81e421_38276630 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'scripts' => 
  array (
    0 => 'Block_159338658369e701bf81e421_38276630',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block "scripts"} */
}
