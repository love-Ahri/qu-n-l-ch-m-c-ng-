<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:49:14
  from 'D:\xampp\htdocs\ddonf\templates\users\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e701cae10cc2_05540207',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '65382361ee9a40686471c7cd194f49077be38adb' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\users\\index.tpl',
      1 => 1776746739,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e701cae10cc2_05540207 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_140750482169e701caddafc0_53861709', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_140750482169e701caddafc0_53861709 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_140750482169e701caddafc0_53861709',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<div class="page-header">
    <div>
        <h1><i class="bi bi-people me-2"></i>Quản lý Người dùng</h1>
    </div>
    <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'admin',$_smarty_tpl->tpl_vars['user_roles']->value ))) {?>
    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Thêm người dùng</a>
    <?php }?>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users" class="filter-bar">
            <input type="text" name="q" class="form-control" placeholder="Tìm tên, email, SĐT..." value="<?php echo $_smarty_tpl->tpl_vars['filters']->value['q'];?>
">
            <select name="department" class="form-select">
                <option value="">-- Phòng ban --</option>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['departments']->value, 'd');
$_smarty_tpl->tpl_vars['d']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->do_else = false;
?>
                <option value="<?php echo $_smarty_tpl->tpl_vars['d']->value['department'];?>
" <?php if ($_smarty_tpl->tpl_vars['filters']->value['department'] == $_smarty_tpl->tpl_vars['d']->value['department']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['d']->value['department'];?>
</option>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                <option value="1" <?php if ($_smarty_tpl->tpl_vars['filters']->value['status'] === '1') {?>selected<?php }?>>Hoạt động</option>
                <option value="0" <?php if ($_smarty_tpl->tpl_vars['filters']->value['status'] === '0') {?>selected<?php }?>>Khóa</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Tìm</button>
            <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users" class="btn btn-ghost btn-sm">Xóa lọc</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th><th>Họ tên</th><th>Email</th><th>Điện thoại</th>
                        <th>Phòng ban</th><th>Vai trò</th><th>Trạng thái</th><th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['users']->value, 'u');
$_smarty_tpl->tpl_vars['u']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->do_else = false;
?>
                    <tr>
                        <td><?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar" style="width:32px;height:32px;font-size:12px;">
                                    <?php echo mb_strtoupper((string) call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'mb_substr' ][ 0 ], array( $_smarty_tpl->tpl_vars['u']->value['name'],0,1 )) ?? '', 'UTF-8');?>

                                </div>
                                <strong><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</strong>
                            </div>
                        </td>
                        <td><?php echo $_smarty_tpl->tpl_vars['u']->value['email'];?>
</td>
                        <td><?php echo (($tmp = $_smarty_tpl->tpl_vars['u']->value['phone'] ?? null)===null||$tmp==='' ? '-' ?? null : $tmp);?>
</td>
                        <td><?php echo (($tmp = $_smarty_tpl->tpl_vars['u']->value['department'] ?? null)===null||$tmp==='' ? '-' ?? null : $tmp);?>
</td>
                        <td>
                            <?php if ($_smarty_tpl->tpl_vars['u']->value['role_list']) {?>
                                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['u']->value['role_list'], 'rn');
$_smarty_tpl->tpl_vars['rn']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['rn']->value) {
$_smarty_tpl->tpl_vars['rn']->do_else = false;
?>
                                <span class="badge badge-<?php echo $_smarty_tpl->tpl_vars['rn']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['rn']->value;?>
</span>
                                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                            <?php }?>
                        </td>
                        <td>
                            <?php if ($_smarty_tpl->tpl_vars['u']->value['is_active']) {?>
                            <span class="badge badge-status badge-active">Hoạt động</span>
                            <?php } else { ?>
                            <span class="badge badge-status badge-inactive">Khóa</span>
                            <?php }?>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users/edit/<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
" class="btn btn-sm btn-ghost" title="Sửa"><i class="bi bi-pencil"></i></a>
                                <?php if (call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'in_array' ][ 0 ], array( 'admin',$_smarty_tpl->tpl_vars['user_roles']->value ))) {?>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users/toggleActive/<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
" class="btn btn-sm btn-ghost" title="<?php if ($_smarty_tpl->tpl_vars['u']->value['is_active']) {?>Khóa<?php } else { ?>Mở khóa<?php }?>">
                                    <i class="bi bi-<?php if ($_smarty_tpl->tpl_vars['u']->value['is_active']) {?>lock<?php } else { ?>unlock<?php }?>"></i>
                                </a>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users/delete/<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
" class="btn btn-sm btn-ghost text-danger" data-confirm="Xóa người dùng '<?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
'?" title="Xóa"><i class="bi bi-trash"></i></a>
                                <?php }?>
                            </div>
                        </td>
                    </tr>
                    <?php
}
if ($_smarty_tpl->tpl_vars['u']->do_else) {
?>
                    <tr><td colspan="8" class="empty-state"><i class="bi bi-inbox"></i><p>Không có dữ liệu</p></td></tr>
                    <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['pagination']->value['total_pages'] > 1) {?>
    <div class="card-body d-flex justify-content-between align-items-center">
        <small class="text-muted">Hiển thị <?php echo $_smarty_tpl->tpl_vars['pagination']->value['current_page'];?>
/<?php echo $_smarty_tpl->tpl_vars['pagination']->value['total_pages'];?>
 trang (<?php echo $_smarty_tpl->tpl_vars['pagination']->value['total'];?>
 kết quả)</small>
        <nav>
            <ul class="pagination mb-0">
                <li class="page-item <?php if (!$_smarty_tpl->tpl_vars['pagination']->value['has_prev']) {?>disabled<?php }?>"><a class="page-link" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users?page=<?php echo $_smarty_tpl->tpl_vars['pagination']->value['current_page']-1;?>
&q=<?php echo $_smarty_tpl->tpl_vars['filters']->value['q'];?>
&department=<?php echo $_smarty_tpl->tpl_vars['filters']->value['department'];?>
&status=<?php echo $_smarty_tpl->tpl_vars['filters']->value['status'];?>
">‹</a></li>
                <?php
$_smarty_tpl->tpl_vars['p'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);$_smarty_tpl->tpl_vars['p']->step = 1;$_smarty_tpl->tpl_vars['p']->total = (int) ceil(($_smarty_tpl->tpl_vars['p']->step > 0 ? $_smarty_tpl->tpl_vars['pagination']->value['total_pages']+1 - (1) : 1-($_smarty_tpl->tpl_vars['pagination']->value['total_pages'])+1)/abs($_smarty_tpl->tpl_vars['p']->step));
if ($_smarty_tpl->tpl_vars['p']->total > 0) {
for ($_smarty_tpl->tpl_vars['p']->value = 1, $_smarty_tpl->tpl_vars['p']->iteration = 1;$_smarty_tpl->tpl_vars['p']->iteration <= $_smarty_tpl->tpl_vars['p']->total;$_smarty_tpl->tpl_vars['p']->value += $_smarty_tpl->tpl_vars['p']->step, $_smarty_tpl->tpl_vars['p']->iteration++) {
$_smarty_tpl->tpl_vars['p']->first = $_smarty_tpl->tpl_vars['p']->iteration === 1;$_smarty_tpl->tpl_vars['p']->last = $_smarty_tpl->tpl_vars['p']->iteration === $_smarty_tpl->tpl_vars['p']->total;?>
                <li class="page-item <?php if ($_smarty_tpl->tpl_vars['p']->value == $_smarty_tpl->tpl_vars['pagination']->value['current_page']) {?>active<?php }?>"><a class="page-link" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users?page=<?php echo $_smarty_tpl->tpl_vars['p']->value;?>
&q=<?php echo $_smarty_tpl->tpl_vars['filters']->value['q'];?>
&department=<?php echo $_smarty_tpl->tpl_vars['filters']->value['department'];?>
&status=<?php echo $_smarty_tpl->tpl_vars['filters']->value['status'];?>
"><?php echo $_smarty_tpl->tpl_vars['p']->value;?>
</a></li>
                <?php }
}
?>
                <li class="page-item <?php if (!$_smarty_tpl->tpl_vars['pagination']->value['has_next']) {?>disabled<?php }?>"><a class="page-link" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Users?page=<?php echo $_smarty_tpl->tpl_vars['pagination']->value['current_page']+1;?>
&q=<?php echo $_smarty_tpl->tpl_vars['filters']->value['q'];?>
&department=<?php echo $_smarty_tpl->tpl_vars['filters']->value['department'];?>
&status=<?php echo $_smarty_tpl->tpl_vars['filters']->value['status'];?>
">›</a></li>
            </ul>
        </nav>
    </div>
    <?php }?>
</div>
<?php
}
}
/* {/block "content"} */
}
