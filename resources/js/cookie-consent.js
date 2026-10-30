/*
 * Cookie consent. Strictly necessary cookies only by default; analytics stays off until accepted.
 * The choice is stored for 6 months in `sw_consent` and logged on the server.
 */
const MAX_AGE = 60 * 60 * 24 * 182; // about 6 months

const uuid = () =>
    crypto.randomUUID
        ? crypto.randomUUID()
        : 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (c) => {
              const r = (crypto.getRandomValues(new Uint8Array(1))[0] & 15) >> 0;
              return (c === 'x' ? r : (r & 0x3) | 0x8).toString(16);
          });

function readCookie(version) {
    const raw = document.cookie.split('; ').find((c) => c.startsWith('sw_consent='));
    if (!raw) return null;
    try {
        const data = JSON.parse(decodeURIComponent(raw.slice('sw_consent='.length)));
        return data.v === version ? data : null;
    } catch {
        return null;
    }
}

export function cookieConsent({ version, endpoint, csrf, analyticsId, analyticsSrc, savedLabel }) {
    return {
        banner: false,
        analytics: false,
        init() {
            const saved = readCookie(version);
            this.banner = !saved;
            this.analytics = !!saved?.analytics;
            if (this.analytics) this.loadAnalytics();
            window.addEventListener('open-cookie-settings', () => this.openSettings());
        },
        acceptAll() {
            this.save(true);
        },
        necessaryOnly() {
            this.save(false);
        },
        openSettings() {
            window.dispatchEvent(new CustomEvent('open-dialog', { detail: { name: 'cookies' } }));
        },
        saveSettings() {
            this.save(this.analytics);
            window.dispatchEvent(new CustomEvent('close-dialog', { detail: { name: 'cookies' } }));
        },
        save(analytics) {
            const previous = readCookie(version);
            const data = { v: version, analytics: !!analytics, id: previous?.id ?? uuid(), t: Date.now() };
            const secure = location.protocol === 'https:' ? '; Secure' : '';
            document.cookie = `sw_consent=${encodeURIComponent(JSON.stringify(data))}; Max-Age=${MAX_AGE}; Path=/; SameSite=Lax${secure}`;
            this.analytics = data.analytics;
            this.banner = false;

            fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
                credentials: 'same-origin',
                body: JSON.stringify({ consent_id: data.id, analytics: data.analytics }),
            }).catch(() => {});

            if (data.analytics) this.loadAnalytics();
            window.dispatchEvent(new CustomEvent('toast', { detail: { message: savedLabel } }));
        },
        loadAnalytics() {
            if (!analyticsId || !analyticsSrc || document.querySelector('script[data-sw-analytics]')) return;
            const s = document.createElement('script');
            s.defer = true;
            s.src = analyticsSrc;
            s.dataset.domain = analyticsId;
            s.dataset.swAnalytics = '1';
            document.head.appendChild(s);
        },
    };
}
