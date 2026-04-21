/**
 * DDONF - Global JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // ===== SIDEBAR TOGGLE =====
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarOverlay?.classList.toggle('show');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // ===== AUTO-DISMISS ALERTS =====
    document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
        setTimeout(function() {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    // ===== CONFIRM DELETE =====
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || 'Bạn có chắc chắn muốn thực hiện?')) {
                e.preventDefault();
            }
        });
    });

    // ===== KANBAN DRAG & DROP =====
    initKanban();

    // ===== TOOLTIPS =====
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(el) {
        return new bootstrap.Tooltip(el);
    });
});

// ===== KANBAN FUNCTIONS =====
function initKanban() {
    const cards = document.querySelectorAll('.kanban-card');
    const columns = document.querySelectorAll('.kanban-cards');

    if (cards.length === 0) return;

    cards.forEach(card => {
        card.setAttribute('draggable', 'true');

        card.addEventListener('dragstart', function(e) {
            e.dataTransfer.setData('text/plain', this.dataset.taskId);
            this.classList.add('dragging');
            setTimeout(() => this.style.opacity = '0.4', 0);
        });

        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            this.style.opacity = '1';
            columns.forEach(col => col.classList.remove('drag-over'));
        });
    });

    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        column.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });

        column.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            const taskId = e.dataTransfer.getData('text/plain');
            const card = document.querySelector(`[data-task-id="${taskId}"]`);
            const newStatus = this.dataset.status;

            if (card && newStatus) {
                this.appendChild(card);
                updateTaskStatus(taskId, newStatus);
                updateColumnCounts();
            }
        });
    });
}

function updateTaskStatus(taskId, newStatus) {
    const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
    fetch(`${baseUrl}/Tasks/updateStatus`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `task_id=${taskId}&status=${newStatus}&csrf_token=${getCSRFToken()}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('success', 'Cập nhật trạng thái thành công');
        } else {
            showToast('error', data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(() => showToast('error', 'Lỗi kết nối server'));
}

function updateColumnCounts() {
    document.querySelectorAll('.kanban-column').forEach(col => {
        const cards = col.querySelector('.kanban-cards');
        const count = col.querySelector('.count');
        if (cards && count) {
            count.textContent = cards.children.length;
        }
    });
}

// ===== TOAST NOTIFICATION =====
function showToast(type, message, duration = 4000) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };

    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <i class="bi ${icons[type] || icons.info}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:var(--text-secondary);margin-left:auto;cursor:pointer;">
            <i class="bi bi-x"></i>
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ===== CSRF TOKEN =====
function getCSRFToken() {
    return document.querySelector('input[name="csrf_token"]')?.value
        || document.querySelector('meta[name="csrf-token"]')?.content
        || '';
}

// ===== FORMAT NUMBERS =====
function formatNumber(num) {
    return new Intl.NumberFormat('vi-VN').format(num);
}

function formatCurrency(num) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(num);
}

// ===== DYNAMIC PROJECT/TASK SELECTS =====
function loadTasksByProject(projectId, taskSelect) {
    if (!projectId) {
        taskSelect.innerHTML = '<option value="">-- Chọn task --</option>';
        return;
    }

    const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
    fetch(`${baseUrl}/Tasks/getByProject/${projectId}`)
        .then(res => res.json())
        .then(data => {
            taskSelect.innerHTML = '<option value="">-- Chọn task --</option>';
            data.forEach(task => {
                taskSelect.innerHTML += `<option value="${task.id}">${task.title}</option>`;
            });
        });
}
