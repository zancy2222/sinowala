/*
 * SinoWala service worker.
 * Pages are never stored: they carry people's absences and notes, and a phone may be shared.
 * Only static files are kept (hashed build assets, fonts, icons), plus one offline page that is
 * shown when a page cannot be reached. Bump VERSION to drop everything stored by an older worker.
 */
const VERSION = 'v1';
const CACHE = `sinowala-static-${VERSION}`;
const OFFLINE = '/offline.html';
const PRECACHE = [OFFLINE, '/favicon.svg', '/icon-192.png'];
const MAX_ENTRIES = 80;

const isHashedBuild = (path) => path.startsWith('/build/assets/');
const isStatic = (path) => isHashedBuild(path) || /^\/(fonts|brand)\//.test(path) || /^\/(favicon|icon-|apple-touch-icon)/.test(path);

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(CACHE)
            .then((cache) => cache.addAll(PRECACHE))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.filter((key) => key !== CACHE).map((key) => caches.delete(key))))
            .then(() => self.clients.claim()),
    );
});

async function trim(cache) {
    const keys = await cache.keys();
    const removable = keys.filter((req) => !PRECACHE.includes(new URL(req.url).pathname));
    await Promise.all(removable.slice(0, Math.max(0, removable.length - MAX_ENTRIES)).map((req) => cache.delete(req)));
}

async function fromCache(request, event) {
    const cache = await caches.open(CACHE);
    const hit = await cache.match(request);
    const refresh = () =>
        fetch(request).then((res) => {
            if (res.ok && res.type === 'basic') {
                cache.put(request, res.clone()).then(() => trim(cache));
            }
            return res;
        });
    if (!hit) return refresh();
    // Hashed build files never change, so a hit is final. Fonts and icons refresh in the background.
    if (!isHashedBuild(new URL(request.url).pathname)) event.waitUntil(refresh().catch(() => {}));
    return hit;
}

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;

    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(() => caches.match(OFFLINE)));
        return;
    }

    if (isStatic(url.pathname)) event.respondWith(fromCache(request, event));
});
