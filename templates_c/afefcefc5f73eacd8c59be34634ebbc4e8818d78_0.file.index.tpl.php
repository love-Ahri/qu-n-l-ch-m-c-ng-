<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:53:48
  from 'D:\xampp\htdocs\ddonf\templates\excel\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e702dc10c882_58147595',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'afefcefc5f73eacd8c59be34634ebbc4e8818d78' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\excel\\index.tpl',
      1 => 1776746006,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e702dc10c882_58147595 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_105731846169e702dc0f7ce9_39534186', "content");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_105731846169e702dc0f7ce9_39534186 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_105731846169e702dc0f7ce9_39534186',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.date_format.php','function'=>'smarty_modifier_date_format',),));
?>

<div class="page-header"><h1><i class="bi bi-file-earmark-spreadsheet me-2"></i>Xuất/Nhập Excel</h1></div>
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-download me-2"></i>Xuất dữ liệu</div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Excel/exportUsers" class="btn btn-outline-primary">
                        <i class="bi bi-people"></i> Xuất Danh sách Nhân sự
                    </a>
                    <div class="card" style="background:var(--bg-input);border-color:var(--border-color);">
                        <div class="card-body">
                            <h6 class="mb-3">Xuất Chấm công</h6>
                            <form method="GET" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Excel/exportTimesheets">
                                <div class="row g-2">
                                    <div class="col-6"><label class="form-label">Từ ngày</label><input type="date" name="start_date" class="form-control" value="<?php echo smarty_modifier_date_format(time(),'%Y-%m-01');?>
"></div>
                                    <div class="col-6"><label class="form-label">Đến ngày</label><input type="date" name="end_date" class="form-control" value="<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d');?>
"></div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 w-100"><i class="bi bi-download"></i> Xuất Excel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-upload me-2"></i>Nhập dữ liệu</div>
            <div class="card-body">
                <form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Excel/importUsers" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo $_smarty_tpl->tpl_vars['csrf_token']->value;?>
">
                    <h6 class="mb-3">Nhập Danh sách Nhân sự</h6>
                    <div class="alert alert-info" style="font-size:13px;">
                        <strong>Format:</strong> File .xlsx với các cột: Họ tên, Email, Điện thoại, Phòng ban, Vai trò (admin/pm/hr/staff)<br>
                        Mật khẩu mặc định: <code>password</code>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-upload"></i> Nhập Excel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}
}
/* {/block "content"} */
}
