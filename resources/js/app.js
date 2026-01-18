import './bootstrap';

// vytvorene s pomocou generativnej AI
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

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('cottage-filters');
    const target = document.getElementById('cottages-list');

    if (!form || !target) return;

    const run = async () => {
        const params = new URLSearchParams(new FormData(form));
        const res = await fetch(`/chaty/filter?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!res.ok) return;

        target.innerHTML = await res.text();
    };

    form.addEventListener('input', run);
    form.addEventListener('change', run);
    form.addEventListener('reset', () => setTimeout(run, 0));
});

document.addEventListener('DOMContentLoaded', () => {
    const cottage = document.querySelector('[name="cottage_id"]');
    const from = document.querySelector('[name="date_from"]');
    const to = document.querySelector('[name="date_to"]');
    const guests = document.querySelector('[name="guests"]');
    const box = document.getElementById('availability-box');

    if (!cottage || !from || !to || !guests || !box) return;

    let timer = null;

    const setBox = (type, text) => {
        box.classList.remove(
            'hidden',
            'bg-emerald-50','text-emerald-700','border-emerald-200',
            'bg-red-50','text-red-700','border-red-200',
            'bg-slate-50','text-slate-700','border-slate-200'
        );

        if (type === 'ok') box.classList.add('bg-emerald-50','text-emerald-700','border-emerald-200');
        else if (type === 'bad') box.classList.add('bg-red-50','text-red-700','border-red-200');
        else box.classList.add('bg-slate-50','text-slate-700','border-slate-200');

        box.textContent = text;
    };

    const check = async () => {
        if (!cottage.value || !from.value || !to.value) {
            box.classList.add('hidden');
            return;
        }

        const url = new URL('/rezervacie/check', window.location.origin);
        url.searchParams.set('cottage_id', cottage.value);
        url.searchParams.set('date_from', from.value);
        url.searchParams.set('date_to', to.value);
        url.searchParams.set('guests', guests.value || '1');

        setBox('info', 'Kontrolujem dostupnosť…');

        const res = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        if (!res.ok) {
            let msg = 'Chyba pri kontrole dostupnosti.';
            try {
                const err = await res.json();
                if (err?.message) msg = err.message;
            } catch (_) {}
            setBox('bad', msg);
            return;
        }

        const data = await res.json();

        if (data.mode === 'whole') {
            if (data.available) setBox('ok', 'Termín je dostupný ✅ (celá chata)');
            else setBox('bad', 'Termín nie je dostupný ❌ (celá chata je už rezervovaná)');
        } else {
            if (data.available) {
                setBox('ok', `Dostupné ✅ Voľné lôžka: ${data.remaining}/${data.capacity}`);
            } else {
                setBox('bad', `Nedostupné ❌ Voľné lôžka: ${data.remaining}/${data.capacity} (požadované: ${data.requested})`);
            }
        }
    };

    const schedule = () => {
        clearTimeout(timer);
        timer = setTimeout(check, 250);
    };

    cottage.addEventListener('change', schedule);
    from.addEventListener('change', schedule);
    to.addEventListener('change', schedule);
    guests.addEventListener('input', schedule);
    guests.addEventListener('change', schedule);
});

