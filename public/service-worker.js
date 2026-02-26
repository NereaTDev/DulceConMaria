const CACHE_NAME = 'dulce-con-maria-v1';

const URLS_TO_CACHE = [
  '/',
  '/manifest.webmanifest',
  // Vite/laravel-mix generarán assets en /build o similares; el SW cacheará bajo demanda.
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(URLS_TO_CACHE))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(key => key !== CACHE_NAME)
          .map(key => caches.delete(key))
      )
    )
  );
});

self.addEventListener('fetch', event => {
  // Estrategia: cache-first para GET, fallback a red.
  if (event.request.method !== 'GET') return;

  event.respondWith(
    caches.match(event.request).then(response => {
      if (response) return response;
      return fetch(event.request).then(networkResponse => {
        const cloned = networkResponse.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(event.request, cloned));
        return networkResponse;
      });
    })
  );
});
