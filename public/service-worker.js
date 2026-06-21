// Integrasi wajib untuk Pusher Beams Push Notifications
importScripts("https://js.pusher.com/beams/service-worker.js");

const CACHE_VERSION = "v2"; // Naikkan versi untuk memaksa browser membuang cache v1 yang korup
const CACHE_NAME = `smartbox-beams-cache-${CACHE_VERSION}`;

// Kita HANYA meng-cache favicon statis dasar agar PWA valid memenuhi syarat installable
const STATIC_ASSETS = [
  "/favicon.ico",
];

self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

// Bersihkan total seluruh sisa cache v1 yang lama agar tidak membajak aset .mp3 lagi
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(
        keys
          .filter((key) => key !== CACHE_NAME)
          .map((key) => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

// 🚀 STRATEGI BYPASS TOTAL: Jangan biarkan Service Worker mencampuri aset / routing Laravel
self.addEventListener("fetch", (event) => {
  const req = event.request;
  const url = new URL(req.url);

  // 1. Abaikan mutlak request selain GET (POST, PUT, DELETE wajib langsung ke network)
  // 2. Abaikan jalur API, Laravel Echo, Webhook Pusher, dan rute internal Laravel
  if (
    req.method !== "GET" || 
    url.pathname.startsWith('/api') || 
    url.pathname.startsWith('/broadcasting') ||
    url.pathname.startsWith('/pusher')
  ) {
    return;
  }

  // 3. Hanya kembalikan favicon jika diambil dari cache statis dasar
  if (url.pathname === '/favicon.ico') {
    event.respondWith(
      caches.match(req).then((cached) => cached || fetch(req))
    );
    return;
  }

  // 4. SISA REQUEST LAINNYA (Termasuk file .mp3, assets build, dan halaman Inertia)
  // Wajib dilempar langsung ke Jaringan (Network Only) tanpa disimpan ke cache browser!
  // Ini mencegah error 404 akibat manifest PWA usang.
  return; 
});