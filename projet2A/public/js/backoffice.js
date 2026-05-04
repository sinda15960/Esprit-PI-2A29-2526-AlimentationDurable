// ==================== NOTIFICATIONS ====================
let notifications = [];

function loadNotifications() {
    const saved = localStorage.getItem('nutriflow_notifications');
    if (saved) {
        notifications = JSON.parse(saved);
    } else {
        notifications = [];
    }
    updateNotificationBadge();
    renderNotifications();
}

function saveNotifications() {
    localStorage.setItem('nutriflow_notifications', JSON.stringify(notifications));
    updateNotificationBadge();
}

function updateNotificationBadge() {
    const unreadCount = notifications.filter(n => !n.read).length;
    const badge = document.getElementById('notificationCount');
    if (badge) {
        badge.textContent = unreadCount;
        badge.style.display = unreadCount > 0 ? 'flex' : 'none';
    }
}

function renderNotifications() {
    const list = document.getElementById('notificationList');
    if (!list) return;
    
    if (notifications.length === 0) {
        list.innerHTML = '<div class="notification-empty">📭 Aucune notification</div>';
        return;
    }
    
    list.innerHTML = notifications.map(notif => `
        <div class="notification-item ${notif.read ? '' : 'unread'}" data-id="${notif.id}" onclick="markAsRead(${notif.id})">
            <div class="notification-icon ${notif.type}">
                <i class="${notif.icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${notif.title}</div>
                <div class="notification-message">${notif.message}</div>
                <div class="notification-time">${notif.time}</div>
            </div>
            <div class="notification-delete">
                <button onclick="event.stopPropagation(); deleteNotification(${notif.id})">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function addNotification(title, message, type = 'info', icon = 'fas fa-info-circle') {
    const newNotif = {
        id: Date.now(),
        title: title,
        message: message,
        type: type,
        icon: icon,
        time: new Date().toLocaleString('fr-FR'),
        read: false
    };
    notifications.unshift(newNotif);
    if (notifications.length > 50) notifications = notifications.slice(0, 50);
    saveNotifications();
    renderNotifications();
    showToast(title, message);
}

function markAsRead(id) {
    const notif = notifications.find(n => n.id === id);
    if (notif && !notif.read) {
        notif.read = true;
        saveNotifications();
        renderNotifications();
        updateNotificationBadge();
    }
}

function deleteNotification(id) {
    notifications = notifications.filter(n => n.id !== id);
    saveNotifications();
    renderNotifications();
}

function clearAllNotifications() {
    if (confirm('Supprimer toutes les notifications ?')) {
        notifications = [];
        saveNotifications();
        renderNotifications();
        updateNotificationBadge();
    }
}

function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        dropdown.classList.toggle('show');
    }
}

function showToast(title, message) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <div class="toast-icon">🔔</div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">×</button>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Fermer le dropdown en cliquant ailleurs
document.addEventListener('click', function(e) {
    const badge = document.querySelector('.notification-badge');
    const dropdown = document.getElementById('notificationDropdown');
    if (badge && dropdown && !badge.contains(e.target)) {
        dropdown.classList.remove('show');
    }
});

// Charger les notifications au démarrage
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
});