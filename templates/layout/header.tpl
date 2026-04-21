<header class="app-header">
    <div class="header-left">
        <button class="btn-sidebar-toggle" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{$base_url}/">Trang chủ</a></li>
                {if isset($page_title) && $page_title != 'Tổng quan'}
                <li class="breadcrumb-item active">{$page_title}</li>
                {/if}
            </ol>
        </nav>
    </div>
    <div class="header-right">
        <div class="dropdown user-dropdown">
            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    {$current_user.name|mb_substr:0:1|upper}
                </div>
                <span class="d-none d-md-inline">{$current_user.name}</span>
                <i class="bi bi-chevron-down" style="font-size: 12px;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <span class="dropdown-item-text" style="font-size:12px; color:var(--text-muted);">
                        {$current_user.email}
                    </span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{$base_url}/Users/profile"><i class="bi bi-person"></i> Hồ sơ cá nhân</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="{$base_url}/Auth/logout"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header>
