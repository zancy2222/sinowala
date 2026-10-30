/*
 * SinoWala front-end behaviour. Alpine ships with Livewire 3; we only register components here.
 * Everything works from the keyboard and degrades to plain links/forms without script.
 */
import { calendarGrid } from './calendar-grid';
import { cookieConsent } from './cookie-consent';
import { registerPwaStore, registerServiceWorker } from './pwa';
import { walaBoard } from './wala-board';

const reduceMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------- Dialogs: native <dialog>, opened by name ---------- */
// The element and trigger live in the closure: inside a method called from a child's @click,
// Alpine's `this.$el` is that child, not the <dialog>, so it must not be used after init().
function swDialog(name) {
    let dialog = null;
    let trigger = null;
    return {
        name,
        init() {
            dialog = this.$el;
            window.addEventListener('open-dialog', (e) => {
                if (e.detail?.name === name) this.open();
            });
            window.addEventListener('close-dialog', (e) => {
                if (e.detail?.name === name) this.close();
            });
            // Click on the backdrop closes; clicks inside the panel do not.
            dialog.addEventListener('click', (e) => {
                if (e.target === dialog) this.close();
            });
            dialog.addEventListener('close', () => {
                const t = trigger;
                trigger = null;
                window.dispatchEvent(new CustomEvent('dialog-closed', { detail: { name } }));
                // Return focus to what opened the dialog (re-found if Livewire replaced it).
                requestAnimationFrame(() => {
                    if (t && document.contains(t)) t.focus();
                    else if (t?.dataset?.date) document.querySelector(`[data-date="${t.dataset.date}"]`)?.focus();
                });
            });
        },
        open() {
            if (!dialog || dialog.open) return;
            trigger = document.activeElement;
            dialog.showModal();
            const first = dialog.querySelector('[autofocus], [data-autofocus]');
            if (first) requestAnimationFrame(() => first.focus());
        },
        close() {
            if (dialog?.open) dialog.close();
        },
    };
}

/* ---------- Toasts (session flash + Livewire `toast` events), announced politely ---------- */
function toasts(initial = []) {
    return {
        items: [],
        init() {
            initial.filter(Boolean).forEach((m) => this.push(m));
            window.addEventListener('toast', (e) => this.push(e.detail?.message ?? e.detail?.[0]?.message));
        },
        push(message) {
            if (!message) return;
            const id = Date.now() + Math.random();
            this.items.push({ id, message });
            setTimeout(() => this.dismiss(id), 6000);
        },
        dismiss(id) {
            this.items = this.items.filter((t) => t.id !== id);
        },
    };
}

/* ---------- Copy to clipboard ---------- */
function copyButton(text, doneLabel) {
    return {
        done: false,
        async copy() {
            try {
                await navigator.clipboard.writeText(text);
            } catch {
                const input = document.createElement('textarea');
                input.value = text;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                input.remove();
            }
            this.done = true;
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: doneLabel } }));
            setTimeout(() => (this.done = false), 2000);
        },
    };
}

/* ---------- Landing page: floating sign-up bar, dismissible and remembered on this device ---------- */
function promoBar(key) {
    const read = () => {
        try {
            return localStorage.getItem(key) === '1';
        } catch {
            return false;
        }
    };
    return {
        shown: false,
        dismissed: read(),
        init() {
            if (this.dismissed) return;
            const hero = document.getElementById('top');
            const check = () => {
                this.shown = !this.dismissed && (!hero || hero.getBoundingClientRect().bottom < 0);
                document.documentElement.classList.toggle('has-promo', this.shown);
            };
            window.addEventListener('scroll', check, { passive: true });
            check();
        },
        dismiss() {
            this.dismissed = true;
            this.shown = false;
            document.documentElement.classList.remove('has-promo');
            try {
                localStorage.setItem(key, '1');
            } catch {
                /* private window: dismissed for this visit only */
            }
            document.getElementById('main')?.focus({ preventScroll: true });
        },
    };
}

/* ---------- Landing page: section index that follows the scroll ---------- */
function pageNav() {
    return {
        active: '#top',
        init() {
            const links = [...this.$el.querySelectorAll('a[href^="#"]')];
            const targets = links.map((a) => document.getElementById(a.getAttribute('href').slice(1))).filter(Boolean);
            const io = new IntersectionObserver(
                (entries) => {
                    entries.forEach((e) => {
                        if (e.isIntersecting) this.active = '#' + e.target.id;
                    });
                },
                { rootMargin: '-45% 0px -50% 0px' },
            );
            targets.forEach((t) => io.observe(t));
        },
    };
}

/* ---------- Keyboard shortcuts inside the app (WCAG 2.1.4: can be turned off) ---------- */
function isTyping(el) {
    if (!el) return false;
    const tag = el.tagName;
    return tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || el.isContentEditable;
}

function initShortcuts() {
    const body = document.body;
    if (body.dataset.shortcuts !== 'on') return;

    document.addEventListener('keydown', (e) => {
        if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.altKey) return;
        if (isTyping(e.target)) return;
        if (document.querySelector('dialog[open]')) return;

        const inGrid = e.target.closest?.('[role="grid"]');
        const L = window.Livewire;
        const key = e.key;
        let handled = true;

        if (key === 'n' || key === 'N') L?.dispatch('open-absence-form');
        else if (key === 't' || key === 'T') L?.dispatch('calendar-today');
        else if (key === 'm' || key === 'M') L?.dispatch('calendar-view', { view: 'month' });
        else if (key === 'w' || key === 'W') L?.dispatch('calendar-view', { view: 'week' });
        else if (key === 'ArrowLeft' && !inGrid) L?.dispatch('calendar-prev');
        else if (key === 'ArrowRight' && !inGrid) L?.dispatch('calendar-next');
        else if (key === '/') {
            const search = document.querySelector('[data-shortcut-search]');
            if (search) search.focus();
            else handled = false;
        } else if (key === '?') window.dispatchEvent(new CustomEvent('open-dialog', { detail: { name: 'shortcuts' } }));
        else handled = false;

        if (handled) e.preventDefault();
    });
}

/* ---------- Register ---------- */
document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;
    Alpine.data('swDialog', swDialog);
    Alpine.data('toasts', toasts);
    Alpine.data('copyButton', copyButton);
    Alpine.data('calendarGrid', calendarGrid);
    Alpine.data('cookieConsent', cookieConsent);
    Alpine.data('walaBoard', walaBoard);
    Alpine.data('promoBar', promoBar);
    Alpine.data('pageNav', pageNav);
    registerPwaStore(Alpine);
});

registerServiceWorker();

document.addEventListener('DOMContentLoaded', () => {
    initShortcuts();
    document.documentElement.classList.toggle('reduce-motion', reduceMotion());
});
