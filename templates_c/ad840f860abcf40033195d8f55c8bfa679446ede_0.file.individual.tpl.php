<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:53:39
  from 'D:\xampp\htdocs\ddonf\templates\reports\individual.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e702d324d213_08147128',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ad840f860abcf40033195d8f55c8bfa679446ede' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\reports\\individual.tpl',
      1 => 1776745822,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e702d324d213_08147128 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_176197259369e702d322ec55_29033196', "content");
?>

<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_19629081469e702d324a422_90096261', "scripts");
?>

<?php $_smarty_tpl->inheritance->endChild($_smarty_tpl, "layout/main.tpl");
}
/* {block "content"} */
class Block_176197259369e702d322ec55_29033196 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_176197259369e702d322ec55_29033196',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'D:\\xampp\\htdocs\\ddonf\\vendor\\smarty\\smarty\\libs\\plugins\\modifier.number_format.php','function'=>'smarty_modifier_number_format',),));
?>

<div class="page-header"><h1><i class="bi bi-person-lines-fill me-2"></i>Báo cáo Cá nhân</h1></div>
<ul class="nav nav-tabs mb-4">
    <li class="nav-item"><a class="nav-link active" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Reports/individual">Cá nhân</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Reports/team">Nhóm</a></li>
    <li class="nav-item"><a class="nav-link" href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Reports/cost">Chi phí</a></li>
</ul>
<form method="GET" class="filter-bar mb-4">
    <select name="user_id" class="form-select">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['all_users']->value, 'u');
$_smarty_tpl->tpl_vars['u']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
$_smarty_tpl->tpl_vars['u']->do_else = false;
?><option value="<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['u']->value['id'] == $_smarty_tpl->tpl_vars['filters']->value['user_id']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</option><?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </select>
    <input type="date" name="start_date" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['filters']->value['start_date'];?>
">
    <input type="date" name="end_date" class="form-control" value="<?php echo $_smarty_tpl->tpl_vars['filters']->value['end_date'];?>
">
    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Xem</button>
</form>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="stat-card primary"><div class="stat-value"><?php echo smarty_modifier_number_format($_smarty_tpl->tpl_vars['report']->value['summary']['total_hours'],1);?>
h</div><div class="stat-label">Tổng giờ làm</div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card warning"><div class="stat-value"><?php echo smarty_modifier_number_format($_smarty_tpl->tpl_vars['report']->value['summary']['total_ot'],1);?>
h</div><div class="stat-label">Giờ OT</div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card success"><div class="stat-value"><?php echo $_smarty_tpl->tpl_vars['report']->value['summary']['working_days'];?>
</div><div class="stat-label">Ngày làm việc</div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="stat-card info"><div class="stat-value"><?php echo (($tmp = $_smarty_tpl->tpl_vars['report']->value['tasks']['done_tasks'] ?? null)===null||$tmp==='' ? 0 ?? null : $tmp);?>
/<?php echo (($tmp = $_smarty_tpl->tpl_vars['report']->value['tasks']['total_tasks'] ?? null)===null||$tmp==='' ? 0 ?? null : $tmp);?>
</div><div class="stat-label">Task hoàn thành</div></div></div>
</div>
<div class="row g-4">
    <div class="col-lg-8"><div class="card"><div class="card-header">Giờ làm theo tuần</div><div class="card-body"><div class="chart-container"><canvas id="weeklyChart"></canvas></div></div></div></div>
    <div class="col-lg-4"><div class="card"><div class="card-header">Theo dự án</div><div class="card-body p-0">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['report']->value['by_project'], 'bp');
$_smarty_tpl->tpl_vars['bp']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['bp']->value) {
$_smarty_tpl->tpl_vars['bp']->do_else = false;
?>
        <div class="d-flex justify-content-between px-3 py-2" style="border-bottom:1px solid var(--border-color);font-size:13px;">
            <span><?php echo $_smarty_tpl->tpl_vars['bp']->value['project_name'];?>
</span><strong><?php echo smarty_modifier_number_format($_smarty_tpl->tpl_vars['bp']->value['hours'],1);?>
h</strong>
        </div>
        <?php
}
if ($_smarty_tpl->tpl_vars['bp']->do_else) {
?>
        <div class="text-center text-muted py-3">Không có dữ liệu</div>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div></div></div>
</div>
<?php
}
}
/* {/block "content"} */
/* {block "scripts"} */
class Block_19629081469e702d324a422_90096261 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'scripts' => 
  array (
    0 => 'Block_19629081469e702d324a422_90096261',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>

<?php echo '<script'; ?>
>
const weeklyData = <?php echo call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'json_encode' ][ 0 ], array( $_smarty_tpl->tpl_vars['report']->value['weekly'] ));?>
;
if (weeklyData.length > 0 && document.getElementById('weeklyChart')) {
    new Chart(document.getElementById('weeklyChart'), {
        type: 'line',
        data: {
            labels: weeklyData.map(w => w.week_start ? w.week_start.substring(5) : ''),
            datasets: [{
                label: 'Giờ làm',
                data: weeklyData.map(w => parseFloat(w.hours)),
                borderColor: '#667eea', backgroundColor: 'rgba(102,126,234,0.1)',
                fill: true, tension: 0.4, borderWidth: 2
            },{
                label: 'OT',
                data: weeklyData.map(w => parseFloat(w.ot)),
                borderColor: '#fa709a', backgroundColor: 'rgba(250,112,154,0.1)',
                fill: true, tension: 0.4, borderWidth: 2
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { color: '#8b8fa3' } } },
            scales: {
                x: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } },
                y: { ticks: { color: '#8b8fa3' }, grid: { color: 'rgba(255,255,255,0.04)' } }
            }
        }
    });
}
<?php echo '</script'; ?>
>
<?php
}
}
/* {/block "scripts"} */
}
