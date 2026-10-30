/*
 * Install as an app. Android and desktop Chrome/Edge hand us an install prompt (beforeinstallprompt);
 * iPhone and iPad never do, so there the button opens a dialog with the Share → Add to Home Screen steps.
 */
let deferredPrompt = null;

const isStandalone = () => window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

function detectPlatform() {
    const ua = navigator.userAgent;
    // iPadOS reports itself as a Mac; touch support gives it away.
    const ios = /iphone|ipad|ipod/i.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
    // Facebook, Messenger, Instagram & co. open links in their own browser, which cannot install.
    const inApp = /FBAN|FBAV|FB_IAB|FBIOS|Instagram|Messenger|Line\/|TikTok|musical_ly|Twitter/i.test(ua);
    if (ios) return inApp ? 'ios-inapp' : 'ios';
    if (/android/i.test(ua)) return inApp ? 'android-inapp' : 'android';
    return 'desktop';
}

export function registerServiceWorker() {
    if (!('serviceWorker' in navigator) || !window.isSecureContext) return;
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {
            /* the site works the same without it */
        });
    });
}

const notify = (name, detail) => window.dispatchEvent(new CustomEvent(name, { detail }));

// Listen from the start: the browser may offer the prompt before Alpine is up.
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault(); // our own button asks, instead of the browser's mini-bar
    deferredPrompt = e;
    if (window.Alpine?.store('pwa')) window.Alpine.store('pwa').canPrompt = true;
});
window.addEventListener('appinstalled', () => {
    deferredPrompt = null;
    const store = window.Alpine?.store('pwa');
    if (store) Object.assign(store, { canPrompt: false, installed: true });
    notify('close-dialog', { name: 'install-app' });
    const done = document.querySelector('[data-install-done]')?.dataset.installDone;
    if (done) notify('toast', { message: done });
});

export function registerPwaStore(Alpine) {
    Alpine.store('pwa', {
        installed: isStandalone(),
        canPrompt: deferredPrompt !== null,
        platform: detectPlatform(),
        async install(onThisDevice = false) {
            // A computer gets the phone steps first; it can still install on itself from the dialog.
            if (deferredPrompt && (onThisDevice || this.platform !== 'desktop')) {
                const prompt = deferredPrompt;
                deferredPrompt = null;
                this.canPrompt = false;
                notify('close-dialog', { name: 'install-app' });
                prompt.prompt();
                await prompt.userChoice.catch(() => null);
                return;
            }
            notify('open-dialog', { name: 'install-app' });
        },
    });
}
