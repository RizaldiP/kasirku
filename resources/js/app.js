// Navbar mobile toggle (delegation)
document.addEventListener('click', function (e) {
    var toggle = e.target.closest('#navbarToggle');
    if (toggle) {
        document.getElementById('navbarNav')?.classList.toggle('hidden');
    }
});

// Dropdown toggle (delegation)
document.addEventListener('click', function (e) {
    var toggle = e.target.closest('.dropdown-toggle');
    if (toggle) {
        e.stopPropagation();
        var menu = toggle.closest('.dropdown')?.querySelector('.dropdown-menu');
        if (menu) menu.classList.toggle('hidden');
    }
});

// Dropdown close on outside click
document.addEventListener('click', function (e) {
    document.querySelectorAll('.dropdown-menu').forEach(function (menu) {
        if (!menu.classList.contains('hidden')) {
            var toggle = menu.closest('.dropdown')?.querySelector('.dropdown-toggle');
            if (toggle && !toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        }
    });
});

// Notification dropdown toggle
document.addEventListener('click', function (e) {
    var toggle = e.target.closest('.notification-toggle');
    if (toggle) {
        e.stopPropagation();
        var menu = toggle.closest('.notification-dropdown')?.querySelector('.notification-menu');
        if (menu) menu.classList.toggle('hidden');
    }
});

// Notification dropdown close on outside click
document.addEventListener('click', function (e) {
    document.querySelectorAll('.notification-menu').forEach(function (menu) {
        if (!menu.classList.contains('hidden')) {
            var toggle = menu.closest('.notification-dropdown')?.querySelector('.notification-toggle');
            if (toggle && !toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        }
    });
});

// Alert dismiss
document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-dismiss="alert"]');
    if (btn) {
        var alert = btn.closest('.alert');
        if (alert) alert.remove();
    }
});

// Modal overlay close
document.addEventListener('click', function (e) {
    var overlay = e.target.closest('.modal-overlay');
    if (overlay && e.target === overlay) {
        closeModal(overlay.id);
    }
});

// Modal close button
document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-modal-close]');
    if (btn) {
        closeModal(btn.dataset.modalClose);
    }
});
