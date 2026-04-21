<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hệ thống Quản lý Dự Án & Chấm Công - DDONF">
    <meta name="base-url" content="{$base_url}">
    <meta name="csrf-token" content="{$csrf_token}">
    <title>{$page_title|default:'Tổng quan'} | {$app_name}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{$base_url}/assets/css/custom.css" rel="stylesheet">
</head>
<body>
    <div class="app-wrapper">
        {include file="layout/sidebar.tpl"}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <div class="app-content">
            {include file="layout/header.tpl"}
            <main class="main-content">
                <div class="container-fluid px-4 py-4">
                    {* Flash messages *}
                    {if isset($flash) && $flash}
                    <div class="alert alert-{$flash.type} alert-dismissible fade show" role="alert">
                        {$flash.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    {/if}
                    {block name="content"}{/block}
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{$base_url}/assets/js/app.js"></script>
    {block name="scripts"}{/block}
</body>
</html>
