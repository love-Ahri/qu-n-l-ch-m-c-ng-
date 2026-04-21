<?php
/* Smarty version 4.5.6, created on 2026-04-21 13:54:58
  from 'D:\xampp\htdocs\ddonf\templates\layout\error.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e71f42018340_74997526',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '53949097a7e1decbe764ce21862e6982f0921f63' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\layout\\error.tpl',
      1 => 1776745191,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e71f42018340_74997526 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>


<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_151627392369e71f41dc5e54_44633276', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_151627392369e71f41dc5e54_44633276 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_151627392369e71f41dc5e54_44633276',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-gradient"><?php echo $_smarty_tpl->tpl_vars['error_code']->value;?>
</h1>
    <p class="fs-5 text-secondary mb-4"><?php echo (($tmp = $_smarty_tpl->tpl_vars['error_message']->value ?? null)===null||$tmp==='' ? 'Đã xảy ra lỗi.' ?? null : $tmp);?>
</p>
    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/" class="btn btn-primary">
        <i class="bi bi-house"></i> Quay lại Trang chủ
    </a>
</div>
<?php
}
}
/* {/block "content"} */
}
