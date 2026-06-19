importScripts("https://js.pusher.com/beams/service-worker.js");

const CACHE_VERSION = "v1";
const CACHE_NAME = `app-cache-${CACHE_VERSION}`;

// Aset statis dasar
const STATIC_ASSETS = [
  "/favicon.ico",
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key)))
    )
  );
  self.clients.claim();
});

self.addEventListener("fetch", (event) => {
  const req = event.request;
  const url = new URL(req.url);

  // Abaikan request selain GET, API, dan Laravel Echo/Reverb agar tidak konflik
  if (req.method !== "GET" || url.pathname.startsWith('/api') || url.pathname.startsWith('/broadcasting')) {
    return;
  }

  // Strategi Cache First untuk aset tampilan
  if (req.destination === 'style' || req.destination === 'script' || req.destination === 'image') {
    event.respondWith(
      caches.match(req).then((cached) => {
        return cached || fetch(req).then((response) => {
          return caches.open(CACHE_NAME).then((cache) => {
            cache.put(req, response.clone());
            return response;
          });
        });
      })
    );
    return;
  }

  // Strategi Network First untuk navigasi halaman utama
  event.respondWith(
    fetch(req)
      .then((response) => {
        const copy = response.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(req, copy));
        return response;
      })
      .catch(() => caches.match(req))
  );
});