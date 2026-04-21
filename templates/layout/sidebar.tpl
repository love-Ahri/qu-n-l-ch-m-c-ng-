<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-kanban"></i>
        <span>DDONF</span>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'dashboard'}active{/if}" href="{$base_url}/">
                    <i class="bi bi-speedometer2"></i><span>Tổng quan</span>
                </a>
            </li>

            {if in_array('admin', $user_roles) || in_array('hr', $user_roles)}
            <li class="nav-section">NHÂN SỰ</li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'users'}active{/if}" href="{$base_url}/Users">
                    <i class="bi bi-people"></i><span>Người dùng</span>
                </a>
            </li>
            {/if}

            <li class="nav-section">DỰ ÁN</li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'projects'}active{/if}" href="{$base_url}/Projects">
                    <i class="bi bi-folder2-open"></i><span>Dự án</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'tasks'}active{/if}" href="{$base_url}/Tasks">
                    <i class="bi bi-list-check"></i><span>Nhiệm vụ</span>
                </a>
            </li>

            <li class="nav-section">CHẤM CÔNG</li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'timesheets'}active{/if}" href="{$base_url}/Timesheets">
                    <i class="bi bi-clock-history"></i><span>Chấm công</span>
                </a>
            </li>

            {if in_array('admin', $user_roles) || in_array('pm', $user_roles) || in_array('hr', $user_roles)}
            <li class="nav-section">PHÂN TÍCH</li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'resources'}active{/if}" href="{$base_url}/Resources">
                    <i class="bi bi-calendar2-week"></i><span>Phân bổ nguồn lực</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'reports'}active{/if}" href="{$base_url}/Reports">
                    <i class="bi bi-bar-chart-line"></i><span>Báo cáo</span>
                </a>
            </li>
            {/if}

            <li class="nav-section">CÔNG CỤ</li>
            <li class="nav-item">
                <a class="nav-link {if $active_menu == 'excel'}active{/if}" href="{$base_url}/Excel">
                    <i class="bi bi-file-earmark-spreadsheet"></i><span>Xuất/Nhập Excel</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
