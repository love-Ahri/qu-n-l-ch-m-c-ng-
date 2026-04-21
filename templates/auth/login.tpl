<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | {$app_name}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{$base_url}/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="login-page">
        <div class="login-card">
            <div class="login-brand">
                <i class="bi bi-kanban"></i>
                <h1>{$app_name}</h1>
                <p>Đăng nhập để tiếp tục</p>
            </div>

            {if $error}
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-2"></i>{$error}
            </div>
            {/if}

            <form method="POST" action="{$base_url}/Auth/doLogin" id="loginForm">
                <input type="hidden" name="csrf_token" value="{$csrf_token}">

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

    <script>
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
    </script>
</body>
</html>
