import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    // ===== THEME TOGGLE =====
    const themeBtn = document.getElementById('theme-toggle');

    if (themeBtn) {
        const stored = localStorage.getItem('theme');
        if (stored === 'dark') {
            body.classList.remove('theme-light');
            body.classList.add('theme-dark');
        } else {
            body.classList.remove('theme-dark');
            body.classList.add('theme-light');
        }

        const updateIcon = () => {
            const isDark = body.classList.contains('theme-dark');
            themeBtn.textContent = isDark ? '🌙' : '☀️';
        };

        updateIcon();

        themeBtn.addEventListener('click', () => {
            const isDark = body.classList.toggle('theme-dark');
            body.classList.toggle('theme-light', !isDark);
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateIcon();
        });
    }

    // ===== MOBILE MENU TOGGLE =====
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });

        menu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                menu.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }
});
