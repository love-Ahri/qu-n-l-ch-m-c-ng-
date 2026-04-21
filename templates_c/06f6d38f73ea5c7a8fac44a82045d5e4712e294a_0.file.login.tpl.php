<?php
/* Smarty version 4.5.6, created on 2026-04-21 11:50:19
  from 'D:\xampp\htdocs\ddonf\templates\auth\login.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.6',
  'unifunc' => 'content_69e7020b967f01_90485230',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '06f6d38f73ea5c7a8fac44a82045d5e4712e294a' => 
    array (
      0 => 'D:\\xampp\\htdocs\\ddonf\\templates\\auth\\login.tpl',
      1 => 1776745219,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_69e7020b967f01_90485230 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | <?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-brand">
                <i class="bi bi-kanban"></i>
                <h1><?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
</h1>
                <p>Đăng nhập để tiếp tục</p>
            </div>

            <?php if ($_smarty_tpl->tpl_vars['error']->value) {?>
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i><?php echo $_smarty_tpl->tpl_vars['error']->value;?>

            </div>
            <?php }?>

            <form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
/Auth/doLogin" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_smarty_tpl->tpl_vars['csrf_token']->value;?>
">

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" 
                               placeholder="your@email.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" 
                               placeholder="Nhập mật khẩu" required>
                        <button class="btn btn-ghost" type="button" onclick="togglePassword()">
                            <i class="bi bi-eye" id="togglePwdIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">
                    Demo: admin@demo.com / pm@demo.com / hr@demo.com / staff@demo.com<br>
                    Mật khẩu: <strong>password</strong>
                </small>
            </div>
        </div>
    </div>

    <?php echo '<script'; ?>
>
    function togglePassword() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('togglePwdIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pwd.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
    <?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
